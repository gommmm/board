<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common
{
    public $CI;

    public function __construct() {
      $this->CI = & get_instance();
      $this->CI->load->library('session');
      $this->CI->load->helper('url');
    }

    function init()
    {
      if($this->CI->uri->segment(1) !== 'install') {
        $this->CI->load->model(['member_model','message_model','menu_model']);
        // 불러올 컨텐츠 설정
        $directory = $this->CI->router->directory;
        $class = $this->CI->router->class;
        $method = $this->CI->router->method;
        $segment = $this->CI->segment;
        $code = !empty($segment) ? $segment[1] : '';
        $page = in_array($method, ['reply', 'modify']) ? 'write' : $method;
        $id = $this->CI->session->userdata('user_id');

        $this->CI->menu_list = $this->CI->menu_model->getMenu();

        if($code != '') {
            $this->CI->menu = $this->CI->menu_model->getMenuOne(['bc_code' => $code]);
            $this->CI->board_name = $this->CI->menu['bc_name'];

            if($page == 'index')
                $this->CI->content = $this->CI->menu['type'] == 'normal' ? 'board' : 'img_board';
            else
                $this->CI->content = $page;
        } else {
          $this->CI->board_name = '최근 등록한 게시물';
          $this->CI->content = 'latest';
        }

        // css 및 js 설정
        if($directory == 'admin/')
            $path = $directory.$class;
        else
            $path = $directory.$page;

        $this->CI->config->load('resource');
        $resource = $this->CI->config->item($path);
        $data = [];

        if(!empty($resource)) {
            foreach($resource as $key => $val) {
              $data[$key] = $val;
            }
        }

        // 새 메세지 수 가져오기
        $data['msgCnt'] = $this->CI->message_model->getNewMessage($id);

        $this->CI->load->vars($data);

        // 로그인 하지 않았을 때 로그인이 필요한 페이지면 로그인 폼으로 리다이렉트
        if(isset($this->CI->allow) && is_array($this->CI->allow) && in_array($method, $this->CI->allow)) {
          if($this->CI->session->userdata('user_id') == null)
              redirect(MAIN_URL."/login");
        }

        // 중복 로그인 처리
        $member = $this->CI->member_model->get_member($id);
        $login_session = $this->CI->session->userdata('login_session');

        if($method != 'logout')
            if($login_session && ($member['login_session'] != $login_session))
                redirect(MAIN_URL.'/logout?reason=multiLogin');
    }

   }
}
