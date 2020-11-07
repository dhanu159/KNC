<?php
class Model_request extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getRequestFinishedByItemID($id)
    {
        $sql = "SELECT I.intItemID , M.intMeasureUnitID , M.vcMeasureunit, IFNULL(B.decStockInHand,'N/A')AS decStockInHand from item AS I
        INNER JOIN measureunit AS M ON M.intMeasureUnitID = I.intMeasureUnitID
        LEFT OUTER JOIN branchstock AS B ON B.intItemID = I.intItemID
        where I.intItemID = ? ";
        $query = $this->db->query($sql, array($id));
        return    $query->row_array();
    }

    public function canRemoveRequest($intRequestHeaderID){
        $sql = "
        SELECT *
        from(
        SELECT RH.intRequestHeaderID,
                    RH.vcRequestNo,
                    B.vcBranchName,
                    RH.dtCreatedDate,
                    US.vcFullName AS Created_User , 
                    count(RD.intRequestDetailID) as Total_Items,
                    SUM(case when RD.intApprovedBy IS NULL then 1 else 0 end) - SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Pending , 
                    SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Rejected 
                    FROM requestheader AS RH
                    INNER JOIN requestdetail AS RD ON RD.intRequestHeaderID = RH.intRequestHeaderID
                    INNER JOIN user AS US ON RH.intUserID = US.intUserID
                    INNER JOIN branch as B ON RH.intBranchID = B.intBranchID
                    group by RH.intRequestHeaderID
                    
        ) t1
        WHERE  t1.Total_Items = t1.Pending AND t1.Rejected = 0 AND t1.intRequestHeaderID = ? ";
        $query = $this->db->query($sql, array($intRequestHeaderID));
        if ($query->result_array() != null) {
            return true;
        }else{
            return false;
        }
    }

    public function removeRequest($intRequestHeaderID){
        if ($intRequestHeaderID) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intRequestHeaderID', $intRequestHeaderID);
            $delete = $this->db->update('requestheader', $data);
            return ($delete == true) ? true : false;
        }
    }

    public function getRequestHeaderData($RequestID = null, $Status = null, $FromDate = null, $ToDate = null)
    {

        if ($RequestID) {
            $sql = "
                SELECT RH.intRequestHeaderID,
                RH.vcRequestNo,
                B.vcBranchName,
                RH.dtCreatedDate,
                US.vcFullName AS Created_User , 
                count(RD.intRequestDetailID) as Total_Items,
                SUM(case when RD.intApprovedBy IS NULL then 1 else 0 end) - SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Pending , 
                SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Rejected 
                FROM requestheader AS RH
                INNER JOIN requestdetail AS RD ON RD.intRequestHeaderID = RH.intRequestHeaderID
                INNER JOIN user AS US ON RH.intUserID = US.intUserID
                INNER JOIN branch as B ON RH.intBranchID = B.intBranchID
                WHERE RH.IsActive = 1 AND RH.intRequestHeaderID = ?
                group by RH.intRequestHeaderID
                ORDER BY RH.dtCreatedDate DESC";

                $query = $this->db->query($sql, array($RequestID));
                return $query->row_array();
        }

        $sql = "
                SELECT *
                    from(
                    SELECT RH.intRequestHeaderID,
                                RH.vcRequestNo,
                                B.vcBranchName,
                                RH.dtCreatedDate,
                                US.vcFullName AS Created_User , 
                                count(RD.intRequestDetailID) as Total_Items,
                                SUM(case when RD.intApprovedBy IS NULL then 1 else 0 end) - SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Pending , 
                                SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Rejected 
                                FROM requestheader AS RH
                                INNER JOIN requestdetail AS RD ON RD.intRequestHeaderID = RH.intRequestHeaderID
                                INNER JOIN user AS US ON RH.intUserID = US.intUserID
                                INNER JOIN branch as B ON RH.intBranchID = B.intBranchID
                                WHERE CAST(RH.dtCreatedDate AS DATE) BETWEEN ? AND ? AND RH.IsActive = 1
                                group by RH.intRequestHeaderID
                                ORDER BY RH.dtCreatedDate DESC
                                
                    ) t1";

        if ($Status == 1) { // Approved
                        $sql = "";
                        $sql= 
                        "SELECT *
                        from(
                        SELECT RH.intRequestHeaderID,
                                    RH.vcRequestNo,
                                    B.vcBranchName,
                                    RH.dtCreatedDate,
                                    US.vcFullName AS Created_User , 
                                    count(RD.intRequestDetailID) as Total_Items,
                                    SUM(case when RD.intApprovedBy IS NULL then 1 else 0 end) - SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Pending , 
                                    SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Rejected 
                                    FROM requestheader AS RH
                                    INNER JOIN requestdetail AS RD ON RD.intRequestHeaderID = RH.intRequestHeaderID
                                    INNER JOIN user AS US ON RH.intUserID = US.intUserID
                                    INNER JOIN branch as B ON RH.intBranchID = B.intBranchID
                                    WHERE CAST(RH.dtCreatedDate AS DATE) BETWEEN ? AND ? AND RH.IsActive = 1
                                    group by RH.intRequestHeaderID
                                    ORDER BY RH.dtCreatedDate DESC
                                    
                        ) t1
                        where  t1.Pending = 0 and t1.Rejected = 0" ;

        } else if ($Status == 2) { // Pending
                        $sql = "";
                        $sql= 
                        "SELECT *
                        from(
                        SELECT RH.intRequestHeaderID,
                                    RH.vcRequestNo,
                                    B.vcBranchName,
                                    RH.dtCreatedDate,
                                    US.vcFullName AS Created_User , 
                                    count(RD.intRequestDetailID) as Total_Items,
                                    SUM(case when RD.intApprovedBy IS NULL then 1 else 0 end) - SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Pending , 
                                    SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Rejected 
                                    FROM requestheader AS RH
                                    INNER JOIN requestdetail AS RD ON RD.intRequestHeaderID = RH.intRequestHeaderID
                                    INNER JOIN user AS US ON RH.intUserID = US.intUserID
                                    INNER JOIN branch as B ON RH.intBranchID = B.intBranchID
                                    WHERE CAST(RH.dtCreatedDate AS DATE) BETWEEN ? AND ? AND RH.IsActive = 1
                                    group by RH.intRequestHeaderID
                                    ORDER BY RH.dtCreatedDate DESC
                                    
                        ) t1
                        where  t1.Pending > 0" ;

        } else if ($Status == 3) { // Rejected
                    $sql = "";
                    $sql= 
                    "SELECT *
                    from(
                    SELECT RH.intRequestHeaderID,
                                RH.vcRequestNo,
                                B.vcBranchName,
                                RH.dtCreatedDate,
                                US.vcFullName AS Created_User , 
                                count(RD.intRequestDetailID) as Total_Items,
                                SUM(case when RD.intApprovedBy IS NULL then 1 else 0 end) - SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Pending , 
                                SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Rejected 
                                FROM requestheader AS RH
                                INNER JOIN requestdetail AS RD ON RD.intRequestHeaderID = RH.intRequestHeaderID
                                INNER JOIN user AS US ON RH.intUserID = US.intUserID
                                INNER JOIN branch as B ON RH.intBranchID = B.intBranchID
                                WHERE CAST(RH.dtCreatedDate AS DATE) BETWEEN ? AND ? AND RH.IsActive = 1
                                group by RH.intRequestHeaderID
                                ORDER BY RH.dtCreatedDate DESC
                                
                    ) t1
                    where  t1.Total_Items = t1.Rejected" ;

        } else {  // All
                        $sql = "";
                        $sql = "
                        SELECT RH.intRequestHeaderID,
                        RH.vcRequestNo,
                        B.vcBranchName,
                        RH.dtCreatedDate,
                        US.vcFullName AS Created_User , 
                        count(RD.intRequestDetailID) as Total_Items,
                        SUM(case when RD.intApprovedBy IS NULL then 1 else 0 end) - SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Pending , 
                        SUM(case when RD.intRejectedBy IS NULL then 0 else 1 end) AS Rejected 
                        FROM requestheader AS RH
                        INNER JOIN requestdetail AS RD ON RD.intRequestHeaderID = RH.intRequestHeaderID
                        INNER JOIN user AS US ON RH.intUserID = US.intUserID
                        INNER JOIN branch as B ON RH.intBranchID = B.intBranchID
                        WHERE CAST(RH.dtCreatedDate AS DATE) BETWEEN ? AND ? AND RH.IsActive = 1
                        group by RH.intRequestHeaderID 
                        ORDER BY RH.dtCreatedDate DESC";
    
        }

        $query = $this->db->query($sql, array($FromDate, $ToDate));
        return $query->result_array();
    }

    public function getRequestDetailData($intRequestHeaderID)
    {
        $sql = "
                SELECT RH.vcRequestNo,
                RH.intRequestHeaderID,
                RD.intRequestDetailID,
                IT.intItemID,
                IT.vcItemName,
                MU.vcMeasureUnit,
                RD.decQty,
                IFNULL(BR.decStockInHand,' N/A') AS decStockInHand
            FROM requestheader AS RH
            INNER JOIN Requestdetail AS RD ON RH.intRequestHeaderID = RD.intRequestHeaderID
            INNER JOIN Item AS IT ON RD.intItemID = IT.intItemID
            INNER JOIN Measureunit AS MU ON IT.intMeasureUnitID = MU.intMeasureUnitID
            LEFT OUTER JOIN branchstock as BR ON IT.intItemID = BR.intItemID
            WHERE RH.intRequestHeaderID  = ?";

        $query = $this->db->query($sql, array($intRequestHeaderID));
        return $query->result_array();

    }

    public function SaveRequestItem()
    {
        $this->db->trans_start();

        $query = $this->db->query("SELECT fnGenerateRequestNo() AS RquestNo");
        $ret = $query->row();
        $RquestNo = $ret->RquestNo;


        $insertDetails = false;

        $data = array(
            'vcRequestNo' => $RquestNo,
            'intBranchID' => $this->session->userdata('branch_id'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $insert = $this->db->insert('requestheader', $data);
        $RequestHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intRequestHeaderID' => $RequestHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decQty' => $this->input->post('itemQty')[$i]
            );
            $insertDetails = $this->db->insert('requestDetail', $items);
        }

        $this->db->trans_complete();

        return ($insertDetails == true) ? true : false;
    }

    public function EditRequest($intRequestHeaderID)
    {
        $this->db->trans_start();

        $editDetails = false;

        $data = array(
            'intBranchID' => $this->session->userdata('branch_id'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $this->db->where('intRequestHeaderID', $intRequestHeaderID);
        $this->db->update('requestheader', $data);

        $this->db->where('intRequestHeaderID', $intRequestHeaderID);
        $this->db->delete('requestDetail');

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intRequestHeaderID' => $intRequestHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decQty' => $this->input->post('itemQty')[$i]
            );
            $editDetails = $this->db->insert('requestDetail', $items);
        }

        $this->db->trans_complete();

        return ($editDetails == true) ? true : false;
    }
}
