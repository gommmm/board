<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Input extends CI_Input {
  protected $data = [];

  public function __construct()
	{
      parent::__construct();
  }

  public function postArray() {
      foreach($this->post(NULL, TRUE) as $key => $val)
          $this->data["{$key}"] = $val;

      return $this->data;
  }

  public function getArray() {
      foreach($this->get(NULL, TRUE) as $key => $val)
          $this->data["{$key}"] = $val;

      return $this->data;
  }
}
