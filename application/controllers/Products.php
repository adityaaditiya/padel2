<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk manajemen produk F&B (kasir, admin_keuangan, owner).
 */
class Products extends CI_Controller
{
    private $categories = ['makanan','snack','cofee','non cofee','tea'];
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
        $data['products'] = $this->Product_model->get_all();
        $this->load->view('products/index', $data);
    }

    public function create()
    {
        $this->authorize();
        $data['categories'] = $this->categories;
        $this->load->view('products/create', $data);
    }

    public function store()
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|in_list['.implode(',', $this->categories).']');
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
        $data['categories'] = $this->categories;
        $this->load->view('products/edit', $data);
    }

    public function update($id)
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|in_list['.implode(',', $this->categories).']');
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
