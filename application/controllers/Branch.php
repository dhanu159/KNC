<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Branch extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('model_branch');
    }
    public function index()
    {
        $this->load->view('partials/header');
        $this->load->view('branch/manageBranch');
        $this->load->view('partials/footer');
    }

    public function fetchBranchDataById($id)
    {
        if ($id) {
            $data = $this->model_branch->getBranchData($id);
            echo json_encode($data);
        }

        return false;
    }

    public function create()
    {
        $response = array();

        $this->form_validation->set_rules('branch_name', 'branch name', 'trim|required');
        $this->form_validation->set_rules('address', 'address', 'trim|required');
        $this->form_validation->set_rules('contact_no', 'contact no', 'required|min_length[10]|max_length[10]');
        // $this->form_validation->set_rules('active', 'Active', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'vcBranchName' => $this->input->post('branch_name'),
                'vcAddress' => $this->input->post('address'),
                'vcContactNo' => $this->input->post('contact_no'),
                // 'IsActive' => $this->input->post('active'),
                'intUserID' => "1",
            );
            $create = $this->model_branch->create($data);
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

    public function fetchBranchData()
    {

        $result = array('data' => array());

        $data = $this->model_branch->getBranchData();
        foreach ($data as $key => $value) {

            // button
            $buttons = '';


            $buttons .= '<button type="button" class="btn btn-default" onclick="editBranch(' . $value['intBranchID'] . ')" data-toggle="modal" data-target="#editBranchModal"><i class="fas fa-edit"></i></button>';

            $buttons .= ' <button type="button" class="btn btn-default" onclick="removeBranch(' . $value['intBranchID'] . ')" data-toggle="modal" data-target="#removeBranchModal"><i class="fa fa-trash"></i></button>';

            //$status = ($value['IsActive'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $result['data'][$key] = array(
                $value['vcBranchName'],
                $value['vcAddress'],
                $value['vcContactNo'],
                $buttons
            );
        } // /foreach

        echo json_encode($result);
    }

    public function update($id)
    {

        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_branch_name', 'branch name', 'trim|required');
            $this->form_validation->set_rules('edit_address', 'address', 'trim|required');
            $this->form_validation->set_rules('edit_contact_no', 'contact no', 'required|min_length[10]|max_length[10]');


            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'vcBranchName' => $this->input->post('edit_branch_name'),
                    'vcAddress' => $this->input->post('edit_address'),
                    'vcContactNo' => $this->input->post('edit_contact_no'),
                );
                $update = $this->model_branch->update($data, $id);
                if ($update == true) {
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

    public function remove($intbranchID = null)
    {
        $intbranchID = $this->input->post('intBranchID');
        $response = array();
        if ($intbranchID) {

            $delete = $this->model_branch->remove($intbranchID);

            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed !";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the brand information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }
        echo json_encode($response);
    }
}
