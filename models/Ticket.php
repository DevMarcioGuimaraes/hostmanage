<?php
class Ticket {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Obter todos os tickets
    public function getAllTickets() {
        $this->db->query('SELECT t.*, u.name as user_name FROM tickets t INNER JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Obter tickets por usuário
    public function getTicketsByUser($userId) {
        $this->db->query('SELECT * FROM tickets WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Obter ticket pelo ID
    public function getTicketById($id) {
        $this->db->query('SELECT t.*, u.name as user_name FROM tickets t INNER JOIN users u ON t.user_id = u.id WHERE t.id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Obter respostas do ticket
    public function getTicketReplies($ticketId) {
        $this->db->query('SELECT r.*, u.name as user_name, u.type as user_type FROM ticket_replies r INNER JOIN users u ON r.user_id = u.id WHERE r.ticket_id = :ticket_id ORDER BY r.created_at ASC');
        $this->db->bind(':ticket_id', $ticketId);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Adicionar ticket
    public function addTicket($data) {
        $this->db->query('INSERT INTO tickets (user_id, subject, message, status, created_at) VALUES (:user_id, :subject, :message, :status, :created_at)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':status', 'open');
        $this->db->bind(':created_at', date('Y-m-d H:i:s'));
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Adicionar resposta
    public function addReply($data) {
        $this->db->query('INSERT INTO ticket_replies (ticket_id, user_id, message, created_at) VALUES (:ticket_id, :user_id, :message, :created_at)');
        $this->db->bind(':ticket_id', $data['ticket_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':created_at', date('Y-m-d H:i:s'));
        
        // Executa
        if ($this->db->execute()) {
            // Atualiza o status do ticket
            $this->db->query('UPDATE tickets SET status = :status WHERE id = :id');
            $this->db->bind(':id', $data['ticket_id']);
            $this->db->bind(':status', 'open');
            $this->db->execute();
            
            return true;
        } else {
            return false;
        }
    }
    
    // Fechar ticket
    public function closeTicket($id) {
        $this->db->query('UPDATE tickets SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', 'closed');
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

