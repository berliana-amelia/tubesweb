<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    // Fungsi ini menampilkan halaman login admin
    public function index() {
        $this->load->view('admin/login');
    }

    // Fungsi ini digunakan untuk melakukan proses otentikasi login admin
    public function authenticate() {
        // Memuat library form_validation untuk melakukan validasi form login
        $this->load->library('form_validation');
        $this->load->model('Admin_model');
        
        // Menetapkan aturan validasi untuk username dan password
        $this->form_validation->set_rules('username','Username', 'trim|required');
        $this->form_validation->set_rules('password','Password', 'trim|required');

        if($this->form_validation->run() == true) {
            // Jika form berhasil melewati validasi, maka akan dilakukan proses otentikasi
            $username = $this->input->post('username');
            $admin = $this->Admin_model->getByUsername($username);
            if(!empty($admin)) {
                // Jika data admin ditemukan berdasarkan username yang dimasukkan
                $password = $this->input->post('password');
                if( password_verify($password, $admin['password']) == true) {
                    // Jika password yang dimasukkan cocok dengan password yang ada di database
                    // Membuat array sesi yang berisi data admin yang berhasil login
                    $adminArray['admin_id'] = $admin['admin_id'];
                    $adminArray['username'] = $admin['username'];
                    // Menyimpan array sesi ke dalam sesi admin
                    $this->session->set_userdata('admin', $adminArray);
                    // Redirect ke halaman dashboard admin
                    redirect(base_url().'admin/home');
                } else {
                    // Jika password tidak cocok, maka tampilkan pesan kesalahan
                    $this->session->set_flashdata('msg', 'Either username or password is incorrect');
                    redirect(base_url().'admin/login/index');
                }
             } else {
                // Jika data admin tidak ditemukan berdasarkan username yang dimasukkan
                // Tampilkan pesan kesalahan
                $this->session->set_flashdata('msg', 'Either username or password is incorrect');
                redirect(base_url().'admin/login/index');
             }
             //success
         } else {
             // Jika form tidak berhasil melewati validasi, maka tampilkan kembali halaman login dengan pesan kesalahan
            $this->load->view('admin/login');
         }
    }

    // Fungsi ini digunakan untuk proses logout admin
    public function logout() {
        // Menghapus sesi admin
        $this->session->unset_userdata('admin');
        // Redirect kembali ke halaman login admin
        redirect(base_url().'admin/login/index');
    }
}
