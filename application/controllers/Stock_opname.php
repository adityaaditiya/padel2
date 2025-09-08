<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk proses stock opname produk.
 */
class Stock_opname extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Product_model','Stock_opname_model']);
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['admin_keuangan','owner'])) {
            redirect('dashboard');
        }
    }

    /**
     * Tampilkan form stock opname.
     */
    public function index()
    {
        $this->authorize();
        $data['products'] = $this->Product_model->get_all();
        $this->load->view('stock_opname/index', $data);
    }

    /**
     * Simpan hasil stock opname.
     */
    public function save()
    {
        $this->authorize();
        $physical = $this->input->post('physical');
        if (!$physical) {
            redirect('stock_opname');
            return;
        }
        $timestamp = date('Y-m-d H:i:s');
        $batch = [];
        foreach ($physical as $product_id => $phys) {
            $product = $this->Product_model->get_by_id($product_id);
            if (!$product) {
                continue;
            }
            $system = (int) $product->stok;
            $phys = (int) $phys;
            $diff = $phys - $system;
            $batch[] = [
                'product_id'  => $product_id,
                'stok_sistem' => $system,
                'stok_fisik'  => $phys,
                'selisih'     => $diff,
                'opname_at'   => $timestamp
            ];
            // update stok produk sesuai jumlah fisik
            $this->Product_model->update($product_id, ['stok' => $phys]);
        }
        if ($batch) {
            $this->Stock_opname_model->insert_batch($batch);
        }
        $this->session->set_flashdata('success', 'Stok opname berhasil disimpan.');
        redirect('stock_opname/report?at='.urlencode($timestamp));
    }

    /**
     * Laporan selisih setelah stock opname.
     */
    public function report()
    {
        $this->authorize();
        $timestamp = $this->input->get('at');
        $data['records'] = $this->Stock_opname_model->get_report($timestamp);
        $this->load->view('stock_opname/report', $data);
    }
}
