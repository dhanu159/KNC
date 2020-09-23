<?php
class Dashboard extends CI_Controller
{
	public function index()
	{
		$this->load->view('partials/header');
		$this->load->view('dashboard');
		$this->load->view('partials/footer');
	}
}
