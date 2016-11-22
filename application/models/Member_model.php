<?php
class Member_model extends My_Model {
    public function __construct()
	{
    parent::__construct();

	}

  /*
  public function getOne() {
    $this->get_where('member', array('m_id'))
  }*/

	public function get_member($user) {
		$user = explode(',', $user);
		if(count($user) == 1) {
	    		$query = $this->db->get_where('member', ['m_id' => $user[0]]);
	    		return $query->row_array();
	    	} else {
	    		$this->db->where_in('m_id', $user);
	    		$query = $this->db->get('member');
	    		return $query->result_array();
	    	}
	}

	public function total_count($table) {
	    $query = $this->db->query("select count(*) as cnt from ". $table);
	    $query = $query->row_array();
		return $query['cnt'];
	}

	public function section_list($table, $page_row, $from_record, $order_c='', $order=''){
	    if($order_c != '' && $order != ''){
		    $this->db->order_by($order_c, $order);
		}
		$this->db->limit($page_row, $from_record);
		$query = $this->db->get($table);

		return $query->result_array();
	}

	public function get_member_one($table, $where_c, $where_v) {
	    $query = $this->db->get_where($table, [$where_c => $where_v]);

		return $query->row_array();
	}

	public function isMember($where) {
		$query = $this->db->get_where('member', $where);

		return $query->row_array();
	}

	public function update($table, $data, $where_c, $where_v) {
	   $this->db->where($where_c, $where_v);
        $this->db->update($table, $data);
	}

	public function delete($table, $where_c, $where_v) {
	    $this->db->delete($table, [$where_c => $where_v]);
	}

	public function insert($table, $data) {
	    $this->db->insert($table, $data);
	}

	public function changeMemberLevel($id_list, $select_value) {
		  if(is_array($id_list)) {
				$this->db->where_in('m_id', $id_list);
      }else{
        $this->db->where('m_id', $id_list);
      }
      $this->db->update('member', ['m_level' => $select_value]);
	}

	public function banishMember($id_list) {
		$cnt = count($id_list);

		for($i=0; $i<$cnt; $i++) {
			if($id_list[$i] != 'admin') {
				$this->db->where('m_id', $id_list[$i]);
				$this->db->delete('member');
			}
		}
	}

	public function checkMember($id, $nick) {
		$this->db->where('m_id', $id);
		$this->db->or_where('m_name', $nick);
		$query = $this->db->get('member');
		$result = $query->num_rows();
		return $result;
	}

	public function changePass($userId, $pass) {
		$this->db->where('m_id', $userId);
		$query = $this->db->update('member', ['m_pass' => $pass]);

		return $query;
	}
}
