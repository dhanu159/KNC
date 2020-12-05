<?php

class Model_issue extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveIssue()
    {
        $this->db->trans_begin();

        $query = $this->db->query("SELECT fnGenerateIssueNo() AS IssueNo");
        $ret = $query->row();
        $IssueNo = $ret->IssueNo;

        $response = array();

        // $IssueNo = "Issue-001";

        $insertDetails = false;

        $paymentType = $this->input->post('paymentmode');

        $data = array(
            'vcIssueNo' => $IssueNo,
            'intCustomerID' => $this->input->post('cmbcustomer'),
            'dtIssueDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('issuedDate')))),
            'intUserID' => $this->session->userdata('user_id'),
            'intPaymentTypeID' =>  $paymentType,
            'decSubTotal' => str_replace(',', '', $this->input->post('subTotal')),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => str_replace(',', '', $this->input->post('grandTotal')),
            'decPaidAmount' => 0.0,
            'decBalance' => 0.0
        );

        $this->db->insert('IssueHeader', $data);
        $IssueHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('itemID'));

        $anotherUserAccess = false;
        $exceedStockQty = false;

        for ($i = 0; $i < $item_count; $i++) {

            $currentRV = $this->model_item->chkRv($this->input->post('itemID')[$i]);
            $previousRV =  $this->input->post('Rv')[$i];


            if ($currentRV['rv'] != $previousRV) {
                $anotherUserAccess = true;
            }
            $decIssuQty = $this->input->post('itemQty')[$i];
            $itemID  = $this->input->post('itemID')[$i];
            $itemData = $this->model_item->getItemData($this->input->post('itemID')[$i]);
            $UnitPrice = $itemData['decUnitPrice'];
            if ($itemData['decStockInHand'] < $decIssuQty) {
                $exceedStockQty = true;
            }
            $items = array(
                'intIssueHeaderID' => $IssueHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decIssueQty' => $this->input->post('itemQty')[$i],
                'decUnitPrice' => $UnitPrice,
                'decTotalPrice' => $this->input->post('totalPrice')[$i]
            );
            $insertDetails = $this->db->insert('IssueDetail', $items);

            $sql = "UPDATE Item AS I
            SET I.decStockInHand = (I.decStockInHand - " . $decIssuQty . ")
                WHERE I.intItemID = ?";

            $this->db->query($sql, array($itemID));
        }


        if ($anotherUserAccess == true) {
            $response['success'] = false;
            $response['messages'] = 'Another user tries to edit this Item details, please refresh the page and try again !';
            $this->db->trans_rollback();
        } else if ($exceedStockQty == true) {
            $response['success'] = false;
            $response['messages'] = 'Stock quantity over exceeds error, please refresh the page and try again !';
            $this->db->trans_rollback();
        } else {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = false;
                $response['messages'] = 'Error in the database while create the issue details';
            } else {

                $IssueHeaderData = $this->GetIssueHeaderData($IssueHeaderID);

                $response['vcIssueNo'] =  $IssueHeaderData['vcIssueNo'];
                $response['intIssueHeaderID'] =  $IssueHeaderData['intIssueHeaderID'];

                $this->db->trans_commit();
                $response['success'] = true;
                $response['messages'] = 'Succesfully created !';
            }
        }

        return $response;
    }

    public function GetIssueHeaderData($IssueHeaderID = null)
    {
        if ($IssueHeaderID) {
            $sql = "
                    SELECT  IH.intIssueHeaderID,
                    IH.vcIssueNo,
                    CU.vcCustomerName,
                    IH.dtIssueDate,
                    IH.dtCreatedDate,
                    U.vcFullName,
                    IH.intPaymentTypeID,
                    PY.vcPayment,
                    IH.decSubTotal,
                    IH.decDiscount,
                    IH.decGrandTotal,
                    IH.decPaidAmount,
                    IH.decBalance
            FROM Issueheader AS IH
            INNER JOIN customer AS CU ON IH.intCustomerID = CU.intCustomerID
            INNER JOIN user as U ON IH.intUserID = U.intUserID
            INNER JOIN paymenttype AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
            WHERE intIssueHeaderID = ? AND IH.IsActive = 1;";

            $query = $this->db->query($sql, array($IssueHeaderID));
            return $query->row_array();
        }
    }

    public function GetIssueDetailsData($IssueHeaderID = null)
    {
        if ($IssueHeaderID) {
            $sql = "
            SELECT I.vcItemName,
            ID.decUnitPrice,
            ID.decIssueQty,
            ID.decTotalPrice
            FROM IssueDetail AS ID
            INNER JOIN Issueheader AS IH ON ID.intIssueHeaderID = IH.intIssueHeaderID
            INNER JOIN Item AS I ON ID.intItemID = I.intItemID
            WHERE ID.intIssueHeaderID = ?";

            $query = $this->db->query($sql, array($IssueHeaderID));
            return $query->result_array();
        }
    }
}
