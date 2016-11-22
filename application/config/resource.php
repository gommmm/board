<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['index']['js'] = [ JS.'board.js' ];
$config['board/index']['js'] = [ JS.'board.js', JS.'search.js' ];
$config['admin/menu']['css'] = [ CSS.'jquery-ui.css' ];
$config['admin/menu']['js'] = [ JS.'jquery-ui.js', JS.'jquery-ui-start.js' ];
$config['board/write']['js'] = [
                                'HuskyEZCreator' => PLUGIN.'/seditor/js/HuskyEZCreator.js',
                                'board' => JS.'board.js'
                               ];
