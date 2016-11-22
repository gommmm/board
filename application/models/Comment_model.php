<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends My_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'comment';
    }

    public function getCommentOne($c_idx)
    {
        $query = $this->db->get_where($this->table, ['c_idx' => $c_idx]);

        return $query->row_array();
    }

    public function getComment($b_idx)
    {
        $this->db->order_by('cp_idx', 'ASC');
        $this->db->order_by('c_seq', 'ASC');
        $query = $this->db->get_where($this->table, ['b_idx' => $b_idx]);

        return $query->result_array();
    }

    public function getMaxCommentSeq($b_idx, $cp_idx)
    {
        $query = $this->db->query('select MAX(c_seq) as csm from '. $this->table .' where b_idx = '.$b_idx.' and cp_idx = '.$cp_idx);

        return $query->row_array();
    }

    public function getParentCommentId($b_idx, $cp_idx)
    {
        $query = $this->db->query('select MIN(c_idx) as ref_id from '. $this->table .' where b_idx = '.$b_idx.' and cp_idx = '.$cp_idx);

        return $query->row_array();
    }

    public function getMaxParentId($b_idx)
    {
        $query = $this->db->query('select MAX(cp_idx) as maxParentNo from '. $this->table .' where b_idx = '.$b_idx);

        return $query->row_array();
    }

    public function getTotalCount($table, $where_c = '', $where_v = '')
    {
        $sql = 'select count(*) as cnt from '.$table;

        if ($where_c != '' && $where_v != '') {
            $sql .= ' where '.$where_c."= '".$where_v."'";
        }

        $query = $this->db->query($sql);
        $query = $query->row_array();

        return $query['cnt'];
    }

    public function getChildCommentCount($cmt_id)
    {
        $query = $this->db->query("select count(*) as cnt from $this->table where p_idx= '$cmt_id'");
        $result = $query->row_array();

        return $result['cnt'];
    }

    public function deleteComment($cmt_id)
    {
        $this->db->delete($this->table, ['c_idx' => $cmt_id]);
    }

    public function getSearchComment($search)
    {
        $arr = [];

        $this->db->select('b_idx');
        $this->db->distinct();

        if (in_array('content', $search['column'])) {
            $this->db->like($search['column'][0], $search['word']);
        } else {
            $this->db->where($search['column'][0], $search['word']);
        }

        $count = count($search['date']);

        if ($count > 0 && $count < 2) {
            $this->db->where('c_regdate between (CURDATE() - INTERVAL '.$search['date'].') and now()');
        } elseif($count == 2) {
            $this->db->where("date_format(c_regdate,'%Y-%m-%d') BETWEEN '".$search['date']['to']."' AND '".$search['date']['from']."'");
        }

        $query = $this->db->get($this->table);

        $result = $query->result_array();

        if(!empty($result)) {
            foreach($result as $key => $row) {
                 $list['list']["{$key}"] = $row['b_idx'];
            }
            $list['is_list'] = true;
        }

        return isset($list) ? $list : null;
    }

    public function insert($table, $data)
    {
        $this->db->insert($table, $data);

        return $this->db->insert_id();
    }

    public function update($table, $data, $where_c, $where_v)
    {
        if(is_array($where_v))
        $this->db->where_in($where_c, $where_v);
        else
        $this->db->where($where_c, $where_v);
        $this->db->update($this->table, $data);
    }

    public function delete($table, $where_c, $where_v)
    {
        if(is_array($where_v))
        $this->db->where_in($where_c, $where_v);
        else
        $this->db->where($where_c, $where_v);
        $this->db->delete($this->table);
    }
}
