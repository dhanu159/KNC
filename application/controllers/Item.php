<?php
class Item extends CI_Controller
{
	public function index()
	{
		$this->load->view('partials/header');
		$this->load->view('item/manageItem');
		$this->load->view('partials/footer');
	}

	public function addItem()
	{
		$this->load->view('partials/header');
		$this->load->view('item/addItem');
		$this->load->view('partials/footer');
	}
}
