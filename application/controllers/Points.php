<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Points extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'owner') {
            show_error('Forbidden', 403);
        }
    }

    public function index()
    {
        $this->authorize();
        $this->load->view('points/rules');
    }
}
?>
