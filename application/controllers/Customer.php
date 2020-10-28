<?php
class Customer extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_customer');
		$this->load->model('model_groups');

		$user_group_data = $this->model_groups->getUserGroupData();
        $this->data['user_groups_data'] = $user_group_data;
	}

	public function index()
	{
		if (!$this->isAdmin) {
            if (!in_array('viewCustomer', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
		$this->load->view('partials/header');
		$this->load->view('customer/manageCustomer',$this->data);
		$this->load->view('partials/footer');
	}


	public function create()
	{
		if (!$this->isAdmin) {
			if (!in_array('createCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		$this->form_validation->set_rules('customer_name', 'customer name', 'trim|required');
		$this->form_validation->set_rules('address', 'address', 'trim|required');
		$this->form_validation->set_rules('contact_no_1', 'contact no', 'required|min_length[10]|max_length[10]');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcCustomerName' => $this->input->post('customer_name'),
				'vcAddress' => $this->input->post('address'),
				'vcContactNo1' => $this->input->post('contact_no_1'),
				'vcContactNo2' => $this->input->post('contact_no_2'),
				'decCreditLimit' => $this->input->post('credit_limit'),
				'intUserID' => $this->session->userdata('user_id'),
			);
			$create = $this->model_customer->create($data);
			if ($create == true) {
				$response['success'] = true;
				$response['messages'] = 'Succesfully created !';
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error in the database while creating the brand information';
			}
		} else {
			$response['success'] = false;
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}

		echo json_encode($response);
	}

	public function fetchCustomerDataById($id)
	{
		if ($id) {
			$data = $this->model_customer->getCustomerData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function fetchCustomerData()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$result = array('data' => array());

		$data = $this->model_customer->getCustomerData();
		foreach ($data as $key => $value) {

			$buttons = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#removeCustomerModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editCustomer', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#editCustomerModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteCustomer', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeCustomer(' . $value['intCustomerID'] . ')" data-toggle="modal" data-target="#removeCustomerModal"><i class="fa fa-trash"></i></button>';
				}
			}




			$result['data'][$key] = array(
				$value['vcCustomerName'],
				$value['vcAddress'],
				$value['vcContactNo1'],
				$value['vcContactNo2'],
				$value['decCreditLimit'],
				$buttons
			);
		}

		echo json_encode($result);
	}

	public function update($id)
	{

		if (!$this->isAdmin) {
			if (!in_array('editCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_customer_name', 'branch name', 'trim|required');
			$this->form_validation->set_rules('edit_address', 'address', 'trim|required');
			$this->form_validation->set_rules('edit_contact_no_1', 'contact no', 'required|min_length[10]|max_length[10]');


			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'vcCustomerName' => $this->input->post('edit_customer_name'),
					'vcAddress' => $this->input->post('edit_address'),
					'vcContactNo1' => $this->input->post('edit_contact_no_1'),
					'vcContactNo2' => $this->input->post('edit_contact_no_2'),
					'decCreditLimit' => $this->input->post('edit_credit_limit'),
				);

				$intEnteredBy = array(
					'intEnteredBy' => $this->session->userdata('user_id'),
				);

				$insertCustomerHitory = $this->model_customer->insertCustomerHitory($intEnteredBy, $id);

				$update = $this->model_customer->update($data, $id);

				if ($update == true && $insertCustomerHitory == true) {
					$response['success'] = true;
					$response['messages'] = 'Succesfully updated';
				} else {
					$response['success'] = false;
					$response['messages'] = 'Error in the database while updated the brand information';
				}
			} else {
				$response['success'] = false;
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	public function remove($intCustomerID = null)
	{

		if (!$this->isAdmin) {
			if (!in_array('deleteCustomer', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$intCustomerID = $this->input->post('intCustomerID');
		$response = array();
		if ($intCustomerID) {

		//	$result = $this->model_customer->chkexists($intCustomerID);
		$result = '';
			if ($result <> '') {
				if ($result[0]['value'] == 1) {
					$response['success'] = false;
					$response['messages'] = "Record already received for the system, can't remove this customer !";
				} else {
					$delete = $this->model_customer->remove($intCustomerID);
					if ($delete == true) {
						$response['success'] = true;
						$response['messages'] = "Successfully removed !";
					} else {
						$response['success'] = false;
						$response['messages'] = "Error in the database while removing the Supplier information";
					}
				}
			}
			echo json_encode($response);
		}
	}
}
