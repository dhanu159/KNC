<?php

class Utilities extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'User Group';
        $this->load->model('model_groups');
        $this->load->model('model_measureunit');

        
        $user_group_data = $this->model_groups->getUserGroupData();
        $this->data['user_groups_data'] = $user_group_data;
    }


    // Manage User Group

    public function UserGroup()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


        $this->render_template('utilities/userGroup', $this->data);
    }

    public function createUserGroup()
    {
        if (!$this->isAdmin) {
            if (!in_array('createUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->form_validation->set_rules('group_name', 'Group name', 'trim|required');


        if ($this->form_validation->run() == TRUE) {
            // true case
            $permission = serialize($this->input->post('permission'));

            $data = array(
                'vcGroupName' => $this->input->post('group_name'),
                'vcPermission' => $permission
            );

            $create = $this->model_groups->createUserGroup($data);

            if ($create == true) {
                $this->session->set_flashdata('success', 'Successfully Created !');
                redirect('utilities/userGroup/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error Occurred !!');
                redirect('utilities/userGroup', 'refresh');
            }
        } else {
            $this->session->set_flashdata('errors', validation_errors());
            // false case
            // $this->render_template('utilities/userGroup', $this->data);
            redirect('utilities/userGroup', 'refresh');
        }
    }

    // Manage Measure Units

    public function MeasureUnit()
	{
		// $this->load->view('partials/header');
		// $this->load->view('measureunit/manageMeasureUnit');
        // $this->load->view('partials/footer');

        $this->render_template('measureunit/manageMeasureUnit', null);

    }

    public function createMeasureUnit()
    {
        $response = array();

        $this->form_validation->set_rules('Unit_name', 'Measure Unit Name', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'vcMeasureUnit' => $this->input->post('Unit_name'),
           
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


            $buttons .= '<button type="button" class="btn btn-default" onclick="editBranch(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#editBranchModal"><i class="fas fa-edit"></i></button>';

            $buttons .= ' <button type="button" class="btn btn-default" onclick="removeBranch(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#removeBranchModal"><i class="fa fa-trash"></i></button>';

            //$status = ($value['IsActive'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $result['data'][$key] = array(
                $value['vcMeasureUnit'],
                $buttons
            );
        } // /foreach

        echo json_encode($result);
    }
    
}
