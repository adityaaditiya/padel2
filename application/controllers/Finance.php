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
        $view_mode = $this->input->get('view');
        if (!$view_mode) {
            $view_mode = 'rekap';
        }
        if (!$category) {
            $category = 'semua';
        }
        if ($view_mode === 'detail' && !in_array($category, ['product','booking','batal'], true)) {
            $category = 'booking';
        }
        if (!$start) {
            $start = date('Y-m-01');
        }
        if (!$end) {
            $end = date('Y-m-t');
        }
        $per_page = (int) $this->input->get('per_page');
        $allowed_per_page = [10, 25, 50, 100];
        if (!in_array($per_page, $allowed_per_page, true)) {
            $per_page = 10;
        }
        $page     = max(1, (int) $this->input->get('page'));
        $keyword  = $this->input->get('q');

        if ($view_mode === 'detail') {
            $report = $this->Report_model->get_financial_report_detail($start, $end, $category);
        } else {
            $report = $this->Report_model->get_financial_report($start, $end, $category);
        }
        $all_details = $report['details'];
        if ($keyword) {
            $all_details = array_filter($all_details, function ($row) use ($keyword) {
                foreach ($row as $value) {
                    if (is_scalar($value) && stripos((string) $value, $keyword) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }
        $all_details = array_values($all_details);
        $report['total_masuk']  = array_sum(array_column($all_details, 'uang_masuk'));
        $report['total_keluar'] = array_sum(array_column($all_details, 'uang_keluar'));
        $report['saldo']        = $report['total_masuk'] - $report['total_keluar'];
        $total_rows = count($all_details);
        $start_index = ($page - 1) * $per_page;
        $report['details'] = array_slice($all_details, $start_index, $per_page);

        $data['start_date']   = $start;
        $data['end_date']     = $end;
        $data['category']     = $category;
        $data['report']       = $report;
        $data['page']         = $page;
        $data['total_pages']  = (int) ceil($total_rows / $per_page);
        $data['per_page']     = $per_page;
        $data['all_details']  = $all_details;
        $data['search']       = $keyword;
        $data['view_mode']    = $view_mode;
        $this->load->view('finance/index', $data);
    }
}
