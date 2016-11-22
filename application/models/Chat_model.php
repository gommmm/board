<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends My_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'chat';
    }

    public function add($data) {
      $this->db->insert($this->table, $data);
    }

    public function getMessage($id, $receiverId) {
      $query = $this->db->select('*')->from($this->table)
                ->where('date between (CURDATE() - INTERVAL 1 day) and now()')
                ->group_start()
                ->where('senderId', $id)
                ->where('receiverId', $receiverId)
                ->group_end()
                ->or_group_start()
                ->where('senderId', $receiverId)
                ->where('receiverId', $id)
                ->group_end()
                ->order_by('date')
                ->get();
      $result = $query->result_array();
      return $result;
    }
}
