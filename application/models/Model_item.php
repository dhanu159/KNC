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
            WHERE IsActive = 1 AND it.intItemID = ? ";
			$query = $this->db->query($sql, array($itemId));
			return $query->row_array();
        }
        
		$sql = "SELECT it.intItemID,it.vcItemName,mu.intMeasureUnitID,mu.vcMeasureUnit,t.intItemTypeID,t.vcItemTypeName,it.decStockInHand,it.decReOrderLevel,it.decUnitPrice,it.rv FROM item as it
        inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
        inner join itemtype as t on t.intItemTypeID = it.intItemTypeID
        where  IsActive = 1
        order by it.vcItemName asc";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
    }


    public function getOnlyRawItemData()
    {
        $sql = "SELECT it.intItemID,it.vcItemName,mu.intMeasureUnitID,mu.vcMeasureUnit,t.intItemTypeID,t.vcItemTypeName,it.decStockInHand,it.decReOrderLevel,it.decUnitPrice,it.rv FROM item as it
        inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
        inner join itemtype as t on t.intItemTypeID = it.intItemTypeID
        where  IsActive = 1 AND it.intItemTypeID = 1
        order by it.vcItemName asc";
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

    public function insertItemHitory($intEnteredBy, $id)
    {
        $this->db->trans_start();
        $sql = "SELECT intItemID, vcItemName, intMeasureUnitID, dtCreatedDate, intUserID, decStockInHand, IsActive, decReOrderLevel, intItemTypeID, decUnitPrice FROM item WHERE intitemID = ? ";
        $query = $this->db->query($sql, array($id));
        if ($query->num_rows()) {
            $this->db->insert('item_his', $query->row_array());
            $insert_id = $this->db->insert_id();
            $this->db->where('intItem_hisID', $insert_id);
            $update = $this->db->update('item_his', $intEnteredBy);
            $this->db->trans_complete();
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