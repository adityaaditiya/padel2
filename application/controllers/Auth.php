<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller otentikasi dan registrasi pengguna.
 *
 * Controller ini menyediakan fungsi untuk login, registrasi, dan logout.
 * Pelanggan dapat melakukan registrasi, sedangkan user lain dibuat oleh owner.
 */
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    /**
     * Halaman login. Jika sudah login, diarahkan ke dashboard.
     */
    public function login()
    {
        // Jika sudah login, redirect
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $user = $this->User_model->login($email, $password);
                if ($user) {
                    // Set session
                    $this->session->set_userdata([
                        'id'           => $user->id,
                        'nama_lengkap' => $user->nama_lengkap,
                        'role'         => $user->role,
                        'logged_in'    => TRUE
                    ]);
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Email atau password salah.');
                }
            }
        }
        $this->load->view('auth/login');
    }

    /**
     * Halaman registrasi untuk pelanggan baru.
     */
    public function register()
    {
        // Jika sudah login, redirect
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]');

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                    'email'        => $this->input->post('email', TRUE),
                    'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'no_telepon'   => $this->input->post('no_telepon', TRUE),
                    'role'         => 'pelanggan'
                ];
                $this->User_model->insert($data);
                $this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login.');
                redirect('auth/login');
                return;
            }
        }
        $this->load->view('auth/register');
    }

    /**
     * Logout dan hapus session.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
