<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    public function __construct(){
        parent::__construct();
        // Memeriksa apakah ada sesi admin aktif, jika tidak maka redirect ke halaman login admin
        $admin = $this->session->userdata('admin');
        if(empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'admin/login/index');
        }
    }

    // Fungsi ini menampilkan daftar kategori pada halaman admin
    public function index() {
        $this->load->model('Category_model');
        $cats = $this->Category_model->getCategory(); // Memuat daftar kategori dari model 'Category_model'
        $cats_data['cats'] = $cats;

        // Menampilkan daftar kategori ke dalam view 'admin/category/list'
        $this->load->view('admin/partials/header');
        $this->load->view('admin/category/list', $cats_data);
        $this->load->view('admin/partials/footer');
    }

    // Fungsi ini untuk menambahkan kategori baru
    public function create_category(){
        $this->load->model('Category_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('category','Category', 'trim|required');

        if($this->form_validation->run() == true) {
            // Mengambil data kategori dari form dan menyimpannya dalam array
            $cat['c_name'] = $this->input->post('category');
            // Menambahkan kategori baru ke dalam database menggunakan model 'Category_model'
            $this->Category_model->create_cat($cat);
            
            // Menampilkan pesan sukses dan redirect kembali ke halaman daftar kategori
            $this->session->set_flashdata('cat_success', 'category added successfully');
            redirect(base_url().'admin/category/index');
        } else {
            // Jika validasi form tidak berhasil, tampilkan kembali halaman tambah kategori dengan pesan kesalahan
            $this->load->view('admin/partials/header');
            $this->load->view('admin/category/add_cat');
            $this->load->view('admin/partials/footer');
        }
    }

    // Fungsi ini untuk mengedit kategori
    public function edit($id) {
        $this->load->model('Category_model');
        $cat = $this->Category_model->getCat($id); // Mendapatkan data kategori berdasarkan ID dari model 'Category_model'

        if(empty($cat)) {
            // Jika kategori tidak ditemukan, tampilkan pesan kesalahan dan redirect kembali ke halaman daftar kategori
            $this->session->set_flashdata('error', 'Category not found');
            redirect(base_url().'admin/category/index');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('category','Category', 'trim|required');

        if($this->form_validation->run() == true) {
            // Mengambil data kategori yang sudah diedit dari form dan menyimpannya dalam array
            $cat['c_name'] = $this->input->post('category');
            // Mengupdate data kategori ke dalam database menggunakan model 'Category_model'
            $this->Category_model->update($id, $cat);
            
            // Menampilkan pesan sukses dan redirect kembali ke halaman daftar kategori setelah mengedit kategori
            $this->session->set_flashdata('cat_success', 'category added successfully');
            redirect(base_url().'admin/category/index');
        } else {
            // Jika validasi form tidak berhasil, tampilkan kembali halaman edit kategori dengan data kategori yang sudah ada
            $data['cat'] = $cat;
            $this->load->view('admin/partials/header');
            $this->load->view('admin/category/edit', $data);
            $this->load->view('admin/partials/footer');
        }
    }

    // Fungsi ini untuk menghapus kategori
    public function delete($id) {
        $this->load->model('Category_model');
        $cat = $this->Category_model->getCat($id); // Mendapatkan data kategori berdasarkan ID dari model 'Category_model'

        if(empty($cat)) {
            // Jika kategori tidak ditemukan, tampilkan pesan kesalahan dan redirect kembali ke halaman daftar kategori
            $this->session->set_flashdata('error', 'Category not found');
            redirect(base_url().'admin/category/index');
        }

        // Menghapus kategori dari database menggunakan model 'Category_model'
        $this->Category_model->delete($id);

        // Menampilkan pesan sukses dan redirect kembali ke halaman daftar kategori setelah menghapus kategori
        $this->session->set_flashdata('cat_success', 'Category deleted successfully');
        redirect(base_url().'admin/category/index');
    }
}
