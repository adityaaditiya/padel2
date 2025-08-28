<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_status extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Store_status_model');
    }

    /**
     * Close the store by advancing the store date by one day.
     */
    public function close()
    {
        $this->Store_status_model->close_store();
        redirect($_SERVER['HTTP_REFERER'] ?? 'dashboard');
    }
}
