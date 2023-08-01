<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Checkout extends CI_Controller {

    function __construct() {
        parent::__construct();

        // Memeriksa apakah ada sesi 'user', jika tidak ada, maka diarahkan ke halaman login
        $user = $this->session->userdata('user');
        if(empty($user)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'login/');
        }

        // Memuat helper 'date', library 'form_validation', 'cart', dan model 'Order_model' dan 'User_model'
        $this->load->helper('date');
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->model('Order_model');
        $this->load->model('User_model');
        $this->controller = 'checkout';
    }

    // Fungsi ini menampilkan halaman checkout untuk proses pembayaran pesanan
    public function index() {
       $loggedUser = $this->session->userdata('user');
       $u_id = $loggedUser['user_id'];
       $user = $this->User_model->getUser($u_id);

        // Jika keranjang kosong, maka diarahkan kembali ke halaman restaurant
        if($this->cart->total_items() <= 0) {
            redirect(base_url().'restaurant');
        }

        // Proses form validasi alamat pengiriman
        $submit = $this->input->post('placeholder');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
        $this->form_validation->set_rules('address', 'Address','trim|required');

        // Jika form validasi berhasil dijalankan
        if($this->form_validation->run() == true) { 
            $formArray['address'] = $this->input->post('address');
            
            // Update alamat pengguna di dalam tabel 'users'
            $this->User_model->update($u_id,$formArray);

            // Memproses pemesanan dan menyimpan pesanan ke dalam database
            $order = $this->placeOrder($u_id);

            // Jika pemesanan berhasil
            if($order) {
                $this->session->set_flashdata('success_msg', 'Thank You! Your order has been placed successfully!');
                redirect(base_url().'orders'); // Diarahkan ke halaman daftar pesanan (orders)
            } else {
                $data['error_msg'] = "Order submission failed, please try again.";
            }
        }

        // Memuat data pengguna dan item keranjang ke dalam view checkout
        $data['user'] = $user;
        $data['cartItems'] = $this->cart->contents();
        $this->load->view('front/partials/header');
        $this->load->view('front/checkout',$data);
        $this->load->view('front/partials/footer');
    }

    // Fungsi ini untuk menyimpan pesanan ke dalam database
    public function placeOrder($u_Id) {  
        $cartItems = $this->cart->contents();
        $i = 0;
        foreach($cartItems as $item) {
            // Menyusun data pesanan untuk disimpan ke dalam tabel 'orders'
            $orderData[$i]['u_id'] = $u_Id;
            $orderData[$i]['d_id'] = $item['id'];
            $orderData[$i]['r_id'] = $item['r_id'];
            $orderData[$i]['d_name'] = $item['name'];
            $orderData[$i]['quantity'] = $item['qty'];
            $orderData[$i]['price'] = $item['subtotal'];
            $orderData[$i]['date'] = date('Y-m-d H:i:s', now());
            $orderData[$i]['success-date'] = date('Y-m-d H:i:s', now());
            $i++;
        }

        if(!empty($orderData)) {                
            $insertOrder = $this->Order_model->insertOrder($orderData); // Menyimpan pesanan ke dalam tabel 'orders'
            if($insertOrder) {
                $this->cart->destroy(); // Mengosongkan keranjang setelah pesanan berhasil disimpan
                // Mengembalikan ID pesanan sebagai hasil dari fungsi ini
                return $insertOrder;
            }
        }   
        return false; // Mengembalikan nilai false jika gagal menyimpan pesanan
    }
}
