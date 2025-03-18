<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Registrar usuário
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, type) VALUES (:name, :email, :password, :type)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':type', 'client');
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Login
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if ($row) {
            $hashed_password = $row->password;
            
            if (password_verify($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Encontrar usuário pelo email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        // Verifica se encontrou
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Obter usuário pelo ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Obter todos os clientes
    public function getClients() {
        $this->db->query('SELECT * FROM users WHERE type = :type');
        $this->db->bind(':type', 'client');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Adicionar cliente
    public function addClient($data) {
        $this->db->query('INSERT INTO users (name, email, password, type) VALUES (:name, :email, :password, :type)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':type', 'client');
        
        // Executa
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

