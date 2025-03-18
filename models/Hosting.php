<?php
class Hosting {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Obter todas as contas de hospedagem
    public function getAllHostings() {
        $this->db->query('SELECT h.*, u.name as user_name, p.name as plan_name FROM hostings h INNER JOIN users u ON h.user_id = u.id INNER JOIN plans p ON h.plan_id = p.id');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Obter contas de hospedagem por usuário
    public function getHostingsByUser($userId) {
        $this->db->query('SELECT h.*, p.name as plan_name FROM hostings h INNER JOIN plans p ON h.plan_id = p.id WHERE h.user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Obter conta de hospedagem pelo ID
    public function getHostingById($id) {
        $this->db->query('SELECT h.*, p.name as plan_name FROM hostings h INNER JOIN plans p ON h.plan_id = p.id WHERE h.id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Adicionar conta de hospedagem
    public function addHosting($data) {
        $this->db->query('INSERT INTO hostings (user_id, plan_id, domain, status, created_at) VALUES (:user_id, :plan_id, :domain, :status, :created_at)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':plan_id', $data['plan_id']);
        $this->db->bind(':domain', $data['domain']);
        $this->db->bind(':status', 'pending');
        $this->db->bind(':created_at', date('Y-m-d H:i:s'));
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Ativar conta de hospedagem
    public function activateHosting($id) {
        $this->db->query('UPDATE hostings SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', 'active');
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Suspender conta de hospedagem
    public function suspendHosting($id) {
        $this->db->query('UPDATE hostings SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', 'suspended');
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Cancelar conta de hospedagem
    public function cancelHosting($id) {
        $this->db->query('UPDATE hostings SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', 'cancelled');
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Migrar de plano
    public function upgradePlan($data) {
        $this->db->query('UPDATE hostings SET plan_id = :plan_id WHERE id = :id');
        $this->db->bind(':id', $data['hosting_id']);
        $this->db->bind(':plan_id', $data['plan_id']);
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

