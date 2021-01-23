<?php
class Model_receipt extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCustomerToBeSettleIssueNos($CustomerID)
    {
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

    public function getIssueNotePaymentDetails($IssueHeaderID)
    {
        $sql = "
            SELECT 
                IH.decGrandTotal,
                SUM(IFNULL(RD.decPaidAmount,0)) AS decPaidAmount,
                REPLACE(IH.rv,' ','-') AS rv
            FROM 
                IssueHeader AS IH
                LEFT OUTER JOIN ReceiptDetail AS RD ON IH.intIssueHeaderID = RD.intIssueHeaderID
            WHERE 
                IH.intIssueHeaderID = ?";

        $query = $this->db->query($sql, array($IssueHeaderID));
        return $query->row_array();
    }

    public function saveReceipt()
    {
        $this->db->trans_begin();

        // $query = $this->db->query("SELECT fnGenerateReceiptNo() AS ReceiptNo");
        // $ret = $query->row();
        // $ReceiptNo = $ret->ReceiptNo;

        $response = array();

        $ReceiptNo = "Receipt-001";

        $customerID = $this->input->post('cmbCustomer');
        $payMode = $this->input->post('cmbPayMode');
        (float)$receiptTotal = $this->input->post('txtAmount');

        $ReceiptHeaderData = array(
            'vcReceiptNo' => $ReceiptNo,
            'intCustomerID' => $customerID,
            'intPayModeID' =>  $payMode,
            'decAmount' => $receiptTotal,
            'dtReceiptDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('ReceiptDate')))),
            'vcRemark' => $this->input->post('txtRemark'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $this->db->insert('ReceiptHeader', $ReceiptHeaderData);
        $ReceiptHeaderID = $this->db->insert_id();

        if ($payMode == 2) { // Cheque
            $ChequeData = array(
                'intBankID' => $this->input->post('cmbBank'),
                'intReceiptHeaderID' => $ReceiptHeaderID,
                'vcChequeNo' => $this->input->post('txtChequeNo'),
                'dtPDDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('PDDate'))))
            );
            $this->db->insert('CustomerCheque', $ChequeData);
        } else { // Cash
            $sql = "UPDATE Customer 
                SET decAvailableCredit = (decAvailableCredit + " . $receiptTotal . ")
                WHERE intCustomerID = ?";

            $this->db->query($sql, array($customerID));
        }

        $item_count = count($this->input->post('issueHeaderID'));

        $anotherUserAccess = false;
        $userRestrictionsExceeded = false;

        (float)$payAmount = 0;

        for ($i = 0; $i < $item_count; $i++) {
            // $currentRV = $this->model_issue->chkRv($this->input->post('issueHeaderID')[$i]);
            // $previousRV =  $this->input->post('Rv')[$i];

            // if ($currentRV['rv'] != $previousRV) {
            //     $anotherUserAccess = true;
            // }

            $payAmount += (float)$this->input->post('payAmount')[$i];

            // $IssueNotePaymentData = $this->getIssueNotePaymentDetails($this->input->post('issueHeaderID')[$i]);
            // $PaidAmount = $IssueNotePaymentData['decPaidAmount'];
            // $IssueGrandTotal = $IssueNotePaymentData['decGrandTotal'];

            // if ((float)$this->input->post('payAmount')[$i] > (float)($IssueGrandTotal - $PaidAmount)) {
            //     $userRestrictionsExceeded = true;
            // }

            $receiptDetailsData = array(
                'intReceiptHeaderID' => $ReceiptHeaderID,
                'intIssueHeaderID' => $this->input->post('issueHeaderID')[$i],
                'decPaidAmount' => (float)$this->input->post('payAmount')[$i]
            );

            $this->db->insert('ReceiptDetail', $receiptDetailsData);
        }

        if ($receiptTotal != $payAmount) {
            $userRestrictionsExceeded = true;
        }

        $this->db->trans_commit();
        $response['success'] = true;
        $response['messages'] = 'Succesfully created !';

        return $response;
    }
}
