<?php

class Model_measureunit extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('measureunit', $data);
            return ($insert == true) ? true : false;
        }
    }

    /* get the get Measure Unit Data data */
    public function getMeasureUnitData($id = null, $isArray)
    {

        if ($id) {
            $sql = "SELECT intMeasureUnitID,vcMeasureUnit FROM measureunit WHERE intMeasureUnitID = ?";
            $query = $this->db->query($sql, array($id));
            if ($isArray == true) {
                return    $query->row_array();
            } else {
                return  $result = $query->result();
            }
        }

        $sql = "SELECT intMeasureUnitID,vcMeasureUnit FROM measureunit";
        $query = $this->db->query($sql);
        if ($isArray == true) {
            return $query->result_array();
        } else {
            $result = $query->result();
            return $result;
        }
    }

        /* get the get Item Type Data data */
        public function getItemTypeData($id = null, $isArray)
        {
            $sql = "SELECT intItemTypeID,vcItemTypeName FROM itemtype";
            $query = $this->db->query($sql);
            if ($isArray == true) {
                return $query->result_array();
            } else {
                $result = $query->result();
                return $result;
            }
        }
}
