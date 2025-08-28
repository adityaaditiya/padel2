<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Store_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['kasir','admin_keuangan','owner'])) {
            redirect('dashboard');
        }
    }

    public function index()
    {
        $this->authorize();
        $data['store'] = $this->Store_model->get_current();
        $this->load->view('store/index', $data);
    }

    public function open()
    {
        $this->authorize();
        $role    = $this->session->userdata('role');
        $date    = $role === 'owner' ? $this->input->post('store_date') : null;
        $current = $this->Store_model->get_current();
        if (!$date && $current) {
            $date = $current->store_date;
        }
        if (!$date) {
            $date = date('Y-m-d');
        }
        if ($current && $current->is_open) {
            $this->session->set_flashdata('error', 'Toko sudah dibuka.');
        } else {
            $this->Store_model->open($date);
            $this->session->set_flashdata('success', 'Toko dibuka pada tanggal: ' . $date);
        }
        redirect('store');
    }

    public function close()
    {
        $this->authorize();
        $current = $this->Store_model->get_current();
        if (!$current || !$current->is_open) {
            $this->session->set_flashdata('error', 'Toko belum dibuka.');
        } else {
            $next = $this->Store_model->close();
            $this->session->set_flashdata('success', 'Toko ditutup pada tanggal: ' . $current->store_date . '. Tanggal berikutnya: ' . $next);
        }
        redirect('store');
    }
}
