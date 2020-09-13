<?php
class Customer extends CI_Controller
{
	public function index()
	{
		$this->load->view('partials/header');
		$this->load->view('customer/manageCustomer');
		$this->load->view('partials/footer');
	}

	public function addCustomer()
	{
		$this->load->view('partials/header');
		$this->load->view('customer/addCustomer');
		$this->load->view('partials/footer');
	}
}
