<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
원래는 이름이 Pagination이어야 되지만
이미 codeigniter 라이브러리로 제공되기 때문에 원리를 이해하기 위해서 만들었다.
*/

class Page {
    protected $ci;
    protected $page = 1;
    protected $page_row = 10;
    protected $page_num_row = 10;
    protected $total_count = 0;
    protected $code = '';
    protected $query_string = '';
    protected $total_page;
    protected $start_page;
    protected $first_page = 1;
    protected $end_page;
    protected $link;

  // 계산이 필요해 total_page, start_page, end_page

    public function __construct() {
        $this->ci = & get_instance();
        $this->page = 1;
        $this->page_row = 10;
    }

    public function initialize($config) {
      foreach($config as $name => $arg) {
        $method = 'set_'.$name;
        if(method_exists($this, $method)) {
            $this->$method($arg);
        }

        $this->_set_from_record();
        $this->_set_total_page();
        $this->_set_start_page();
        $this->_set_end_page();
      }
    }

    /* 페이지 값을 설정하는 방법이 2가지다.
       첫번째는 새그먼트 방식으로 page를 만들었을시 새그먼트 index 번호를 지정해준다.
       두번째는 get으로 page값을 넘겼을 떄, page값을 직접 지정해주는 방식이다.
    */
    public function set_uri_segment($index) {
      $page = (int) $this->ci->uri->segment($index);
      if($page > 0)
          $this->set_page($page);
      else $this->set_page(1);
    }

    public function set_page($page) {
      $page = (int) $page;

      if($page > 0) {
        $this->page = $page;
      } else {
        $this->page = 1;
      }
    }

    public function set_page_row($page_row) {
      $this->page_row = (int) $page_row;
    }

    public function set_page_num_row($page_num_row) {
      $this->page_num_row = (int) $page_num_row;
    }

    public function set_total_count($total_count) {
      $this->total_count = $total_count;
    }

    public function set_code($code) {
      $this->code = $code;
    }

    public function set_use_query_string($use_query_string) {
        if($use_query_string == true)
            $this->query_string = '?'.$this->ci->input->server('QUERY_STRING');
    }

    /* set 앞에 _이 붙은 메소드들은 설정을 하는 메소드이긴 하지만
       계산에 의해 설정을 하는 메소드이기 때문에
       초기화 메소드에서 컨트롤러에서 설정한 값에 의해서
       동적으로 메소드를 호출하는 것을 막기위해서 _를 붙였다.
    */
    public function _set_from_record() {
      $this->from_record = ($this->page - 1) * $this->page_row;
    }

    public function get_from_record() {
      return $this->from_record;
    }

    public function _set_total_page() {
      $this->total_page = ceil($this->total_count / $this->page_row);
    }

    public function _set_start_page() {
      $this->start_page = ((ceil($this->page / $this->page_num_row) - 1) * $this->page_num_row) + 1;
    }

    public function _set_end_page() {
      $this->end_page = $this->start_page + $this->page_num_row - 1;
    	if($this->end_page > $this->total_page)
          $this->end_page = $this->total_page;
    }

    // 링크 생성 메소드 (일단은 다 만들었는데 공통부분은 변수로 만들어놓자.)
    public function createLink() {
      $this->link = '';

      // 처음 링크 만들기
      if($this->page > $this->first_page)
    	    $this->link .= " <li><a href='".MAIN_URL ."/". $this->code ."/". $this->first_page . $this->query_string."' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

      // 이전 링크 만들기
      if($this->start_page > $this->first_page)
          $this->link .= " <li><a href='". MAIN_URL ."/". $this->code ."/". ($this->start_page - 1) . $this->query_string ."'>이전</a></li>";

      // 페이지 링크 만들기
      if($this->end_page > $this->first_page) {
          for($i=$this->start_page; $i<=$this->end_page; $i++) {
              $page_num = $i;

              // 현재 페이지 여부에 따라 링크 유무 셋팅
              if($this->page != $page_num) {
                  $this->link .= " <li><a href='". MAIN_URL ."/". $this->code ."/". $page_num . $this->query_string ."' aria-label='Page ".$page_num."'><span>$page_num</span></a></li>";
              } else {
                  $this->link .= " <li class='current'>". $page_num ."</li> ";
              }
          }
      }

      // 다음 링크 만들기
    	if($this->total_page > $this->end_page)
    	    $this->link .= " <li><a href='". MAIN_URL ."/". $this->code ."/". ($this->end_page + 1) . $this->query_string ."'>다음</a></li>";
    	// 마지막 링크 만들기
    	if($this->page < $this->total_page)
    	    $this->link .= " <li><a href='". MAIN_URL ."/". $this->code ."/". $this->total_page . $this->query_string ."' aria-label='Previous'><span aria-hidden='true'>&raquo;</span></a></li>";

      return $this->link;
    }

    public function get() {
      var_dump($this->query_string);
      var_dump(BOARDCODE);
      var_dump(MAIN_URL);
    }
}
