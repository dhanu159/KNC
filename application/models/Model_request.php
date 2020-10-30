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

    
}
