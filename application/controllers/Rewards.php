<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rewards extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Reward_product_model','Member_model']);
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $this->authorize();
        $data['products'] = $this->Reward_product_model->get_all();
        $data['role'] = $this->session->userdata('role');
        $this->load->view('rewards/index', $data);
    }

    public function redeem($id)
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'pelanggan') {
            redirect('rewards');
            return;
        }
        $product = $this->Reward_product_model->get_by_id($id);
        if (!$product || $product->stok <= 0) {
            $this->session->set_flashdata('error', 'Produk tidak tersedia.');
            redirect('rewards');
            return;
        }
        $user_id = $this->session->userdata('id');
        $member = $this->Member_model->get_by_id($user_id);
        if (!$member || $member->poin < $product->poin) {
            $this->session->set_flashdata('error', 'Poin tidak mencukupi.');
            redirect('rewards');
            return;
        }
        $this->Member_model->deduct_points($user_id, $product->poin);
        $this->Reward_product_model->reduce_stock($id, 1);
        $this->Reward_product_model->log_redemption($user_id, $id);
        $this->session->set_flashdata('success', 'Penukaran berhasil.');
        redirect('rewards');
    }

    public function create()
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
        $this->load->view('rewards/create');
    }

    public function store()
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('poin', 'Poin', 'required|integer');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');
        if ($this->form_validation->run() === TRUE) {
            $data = [
                'nama_produk' => $this->input->post('nama_produk', TRUE),
                'poin'        => $this->input->post('poin', TRUE),
                'stok'        => $this->input->post('stok', TRUE)
            ];
            $this->Reward_product_model->insert($data);
            $this->session->set_flashdata('success', 'Produk ditambahkan.');
            redirect('rewards');
            return;
        }
        $this->create();
    }

    public function delete($id)
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
        $this->Reward_product_model->delete($id);
        $this->session->set_flashdata('success', 'Produk dihapus.');
        redirect('rewards');
    }
}
?>
