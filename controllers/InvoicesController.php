<?php
class InvoicesController extends Controller {
    private $invoiceModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->requireClient();
        
        $this->invoiceModel = $this->model('Invoice');
    }
    
    // Lista de faturas
    public function index() {
        // Obtém as faturas do usuário
        $invoices = $this->invoiceModel->getInvoicesByUser($_SESSION['user_id']);
        
        $data = [
            'title' => 'Minhas Faturas',
            'invoices' => $invoices
        ];
        
        $this->view('invoices/index', $data);
    }
    
    // Visualizar fatura
    public function view($id) {
        // Obtém a fatura
        $invoice = $this->invoiceModel->getInvoiceById($id);
        
        // Verifica se a fatura pertence ao usuário
        if ($invoice->user_id != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/invoices');
            exit;
        }
        
        $data = [
            'title' => 'Visualizar Fatura',
            'invoice' => $invoice
        ];
        
        $this->view('invoices/view', $data);
    }
    
    // Pagar fatura
    public function pay($id) {
        // Obtém a fatura
        $invoice = $this->invoiceModel->getInvoiceById($id);
        
        // Verifica se a fatura pertence ao usuário
        if ($invoice->user_id != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/invoices');
            exit;
        }
        
        // Verifica se a fatura já foi paga
        if ($invoice->status == 'paid') {
            header('Location: ' . BASE_URL . '/invoices');
            exit;
        }
        
        // Simula o pagamento (aqui seria integrado com um gateway de pagamento)
        if ($this->invoiceModel->markInvoiceAsPaid($id)) {
            header('Location: ' . BASE_URL . '/invoices');
        } else {
            die('Algo deu errado');
        }
    }
}

