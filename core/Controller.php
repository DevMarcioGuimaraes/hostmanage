<?php
class Controller {
    // Carregar modelo
    public function model($model) {
        require_once 'models/' . $model . '.php';
        return new $model();
    }
    
    // Carregar view
    public function view($view, $data = []) {
        if (file_exists('views/' . $view . '.php')) {
            require_once 'views/' . $view . '.php';
        } else {
            die('View não existe');
        }
    }
    
    // Verificar se o usuário está logado
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Verificar se o usuário é um revendedor
    public function isReseller() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'reseller';
    }
    
    // Verificar se o usuário é um cliente
    public function isClient() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'client';
    }
    
    // Redirecionar se não estiver logado
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/users/login');
            exit;
        }
    }
    
    // Redirecionar se não for revendedor
    public function requireReseller() {
        $this->requireLogin();
        if (!$this->isReseller()) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    
    // Redirecionar se não for cliente
    public function requireClient() {
        $this->requireLogin();
        if (!$this->isClient()) {
            header('Location: ' . BASE_URL . '/reseller/dashboard');
            exit;
        }
    }
}

