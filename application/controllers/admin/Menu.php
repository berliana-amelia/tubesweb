<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {
    // Konstruktor yang melakukan pengecekan sesi admin dan memuat helper URL.
    public function __construct(){
        parent::__construct();
        $admin = $this->session->userdata('admin');
        if(empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'admin/login/index');
        }
        $this->load->helper('url');
    }

    // Fungsi ini menampilkan halaman daftar menu dan data menu dari model Menu_model.
    public function index() {
        $this->load->model('Menu_model');
        $dishesh = $this->Menu_model->getMenu();
        $data['dishesh'] = $dishesh;
        $this->load->view('admin/partials/header');
        $this->load->view('admin/menu/list', $data);
        $this->load->view('admin/partials/footer');
    }

    // Fungsi ini menampilkan halaman tambah menu.
    public function create_menu(){
        // Load helper common_helper untuk fungsi resizeImage dan model Store_model untuk data restoran.
        $this->load->helper('common_helper');
        $this->load->model('Store_model');
        $store = $this->Store_model->getStores();

        // Konfigurasi upload gambar menggunakan library Upload.
        $config['upload_path'] = './public/uploads/dishesh/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        
        $this->load->library('upload', $config);

        $this->load->model('Menu_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">', '</p>');
        $this->form_validation->set_rules('name', 'Dish name', 'trim|required');
        $this->form_validation->set_rules('about', 'About', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('rname', 'Restaurant name', 'trim|required');

        if($this->form_validation->run() == true) {
            // Jika form berhasil melewati validasi, maka akan dilakukan proses menyimpan menu ke dalam database.
            if(!empty($_FILES['image']['name'])){
                // Jika gambar dipilih, maka akan dilakukan proses upload dan resize gambar.
                if($this->upload->do_upload('image')) {
                    $data = $this->upload->data();
                    // Menyimpan nama file gambar ke dalam formArray untuk disimpan ke database.
                    $formArray['img'] = $data['file_name'];
                    $formArray['name'] = $this->input->post('name');
                    $formArray['about'] = $this->input->post('about');
                    $formArray['price'] = $this->input->post('price');
                    $formArray['r_id'] = $this->input->post('rname');
        
                    $this->Menu_model->create($formArray);
        
                    $this->session->set_flashdata('dish_success', 'Menu added successfully');
                    redirect(base_url(). 'admin/menu/index');

                } else {
                    // Jika ada kesalahan saat proses upload gambar, tampilkan pesan kesalahan dan kembali ke halaman tambah menu.
                    $error = $this->upload->display_errors("<p class='invalid-feedback'>","</p>");
                    $data['errorImageUpload'] = $error; 
                    $data['stores']= $store;
                    $this->load->view('admin/partials/header');
                    $this->load->view('admin/menu/add_menu', $data);
                    $this->load->view('admin/partials/footer');
                }
                
            } else {
                // Jika tidak ada gambar yang dipilih, maka simpan data menu tanpa gambar.
                $formArray['name'] = $this->input->post('name');
                $formArray['about'] = $this->input->post('about');
                $formArray['price'] = $this->input->post('price');
                $formArray['r_id'] = $this->input->post('rname');
    
                $this->Menu_model->create($formArray);
                
                $this->session->set_flashdata('dish_success', 'Dish added successfully');
                redirect(base_url(). 'admin/menu/index');
            }

        } else {
            // Jika form tidak berhasil melewati validasi, maka tampilkan kembali halaman tambah menu dengan pesan kesalahan.
            $store_data['stores']= $store;
            $this->load->view('admin/partials/header');
            $this->load->view('admin/menu/add_menu', $store_data);
            $this->load->view('admin/partials/footer');
        }
    }

    // Fungsi ini menampilkan halaman edit menu dan data menu yang akan diedit.
    public function edit($id) {
        $this->load->model('Menu_model');
        $dish = $this->Menu_model->getSingleDish($id);

        $this->load->model('Store_model');
        $store = $this->Store_model->getStores();
        
        if(empty($dish)) {
            // Jika data menu tidak ditemukan berdasarkan ID yang diberikan, tampilkan pesan kesalahan dan kembali ke halaman daftar menu.
            $this->session->set_flashdata('error', 'Dish not found');
            redirect(base_url(). 'admin/menu/index');
        }

        // Load helper common_helper untuk fungsi resizeImage dan konfigurasi upload gambar.
        $this->load->helper('common_helper');

        $config['upload_path'] = './public/uploads/dishesh/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        
        $this->load->library('upload', $config);

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">', '</p>');
        $this->form_validation->set_rules('name', 'Dish name', 'trim|required');
        $this->form_validation->set_rules('about', 'About', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('rname', 'Restaurant name', 'trim|required');

        if($this->form_validation->run() == true) {
            // Jika form berhasil melewati validasi, maka akan dilakukan proses menyimpan menu yang telah diedit ke dalam database.
            if(!empty($_FILES['image']['name'])){
                // Jika gambar dipilih, maka akan dilakukan proses upload dan resize gambar.
                if($this->upload->do_upload('image')) {
                    $data = $this->upload->data();
                    // Menyimpan nama file gambar ke dalam formArray untuk disimpan ke database.
                    $formArray['img'] = $data['file_name'];
                    $formArray['name'] = $this->input->post('name');
                    $formArray['about'] = $this->input->post('about');
                    $formArray['price'] = $this->input->post('price');
                    $formArray['r_id'] = $this->input->post('rname');
        
                    $this->Menu_model->update($id, $formArray);

                    // Menghapus gambar lama setelah menyimpan gambar baru.
                    if (file_exists('./public/uploads/dishesh/'.$dish['img'])) {
                        unlink('./public/uploads/dishesh/'.$dish['img']);
                    }

                    if(file_exists('./public/uploads/dishesh/thumb/'.$dish['img'])) {
                        unlink('./public/uploads/dishesh/thumb/'.$dish['img']);
                    }
        
                    $this->session->set_flashdata('dish_success', 'Dish updated successfully');
                    redirect(base_url(). 'admin/menu/index');

                } else {
                    // Jika ada kesalahan saat proses upload gambar, tampilkan pesan kesalahan dan kembali ke halaman edit menu.
                    $error = $this->upload->display_errors("<p class='invalid-feedback'>","</p>");
                    $data['errorImageUpload'] = $error;
                    $data['dish'] = $dish;
                    $data['stores'] = $store;
                    $this->load->view('admin/partials/header');
                    $this->load->view('admin/menu/edit', $data);
                    $this->load->view('admin/partials/footer');
                }
            } else {
                // Jika tidak ada gambar yang dipilih, maka simpan data menu tanpa gambar.
                $formArray['name'] = $this->input->post('name');
                $formArray['about'] = $this->input->post('about');
                $formArray['price'] = $this->input->post('price');
                $formArray['r_id'] = $this->input->post('rname');
    
                $this->Menu_model->update($id, $formArray);
    
                $this->session->set_flashdata('dish_success', 'Dish updated successfully');
                redirect(base_url(). 'admin/menu/index');
            }

        } else {
            // Jika form tidak berhasil melewati validasi, maka tampilkan kembali halaman edit menu dengan pesan kesalahan.
            $data['dish'] = $dish;
            $data['stores'] = $store;
            $this->load->view('admin/partials/header');
            $this->load->view('admin/menu/edit', $data);
            $this->load->view('admin/partials/footer');

        }

    }

    // Fungsi ini menghapus data menu berdasarkan ID yang diberikan.
    public function delete($id){
        $this->load->model('Menu_model');
        $dish = $this->Menu_model->getSingleDish($id);

        if(empty($dish)) {
            // Jika data menu tidak ditemukan berdasarkan ID yang diberikan, tampilkan pesan kesalahan dan kembali ke halaman daftar menu.
            $this->session->set_flashdata('error', 'dish not found');
            redirect(base_url().'admin/menu');
        }

        // Menghapus gambar dari direktori setelah menghapus data menu dari database.
        if (file_exists('./public/uploads/dishesh/'.$dish['img'])) {
            unlink('./public/uploads/dishesh/'.$dish['img']);
        }

        if(file_exists('./public/uploads/dishesh/thumb/'.$dish['img'])) {
            unlink('./public/uploads/dishesh/thumb/'.$dish['img']);
        }

        $this->Menu_model->delete($id);

        $this->session->set_flashdata('dish_success', 'dish deleted successfully');
        redirect(base_url().'admin/menu/index');
    }
}
