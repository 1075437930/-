<?php
/**
 * 淘玉php 商城后台入口
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 商城后台入口
 * $Id: index.php 17217 2015-05-19 06:29:08Z 淘玉 $
*/
define('APP_ID','tao_yuec');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));//当前目录
if (!@include(dirname(dirname(__FILE__)).'/taoyushop.php')) exit('taoyushop.php isn\'t exists!');//当前目录的上级目录下面
if (!@include(BASE_CORE_PATH.'/taoyuphp.php')) exit('taoyuphp.php isn\'t exists!');//核心目录里面的运行框架文件
if (!@include(BASE_PATH.'/config/config.ini.php')) exit('config.ini.php isn\'t exists!');//当前目录下面配置文件

define('TPL_NAME',TPL_ADMIN_NAME);
define('ADMIN_TEMPLATES_URL',ADMIN_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);
//创建缓存文件给权限 temp必须先存在
if (!file_exists('./temp/caches')){
    @mkdir('./temp/caches', 0777);
    @chmod('./temp/caches', 0777);
}
if (!file_exists('./temp/compiled')){
    @mkdir('./temp/compiled', 0777);
    @chmod('./temp/compiled', 0777);
}
if (!file_exists('./temp/static_caches')){
    @mkdir('./temp/static_caches', 0777);
    @chmod('./temp/static_caches', 0777);
}

//自动加载项目框架扩展
$basefiles = glob(BASE_PATH.'/libraries/*.php');
foreach ($basefiles as $file) {
    if(is_file($file)){
        require_once($file) ;
    }
}
if (!@include(BASE_PATH.'/control/base.php')) exit('base.php isn\'t exists!');//获取admin里面控制器初始化类
Base::run();
