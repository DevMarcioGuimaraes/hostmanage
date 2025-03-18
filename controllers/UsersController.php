<?php
class UsersController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    // Página de login
    public function login() {
        // Verifica se já está logado
        if ($this->isLoggedIn()) {
            if ($this->isReseller()) {
                header('Location: ' . BASE_URL . '/reseller/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/dashboard');
            }
            exit;
        }
        
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
                'title' => 'Login'
            ];
            
            // Valida o email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor, informe o email';
            }
            
            // Valida a senha
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor, informe a senha';
            }
            
            // Verifica se o usuário existe
            if (empty($data['email_err']) && empty($data['password_err'])) {
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    // Cria a sessão
                    $this->createUserSession($loggedInUser);
                } else {
                    // Usuário/senha incorretos
                    $data['password_err'] = 'Email ou senha incorretos';
                    $this->view('users/login', $data);
                }
            } else {
                // Carrega a view com erros
                $this->view('users/login', $data);
            }
        } else {
            // Inicializa o formulário
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
                'title' => 'Login'
            ];
            
            // Carrega a view
            $this->view('users/login', $data);
        }
    }
    
    // Página de registro (apenas para clientes)
    public function register() {
        // Verifica se já está logado
        if ($this->isLoggedIn()) {
            if ($this->isReseller()) {
                header('Location: ' . BASE_URL . '/reseller/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/dashboard');
            }
            exit;
        }
        
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'title' => 'Registro'
            ];
            
            // Valida o nome
            if (empty($data['name'])) {
                $data['name_err'] = 'Por favor, informe o nome';
            }
            
            // Valida o email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor, informe o email';
            } else if ($this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email já está em uso';
            }
            
            // Valida a senha
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor, informe a senha';
            } else if (strlen($data['password']) < 6) {
                $data['password_err'] = 'A senha deve ter pelo menos 6 caracteres';
            }
            
            // Valida a confirmação de senha
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Por favor, confirme a senha';
            } else if ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'As senhas não conferem';
            }
            
            // Verifica se não há erros
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash da senha
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Registra o usuário
                if ($this->userModel->register($data)) {
                    // Redireciona para o login
                    header('Location: ' . BASE_URL . '/users/login');
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('users/register', $data);
            }
        } else {
            // Inicializa o formulário
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'title' => 'Registro'
            ];
            
            // Carrega a view
            $this->view('users/register', $data);
        }
    }
    
    // Cria a sessão do usuário
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_type'] = $user->type;
        
        if ($user->type == 'reseller') {
            header('Location: ' . BASE_URL . '/reseller/dashboard');
        } else {
            header('Location: ' . BASE_URL . '/dashboard');
        }
    }
    
    // Logout
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_type']);
        session_destroy();
        
        header('Location: ' . BASE_URL . '/users/login');
    }
}

