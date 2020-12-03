<?php

class Issue extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'Issue';
        $this->load->model('model_supplier');
        $this->load->model('model_item');
        $this->load->model('model_measureunit');
        $this->load->model('model_customer');
        $this->load->model('model_issue');
    }

    //-----------------------------------
    // Create Issue
    //-----------------------------------

    public function CreateIssue()
    {
        if (!$this->isAdmin) {
            if (!in_array('createIssue', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $customer_data = $this->model_customer->getCustomerData();
        $item_data = $this->model_item->getOnlyFinishItemData();

        $this->data['customer_data'] = $customer_data;
        $this->data['item_data'] = $item_data;

        $this->render_template('Issue/createIssue', 'Create Issue',  $this->data);
    }


    public function SaveIssue()
    {
        if (!$this->isAdmin) {
            if (!in_array('createIssue', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = $this->model_issue->saveIssue();

        echo json_encode($response);
    }
}
