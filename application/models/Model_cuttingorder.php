<?php
class Model_cuttingorder extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //-----------------------------------
    // Create Cutting Order
    //-----------------------------------

    public function SaveCuttingOrder()
    {
        $this->db->trans_start();

        $insertDetails = false;
        $insert = false;

        $data = array(
            'vcOrderName' => $this->input->post('cutting_order_name'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $insert = $this->db->insert('CuttingOrderHeader', $data);
        $CuttingOrderHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('description'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intCuttingOrderHeaderID' => $CuttingOrderHeaderID,
                'vcSizeDescription' => $this->input->post('description')[$i],
                'decQty' => $this->input->post('qty')[$i]
            );
            $insertDetails = $this->db->insert('CuttingOrderDetail', $items);
        }

        $this->db->trans_complete();


        return ($insertDetails == true && $insert == true) ? true : false;
    }

    
    public function editCuttingOrder($intCuttingOrderHeaderID)
    {
        $this->db->trans_start();

        $editDetails = false;

        $data = array(
            'vcOrderName' => $this->input->post('edit_order_name'),
            'intUserID' => $this->session->userdata('user_id'),
        );

        $this->db->where('intCuttingOrderHeaderID', $intCuttingOrderHeaderID);
        $this->db->update('CuttingOrderHeader', $data);

        $this->db->where('intCuttingOrderHeaderID', $intCuttingOrderHeaderID);
        $this->db->delete('CuttingOrderDetail');

        $item_count = count($this->input->post('description'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intCuttingOrderHeaderID' => $intCuttingOrderHeaderID,
                'vcSizeDescription' => $this->input->post('description')[$i],
                'decQty' => $this->input->post('qty')[$i]
            );
            $editDetails = $this->db->insert('CuttingOrderDetail', $items);
        }

        $this->db->trans_complete();

        return ($editDetails == true) ? true : false;
    }


    public function getCuttingOrderHeaderData($intCuttingOrderHeaderID = null)
    {
        if ($intCuttingOrderHeaderID) {
            $sql = "SELECT CH.intCuttingOrderHeaderID , CH.vcOrderName , CH.dtCreatedDate, ltrim(U.vcFullName) AS vcFullName
            FROM CuttingOrderHeader CH
            INNER JOIN User U ON CH.intUserID = U.intUserID
            WHERE CH.IsActive = 1 AND CH.intCuttingOrderHeaderID = ?";
            $query = $this->db->query($sql, array($intCuttingOrderHeaderID));
            return    $query->row_array();
        }
        $sql = "SELECT CH.intCuttingOrderHeaderID , CH.vcOrderName , CH.dtCreatedDate, ltrim(U.vcFullName) AS vcFullName
        FROM CuttingOrderHeader CH
        INNER JOIN User U ON CH.intUserID = U.intUserID
        WHERE CH.IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getCuttingOrderDetailData($intCuttingOrderHeaderID = null)
    {
        if ($intCuttingOrderHeaderID) {
            $sql = "SELECT  CH.intCuttingOrderHeaderID, 
            CH.vcOrderName,
            CH.dtCreatedDate,
            CD.intCuttingOrderDetailID,
            CD.vcSizeDescription,
            CD.decQty
              FROM CuttingOrderHeader CH
              INNER JOIN cuttingorderdetail CD ON CH.intCuttingOrderHeaderID = CD.intCuttingOrderHeaderID
              WHERE CH.IsActive = 1 AND CH.intCuttingOrderHeaderID = ?";
            $query = $this->db->query($sql, array($intCuttingOrderHeaderID));
            return  $query->result_array();
        }
    }

    public function removeCuttingOrder($intCuttingOrderHeaderID = null)
    {
        if ($intCuttingOrderHeaderID) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intCuttingOrderHeaderID', $intCuttingOrderHeaderID);
            $delete = $this->db->update('CuttingOrderHeader', $data);
            return ($delete == true) ? true : false;
        }
    }

    //-----------------------------------
    // Create Cutting Order Configuration
    //-----------------------------------

    public function cuttingOrderHeaderData()
    {
        $sql = "SELECT OH.intCuttingOrderHeaderID , OH.vcOrderName, OH.dtCreatedDate , U.vcFullName  
        FROM CuttingOrderHeader OH
        INNER JOIN user U ON OH.intUserID = U.intUserID
        WHERE OH.IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function SaveCuttingOrderConfiguration()
    {
        $this->db->trans_start();

        $insertDetails = false;
        $item_ID = $this->input->post('itemID');
        $item_count = count($this->input->post('cuttingorderID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intItemID' =>  $this->input->post('itemID')[$i],
                'intCuttingOrderHeaderID' => $this->input->post('cuttingorderID')[$i],
                'intUserID' => $this->session->userdata('user_id'),
            );
            $insertDetails = $this->db->insert('cuttingorderconfiguration', $items);
        }

        $this->db->trans_complete();

        return ($insertDetails == true) ? true : false;
    }

}
