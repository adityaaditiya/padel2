<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller laporan tukar poin untuk kasir, admin_keuangan dan owner.
 */
class Point_report extends CI_Controller
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
        $role = $this->session->userdata('role');
        if (!in_array($role, ['kasir','admin_keuangan','owner'])) {
            redirect('dashboard');
        }
    }

    /**
     * Menampilkan laporan penukaran poin berdasarkan rentang tanggal.
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
        $per_page = (int) $this->input->get('per_page');
        $allowed = [10,25,50,100];
        if (!in_array($per_page, $allowed, true)) {
            $per_page = 10;
        }
        $page = max(1, (int) $this->input->get('page'));
        $keyword = $this->input->get('q');

        $all_details = $this->Report_model->get_point_exchange_report($start, $end);
        if ($keyword) {
            $all_details = array_filter($all_details, function($row) use ($keyword) {
                return stripos($row['kode_member'], $keyword) !== false
                    || stripos($row['barang_tukar'], $keyword) !== false
                    || stripos($row['tanggal'], $keyword) !== false;
            });
            $all_details = array_values($all_details);
        }
        $total_rows = count($all_details);
        $start_index = ($page - 1) * $per_page;
        $details = array_slice($all_details, $start_index, $per_page);

        $data['start_date']  = $start;
        $data['end_date']    = $end;
        $data['details']     = $details;
        $data['page']        = $page;
        $data['total_pages'] = (int) ceil($total_rows / $per_page);
        $data['per_page']    = $per_page;
        $data['all_details'] = $all_details;
        $data['search']      = $keyword;
        $data['total_rows']  = $total_rows;
        $this->load->view('points/report', $data);
    }
}
?>
