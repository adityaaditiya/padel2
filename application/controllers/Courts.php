<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk manajemen data lapangan (hanya owner).
 */
class Courts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Court_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'owner') {
            redirect('dashboard');
        }
    }

    /**
     * Daftar lapangan.
     */
    public function index()
    {
        $this->authorize();
        $data['courts'] = $this->Court_model->get_all();
        $this->load->view('courts/index', $data);
    }

    /**
     * Form tambah lapangan.
     */
    public function create()
    {
        $this->authorize();
        $this->load->view('courts/create');
    }

    /**
     * Simpan lapangan baru.
     */
    public function store()
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_lapangan', 'Nama Lapangan', 'required');
        $this->form_validation->set_rules('harga_per_jam', 'Harga per Jam', 'required|numeric');
        if ($this->form_validation->run() === TRUE) {
            $data = [
                'nama_lapangan' => $this->input->post('nama_lapangan', TRUE),
                'harga_per_jam' => $this->input->post('harga_per_jam', TRUE),
                'status'        => $this->input->post('status', TRUE)
            ];
            $this->Court_model->insert($data);
            $this->session->set_flashdata('success', 'Data lapangan berhasil ditambahkan.');
            redirect('courts');
            return;
        }
        $this->create();
    }

    /**
     * Form edit lapangan.
     */
    public function edit($id)
    {
        $this->authorize();
        $data['court'] = $this->Court_model->get_by_id($id);
        $this->load->view('courts/edit', $data);
    }

    /**
     * Update data lapangan.
     */
    public function update($id)
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_lapangan', 'Nama Lapangan', 'required');
        $this->form_validation->set_rules('harga_per_jam', 'Harga per Jam', 'required|numeric');
        if ($this->form_validation->run() === TRUE) {
            $data = [
                'nama_lapangan' => $this->input->post('nama_lapangan', TRUE),
                'harga_per_jam' => $this->input->post('harga_per_jam', TRUE),
                'status'        => $this->input->post('status', TRUE)
            ];
            $this->Court_model->update($id, $data);
            $this->session->set_flashdata('success', 'Data lapangan berhasil diupdate.');
            redirect('courts');
            return;
        }
        $this->edit($id);
    }

    /**
     * Hapus lapangan.
     */
    public function delete($id)
    {
        $this->authorize();
        $this->Court_model->delete($id);
        $this->session->set_flashdata('success', 'Data lapangan berhasil dihapus.');
        redirect('courts');
    }
}
