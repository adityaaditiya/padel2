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
        if ($this->session->userdata('role') !== 'kasir') {
            show_error('Forbidden', 403);
        }
        $data['products'] = $this->Reward_product_model->get_all();
        $this->load->view('rewards/index', $data);
    }

    public function catalog()
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'pelanggan') {
            show_error('Forbidden', 403);
        }
        $data['products'] = $this->Reward_product_model->get_all();
        $this->load->view('rewards/catalog', $data);
    }

    public function member_lookup()
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'kasir') {
            show_error('Forbidden', 403);
        }
        $kode = $this->input->post('kode_member', TRUE);
        $member = $this->Member_model->get_by_kode($kode);
        if ($member) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'ok', 'member' => $member]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Member tidak ditemukan']));
        }
    }

    public function redeem($id)
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'kasir') {
            show_error('Forbidden', 403);
        }
        $kode = $this->input->post('kode_member', TRUE);
        $member = $this->Member_model->get_by_kode($kode);
        if (!$member) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Member tidak ditemukan']));
            return;
        }
        $product = $this->Reward_product_model->get_by_id($id);
        if (!$product) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan']));
            return;
        }
        if ($product->stok <= 0) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Maaf, stok hadiah ini sudah habis.']));
            return;
        }
        if ($member->poin < $product->poin) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Maaf, poin member tidak mencukupi untuk menukar hadiah ini.']));
            return;
        }
        $point_awal = $member->poin;
        $this->Member_model->deduct_points($member->id, $product->poin);
        $this->Reward_product_model->reduce_stock($id, 1);
        $updated_member = $this->Member_model->get_by_kode($kode);
        $point_akhir = $updated_member ? $updated_member->poin : max($point_awal - $product->poin, 0);
        $this->Reward_product_model->log_redemption($member->id, $id, $point_awal, $point_akhir);
        $updated_product = $this->Reward_product_model->get_by_id($id);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'ok',
                'poin'   => $updated_member ? $updated_member->poin : 0,
                'stok'   => $updated_product ? $updated_product->stok : 0
            ]));
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
            redirect('rewards/manage');
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
        redirect('rewards/manage');
    }

    public function manage()
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
        $data['products'] = $this->Reward_product_model->get_all();
        $this->load->view('rewards/manage', $data);
    }

    public function edit($id)
    {
        $this->authorize();
        if ($this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
        $product = $this->Reward_product_model->get_by_id($id);
        if (!$product) {
            show_404();
        }
        $data['product'] = $product;
        $this->load->view('rewards/edit', $data);
    }

    public function update($id)
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
            $this->Reward_product_model->update($id, $data);
            $this->session->set_flashdata('success', 'Produk diperbarui.');
            redirect('rewards/manage');
            return;
        }
        $this->edit($id);
    }
}
?>
