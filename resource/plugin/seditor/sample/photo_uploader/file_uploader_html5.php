<?php
 	$sFileInfo = '';
	$headers = array();

	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		}
	}

	$file = new stdClass;
	$file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");

  $filename_ext = explode('.',$file->name);
	$filename_ext = strtolower(array_pop($filename_ext));

	$allow_file = array("jpg", "png", "bmp", "gif");

	if(!in_array($filename_ext, $allow_file)) {
		echo "NOTALLOW_".$file->name;
	} else {
    $uploadDir = '../../../../../upload/img/';
    //$uploadDir = './upload/';
		if(!is_dir($uploadDir)){
			mkdir($uploadDir, 0777);
		}

    $sub = '/board';
    $url = 'http://'.$_SERVER['SERVER_NAME'].$sub;
    $upload_path = $url.'/upload/img/';

    $randNum = mt_rand(1,10000000);

    $file->name = $randNum.'_'.$file->name;

    //$newPath = $uploadDir.iconv("utf-8", "cp949", $file->name);
    //$newPath = $uploadDir.iconv("utf-8", "euc-kr", $file->name);
		$newPath = $uploadDir.$file->name;

		if(file_put_contents($newPath, $file->content)) {
			$sFileInfo .= "&bNewLine=true";
			$sFileInfo .= "&sFileName=".$file->name;
      $sFileInfo .= "&sFileURL=$upload_path".$file->name;
			//$sFileInfo .= "&sFileURL=http://localhost/gom/resource/plugin/seditor/sample/photo_uploader/upload/".$file->name;
		}

		echo $sFileInfo;
	}
?>
