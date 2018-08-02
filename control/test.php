<?php

/**
 * 淘玉php 
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 
 * $Id:  17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class testControl extends BaseControl {
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('ads,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    public function tpl_single_send() {
    	$yun = new Yunpian;
        $param['number'] = '1234';
        $param['number1']= '123456';
        $json_data = $yun->tpl_single_send('2353894',$param,'17550353616');
        echo '<pre>';print_r($json_data);
    }

    public function single_send() {
        $yun = new Yunpian;
        $param = '【麦兜麦】您的验证码是4567';
        $json_data = $yun->single_send($param,'17550353616');
        echo '<pre>';print_r($json_data);
    }

    public function batch_send() {
        $yun = new Yunpian;
        $param = '【麦兜麦】您的验证码是1234';
        $json_data = $yun->batch_send($param,'17550353616,18238192790');
        echo '<pre>';print_r($json_data);
    }

    public function tpl_batch_send() {
        $yun = new Yunpian;
        $param['number'] = '1234';
        $param['number1']= '123456';
        $json_data = $yun->tpl_batch_send('2353894',$param,'17550353616,18238192790');
        echo '<pre>';print_r($json_data);
    }

    public function tpl_add() {
        $yun = new Yunpian1;
        $content = '【麦兜麦】欢迎5#name#！';
        $json_data = $yun->tpl_add($content,1);
        echo '<pre>';print_r($json_data);
    }

    public function tpl_update() {
        $yun = new Yunpian;
        $content = '【麦兜麦】欢迎#user_name#！';;
        $json_data = $yun->tpl_update('2355290',$content);
        echo '<pre>';print_r($json_data);
    }

    public function tpl_del() {
        $yun = new Yunpian;
        $json_data = $yun->tpl_del('2355340');
        echo '<pre>';print_r($json_data);
    }

   
}
