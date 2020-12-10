<?php

class Model_dispatch extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_item');
    }

    public function saveDispatch()
    {
        $this->db->trans_begin();

        // $query = $this->db->query("SELECT fnGenerateDispatchNo() AS DispatchNo");
        // $ret = $query->row();
        // $DispatchNo = $ret->DispatchNo;

        $response = array();

        $DispatchNo = "Dispatch-001";

        $insertDetails = false;


        $data = array(
            'vcDispatchNo' => $DispatchNo,
            'dtDispatchDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('dispatchDate')))),
            'intUserID' => $this->session->userdata('user_id')
        );

        $this->db->insert('DispatchHeader', $data);
        $DispatchHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('itemID'));


        $anotherUserAccess = false;
        $exceedStockQty = false;

        $itemArray = array();

        for ($i = 0; $i < $item_count; $i++) {

            $itemID = $this->input->post('itemID')[$i];
            $dispatchQty = 0;

            // $items = array_unique($this->input->post('itemID'));

            // for ($x = $i; $x < ($item_count - $i); $x++) {
            for ($x = $i; $x < ($item_count); $x++) {

                if (!in_array($itemID, $itemArray)) {
                    if ($itemID == $this->input->post('itemID')[$x]) {

                        $dispatchQty = $dispatchQty + $this->input->post('itemQty')[$x];
                    }
                }
            }

            if (!in_array($itemID, $itemArray)) {
                array_push($itemArray, $itemID);

                $currentRV = $this->model_item->chkRv($itemID);
                $previousRV =  $this->input->post('Rv')[$i];


                if ($currentRV['rv'] != $previousRV) {
                    $anotherUserAccess = true;
                }

                $stockInHandQty = $this->model_item->getItemData($itemID);

                if ($stockInHandQty['decStockInHand'] < $dispatchQty) {
                    $exceedStockQty = true;
                }

                $items = array(
                    'intDispatchHeaderID' => $DispatchHeaderID,
                    'intCuttingOrderHeaderID' => $this->input->post('cuttingOrderId')[$i],
                    'intItemID' => $itemID,
                    'decDispatchQty' => $dispatchQty
                );
                $this->db->insert('DispatchDetail', $items);

                $sql = "UPDATE Item AS I SET I.decStockInHand = (I.decStockInHand - " . $dispatchQty . ")
                WHERE I.intItemID = ?";

                $this->db->query($sql, array($itemID));
            }
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
                $response['messages'] = 'Error in the database while create the dispatch details';
            } else {

                $DispatchHeaderData = $this->getDispatchHeaderData($DispatchHeaderID, null, null, null, null);

                $response['vcDispatchNo'] =  $DispatchHeaderData['vcDispatchNo'];
                $response['intDispatchHeaderID'] =  $DispatchHeaderData['intDispatchHeaderID'];

                $this->db->trans_commit();
                $response['success'] = true;
                $response['messages'] = 'Succesfully created !';
            }
        }

        return $response;
    }

    public function getDispatchHeaderData($DispatchHeaderID = null, $Status = null, $FromDate = null, $ToDate = null)
    {
        if ($DispatchHeaderID) {
            $sql = "
                SELECT 
                    DH.intDispatchHeaderID, 
                    DH.vcDispatchNo,
                    DH.dtDispatchDate, 
                    DH.dtCreatedDate,
                    CreatedUser.vcFullName AS CreatedUser,
                    IFNULL(DH.dtCancelledDate,'') AS dtCancelledDate,
                    IFNULL(CancledUser.vcFullName,'') AS CancledUser,
                    IFNULL(DH.dtReceiveCompletedDate,'') AS dtReceiveCompletedDate,
                    IFNULL(ReceiveCompletedUser.vcFullName,'') AS ReceiveCompletedUser
                FROM DispatchHeader AS DH
                INNER JOIN User AS CreatedUser ON DH.intUserID = CreatedUser.intUserID
                LEFT OUTER JOIN User AS CancledUser ON DH.intCancelledBy = CancledUser.intUserID
                LEFT OUTER JOIN User AS ReceiveCompletedUser ON DH.intReceiveCompletedBy = ReceiveCompletedUser.intUserID
                WHERE DH.intDispatchHeaderID = ?";

            $query = $this->db->query($sql, array($DispatchHeaderID));
            return $query->row_array();
        }



        $sql = "
                SELECT 
                    DH.intDispatchHeaderID, 
                    DH.vcDispatchNo,
                    DH.dtDispatchDate, 
                    DH.dtCreatedDate,
                    CreatedUser.vcFullName AS CreatedUser,
                    IFNULL(DH.dtCancelledDate,'') AS dtCancelledDate,
                    IFNULL(CancledUser.vcFullName,'') AS CancledUser,
                    IFNULL(DH.dtReceiveCompletedDate,'') AS dtReceiveCompletedDate,
                    IFNULL(ReceiveCompletedUser.vcFullName,'') AS ReceiveCompletedUser
                FROM DispatchHeader AS DH
                INNER JOIN User AS CreatedUser ON DH.intUserID = CreatedUser.intUserID
                LEFT OUTER JOIN User AS CancledUser ON DH.intCancelledBy = CancledUser.intUserID
                LEFT OUTER JOIN User AS ReceiveCompletedUser ON DH.intReceiveCompletedBy = ReceiveCompletedUser.intUserID ";


        $dateFilter = " WHERE CAST(DH.dtCreatedDate AS DATE) BETWEEN ? AND ? ";


        if ($Status == 1) { // To Be Received
            $statusFilter = " AND DH.dtReceiveCompletedDate IS NULL AND DH.dtCancelledDate IS NULL ";
        } else if ($Status == 2) { // Received
            $statusFilter = " AND DH.dtReceiveCompletedDate IS NOT NULL ";
        } else if ($Status == 3) { // Canceld
            $statusFilter = " AND DH.dtCancelledDate IS NOT NULL ";
        } else {  // All
            $statusFilter = "";
        }


        $sql  = $sql . $dateFilter . $statusFilter . " ORDER BY DH.intDispatchHeaderID";

        $query = $this->db->query($sql, array($FromDate, $ToDate));
        return $query->result_array();
    }

    public function getDispatcDetailsData($DispatchHeaderID = null)
    {
        if ($DispatchHeaderID) {
            $sql = "
            SELECT CD.vcOrderName,
                    IT.vcItemName,
                    MU.vcMeasureUnit,
                    DD.decDispatchQty
            FROM dispatchdetail AS DD
            INNER JOIN dispatchheader AS DH ON DH.intDispatchHeaderID = DD.intDispatchHeaderID
            INNER JOIN cuttingorderheader AS CD ON DD.intCuttingOrderHeaderID = CD.intCuttingOrderHeaderID
            INNER JOIN item AS IT ON DD.intItemID = IT.intItemID
            INNER JOIN measureunit AS MU ON IT.intMeasureUnitID = MU.intMeasureUnitID
            WHERE DD.intDispatchHeaderID = ?";

            $query = $this->db->query($sql, array($DispatchHeaderID));
            return $query->result_array();
        }
    }

    public function getCollectionPendingDispatchNos(){
        $sql = "
                SELECT 
                    intDispatchHeaderID,
                    vcDispatchNo,
                    dtDispatchDate 
                FROM KNC.DispatchHeader WHERE dtReceiveCompletedDate IS NULL";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getDispatchedItemDetails($intDispatchHeaderID)
    {
        if ($intDispatchHeaderID) {
            $sql = "SELECT 
                        DD.intDispatchDetailID,    
                        I.intItemID,
                        I.vcItemName,
                        CH.vcOrderName,
                        CD.vcSizeDescription,
                        MU.vcMeasureUnit,
                        I.decStockInHand,
                        (CD.decQty * DD.decDispatchQty) AS ExpectedQty,
                        I.rv
                    FROM 
                    KNC.DispatchDetail AS DD
                    INNER JOIN KNC.CuttingOrderHeader AS CH ON DD.intCuttingOrderHeaderID = CH.intCuttingOrderHeaderID
                    INNER JOIN KNC.CuttingOrderDetail AS CD ON CH.intCuttingOrderHeaderID = CD.intCuttingOrderHeaderID
                    INNER JOIN KNC.Item AS I ON DD.intItemID = I.intItemID
                    INNER JOIN KNC.MeasureUnit AS MU ON I.intMeasureUnitID = MU.intMeasureUnitID
                    WHERE DD.intDispatchHeaderID = ?";
            $query = $this->db->query($sql, array($intDispatchHeaderID));
            return  $query->result_array();
        }
    }
}
