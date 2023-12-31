<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Singup extends CI_Controller {
    public function __construct(){
        parent::__construct();
        // Memuat model 'User_model' untuk mengelola data pengguna (user)
        $this->load->model('User_model');
    }

    // Fungsi ini menampilkan halaman pendaftaran akun (signup)
    public function index() {
        $this->load->view('front/singup');
    }

    // Fungsi ini untuk membuat akun pengguna berdasarkan data dari form pendaftaran akun (signup)
    public function create_user() {
        $this->load->library('form_validation');
        // Menentukan aturan validasi untuk setiap field yang ada dalam form pendaftaran akun
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
        $this->form_validation->set_rules('username', 'Username','trim|required');
        $this->form_validation->set_rules('firstname', 'First Name','trim|required');
        $this->form_validation->set_rules('lastname', 'Last Name','trim|required');
        $this->form_validation->set_rules('email', 'Email','trim|required');
        $this->form_validation->set_rules('password', 'Password','trim|required');
        $this->form_validation->set_rules('phone', 'Phone','trim|required');
        $this->form_validation->set_rules('address', 'Address','trim|required');

        // Jika form validasi berhasil dijalankan
        if($this->form_validation->run() == true) {

            // Mengambil data dari form pendaftaran akun dan menyimpannya dalam array
            $formArray['username'] = $this->input->post('username');
            $formArray['f_name'] = $this->input->post('firstname');
            $formArray['l_name'] = $this->input->post('lastname');
            $formArray['email'] = $this->input->post('email');
            $formArray['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT); // Mengenkripsi password dengan fungsi password_hash()
            $formArray['phone'] = $this->input->post('phone');
            $formArray['address'] = $this->input->post('address');

            // Membuat akun pengguna baru dengan data yang telah dikumpulkan dari form pendaftaran akun
            $this->User_model->create($formArray);

            // Menampilkan pesan sukses dan redirect ke halaman login setelah membuat akun pengguna berhasil
            $this->session->set_flashdata("success", "Account created successfully, please login");
            redirect(base_url().'login/index');
        } else {
            // Jika validasi form tidak berhasil, tampilkan kembali halaman pendaftaran akun dengan pesan kesalahan
            $this->load->view('front/singup');
        }
    }
}
