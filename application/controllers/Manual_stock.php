<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk manajemen stok manual dan laporan stok masuk/keluar.
 */
class Manual_stock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Product_model','Stock_manual_model']);
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

    /**
     * Tampilkan form manajemen stok manual.
     */
    public function index()
    {
        $this->authorize();
        $data['products'] = $this->Product_model->get_all();
        $this->load->view('manual_stock/index', $data);
    }

    /**
     * Simpan transaksi stok manual.
     */
    public function save()
    {
        $this->authorize();
        $product_id = (int) $this->input->post('product_id');
        $qty        = (int) $this->input->post('qty');
        $type       = $this->input->post('type');
        $note       = $this->input->post('note');

        $product = $this->Product_model->get_by_id($product_id);
        if (!$product || $qty <= 0 || !in_array($type, ['Tambah','Ambil'])) {
            $this->session->set_flashdata('error', 'Data tidak valid.');
            redirect('manual_stock');
            return;
        }

        $this->db->trans_start();
        if ($type === 'Tambah') {
            $this->Product_model->increase_stock($product_id, $qty);
        } else {
            $this->Product_model->decrease_stock($product_id, $qty);
        }
        $updated = $this->Product_model->get_by_id($product_id);
        $log = [
            'product_id' => $product_id,
            'type'       => strtolower($type),
            'quantity'   => $qty,
            'note'       => $note,
            'total_stock'=> $updated ? $updated->stok : 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->Stock_manual_model->insert($log);
        $this->db->trans_complete();

        $this->session->set_flashdata('success', 'Transaksi stok berhasil disimpan.');
        redirect('manual_stock');
    }

    /**
     * Laporan stok masuk/keluar.
     */
    public function report()
    {
        $this->authorize();
        $start   = $this->input->get('start');
        $end     = $this->input->get('end');
        $keyword = $this->input->get('q');

        $data['records'] = $this->Stock_manual_model->get_report($start, $end, $keyword);
        $data['filter_start'] = $start;
        $data['filter_end']   = $end;
        $data['search_query'] = $keyword;
        $this->load->view('manual_stock/report', $data);
    }
}
