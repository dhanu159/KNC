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
    }

    public function CreateGRN(){

        if (!$this->isAdmin) {
            if (!in_array('createGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $supplier_data = $this->model_supplier->getSupplierData();
        $item_data = $this->model_item->getItemData();

        $this->data['supplier_data'] = $supplier_data;
        $this->data['item_data'] = $item_data;

        $this->render_template('GRN/createGRN',$this->data);
    }
}
