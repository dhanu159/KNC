<?php

class Model_supplier extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('supplier', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {

            $this->db->trans_start();
            $sql = "SELECT intSupplierID, vcSupplierName, vcAddress, vcContactNo, dtCreatedDate, decCreditLimit, decAvailableCredit, IsActive, intUserID, rv FROM supplier WHERE intSupplierID = ? ";
            $query = $this->db->query($sql, array($id));
            if ($query->num_rows()) {
                $this->db->insert('supplier_his', $query->row_array());
                $insert_id = $this->db->insert_id();
                $this->db->where('intSupplier_hisID', $insert_id);
                $update = $this->db->update('supplier_his', array('intEnteredBy' => $this->session->userdata('user_id')));
    
                $this->db->where('intSupplierID', $id);
                $update = $this->db->update('supplier', $data);
    
                $this->db->trans_complete();
                return ($update == true) ? true : false;
            }
        }
    }

    /* get the Supplier data */
    public function getSupplierData($id = null)
    {
        if ($id) {
            $sql = "SELECT intSupplierID,vcSupplierName,vcAddress,vcContactNo,decCreditLimit,decAvailableCredit,rv FROM supplier WHERE intSupplierID = ? AND IsActive = 1";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT intSupplierID,vcSupplierName,vcAddress,vcContactNo,decCreditLimit,decAvailableCredit,rv FROM supplier WHERE IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();

    }


    public function remove($id)
    {
        if ($id) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intSupplierID', $id);
            $delete = $this->db->update('supplier', $data);
            return ($delete == true) ? true : false;
        }
    }

    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intSupplierID  FROM grnheader WHERE intSupplierID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }

    public function chkRv($id = null)
    {
        if ($id) {
            $sql = "SELECT rv FROM `supplier` WHERE intSupplierID = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }
    }
}
