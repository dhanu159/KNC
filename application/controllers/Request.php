<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('model_branch');
        $this->load->model('model_groups');
        $this->load->model('model_item');
        $this->load->model('model_measureunit');
        $this->load->model('model_request');

        $user_group_data = $this->model_groups->getUserGroupData();
        $this->data['user_groups_data'] = $user_group_data;
    }

    public function RequestItem()
    {
        if (!$this->isAdmin) {
            if (!in_array('viewRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $item_data = $this->model_item->getOnlyFinishedItemData();
        $this->data['item_data'] = $item_data;

        $this->load->view('partials/header');
        $this->load->view('request/requestItem', $this->data);
        $this->load->view('partials/footer');
    }

    public function getRequestFinishedByItemID($ItemID)
    {
            $data = $this->model_request->getRequestFinishedByItemID($ItemID);

            echo json_encode($data);
    }

    public function SaveRequestItem()
    {
        if (!$this->isAdmin) {
            if (!in_array('createRequestItem', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }
        $response = array();

        // $this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
        // $this->form_validation->set_rules('invoice_no', 'invoice no', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $result = $this->model_request->SaveRequestItem();
            if ($result == true) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the GRN idetails. Please contact service provider.';
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