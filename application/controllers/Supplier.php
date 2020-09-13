<?php
class Supplier extends CI_Controller
{
	public function index()
	{
		$this->load->view('partials/header');
		$this->load->view('supplier/manageSupplier');
		$this->load->view('partials/footer');
	}

	public function addSupplier()
	{
		$this->load->view('partials/header');
		$this->load->view('supplier/addSupplier');
		$this->load->view('partials/footer');
	}
}
