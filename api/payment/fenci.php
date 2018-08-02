<?php


/**
 * 内部支付插件
 * ============================================================================
 * * 版权所有 2005-2012 淘玉商城，并保留所有权利。
 * 网站地址: http://www.taoyumall.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: douqinghua $
 * $Id: fenci.php 251230 2015-03-19 06:29:08Z douqinghua $
 */


if (!defined('TaoyuShop')) {

    die('Hacking attempt');

}


/* 模块的基本信息 */

if (isset($set_modules) && $set_modules == TRUE) {

    $i = isset($modules) ? count($modules) : 0;


    /* 代码 */

    $modules[$i]['code'] = basename(__FILE__, '.php');


    /* 描述对应的语言项 */

    $modules[$i]['desc'] = 'fenci_desc';


    /* 是否支持货到付款 */

    $modules[$i]['is_cod'] = '0';


    /* 是否支持在线支付 */

    $modules[$i]['is_online'] = '1';


    /* 作者 */

    $modules[$i]['author'] = 'TAOYU TEAM';


    /* 网址 */

    $modules[$i]['website'] = 'http://www.taoyumall.com';


    /* 版本号 */

    $modules[$i]['version'] = '1.0.0';


    /* 配置信息 */

    $modules[$i]['config'] = array();


    return;

}


/**
 * 类
 */
class fenci

{


    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */


    /* 代码修改_start  By  www.taoyumall.com */

    function __construct()

    {

        $this->fenci();

    }


    function fenci()

    {

    }

    /* 代码修改_end  By  www.taoyumall.com */


    /**
     * 响应操作
     */

    function respond()

    {


    }

}


?>