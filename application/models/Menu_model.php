<?php
class Menu_model extends My_Model {
    public function __construct()
    {
        parent::__construct();

        $this->table = 'board_config';
	  }

    public function getMenu()
    {
        $this->db->order_by('seq', 'ASC');
        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    public function getMenuOne($where)
    {
        $query = $this->db->get_where($this->table, $where);

        return $query->row_array();
    }

    public function addMenu($menus)
    {
        $id_list = [];
        $cnt = count($menus);

        $this->db->db_debug = FALSE;
        $this->db->trans_start();
        for ($i = 0; $i < $cnt; ++$i) {
            $data = [
                'is_group' => $menus[$i]['is_group'],
                'bc_code' => $menus[$i]['bc_code'],
                'type' => $menus[$i]['type'],
                'bc_name' => $menus[$i]['bc_name'],
                'bc_read_level' => $menus[$i]['bc_read_level'],
                'bc_write_level' => $menus[$i]['bc_write_level'],
                'bc_comment_level' => $menus[$i]['bc_comment_level'],
                'indent' => $menus[$i]['indent'],
            ];

              $query = $this->db->insert($this->table, $data);

              if($this->db->trans_status() === FALSE)
              {
                  return false;
              } else {
                  $id = $this->db->insert_id();
                  array_push($id_list, $id);
              }
        }
        $this->db->trans_complete();

        return $id_list;
    }

    public function delMenu($menuids)
    {
        $cnt = count($menuids);

        for ($i = 0; $i < $cnt; ++$i) {
            $this->db->delete($this->table, ['bc_idx' => $menuids[$i]]);
        }
    }

    public function updateMenu($menus)
    {
        $cnt = count($menus);

        for ($i = 0; $i < $cnt; ++$i) {
            $id = $menus[$i]['bc_idx'];
            $data = [
                'bc_code' => $menus[$i]['bc_code'],
                'type' => $menus[$i]['type'],
                'bc_name' => $menus[$i]['bc_name'],
                'bc_read_level' => $menus[$i]['bc_read_level'],
                'bc_write_level' => $menus[$i]['bc_write_level'],
                'bc_comment_level' => $menus[$i]['bc_comment_level'],
                'indent' => $menus[$i]['indent'],
            ];

            $this->db->where('bc_idx', $id);
            $this->db->update($this->table, $data);
        }
    }

    public function updateMenuSeq($menuid_order, $add_id_lists)
    {
        $cnt = count($menuid_order);
        $id = '';
        $seq = 1;

        for ($i = 0; $i < $cnt; ++$i) {
            if ($menuid_order[$i] == -1) {
                $id = array_shift($add_id_lists);
            } else {
                $id = $menuid_order[$i];
            }

            $this->db->where('bc_idx', $id);
            $this->db->update($this->table, ['seq' => $seq]);

            ++$seq;
        }
    }
}
