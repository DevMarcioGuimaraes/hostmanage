<?php
class DashboardController extends Controller {
    private $hostingModel;
    private $invoiceModel;
    private $ticketModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->requireClient();
        
        $this->hostingModel = $this->model('Hosting');
        $this->invoiceModel = $this->model('Invoice');
        $this->ticketModel = $this->model('Ticket');
    }
    
    // Dashboard do cliente
    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Obtém os dados do dashboard
        $hostings = $this->hostingModel->getHostingsByUser($userId);
        $invoices = $this->invoiceModel->getInvoicesByUser($userId);
        $tickets = $this->ticketModel->getTicketsByUser($userId);
        
        $data = [
            'title' => 'Dashboard',
            'hostings' => $hostings,
            'invoices' => $invoices,
            'tickets' => $tickets
        ];
        
        $this->view('dashboard/index', $data);
    }
}

