<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('User_model'); // Memuat model 'User_model' untuk mengelola data pengguna (user)
    }

    // Fungsi ini menampilkan halaman login
    public function index() {
        $this->load->view('front/login'); // Memuat view 'login' untuk halaman login
    }

    // Fungsi ini untuk memproses autentikasi login pengguna
    public function authenticate() {
        $this->load->library('form_validation');
        // Menentukan aturan validasi untuk setiap field yang ada dalam form login
        $this->form_validation->set_rules('username','Username', 'trim|required');
        $this->form_validation->set_rules('password','Password', 'trim|required');

        // Jika form validasi berhasil dijalankan
        if($this->form_validation->run() == true) {
            $username = $this->input->post('username');
            $user = $this->User_model->getByUsername($username); // Mendapatkan data pengguna (user) berdasarkan username

            // Jika pengguna dengan username tersebut ditemukan
            if(!empty($user)) {
                $password = $this->input->post('password');
                // Memeriksa apakah password yang diinputkan cocok dengan password yang tersimpan di database
                if(password_verify($password, $user['password']) == true) {

                    // Jika autentikasi berhasil, simpan data pengguna ke dalam sesi (session) dan redirect ke halaman beranda (home)
                    $userArray['user_id'] = $user['u_id'];
                    $userArray['username'] = $user['username'];
                    $this->session->set_userdata('user', $userArray);
                    redirect(base_url().'home/index');
                } else {
                    // Jika password salah, tampilkan pesan kesalahan dan redirect kembali ke halaman login
                    $this->session->set_flashdata('msg', 'Either username or password is incorrect');
                    redirect(base_url().'login/index');
                }
             } else {
                // Jika username tidak ditemukan, tampilkan pesan kesalahan dan redirect kembali ke halaman login
                $this->session->set_flashdata('msg', 'Either username or password is incorrect');
                redirect(base_url().'login/index');
             }
         } else {
             // Jika validasi form tidak berhasil, tampilkan kembali halaman login
            $this->load->view('front/login');
         }
    }

    // Fungsi ini untuk proses logout pengguna
    public function logout() {
        $this->session->unset_userdata('user'); // Menghapus data pengguna dari sesi (session)
        redirect(base_url().'login/index'); // Diarahkan kembali ke halaman login setelah logout
    }
}
