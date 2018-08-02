<?php


/**
 * ECSHOP 淘玉币插件
 * ============================================================================
 * 版权所有 2015-2025 淘玉商城，并保留所有权利。
 * 网站地址: http://www.taoyumall.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: taoyu $
 * $Id: taoyubi.php 251230 2015-03-19 06:29:08Z taoyu $
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

    $modules[$i]['desc'] = 'bank_desc';


    /* 是否支持货到付款 */

    $modules[$i]['is_cod'] = '0';


    /* 是否支持在线支付 */

    $modules[$i]['is_online'] = '0';


    /* 作者 */

    $modules[$i]['author'] = 'ECSHOP TEAM';


    /* 网址 */

    $modules[$i]['website'] = 'http://www.ecshop.com';


    /* 版本号 */

    $modules[$i]['version'] = '1.0.0';


    /* 配置信息 */

    $modules[$i]['config'] = array();


    return;

}


/**
 * 类
 */
class taoyubi

{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */


    /* 代码修改_start  By www.taoyumall.com */


    function __construct()

    {

        $this->taoyubi();

    }


    function taoyubi()

    {

    }



    /* 代码修改_end  By www.taoyumall.com */


    /**
     * 提交函数
     */

    function get_code()

    {

        return '';

    }


    /**
     * 处理函数
     */

    function response()

    {

        return;

    }

}


?>