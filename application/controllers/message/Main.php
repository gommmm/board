<?php

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('basic_helper');
        $this->load->helper('alert_helper');
        $this->load->library('page');
        $this->load->model('message_model');
    }

    public function index()
    {
        $userId = $this->session->userdata('user_id');
        $mode = $this->input->get('mode');
        $seg = $this->uri->segment(2);

        $queryStr = $mode != '' ? '?' : '';
        $queryStr .= $_SERVER['QUERY_STRING'];

        $config['code'] = 'message';
        $config['page'] = $seg != '' ? $seg : '1'; // 페이지 번호
        $config['page_row'] = 10;
        $config['page_num_row'] = 10;
        $config['total_count'] = $this->message_model->total_count($userId, $mode);
        $config['use_query_string'] = true;

        $this->page->initialize($config);

        $from_record = $this->page->get_from_record();
        $data['message'] = $this->message_model->getMessageList($userId, $mode, $config['page_row'], $from_record);
        $data['pagination'] = $this->page->createLink();
        $data['mode'] = $mode != '' ? $mode : 'recv';

        $this->load->view('header');
        $this->load->view('message/main', $data);
        $this->load->view('footer');
    }

    public function send()
    {
        if ($this->input->post('param') == null) {
            if ($this->input->get('receiverId') != null) {
                $receiverId = $this->input->get('receiverId');
                $nickname = $this->input->post('nickname') != null ? $this->input->post('nickname') : $this->session->userdata('receiverNick');
            } else {
                $receiverId = $this->input->post('id_list');
                $nickname = $this->input->post('nick_list');
            }

            $data['senderId'] = $this->session->userdata('user_id');
            $data['senderNick'] = $this->session->userdata('user_name');
            $data['receiverId'] = $receiverId;
            $data['receiverNick'] = $nickname;
            $this->session->unset_userdata('receiverNick');

            $this->load->view('header');
            $this->load->view('message/sendForm', $data);
            $this->load->view('footer');
        } else {
            $messageInfo = $this->input->post('param');
            $result = $this->message_model->saveMessage($messageInfo);

            if ($result == true) {
                echo json_encode($messageInfo['receiverId']);
            }
        }
    }

    public function view()
    {
        $no = $this->uri->segment(3);
        $mode = $this->input->get('mode');

        if ($mode == 'recv') {
            $this->message_model->updateRead($no);
        }

        $data['mode'] = $mode;
        $data['message'] = $this->message_model->getMessage($no);

        $this->session->set_userdata('receiverNick', $data['message']['senderNick']);
      // 쪽지를 보낼때는 post로 처리해줬었는데 답장을 할때는 링크를 통해야되서 세션을 이용해 닉네임을 저장했다.

        $this->load->view('header');
        $this->load->view('message/message_view', $data);
        $this->load->view('footer');
    }

    public function delete()
    {   // 링크를 직접입력하면 세션값이 일치해야 삭제되야되는데 아직 그 부분은 해결 못함. 나중에 해결바람.
        $no = $this->uri->segment(3);
        $mode = $this->input->get('mode');
        $user_id = $this->session->userdata('user_id');

      /*if($message['receiverId'] != $user_id && $message['senderId'] == $user_id) {
        alert('아이디가 틀립니다.');
      }*/

        $this->message_model->updateDelete($no, $mode);
        $message = $this->message_model->getMessage($no);

        if ($message['sender_deleted'] == 1 && $message['receiver_deleted'] == 1) {
            $this->message_model->deleteMessage($no);
        }

        alert('쪽지를 삭제했습니다.', MAIN_URL.'/message?mode='.$mode);
    }
}
