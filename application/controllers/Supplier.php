<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->model('model_supplier');
		$this->load->model('model_groups');

		$user_group_data = $this->model_groups->getUserGroupData();
        $this->data['user_groups_data'] = $user_group_data;
	}
	public function index()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewSupplier', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$this->load->view('partials/header');
		$this->load->view('supplier/manageSupplier',$this->data);
		$this->load->view('partials/footer');


	}

	public function fetchSupplierDataById($id)
	{
		if ($id) {
			$data = $this->model_supplier->getSupplierData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function create()
	{

		if (!$this->isAdmin) {
			if (!in_array('createSupplier', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$response = array();

		$this->form_validation->set_rules('supplier_name', 'supplier name', 'trim|required');
		$this->form_validation->set_rules('address', 'address', 'trim|required');
		$this->form_validation->set_rules('contact_no', 'contact no', 'required|min_length[10]|max_length[10]');
		// $this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcSupplierName' => $this->input->post('supplier_name'),
				'vcAddress' => $this->input->post('address'),
				'vcContactNo' => $this->input->post('contact_no'),
				// 'IsActive' => $this->input->post('active'),
				'intUserID' => $this->session->userdata('user_id'),
			);
			$create = $this->model_supplier->create($data);
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

	public function fetchSupplierData()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewSupplier', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$result = array('data' => array());

		$data = $this->model_supplier->getSupplierData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			
            if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editSupplier(' . $value['intSupplierID'] . ')" data-toggle="modal" data-target="#editSupplierModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeSupplier(' . $value['intSupplierID'] . ')" data-toggle="modal" data-target="#removeSupplierModal"><i class="fa fa-trash"></i></button>';

            } else {
                if (in_array('editSupplier', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editSupplier(' . $value['intSupplierID'] . ')" data-toggle="modal" data-target="#editSupplierModal"><i class="fas fa-edit"></i></button>';
                }

                if (in_array('deleteSupplier', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeSupplier(' . $value['intSupplierID'] . ')" data-toggle="modal" data-target="#removeSupplierModal"><i class="fa fa-trash"></i></button>';

                }
            }




			$result['data'][$key] = array(
				$value['vcSupplierName'],
				$value['vcAddress'],
				$value['vcContactNo'],
				$buttons
			);
		} 

		echo json_encode($result);
	}

	public function update($id)
	{
		if (!$this->isAdmin) {
			if (!in_array('editSupplier', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_supplier_name', 'supplier name', 'trim|required');
			$this->form_validation->set_rules('edit_address', 'address', 'trim|required');
			$this->form_validation->set_rules('edit_contact_no', 'contact no', 'required|min_length[10]|max_length[10]');


			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'vcSupplierName' => $this->input->post('edit_supplier_name'),
					'vcAddress' => $this->input->post('edit_address'),
					'vcContactNo' => $this->input->post('edit_contact_no'),
				);
				$currentRV = '';
				$currentRV =  $this->input->post('edit_rv');

				$previousRV = $this->model_supplier->chkRv($id);

				if ($previousRV[0]['rv'] != $currentRV) {
					$response['success'] = false;
					$response['messages'] = 'Another user tries to edit this supplier details, please refresh the page and try again !';
				} else {

					$update = $this->model_supplier->update($data, $id);

					if ($update == true) {
						$response['success'] = true;
						$response['messages'] = 'Succesfully updated !';
					} else {
						$response['success'] = false;
						$response['messages'] = 'Error in the database while updated the brand information';
					}
				}
			} else {
				$response['success'] = false;
				foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
				}
			}
		}

		echo json_encode($response);
	}

	public function remove($intSupplierID = null)
	{
		if (!$this->isAdmin) {
			if (!in_array('deleteSupplier', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$intSupplierID = $this->input->post('intSupplierID');
		$response = array();
		if ($intSupplierID) {

			$result = $this->model_supplier->chkexists($intSupplierID);

			if ($result <> '') {
				if ($result[0]['value'] == 1) {
					$response['success'] = false;
					$response['messages'] = "Record already received for the system, can't remove this supplier !";
				} else {
					$delete = $this->model_supplier->remove($intSupplierID);
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
