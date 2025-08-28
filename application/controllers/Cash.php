<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Cash_model','Store_model']);
        $this->load->library('session');
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

    public function add()
    {
        $this->authorize();
        if ($this->input->method() === 'post') {
            $error = $this->Store_model->validate_device_date($this->input->post('device_date'));
            if ($error) {
                $this->session->set_flashdata('error', $error);
            } else {
                $data = [
                    'tanggal'  => date('Y-m-d H:i:s'),
                    'type'     => 'in',
                    'category' => $this->input->post('category'),
                    'amount'   => (float) $this->input->post('amount'),
                    'note'     => $this->input->post('note')
                ];
                $this->Cash_model->insert($data);
                $this->session->set_flashdata('success', 'Kas masuk berhasil disimpan');
            }
            redirect('cash/add');
        }
        $data['store'] = $this->Store_model->get_current();
        $this->load->view('cash/add', $data);
    }

    public function withdraw()
    {
        $this->authorize();
        if ($this->input->method() === 'post') {
            $error = $this->Store_model->validate_device_date($this->input->post('device_date'));
            if ($error) {
                $this->session->set_flashdata('error', $error);
            } else {
                $data = [
                    'tanggal'  => date('Y-m-d H:i:s'),
                    'type'     => 'out',
                    'category' => $this->input->post('category'),
                    'amount'   => (float) $this->input->post('amount'),
                    'note'     => $this->input->post('note')
                ];
                $this->Cash_model->insert($data);
                $this->session->set_flashdata('success', 'Kas keluar berhasil disimpan');
            }
            redirect('cash/withdraw');
        }
        $data['store'] = $this->Store_model->get_current();
        $this->load->view('cash/withdraw', $data);
    }
}
