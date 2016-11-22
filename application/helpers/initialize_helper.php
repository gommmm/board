<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	// 초기화 클래스로
	function createDbConfig($hostname, $username, $password, $database) { // db설정 파일 생성 함수
		if($hostname = 'localhost') $hostname = '127.0.0.1';

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><config />');
		$xml->addChild('hostname', $hostname);
		$xml->addChild('username', $username);
		$xml->addChild('password', $password);
		$xml->addChild('database', $database);
		$xml->addChild('dbdriver', 'mysqli');
		$xml->addChild('dbprefix', '');
		$xml->addChild('pconnect', FALSE);
		$xml->addChild('db_debug', FALSE);
		$xml->addChild('cache_on', FALSE);
		$xml->addChild('cachedir', '');
		$xml->addChild('char_set', 'utf8');
		$xml->addChild('dbcollat', 'utf8-general_ci');

		$xml->asXML(MAIN_DIRECTORY.'/dbconfig.xml');
	}

	// db관련 클래스로
	function loadDbConfig() {
		$file = file_get_contents(MAIN_DIRECTORY.'/dbconfig.xml');
		$xml = new SimpleXMLElement($file);
		$dbconfig = [];

		// xml 파일의 요소와 값을 뽑아 각각 배열의 키와 값으로 설정해줌.
		foreach($xml as $key => $value) {
			$value = (string) $value;

			if($value == 'TRUE' || $value == 'FALSE')
			    $dbconfig[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
			else
			    $dbconfig[$key] = $value;
			// 뽑아오면 값이 object(SimpleXMLElement)형이므로 문자열로 변환하기 위해 강제형변환을 함.
		}

		return $dbconfig;
	}
