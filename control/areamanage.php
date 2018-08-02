<?php

/**
 * 淘玉php 区域管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 区域管理
 * $Id: areamanage.php 17217 2018年4月26日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class areamanageControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('areamanage'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 区域列表
     */
    public function lists() {
        
        admin_priv('area_manage');
        $area_model = Model('systemset');
        $region_model = Model('region');
        /* 取得参数：上级地区id */
        $region_id = empty($_REQUEST['pid']) ? 0 : intval($_REQUEST['pid']);

        TPL::assign('parent_id', $region_id);

        /* 取得列表显示的地区的类型 */

        if ($region_id == 0) {
            $region_type = 0;
        } else {
            $w['region_id'] = $region_id;
            $res = $region_model->select_region_info('*',$w);
            $region_type = $res['region_type'] + 1;
        }
        
        TPL::assign('region_type', $region_type);

        /* 获取地区列表 */

        $region_arr = $this->area_list($region_id);

        TPL::assign('region_arr', $region_arr);

        /* 当前的地区名称 */
        if ($region_id > 0) {
            $h['region_id'] = $region_id;
            $arr = $region_model->select_region_info('*',$h);

            $area_name = $arr['region_name'];

            $area = '[ ' . $area_name . ' ] ';

            if ($region_arr) {

                $area .= $region_arr[0]['type'];
            }
        } else {

            $area = L('country');
        }

        TPL::assign('area_here', $area);

        /* 返回上一级的链接 */

        if ($region_id > 0) {
            $wh['region_id'] = $region_id;
            $arr1 = $region_model->select_region_info('*',$wh);
            $parent_id = $arr1['parent_id'];
            $action_link = array('text' => L('back_page'), 'href' => 'index.php?act=areamanage&op=lists&pid=' . $parent_id);
        } else {

            $action_link = '';
        }

        TPL::assign('action_link', $action_link);

        /* 赋值模板显示 */

        TPL::assign('ur_here', L('05_area_list'));

        TPL::assign('full_page', 1);

//        assign_query_info();

        TPL::display('area_list.htm');
    }

    /**
     * @return 添加新的地区
     */
    public function add_area() {
        check_authz_json('area_manage');
        $parent_id = intval($_POST['parent_id']);
        $region_name = json_str_iconv(trim($_POST['region_name']));
        $region_type = intval($_POST['region_type']);
        if (empty($region_name)) {
            make_json_error(L('region_name_empty'));
        }
        /* 查看区域是否重复 */
        $where['region_name'] = $region_name;
        $where['parent_id'] = $parent_id;
        $result = Model('region')->select_region_info('*', $where);
        if (!empty($result)) {
            make_json_error(L('region_name_exist'));
        }
        $param['parent_id'] = $parent_id;
        $param['region_name'] = $region_name;
        $param['region_type'] = $region_type;
        $arr_result = Model('region')->insert_region($param);
        if ($arr_result) {
            admin_log($region_name, 'add', 'area');
            /* 获取地区列表 */
            $region_arr = $this->area_list($parent_id);
            TPL::assign('region_arr', $region_arr);
            TPL::assign('region_type', $region_type);
            make_json_result(TPL::fetch('area_list.htm'));
        } else {
            make_json_error(L('add_area_error'));
        }
    }

    /**
     * @return 编辑区域名称
     * 
     */
    public function edit_area_name() {
        check_authz_json('area_manage');
        $id = intval($_POST['id']);
        $area_model = Model('region');
        $region_name = json_str_iconv(trim($_POST['val']));
        if (empty($region_name)) {
            make_json_error(L('region_name_empty'));
        }
        $msg = '';
        /* 查看区域是否重复 */
        $w['region_id'] = $id;
        $res = $area_model->select_region_info('*',$w);
        $parent_id = $res['parent_id'];
        $result = $area_model->select_region_info('*', "region_name = '".$region_name ."' AND region_id <>".$id." AND parent_id = ".$parent_id);
        if (!empty($result)) {
            make_json_error(L('region_name_exist'));
        }
        $param['region_name'] = $region_name;
        $where['region_id'] = $id;
        $res = $area_model->update_region($param,$where);
        if ($res) {
            admin_log($region_name, 'edit', 'area');
            make_json_result(stripslashes($region_name));
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 删除区域
     * 
     */
    public function drop_area() {
        check_authz_json('area_manage');
        $id = intval($_REQUEST['id']);
        $w['region_id'] = $id;
        $region = Model('region')->select_region_info('*',$w);
        $region_type = $region['region_type'];
        $delete_region[] = $id;
        $new_region_id = $id;
        if ($region_type < 6) {
            for ($i = 1; $i < 6 - $region_type; $i++) {
                $new_region_id = $this->new_region_id($new_region_id);
                if (count($new_region_id)) {
                    $delete_region = array_merge($delete_region, $new_region_id);
                } else {
                    continue;
                }
            }
        }
        $where = "region_id ".db_create_in($delete_region);
        $result=Model('region')->delete_region($where);
        if ($result) {
            admin_log(addslashes($region['region_name']), 'remove', 'area');
            /* 获取地区列表 */
            $region_arr = $this->area_list($region['parent_id']);
            TPL::assign('region_arr', $region_arr);
            TPL::assign('region_type', $region['region_type']);
            make_json_result(TPL::fetch('area_list.htm'));
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * 获取地区列表的函数。
     *
     * @access  public
     * @param   int     $region_id  上级地区id
     * @return  void
     */
    function area_list($region_id) {
        $area_arr = array();
        $where['parent_id'] = $region_id;
        $res = Model('region')->get_regoin_list('*',$where,'region_id');
        foreach ($res as $key => $value) {
            $res[$key]['type'] = ($value['region_type'] == 0) ? L('country') : '';
            $res[$key]['type'] .= ($value['region_type'] == 1) ? L('province') : '';
            $res[$key]['type'] .= ($value['region_type'] == 2) ? L('city') : '';
            $res[$key]['type'] .= ($value['region_type'] == 3) ? L('cantonal') : '';
        }
        return $res;
    }

    function new_region_id($region_id) {
        $regions_id = array();
        if (empty($region_id)) {
            return $regions_id;
        }
        $where = " parent_id ".db_create_in($region_id);
        $result = Model('region')->get_regoin_list('*',$where);
        foreach ($result as $val) {
            $regions_id[] = $val['region_id'];
        }
        return $regions_id;
    }

}
?>

