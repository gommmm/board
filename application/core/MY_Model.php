<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    public $table;

    function __construct()
    {
        parent::__construct();

        // db정보를 불러와서 db설정
  	    $this->load->helper('initialize');
  	    $dbconfig = loadDbConfig();

        mysqli_report(MYSQLI_REPORT_STRICT);

        try {
          $this->load->database($dbconfig);

        } catch (Exception $e)  { show_error("db 설정정보가 맞는지 확인해주세요.", 500, 'Database Error'); }

    }

    /* 복잡한 모델을 사용하는게 아니면 여기서 사용

    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($data, $where_c, $where_v)
    {
        $this->db->where($where_c, $where_v);
        $this->db->update($this->table, $data);
    }

    public function delete($where_column, $where_value)
    {
        $this->db->delete($this->table, array($where_column => $where_value));
    }

    */

  /*
  function get_list($param = NULL)
  {
      $this->db->select( "SQL_CALC_FOUND_ROWS *", false );

      if( !isset( $param['page'] ) )
      {
          $param['page'] = 1;
      }

      $this->db->order_by('idx DESC');

      if( isset( $param['limit'] ) )
      {
          $result = $this->db->get( _TBL, $param['limit'], ( ( $param['page'] - 1 ) * $param['limit'] ) );
      }
      else
      {
          $result = $this->db->get( _TBL );
      }

      $return['list'] = $result->result_array();

      $result = $this->db->query( "SELECT FOUND_ROWS() as cnt" );
      $return['count'] = $result->row( 0 )->cnt;

      return $return;
  }

  function get_one($idx = NULL )
  {
      $this->db->where( 'idx', $idx );
      $result = $this->db->get( _TBL );
      if( $result->num_rows() > 0 )
      {
          return $result->row_array();
      }
      else
      {
          return false;
      }
  }

  function create( $param = NULL )
  {
      foreach( $param as $k => $v )
      {
          $this->db->set( $k, $v );
      }
      $this->db->insert( _TBL );

      return $this->db->insert_id();
  }

  public function update($idx = NULL, $param = NULL)
  {
      foreach( $param as $k => $v )
      {
          $this->db->set( $k, $v );
      }
      $this->db->where( 'idx', $idx );
      $this->db->update( _TBL );
  }
  */
}
