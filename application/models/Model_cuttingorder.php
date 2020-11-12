<?php
class Model_cuttingorder extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

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
}
