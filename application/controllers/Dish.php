<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Dish extends CI_Controller {

    function __construct(){
        parent::__construct();
        // Memuat library 'cart'
        $this->load->library('cart');
    }

    // Fungsi ini menampilkan daftar hidangan (dish) dari suatu toko/restoran berdasarkan ID toko/restoran
    public function list($id) {
        $this->load->model('Menu_model');
        $dishesh = $this->Menu_model->getDishesh($id); // Mengambil daftar hidangan dari model 'Menu_model'

        $this->load->model('Store_model');
        $res = $this->Store_model->getStore($id); // Mengambil informasi toko/restoran dari model 'Store_model'

        // Memuat data daftar hidangan (dish) dan informasi toko/restoran ke dalam view 'dish'
        $data['dishesh'] = $dishesh;
        $data['res'] = $res;
        $this->load->view('front/partials/header');
        $this->load->view('front/dish', $data);
        $this->load->view('front/partials/footer');
    }

    // Fungsi ini untuk menambahkan sebuah hidangan ke dalam keranjang belanja (cart) berdasarkan ID hidangan
    public function addToCart($id) {
        $this->load->model('Menu_model');
        $dishesh = $this->Menu_model->getSingleDish($id); // Mengambil informasi tentang hidangan (dish) berdasarkan ID

        // Menyiapkan data hidangan (dish) untuk dimasukkan ke dalam keranjang belanja (cart)
        $data = array (
            'id'    => $dishesh['d_id'], // ID hidangan
            'r_id'  => $dishesh['r_id'], // ID toko/restoran
            'qty'   => 1, // Jumlah awal (1) untuk ditambahkan ke keranjang
            'price' => $dishesh['price'], // Harga hidangan
            'name' => $dishesh['name'], // Nama hidangan
            'image' => $dishesh['img'] // URL gambar hidangan
        );

        $this->cart->insert($data); // Memasukkan hidangan ke dalam keranjang belanja (cart)
        redirect(base_url(). 'cart/index'); // Diarahkan ke halaman keranjang belanja (cart)
    }
}
