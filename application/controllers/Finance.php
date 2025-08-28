<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller laporan keuangan untuk kasir, admin_keuangan dan owner.
 */
class Finance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Report_model']);
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

    /**
     * Menampilkan laporan keuangan detail berdasarkan rentang tanggal.
     */
    public function index()
    {
        $this->authorize();
        $start    = $this->input->get('start_date');
        $end      = $this->input->get('end_date');
        $category = $this->input->get('category');
        if (!$category) {
            $category = 'semua';
        }
        if (!$start) {
            $start = date('Y-m-01');
        }
        if (!$end) {
            $end = date('Y-m-t');
        }
        $data['start_date'] = $start;
        $data['end_date']   = $end;
        $data['category']   = $category;
        $data['report']     = $this->Report_model->get_financial_report($start, $end, $category);
        $this->load->view('finance/index', $data);
    }
}
