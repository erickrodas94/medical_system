<?php
namespace App\Models;

class Patient {
    
    private $db;

    // =====================================================
    // CONSULTAS A LAS DIFERENTES TABLAS DE LA BASE DE DATOS
    // =====================================================


    // 1. Recibimos la base de datos al crear el Router
    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    // 2. Tabala unificada de consulta del paciente.
    public function getUnifiedTimeline($personId, $caseId = null) {
        $params = [':person_id' => $personId];
        $caseFilter = $caseId ? " AND E.case_ID = :case_id" : "";
        if ($caseId) $params[':case_id'] = $caseId;

        $sql = "
            /* 1. Evoluciones */
            SELECT 'evolution' as record_type, E.ID as record_id, E.created_at, E.evolution_notes as main_content, E.physical_exam_notes as sub_content,
                    CONCAT(U.first_name, ' ', U.last_name) as doctor_full_name, U.ID as doctor_id, E.case_ID as extra_data
            FROM clinic_evolutions E JOIN users U ON E.doctor_ID = U.ID
            WHERE E.person_ID = :person_id $caseFilter

            UNION ALL

            /* 2. Signos Vitales (Dinámico y Completo) */
            SELECT 'vitals' as record_type, T.ID as record_id, T.taken_at as created_at, CONCAT_WS('|',
                    IF(T.systolic_bp IS NOT NULL AND T.diastolic_bp IS NOT NULL, CONCAT('P. Arterial:', T.systolic_bp, '/', T.diastolic_bp, ' mmHg'), NULL),
                    IF(T.heart_rate_bpm IS NOT NULL, CONCAT('F. Cardíaca:', T.heart_rate_bpm, ' lpm'), NULL),
                    IF(T.oxygen_saturation_pct IS NOT NULL, CONCAT('SpO2:', T.oxygen_saturation_pct, '%'), NULL),
                    IF(T.temperature_value IS NOT NULL, CONCAT('Temp:', T.temperature_value, '°', T.temperature_unit), NULL),
                    IF(T.weight_value IS NOT NULL, CONCAT('Peso:', T.weight_value, ' ', T.weight_unit), NULL),
                    IF(T.height_value IS NOT NULL, CONCAT('Estatura:', T.height_value, ' ', T.height_unit), NULL),
                    IF(T.bmi_calculated IS NOT NULL, CONCAT('IMC:', T.bmi_calculated), NULL),
                    IF(T.respiratory_rate_rpm IS NOT NULL, CONCAT('F. Resp:', T.respiratory_rate_rpm, ' rpm'), NULL),
                    IF(T.glucose_value IS NOT NULL, CONCAT('Glucosa:', T.glucose_value, ' mg/dL'), NULL),
                    IF(T.head_circumference IS NOT NULL, CONCAT('P. Cefálico:', T.head_circumference, ' ', T.head_circumference_unit), NULL)
                ) as main_content, NULL as sub_content, CONCAT(U.first_name, ' ', U.last_name) as doctor_full_name, U.ID as doctor_id, NULL as extra_data
            FROM clinic_triage T JOIN users U ON T.created_by_user_ID = U.ID
            WHERE T.person_ID = :person_id

            UNION ALL

            /* 3. Recetas (Medicamentos como contenido PRINCIPAL) */
            SELECT 'prescription' as record_type, P.ID as record_id, P.created_at, 
                /* Extraemos todos los detalles separados por :: (columnas) y || (filas) */
                (SELECT GROUP_CONCAT(
                    CONCAT(
                        IFNULL(medication_name, ''), '::', 
                        IFNULL(dosage, ''), '::', 
                        IFNULL(frequency, ''), '::', 
                        IFNULL(duration, ''), '::', 
                        IFNULL(total_quantity, '')
                    ) SEPARATOR '||'
                ) 
                FROM prescription_details 
                WHERE prescription_ID = P.ID) as main_content,
                /* Las instrucciones pasan a ser el contenido secundario */
                P.general_instructions as sub_content, CONCAT(U.first_name, ' ', U.last_name) as doctor_full_name, U.ID as doctor_id, NULL as extra_data
            FROM evolution_prescriptions P JOIN clinic_evolutions E ON P.evolution_ID = E.ID JOIN users U ON P.doctor_user_ID = U.ID
            WHERE E.person_ID = :person_id $caseFilter

            ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
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
     * Busca una persona en la DB. Si no existe, la crea y devuelve su ID.
     */
    private function findOrCreatePerson($pData) {
        $existing = false;

        // 1. Búsqueda exacta: Por Documento de Identidad (DPI, Pasaporte, etc.)
        if (!empty($pData['identity_number']) && !empty($pData['identity_type_ID'])) {
            $sql = "SELECT ID FROM persons WHERE identity_number = :doc AND identity_type_ID = :type LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['doc' => trim($pData['identity_number']), 'type' => $pData['identity_type_ID']]);
            $existing = $stmt->fetch();
        }

        // 2. Búsqueda secundaria: Por Email o Teléfono
        if (!$existing && (!empty($pData['primary_email']) || !empty($pData['primary_cellphone']))) {
            $sql = "SELECT ID FROM persons WHERE 
                    (primary_email = :email AND primary_email != '') OR 
                    (primary_cellphone = :phone AND primary_cellphone != '') LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'email' => $pData['primary_email'] ?? '',
                'phone' => $pData['primary_cellphone'] ?? ''
            ]);
            $existing = $stmt->fetch();
        }

        // 3. Búsqueda terciaria: Por Nombres y Apellidos
        if (!$existing && !empty($pData['first_name']) && !empty($pData['last_name'])) {
            $sql = "SELECT ID FROM persons WHERE first_name = :fname AND last_name = :lname LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'fname' => trim($pData['first_name']),
                'lname' => trim($pData['last_name'])
            ]);
            $existing = $stmt->fetch();
        }

        // Si la persona ya existe, retornamos su ID y no duplicamos
        if ($existing) {
            return $existing['ID'];
        }

        // Si pasó todos los filtros y no existe, hacemos el INSERT
        $sqlInsert = "INSERT INTO persons (
                        identity_type_ID, identity_number, first_name, last_name, 
                        birth_date, gender, primary_email, primary_cellphone, 
                        requires_tutor, country_ID, state_ID
                    ) VALUES (
                        :type_id, :id_num, :fname, :lname, 
                        :bdate, :gender, :email, :phone, 
                        :req_tutor, :country_id, :state_id
                    )";
        $stmtInsert = $this->db->prepare($sqlInsert);
        $stmtInsert->execute([
            'type_id'    => $pData['identity_type_ID'] ?? null,
            'id_num'     => !empty($pData['identity_number']) ? trim($pData['identity_number']) : null,
            'fname'      => trim($pData['first_name']),
            'lname'      => trim($pData['last_name']),
            'bdate'      => $pData['birth_date'] ?? null,
            'gender'     => $pData['gender'] ?? null,
            'email'      => !empty($pData['primary_email']) ? trim($pData['primary_email']) : null,
            'phone'      => !empty($pData['primary_cellphone']) ? trim($pData['primary_cellphone']) : null,
            'req_tutor'  => $pData['requires_tutor'] ?? 0,
            'country_id' => $pData['country_ID'] ?? null,
            'state_id'   => $pData['state_ID'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    /*
    * Obtiene el historial de crecimiento de un paciente pediátrico
    */
    public function getPediatricGrowthHistory($personId) {
        $sql = "SELECT 
                    T.taken_at,
                    T.weight_value, T.weight_unit,
                    T.height_value, T.height_unit,
                    T.head_circumference, T.head_circumference_unit,
                    T.bmi_calculated,
                    P.birth_date,
                    P.gender,
                    /* Cálculo de meses entre nacimiento y toma de datos */
                    TIMESTAMPDIFF(MONTH, P.birth_date, T.taken_at) as age_months
                FROM clinic_triage T
                JOIN persons P ON T.person_ID = P.ID
                WHERE T.person_ID = :person_id
                ORDER BY T.taken_at ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':person_id' => $personId]);
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
            // PREPARACIÓN DE VARIABLES (Fallback a ID 1)
            // Si no vienen en el form, usamos 1 por defecto para no violar el NOT NULL
            // ==========================================
            $stateId = !empty($data['state_ID']) ? $data['state_ID'] : 1;

            // ==========================================
            // PASO 0: VALIDACIÓN TÉCNICA DE IDENTIDAD (Regex)
            // ==========================================
            if (!empty($data['identity_number']) && !empty($data['identity_type_ID'])) {
                $this->validateIdentityFormat($data['identity_number'], $data['identity_type_ID']);
            }
            if (!empty($data['tutor_identity_number']) && !empty($data['tutor_identity_type_ID'])) {
                $this->validateIdentityFormat($data['tutor_identity_number'], $data['tutor_identity_type_ID']);
            }

            // ==========================================
            // PASO 1: CREAR O BUSCAR AL PACIENTE
            // ==========================================
            $personId = $this->findOrCreatePerson([
                'identity_type_ID'  => $data['identity_type_ID'] ?? null,
                'identity_number'   => $data['identity_number'] ?? null,
                'first_name'        => $data['first_name'],
                'last_name'         => $data['last_name'],
                'primary_email'     => $data['primary_email'] ?? null,
                'primary_cellphone' => $data['primary_cellphone'] ?? null,
                'birth_date'        => $data['birth_date'],
                'gender'            => $data['gender'] ?? null,
                'requires_tutor'    => $data['requires_tutor'],
                'country_ID'        => $data['country_ID'],
                'state_ID'          => $stateId
            ]);

            // Verificar si YA está en esta clínica
            $sqlCheckClinic = "SELECT ID FROM clinic_patients WHERE clinic_ID = :clinicId AND person_ID = :personId LIMIT 1";
            $stmtCC = $this->db->prepare($sqlCheckClinic);
            $stmtCC->execute(['clinicId' => $clinicId, 'personId' => $personId]);
            $existingPatient = $stmtCC->fetch();

            if ($existingPatient) {
                // Ya es paciente de esta clínica. 
                $patientId = $existingPatient['ID'];
                $assignedUserId = !empty($data['assigned_doctor_ID']) ? $data['assigned_doctor_ID'] : $_SESSION['user']['id'];
                
                // Verificamos si este doctor específico ya tiene acceso a este paciente
                $sqlCheckAccess = "SELECT ID FROM clinic_patient_users 
                                   WHERE clinic_patient_ID = :cpId AND user_ID = :userId 
                                   AND deleted_at IS NULL LIMIT 1";
                $stmtAccess = $this->db->prepare($sqlCheckAccess);
                $stmtAccess->execute(['cpId' => $patientId, 'userId' => $assignedUserId]);
                
                if (!$stmtAccess->fetch()) {
                    // Si el doctor no tenía acceso, se lo damos en este momento
                    $sqlAddAccess = "INSERT INTO clinic_patient_users (clinic_patient_ID, user_ID, assignment_role) 
                                     VALUES (:cpId, :userId, 'Primary')";
                    $this->db->prepare($sqlAddAccess)->execute(['cpId' => $patientId, 'userId' => $assignedUserId]);
                    
                    $this->db->commit(); // Guardamos el nuevo acceso
                } else {
                    $this->db->rollBack(); // Ya tenía acceso, no hicimos cambios
                }

                return ['success' => true, 'patient_id' => $patientId, 'is_new' => false];
            }

            // ==========================================
            // PASO 2: VINCULAR A LA CLÍNICA
            // ==========================================
            $sqlClinic = "INSERT INTO clinic_patients (clinic_ID, person_ID, patient_status) VALUES (:clinicId, :personId, 'Active')";
            $stmtC = $this->db->prepare($sqlClinic);
            $stmtC->execute(['clinicId' => $clinicId, 'personId' => $personId]);
            $patientId = $this->db->lastInsertId();

            // ==========================================
            // PASO 2.5: ASIGNAR EL PACIENTE AL USUARIO
            // ==========================================
            $assignedUserId = !empty($data['assigned_doctor_ID']) ? $data['assigned_doctor_ID'] : $_SESSION['user']['id'];

            $sqlAssign = "INSERT INTO clinic_patient_users (clinic_patient_ID, user_ID, assignment_role) 
                          VALUES (:cpId, :userId, 'Primary')";
            $stmtAssign = $this->db->prepare($sqlAssign);
            $stmtAssign->execute([
                'cpId' => $patientId,
                'userId' => $assignedUserId
            ]);

            /// ==========================================
            // PASO 3: LÓGICA DEL TUTOR
            // ==========================================
            if (isset($data['requires_tutor']) && $data['requires_tutor'] == 1) {
                
                // Buscamos o creamos al tutor usando su PROPIO país y documento
                $tutorPersonId = $this->findOrCreatePerson([
                    'identity_type_ID'  => $data['tutor_identity_type_ID'] ?? null,
                    'identity_number'   => $data['tutor_identity_number'] ?? null,
                    'first_name'        => $data['tutor_first_name'],
                    'last_name'         => $data['tutor_last_name'],
                    'primary_cellphone' => $data['tutor_phone'] ?? null,
                    
                    'country_ID'        => $data['tutor_country_ID'], 
                    'state_ID'          => $stateId
                ]);

                // Verificar que no exista ya el vínculo Guardián-Paciente
                $sqlCheckLink = "SELECT ID FROM person_guardians WHERE dependent_person_ID = :depId AND responsible_person_ID = :respId LIMIT 1";
                $stmtCheckLink = $this->db->prepare($sqlCheckLink);
                $stmtCheckLink->execute(['depId' => $personId, 'respId' => $tutorPersonId]);
                
                if (!$stmtCheckLink->fetch()) {
                    $sqlLink = "INSERT INTO person_guardians (dependent_person_ID, responsible_person_ID, relationship) VALUES (:depId, :respId, :relation)";
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
     * Actualiza los datos maestros de una persona (Paciente)
     */
    public function update($data) {
        try {
            $sql = "UPDATE persons 
                    SET first_name = :fname, 
                        last_name = :lname, 
                        gender = :gender,
                        marital_status = :marital,
                        ethnicity = :ethnicity,
                        religion = :religion,
                        primary_cellphone = :phone1,
                        landline_phone = :landline,
                        primary_email = :email,
                        country_ID = :country_id,
                        state_ID = :state_id,
                        postal_code = :postal,
                        address_line1 = :address1,
                        address_line2 = :address2,
                        blood_type = :blood_type, 
                        emergency_contact_name = :e_name,
                        emergency_contact_phone = :e_phone,
                        emergency_contact_relationship = :e_rel,
                        critical_medical_alert = :alert
                    WHERE ID = :person_id";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'fname'      => trim($data['first_name'] ?? ''),
                'lname'      => trim($data['last_name'] ?? ''),
                'gender'     => $data['gender'] ?? null,
                'marital'    => $data['marital_status'] ?? 'Single',
                'ethnicity'  => $data['ethnicity'] ?? null,
                'religion'   => $data['religion'] ?? null,
                'phone1'     => trim($data['primary_cellphone'] ?? ''),
                'landline'   => trim($data['landline_phone'] ?? ''),
                'email'      => trim($data['primary_email'] ?? ''),
                'country_id' => !empty($data['country_ID']) ? $data['country_ID'] : null,
                'state_id'   => !empty($data['state_ID']) ? $data['state_ID'] : null,
                'postal'     => trim($data['postal_code'] ?? ''),
                'address1'   => trim($data['address_line1'] ?? ''),
                'address2'   => trim($data['address_line2'] ?? ''),
                'blood_type' => $data['blood_type'] ?? 'Unknown',
                'e_name'     => trim($data['emergency_contact_name'] ?? ''),
                'e_phone'    => trim($data['emergency_contact_phone'] ?? ''),
                'e_rel'      => $data['emergency_contact_relationship'] ?: null,
                'alert'      => trim($data['critical_medical_alert'] ?? ''),
                'person_id'  => $data['person_id']
            ]);
            return ['success' => true];
        } catch (\Exception $e) {
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

    /**
     *  Obtiene los pacientes de cada usuario
     */
    public function getPatientsForUser($clinicId, $userId, $isAdmin, $limit = 50) {
        if ($isAdmin) {
            // El Admin ve todo (Tu consulta actual)
            $sql = "SELECT p.*, cp.ID as patient_id, cp.patient_status,
                        t.first_name as tutor_fname, t.last_name as tutor_lname, 
                        t.primary_cellphone as tutor_phone, pg.relationship as tutor_relation
                    FROM clinic_patients cp
                    INNER JOIN persons p ON cp.person_ID = p.ID
                    LEFT JOIN person_guardians pg ON p.ID = pg.dependent_person_ID
                    LEFT JOIN persons t ON pg.responsible_person_ID = t.ID
                    WHERE cp.clinic_ID = :clinicId AND cp.deleted_at IS NULL
                    ORDER BY cp.created_at DESC LIMIT :limit";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':clinicId', $clinicId, \PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            
        } else {
            // El Doctor solo ve los suyos (Gracias al INNER JOIN)
            $sql = "SELECT p.*, cp.ID as patient_id, cp.patient_status,
                        t.first_name as tutor_fname, t.last_name as tutor_lname, 
                        t.primary_cellphone as tutor_phone, pg.relationship as tutor_relation
                    FROM clinic_patients cp
                    INNER JOIN persons p ON cp.person_ID = p.ID
                    INNER JOIN clinic_patient_users cpu ON cp.ID = cpu.clinic_patient_ID
                    LEFT JOIN person_guardians pg ON p.ID = pg.dependent_person_ID
                    LEFT JOIN persons t ON pg.responsible_person_ID = t.ID
                    WHERE cp.clinic_ID = :clinicId 
                    AND cpu.user_ID = :userId 
                    AND cp.deleted_at IS NULL
                    ORDER BY cp.created_at DESC LIMIT :limit";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':clinicId', $clinicId, \PDO::PARAM_INT);
            $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
        }
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el expediente de un paciente, respetando los permisos del usuario
     */
    public function getByIdAndUser($patientId, $clinicId, $userId, $canSeeAll) {
        $sql = "SELECT p.*, cp.ID as patient_id, cp.patient_status, cp.created_at as registered_at,
                       it.label_key as identity_type_label,
                       t.first_name as tutor_fname, t.last_name as tutor_lname, 
                       t.primary_cellphone as tutor_phone, pg.relationship as tutor_relation,
                       tit.label_key as tutor_identity_type_label,
                       t.identity_number as tutor_identity,
                       co.iso_code as country_name,
                       st.name as state_name
                FROM clinic_patients cp
                INNER JOIN persons p ON cp.person_ID = p.ID
                LEFT JOIN identity_types it ON p.identity_type_ID = it.ID
                LEFT JOIN person_guardians pg ON p.ID = pg.dependent_person_ID
                LEFT JOIN persons t ON pg.responsible_person_ID = t.ID 
                LEFT JOIN identity_types tit ON t.identity_type_ID = tit.ID
                LEFT JOIN countries co ON p.country_ID = co.ID
                LEFT JOIN states st ON p.state_ID = st.ID";
        
        // Si no es admin, obligamos a que exista un registro en clinic_patient_users
        if (!$canSeeAll) {
            $sql .= " INNER JOIN clinic_patient_users cpu ON cp.ID = cpu.clinic_patient_ID ";
        }
        
        $sql .= " WHERE cp.ID = :patientId AND cp.clinic_ID = :clinicId AND cp.deleted_at IS NULL ";
        
        // Si no es admin, filtramos por su ID
        if (!$canSeeAll) {
            $sql .= " AND cpu.user_ID = :userId AND cpu.deleted_at IS NULL ";
        }
        
        $sql .= " LIMIT 1";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':patientId', $patientId, \PDO::PARAM_INT);
        $stmt->bindValue(':clinicId', $clinicId, \PDO::PARAM_INT);
        if (!$canSeeAll) {
            $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        }
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}