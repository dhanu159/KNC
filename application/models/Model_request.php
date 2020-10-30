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

    public function SaveRequestItem()
    {
        $this->db->trans_start();

        $query = $this->db->query("SELECT fnGenerateGRNNo() AS GRNNo");

        $ret = $query->row();
        $GRNNo = $ret->GRNNo;


        $insertDetails = false;

        $data = array(
            'vcGRNNo' => $GRNNo,
            'vcInvoiceNo' => $this->input->post('invoice_no'),
            'intSupplierID' => $this->input->post('supplier'),
            'dtReceivedDate' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('receivedDate')))),
            'intUserID' => $this->session->userdata('user_id'),
            'decSubTotal' => $this->input->post('subTotal'),
            'decDiscount' => $this->input->post('txtDiscount'),
            'decGrandTotal' => $this->input->post('grandTotal'),
        );

        $insert = $this->db->insert('GRNHeader', $data);
        $GRNHeaderID = $this->db->insert_id();

        $item_count = count($this->input->post('itemID'));

        for ($i = 0; $i < $item_count; $i++) {
            $items = array(
                'intGRNHeaderID' => $GRNHeaderID,
                'intItemID' => $this->input->post('itemID')[$i],
                'decQty' => $this->input->post('itemQty')[$i],
                'decUnitPrice' => $this->input->post('unitPrice')[$i],
                'decTotalPrice' => $this->input->post('totalPrice')[$i]
            );
            $insertDetails = $this->db->insert('GRNDetail', $items);
        }

        $this->db->trans_complete();

        return ($insertDetails == true) ? true : false;
    }
    
}
