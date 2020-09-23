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

		// $measureunit = $this->Model_measureunit->getMeasureUnitData(1);
		// $this->data['measureunit'] = $measureunit;

		
		$this->load->view('partials/header');
		$this->load->view('item/manageItem',$data);
		$this->load->view('partials/footer');

		
		//$this->render_template('item/manageItem', $this->data);
	}
	
	public function render_template($page = null, $data = array())
	{
		$this->load->view($page, $data);
	

	}

	public function create()
	{
		

	}


}