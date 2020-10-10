<?php

class Model_item extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getItemData($itemId = null) 
	{
		if($itemId) {
			$sql = "SELECT it.intItemID,it.vcItemName,mu.vcMeasureUnit,t.vcItemTypeName,it.decStockInHand,it.decReOrderLevel FROM item as it
            inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
            inner join itemtype as t on t.intItemTypeID = it.intItemTypeID
            WHERE it.intItemID = ?";
			$query = $this->db->query($sql, array($itemId));
			return $query->row_array();
        }
        
		$sql = "SELECT it.intItemID,it.vcItemName,mu.vcMeasureUnit,t.vcItemTypeName,it.decStockInHand,it.decReOrderLevel FROM item as it
        inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
        inner join itemtype as t on t.intItemTypeID = it.intItemTypeID";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('item', $data);
            return ($insert == true) ? true : false;
        }
    }

}