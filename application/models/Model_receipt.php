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
                IH.vcISSueNo,
                IH.decGrandTotal,
                SUM(IFNULL(RD.decPayAmount,0)) AS PayAmount 
            FROM 
                IssueHeader AS IH
                LEFT OUTER JOIN ReceiptDetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
            WHERE 
                intPaymentTypeID = 2 AND IH.intCustomerID = ?
            GROUP BY
                IH.intIssueHeaderID
            HAVING
                IH.decGrandTotal > SUM(IFNULL(RD.decPayAmount,0)) ";

        $query = $this->db->query($sql, array($CustomerID));
        return $query->result_array();
    }


}