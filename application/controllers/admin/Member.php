<?php

class Member extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'page']);
        $this->load->helper('alert');
        $this->load->model('member_model');
    }

    public function index()
    {
        // 페이지 변수 설정
         if ($this->uri->segment(3) != '' && $this->uri->segment(3) > 0) {
             $page = $this->uri->segment(3);
         } else {
             $page = 1;
         }

        $table = 'member';

        $config['code'] = $this->uri->segment(1).'/'.$this->uri->segment(2);
        $config['page'] = $page;
        $config['page_row'] = 10;
        $config['page_num_row'] = 10;
        $config['total_count'] = $this->member_model->total_count($table);

        $this->page->initialize($config);
        $from_record = $this->page->get_from_record();

        $data['page'] = $config['page'];
        $data['list'] = $this->member_model->section_list($table, $config['page_row'], $from_record, 'm_idx', 'desc'); // limit 기준점으로부터 회원목록 구하기
        $data['total_count'] = $config['total_count'];
        $data['paging_str'] = $this->page->createLink();

        $this->load->view('header');
        $this->load->view('admin/nav');
        $this->load->view('admin/member', $data);
        $this->load->view('footer');
    }

    public function changeLevel()
    {
        $referer = $this->input->post('referer');
        $id_list = $this->input->post('id_list');
        $select_value = $this->input->post('select_value');

        if(is_array($id_list)) {
          $index = array_search('admin', $id_list);
          if($index !== false)
            array_splice($id_list, $index, 1);
        }

        $this->member_model->changeMemberLevel($id_list, $select_value);

        if($referer != null) {
            alert('회원등급을 변경했습니다', $referer);
        }
    }

    public function banish()
    {
        $id_list = $this->input->post('id_list');
        $prev_url = $_SERVER['HTTP_REFERER'];
        $is_admin_url = strpos($prev_url, 'admin');

        if(!is_array($id_list)) $id_list = explode(',', $id_list);

        $this->member_model->banishMember($id_list);

        if($is_admin_url === false) {
          alert('해당 사용자를 강퇴시켰습니다.', $prev_url);
        }
    }

    public function refresh()
    {
        $page = $this->input->post('page');
        $table = 'member';

        $config['code'] = $this->uri->segment(1).'/'.$this->uri->segment(2);
        $config['page'] = $page;
        $config['page_row'] = 10;
        $config['page_num_row'] = 10;
        $config['total_count'] = $this->member_model->total_count($table);

        $this->page->initialize($config);
        $from_record = $this->page->get_from_record();

        $data['list'] = $this->member_model->section_list($table, $config['page_row'], $from_record, 'm_idx', 'desc'); // limit 기준점으로부터 회원목록 구하기
        $data['total_count'] = $config['total_count'];
        $data['paging_str'] = $this->page->createLink();

        $this->load->view('admin/member_list', $data);
    }

    public function changeLevelForm() {
      $data['id_list'] = $this->input->post('id_list');
      $data['referer'] = $_SERVER['HTTP_REFERER'];
      $member = $this->member_model->get_member_one('member', 'm_id', $data['id_list']);
      $data['level'] = (int) $member['m_level'];

      $this->load->view('header');
      $this->load->view('admin/changeLevelForm', $data);
      $this->load->view('footer');
    }

}
