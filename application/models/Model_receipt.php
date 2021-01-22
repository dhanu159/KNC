<?php
class Model_receipt extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCustomerToBeSettleIssueNos($CustomerID){
        $sql = "
            SELECT 
                IH.intIssueHeaderID,
                IH.vcIssueNo,
                IH.decGrandTotal,
                SUM(IFNULL(RD.decPaidAmount,0)) AS decPaidAmount 
            FROM 
                IssueHeader AS IH
                LEFT OUTER JOIN ReceiptDetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
            WHERE 
                intPaymentTypeID = 2 AND IH.intCustomerID = ?
            GROUP BY
                IH.intIssueHeaderID
            HAVING
                IH.decGrandTotal > SUM(IFNULL(RD.decPaidAmount,0)) ";

        $query = $this->db->query($sql, array($CustomerID));
        return $query->result_array();
    }

    public function getIssueNotePaymentDetails($IssueHeaderID){
        $sql = "
            SELECT 
                IH.decGrandTotal,
                SUM(IFNULL(RD.decPaidAmount,0)) AS decPaidAmount
            FROM 
                IssueHeader AS IH
                LEFT OUTER JOIN ReceiptDetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
            WHERE 
                IH.intIssueHeaderID = ?";

        $query = $this->db->query($sql, array($IssueHeaderID));
        return $query->row_array();
    }


}