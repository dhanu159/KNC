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

    public function SaveRequest()
    {

    }


}