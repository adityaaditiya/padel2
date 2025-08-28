<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk laporan bisnis (owner).
 */
class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_model');
        $this->load->library('session');
        $this->load->helper(['url','form']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'owner') {
            redirect('dashboard');
        }
    }

    /**
     * Tampilkan laporan ringkasan bisnis.
     */
    public function index()
    {
        $this->authorize();
        $start = $this->input->get('start_date');
        $end   = $this->input->get('end_date');
        if (!$start) {
            $start = date('Y-m-01');
        }
        if (!$end) {
            $end = date('Y-m-t');
        }
        $data['start_date'] = $start;
        $data['end_date'] = $end;
        $data['summary'] = $this->Report_model->get_business_summary($start, $end);
        $this->load->view('reports/index', $data);
    }
}
