<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
원래는 이름이 upload여야 되지만
이미 codeigniter 라이브러리로 제공되기 때문에 원리를 이해하기 위해서 만들었다.
*/

class File {
  protected $ci;

  public function __construct($config = []) {
    $this->ci = & get_instance();
  }

  public function upload($file, $b_idx, $dir) {
    $count = count($_FILES['b_file']['name']) - 1;
    $data = [];

    if($count > 0) {
        for($i=0; $i<$count; $i++) {
          $b_filename = $_FILES['b_file']['name']["{$i}"];
          $b_filesize = $_FILES['b_file']['size']["{$i}"];
          $b_filetype = $_FILES['b_file']['type']["{$i}"];
          $b_tmpfile = $_FILES['b_file']['tmp_name']["{$i}"];

          $b_file = $dir.'/'.$b_idx.'_'.$b_filename;

          if (file_exists($b_file)) {
              @unlink($b_file);
          }
          move_uploaded_file($b_tmpfile, $b_file);
          chmod($b_file, 0666);

          $data["{$i}"] = [
            'b_idx' => $b_idx,
            'filename' => $b_filename,
            'filesize' => $b_filesize,
            'filetype' => $b_filetype
          ];
        }
    }

    return $data;
  }

}
