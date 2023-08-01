<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct(){
        parent::__construct();
        // Memeriksa apakah ada sesi admin aktif, jika tidak maka redirect ke halaman login admin
        $admin = $this->session->userdata('admin');
        if(empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'admin/login/index');
        }
        // Memuat model-model yang dibutuhkan
        $this->load->model('Admin_model');
        $this->load->model('Store_model');
        $this->load->model('Menu_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Category_model');
    }

    // Fungsi ini menampilkan halaman dashboard admin dengan data statistik
    public function index() {
        // Mengambil data statistik dari model dan memasukkannya ke dalam variabel $data
        $data['countStore'] = $this->Store_model->countStore();
        $data['countDish'] = $this->Menu_model->countDish();
        $data['countUser'] = $this->User_model->countUser();
        $data['countOrders'] = $this->Order_model->countOrders();
        $data['countCategory'] = $this->Category_model->countCategory();
        $data['countPendingOrders'] = $this->Order_model->countPendingOrders();
        $data['countDeliveredOrders'] = $this->Order_model->countDeliveredOrders();
        $data['countRejectedOrders'] = $this->Order_model->countRejectedOrders();

        // Mengambil data laporan restoran dari model dan memasukkannya ke dalam variabel $resReport
        $resReport = $this->Admin_model->getResReport();
        $data['resReport'] = $resReport;

        // Mengambil data laporan menu dari model dan memasukkannya ke dalam variabel $dishReport
        $dishReport = $this->Admin_model->dishReport();
        $data['dishReport'] = $dishReport;

        // Menampilkan halaman dashboard dengan data yang telah diambil
        $this->load->view('admin/partials/header');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/partials/footer');
    }

    // Fungsi ini menampilkan laporan restoran dalam bentuk tabel
    public function resReport() {
        $resReport = $this->Admin_model->getResReport();
        $data['resReport'] = $resReport;
        $this->load->view('admin/reports/res_report', $data);
    }

    // Fungsi ini menampilkan laporan menu dalam bentuk tabel
    public function dishesReport() {
        $dishReport = $this->Admin_model->dishReport();
        $data['dishReport'] = $dishReport;
        $this->load->view('admin/reports/dish_report', $data);
    }

    // Fungsi ini menampilkan laporan pengguna dalam bentuk tabel (masih kosong)
    public function usersReport() {
        echo "user";
    }

    // Fungsi ini menampilkan laporan pesanan dalam bentuk tabel
    public function ordersReport() {
        $resReport = $this->Admin_model->getResReport();
        $data['resReport'] = $resReport;

        $this->load->view('admin/partials/header');
        $this->load->view('admin/reports/res_report', $data);
        $this->load->view('admin/partials/footer');
    }

    // Fungsi ini digunakan untuk mengenerate file PDF dari laporan (laporan restoran atau laporan menu)
    public function generate_pdf($id) {
        //load pdf library
        $this->load->library('Pdf');

        // Membuat objek PDF baru
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('www.foodienator.com');
        $pdf->SetTitle('Report');
        $pdf->SetSubject('Report generated using Codeigniter and TCPDF');
        $pdf->SetKeywords('TCPDF, PDF, MySQL, Codeigniter');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $pdf->SetFont('times', 'BI', 12);

        // ---------------------------------------------------------

        //Generate HTML table data from MySQL - start
        $template = array(
            'table_open' => '<table border="1" cellpadding="2" cellspacing="1">'
        );

        $this->table->set_template($template);

        if($id == 1) {
            // Jika ID adalah 1, berarti ingin membuat laporan restoran
            $resReport = $this->Admin_model->getResReport();
            $this->table->set_heading('Id', 'Restaurants', 'Total-sales');
            foreach ($resReport as $sf):
                $this->table->add_row($sf->r_id, $sf->name, $sf->price);
            endforeach; 

        } else if($id == 2) {
            // Jika ID adalah 2, berarti ingin membuat laporan menu
            $this->table->set_heading('Id', 'Dish name', 'total number of times dish ordered');
            $dishReport = $this->Admin_model->dishReport();
            foreach ($dishReport as $sf):
                $this->table->add_row($sf->d_id, $sf->d_name, $sf->qty);
            endforeach;
            
        } else {
            // Jika ID tidak valid, redirect kembali ke halaman dashboard admin
            redirect(base_url(). 'admin/home');
        }

        // Generate HTML table data from MySQL - end

        // add a page
        $pdf->AddPage();


        // reset pointer to the last page
        $pdf->lastPage();

        //Close and output PDF document
        $pdf->Output(md5(time()).'.pdf', 'I');
    }
}
