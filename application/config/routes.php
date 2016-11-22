<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'main'; // 기본주소를 입력했을때 controller폴더에서 이 이름의 클래스를 호출함.
$route['chat/(:any)'] = 'chat/$1';
$route['chat/(:any)/(:any)'] = 'chat/index/$1/$2';
$route['memberModify/(:any)'] = 'memberModify/$1';
$route['register/(:any)'] = 'register/$1';
$route['install/(:any)'] = 'install/$1';
$route['admin/board'] = 'admin/board';
$route['admin/member'] = 'admin/member';
$route['admin/group'] = 'admin/group';
$route['admin/menu'] = 'admin/menu';
$route['admin/board/(:num)'] = 'admin/board';
$route['admin/member/(:num)'] = 'admin/member';
$route['admin/group/(:num)'] = 'admin/group';
$route['admin/board/(:any)'] = 'admin/board/$1';
$route['admin/member/(:any)'] = 'admin/member/$1';
$route['admin/group/(:any)'] = 'admin/group/$1';
$route['admin/menu/(:any)'] = 'admin/menu/$1';
$route['login'] = 'auth/main/index';
$route['login/(:any)'] = 'auth/main/$1';
$route['logout'] = 'auth/main/logout';
$route['message/(:num)'] = 'message/main/index/$1';
$route['message/send'] = 'message/main/send';
$route['message/view/(:num)'] = 'message/main/view';
$route['message/delete/(:num)'] = 'message/main/delete';

$route['([a-zA-Z]\w+)/(:num)'] = 'board/main/index'; // 게시판 리스트
$route['([a-zA-Z]\w+)/(:any)'] = 'board/main/$2';
$route['([a-zA-Z]\w+)/(:any)/(:num)'] = 'board/main/$2'; // 게시판 글쓰기, 답글 등등 ...


//$route['(:any)'] = 'main/index/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
