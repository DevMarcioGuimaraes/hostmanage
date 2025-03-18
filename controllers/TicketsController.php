<?php
class TicketsController extends Controller {
    private $ticketModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->requireClient();
        
        $this->ticketModel = $this->model('Ticket');
    }
    
    // Lista de tickets
    public function index() {
        // Obtém os tickets do usuário
        $tickets = $this->ticketModel->getTicketsByUser($_SESSION['user_id']);
        
        $data = [
            'title' => 'Meus Tickets',
            'tickets' => $tickets
        ];
        
        $this->view('tickets/index', $data);
    }
    
    // Criar ticket
    public function create() {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'user_id' => $_SESSION['user_id'],
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'subject_err' => '',
                'message_err' => '',
                'title' => 'Criar Ticket'
            ];
            
            // Valida o assunto
            if (empty($data['subject'])) {
                $data['subject_err'] = 'Por favor, informe o assunto';
            }
            
            // Valida a mensagem
            if (empty($data['message'])) {
                $data['message_err'] = 'Por favor, informe a mensagem';
            }
            
            // Verifica se não há erros
            if (empty($data['subject_err']) && empty($data['message_err'])) {
                // Cria o ticket
                if ($this->ticketModel->addTicket($data)) {
                    // Redireciona para a lista de tickets
                    header('Location: ' . BASE_URL . '/tickets');
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('tickets/create', $data);
            }
        } else {
            // Inicializa o formulário
            $data = [
                'subject' => '',
                'message' => '',
                'subject_err' => '',
                'message_err' => '',
                'title' => 'Criar Ticket'
            ];
            
            $this->view('tickets/create', $data);
        }
    }
    
    // Visualizar ticket
    public function view($id) {
        // Obtém o ticket
        $ticket = $this->ticketModel->getTicketById($id);
        
        // Verifica se o ticket pertence ao usuário
        if ($ticket->user_id != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/tickets');
            exit;
        }
        
        // Obtém as respostas do ticket
        $replies = $this->ticketModel->getTicketReplies($id);
        
        $data = [
            'title' => 'Visualizar Ticket',
            'ticket' => $ticket,
            'replies' => $replies
        ];
        
        $this->view('tickets/view', $data);
    }
    
    // Responder ticket
    public function reply($id) {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'ticket_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'message' => trim($_POST['message']),
                'message_err' => '',
                'title' => 'Responder Ticket'
            ];
            
            // Valida a mensagem
            if (empty($data['message'])) {
                $data['message_err'] = 'Por favor, informe a mensagem';
            }
            
            // Verifica se não há erros
            if (empty($data['message_err'])) {
                // Adiciona a resposta
                if ($this->ticketModel->addReply($data)) {
                    // Redireciona para a visualização do ticket
                    header('Location: ' . BASE_URL . '/tickets/view/' . $id);
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('tickets/reply', $data);
            }
        } else {
            // Obtém o ticket
            $ticket = $this->ticketModel->getTicketById($id);
            
            // Verifica se o ticket pertence ao usuário
            if ($ticket->user_id != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/tickets');
                exit;
            }
            
            $data = [
                'title' => 'Responder Ticket',
                'ticket' => $ticket,
                'message' => '',
                'message_err' => ''
            ];
            
            $this->view('tickets/reply', $data);
        }
    }
}

