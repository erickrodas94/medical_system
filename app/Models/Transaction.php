<?php
namespace App\Models;

class Transaction {
    
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Crea un nuevo registro financiero en el estado de cuenta del paciente
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO clinic_transactions (
                        clinic_ID, person_ID, created_by_user_ID, evolution_ID, 
                        movement_type, currency_iso, original_amount, amount, status, notes
                    ) VALUES (
                        :clinic_id, :person_id, :user_id, :evo_id, 
                        :movement_type, :currency, :original_amount, :amount, :status, :notes
                    )";
                    
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                'clinic_id'       => $data['clinic_id'],
                'person_id'       => $data['person_id'],
                'user_id'         => $data['user_id'],
                'evo_id'          => $data['evo_id'] ?? null,
                'movement_type'   => $data['movement_type'] ?? 'Charge', // Charge o Credit
                'currency'        => $data['currency'] ?? 'GTQ',
                'original_amount' => $data['original_amount'],
                'amount'          => $data['amount'],
                'status'          => $data['status'] ?? 'Pending',
                'notes'           => $data['notes'] ?? ''
            ]);
            
            return ['success' => true, 'transaction_id' => $this->db->lastInsertId()];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}