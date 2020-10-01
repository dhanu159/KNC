<?php
class Customer extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_customer');
	}

	public function index()
	{
		$this->load->view('partials/header');
		$this->load->view('customer/manageCustomer');
		$this->load->view('partials/footer');
	}

	public function fetchCustomerData()
	{
		$result = array('data' => array());

		$data = $this->model_customer->getCustomerData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';


			$buttons .= '<button type="button" class="btn btn-default" onclick="editCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';

			$buttons .= ' <button type="button" class="btn btn-default" onclick="removeCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#removeCustomerModal"><i class="fa fa-trash"></i></button>';

			//$status = ($value['IsActive'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['vcCustomerName'],
				$value['vcAddress'],
				$value['vcContactNo1'],
				$value['vcContactNo2'],
				$value['decCreditLimit'],
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

}
