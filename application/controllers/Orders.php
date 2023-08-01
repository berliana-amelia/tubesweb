<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
    function __construct(){
        parent::__construct();

        // Memeriksa apakah ada sesi 'user', jika tidak ada, maka diarahkan ke halaman login
        $user = $this->session->userdata('user');
        if(empty($user)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'login/');
        }

        // Memuat model 'Order_model', 'Store_model', dan 'Menu_model' untuk mengelola data pesanan, toko/restoran, dan menu
        $this->load->model('Order_model');
        $this->load->model('Store_model');
        $this->load->model('Menu_model');
    }

    // Fungsi ini menampilkan halaman daftar pesanan (orders) untuk pengguna tertentu
    public function index() {
        $user = $this->session->userdata('user');
        $order = $this->Order_model->getUserOrder($user['user_id']); // Mendapatkan daftar pesanan pengguna berdasarkan ID pengguna
        $data['orders'] = $order;

        // Memuat data daftar pesanan ke dalam view 'orders'
        $this->load->view('front/partials/header');
        $this->load->view('front/orders', $data);
        $this->load->view('front/partials/footer');
    }

    // Fungsi ini untuk menghapus sebuah pesanan berdasarkan ID pesanan
    public function deleteOrder($id) {
        $order = $this->Order_model->getOrder($id); // Mendapatkan informasi pesanan berdasarkan ID

        // Jika pesanan tidak ditemukan, tampilkan pesan kesalahan dan redirect kembali ke halaman daftar pesanan
        if(empty($order)) {
            $this->session->set_flashdata('error_msg', 'Order not found');
            redirect(base_url().'orders');
        }

        // Menghapus pesanan dari database berdasarkan ID
        $this->Order_model->deleteOrder($id);

        // Menampilkan pesan sukses dan redirect kembali ke halaman daftar pesanan setelah menghapus pesanan
        $this->session->set_flashdata('success_msg', 'Your order cancelled successfully');
        redirect(base_url().'orders');
    }

    // Fungsi ini menampilkan halaman invoice (nota) untuk sebuah pesanan berdasarkan ID pesanan
    public function invoice($id) {
        $order = $this->Order_model->getOrderByUser($id); // Mendapatkan informasi pesanan berdasarkan ID
        $data['order'] = $order;
        $u_id = $order['u_id'];
        $r_id = $order['r_id'];
        $d_id = $order['d_id'];
        $res = $this->Store_model->getStore($r_id); // Mendapatkan informasi toko/restoran berdasarkan ID
        $data['res'] = $res;   
        $dish = $this->Menu_model->getSingleDish($d_id); // Mendapatkan informasi hidangan (dish) berdasarkan ID
        $data['dish'] = $dish;
    
        $user = $this->session->userdata('user');
        // Memeriksa apakah pesanan ini milik pengguna yang sedang login dan apakah pesanan tersebut sudah selesai (status 'closed')
        if($u_id == $user['user_id']) {
            if($order['status'] == 'closed') {
                $this->load->view('front/invoice', $data); // Memuat halaman invoice (nota) jika pesanan sudah selesai
            } else {
                // Jika pesanan belum selesai, tampilkan pesan kesalahan dan redirect kembali ke halaman daftar pesanan
                $this->session->set_flashdata('error_msg', 'your order is not yet complete');
                redirect(base_url().'orders');
            }
        } else {
            // Jika pengguna mencoba mengakses pesanan yang bukan miliknya, tampilkan pesan kesalahan dan redirect kembali ke halaman daftar pesanan
            $this->session->set_flashdata('error_msg', 'you are accessing wrong order data');
            redirect(base_url().'orders');
        }        
    }
}
