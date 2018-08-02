<?php

/**
 * 淘玉php 第三方支付封装类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 第三方支付封装类
 * $Id: payment.php 17217 2015-05-19 06:29:08Z 淘玉 $
*/
defined('TaoyuShop') or exit('Access Invalid!tpl');

class Payment {
	public $lang = array();
	public $payment = null;
	public $module = array();
	public function __construct($code){
		$set_modules = true;
		$language_file = BASE_PATH .'/api/payment/language/'.$code.'.php';
		if(file_exists($language_file)){
			include_once($language_file);
		}
		include_once(BASE_PATH .'/api/payment/'.$code.'.php');		
		$this->lang = $_LANG;
		$this->module = $modules;
		$this->payment = new $code;
	} 	   
}