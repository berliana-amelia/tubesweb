<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Cart extends CI_Controller {

    function __construct(){
        parent::__construct();

        // Memeriksa apakah ada sesi 'user', jika tidak ada, maka diarahkan ke halaman login
        $user = $this->session->userdata('user');
        if(empty($user)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'login/');
        }

        // Memuat library 'cart' dan model 'Menu_model'
        $this->load->library('cart');
        $this->load->model('Menu_model');
    }

    // Fungsi ini menampilkan halaman keranjang belanja dengan menampilkan daftar item di dalam keranjang
    public function index() {
        $data['cartItems'] = $this->cart->contents();
        $this->load->view('front/partials/header');
        $this->load->view('front/cart', $data);
        $this->load->view('front/partials/footer');
    }

    // Fungsi ini untuk mengupdate jumlah (qty) dari sebuah item di dalam keranjang
    function updateCartItemQty() {
        $update = 0;

        // Mengambil informasi item di dalam keranjang yang akan diupdate jumlahnya
        $rowid = $this->input->get('rowid'); // Mengambil nilai rowid dari permintaan GET
        $qty = $this->input->get('qty');     // Mengambil nilai qty dari permintaan GET

        if(!empty($rowid) && !empty($qty)) {
            // Menyusun data untuk update dan mengupdate item di dalam keranjang
            $data = array (
                'rowid' => $rowid,
                'qty'   => $qty
            );
            $update = $this->cart->update($data); // Mengupdate item di dalam keranjang
        }
        echo $update ? 'ok':'err'; // Menampilkan pesan 'ok' jika berhasil diupdate, dan 'err' jika gagal.
    }

    // Fungsi ini untuk menghapus sebuah item dari keranjang berdasarkan ID item tersebut
    function removeItem($id) {
        $remove = $this->cart->remove($id); // Menghapus item dari keranjang berdasarkan ID

        redirect(base_url().'cart'); // Mengalihkan kembali ke halaman keranjang belanja setelah menghapus item
    }

}
