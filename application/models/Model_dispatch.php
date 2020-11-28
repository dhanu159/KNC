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

            for ($x = $i; $x < ($item_count - $i); $x++) {
                if (!in_array($itemID, $itemArray)) {
                    if ($itemID == $this->input->post('itemID')[$x]) {

                        $dispatchQty += $this->input->post('itemQty')[$x];
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
                $this->db->trans_commit();
                $response['success'] = true;
                $response['messages'] = 'Succesfully created !';
            }
        }

        return $response;
    }
}
