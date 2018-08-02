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
class test_oneControl extends BaseControl {
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
    
    public function test() {
       /* $c = new Yunpian;
        $param['number'] = '1234';
        $param['number1']= '999';
        $param['number1']= '666';
        $r = $c->tpl_single_send($param,'17550353616');
        echo '<pre>';print_r($r);*/
/*
        $yun = new Yunpian;
        $param = '【麦兜麦】您的验证码是1111';
        $json_data = $yun->single_send($param,'18238819085');
        echo '<pre>';print_r($json_data);*/

       /* $yun = new Yunpian;
        $param['number'] = '1234';
        $param['number1']= '12345';
        $json_data = $yun->tpl_batch_send('2353894',$param,'17550353616,18238819085');
        echo '<pre>';print_r($json_data);*/

        $yun = new Yunpian;
        $param = '【麦兜麦】您的验证码是3636';
        $json_data = $yun->batch_send($param,'18238819085');
        echo '<pre>';print_r($json_data);

        /*$yun = new Yunpian;
        $content = '【麦兜麦】wwwwwww #num111#';//0通知类 内容带 验证或者1自动认定为验证码类  2营销类  
        $notify_type = 3;
        $json_data = $yun->tpl_add($content,1);
        echo '<pre>';print_r($json_data);*/

       /* $yun = new Yunpian;
        $content = '【麦兜麦】#5656#，请充值！';
        $json_data = $yun->tpl_update('2358686',$content,0);
        echo '<pre>';print_r($json_data);*/

        /*$yun = new Yunpian;
        $json_data = $yun->tpl_del('2357626');
        echo '<pre>';print_r($json_data);*/

        /*$yun = new Yunpian;
        $content = '【麦兜麦】#5656#，请充值！';
        $json_data = $yun->tpl_get('');
        echo '<pre>';print_r($json_data);*/
       /* $yun = new Yunpian;
        $page_size = 100;
        $json_data = $yun->pull_status($page_size);
        echo '<pre>';print_r($json_data);*/
        /*$yun = new Yunpian;
        $json_data = $yun->get_record();
        echo '<pre>';print_r($json_data);*/
        // clear_cache_files();
        // $yun = new Yunpian;
        // $json_data = $yun->get_sms_record('2018-02-11 00:00:00','2018-08-11 00:00:00',17,100);
        // echo '<pre>';print_r($json_data);
       
       /* $yun = new Yunpian;
        $param = array(
            '18238819085'=>'【麦兜麦】您的订单编号：1123,物流信息：3333',
            '17550353616'=>'【麦兜麦】您的订单编号：1123,物流信息：5555',
        );*/
       /* $param = array(
            'number'=>'3636',
            'number1'=>'3636'
        );*/
       /* $str = $yun->multi_send($param);
        echo '<pre>';print_r($str);*/
    }

}
