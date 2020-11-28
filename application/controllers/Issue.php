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
        $this->load->model('model_grn');
        $this->load->model('model_measureunit');
    }

    //-----------------------------------
    // Create Issue
    //-----------------------------------

    public function CreateIssue()
    {
        if (!$this->isAdmin) {
            if (!in_array('createIssue',$this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $supplier_data = $this->model_supplier->getSupplierData();
        $item_data = $this->model_item->getOnlyRawItemData();

        $this->data['supplier_data'] = $supplier_data;
        $this->data['item_data'] = $item_data;

        $this->render_template('Issue/createIssue', 'Create Issue',  $this->data);
    }
}