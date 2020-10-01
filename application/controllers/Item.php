<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_item');
		$this->load->model('Model_measureunit');
    }
    
    public function index()
	{
		$data["measureUnit"] = $this->Model_measureunit->getMeasureUnitData(null,false);

		$this->load->view('partials/header');
		$this->load->view('item/manageItem',$data);
		$this->load->view('partials/footer');
	}
	

	public function create()
	{
		$response = array();

		$this->form_validation->set_rules('Item_name', 'Item Name', 'trim|required');
		$this->form_validation->set_rules('measure_unit', 'Measure Unit', 'trim|required');
		//$this->form_validation->set_rules('re_order', 'contact no', 'required|min_length[10]|max_length[10]');
		// $this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'vcItemName' => $this->input->post('Item_name'),
				'intMeasureUnitID' => $this->input->post('measure_unit'),
				'decReOrderLevel' => $this->input->post('re_order'),
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


}