<?php 

class Model_groups extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getGroupData($groupId = null) 
	{
		if($groupId) {
			$sql = "SELECT * FROM UserGroup WHERE intUserGroupID = ?";
			$query = $this->db->query($sql, array($groupId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM UserGroup WHERE intUserGroupID != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data = '')
	{
		$create = $this->db->insert('UserGroup', $data);
		return ($create == true) ? true : false;
	}

	public function edit($data, $id)
	{
		$this->db->where('intUserGroupID', $id);
		$update = $this->db->update('UserGroup', $data);
		return ($update == true) ? true : false;	
	}

	public function delete($id)
	{
		$this->db->where('intUserGroupID', $id);
		$delete = $this->db->delete('UserGroup');
		return ($delete == true) ? true : false;
	}

	public function existInUserGroup($id)
	{
		$sql = "SELECT * FROM UserGroup WHERE group_id = ?";
		$query = $this->db->query($sql, array($id));
		return ($query->num_rows() == 1) ? true : false;
	}

	public function getUserGroupByUserId($user_id) 
	{
		$sql = "SELECT * FROM UserGroup AS UG
		INNER JOIN User AS U ON UG.intUserGroupID = U.intUserGroupID 
		WHERE U.user_id = ?";
		$query = $this->db->query($sql, array($user_id));
		$result = $query->row_array();

		return $result;

	}
}