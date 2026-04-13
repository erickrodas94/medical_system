<?php
namespace App\Models;

class Patient {
    
    private $db;

    // 1. Recibimos la base de datos al crear el Router
    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    /**
     * Obtener solo los últimos 50 pacientes (Para la vista inicial)
     */
    public function getLatestByClinic($clinicId, $limit = 50) {
        $sql = "SELECT p.*, cp.ID as patient_id, cp.patient_status,
                       t.first_name as tutor_fname, t.last_name as tutor_lname, 
                       t.primary_cellphone as tutor_phone, pg.relationship as tutor_relation
                FROM clinic_patients cp
                INNER JOIN persons p ON cp.person_ID = p.ID
                LEFT JOIN person_guardians pg ON p.ID = pg.dependent_person_ID
                LEFT JOIN persons t ON pg.responsible_person_ID = t.ID
                WHERE cp.clinic_ID = :clinicId 
                  AND cp.deleted_at IS NULL
                ORDER BY cp.created_at DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':clinicId', $clinicId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Registro rápido de paciente
     * Transacción segura entre persons y clinic_patients
     */
    public function create($data) {
        try {
            $this->db->beginTransaction();
            $clinicId = $_SESSION['user']['clinic_id'];

            // ==========================================
            // PASO 0: VALIDACIÓN TÉCNICA DE IDENTIDAD (Regex)
            // ==========================================
            
            // Validar identidad del Paciente
            if (!empty($data['identity_number']) && !empty($data['identity_type_ID'])) {
                $this->validateIdentityFormat($data['identity_number'], $data['identity_type_ID']);
            }

            // Validar identidad del Tutor (si aplica)
            if (!empty($data['tutor_identity_number']) && !empty($data['tutor_identity_type_ID'])) {
                $this->validateIdentityFormat($data['tutor_identity_number'], $data['tutor_identity_type_ID']);
            }
            // ==========================================
            // PASO 1: ¿LA PERSONA YA EXISTE EN EL SISTEMA GLOBAL?
            // ==========================================
            $sqlCheckPerson = "SELECT ID FROM persons 
                   WHERE (identity_number = :idNum AND identity_number != '') 
                      OR (primary_email = :email AND primary_email != '')
                      OR (primary_cellphone = :phone AND primary_cellphone != '')
                      OR (first_name = :fname AND last_name = :lname AND birth_date = :bdate) 
                   LIMIT 1";
            $stmtCheck = $this->db->prepare($sqlCheckPerson);
            $stmtCheck->execute([
                'idNum' => $data['identity_number'] ?? '',
                'email' => $data['primary_email'] ?? '',
                'phone' => $data['primary_cellphone'] ?? '',
                'fname' => $data['first_name'],
                'lname' => $data['last_name'],
                'bdate' => $data['birth_date']
            ]);
            $existingPerson = $stmtCheck->fetch();

            $personId = null;

            if ($existingPerson) {
                // LA PERSONA EXISTE
                $personId = $existingPerson['ID'];

                // Verificamos si YA está en esta clínica
                $sqlCheckClinic = "SELECT ID FROM clinic_patients 
                                   WHERE clinic_ID = :clinicId AND person_ID = :personId LIMIT 1";
                $stmtCC = $this->db->prepare($sqlCheckClinic);
                $stmtCC->execute(['clinicId' => $clinicId, 'personId' => $personId]);
                $existingPatient = $stmtCC->fetch();

                if ($existingPatient) {
                    // Escenario C: Ya es paciente de esta clínica. Devolvemos su ID y un flag.
                    $this->db->rollBack(); // No hicimos cambios
                    return ['success' => true, 'patient_id' => $existingPatient['ID'], 'is_new' => false];
                }
            } else {
                // Escenario A: Crear Paciente Nuevo
                $sqlPerson = "INSERT INTO persons (
                                identity_type_ID, identity_number, first_name, last_name, 
                                birth_date, gender, primary_email, primary_cellphone, 
                                requires_tutor, country_ID, state_ID, city_ID
                            ) VALUES (
                                :type_id, :id_num, :fname, :lname, 
                                :bdate, :gender, :email, :phone, 
                                :req_tutor, :country_id, :state_id, :city_id
                            )";

                $stmtP = $this->db->prepare($sqlPerson);
                $stmtP->execute([
                    'type_id'    => $data['identity_type_ID'] ?? null, // <--- AQUÍ SE AGREGA
                    'id_num'     => $data['identity_number'] ?? null,  // <--- AQUÍ SE AGREGA
                    'fname'      => $data['first_name'],
                    'lname'      => $data['last_name'],
                    'bdate'      => $data['birth_date'],
                    'gender'     => $data['gender'] ?? null,
                    'email'      => $data['primary_email'] ?? null,
                    'phone'      => $data['primary_cellphone'] ?? null,
                    'req_tutor'  => $data['requires_tutor'],
                    'country_id' => $data['country_ID'],
                    'state_id'   => $data['state_ID'],
                    'city_id'    => $data['city_ID']
                ]);
                $personId = $this->db->lastInsertId();
            }

            // ==========================================
            // PASO 2: VINCULAR A LA CLÍNICA (Escenario A y B)
            // ==========================================
            $sqlClinic = "INSERT INTO clinic_patients (
                            clinic_ID, person_ID, patient_status
                          ) VALUES (
                            :clinicId, :personId, 'Active'
                          )";
            $stmtC = $this->db->prepare($sqlClinic);
            $stmtC->execute([
                'clinicId' => $clinicId,
                'personId' => $personId
            ]);
            $patientId = $this->db->lastInsertId(); // ID de clinic_patients

            // ==========================================
            // PASO 3: LÓGICA DEL TUTOR (Búsqueda Inteligente)
            // ==========================================
            if (isset($data['requires_tutor']) && $data['requires_tutor'] == 1) {
                
                $tutorPersonId = null;

                // 3.1 Buscar si el tutor ya existe por Nombres y Apellidos
                $sqlCheckTutor = "SELECT ID FROM persons 
                                  WHERE first_name = :tfname 
                                  AND last_name = :tlname 
                                  LIMIT 1";
                $stmtCheckTutor = $this->db->prepare($sqlCheckTutor);
                $stmtCheckTutor->execute([
                    'tfname' => trim($data['tutor_first_name']),
                    'tlname' => trim($data['tutor_last_name'])
                ]);
                $existingTutor = $stmtCheckTutor->fetch();

                if ($existingTutor) {
                    // El tutor ya existe en tu DB, usamos su ID (Evita el duplicado)
                    $tutorPersonId = $existingTutor['ID'];
                } else {
                    // El tutor no existe, lo creamos
                    $sqlTutor = "INSERT INTO persons (
                                    identity_type_ID, identity_number, first_name, last_name, 
                                    primary_cellphone, country_ID, state_ID, city_ID
                                ) VALUES (
                                    :type_id, :id_num, :tfname, :tlname, 
                                    :tphone, :country_id, :state_id, :city_id
                                )";

                    $stmtT = $this->db->prepare($sqlTutor);
                    $stmtT->execute([
                        'type_id'    => $data['tutor_identity_type_ID'] ?? null, // <--- TAMBIÉN PARA EL TUTOR
                        'id_num'     => $data['tutor_identity_number'] ?? null,
                        'tfname'     => trim($data['tutor_first_name']),
                        'tlname'     => trim($data['tutor_last_name']),
                        'tphone'     => trim($data['tutor_phone']) ?: null,
                        'country_id' => $data['country_ID'],
                        'state_id'   => $data['state_ID'],
                        'city_id'    => $data['city_ID']
                    ]);
                    $tutorPersonId = $this->db->lastInsertId();
                }

                // 3.2 Verificar que no exista ya el vínculo Guardián-Paciente para no duplicarlo
                $sqlCheckLink = "SELECT ID FROM person_guardians 
                                 WHERE dependent_person_ID = :depId 
                                 AND responsible_person_ID = :respId 
                                 LIMIT 1";
                $stmtCheckLink = $this->db->prepare($sqlCheckLink);
                $stmtCheckLink->execute([
                    'depId' => $personId, 
                    'respId' => $tutorPersonId
                ]);
                
                // Si el vínculo no existe, lo creamos
                if (!$stmtCheckLink->fetch()) {
                    $sqlLink = "INSERT INTO person_guardians (
                                    dependent_person_ID, responsible_person_ID, relationship
                                ) VALUES (:depId, :respId, :relation)";
                    $stmtL = $this->db->prepare($sqlLink);
                    $stmtL->execute([
                        'depId'    => $personId, 
                        'respId'   => $tutorPersonId, 
                        'relation' => $data['tutor_relationship']
                    ]);
                }
            }

            $this->db->commit();
            return ['success' => true, 'patient_id' => $patientId, 'is_new' => true];

        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Método privado auxiliar para no repetir código de validación
     */
    private function validateIdentityFormat($number, $typeId) {
        $sqlType = "SELECT validation_regex, label_key FROM identity_types WHERE ID = :typeId";
        $stmtT = $this->db->prepare($sqlType);
        $stmtT->execute(['typeId' => $typeId]);
        $type = $stmtT->fetch();

        if ($type && !empty($type['validation_regex'])) {
            // IMPORTANTE: El trim elimina espacios accidentales
            $cleanNumber = trim($number);
            $pattern = '/' . $type['validation_regex'] . '/'; 
            
            if (!preg_match($pattern, $cleanNumber)) {
                // Este throw detiene todo y manda el mensaje al catch
                throw new \Exception("Formato inválido para " . __($type['label_key']));
            }
        }
    }

    /**
     * Búsqueda en tiempo real (Server-Side)
     */
    public function searchPatients($clinicId, $term, $limit = 50) {
        $searchTerm = "%{$term}%";
        $sql = "SELECT p.*, cp.ID as patient_id, cp.patient_status,
                       t.first_name as tutor_fname, t.last_name as tutor_lname, 
                       t.primary_cellphone as tutor_phone, pg.relationship as tutor_relation
                FROM clinic_patients cp
                INNER JOIN persons p ON cp.person_ID = p.ID
                LEFT JOIN person_guardians pg ON p.ID = pg.dependent_person_ID
                LEFT JOIN persons t ON pg.responsible_person_ID = t.ID
                WHERE cp.clinic_ID = :clinicId 
                  AND cp.deleted_at IS NULL
                  AND (
                      CONCAT(p.first_name, ' ', p.last_name) LIKE :term1 
                      OR p.primary_email LIKE :term2 
                      OR p.primary_cellphone LIKE :term3 
                      OR p.uuid LIKE :term4
                  )
                ORDER BY p.first_name ASC LIMIT :limit";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':clinicId', $clinicId, \PDO::PARAM_INT);
        $stmt->bindValue(':term1', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':term2', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':term3', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':term4', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}