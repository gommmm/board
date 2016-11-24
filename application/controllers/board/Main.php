<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller
{
    public $segment;
    public $data;

    public function __construct()
    {
        parent::__construct();

        $this->allow = ['write', 'reply', 'modify'];

        $this->load->model(['menu_model', 'board_model', 'comment_model', 'file_model']);
        $this->load->library('page');

        $this->load->helper(['alert', 'basic', 'image']);

        define('BOARDCODE', $this->segment[1]);
        define('IMAGEPAGEROW', 12);
        define('PAGEROW', 10);
    }

    public function index() // list
    {
        $data = $this->data;

        $searchDate = $this->input->get('date');
        $search = $this->input->get('search');
        $word = $this->input->get('word');
        $board_code = BOARDCODE;

        //$board = $this->menu_model->getMenuOne($bc_code); // 게시판 체크 쿼리

        $board = $this->menu;

        if (empty($board['bc_code'])) {
            alert('게시판이 존재하지않습니다.');
        }

       // 사용자레벨 세션 값이 존재하는지 확인 후 게시판권한 체크
       if ($this->session->userdata('user_level') != '') {
           $u_level = $this->session->userdata('user_level');
       } else {
           $u_level = 0;
       }

        $data['menu_list'] = $this->menu_list;

        if ($this->segment[2] > 0) {
            $page = $this->segment[2];
        } else {
            $page = 1;
        }

        $table = 'board';

        if ($board['type'] == 'image') {
            $page_row = IMAGEPAGEROW;
        } else {
            $page_row = PAGEROW;
        }

        $page_scale = 10; // 페이지 번호 수
        $from_record = ($page - 1) * $page_row; // db의 어디서 부터 뿌려질지를 정하는 기준값

        if ($searchDate != '' && $search != '' && $word != '') {
            $board_data = $this->_search($board_code, $searchDate, $search, $word, $page_row, $from_record);
            $queryString = $board_data['queryString'];
            $total_count = $board_data['total_count'];
            $list = $board_data['list'];
            $config['use_query_string'] = true;
            $data['notice_list'] = [];
        } else {
            $queryString = '';
            $board_data = $this->board_model->getList($board_code, $page_row, $from_record, $page_row, $from_record); // 게시물 리스트를 가져오는 쿼리
            $total_count = $board_data['total_count'];
            $list = $board_data['list'];
            $data['notice_list'] = $this->board_model->getNoticeList($board_code);
        }

        $config['uri_segment'] = 2;
        $config['code'] = BOARDCODE;
        $config['page_row'] = $page_row;
        $config['page_num_row'] = $page_scale;
        $config['total_count'] = $total_count;

        $this->page->initialize($config);
        $paging_str = $this->page->createLink();

        //$paging_str = pagination($page, $page_row, $page_scale, $total_count, $board_code, $queryString);

        $data['paging_str'] = $paging_str;
        $data['list'] = $list;
        $data['bc_code'] = BOARDCODE;
        $data['board_name'] = $board['bc_name'];
        $data['write_level'] = $board['bc_write_level'];
        $data['user_level'] = $u_level;
        $data['searchDate'] = $searchDate;
        $data['search'] = $search;
        $data['word'] = $word;
        $data['content'] = $this->content;

        $this->load->view('header');
        $this->load->view('main', $data);
        $this->load->view('footer');
    }

    public function write() // 글쓰기
    {
        $data = $this->data;

      //var_dump($this->input->post(NULL, TRUE));
        if ($this->input->post('bc_code') != '') {
            $bc_code = $this->input->post('bc_code');
        } else {
            $bc_code = $this->uri->segment(1);
        }

        $data['menu_list'] = $this->menu_list;
        $board_config = $this->menu_model->getMenuOne(['bc_code'=>$bc_code]);
        $data['board_config'] = $board_config;

        if (empty($board_config['bc_code'])) {
            alert('게시판이 존재하지않습니다.');
        }

        if ($this->session->userdata('user_level') != '') {
            $u_level = $this->session->userdata('user_level');
        } else {
            $u_level = 0;
        }

        if ($u_level < $board_config['bc_write_level']) {
            alert('권한이 없습니다.');
        }

        if (empty($this->input->post())) {
            $data['type'] = 'w';
            $data['content'] = $this->content;
            $data['user_level'] = (int) $u_level;

            $this->load->view('header');
            $this->load->view('main', $data);
            $this->load->view('footer');

            return;
        }

        if ($this->input->post('b_title') == '') {
            alert('글제목을 입력해주세요.');
        }

        if ($this->input->post('b_content') == '') {
            alert('글제목을 입력해주세요.');
        }

        $notice = $this->input->post('notice') != '' ? $this->input->post('notice') : 0;

        $img = getImagePath($this->input->post('b_content'));

        if(!empty($img)) {
            $path = MAIN_DIRECTORY.'/upload/img/'.$img['filename'];
            $img['filename'] = str_replace('bmp', 'jpg', $img['filename']);
            $resizePath = MAIN_DIRECTORY.'/upload/img/resize/resize_'.$img['filename'];
        }

        $board_data = [
                        'bc_code' => $bc_code,
                        'b_reply' => '',
                        'm_id' => $this->session->userdata('user_id'),
                        'name' => $this->input->post('m_name'),
                        'title' => $this->input->post('b_title'),
                        'content' => strip_particular_tags($this->input->post('b_content'), ['p']),
                        's_content' => strip_tags($this->input->post('b_content')),
                        'b_regdate' => date('Y-m-d h:i:s'),
                        'notice' => $notice
                      ];

        $b_idx = $this->board_model->insert('board', $board_data);
        $this->board_model->update('board', ['b_num' => $b_idx], 'b_idx', $b_idx);

        // 리사이즈용 이미지 업로드
        if(isset($path) && file_exists($path)) {
            resizeImage($path, $resizePath, 0.3);
        }

        // 파일 업로드
        $dir = MAIN_DIRECTORY.'/upload/file';
        $this->load->library('file');
        $file_data = $this->file->upload($_FILES, $b_idx, $dir);
        // 파일정보 db저장
        if(empty($file_data) === false)
            $this->file_model->insert($file_data);

        alert('글이 저장되었습니다.', MAIN_URL.'/'.$bc_code.'/1');
    }

    public function reply() // 글답변
    {
        $data = $this->data;
        $b_idx = $this->uri->segment(3);
        $bc_code = $this->uri->segment(1);
        $board_config = $this->menu_model->getMenuOne(['bc_code'=>$bc_code]);

        if ($this->session->userdata('user_level') != '') {
            $u_level = $this->session->userdata('user_level');
        } else {
            $u_level = 0;
        }

        if ($u_level < $board_config['bc_write_level']) {
            alert('권한이 없습니다.', MAIN_URL);
        }

        $posting = $this->board_model->select_writing($b_idx, $bc_code);

        if ($posting['b_idx'] == '') {
            alert('글이 존재하지 않습니다.');
        }

        if (strlen($posting['b_reply']) == 3) {
            alert('더 이상 답변을 다실 수 없습니다.');
        }

        $parent = $this->board_model->select_reply($bc_code, $posting['b_num'], $posting['b_reply'], 'n');
        $last_reply_char = substr($parent['b_reply'], strlen($posting['b_reply']), 1);

        if ($last_reply_char == 'Z') {
            alert('더 이상 답변을 다실 수 없습니다.');
        }

        $data['board_config'] = $board_config;
        $data['board'] = $posting;
        $data['menu_list'] = $this->menu_list;

        if (empty($this->input->post()) == true) {
            $data['type'] = 'r';
            $data['content'] = $this->content;
            $data['user_level'] = (int) $u_level;

            $this->load->view('header');
            $this->load->view('main', $data);
            //$this->load->view('board/writeForm', $data);
            $this->load->view('footer');

            return;
        }

        $b_num = $posting['b_num'];

        if ($last_reply_char) {
            $b_reply = $posting['b_reply'].chr(ord($last_reply_char) + 1);
        } else {
            $b_reply = $posting['b_reply'].'A';
        }

        $notice = $this->input->post('notice') != '' ? $this->input->post('notice') : 0;

        $img = getImagePath($this->input->post('b_content'));

        if(!empty($img)) {
            $path = MAIN_DIRECTORY.'/upload/img/'.$img['filename'];
            $img['filename'] = str_replace('bmp', 'jpg', $img['filename']);
            $resizePath = MAIN_DIRECTORY.'/upload/img/resize/resize_'.$img['filename'];
        }

        $table = 'board';
        $board_data = [
                       'bc_code' => $bc_code,
                       'b_num' => $b_num,
                       'b_reply' => $b_reply,
                       'm_id' => $this->session->userdata('user_id'),
                       'name' => $this->input->post('m_name'),
                       'title' => $this->input->post('b_title'),
                       'content' => strip_particular_tags($this->input->post('b_content'), ['p']),
                       's_content' => strip_tags($this->input->post('b_content')),
                       'b_regdate' => date('Y-m-d h:i:s'),
                       'parent_id' => $b_idx,
                       'notice' => $notice
                      ];
        $b_idx = $this->board_model->insert($table, $board_data);

        // 리사이즈용 이미지 업로드
        if(isset($path) && file_exists($path) && !file_exists($resizePath)) {
            resizeImage($path, $resizePath, 0.3);
        }

        // 파일 업로드
        $dir = MAIN_DIRECTORY.'/upload/file';
        $this->load->library('file');
        $file_data = $this->file->upload($_FILES, $b_idx, $dir);

        if(empty($file_data) === false)
            $this->file_model->insert($file_data);

        alert('글이 저장되었습니다.', MAIN_URL.'/'.$bc_code.'/1');
    }

    public function modify() // 글수정
    {
        $data = $this->data;
        $b_idx = $this->uri->segment(3);
        $bc_code = $this->uri->segment(1);
        $board_config = $this->menu_model->getMenuOne(['bc_code'=>$bc_code]);

        if ($this->session->userdata('user_level') != '') {
            $u_level = $this->session->userdata('user_level');
        } else {
            $u_level = 0;
        }

        $posting = $this->board_model->select_writing($b_idx, $bc_code);

        if ($posting['b_idx'] == '') {
            alert('글이 존재하지 않습니다.');
        }

        if ($posting['m_id'] != $this->session->userdata('user_id') && $u_level < 9) {
            alert('작성자가 다릅니다.');
        }

        $data['board'] = $posting;
        $data['board_config'] = $board_config;
        $data['menu_list'] = $this->menu_list;

        if (empty($this->input->post()) == true) {
            $data['type'] = 'm';
            $data['content'] = $this->content;
            $data['user_level'] = (int) $u_level;
            $data['file_list'] = $this->file_model->getFile($b_idx);

            $this->load->view('header');
            $this->load->view('main', $data);
            //$this->load->view('board/writeForm', $data);
            $this->load->view('footer');

            return;
        }


        $remove_file_list = json_decode($this->input->post('remove_id_list'), true);
        $dir = MAIN_DIRECTORY.'/upload/file';

        // 파일 업로드 및 파일정보 db저장
        $this->load->library('file');
        $file_data = $this->file->upload($_FILES, $b_idx, $dir);

        if(empty($file_data) === false)
            $this->file_model->insert($file_data);

        // 파일 삭제 및 파일정보 db삭제
        if(!empty($remove_file_list)) {
          $file_id = [];
          $file_name = [];

          foreach($remove_file_list as $key => $val) {
            array_push($file_id, $key);
            array_push($file_name, $val);
          }

          $this->file_model->delete($file_id);

          foreach($file_name as $key => $val) {
            $file = $dir.'/'.$b_idx.'_'.$file_name["{$key}"];

            if(file_exists($file)) {
              unlink($file);
            }
          }
        }

        $notice = $this->input->post('notice') != '' ? $this->input->post('notice') : 0;

        $img = getImagePath($this->input->post('b_content'));

        if(!empty($img)) {
            $path = MAIN_DIRECTORY.'/upload/img/'.$img['filename'];
            $img['filename'] = str_replace('bmp', 'jpg', $img['filename']);
            $resizePath = MAIN_DIRECTORY.'/upload/img/resize/resize_'.$img['filename'];
        }

        $table = 'board';
        $set = [
                'title' => $this->input->post('b_title'),
                'content' => strip_particular_tags($this->input->post('b_content'), ['p']),
                's_content' => strip_tags($this->input->post('b_content')),
                'notice' => $notice
               ];
        $this->board_model->update($table, $set, 'b_idx', $b_idx);

        // 리사이즈용 이미지 업로드
        if(isset($path) && file_exists($path) && !file_exists($resizePath)) {
            resizeImage($path, $resizePath, 0.3);
        }

        alert('글이 저장되었습니다.', MAIN_URL.'/'.$bc_code.'/1');
    }

    public function view() // 글보기
    {
        $bc_code = $this->uri->segment(1);
        $b_idx = $this->uri->segment(3);
        $board = $this->board_model->select_writing($b_idx, $bc_code);

        if ($board['b_reply'] == '') {
            $prev_post = $this->board_model->get_size_post($b_idx, '>', $bc_code, 'asc');
            $next_post = $this->board_model->get_size_post($b_idx, '<', $bc_code, 'desc');
        } else {
            $prev_post = $this->board_model->get_size_post($board['b_num'], '>', $bc_code, 'asc');
            $next_post = $this->board_model->get_size_post($board['b_num'], '<', $bc_code, 'desc');
        }

        // b_reply 값이 없으면 굳이 이 쿼리를 실행할 필요가 없음
        $near_posts = $this->board_model->get_near_posts($board['b_num'], $bc_code);

        if ($board['b_idx'] == '') {
            alert('글이 존재하지 않습니다');
        }

        $board_config = $this->menu_model->getMenuOne(['bc_code'=>$bc_code]);

        if (isset($board_config['bc_code']) == false) {
            alert('게시판이 존재하지 않습니다.');
        }

        if ($this->session->userdata('user_level') != '') {
            $u_level = $this->session->userdata('user_level');
        } else {
            $this->session->set_userdata('user_level', 0);
            $u_level = 0;
        }

        if ($u_level < $board_config['bc_read_level']) {
            alert('권한이 없습니다.');
        }
        $file_list = $this->file_model->getFile($b_idx);
        $this->board_model->update('board', ['b_cnt' => $board['b_cnt'] + 1], 'b_idx', $b_idx);

        $data['prev_post'] = $prev_post;
        $data['next_post'] = $next_post;
        $data['near_posts'] = $near_posts;
        $data['board'] = $board;
        $data['file_list'] = $file_list;
        $data['board_config'] = $board_config;
        $data['comment_count'] = $this->comment_model->getTotalCount('comment', 'b_idx', $b_idx); // 이 부분도 comment_list 받아오면서 한꺼번에 처리하자!
        // $data['comment_list'] = $this->board_model->select_where('comment', 'b_idx', $b_idx, 'c_idx', 'asc');
        $data['comment_list'] = $this->comment_model->getComment($b_idx);
        $data['menu_list'] = $this->menu_list;
        $data['content'] = $this->content;
        $data['user_level'] = $u_level;

        $this->load->view('header');
        $this->load->view('main', $data);
        //$this->load->view('board/view', $data);
        $this->load->view('footer');
    }

    public function comment() // 댓글
    {
        if ($this->input->post('cmt_id') != '') { // 댓글의 댓글이면
            $c_idx = $this->input->post('cmt_id');
        }
        $c_content = $this->input->post('c_content');

        if ($this->input->post('mode') == 'edit') { // 주소로 접근할 수도 있으니 이에 따른 접근거부 추가바람.
            $url = $_SERVER['HTTP_REFERER']; // 댓글 저장 후 원래 페이지로 리다이렉트 하기 위한 이 페이지로 오기 전 주소 값
            $this->comment_model->update('comment', ['content' => $c_content, 's_content' => strip_tags($c_content)], 'c_idx', $c_idx);

            page_move($url);
        } else {
            $b_idx = $this->input->post('b_idx');
            $bc_code = $this->input->post('bc_code');
            $m_name = $this->input->post('m_name');
            $rereply = $this->input->post('rereply');

            $table = 'comment';
            $board = $this->board_model->select_writing($b_idx, $bc_code);

            if ($board['b_idx'] == '') {
                alert('글이 존재하지 않습니다.');
            }

            if (trim($this->input->post('m_name') == '')) {
                alert('이름을 입력해 주세요.');
            }

            if (trim($this->input->post('c_content') == '')) {
                alert('내용을 입력해 주세요.');
            }

            if (isset($c_idx)) {
                $comment = $this->comment_model->getCommentOne($c_idx);

                if ($comment['c_idx'] == '') {
                    alert('글이 존재하지 않습니다.');
                }

                $cp_idx = $comment['cp_idx']; // 댓글의 댓글이 아닐 때의 순서
                $cp_name = $comment['name']; // 댓글의 부모 값

                $result = $this->comment_model->getMaxCommentSeq($b_idx, $cp_idx);
                $c_seq = $result['csm'] + 1;

                $result2 = $this->comment_model->getParentCommentId($b_idx, $cp_idx);
                $ref_id = $result2['ref_id'];

                //댓글삭제 관련테스트
                //$this->board_model->update('comment', array('cc_cnt' => $comment['cc_cnt']+1), 'c_idx', $c_idx);
            } else {
                $result = $this->comment_model->getMaxParentId($b_idx);
                $cp_idx = $result['maxParentNo'] + 1;
                $depth = '';
            }

            $data = [
                     'b_idx' => $b_idx,
                     'cp_idx' => isset($cp_idx) ? $cp_idx : '',
                     'c_seq' => isset($c_seq) ? $c_seq : 1,
                     'm_id' => $this->session->userdata('user_id') != '' ? $this->session->userdata('user_id') : '',
                     'name' => $m_name,
                     'content' => $c_content,
                     's_content' => strip_tags($c_content),
                     'c_regdate' => date('Y-m-d h:i:s'),
                     'p_idx' => isset($ref_id) ? $ref_id : '',
                     'cp_name' => isset($cp_name) ? $cp_name : '',
                     'rereply' => $rereply
                    ];

            $this->comment_model->insert($table, $data);

            $this->board_model->update('board', ['c_cnt' => $board['c_cnt'] + 1], 'b_idx', $b_idx);

            alert('댓글이 저장되었습니다', MAIN_URL.'/'.$bc_code.'/view/'.$b_idx);
        }
    }

    public function download() // 파일다운로드
    {
        $file_id = $this->uri->segment(3);
        $bc_code = $this->uri->segment(1);
        $file = $this->file_model->getFileOne($file_id);

        $dir = MAIN_DIRECTORY.'/upload/file';
        $filename = $file['filename'];
        $file_path = $dir.'/'.$file['b_idx'].'_'.$filename;

        if (!empty($file) && file_exists($file_path)) {
            header('Content-type: doesn/matter');
            header('Content-Length: '.(string) (filesize("$file_path")));
            header("Content-Disposition: attachment; filename=$filename");
            header('Content-Description: PHP3 Generated Data');
            header('Pragma: no-cache');
            header('Expires: 0');

            /* 파일을 실제로 다운로드 받아주는 함수 */
            readfile($file_path);
        } else {
            alert('파일이 존재하지 않습니다');
        }
    }

    public function delete() // 글삭제
    {
        $bc_code = $this->segment[1];

        if($this->input->post('id_list') != null) {
          $b_idx = explode(',', $this->input->post('id_list'));
        } else {
          $b_idx = $this->segment[3];
          $posting = $this->board_model->select_writing($b_idx, $bc_code);
          $writer = $posting['m_id'];
        }

        $board_config = $this->menu_model->getMenuOne(['bc_code'=>$bc_code]);

        if ($this->session->userdata('user_level') != '') {
            $user_level = $this->session->userdata('user_level');
            $user_id = $this->session->userdata('user_id');
        } else {
            $user_level = 0;
            $user_id = '';
        }

        if ($user_level < 9 && $writer != $user_id) {
            alert('작성자가 다릅니다.');
        }

        $this->board_model->delete('board', 'b_idx', $b_idx);
        $child_id_list = $this->board_model->getChildPost($b_idx);

        if(!empty($child_id_list)) {
            $update_data = [
              'parent_deleted' => 1
            ];

            $this->board_model->update('board', $update_data, 'b_idx', $child_id_list);
        }
        // 부모 게시글이 삭제 된 아들 게시글에 부모글이 삭제됐다고 표시하기 위해 parent_deleted 컬럼을 업데이트한다.

        /* 이 방식은 글삭제를 할때 자식 게시물, 해당 댓글까지 다 삭제하는 방법이다. 혹시나 해서 남겨둔다.
        $b_num_list = $this->board_model->select_reply($bc_code, $posting['b_num'], $posting['b_reply'], 'y');

        foreach ($b_num_list as $posting) { // 삭제 대상글과 답변 글들의 댓글을 삭제하기 위해 쿼리를 반복하는데 where in 방식으로 수정하는게 좋을 것 같다.
            $dir = MAIN_DIRECTORY.'/file';
            $b_file = $dir.'/'.$posting['b_idx'].'_'.$posting['b_filename'];
            @unlink($b_file);

            $this->board_model->delete('comment', 'b_idx', $posting['b_idx']);
        }

        $this->board_model->del_incReply($bc_code, $posting['b_num'], $posting['b_reply']);
        */

        alert('글이 삭제되었습니다', MAIN_URL.'/'.$bc_code.'/1');
    }

    public function deleteComment() { // 댓글삭제
        $id = $this->uri->segment(3);
        $child_count = $this->comment_model->getChildCommentCount($id);
        $comment = $this->comment_model->getCommentOne($id);
        $b_idx = $comment['b_idx'];

        // 자식 댓글이 있으면 실제로 테이블에서 데이터를 삭제하지 않고 deleted 컬럼의 값을 1로 설정해준다.
        if($child_count > 0) {
          $set = [
            'deleted' => 1
          ];

          $this->comment_model->update('comment', $set, 'c_idx', $id);
        } else {
          $parent_id = $comment['p_idx'] != 0 ? $comment['p_idx'] : '';

          if($parent_id !== '') {
            $parent_comment = $this->comment_model->getCommentOne($parent_id);
            $child_count = $this->comment_model->getChildCommentCount($parent_id);
          }

          if($child_count == 1 && $parent_comment['deleted'] == 1)
            $id = [$parent_comment['c_idx'], $id];

            $this->comment_model->delete('commnet', 'c_idx', $id);
        }
        /* 자식 댓글이 없으면 부모 댓글이냐 자식 댓글이냐에 따라서 댓글을 삭제하는데
           자식 댓글이면 부모 댓글 id값을 얻은 후 부모 댓글 정보를 받아와서 부모 댓글이
           삭제된 댓글인지 체크 후 부모 댓글이 삭제된 댓글이면 부모 댓글의 id값을 추가한 후
           같이 삭제해준다.
        */
        
        $this->board_model->updateCount($b_idx);

        alert('댓글을 삭제했습니다.', $this->referer);
    }

    // 검색 (index 메소드에서 호출해서 앞에 _를 붙임)
    public function _search($bc_code, $dateStr, $searchStr, $word, $page_row, $from_record)
    {
        $set_print = [];

        $search['date'] = replaceDBDate($dateStr);

        $type = substr($searchStr, 0, 1); // 게시판 검색인지 댓글 검색인지 체크
        $pattern = '/'.$type.'_/';
        $match = preg_replace($pattern, '', $searchStr);
        $search['column'] = explode('||', $match);
        $search['word'] = $word;

        $set_print['page_row'] = $page_row;
        $set_print['from_record'] = $from_record;

        if ($type == 'b') { // 댓글검색이냐 게시물검색이냐
            $data = $this->board_model->board_search($search, $set_print);
        } else {
            $list = $this->comment_model->getSearchComment($search);
            $count = count($list);

            if ($count > 0) { // 검색어에 해당하는 댓글이 있으면
                $data = $this->board_model->board_search($list, $set_print);
            } else {
                $data['total_count'] = 0;
                $data['list'] = [];
            }
        }

        $data['queryString'] = '?'.$_SERVER['QUERY_STRING'];

        return $data;
    }
}
