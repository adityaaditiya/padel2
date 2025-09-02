<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk manajemen produk F&B (kasir, admin_keuangan, owner).
 */
class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
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
        $start_date = $this->input->get('start_date');
        $end_date   = $this->input->get('end_date');
        $keyword    = $this->input->get('q');
        $allowed_per_page = [10, 25, 50, 100];
        $per_page = (int) $this->input->get('per_page');
        if (!in_array($per_page, $allowed_per_page, true)) {
            $per_page = 10;
        }

        $page = max(1, (int) $this->input->get('page'));
        $offset = ($page - 1) * $per_page;

        $total_rows = $this->Product_model->count_all($start_date, $end_date, $keyword);

        $data['start_date']   = $start_date;
        $data['end_date']     = $end_date;
        $data['per_page']     = $per_page;
        $data['page']         = $page;
        $data['total_pages']  = (int) ceil($total_rows / $per_page);
        $data['products']     = $this->Product_model->get_all($start_date, $end_date, $per_page, $offset, $keyword);
        $data['search_query'] = $keyword;
        $this->load->view('products/index', $data);
    }

    public function export_excel()
    {
        $this->authorize();
        $start_date = $this->input->get('start_date');
        $end_date   = $this->input->get('end_date');
        $keyword    = $this->input->get('q');
        $products   = $this->Product_model->get_all($start_date, $end_date, null, null, $keyword);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="daftar_produk.xls"');

        $data = [
            'title'      => 'Daftar Produk',
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'products'   => $products,
            'search_query' => $keyword

        ];

        $this->load->view('products/export_excel', $data);
    }

    public function create()
    {
        $this->authorize();
        $data['categories'] = $this->Product_model->get_categories();
        $this->load->view('products/create', $data);
    }

    public function store()
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');
        $category_list = $this->Product_model->get_categories();
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|in_list['.implode(',', $category_list).']');
        if ($this->form_validation->run() === TRUE) {
            $data = [
                'nama_produk' => $this->input->post('nama_produk', TRUE),
                'harga_jual'  => $this->input->post('harga_jual', TRUE),
                'stok'        => $this->input->post('stok', TRUE),
                'kategori'    => $this->input->post('kategori', TRUE)
            ];
            $this->Product_model->insert($data);
            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
            redirect('products');
            return;
        }
        $this->create();
    }

    public function edit($id)
    {
        $this->authorize();
        $data['product'] = $this->Product_model->get_by_id($id);
        $data['categories'] = $this->Product_model->get_categories();
        $this->load->view('products/edit', $data);
    }

    public function update($id)
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');
        $category_list = $this->Product_model->get_categories();
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|in_list['.implode(',', $category_list).']');
        if ($this->form_validation->run() === TRUE) {
            $data = [
                'nama_produk' => $this->input->post('nama_produk', TRUE),
                'harga_jual'  => $this->input->post('harga_jual', TRUE),
                'stok'        => $this->input->post('stok', TRUE),
                'kategori'    => $this->input->post('kategori', TRUE)
            ];
            $this->Product_model->update($id, $data);
            $this->session->set_flashdata('success', 'Produk berhasil diupdate.');
            redirect('products');
            return;
        }
        $this->edit($id);
    }

    public function delete($id)
    {
        $this->authorize();
        $this->Product_model->delete($id);
        $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        redirect('products');
    }
}
