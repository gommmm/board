<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function alert($msg='', $url='') {
	if(!$msg) $msg = '�ùٸ� ������� �̿����ּ���.';
	
	echo "<meta charset='utf-8' />";
	echo "<script type='text/javascript'>alert('".$msg."');";
	if($url != '') {
	    echo "location.replace('".$url."');";
	} else echo "history.go(-1);";
	echo "</script>";
	exit;
}

function page_move($url) {
	echo "<meta charset='utf-8' />";
	echo "<script type='text/javascript'>location.replace('".$url."');</script>";
}