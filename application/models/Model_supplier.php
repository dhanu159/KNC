<?php

class Model_supplier extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('supplier', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {

            $this->db->trans_start();
            $sql = "SELECT intSupplierID, vcSupplierName, vcAddress, vcContactNo, dtCreatedDate, decCreditLimit, decAvailableCredit, IsActive, intUserID, rv FROM supplier WHERE intSupplierID = ? ";
            $query = $this->db->query($sql, array($id));
            if ($query->num_rows()) {
                $this->db->insert('supplier_his', $query->row_array());
                $insert_id = $this->db->insert_id();
                $this->db->where('intSupplier_hisID', $insert_id);
                $update = $this->db->update('supplier_his', array('intEnteredBy' => $this->session->userdata('user_id')));
    
                $this->db->where('intSupplierID', $id);
                $update = $this->db->update('supplier', $data);
    
                $this->db->trans_complete();
                return ($update == true) ? true : false;
            }
        }
    }

    /* get the Supplier data */
    public function getSupplierData($id = null)
    {
        if ($id) {
            $sql = "SELECT intSupplierID,vcSupplierName,vcAddress,vcContactNo,decCreditLimit,decAvailableCredit,rv FROM supplier WHERE intSupplierID = ? AND IsActive = 1";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT intSupplierID,vcSupplierName,vcAddress,vcContactNo,decCreditLimit,decAvailableCredit,rv FROM supplier WHERE IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();

    }


    public function remove($id)
    {
        if ($id) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intSupplierID', $id);
            $delete = $this->db->update('supplier', $data);
            return ($delete == true) ? true : false;
        }
    }

    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intSupplierID  FROM grnheader WHERE intSupplierID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }

    public function chkRv($id = null)
    {
        if ($id) {
            $sql = "SELECT rv FROM `supplier` WHERE intSupplierID = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }
    }

	//-----------------------------------
    // Supplier Credit Settlement
    //-----------------------------------

    public function getSupplierWiseInvoiceAndGRNno($supplierID)
    {
        if ($supplierID) {
            $sql = "SELECT G.intGRNHeaderID, CONCAT(G.vcInvoiceNo,' ( ', G.vcGRNNo,' ) ') AS vcGRNNo , G.intSupplierID  , SUM(IFNULL(SD.decPaidAmount,0)) AS PayAmount ,G.decGrandTotal
            FROM grnheader AS G
            LEFT OUTER JOIN suppliersettlementdetail AS SD ON G.intGRNHeaderID = SD.intGRNHeaderID
            WHERE  G.intSupplierID = ? AND  G.intPaymentTypeID = 2 AND G.intApprovedBy is not null AND G.intRejectedBy is null
            GROUP BY 
            G.intGRNHeaderID
            HAVING 
            G.decGrandTotal > SUM(IFNULL(SD.decPaidAmount,0))";
            $query = $this->db->query($sql, array($supplierID));
            return $query->result_array();
        }
    }

    public function SaveSupplierCreditSettlement()
    {
        $this->db->trans_start();

        $data = array(
            'intSupplierID' => $this->input->post('cmbsupplier'),
            'decAmount' => $this->input->post('txtAmount'),
            'intPayModelD' =>  $this->input->post('cmbPayMode'),
            'dtPaidDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('dtSettlementDate')))),
            'intUserID' =>  $this->session->userdata('user_id'),
            'vcChequeNo' => $this->input->post('txtChequeNo'),
            'intBankID' =>  $this->input->post('cmbBank'),
            'dtPDDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('dtPDDate')))),
            'vcRemark' => $this->input->post('txtRemark'),
        );

        $this->db->insert('suppliersettlementheader', $data);
        $SupplierSettlementHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('----------'));





    }
}
