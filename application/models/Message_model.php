<?php
class Message_model extends My_Model {
    public function __construct()
	{
    parent::__construct();

    $this->table = 'message';
	}

	public function saveMessage($info) {
		$senderId = $info['senderId'];
		$senderNick = $info['senderNick'];
		$receiverId = $info['receiverId'];
		$receiverNick = $info['receiverNick'];
		$content = $info['content'];
		$len = count($receiverId);
		$date = date('Y-m-d H:i:s', time());

		for($i=0; $i<$len; $i++) {
			$this->db->set('senderId', $senderId);
			$this->db->set('senderNick', $senderNick);
			$this->db->set('receiverId', $receiverId[$i]);
			$this->db->set('receiverNick', $receiverNick[$i]);
			$this->db->set('content', $content);
			$this->db->set('write_time', $date);
			$result = $this->db->insert('message');
		}

    if($result == TRUE) {
      return TRUE;
    }

    return FALSE;
	}

  public function total_count($id, $mode) {
    if($mode == NULL || $mode == 'recv') {
      $where = 'receiverId';
      $where2 = 'receiver_deleted';
    } elseif($mode == 'send') {
      $where = 'senderId';
      $where2 = 'sender_deleted';
    }

    $sql = "select count(*) as cnt from message where $where = '$id' and $where2 <> 1";

    $query = $this->db->query($sql);
    $res = $query->row_array();

    return $res['cnt'];
  }

	public function getMessageList($id, $mode, $page_row, $from_record) {
		if($mode == NULL || $mode == 'recv') {
      $targetId = 'receiverId';
      $deleted = 'receiver_deleted !=';
		} else {
      $targetId = 'senderId';
      $deleted = 'sender_deleted !=';
		}

    $this->db->where($targetId, $id);
    $this->db->where($deleted, 1);
    $this->db->limit($page_row, $from_record);
    $this->db->order_by('write_time DESC');

		$query = $this->db->get('message');
		$result = $query->result_array();
		return $result;
	}

	public function getMessage($no) {
		$query = $this->db->get_where('message', ['no' => $no]);
		$result = $query->row_array();
		return $result;
	}

	public function updateRead($no) {
		$message = $this->getMessage($no);

		$date = date('Y-m-d H:i:s', time());

		if($message['read_message'] == 0) {
			$date = date('Y-m-d H:i:s', time());
			$data = [
               'read_time' => $date,
							 'read_message' => 1
              ];
			$this->db->where('no', $no);
			$this->db->update('message', $data);
		}
	}

	public function deleteMessage($no) {
		$this->db->delete('message', ['no' => $no]);
	}

	public function updateDelete($no, $mode) {
		if($mode == 'recv') {
			$column = 'receiver_deleted';
		} else {
			$column = 'sender_deleted';
		}

		$this->db->set($column, 1);
		$this->db->where('no', $no);
		$this->db->update('message');
	}
}
