<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Store extends CI_Controller {

    public function __construct(){
        parent::__construct();
        // Mengecek apakah admin sudah login, jika tidak, maka diarahkan ke halaman login
        $admin = $this->session->userdata('admin');
        if(empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired');
            redirect(base_url().'admin/login/index');
        }
    }

    public function index() {
        // Memuat model 'Store_model' untuk mendapatkan data restoran dari database
        $this->load->model('Store_model');
        $stores = $this->Store_model->getStores();
        $store_data['stores'] = $stores;
        // Memuat tampilan header, daftar restoran, dan footer
        $this->load->view('admin/partials/header');
        $this->load->view('admin/store/list', $store_data);
        $this->load->view('admin/partials/footer');
    }

    public function create_restaurant() {
        // Mendapatkan daftar kategori restoran dari model 'Category_model'
        $this->load->model('Category_model');
        $cat = $this->Category_model->getCategory();

        // Memuat helper 'common_helper' yang berisi fungsi utilitas umum
        $this->load->helper('common_helper');

        // Konfigurasi untuk upload gambar restoran
        $config['upload_path'] = './public/uploads/restaurant/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';

        $this->load->library('upload', $config);

        $this->load->model('Store_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">', '</p>');
        $this->form_validation->set_rules('res_name', 'Restaurant name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('url', 'URL', 'trim|required');
        $this->form_validation->set_rules('o_hr', 'o_hr', 'trim|required');
        $this->form_validation->set_rules('c_hr', 'c_hr', 'trim|required');
        $this->form_validation->set_rules('o_days', 'o_days', 'trim|required');
        $this->form_validation->set_rules('c_name', 'category', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');

        if ($this->form_validation->run() == true) {
            if (!empty($_FILES['image']['name'])) {
                // Jika gambar dipilih oleh pengguna
                if ($this->upload->do_upload('image')) {
                    // Jika upload berhasil

                    $data = $this->upload->data();

                    // Mengubah ukuran gambar untuk versi thumbnya
                    resizeImage($config['upload_path'] . $data['file_name'], $config['upload_path'] . 'thumb/' . $data['file_name'], 300, 270);

                    // Membuat array data restoran yang akan disimpan ke database
                    $formArray['img'] = $data['file_name'];
                    $formArray['name'] = $this->input->post('res_name');
                    $formArray['email'] = $this->input->post('email');
                    $formArray['phone'] = $this->input->post('phone');
                    $formArray['url'] = $this->input->post('url');
                    $formArray['o_hr'] = $this->input->post('o_hr');
                    $formArray['c_hr'] = $this->input->post('c_hr');
                    $formArray['o_days'] = $this->input->post('o_days');
                    $formArray['c_id'] = $this->input->post('c_name');
                    $formArray['address'] = $this->input->post('address');

                    // Menyimpan data restoran ke database melalui model 'Store_model'
                    $this->Store_model->create($formArray);

                    $this->session->set_flashdata('res_success', 'Restaurant added successfully');
                    redirect(base_url() . 'admin/store/index');

                } else {
                    // Jika terjadi error saat upload gambar
                    $error = $this->upload->display_errors("<p class='invalid-feedback'>", "</p>");
                    $data['errorImageUpload'] = $error;
                    $data['cats'] = $cat;
                    $this->load->view('admin/partials/header');
                    $this->load->view('admin/store/add_res', $data);
                    $this->load->view('admin/partials/footer');
                }
            } else {
                // Jika tidak ada gambar yang dipilih, data restoran akan disimpan tanpa gambar
                $formArray['name'] = $this->input->post('res_name');
                $formArray['email'] = $this->input->post('email');
                $formArray['phone'] = $this->input->post('phone');
                $formArray['url'] = $this->input->post('url');
                $formArray['o_hr'] = $this->input->post('o_hr');
                $formArray['c_hr'] = $this->input->post('c_hr');
                $formArray['o_days'] = $this->input->post('o_days');
                $formArray['c_id'] = $this->input->post('c_name');
                $formArray['address'] = $this->input->post('address');

                // Menyimpan data restoran ke database melalui model 'Store_model'
                $this->Store_model->create($formArray);

                $this->session->set_flashdata('res_success', 'Restaurant added successfully');
                redirect(base_url() . 'admin/store/index');
            }
        } else {
            // Jika form validasi gagal, tampilkan kembali halaman tambah restoran dengan pesan error
            $data['cats'] = $cat;
            $this->load->view('admin/partials/header');
            $this->load->view('admin/store/add_res', $data);
            $this->load->view('admin/partials/footer');
        }
    }

    public function edit($id) {
        // Mendapatkan data restoran berdasarkan ID
        $this->load->model('Store_model');
        $store = $this->Store_model->getStore($id);

        // Mendapatkan daftar kategori restoran
        $this->load->model('Category_model');
        $cat = $this->Category_model->getCategory();

        if (empty($store)) {
            $this->session->set_flashdata('error', 'Store not found');
            redirect(base_url() . 'admin/store/index');
        }

        $this->load->helper('common_helper');

        $config['upload_path'] = './public/uploads/restaurant/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';

        $this->load->library('upload', $config);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">', '</p>');
        $this->form_validation->set_rules('res_name', 'Restaurant name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('url', 'URL', 'trim|required');
        $this->form_validation->set_rules('o_hr', 'o_hr', 'trim|required');
        $this->form_validation->set_rules('c_hr', 'c_hr', 'trim|required');
        $this->form_validation->set_rules('o_days', 'o_days', 'trim|required');
        $this->form_validation->set_rules('c_name', 'category', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');

        if ($this->form_validation->run() == true) {
            if (!empty($_FILES['image']['name'])) {
                // Jika gambar dipilih oleh pengguna
                if ($this->upload->do_upload('image')) {
                    // Jika upload berhasil

                    $data = $this->upload->data();

                    // Mengubah ukuran gambar untuk versi thumbnya
                    resizeImage($config['upload_path'] . $data['file_name'], $config['upload_path'] . 'thumb/' . $data['file_name'], 300, 270);

                    // Membuat array data restoran yang akan diupdate di database
                    $formArray['img'] = $data['file_name'];
                    $formArray['name'] = $this->input->post('res_name');
                    $formArray['email'] = $this->input->post('email');
                    $formArray['phone'] = $this->input->post('phone');
                    $formArray['url'] = $this->input->post('url');
                    $formArray['o_hr'] = $this->input->post('o_hr');
                    $formArray['c_hr'] = $this->input->post('c_hr');
                    $formArray['o_days'] = $this->input->post('o_days');
                    $formArray['c_id'] = $this->input->post('c_name');
                    $formArray['address'] = $this->input->post('address');

                    // Memperbarui data restoran di database melalui model 'Store_model'
                    $this->Store_model->update($id, $formArray);

                    // Menghapus file gambar lama dari server
                    if (file_exists('./public/uploads/restaurant/' . $store['img'])) {
                        unlink('./public/uploads/restaurant/' . $store['img']);
                    }

                    if (file_exists('./public/uploads/restaurant/thumb/' . $store['img'])) {
                        unlink('./public/uploads/restaurant/thumb/' . $store['img']);
                    }

                    $this->session->set_flashdata('res_success', 'Restaurant updated successfully');
                    redirect(base_url() . 'admin/store/index');
                } else {
                    // Jika terjadi error saat upload gambar
                    $error = $this->upload->display_errors("<p class='invalid-feedback'>", "</p>");
                    $data['errorImageUpload'] = $error;
                    $data['store'] = $store;
                    $data['cats'] = $cat;
                    $this->load->view('admin/partials/header');
                    $this->load->view('admin/store/edit', $data);
                    $this->load->view('admin/partials/footer');
                }
            } else {
                // Jika tidak ada gambar yang dipilih, data restoran akan diupdate tanpa mengubah gambar
                $formArray['name'] = $this->input->post('res_name');
                $formArray['email'] = $this->input->post('email');
                $formArray['phone'] = $this->input->post('phone');
                $formArray['url'] = $this->input->post('url');
                $formArray['o_hr'] = $this->input->post('o_hr');
                $formArray['c_hr'] = $this->input->post('c_hr');
                $formArray['o_days'] = $this->input->post('o_days');
                $formArray['c_id'] = $this->input->post('c_name');
                $formArray['address'] = $this->input->post('address');

                // Memperbarui data restoran di database melalui model 'Store_model'
                $this->Store_model->update($id, $formArray);

                $this->session->set_flashdata('res_success', 'Restaurant updated successfully');
                redirect(base_url() . 'admin/store/index');
            }
        } else {
            // Jika form validasi gagal, tampilkan kembali halaman edit restoran dengan pesan error
            $data['store'] = $store;
            $data['cats'] = $cat;
            $this->load->view('admin/partials/header');
            $this->load->view('admin/store/edit', $data);
            $this->load->view('admin/partials/footer');
        }
    }

    public function delete($id){
        // Mendapatkan data restoran berdasarkan ID
        $this->load->model('Store_model');
        $store = $this->Store_model->getStore($id);

        if (empty($store)) {
            $this->session->set_flashdata('error', 'Restaurant not found');
            redirect(base_url() . 'admin/store');
        }

        // Menghapus file gambar dari server
        if (file_exists('./public/uploads/restaurant/' . $store['img'])) {
            unlink('./public/uploads/restaurant/' . $store['img']);
        }

        if (file_exists('./public/uploads/restaurant/thumb/' . $store['img'])) {
            unlink('./public/uploads/restaurant/thumb/' . $store['img']);
        }

        // Menghapus data restoran dari database melalui model 'Store_model'
        $this->Store_model->delete($id);

        $this->session->set_flashdata('res_success', 'Restaurant deleted successfully');
        redirect(base_url() . 'admin/store/index');
    }
}
