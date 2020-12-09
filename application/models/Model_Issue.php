<?php

class Model_issue extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_customer');
        $this->load->model('model_item');
    
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
        $GrandTotal = str_replace(',', '', $this->input->post('grandTotal'));

        $customerData = $this->model_customer->getCustomerData($this->input->post('cmbcustomer'));
        $customerAvailableCredit = $customerData['decAvailableCredit'];
        $exceedCreditLimit = false;

        if ($paymentType == 2) //Credit
        {
            if ($customerAvailableCredit <  $GrandTotal) {
                $exceedCreditLimit = true;
            }
        }


        $data = array(
            'vcIssueNo' => $IssueNo,
            'intCustomerID' => $this->input->post('cmbcustomer'),
            'dtIssueDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('issuedDate')))),
            'intUserID' => $this->session->userdata('user_id'),
            'intPaymentTypeID' =>  $paymentType,
            'decSubTotal' => str_replace(',', '', $this->input->post('subTotal')),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => $GrandTotal,
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
                'decTotalPrice' => ($this->input->post('itemQty')[$i] * $UnitPrice)
            );
            $insertDetails = $this->db->insert('IssueDetail', $items);
            $IssueDetailID = $this->db->insert_id();
            
            $Logdata = array(
                'intItemID' => $itemData['intItemID'],
                'intTransactionLogTypeID' => 3, //Item Issue
                'vcPerformColumn' => 'intIssueDetailID',
                'intPerformID' => $IssueDetailID,
                'decPreviousQty' => $itemData['decStockInHand'],
                'decCurrentQty' => $itemData['decStockInHand'] - $decIssuQty,
                'intLoggedBy' => $this->session->userdata('user_id'),
            );

            $insertLog = $this->db->insert('itemtransactionlog', $Logdata);

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
        } else if ($exceedCreditLimit == true) {
            $response['success'] = false;
            $response['messages'] = 'You cannot exceed cutomer credit limit !';
            $this->db->trans_rollback();
        } else {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = false;
                $response['messages'] = 'Error in the database while create the issue details';
            } else {

                $IssueHeaderData = $this->GetIssueHeaderData($IssueHeaderID, null, null, null, null);

                $response['vcIssueNo'] =  $IssueHeaderData['vcIssueNo'];
                $response['intIssueHeaderID'] =  $IssueHeaderData['intIssueHeaderID'];

                $this->db->trans_commit();
                $response['success'] = true;
                $response['messages'] = 'Succesfully created !';
            }
        }

        return $response;
    }

    public function GetIssueHeaderData($IssueHeaderID = null, $PaymentType = null, $CustomerID = null, $FromDate = null, $ToDate = null)
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
                    IH.decBalance,      
                    CU.decCreditLimit,
                    CU.decAvailableCredit
            FROM Issueheader AS IH
            INNER JOIN customer AS CU ON IH.intCustomerID = CU.intCustomerID
            INNER JOIN user as U ON IH.intUserID = U.intUserID
            INNER JOIN paymenttype AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
            WHERE intIssueHeaderID = ? AND IH.IsActive = 1;";

            $query = $this->db->query($sql, array($IssueHeaderID));
            return $query->row_array();
        }



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
       INNER JOIN paymenttype AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID";

        $dateFilter = " WHERE IH.IsActive = 1 AND CAST(IH.dtCreatedDate AS DATE) BETWEEN ? AND ? ";

        $customerFilte = "";
        $paymentTypeFilte = "";

        $sqlParam = array();

        array_push($sqlParam, $FromDate);
        array_push($sqlParam, $ToDate);

        if ($PaymentType != 0) {

            $paymentTypeFilte = " AND IH.intPaymentTypeID = ? ";
            array_push($sqlParam, $PaymentType);
           
        }

        if ($CustomerID != 0) {

            $customerFilte = " AND IH.intCustomerID = ? ";
            array_push($sqlParam, $CustomerID);
        }

        $sql  = $sql . $dateFilter  . $paymentTypeFilte . $customerFilte . " ORDER BY IH.dtCreatedDate DESC";

        $query = $this->db->query($sql, $sqlParam);
        return $query->result_array();
    }

    public function GetIssueDetailsData($IssueHeaderID = null)
    {
        if ($IssueHeaderID) {
            $sql = "
            SELECT I.vcItemName,
            ID.decUnitPrice,
            ID.decIssueQty,
            MU.vcMeasureUnit,
            ID.decTotalPrice
            FROM IssueDetail AS ID
            INNER JOIN Issueheader AS IH ON ID.intIssueHeaderID = IH.intIssueHeaderID
            INNER JOIN Item AS I ON ID.intItemID = I.intItemID
            INNER JOIN measureunit AS MU ON I.intMeasureUnitID = MU.intMeasureUnitID
            WHERE ID.intIssueHeaderID = ?";

            $query = $this->db->query($sql, array($IssueHeaderID));
            return $query->result_array();
        }
    }

    public function getPaymentTypes()
    {
        $sql = "SELECT intPaymentTypeID,vcPayment FROM paymenttype;";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
