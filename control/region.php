<?php

/**
 * 淘玉php 地区控制器
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 地区控制器
 * $Id: region.php 17217 2018年5月2日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class regionControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
//        Language::read('user_rank'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 用户等级列表
     */
    function get_region() {
        $type = !empty($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $parent = !empty($_REQUEST['parent']) ? intval($_REQUEST['parent']) : 0;
        $arr['regions'] = get_regions($type, $parent);
        $arr['type'] = $type;
        $arr['target'] = !empty($_REQUEST['target']) ? stripslashes(trim($_REQUEST['target'])) : '';
        $arr['target'] = htmlspecialchars($arr['target']);
        $json = new JSON;
        echo $json->encode($arr);
    }

}
?>

