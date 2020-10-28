<?php
class Model_customer extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('customer', $data);
            return ($insert == true) ? true : false;
        }
    }

    /* get the Customer data */
    public function getCustomerData($id = null)
    {
        if ($id) {
            $sql = "SELECT intCustomerID,vcCustomerName,vcAddress,vcContactNo1,vcContactNo2,decCreditLimit FROM customer WHERE intCustomerID = ? AND IsActive = 1";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT intCustomerID,vcCustomerName,vcAddress,vcContactNo1,vcContactNo2,decCreditLimit FROM customer WHERE IsActive = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function insertCustomerHitory($intEnteredBy, $id)
    {
        $this->db->trans_start();
        $sql = "SELECT intCustomerID,vcCustomerName,vcAddress,vcContactNo1,vcContactNo2,decCreditLimit,IsActive,intUserID,dtCreatedDate FROM customer WHERE intCustomerID = ? ";
        $query = $this->db->query($sql, array($id));
        if ($query->num_rows()) {
            $this->db->insert('customer_his', $query->row_array());
            $insert_id = $this->db->insert_id();
            $this->db->where('intCustomer_hisID', $insert_id);
            $update = $this->db->update('customer_his', $intEnteredBy);
            $this->db->trans_complete();
            return ($update == true) ? true : false;
        }
       
    }
    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intCustomerID  FROM issu WHERE intCustomerID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
	}

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('intCustomerID', $id);
            $update = $this->db->update('customer', $data);
            return ($update == true) ? true : false;
        }
    }
}
