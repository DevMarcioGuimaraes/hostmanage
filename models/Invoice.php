<?php
class Invoice {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Obter todas as faturas
    public function getAllInvoices() {
        $this->db->query('SELECT i.*, u.name as user_name FROM invoices i INNER JOIN users u ON i.user_id = u.id ORDER BY i.created_at DESC');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Obter faturas por usuário
    public function getInvoicesByUser($userId) {
        $this->db->query('SELECT * FROM invoices WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Obter fatura pelo ID
    public function getInvoiceById($id) {
        $this->db->query('SELECT * FROM invoices WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Adicionar fatura
    public function addInvoice($data) {
        $this->db->query('INSERT INTO invoices (user_id, amount, description, status, due_date, created_at) VALUES (:user_id, :amount, :description, :status, :due_date, :created_at)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', 'pending');
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':created_at', date('Y-m-d H:i:s'));
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Marcar fatura como paga
    public function markInvoiceAsPaid($id) {
        $this->db->query('UPDATE invoices SET status = :status, paid_at = :paid_at WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', 'paid');
        $this->db->bind(':paid_at', date('Y-m-d H:i:s'));
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Marcar fatura como vencida
    public function markInvoiceAsOverdue($id) {
        $this->db->query('UPDATE invoices SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', 'overdue');
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

