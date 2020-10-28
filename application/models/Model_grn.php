<?php

class Model_grn extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveGRN()
    {
        $this->db->trans_start();

        $query = $this->db->query("SELECT fnGenerateGRNNo() AS GRNNo");
        // $query = $this->db->get();
        $ret = $query->row();
        $GRNNo = $ret->GRNNo;

        // $GRNNo = $this->db->query("SELECT fnGenerateGRNNo() AS GRNNo");
        // $GRNNo->row_arry();
        // var_dump($GRNNo);
        // $GRNNo = "Test-001";
        $insertDetails = false;

        $data = array(
            'vcGRNNo' => $GRNNo,
            'vcInvoiceNo' => $this->input->post('invoice_no'),
            'intSupplierID' => $this->input->post('supplier'),
            'dtReceivedDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('receivedDate')))),
            'intUserID' => $this->session->userdata('user_id'),
            'decSubTotal' => $this->input->post('subTotal'),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => $this->input->post('grandTotal'),
        );

        $insert = $this->db->insert('GRNHeader', $data);
        $GRNHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intGRNHeaderID' => $GRNHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decQty' => $this->input->post('itemQty')[$i],
                'decUnitPrice' => $this->input->post('unitPrice')[$i],
                'decTotalPrice' => $this->input->post('totalPrice')[$i]
            );
            $insertDetails = $this->db->insert('GRNDetail', $items);
        }

        $this->db->trans_complete();

        return ($insertDetails == true) ? true : false;
    }

    public function getGRNHeaderData($GRNID = null)
    {
        if ($GRNID) {
            $sql = "
                    SELECT 
                        GH.intGRNHeaderID, 
                        GH.vcGRNNo, 
                        GH.vcInvoiceNo, 
                        GH.intSupplierID,
                        S.vcSupplierName, 
                        GH.decSubTotal, 
                        IFNULL(GH.decDiscount,0.00) AS decDiscount, 
                        GH.decGrandTotal, 
                        GH.dtReceivedDate, 
                        GH.dtCreatedDate, 
                        GH.intUserID AS CreatedUserID,
                        CreatedUser.vcFullName AS CreatedUser, 
                        GH.intApprovedBy,
                        ApprovedUser.vcFullName AS ApprovedUser, 
                        GH.dtApprovedOn
                    FROM 
                        KNC.GRNHeader AS GH
                        INNER JOIN KNC.Supplier AS S ON GH.intSupplierID = S.intSupplierID
                        INNER JOIN KNC.User AS CreatedUser ON GH.intUserID = CreatedUser.intUserID
                        LEFT OUTER JOIN KNC.User AS ApprovedUser ON GH.intApprovedBy = ApprovedUser.intUserID
                    WHERE intGRNHeaderID = ?";
                    
            $query = $this->db->query($sql, array($GRNID));
            return $query->result_array();
        }

        $sql = "
                    SELECT 
                        GH.intGRNHeaderID, 
                        GH.vcGRNNo, 
                        GH.vcInvoiceNo, 
                        GH.intSupplierID,
                        S.vcSupplierName, 
                        GH.decSubTotal, 
                        IFNULL(GH.decDiscount,0.00) AS decDiscount, 
                        GH.decGrandTotal, 
                        GH.dtReceivedDate, 
                        GH.dtCreatedDate, 
                        GH.intUserID AS CreatedUserID,
                        CreatedUser.vcFullName AS CreatedUser, 
                        GH.intApprovedBy,
                        ApprovedUser.vcFullName AS ApprovedUser, 
                        GH.dtApprovedOn
                    FROM 
                        KNC.GRNHeader AS GH
                        INNER JOIN KNC.Supplier AS S ON GH.intSupplierID = S.intSupplierID
                        INNER JOIN KNC.User AS CreatedUser ON GH.intUserID = CreatedUser.intUserID
                        LEFT OUTER JOIN KNC.User AS ApprovedUser ON GH.intApprovedBy = ApprovedUser.intUserID";

        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }
}
