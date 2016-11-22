<?php

class Menu extends MY_Controller {
    public function __construct() {
	    parent::__construct();
		$this->load->library(['session', 'user_agent']);
		$this->load->helper(['alert', 'basic', 'url']);
		$this->load->model(['menu_model', 'board_model']);
	}

	public function index() {
    $data = $this->data;
	  $data['menu_list'] = $this->menu_model->getMenu();

	  $this->load->view('header');
	  $this->load->view('admin/nav');
	  $this->load->view('admin/menu', $data);
	  $this->load->view('footer');
	}

	public function get_menu() {
		$id = $this->input->post('id');
		$data['menu'] = $this->menu_model->getMenuOne(['bc_idx'=>$id]);; // board_model의 board_exist 메소드와 동일

		$this->load->view('admin/get_menu', $data);
	}

	public function save() {
		$get_json = $this->input->post('menu');
		$menu = json_decode(stripcslashes($get_json), true);

		$added_menus = $menu['added_menus'];
		$deleted_menuids = $menu['deleted_menuids'];
		$menuid_order = $menu['menuid_order'];
		$modified_menus = $menu['modified_menus'];
		$add_id_lists = [];

    if(count($added_menus) > 0) {
			$add_id_lists = $this->_insert($added_menus);

			if($add_id_lists == false) {
				echo 'fail';
			}
		}

		if(count($deleted_menuids) > 0) {
			$this->_delete($deleted_menuids);
		}

		$this->_update($menuid_order, $modified_menus, $add_id_lists);
		echo 'success';
	}

	public function _insert($added_menus) {
		$id_list = $this->menu_model->addMenu($added_menus);
		return $id_list;
	}

	public function _update($menuid_order, $modified_menus, $add_id_lists) {

    if(count($modified_menus) > 0) {
			$this->menu_model->updateMenu($modified_menus);
		}

		$this->menu_model->updateMenuSeq($menuid_order, $add_id_lists);
	}

	public function _delete($deleted_menuids) {
		$this->menu_model->delMenu($deleted_menuids);
	}

	public function refresh() {
		$data['menu_list'] = $this->menu_model->getMenu();
		$this->load->view('admin/menu_list',$data);
	}

  public function movePostForm() {
    $data['id_list'] = $this->input->post('id_list');
    $data['menu_list'] = $this->menu_model->getMenu();
    $data['referer'] = $_SERVER['HTTP_REFERER'];

    $this->load->view('header');
    $this->load->view('admin/movePostForm', $data);
    $this->load->view('footer');
  }

  public function movePost() {
    $id_list = explode(',', $this->input->post('id_list'));
    $referer = $this->input->post('referer');
    $menu_name = $this->input->post('menu_name');

    $set = [
      'bc_code' => $menu_name
    ];

    $this->board_model->update('board', $set, 'b_num', $id_list);
    alert("게시글이 이동되었습니다.", $referer);
  }

  public function removeNotice() {
    $id = $this->input->post('id_list');
    $referer = $this->agent->referrer();
    $set = [
      'notice' => 0
    ];

    $this->board_model->update('board', $set, 'b_idx', $id);
    redirect($referer);
  }
}
