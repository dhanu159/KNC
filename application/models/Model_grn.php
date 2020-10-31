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

        // $query = $this->db->query("SELECT fnGenerateGRNNo() AS GRNNo");
        // $ret = $query->row();
        // $GRNNo = $ret->GRNNo;


        $GRNNo = "Test-001";

        $insertDetails = false;


        $data = array(
            'vcGRNNo' => $GRNNo,
            'vcInvoiceNo' => $this->input->post('invoice_no'),
            'intSupplierID' => $this->input->post('supplier'),
            'dtReceivedDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('receivedDate')))),
            'intUserID' => $this->session->userdata('user_id'),
            'decSubTotal' => str_replace(',', '', $this->input->post('subTotal')),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => str_replace(',', '', $this->input->post('grandTotal')),
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

    public function getGRNHeaderData($GRNID = null, $Status = null, $FromDate = null, $ToDate = null)
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
                        GH.dtApprovedOn,
                        RejectedUser.vcFullName AS RejectedUser,
                        GH.dtRejectedOn 
                    FROM 
                        KNC.GRNHeader AS GH
                        INNER JOIN KNC.Supplier AS S ON GH.intSupplierID = S.intSupplierID
                        INNER JOIN KNC.User AS CreatedUser ON GH.intUserID = CreatedUser.intUserID
                        LEFT OUTER JOIN KNC.User AS ApprovedUser ON GH.intApprovedBy = ApprovedUser.intUserID
                        LEFT OUTER JOIN KNC.User AS RejectedUser ON GH.intRejectedBy = RejectedUser.intUserID
                    WHERE GH.intGRNHeaderID = ?";

            $query = $this->db->query($sql, array($GRNID));
            return $query->row_array();
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
                        GH.dtApprovedOn,
                        RejectedUser.vcFullName AS RejectedUser,
                        GH.dtRejectedOn 
                    FROM 
                        KNC.GRNHeader AS GH
                        INNER JOIN KNC.Supplier AS S ON GH.intSupplierID = S.intSupplierID
                        INNER JOIN KNC.User AS CreatedUser ON GH.intUserID = CreatedUser.intUserID
                        LEFT OUTER JOIN KNC.User AS ApprovedUser ON GH.intApprovedBy = ApprovedUser.intUserID
                        LEFT OUTER JOIN KNC.User AS RejectedUser ON GH.intRejectedBy = RejectedUser.intUserID";


        $dateFilter = " WHERE CAST(GH.dtCreatedDate AS DATE) BETWEEN ? AND ? ";


        if ($Status == 1) { // Approved
            $statusFilter = " AND GH.intApprovedBy IS NOT NULL ";
        } else if ($Status == 2) { // Pending
            $statusFilter = " AND GH.intApprovedBy IS NULL AND GH.intRejectedBy IS NULL ";
        } else if ($Status == 3) { // Rejected
            $statusFilter = " AND GH.intRejectedBy IS NOT NULL ";
        } else {  // All
            $statusFilter = "";
        }


        $sql  = $sql . $dateFilter . $statusFilter;

        $query = $this->db->query($sql, array($FromDate, $ToDate));
        return $query->result_array();
    }

    public function getGRNDetailData($GRNHeaderID)
    {
        $sql = "
                SELECT 
                    GD.intGRNDetailID,
                    GD.intGRNHeaderID,
                    GD.decQty,
                    GD.decUnitPrice,
                    GD.decTotalPrice,
                    I.intItemID,
                    I.vcItemName,
                    MU.intMeasureUnitID,
                    MU.vcMeasureUnit
                FROM 
                    KNC.GRNDetail AS GD
                    INNER JOIN KNC.Item AS I ON GD.intItemID = I.intItemID
                    INNER JOIN KNC.MeasureUnit AS MU ON I.intMeasureUnitID = MU.intMeasureUnitID
                WHERE 
                    GD.intGRNHeaderID = ?";

        $query = $this->db->query($sql, array($GRNHeaderID));
        return $query->result_array();
    }
}
