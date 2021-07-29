<?php

class Model_dashboard extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /////////////////////
    // Pending Counts
    ////////////////////

    public function getApprovalPendingGRNCount()
    {
        $sql = "
                SELECT SUM(PendingGRNCount) AS PendingGRNCount
                FROM(
                    SELECT COUNT(intGRNHeaderID) AS PendingGRNCount 
                    FROM GRNHeader 
                    WHERE dtApprovedOn IS NULL AND dtRejectedOn IS NULL AND isActive = 1

                    UNION
                    
                    SELECT COUNT(intFGGRNHeaderID) AS PendingGRNCount 
                    FROM FG_GRNHeader 
                    WHERE dtApprovedOn IS NULL AND dtRejectedOn IS NULL AND isActive = 1
                ) AS X";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        return $data['PendingGRNCount'];
    }

    // public function getPendingDispatchCount(){
    //         $sql = "";
    //         $query = $this->db->query($sql);
    //         $data = $query->row_array();
    //         return $data['PendingGRNCount'];
    // }

    public function getMainBranchApprovalPendingData()
    {
        $sql = "			SELECT * FROM(
            SELECT 
                intGRNHeaderID,
                vcGRNNo,
                TIMESTAMPDIFF(MINUTE, CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END, NOW()) AS `Minutes`,
                TIMESTAMPDIFF(HOUR, CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END, NOW()) AS `Hours`,
                DATEDIFF(NOW(),CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END) AS `Days`,
                dtLastModifiedDate,
                dtCreatedDate,
                0 AS IsFGGRN
            FROM GRNHeader WHERE dtApprovedOn IS NULL AND dtRejectedOn IS NULL AND isActive = 1

           
           UNION
           
           SELECT 
                intFGGRNHeaderID AS intGRNHeaderID,
                vcFGGRNNo AS vcGRNNo,
                TIMESTAMPDIFF(MINUTE, CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END, NOW()) AS `Minutes`,
                TIMESTAMPDIFF(HOUR, CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END, NOW()) AS `Hours`,
                DATEDIFF(NOW(),CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END) AS `Days`,
                dtLastModifiedDate,
                dtCreatedDate,
                1 AS IsFGGRN
            FROM FG_GRNHeader WHERE dtApprovedOn IS NULL AND dtRejectedOn IS NULL AND isActive = 1 
            ) AS A
            ORDER BY CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getSIHExceedItemData($branch_id)
    {
        $sql = "";
        if ($branch_id == 0) {
            $sql = "SELECT 
                I.vcItemName,I.decStockInHand,IT.vcItemTypeName
                FROM item AS I
                INNER JOIN  itemtype AS IT ON I.intItemTypeID = IT.intItemTypeID
                WHERE I.decReOrderLevel > I.decStockInHand AND I.IsActive = 1";
            $query = $this->db->query($sql);
        } else {
            $sql = "SELECT I.vcItemName,I.decStockInHand,IT.vcItemTypeName
            FROM item AS I
            INNER JOIN  itemtype AS IT ON I.intItemTypeID = IT.intItemTypeID
            INNER JOIN branchstock AS BS ON I.intItemID = BS.intItemID
            WHERE I.decReOrderLevel > I.decStockInHand AND I.IsActive = 1 AND BS.intBranchID = ?";
            $query = $this->db->query($sql, array($branch_id));
        }
        return $query->result_array();
    }
}
