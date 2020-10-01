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
}
