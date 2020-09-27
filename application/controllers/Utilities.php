<?php

class Utilities extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'User Group';
        $this->load->model('model_groups');
    }
    public function UserGroup()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $user_group_data = $this->model_groups->getUserGroupData();
        $this->data['user_groups_data'] = $user_group_data;

        $this->render_template('utilities/userGroup', $this->data);
    }

    public function createUserGroup()
    {
        if (!$this->isAdmin) {
            if (!in_array('createUserGroup', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->form_validation->set_rules('group_name', 'Group name', 'required');

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
            // false case
            $this->render_template('utilities/userGroup', $this->data);
        }
    }
}
