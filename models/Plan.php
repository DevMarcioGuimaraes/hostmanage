<?php
class Plan {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Obter todos os planos
    public function getPlans() {
        $this->db->query('SELECT * FROM plans');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Obter plano pelo ID
    public function getPlanById($id) {
        $this->db->query('SELECT * FROM plans WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Adicionar plano
    public function addPlan($data) {
        $this->db->query('INSERT INTO plans (name, description, price, disk_space, bandwidth, email_accounts, databases, subdomains) VALUES (:name, :description, :price, :disk_space, :bandwidth, :email_accounts, :databases, :subdomains)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':disk_space', $data['disk_space']);
        $this->db->bind(':bandwidth', $data['bandwidth']);
        $this->db->bind(':email_accounts', $data['email_accounts']);
        $this->db->bind(':databases', $data['databases']);
        $this->db->bind(':subdomains', $data['subdomains']);
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Atualizar plano
    public function updatePlan($data) {
        $this->db->query('UPDATE plans SET name = :name, description = :description, price = :price, disk_space = :disk_space, bandwidth = :bandwidth, email_accounts = :email_accounts, databases = :databases, subdomains = :subdomains WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':disk_space', $data['disk_space']);
        $this->db->bind(':bandwidth', $data['bandwidth']);
        $this->db->bind(':email_accounts', $data['email_accounts']);
        $this->db->bind(':databases', $data['databases']);
        $this->db->bind(':subdomains', $data['subdomains']);
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Excluir plano
    public function deletePlan($id) {
        $this->db->query('DELETE FROM plans WHERE id = :id');
        $this->db->bind(':id', $id);
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

