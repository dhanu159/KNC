<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->model('model_item');
		$this->load->model('Model_measureunit');
		$this->load->model('model_groups');
		// $item_data = $this->model_item->getItemData();
		// $this->data['item_data'] = $item_data;
		// $user_group_data = $this->model_groups->getUserGroupData();
		// $this->data['user_groups_data'] = $user_group_data;

	}

	public function index()
	{
		if (!$this->isAdmin) {
			if (!in_array('viewItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$this->data["measureUnit"] = $this->Model_measureunit->getMeasureUnitData(null, false);
		$this->data["itemType"] = $this->Model_measureunit->getItemTypeData(null, false);
		$this->data["itemTypeAll"] = $this->Model_measureunit->getItemTypeData(null, true);
		$this->data["itemColourData"] = $this->model_item->getItemColourData();

		$this->render_template('item/manageItem', 'Manage Item', $this->data);
	}

	public function manageItemColour()
	{
		if (!$this->isAdmin) {
			if (!in_array('viewBranch', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$this->render_template('item/manageItemColour', 'Manage Item Colour', $this->data);
	}

	public function fetchItemColourData()
	{
		if (!$this->isAdmin) {
			if (!in_array('viewItemColourData', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$result = array('data' => array());

		$data = $this->model_item->fetchItemColourData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editColour(' . $value['intItemColourID'] . ')" data-toggle="modal" data-target="#editColourModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeColour(' . $value['intItemColourID'] . ')" data-toggle="modal" data-target="#removeColourModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editItemColourData', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editColour(' . $value['intItemColourID'] . ')" data-toggle="modal" data-target="#editColourModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteItemColourData', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeColour(' . $value['intItemColourID'] . ')" data-toggle="modal" data-target="#removeColourModal"><i class="fa fa-trash"></i></button>';
				}
			}

			$result['data'][$key] = array(
				$value['vcColourName'],
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function fetchColourDataById($id)
	{
		if ($id) {
			$data = $this->model_item->fetchItemColourData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function createItemColour()
	{

		$response = array();

		$this->form_validation->set_rules('colour_name', 'Colour Name', 'trim|required|is_unique[itemcolour.vcColourName]');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcColourName' => $this->input->post('colour_name'),
			);
			$create = $this->model_item->createItemColour($data);
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

	public function updateItemColour($id)
	{
		$response = array();

		$this->form_validation->set_rules('edit_Colour_name', 'Colour Name', 'trim|required|is_unique[itemcolour.vcColourName]');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcColourName' => $this->input->post('edit_Colour_name'),
			);
			$update = $this->model_item->updateItemColour($data, $id);
			if ($update == true) {
				$response['success'] = true;
				$response['messages'] = 'Succesfully Updated !';
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

	public function removeItemColour()
	{
		$response = array();

		$id = $this->input->post('intColourID');

		$delete = $this->model_item->removeItemColour($id);
		if ($delete == true) {
			$response['success'] = true;
			$response['messages'] = 'Succesfully Deleted !';
		} else {
			$response['success'] = false;
			$response['messages'] = 'Error in the database while creating the brand information';
		}


		echo json_encode($response);
	}

	public function fetchItemDataById($id)
	{
		if ($id) {
			$data = $this->model_item->getItemData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function fetchItemDataByItemTypeID($itemTypeID)
	{

		$data = $this->model_item->getItemDataByItemTypeID($itemTypeID);
		foreach ($data as $key => $value) {

			// button
			$buttons = '';
			$ReorderLevl = '';
			$UnitPrice = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editItem', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteItem', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
				}
			}

			$ReorderLevl =  '<p class="text-right">' . $value['decReOrderLevel'] . '</p>';
			$UnitPrice =  '<p class="text-right">' . $value['decUnitPrice'] . '</p>';

			$result['data'][$key] = array(
				$value['vcItemName'],
				$value['vcMeasureUnit'],
				$value['vcItemTypeName'],
				$value['decStockInHand'],
				$ReorderLevl,
				$UnitPrice,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function fetchItemDetailsByCustomerID($ItemID, $customerID)
	{
		$data = $this->model_item->getItemDetailsByCustomerID($ItemID, $customerID);
		echo json_encode($data);
	}

	public function fetchItemData()
	{

		if (!$this->isAdmin) {
			if (!in_array('viewItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$result = array('data' => array());

		$data = $this->model_item->getItemData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';
			$ReorderLevl = '';
			$UnitPrice = '';

			if ($this->isAdmin) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
			} else {
				if (in_array('editItem', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-default" onclick="editItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#editItemModal"><i class="fas fa-edit"></i></button>';
				}

				if (in_array('deleteItem', $this->permission)) {
					$buttons .= ' <button type="button" class="btn btn-default" onclick="removeItem(' . $value['intItemID'] . ')" data-toggle="modal" data-target="#removeItemModal"><i class="fa fa-trash"></i></button>';
				}
			}

			$ReorderLevl =  '<p class="text-right">' . $value['decReOrderLevel'] . '</p>';
			$UnitPrice =  '<p class="text-right">' . $value['decUnitPrice'] . '</p>';

			$result['data'][$key] = array(
				$value['vcItemName'],
				$value['vcMeasureUnit'],
				$value['vcItemTypeName'],
				$value['decStockInHand'],
				$ReorderLevl,
				$UnitPrice,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function remove($intItemID = null)
	{
		if (!$this->isAdmin) {
			if (!in_array('deleteItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$inItemID = $this->input->post('intItemID');
		$response = array();
		if ($inItemID) {

			$result = $this->model_item->chkexists($inItemID);

			if ($result <> '') {
				if ($result[0]['value'] == 1) {
					$response['success'] = false;
					$response['messages'] = "Record already received for the system, can't remove this Item !";
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

		if (!$this->isAdmin) {
			if (!in_array('createItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}


		$response = array();

		$this->form_validation->set_rules('Item_name', 'Item Name', 'trim|required|is_unique[item.vcItemName]');
		$this->form_validation->set_rules('measure_unit', 'Measure Unit', 'trim|required');
		$this->form_validation->set_rules('item_type', 'Item Type', 'trim|required');
		if ($this->input->post('edit_item_type') == 2) {
			$this->form_validation->set_rules('edit_unit_price', 'Measure Unit', 'trim|required');
		}

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {

			$ItemName = $this->input->post('ItemName');

			$data = array(
				'vcItemName' => $ItemName,
				'intMeasureUnitID' => $this->input->post('measure_unit'),
				'decReOrderLevel' => $this->input->post('re_order'),
				'intItemTypeID' => $this->input->post('item_type'),
				'decUnitPrice' => $this->input->post('unit_price'),
				'intUserID' => $this->session->userdata('user_id'),
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

		if (!$this->isAdmin) {
			if (!in_array('editItem', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_item_name', 'Item Name', 'trim|required');
			$this->form_validation->set_rules('edit_measure_unit', 'Measure Unit', 'trim|required');
			$this->form_validation->set_rules('edit_item_type', 'Item Type', 'trim|required');
			if ($this->input->post('edit_item_type') == 2) {
				$this->form_validation->set_rules('edit_unit_price', 'Measure Unit', 'trim|required');
			}



			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			$ItemName = $this->input->post('ItemName');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'vcItemName' => $ItemName,
					'intMeasureUnitID' => $this->input->post('edit_measure_unit'),
					'decReOrderLevel' => $this->input->post('edit_re_order'),
					'intItemTypeID' => $this->input->post('edit_item_type'),
					'decUnitPrice' => $this->input->post('edit_unit_price'),
					'intUserID' => $this->session->userdata('user_id'),
				);
				$currentRV = '';
				$currentRV =  $this->input->post('edit_rv');

				$previousRV = $this->model_item->chkRv($id);

				if ($previousRV['rv'] != $currentRV) {
					$response['success'] = false;
					$response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
				} else {

					$intEnteredBy = array(
						'intEnteredBy' => $this->session->userdata('user_id'),
					);

					$insertItemHitory = $this->model_item->insertItemHitory($intEnteredBy, $id);
					$update = $this->model_item->update($data, $id);

					if ($update == true && $insertItemHitory == true) {
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
