<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
    function __construct(){
        parent::__construct();

        // Memeriksa apakah ada sesi 'user', jika tidak ada, maka diarahkan ke halaman login
        $user = $this->session->userdata('user');
        if(empty($user)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'login/');
        }

        // Memuat model 'User_model' untuk mengelola data pengguna (user)
        $this->load->model('User_model');
    }

    // Fungsi ini menampilkan halaman profil pengguna
    public function index() {
        $loggedUser = $this->session->userdata('user');
        $id = $loggedUser['user_id'];
        $user = $this->User_model->getUser($id); // Mendapatkan informasi pengguna (user) berdasarkan ID
        $data['user'] = $user;

        // Memuat data pengguna ke dalam view 'profile'
        $this->load->view('front/partials/header');
        $this->load->view('front/profile', $data);
        $this->load->view('front/partials/footer');
    }

    // Fungsi ini untuk mengedit profil pengguna berdasarkan ID pengguna
    public function edit($id) {
        $user = $this->User_model->getUser($id); // Mendapatkan informasi pengguna (user) berdasarkan ID

        // Jika pengguna tidak ditemukan, tampilkan pesan kesalahan dan redirect kembali ke halaman profil
        if(empty($user)) {
            $this->session->set_flashdata('error', 'User not found');
            redirect(base_url().'profile');
        }

        $this->load->library('form_validation');
        // Menentukan aturan validasi untuk setiap field yang ada dalam form edit profil
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
        $this->form_validation->set_rules('username', 'Username','trim|required');
        $this->form_validation->set_rules('firstname', 'First Name','trim|required');
        $this->form_validation->set_rules('lastname', 'Last Name','trim|required');
        $this->form_validation->set_rules('email', 'Email','trim|required');
        $this->form_validation->set_rules('phone', 'Phone','trim|required');
        $this->form_validation->set_rules('address', 'Address','trim|required');

        // Jika form validasi berhasil dijalankan
        if($this->form_validation->run() == true) { 

            // Memperbarui data pengguna berdasarkan inputan dari form edit profil
            $formArray['username'] = $this->input->post('username');
            $formArray['f_name'] = $this->input->post('firstname');
            $formArray['l_name'] = $this->input->post('lastname');
            $formArray['email'] = $this->input->post('email');
            $formArray['phone'] = $this->input->post('phone');
            $formArray['address'] = $this->input->post('address');

            $this->User_model->update($id,$formArray); // Memperbarui data pengguna ke dalam database

            // Menampilkan pesan sukses dan redirect kembali ke halaman profil setelah memperbarui profil
            $this->session->set_flashdata('success', 'User updated successfully');
            redirect(base_url(). 'profile/index');

        } else {
            // Jika validasi form tidak berhasil, tampilkan kembali halaman profil dengan data pengguna yang sudah ada
            $data['user'] = $user; 
            $this->load->view('front/partials/header');
            $this->load->view('front/profile', $data);
            $this->load->view('front/partials/footer');
        }
    }
 
    // Fungsi ini untuk mengedit password pengguna berdasarkan ID pengguna
    public function editPassword($id) {
        $user = $this->User_model->getUser($id); // Mendapatkan informasi pengguna (user) berdasarkan ID

        // Jika pengguna tidak ditemukan, tampilkan pesan kesalahan dan redirect kembali ke halaman profil
        if(empty($user)) {
            $this->session->set_flashdata('error', 'User not found');
            redirect(base_url().'profile');
        }

        $this->load->library('form_validation');
        // Menentukan aturan validasi untuk setiap field yang ada dalam form edit password
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
        $this->form_validation->set_rules('cPassword', 'Current password','trim|required');
        $this->form_validation->set_rules('nPassword', 'New password','trim|required');
        $this->form_validation->set_rules('nRPassword', 'New password','trim|required');

        // Jika form validasi berhasil dijalankan
        if($this->form_validation->run() == true) { 
            $cPassword = $this->input->post('cPassword');
            $nPassword = $this->input->post('nPassword');
            $nRPassword = $this->input->post('nRPassword');

            // Memeriksa apakah password saat ini cocok dengan password yang tersimpan di database
            if(password_verify($cPassword, $user['password']) == true) {
                if($nPassword != $nRPassword) {
                    // Jika password baru tidak cocok, tampilkan pesan kesalahan dan redirect kembali ke halaman profil
                    $this->session->set_flashdata('pwd_error', 'password not match');
                    redirect(base_url(). 'profile/index');
                } else {
                    // Jika password baru cocok, memperbarui password pengguna ke dalam database
                    $formArray['password'] = password_hash($this->input->post('nPassword'), PASSWORD_DEFAULT);
                    $this->User_model->update($id,$formArray);

                    // Menampilkan pesan sukses dan redirect kembali ke halaman profil setelah memperbarui password
                    $this->session->set_flashdata('pwd_success', 'Password updated successfully');
                    redirect(base_url(). 'profile/index');
                }
            } else {
                // Jika password saat ini tidak cocok, tampilkan pesan kesalahan dan redirect kembali ke halaman profil
                $this->session->set_flashdata('pwd_error', 'Your old password is incorrect');
                redirect(base_url(). 'profile/index');
            }
        } else {
            // Jika validasi form tidak berhasil, tampilkan kembali halaman profil dengan data pengguna yang sudah ada
            $data['user'] = $user; 
            $this->load->view('front/partials/header');
            $this->load->view('front/profile', $data);
            $this->load->view('front/partials/footer');
        }
    }
}
