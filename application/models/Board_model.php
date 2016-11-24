<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Board_model extends My_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'board';
    }

    public function getChildPost($id_list) {
      if(is_array($id_list))
      $this->db->where_in('parent_id', $id_list);
      else
      $this->db->where('parent_id', $id_list);

      $query = $this->db->get($this->table);
      $result = $query->result_array();

      $data = [];

      foreach($result as $index => $row) {
        array_push($data, $row['b_idx']);
      }

      return $data;
    }

    public function getLatestList($board_name)
    {
        $this->db->where('b_reply', '');
        $this->db->order_by('b_idx', 'DESC');
        $this->db->limit(12);
        $query = $this->db->get($board_name);

        return $query->result_array();
    }

    public function total_count($table, $where_c = '', $where_v = '')
    {
        $sql = 'select count(*) as cnt from '.$table;

        if ($where_c != '' && $where_v != '') {
            $sql .= ' where '.$where_c."= '".$where_v."'";
        }

        $query = $this->db->query($sql);
        $query = $query->row_array();

        return $query['cnt'];
    }

    public function getNoticeList($board_code) {
      $this->db->order_by('b_idx', 'DESC');
      $query = $this->db->get_where($this->table, ['bc_code' => $board_code, 'notice' => 1]);
      return $query->result_array();
    }

    // 지정한 페이지 시작 레코드 값과 가져올 레코드 값의 수에 따라 게시물 리스트를 가져오는 메소드이다.
    public function getList($where_v, $page_row, $limit_start, $search = '', $word = '')
    {
        $this->db->select('SQL_CALC_FOUND_ROWS board.*, ifnull(count(file.id),0) as file_count', false);
        $this->db->distinct();
        $this->db->from('board');
        $this->db->join('file', 'board.b_idx = file.b_idx', 'left');
        $this->db->where('bc_code', $where_v);
        $this->db->group_by('board.b_idx');
        $this->db->order_by('b_num', 'DESC');
        $this->db->order_by('b_reply', 'ASC');
        $this->db->limit($page_row, $limit_start);
        $query = $this->db->get();

        $result['list'] = $query->result_array();
        $query = $this->db->query( "SELECT FOUND_ROWS() as cnt" );
        $result['total_count'] = $query->row(0)->cnt;

        return $result;
    }

    public function select_writing($b_idx, $bc_code = '')
    {
        if ($bc_code != '') {
            $this->db->where('bc_code', $bc_code);
        }
        $this->db->where('b_idx', $b_idx);
        $this->db->order_by('b_reply', 'DESC');
        $query = $this->db->get('board');

        return $query->row_array();
    }

    public function select_reply($bc_code, $b_num, $b_reply, $check)
    {
        $this->db->where('bc_code', $bc_code);
        $this->db->where('b_num', $b_num);
        $this->db->like('b_reply', $b_reply, 'after');
        $this->db->order_by('b_reply', 'DESC');
        $query = $this->db->get('board');
        if ($check == 'y') {
            return $query->result_array();
        } else {
            return $query->row_array();
        }
    }

    // 이 메소드는 컨트롤에서 현재 두 번 호출하고 있는데 union all을 사용해서 한 번 호출하게 변경하려고 한다.
    public function get_size_post($b_idx, $sign, $bc_code, $order)
    {
        $this->db->where('bc_code', $bc_code);
        $this->db->where('b_reply', '');
        $this->db->where('b_idx '.$sign, $b_idx);
        $this->db->order_by('b_idx', $order);
        $query = $this->db->get('board', 1, 0);
        $result = $query->row_array();

        return $result;
    }

    public function get_near_posts($b_num, $bc_code)
    {
        $this->db->where('bc_code', $bc_code);
        $this->db->where('b_num', $b_num);
        $this->db->order_by('b_reply', 'asc');
        $query = $this->db->get('board');
        $result = $query->result_array();

        return $result;
    }

    // 답변을 삭제할 때 답변의 답변이 있을 시 그 답변의 답변까지 삭제하는 메소드
    public function del_incReply($bc_code, $b_num, $b_reply)
    {
        $this->db->where('bc_code', $bc_code);
        $this->db->where('b_num', $b_num);
        $this->db->like('b_reply', $b_reply, 'after');
        $this->db->delete('board');
    }

    public function board_search($search, $set_print)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS board.*, ifnull(count(file.id),0) as file_count', false);
        $this->db->distinct();
        $this->db->where('bc_code', BOARDCODE);

        // 이미 comment테이블에서 검색해서 list가 매개변수로 왔을 때를 위한 분기처리
        if (isset($search['is_list']) && $search['is_list'] == true) {
            $this->db->where_in('board.b_idx', $search['list']);
        } else {
            $count = count($search['column']);
            $key = array_search('name', $search['column']);

            if($key !== false) {
              $this->db->where($search['column']["{$key}"], $search['word']);
            } else {
                  for($i=0; $i<$count; $i++) {
                      if($i == 0) {
                        $this->db->group_start();
                        $this->db->like($search['column']["{$i}"], $search['word']);
                      }

                      $this->db->or_like($search['column']["{$i}"], $search['word']);

                      if($i == $count-1) $this->db->group_end();
                  }
            }

            $count = count($search['date']);

            if ($count > 0 && $count < 2) {
                $this->db->where('b_regdate between (CURDATE() - INTERVAL '.$search['date'].') and now()');
            } elseif($count == 2){
                $this->db->where("date_format(b_regdate,'%Y-%m-%d') BETWEEN '".$search['date']['to']."' AND '".$search['date']['from']."'");
            }
        } // 분기처리 끝

        $this->db->order_by('b_num', 'desc');
        $this->db->order_by('b_reply', 'asc');
        $this->db->limit($set_print['page_row'], $set_print['from_record']);
        $this->db->from($this->table);
        $this->db->join('file', 'board.b_idx = file.b_idx', 'left');
        $this->db->group_by('board.b_idx');
        $query = $this->db->get();
        $result['list'] = $query->result_array();

        $query = $this->db->query( "SELECT FOUND_ROWS() as cnt" );
        $result['total_count'] = $query->row(0)->cnt;

        return $result;
    }

    // 삭제 예정 (My_model로 이동)
    public function insert($table, $data)
    {
        $this->db->insert($table, $data);

        return $this->db->insert_id();
    }

    // 삭제 예정 (My_model로 이동)
    public function update($table, $data, $where_c, $where_v)
    {
        if(is_array($where_v))
        $this->db->where_in($where_c, $where_v);
        else
        $this->db->where($where_c, $where_v);
        $this->db->update($table, $data);
    }

    public function updateCount($b_idx) {
        $this->db->set('c_cnt', 'c_cnt-1', false);
        $this->db->where('b_idx', $b_idx);
        $this->db->update('board');
    }

    // 삭제 예정 (My_model로 이동)
    public function delete($table, $where_c, $where_v)
    {
        if(is_array($where_v))
        $this->db->where_in($where_c, $where_v);
        else
        $this->db->where($where_c, $where_v);
        $this->db->delete($this->table);
    }

}
