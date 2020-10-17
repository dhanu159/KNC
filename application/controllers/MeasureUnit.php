<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MeasureUnit extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_measureunit');
    }
    
    public function MeasureUnit()
	{
		$this->load->view('partials/header');
		$this->load->view('measureunit/manageMeasureUnit');
		$this->load->view('partials/footer');
    }
    
    public function create()
    {
        $response = array();

        $this->form_validation->set_rules('Unit_name', 'Measure Unit Name', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'vcMeasureUnit	' => $this->input->post('Unit_name'),
           
            );
            $create = $this->model_measureunit->create($data);
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Succesfully created';
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

    public function fetchMeasureUnitData()
    {

        $result = array('data' => array());

        $data = $this->model_measureunit->getMeasureUnitData(null,true);
        foreach ($data as $key => $value) {

            // button
            $buttons = '';


            $buttons .= '<button type="button" class="btn btn-default" onclick="editMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#editMeasureUnitModal"><i class="fas fa-edit"></i></button>';

            $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#removeMeasureUnithModal"><i class="fa fa-trash"></i></button>';

            //$status = ($value['IsActive'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $result['data'][$key] = array(
                $value['vcMeasureUnit'],
                $buttons
            );
        } // /foreach

        echo json_encode($result);
    }



    
}