<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_item');
		$this->load->model('Model_measureunit');

		// $item_data = $this->model_item->getItemData();
		// $this->data['item_data'] = $item_data;
	
		
    }
    
    public function index()
	{
		$data["measureUnit"] = $this->Model_measureunit->getMeasureUnitData(null,false);
		$data["itemType"] = $this->Model_measureunit->getItemTypeData(null,false);

		$this->load->view('partials/header');
		$this->load->view('item/manageItem',$data);
		$this->load->view('partials/footer');
		
	}

	public function fetchItemDataById($id)
	{
		if ($id) {
			$data = $this->model_item->getItemData($id);
			echo json_encode($data);
		}

		return false;
	}
	
	public function fetchItemData()
	{
		$result = array('data' => array());

		$data = $this->model_item->getItemData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';


			$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';

			$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';

			//$status = ($value['IsActive'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['vcItemName'],
				$value['vcMeasureUnit'],
				$value['vcItemTypeName'],
				$value['decStockInHand'],
				$value['decReOrderLevel'],
				$value['decUnitPrice'],
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function remove($intItemID = null)
	{
		$inItemID = $this->input->post('intItemID');
		$response = array();
		if ($inItemID) {

			$result = $this->model_item->chkexists($inItemID);

			if ($result <> '') {
				if ($result[0]['value'] == 1) {
					$response['success'] = false;
					$response['messages'] = "Record already received for the system, can't remove this supplier !";
				} else {
					$delete = $this->model_item->remove($inItemID);
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

	public function create()
	{
		$response = array();

		$this->form_validation->set_rules('Item_name', 'Item Name', 'trim|required');
		$this->form_validation->set_rules('measure_unit', 'Measure Unit', 'trim|required');
		$this->form_validation->set_rules('item_type', 'Item Type', 'trim|required');
		// $this->form_validation->set_rules('measure_unit', 'Measure Unit', 'required|callback_select_validate'); // Validating select option field.

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcItemName' => $this->input->post('Item_name'),
				'intMeasureUnitID' => $this->input->post('measure_unit'),
				'decReOrderLevel' => $this->input->post('re_order'),
				'intItemTypeID'=> $this->input->post('item_type'),
				'decUnitPrice'=> $this->input->post('unit_price'),
				'intUserID' => "1",
			);
			$create = $this->model_item->create($data);
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

	public function update($id)
	{

		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_item_name', 'Item Name', 'trim|required');
			$this->form_validation->set_rules('edit_measure_unit', 'Measure Unit', 'trim|required');
			$this->form_validation->set_rules('edit_item_type', 'Item Type', 'trim|required');
			

			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'vcItemName' => $this->input->post('edit_item_name'),
					'intMeasureUnitID' => $this->input->post('edit_measure_unit'),
					'decReOrderLevel' => $this->input->post('edit_re_order'),
					'intItemTypeID'=> $this->input->post('edit_item_type'),
					'decUnitPrice'=> $this->input->post('edit_unit_price'),
					'intUserID' => "1",
				);
				$currentRV = '';
				$currentRV =  $this->input->post('edit_rv');

				$previousRV = $this->model_item->chkRv($id);

				if ($previousRV[0]['rv'] != $currentRV) {
					$response['success'] = false;
					$response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
				} else {

					$update = $this->model_item->update($data, $id);

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


}