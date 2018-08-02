<?php

/**
 * 淘玉php 店铺分类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 店铺分类
 * $Id: supstreetcat.php 17217 2018年5月10日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class supstreetcatControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('supstreetcat'); //载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 店铺街分类列表
     */
    public function lists() {
        /* 获取分类列表 */
        $cat_list = $this->street_list();
        /* 模板赋值 */
        TPL::assign('ur_here', L('supplier_street_cat'));
        TPL::assign('action_link', array('href' => 'index.php?act=supstreetcat&op=add', 'text' => L('category_add')));
        TPL::assign('full_page', 1);
        TPL::assign('cat_info', $cat_list);
        /* 列表页面 */
        TPL::display('street_category_list.htm');
    }

    /**
     * @return 店铺街分类列表分页、排序、查询
     */
    public function query() {
        $cat_list = $this->street_list();
        TPL::assign('cat_info', $cat_list);
        make_json_result(TPL::fetch('street_category_list.htm'));
    }

    /**
     * @return 添加商品分类
     */
    public function add() {
        /* 权限检查 */
        admin_priv('supplier_manage');
        /* 模板赋值 */
        TPL::assign('ur_here', L('category_add'));
        TPL::assign('action_link', array('href' => 'index.php?act=supstreetcat&op=lists', 'text' => L('supplier_street_cat_list')));
        TPL::assign('form_act', 'supstreetcat');
        TPL::assign('form_op', 'insert');
        TPL::assign('cat_info', array('is_show' => 1));
        /* 显示页面 */
        TPL::display('street_category_info.htm');
    }

    /**
     * @return 商品分类添加时的处理
     */
    public function insert() {
        /* 权限检查 */
        admin_priv('supplier_manage');
        $supplier_model = Model('supplier');
        /* 初始化变量 */
        $cat['is_groom'] = !empty($_POST['is_groom']) ? intval($_POST['is_groom']) : 0;
        $cat['sort_order'] = !empty($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
        $cat['is_show'] = !empty($_POST['is_show']) ? intval($_POST['is_show']) : 0;
        $cat['str_style'] = !empty($_POST['str_style']) ? trim(addslashes(htmlspecialchars($_POST['str_style']))) : 0;
        $cat['str_name'] = !empty($_POST['str_name']) ? trim($_POST['str_name']) : '';
        $arrCatName = explode(",", $cat['str_name']);
        foreach ($arrCatName as $arrCatNameValue) {
            $cat['str_name'] = $arrCatNameValue;
            $result = Model('street_category')->get_street_category_list('*'," str_name = '$cat_name' AND str_id<>'$exclude'");
            if ($result) {
                /* 同级别下不能有重复的分类名称 */
                $link[] = array('text' => L('go_back'), 'href' => 'javascript:history.back(-1)');
                showMessage(L('category_name_exist'), $link);
            }
            /* 入库的操作 */
            $cat_id = Model('street_category')->insert_street_category($cat);
        }
        admin_log($_POST['str_name'], 'add', 'street_category');
        clear_cache_files();
        /* 添加链接 */
        $link[0]['text'] = L('category_add_continue');
        $link[0]['href'] = 'index.php?act=supstreetcat&op=add';
        $link[1]['text'] = L('back_to_list');
        $link[1]['href'] = 'index.php?act=supstreetcat&op=lists';
        showMessage(L('category_add_succ'), $link);
    }

    /**
     * @return 编辑样式
     */
    public function edit_str_style() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = trim(addslashes(htmlspecialchars($_POST['val'])));
        $w['str_id'] = $id;
        if (Model('street_category')->update_street_category(array('str_style' => $val), $w)) {
            clear_cache_files();
            make_json_result($val);
        } else {
            make_json_result('','database error',array('error'=>1));
        }
    }

    /**
     * @return 编辑排序序号
     */
    public function edit_sort_order() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $w['str_id'] = $id;
        if (Model('street_category')->update_street_category(array('sort_order' => $val), $w)) {
            clear_cache_files();
            make_json_result($val);
        } else {
            make_json_result('','database error',array('error'=>1));
        }
    }

    /**
     * @return 切换是否显示
     */
    public function toggle_is_show() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $w['str_id'] = $id;
        if (Model('street_category')->update_street_category(array('is_show' => $val), $w) != false) {
            clear_cache_files();
            make_json_result($val);
        } else {
            make_json_result('','database error',array('error'=>1));
        }
    }

    /**
     * @return 切换是否推荐
     */
    public function toggle_is_groom() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $w['str_id'] = $id;
        if (Model('street_category')->update_street_category(array('is_groom' => $val), $w) != false) {
            clear_cache_files();
            make_json_result($val);
        } else {
            make_json_result('','database error',array('error'=>1));
        }
    }

    /**
     * @return 删除店铺分类
     */
    public function remove() {
        check_authz_json('supplier_manage');
 
        /* 初始化分类ID并取得分类名称 */
        $cat_id = intval($_REQUEST['id']);
        $w['str_id'] = $cat_id;
        $cat_info = Model('street_category')->select_street_category_info('*',$w);
        $cat_name = $cat_info['str_name'];
        /* 当前分类下是否存在店铺 */
        $wh['supplier_type'] = $cat_id;
        $shop_count_arr = Model('street_category')->get_supplier_street_list('*',$wh);
        /* 如果不存在店铺，则删除之 */
        if (empty($shop_count_arr)) {
            /* 删除分类 */
            $where['str_id'] = $cat_id;
            Model('street_category')->delete_street_category($where);
        } else {
            make_json_error('分类'.$cat_name . ' 下存在店铺，不可删除');
        }
        $url = 'index.php?act=supstreetcat&op=query';
        ecs_header("Location: $url\n");
        exit;
    }

    /**
     * @return 获取店铺街分类列表
     * @return void
     */
    private function street_list() {
        $where = ' 1=1 GROUP BY supplier_type ';
        $rows = Model('street_category')->get_supplier_street_list('supplier_type,count( supplier_type ) as num', $where);
        $arr = Model('street_category')->get_street_category_list('*', '', 'sort_order');
        if ($rows) {
            foreach ($rows as $key => $val) {
                foreach ($arr as $k => $v) {
                    if ($v['str_id'] == $val['supplier_type']) {
                        $arr[$k]['num'] = $val['num'];
                    }
                }
            }
        }
        return $arr;
    }
}
?>

