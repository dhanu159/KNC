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
			$sql = "SELECT it.intItemID,it.vcItemName,mu.intMeasureUnitID,mu.vcMeasureUnit,t.intItemTypeID,t.vcItemTypeName,it.decStockInHand,it.decReOrderLevel,it.decUnitPrice,it.rv FROM item as it
            inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
            inner join itemtype as t on t.intItemTypeID = it.intItemTypeID
            WHERE it.intItemID = ?";
			$query = $this->db->query($sql, array($itemId));
			return $query->row_array();
        }
        
		$sql = "SELECT it.intItemID,it.vcItemName,mu.intMeasureUnitID,mu.vcMeasureUnit,t.intItemTypeID,t.vcItemTypeName,it.decStockInHand,it.decReOrderLevel,it.decUnitPrice,it.rv FROM item as it
        inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
        inner join itemtype as t on t.intItemTypeID = it.intItemTypeID
        order by it.dtCreatedDate desc";
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

    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intItemID  FROM grndetail WHERE intItemID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('intItemID', $id);
            $update = $this->db->update('item', $data);
            return ($update == true) ? true : false;
        }
    }

    public function chkRv($id = null)
    {
        if ($id) {
            $sql = "SELECT rv FROM `item` WHERE intItemID = ?";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }

    public function remove($id)
    {
        if ($id) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intItemID', $id);
            $delete = $this->db->update('item', $data);
            return ($delete == true) ? true : false;
        }
    }


}