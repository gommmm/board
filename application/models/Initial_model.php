<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Initial_model extends My_Model {
    public function __construct()
	  {
		    $this->load->dbforge();
	  }

	public function create($data) {
      $tables = ['board', 'board_config', 'chat', 'comment', 'file', 'member', 'message'];

      // 테이블 존재여부 체크 후 존재하면 삭제
      for($i=0; $i<count($tables); $i++) {
        if($this->db->table_exists($tables[$i]) == true)
            $this->dbforge->drop_table($tables[$i], true);
      }

      $this->db->trans_start();

      $this->queryFile($data['sql']); // .sql 파일 통째로 쿼리처리하기위한 메소드 호출
      $this->db->insert('member', $data['admin']); // 관리자 계정 생성쿼리

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE)
      {
        for($i=0; $i<count($tables); $i++) {
            $this->dbforge->drop_table($tables[$i], true);
        }

        return false;
      }

      return true;
	}

    private function queryFile($sql)
    {
        $error = 0;

        $lines = explode("\n", $sql);

        $templine = '';

        /*foreach ($lines as $line)
        {
            $line .= ' ';

            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            $templine .= $line;

            if (substr(trim($line), -1, 1) == ';')
            {
                $result = $this->db->query($templine);

				if($result != TRUE) $error++;

                $templine = '';
            }
        }*/

        foreach ($lines as $line)
        {
            $line .= ' ';

            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            $templine .= $line;

            if (substr(trim($line), -1, 1) == ';')
            {
                $result = $this->db->query($templine);

                $templine = '';
            }
        }
    }
}
