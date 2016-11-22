<?php
class Chat extends MY_Controller {
    public function __construct() {
	    parent::__construct();
      $this->load->model(['member_model', 'chat_model']);
    }

    public function index() {
      $data['id'] = $this->uri->segment(2);
      $data['receiverId'] = $this->uri->segment(3);
      $member = $this->member_model->get_member($data['id']);
      $data['message'] = $this->chat_model->getMessage($data['id'], $data['receiverId']);
      $data['nickname'] = $member['m_name'];

      $this->load->view('header');
      $this->load->view('chat', $data);
      $this->load->view('footer');
    }

    public function save() {
      $json = file_get_contents('php://input');
      $data = json_decode($json);
      $data->date = date('Y-m-d h:i:s');

      $this->chat_model->add($data);
    }
}
