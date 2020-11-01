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

        $supplier_data = $this->model_supplier->getSupplierData();
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
        $data = $this->model_measureunit->getMeasureUnitByItemID($ItemID);
        echo json_encode($data);
    }

    //-----------------------------------
    // View GRN
    //-----------------------------------


    // public function ViewGRN($StatusType = 0, $FromDate = "", $ToDate = "2020-10-10")
public function ViewGRN(){
        if (!$this->isAdmin) {
            if (!in_array('viewGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $this->render_template('GRN/viewGRN');

}

    public function FilterGRNHeaderData($StatusType, $FromDate, $ToDate)
    {
        if (!$this->isAdmin) {
            if (!in_array('viewGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        $result = array('data' => array());


        $grn_data = $this->model_grn->getGRNHeaderData(null,$StatusType, $FromDate, $ToDate);

        // $this->data['grn_data'] = $grn_data;

        foreach ($grn_data as $key => $value) {

            $buttons = '';

            if ($value['ApprovedUser'] == null && $value['RejectedUser'] == null) { // Pending && Edit
                if (in_array('editGRN', $this->permission) || $this->isAdmin) {
                    $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/EditGRN/" . $value['intGRNHeaderID']) . '" style="margin:0 !important;"><i class="fas fa-edit"></i></a>';
                }
                if (in_array('deleteGRN', $this->permission) || $this->isAdmin) {
                    $buttons .= '<a class="button btn btn-default" onclick="removeGRN('. $value['intGRNHeaderID'] .')"><i class="fa fa-trash"></i></a>';
                }
            }else if($value['ApprovedUser'] == null){ // Pending 
                if (in_array('approveGRN', $this->permission) || $this->isAdmin) {
                    $buttons .= '<a class="button btn btn-default" href="' . base_url("GRN/ApproveGRN/" . $value['intGRNHeaderID']) . '"><i class="far fa-thumbs-up"></i></a>';
                }
            }


            $result['data'][$key] = array(
                $value['vcGRNNo'],
                $value['vcInvoiceNo'],
                $value['vcSupplierName'],
                number_format((float)$value['decSubTotal'], 2, '.', ''),
                number_format((float)$value['decDiscount'], 2, '.', ''),
                number_format((float)$value['decGrandTotal'], 2, '.', ''),
                $value['dtReceivedDate'],
                $value['dtCreatedDate'],
                $value['CreatedUser'],
                ($value['dtApprovedOn'] == NULL) ? "N/A" : $value['dtApprovedOn'],
                ($value['ApprovedUser'] == NULL) ? "N/A" : $value['ApprovedUser'],
                ($value['dtRejectedOn'] == NULL) ? "N/A" : $value['dtRejectedOn'],
                ($value['RejectedUser'] == NULL) ? "N/A" : $value['RejectedUser'],
                $buttons
            );
        }

        echo json_encode($result);

    }

    //-----------------------------------
    // Edit GRN
    //-----------------------------------

    public function EditGRN($GRNHeaderID)
    {
        if (!$this->isAdmin) {
            if (!in_array('editGRN', $this->permission)) {
                redirect('dashboard', 'refresh');
            }
        }

        if (!$GRNHeaderID) {
            redirect('dashboard', 'refresh');
        }

        $grn_header_data = $this->model_grn->getGRNHeaderData($GRNHeaderID);
        $grn_detail_data = $this->model_grn->getGRNDetailData($GRNHeaderID);
        $supplier_data = $this->model_supplier->getSupplierData();
        $item_data = $this->model_item->getOnlyRawItemData();

        $this->data['supplier_data'] = $supplier_data;
        $this->data['item_data'] = $item_data;

        $this->data['grn_detail_data'] = $grn_detail_data;
        $this->data['grn_header_data'] = $grn_header_data;


        $this->render_template('GRN/editGRN', $this->data);
    }


}
