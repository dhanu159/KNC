<?php

class GRN extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'Goods Receive Note';
        $this->load->model('model_supplier');
        $this->load->model('model_item');
        $this->load->model('model_grn');
        $this->load->model('model_measureunit');
    }

    //-----------------------------------
    // Create GRN
    //-----------------------------------

    public function CreateGRN()
    {
        if (!$this->isAdmin) {
            if (!in_array('createGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $supplier_data = $this->model_supplier->getSupplierData(null, 1);
        $item_data = $this->model_item->getOnlyRawItemData();

        $this->data['supplier_data'] = $supplier_data;
        $this->data['item_data'] = $item_data;

        $this->render_template('GRN/createGRN', $this->data);
    }

    public function SaveGRN()
    {
        if (!$this->isAdmin) {
            if (!in_array('createGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $response = array();

        $this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
        $this->form_validation->set_rules('invoice_no', 'invoice no', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $result = $this->model_grn->saveGRN();
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

    public function getMeasureUnitByItemID($ItemID)
    {
        if ($ItemID) {
            $data = $this->model_measureunit->getMeasureUnitData($ItemID, true);
            echo json_encode($data);
        }

        return false;
    }

    //-----------------------------------
    // View GRN
    //-----------------------------------

    public function ViewGRN(){
        if (!$this->isAdmin) {
            if (!in_array('viewGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $grn_data = $this->model_grn->getGRNHeaderData();
        $this->data['grn_data'] = $grn_data;

        $this->render_template('GRN/viewGRN',$this->data);
    }
}
