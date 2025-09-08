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

        $kategori = $this->input->get('kategori');
        $keyword  = $this->input->get('q');

        $data['products'] = $this->Product_model->get_filtered($kategori, $keyword);
        $data['categories'] = $this->Product_model->get_categories();
        $data['selected_category'] = $kategori;
        $data['search_query'] = $keyword;

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
            // stok produk tidak diperbarui di sini; hanya simpan data opname
        }
        if ($batch) {
            $this->db->trans_start();
            $this->Stock_opname_model->insert_batch($batch);
            $this->Stock_opname_model->delete_except($timestamp);
            $this->db->trans_complete();
        }
        $this->session->set_flashdata('success', 'Data opname berhasil disimpan.');
        redirect('stock_opname/report');
    }

    /**
     * Laporan selisih setelah stock opname.
     */
    public function report()
    {
        $this->authorize();
        $data['records'] = $this->Stock_opname_model->get_report();
        $this->load->view('stock_opname/report', $data);
    }

    /**
     * Endpoint AJAX untuk mengambil daftar produk terfilter.
     */
    public function search()
    {
        $this->authorize();
        $kategori = $this->input->get('kategori');
        $keyword  = $this->input->get('q');
        $products = $this->Product_model->get_filtered($kategori, $keyword);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($products));
    }
}
