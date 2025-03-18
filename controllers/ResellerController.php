<?php
class ResellerController extends Controller {
    private $planModel;
    private $userModel;
    private $hostingModel;
    private $invoiceModel;
    private $ticketModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->requireReseller();
        
        $this->planModel = $this->model('Plan');
        $this->userModel = $this->model('User');
        $this->hostingModel = $this->model('Hosting');
        $this->invoiceModel = $this->model('Invoice');
        $this->ticketModel = $this->model('Ticket');
    }
    
    // Dashboard do revendedor
    public function dashboard() {
        // Obtém os dados do dashboard
        $plans = $this->planModel->getPlans();
        $clients = $this->userModel->getClients();
        $hostings = $this->hostingModel->getAllHostings();
        $invoices = $this->invoiceModel->getAllInvoices();
        $tickets = $this->ticketModel->getAllTickets();
        
        // Estatísticas
        $totalClients = count($clients);
        $totalHostings = count($hostings);
        $totalInvoices = count($invoices);
        $totalTickets = count($tickets);
        
        $pendingInvoices = 0;
        $paidInvoices = 0;
        $overdueInvoices = 0;
        
        foreach ($invoices as $invoice) {
            if ($invoice->status == 'pending') {
                $pendingInvoices++;
            } else if ($invoice->status == 'paid') {
                $paidInvoices++;
            } else if ($invoice->status == 'overdue') {
                $overdueInvoices++;
            }
        }
        
        $data = [
            'title' => 'Dashboard do Revendedor',
            'plans' => $plans,
            'clients' => $clients,
            'hostings' => $hostings,
            'invoices' => $invoices,
            'tickets' => $tickets,
            'totalClients' => $totalClients,
            'totalHostings' => $totalHostings,
            'totalInvoices' => $totalInvoices,
            'totalTickets' => $totalTickets,
            'pendingInvoices' => $pendingInvoices,
            'paidInvoices' => $paidInvoices,
            'overdueInvoices' => $overdueInvoices
        ];
        
        $this->view('reseller/dashboard', $data);
    }
    
    // Gerenciamento de planos
    public function plans() {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => floatval($_POST['price']),
                'disk_space' => intval($_POST['disk_space']),
                'bandwidth' => intval($_POST['bandwidth']),
                'email_accounts' => intval($_POST['email_accounts']),
                'databases' => intval($_POST['databases']),
                'subdomains' => intval($_POST['subdomains']),
                'name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'disk_space_err' => '',
                'bandwidth_err' => '',
                'email_accounts_err' => '',
                'databases_err' => '',
                'subdomains_err' => '',
                'title' => 'Gerenciar Planos'
            ];
            
            // Valida o nome
            if (empty($data['name'])) {
                $data['name_err'] = 'Por favor, informe o nome do plano';
            }
            
            // Valida a descrição
            if (empty($data['description'])) {
                $data['description_err'] = 'Por favor, informe a descrição do plano';
            }
            
            // Valida o preço
            if (empty($data['price'])) {
                $data['price_err'] = 'Por favor, informe o preço do plano';
            }
            
            // Verifica se não há erros
            if (empty($data['name_err']) && empty($data['description_err']) && empty($data['price_err'])) {
                // Adiciona o plano
                if ($this->planModel->addPlan($data)) {
                    // Redireciona para a lista de planos
                    header('Location: ' . BASE_URL . '/reseller/plans');
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('reseller/plans', $data);
            }
        } else {
            // Obtém os planos
            $plans = $this->planModel->getPlans();
            
            $data = [
                'title' => 'Gerenciar Planos',
                'plans' => $plans,
                'name' => '',
                'description' => '',
                'price' => '',
                'disk_space' => '',
                'bandwidth' => '',
                'email_accounts' => '',
                'databases' => '',
                'subdomains' => '',
                'name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'disk_space_err' => '',
                'bandwidth_err' => '',
                'email_accounts_err' => '',
                'databases_err' => '',
                'subdomains_err' => ''
            ];
            
            $this->view('reseller/plans', $data);
        }
    }
    
    // Gerenciamento de clientes
    public function clients() {
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
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'title' => 'Gerenciar Clientes'
            ];
            
            // Valida o nome
            if (empty($data['name'])) {
                $data['name_err'] = 'Por favor, informe o nome do cliente';
            }
            
            // Valida o email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor, informe o email do cliente';
            } else if ($this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email já está em uso';
            }
            
            // Valida a senha
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor, informe a senha do cliente';
            } else if (strlen($data['password']) < 6) {
                $data['password_err'] = 'A senha deve ter pelo menos 6 caracteres';
            }
            
            // Verifica se não há erros
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err'])) {
                // Hash da senha
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Adiciona o cliente
                if ($this->userModel->addClient($data)) {
                    // Redireciona para a lista de clientes
                    header('Location: ' . BASE_URL . '/reseller/clients');
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('reseller/clients', $data);
            }
        } else {
            // Obtém os clientes
            $clients = $this->userModel->getClients();
            
            $data = [
                'title' => 'Gerenciar Clientes',
                'clients' => $clients,
                'name' => '',
                'email' => '',
                'password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            $this->view('reseller/clients', $data);
        }
    }
    
    // Gerenciamento de contas de hospedagem
    public function hostings() {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'user_id' => intval($_POST['user_id']),
                'plan_id' => intval($_POST['plan_id']),
                'domain' => trim($_POST['domain']),
                'user_id_err' => '',
                'plan_id_err' => '',
                'domain_err' => '',
                'title' => 'Gerenciar Contas de Hospedagem'
            ];
            
            // Valida o usuário
            if (empty($data['user_id'])) {
                $data['user_id_err'] = 'Por favor, selecione um cliente';
            }
            
            // Valida o plano
            if (empty($data['plan_id'])) {
                $data['plan_id_err'] = 'Por favor, selecione um plano';
            }
            
            // Valida o domínio
            if (empty($data['domain'])) {
                $data['domain_err'] = 'Por favor, informe o domínio';
            }
            
            // Verifica se não há erros
            if (empty($data['user_id_err']) && empty($data['plan_id_err']) && empty($data['domain_err'])) {
                // Adiciona a conta de hospedagem
                if ($this->hostingModel->addHosting($data)) {
                    // Redireciona para a lista de contas de hospedagem
                    header('Location: ' . BASE_URL . '/reseller/hostings');
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('reseller/hostings', $data);
            }
        } else {
            // Obtém as contas de hospedagem
            $hostings = $this->hostingModel->getAllHostings();
            
            // Obtém os clientes
            $clients = $this->userModel->getClients();
            
            // Obtém os planos
            $plans = $this->planModel->getPlans();
            
            $data = [
                'title' => 'Gerenciar Contas de Hospedagem',
                'hostings' => $hostings,
                'clients' => $clients,
                'plans' => $plans,
                'user_id' => '',
                'plan_id' => '',
                'domain' => '',
                'user_id_err' => '',
                'plan_id_err' => '',
                'domain_err' => ''
            ];
            
            $this->view('reseller/hostings', $data);
        }
    }
    
    // Ativar conta de hospedagem
    public function activateHosting($id) {
        if ($this->hostingModel->activateHosting($id)) {
            header('Location: ' . BASE_URL . '/reseller/hostings');
        } else {
            die('Algo deu errado');
        }
    }
    
    // Suspender conta de hospedagem
    public function suspendHosting($id) {
        if ($this->hostingModel->suspendHosting($id)) {
            header('Location: ' . BASE_URL . '/reseller/hostings');
        } else {
            die('Algo deu errado');
        }
    }
    
    // Cancelar conta de hospedagem
    public function cancelHosting($id) {
        if ($this->hostingModel->cancelHosting($id)) {
            header('Location: ' . BASE_URL . '/reseller/hostings');
        } else {
            die('Algo deu errado');
        }
    }
    
    // Gerenciamento de faturas
    public function invoices() {
        // Obtém as faturas
        $invoices = $this->invoiceModel->getAllInvoices();
        
        $data = [
            'title' => 'Gerenciar Faturas',
            'invoices' => $invoices
        ];
        
        $this->view('reseller/invoices', $data);
    }
    
    // Marcar fatura como paga
    public function markInvoiceAsPaid($id) {
        if ($this->invoiceModel->markInvoiceAsPaid($id)) {
            header('Location: ' . BASE_URL . '/reseller/invoices');
        } else {
            die('Algo deu errado');
        }
    }
    
    // Gerenciamento de tickets
    public function tickets() {
        // Obtém os tickets
        $tickets = $this->ticketModel->getAllTickets();
        
        $data = [
            'title' => 'Gerenciar Tickets',
            'tickets' => $tickets
        ];
        
        $this->view('reseller/tickets', $data);
    }
    
    // Responder ticket
    public function replyTicket($id) {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'ticket_id' => $id,
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
                    // Redireciona para a lista de tickets
                    header('Location: ' . BASE_URL . '/reseller/tickets');
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('reseller/reply_ticket', $data);
            }
        } else {
            // Obtém o ticket
            $ticket = $this->ticketModel->getTicketById($id);
            
            $data = [
                'title' => 'Responder Ticket',
                'ticket' => $ticket,
                'message' => '',
                'message_err' => ''
            ];
            
            $this->view('reseller/reply_ticket', $data);
        }
    }
}

