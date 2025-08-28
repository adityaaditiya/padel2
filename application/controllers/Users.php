<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk manajemen pengguna dan profil.
 */
class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Daftar semua pengguna (owner saja).
     */
    public function index()
    {
        if ($this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
        $data['users'] = $this->User_model->get_all();
        $this->load->view('users/index', $data);
    }
    /**
     * Owner only: edit data user lain, termasuk mengganti password dan role.
     */
    public function edit($id)
    {
        if ($this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }

        // Gunakan logika yang sama dengan profile() tetapi memaksa ID yang dipilih
        return $this->profile($id);
    }
    /**
     * Edit profil pengguna. Jika $id null, edit profil sendiri.
     * Owner dapat mengedit semua user termasuk role.
     */
    public function profile($id = NULL)
    {
        $current_id = $this->session->userdata('id');
        $current_role = $this->session->userdata('role');

        if ($id === NULL) {
            $id = $current_id;
        } elseif ($current_role !== 'owner' && (int)$id !== (int)$current_id) {
            show_error('Forbidden', 403);
        }

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            }
            if ($current_role === 'owner') {
                $this->form_validation->set_rules('role', 'Role', 'required|in_list[pelanggan,kasir,admin_keuangan,owner]');
            }
            if ($this->form_validation->run() === TRUE) {
                $update = [
                    'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                    'email'        => $this->input->post('email', TRUE),
                    'no_telepon'   => $this->input->post('no_telepon', TRUE)
                ];
                if ($this->input->post('password')) {
                    $update['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }
                if ($current_role === 'owner') {
                    $update['role'] = $this->input->post('role', TRUE);
                }
                $this->User_model->update($id, $update);

                // perbarui session jika mengubah diri sendiri
                if ((int)$id === (int)$current_id) {
                    $session_update = [
                        'nama_lengkap' => $update['nama_lengkap'],
                        'email'        => $update['email']
                    ];
                    if ($current_role === 'owner' && isset($update['role'])) {
                        $session_update['role'] = $update['role'];
                    }
                    $this->session->set_userdata($session_update);
                }

                $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
                if ($current_role === 'owner' && (int)$id !== (int)$current_id) {
                    redirect('users');
                } else {
                    redirect('users/profile');
                }
                return;
            }
        }

        $data['user'] = $user;
        $data['editing_self'] = ((int)$id === (int)$current_id);
        $this->load->view('users/profile', $data);
    }
}
