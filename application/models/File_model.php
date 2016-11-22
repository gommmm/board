<?php defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends My_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'file';
    }

    public function insert($data) {
      $this->db->insert_batch($this->table, $data);
    }

    public function getFile($id) {
      $this->db->where('b_idx', $id);
      $query = $this->db->get($this->table);
      return $query->result_array();
    }

    public function getFileOne($id) {
      $this->db->where('id', $id);

      $query = $this->db->get($this->table);
      return $query->row_array();
    }

    public function delete($id) {
      $this->db->where_in('id', $id);
      $this->db->delete($this->table);
    }
}
