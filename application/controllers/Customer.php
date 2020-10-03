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

	
	public function create()
	{
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
				'intUserID' => "1",
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

	public function update($id)
    {

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
                    'intEnteredBy' => "1",
				);

				$insertCustomerHitory = $this->model_customer->insertCustomerHitory($intEnteredBy,$id);
				
				$update = $this->model_customer->update($data, $id);
				
                if ($update == true && $insertCustomerHitory ==true) {
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
		$intCustomerID = $this->input->post('intCustomerID');
		$response = array();
		if ($intCustomerID) {

			$result = $this->model_customer->chkexists($intCustomerID);

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
