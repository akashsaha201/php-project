<?php

class Reports extends Controller {
    private $orderRepo;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/showLoginForm');
        } elseif(!isAdmin()) {
            redirect('products');
        }
        $this->orderRepo = new OrderRepository();
    }

    public function index() {
        $data = [
            'title' => 'Admin Dashboard',
            'totalOrders' => $this->orderRepo->getTotalOrders(),
            'totalRevenue' => $this->orderRepo->getTotalRevenue(),
            'topProducts' => $this->orderRepo->getTopProducts()
        ];
        $this->view('admin/reports', $data);
    }

    // Download PDF
    public function downloadPdf() {
        $data = [
            'totalOrders' => $this->orderRepo->getTotalOrders(),
            'totalRevenue' => $this->orderRepo->getTotalRevenue(),
            'topProducts' => $this->orderRepo->getTopProducts()
        ];

        $dompdf = new Dompdf\Dompdf();

        ob_start();
        include '../app/views/admin/report_pdf.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("report.pdf", ["Attachment" => true]);
    }
}
