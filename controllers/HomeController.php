<?php
class HomeController extends Controller {
    public function index() {
        // Se estiver logado, redireciona para o dashboard apropriado
        if ($this->isLoggedIn()) {
            if ($this->isReseller()) {
                header('Location: ' . BASE_URL . '/reseller/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/dashboard');
            }
            exit;
        }
        
        // Carrega a página inicial
        $data = [
            'title' => 'Bem-vindo ao ' . APP_NAME
        ];
        
        $this->view('home/index', $data);
    }
}

