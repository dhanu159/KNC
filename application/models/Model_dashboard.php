<?php

class Model_dashboard extends CI_Model
{
    public function __construct()
    {
        parent::__construct(); 
    }

    public function getApprovalPendingGRNCount(){
        $sql = "SELECT COUNT(intGRNHeaderID) AS PendingGRNCount FROM GRNHeader WHERE dtApprovedOn IS NULL AND isActive = 1";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        return $data['PendingGRNCount'];
    }

    public function getMainBranchApprovalPendingData(){
        $sql = "SELECT 
                    intGRNHeaderID,
                    vcGRNNo,
                    TIMESTAMPDIFF(MINUTE, CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END, NOW()) AS `Minutes`,
                    TIMESTAMPDIFF(HOUR, CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END, NOW()) AS `Hours`,
                    DATEDIFF(NOW(),CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END) AS `Days`   
                FROM GRNHeader WHERE dtApprovedOn IS NULL AND isActive = 1
                ORDER BY CASE WHEN dtLastModifiedDate IS NULL THEN dtCreatedDate ELSE dtLastModifiedDate END DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}