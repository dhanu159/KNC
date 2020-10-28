<?php
defined('BASEPATH') or exit('No direct script access allowed');
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

    public function fetchUserGroupData()
    {

        if (!$this->isAdmin) {
            if (!in_array('viewUserGroupData', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_groups->getUserGroupData();
        foreach ($data as $key => $value) {


            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#editUserGroupModal"><i class="fas fa-edit"></i></button>';
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#removeUserGroupModal"><i class="fa fa-trash"></i></button>';
            } else {
                if (in_array('editUserGroup', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#editUserGroupModal"><i class="fas fa-edit"></i></button>';
                }

                if (in_array('deleteUserGroup', $this->permission)) {
                    $buttons .= ' <button type="button" class="btn btn-default" onclick="removeUserGroup(' . $value['intUserGroupID'] . ')" data-toggle="modal" data-target="#removeUserGroupModal"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcGroupName'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function fetchUserGroupDataById($id)
    {
        if ($id) {
            $data = $this->model_groups->getUserGroupData($id);
            echo json_encode($data);
        }

        return false;
    }


    public function removeUserGroup($intUserGroupID = null)
    {
        if (!$this->isAdmin) {
            if (!in_array('deleteUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $intUserGroupID = $this->input->post('intUserGroupID');
        $response = array();
        if ($intUserGroupID) {

            $result = $this->model_groups->userGroupChkExists($intUserGroupID);

            if ($result <> '') {
                if ($result[0]['value'] == 1) {
                    $response['success'] = false;
                    $response['messages'] = "Record already received for the system, can't remove this supplier !";
                } else {
                    $delete = $this->model_groups->removeUserGroup($intUserGroupID);
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

        $response = array();

        $this->form_validation->set_rules('group_name', 'Group name', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $permission = serialize($this->input->post('permission'));

            $data = array(
                'vcGroupName' => $this->input->post('group_name'),
                'vcPermission' => $permission
            );


            $create = $this->model_groups->createUserGroup($data);
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

    public function updateUserGroup($id)
    {

        if (!$this->isAdmin) {
            if (!in_array('editUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_group_name', 'group name', 'trim|required');

            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {

                $permission = serialize($this->input->post('permission'));

                $data = array(
                    'vcGroupName' => $this->input->post('edit_group_name'),
                    'vcPermission' => $permission
                );
                $update = $this->model_groups->updateUserGroup($data, $id);
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

    public function MeasureUnit()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        // $this->load->view('partials/header');
        // $this->load->view('measureunit/manageMeasureUnit');
        // $this->load->view('partials/footer');

        $this->render_template('measureunit/manageMeasureUnit', $this->data);
    }

    public function fetchMeasureUnitDataById($id)
    {
        if ($id) {
            $data = $this->model_measureunit->getMeasureUnitData($id, true);
            echo json_encode($data);
        }

        return false;
    }

    public function updateMeasureUnit($id)
    {

        if (!$this->isAdmin) {
            if (!in_array('editMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_unit_name', 'unit name', 'trim|required');


            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'vcMeasureUnit' => $this->input->post('edit_unit_name'),
                );
                $update = $this->model_measureunit->update($data, $id);
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

    public function createMeasureUnit()
    {
        if (!$this->isAdmin) {
            if (!in_array('createMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


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

        if (!$this->isAdmin) {
            if (!in_array('viewMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());

        $data = $this->model_measureunit->getMeasureUnitData(null, true);
        foreach ($data as $key => $value) {

            // button
            $buttons = '';

            if ($this->isAdmin) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#editMeasureUnitModal"><i class="fas fa-edit"></i></button>';
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#removeMeasureUnithModal"><i class="fa fa-trash"></i></button>';
            } else {
                if (in_array('editMeasureUnit', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="editMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#editMeasureUnitModal"><i class="fas fa-edit"></i></button>';
                }

                if (in_array('deleteMeasureUnit', $this->permission)) {
                    $buttons .= ' <button type="button" class="btn btn-default" onclick="removeMeasureUnit(' . $value['intMeasureUnitID'] . ')" data-toggle="modal" data-target="#removeMeasureUnithModal"><i class="fa fa-trash"></i></button>';
                }
            }

            $result['data'][$key] = array(
                $value['vcMeasureUnit'],
                $buttons
            );
        }

        echo json_encode($result);
    }

    public function removeMeasureUnit($intMeasureUnitID = null)
    {

        if (!$this->isAdmin) {
            if (!in_array('deleteMeasureUnit', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }


        $intMeasureUnitID = $this->input->post('intMeasureUnitID');
        $response = array();
        if ($intMeasureUnitID) {

            $result = $this->model_measureunit->chkexists($intMeasureUnitID);

            if ($result <> '') {
                if ($result[0]['value'] == 1) {
                    $response['success'] = false;
                    $response['messages'] = "Record already received for the system, can't remove this supplier !";
                } else {
                    $delete = $this->model_measureunit->remove($intMeasureUnitID);
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
