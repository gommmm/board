<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// 무분별하게 함수가 정의되어있음. 나누기 바람.

function ext_value($val) {
    return isset($val) == TRUE ? $val : '';
}

function mode($type, $bc_code='') {
    $str = '';
	if($type == 'w') {
	    $str = "/write";
	}

	if($type == 'r') {
	    $str = "/reply/".$bc_code;
	}

	if($type == 'm') {
	    $str = "/modify/".$bc_code;
	}

	return $str;
}

function reply_str($depth) {
     $str = '';
	 $depth = strlen($depth);

	 for($i=0; $i< $depth; $i++) {
	     $str .= '&nbsp;&nbsp;';
	 }

	 if($depth != 0) {
	     $str .= '└';
	 }

	 return $str;
}

function comment_count($c_cnt) {
    $str = '';
	if($c_cnt != 0) {
	    $str = '<strong>['.$c_cnt.']</strong>';
	} else {
	    $str = '';
	}
    return $str;
}

function blank($depth) {
  $length = strlen($depth);
	return $length * 30;
}

function get_img_src($content) {
    preg_match('/<img\s+.*?(src\s*=\s*("[^"\\\\]*(?:[^"\\\\]*)*"|\'[^\'\\\\]*(?:[^\'\\\\]*)*\'|[^\s]+)).*?>/is', $content, $matches);
	$src = isset($matches[2]) ? $matches[2] : '';
	return $src;
}

function today_check($post_datetime) {
  $today = date('Y-m-d');
	$post_datetime_divide = explode(' ', $post_datetime);
	$date = $post_datetime_divide[0];
	$time = substr($post_datetime_divide[1], 0, 5);

	if($today == $date) {
	    return $time;
	}

	return $date;
}

function searchDate_check($d) {
	$chk_arr = ['all', '1d', '1w', '1m', '6m', '1y'];
	$pattern = '/([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1-2][0-9]|[3][0-1])/';
	$d_to = substr($d, 0, 10);
	$d_from = substr($d, 10, 10);

	if(in_array($d, $chk_arr)) {
		return true;
	}

	if(preg_match($pattern, $d_to) && preg_match($pattern, $d_from)) {
		$d_to_ut = strtotime($d_to);
		$d_from_ut = strtotime($d_from);

		if($d_to_ut > $d_from_ut) {
			return false;
		}

		return true;

	} else {
		return false;
	}
}

function replaceDBDate($dateStr) {

  if(strlen($dateStr) <= 3) {
    switch ($dateStr) {
    case '1d':
        $date = '1 DAY';
        break;
    case '1w':
        $date = '1 WEEK';
        break;
    case '1m':
        $date = '1 MONTH';
        break;
    case '6m':
        $date = '6 MONTH';
        break;
    case '1y':
        $date = '1 YEAR';
        break;
    default:
        $date = null;
    }
  } else {
	    $date['to'] = substr($dateStr, 0, 10);
	    $date['from'] = substr($dateStr, 10, 10);
  }

	return $date;
}

function searchDate_to_str($searchDate) { // form에서 받아온 searchDate값을 프론트에서 표시하기위해 치환
    if($searchDate != '') {
		if($searchDate == 'all') { return '전체기간'; }
		else if($searchDate == '1d') { return '1일'; }
		else if($searchDate == '1w') { return '1주'; }
		else if($searchDate == '1m') { return '1개월'; }
		else if($searchDate == '6m') { return '6개월'; }
		else if($searchDate == '1y') { return '1년'; }
		else { return '기간입력'; }
	} else {
	    return '전체기간';
	}
}

function searchTypeToStr($searchType) {
  $type = '';

  switch($searchType) {
  case 'b_title||b_content' :
      $type = '제목+내용';
      break;
  case 'b_title' :
      $type = '제목만';
      break;
  case 'b_name' :
      $type = '작성자';
      break;
  case 'c_content' :
      $type = '댓글 내용';
      break;
  case 'c_name' :
      $type = '댓글 작성자';
      break;
  default:
      $type = '제목+내용';
  }
  return $type;
}

function createBoardType($type) {
  if($type == "normal") {
    return "normal_board";
  } else if ($type == "image") {
    return "image_board";
  } else {
    return "group";
  }
}

function divideList($dmt, $list) {
  $result = explode($dmt , $list);
  return json_encode($result);
}

function cut_str($str) {
	if(mb_strlen($str, 'UTF-8') > 10) {
		$sub_str = mb_substr($str, 0, 10, 'UTF-8');
		$sub_str .= '...';
	} else {
		$sub_str = $str;
	}

	return $sub_str;
}

/*
function selectPage($segment) {
  $count = count($segment);

  $data['code'] = isset($segment[1]) ? $segment[1] : '';

  if($count != 0 && ($count == 3 || $segment[2] == 'write')) {
    $data['page'] = $segment[2] == 'view' ? $segment[2] : 'write';
  }

  if($count == 0) {
    $data['page'] = 'latest';
  }

  $data['count'] = $count;

   segment 배열의 값이 3개면 view, reply, modify , 0개면 main , 2개면 list, write
     3개면 view일 경우를 제외하고 write 페이지를 띄우고 0개면 latest,
     write도 2개인데 if에서 따로 처리
     2개면 디비에서 그 메뉴의 설정값들을 가져와서 타입을 체크해야 되기 때문에 배열변수를 따로 만들어준다.


  return $data;
}*/

function strip_particular_tags($str, $tags) {
    foreach($tags as $key => $val) {
        $str = preg_replace("/<{$val}[^>]*>/i", '', $str);
        $str = preg_replace("/<\/{$val}>/i", '', $str);
    }

    return $str;
}
