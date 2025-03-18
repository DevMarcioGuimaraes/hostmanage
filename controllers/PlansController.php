<?php
class PlansController extends Controller {
    private $planModel;
    private $hostingModel;
    private $invoiceModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->requireClient();
        
        $this->planModel = $this->model('Plan');
        $this->hostingModel = $this->model('Hosting');
        $this->invoiceModel = $this->model('Invoice');
    }
    
    // Lista de planos
    public function index() {
        // Obtém os planos
        $plans = $this->planModel->getPlans();
        
        $data = [
            'title' => 'Planos de Hospedagem',
            'plans' => $plans
        ];
        
        $this->view('plans/index', $data);
    }
    
    // Contratar plano
    public function order($id) {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'user_id' => $_SESSION['user_id'],
                'plan_id' => $id,
                'domain' => trim($_POST['domain']),
                'domain_err' => '',
                'title' => 'Contratar Plano'
            ];
            
            // Valida o domínio
            if (empty($data['domain'])) {
                $data['domain_err'] = 'Por favor, informe o domínio';
            }
            
            // Verifica se não há erros
            if (empty($data['domain_err'])) {
                // Adiciona a conta de hospedagem
                if ($this->hostingModel->addHosting($data)) {
                    // Cria a fatura
                    $plan = $this->planModel->getPlanById($id);
                    $invoiceData = [
                        'user_id' => $_SESSION['user_id'],
                        'amount' => $plan->price,
                        'description' => 'Contratação do plano ' . $plan->name,
                        'due_date' => date('Y-m-d', strtotime('+7 days'))
                    ];
                    
                    if ($this->invoiceModel->addInvoice($invoiceData)) {
                        // Redireciona para o dashboard
                        header('Location: ' . BASE_URL . '/dashboard');
                    } else {
                        die('Algo deu errado');
                    }
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('plans/order', $data);
            }
        } else {
            // Obtém o plano
            $plan = $this->planModel->getPlanById($id);
            
            $data = [
                'title' => 'Contratar Plano',
                'plan' => $plan,
                'domain' => '',
                'domain_err' => ''
            ];
            
            $this->view('plans/order', $data);
        }
    }
    
    // Migrar de plano
    public function upgrade($hostingId) {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Processa o formulário
            
            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Dados do formulário
            $data = [
                'hosting_id' => $hostingId,
                'plan_id' => intval($_POST['plan_id']),
                'plan_id_err' => '',
                'title' => 'Migrar de Plano'
            ];
            
            // Valida o plano
            if (empty($data['plan_id'])) {
                $data['plan_id_err'] = 'Por favor, selecione um plano';
            }
            
            // Verifica se não há erros
            if (empty($data['plan_id_err'])) {
                // Atualiza a conta de hospedagem
                if ($this->hostingModel->upgradePlan($data)) {
                    // Cria a fatura
                    $plan = $this->planModel->getPlanById($data['plan_id']);
                    $invoiceData = [
                        'user_id' => $_SESSION['user_id'],
                        'amount' => $plan->price,
                        'description' => 'Migração para o plano ' . $plan->name,
                        'due_date' => date('Y-m-d', strtotime('+7 days'))
                    ];
                    
                    if ($this->invoiceModel->addInvoice($invoiceData)) {
                        // Redireciona para o dashboard
                        header('Location: ' . BASE_URL . '/dashboard');
                    } else {
                        die('Algo deu errado');
                    }
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carrega a view com erros
                $this->view('plans/upgrade', $data);
            }
        } else {
            // Obtém a conta de hospedagem
            $hosting = $this->hostingModel->getHostingById($hostingId);
            
            // Verifica se a conta de hospedagem pertence ao usuário
            if ($hosting->user_id != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }
            
            // Obtém os planos
            $plans = $this->planModel->getPlans();
            
            $data = [
                'title' => 'Migrar de Plano',
                'hosting' => $hosting,
                'plans' => $plans,
                'plan_id' => '',
                'plan_id_err' => ''
            ];
            
            $this->view('plans/upgrade', $data);
        }
    }
}

