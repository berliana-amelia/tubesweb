
<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $admin = $this->session->userdata('admin');
        if(empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'admin/login/index');
        }
    }

    public function index() {
        // Menampilkan daftar semua pengguna dari model User_model dan mengirimkan datanya ke view
        $this->load->model('User_model');
        $users = $this->User_model->getUsers();
        $data['users'] = $users;
        $this->load->view('admin/partials/header');
        $this->load->view('admin/user/list', $data);
        $this->load->view('admin/partials/footer');
    }

    public function create_user() {
        // Menambahkan pengguna baru ke dalam database berdasarkan inputan form yang dikirimkan
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
        // Validasi data form yang dikirimkan
        $this->form_validation->set_rules('username', 'Username','trim|required');
        $this->form_validation->set_rules('firstname', 'First Name','trim|required');
        $this->form_validation->set_rules('lastname', 'Last Name','trim|required');
        $this->form_validation->set_rules('email', 'Email','trim|required');
        $this->form_validation->set_rules('password', 'Password','trim|required');
        $this->form_validation->set_rules('phone', 'Phone','trim|required');
        $this->form_validation->set_rules('address', 'Address','trim|required');

        if($this->form_validation->run() == true) {
            // Jika data form valid, maka simpan data pengguna baru ke database
            $formArray['username'] = $this->input->post('username');
            $formArray['f_name'] = $this->input->post('firstname');
            $formArray['l_name'] = $this->input->post('lastname');
            $formArray['email'] = $this->input->post('email');
            $formArray['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT); // Mengenkripsi password sebelum disimpan
            $formArray['phone'] = $this->input->post('phone');
            $formArray['address'] = $this->input->post('address');

            $this->User_model->create($formArray); // Panggil method create dari model User_model untuk menyimpan data baru

            $this->session->set_flashdata('success', 'User added successfully');
            redirect(base_url(). 'admin/user/index');
        } else {
            // Jika data form tidak valid, tampilkan kembali halaman form pembuatan pengguna
            $this->load->view('admin/partials/header');
            $this->load->view('admin/user/add_user');
            $this->load->view('admin/partials/footer');
        }
    }

    public function edit($id) {
        // Mengedit data pengguna berdasarkan ID yang diberikan
        $this->load->model('User_model');
        $user = $this->User_model->getUser($id); // Mendapatkan data pengguna berdasarkan ID

        if(empty($user)) {
            // Jika data pengguna tidak ditemukan, tampilkan pesan error
            $this->session->set_flashdata('error', 'User not found');
            redirect(base_url().'admin/user/index');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
        // Validasi data form yang dikirimkan
        $this->form_validation->set_rules('username', 'Username','trim|required');
        $this->form_validation->set_rules('firstname', 'First Name','trim|required');
        $this->form_validation->set_rules('lastname', 'Last Name','trim|required');
        $this->form_validation->set_rules('email', 'Email','trim|required');
        $this->form_validation->set_rules('password', 'Password','trim|required');
        $this->form_validation->set_rules('phone', 'Phone','trim|required');
        $this->form_validation->set_rules('address', 'Address','trim|required');

        if($this->form_validation->run() == true) { 
            // Jika data form valid, maka perbarui data pengguna ke database
            $formArray['username'] = $this->input->post('username');
            $formArray['f_name'] = $this->input->post('firstname');
            $formArray['l_name'] = $this->input->post('lastname');
            $formArray['email'] = $this->input->post('email');
            $formArray['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT); // Mengenkripsi password sebelum disimpan
            $formArray['phone'] = $this->input->post('phone');
            $formArray['address'] = $this->input->post('address');

            $this->User_model->update($id,$formArray); // Panggil method update dari model User_model untuk memperbarui data pengguna

            $this->session->set_flashdata('success', 'User updated successfully');
            redirect(base_url(). 'admin/user/index');
        } else {
            // Jika data form tidak valid, tampilkan kembali halaman form edit pengguna
            $data['user'] = $user;
            $this->load->view('admin/partials/header');
            $this->load->view('admin/user/edit', $data);
            $this->load->view('admin/partials/footer');
        }
    }

    public function delete($id) {
        // Menghapus data pengguna berdasarkan ID yang diberikan
        $this->load->model('User_model');
        $user = $this->User_model->getUser($id); // Mendapatkan data pengguna berdasarkan ID

        if(empty($user)) {
            // Jika data pengguna tidak ditemukan, tampilkan pesan error
            $this->session->set_flashdata('error', 'User not found');
            redirect(base_url().'admin/user/index');
        }

        $this->User_model->delete($id); // Panggil method delete dari model User_model untuk menghapus data pengguna

        $this->session->set_flashdata('success', 'User deleted successfully');
        redirect(base_url().'admin/user/index');
    }
}
