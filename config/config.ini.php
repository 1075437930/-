<?php

defined('TaoyuShop') or exit('Access Invalid!mianconfig');
$_COOKIE['ECSCP']['ceshisql'] = 1;
$config['dbdriver'] 	= 'mysqli';
$config['tablepre']		= 'ecs_';
$config['db']['1']['dbhost']       = 'localhost';
$config['db']['1']['dbport']       = '3306';
$config['db']['1']['dbuser']       = 'testtaoyu';
$config['db']['1']['dbpwd']        = 'JxLj7568J98d';
$config['db']['1']['dbname']       = 'testtaoyu';
$config['db']['1']['dbcharset']    = 'UTF-8';
$config['db']['slave']                  = $config['db']['master'];
$config['sys_debug'] = true;
$config['sys_debug_id'] = 2717;
$config['sys_debug_tel'] =18238192790;
