<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function index()
    {
        $this->load->model('Menu_model');
        $dish = $this->Menu_model->getMenu(); 
		// Mendapatkan daftar menu dari model 'Menu_model'
        $data['dishesh'] = $dish;

        // Memuat data daftar menu ke dalam view 'home'
        $this->load->view('front/partials/header');
        $this->load->view('front/home', $data);
        $this->load->view('front/partials/footer');
    }

    public function sendMail() {
        $this->load->library('form_validation');
        // Menentukan aturan validasi untuk setiap field yang ada dalam form
        $this->form_validation->set_rules('name','name', 'trim|required');
        $this->form_validation->set_rules('email','email', 'trim|required');
        $this->form_validation->set_rules('subject','subject', 'trim|required');
        $this->form_validation->set_rules('message','message', 'trim|required');

        // Jika form validasi berhasil dijalankan
        if($this->form_validation->run() == true) {
            // Mengambil nilai dari setiap field dalam form
            $name = $this->input->post('name');
            $emailFrom = $this->input->post('email');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');

            $toEmail = "rahulrajendrashewale@gmail.com";
            $mailHeaders = "From: ". $name . "<". $emailFrom .">\r\n";

            // Mengirimkan email dengan menggunakan fungsi mail()
            if(mail($toEmail, $subject, $message, $mailHeaders)) {
                $this->session->set_flashdata("msg","mail has been sent successfully");
            } else {
                $this->session->set_flashdata("msg","mail is not sent, try again.");
            }
            redirect(base_url().'home/index'); // Diarahkan kembali ke halaman beranda (home)
        } else {
            redirect(base_url().'home/index'); // Diarahkan kembali ke halaman beranda (home) jika validasi tidak berhasil
        }
    }
}
