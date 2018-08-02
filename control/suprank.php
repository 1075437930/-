<?php

/**
 * 淘玉php 入驻商等级
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 入驻商等级
 * $Id: suprank.php 17217 2018年5月10日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class suprankControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('supplier'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 入驻商等级列表
     */
    public function lists() {
        admin_priv('supplier');
        $ranks = array();
        $supplier_model = Model('supplier');
        $ranks = $supplier_model->get_supplier_rank_list('*', '', 'sort_order', '');
        TPL::assign('ur_here', L('supplier_rank_list'));
        TPL::assign('action_link', array('text' => L('add_supplier_rank'), 'href' => 'index.php?act=suprank&op=add'));
        TPL::assign('full_page', 1);
        TPL::assign('user_ranks', $ranks);
        TPL::display('supplier_rank.htm');
    }

    /**
     * @return 翻页，排序
     */
    public function query() {
        check_authz_json('supplier_rank');
        $ranks = array();
        $supplier_model = Model('supplier');
        $ranks = $supplier_model->get_supplier_rank_list('*', '', 'sort_order', '');
        TPL::assign('user_ranks', $ranks);
        make_json_result(TPL::fetch('supplier_rank.htm'));
    }

    /**
     * @return 添加供货商等级
     */
    public function add() {
        admin_priv('supplier_rank');
        $rank['rank_id'] = 0;
        $rank['rank_special'] = 0;
        $rank['sort_order'] = 50;
        TPL::assign('rank', $rank);
        TPL::assign('ur_here', L('add_supplier_rank'));
        TPL::assign('action_link', array('text' => L('supplier_rank_list'), 'href' => 'index.php?act=suprank&op=lists'));
        TPL::assign('ur_here', L('add_supplier_rank'));
        TPL::assign('form_act', 'suprank');
        TPL::assign('form_op', 'insert');
        TPL::display('supplier_rank_info.htm');
    }

    /**
     * @return 增加供货商等级到数据库
     */
    public function insert() {
        admin_priv('supplier_rank');
        /* 检查是否存在重名的会员等级 */
        $supplier_model = Model('supplier');
        $where['rank_name'] = trim($_POST['rank_name']);
        $result = $supplier_model->get_supplier_rank_list('*', $where);
        if (!empty($result)) {
            showMessage(sprintf(L('rank_name_exists'), trim($_POST['rank_name'])));
        }
        $param['rank_name'] = $_POST[rank_name];
        $param['sort_order'] = intval($_POST['sort_order']);
        $supplier_model->insert_supplier_rank($param);
        /* 管理员日志 */
        clear_cache_files();
        $lnk[] = array('text' => L('back_list'), 'href' => 'index.php?act=suprank&op=lists');
        $lnk[] = array('text' => L('add_continue'), 'href' => 'index.php?act=suprank&op=add');
        showMessage(L('add_rank_success'), $lnk);
    }

    /**
     * @return 删除供货商等级
     */
    public function remove() {
        check_authz_json('supplier_rank');
        $supplier_model = Model('supplier');
        $rank_id = intval($_GET['id']);
        $where['rank_id'] = $rank_id;
        $rank_info = $supplier_model->select_supplier_rank_info('rank_name',$where);
        /* 当前等级下是否存在供货商 */
        $supplier_count = $supplier_model->get_supplier_count($where);
        /* 如果存在，不可删除 */
        if ($supplier_count > 0) {
            make_json_error('等级'.$rank_info['rank_name'] . ' 下存在供货商，不可删除');
        } else {
            $result = $supplier_model->delete_supplier_rank($where);
            clear_cache_files();
        }
        $url = 'index.php?act=suprank&op=query';
        ecs_header("Location: $url\n");
        exit;
    }

    /**
     * @return 编辑供货商等级名称
     */
    public function edit_name() {
        check_authz_json('supplier_rank');
        $id = intval($_REQUEST['id']);
        $val = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
        $supplier_model = Model('supplier');
        $where = " rank_name = " . $val . " AND rank_id <> " . $id;
        $result = $supplier_model->get_supplier_rank_list('*', $where);
        if (empty($result)) {
            $w['rank_id'] = $id;
            $update_arr['rank_name'] = $val;
            $res = $supplier_model->update_supplier_rank($update_arr, $w);
            if ($res) {
                /* 管理员日志 */
                clear_cache_files();
                make_json_result(stripcslashes($val));
            } else {
                make_json_error(Db::error());
            }
        } else {
            make_json_error(sprintf(L('rank_name_exists'), htmlspecialchars($val)));
        }
    }

    /**
     * @return 修改排序
     */
    public function edit_sort() {
        check_authz_json('supplier_rank');
        $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
        $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);
        $supplier_model = Model('supplier');
        if ($val < 0 || $val > 255) {
            make_json_error(L('js_languages.sort_order_invalid'));
        }
        $w['rank_id'] = $rank_id;
        $update_arr['sort_order'] = $val;
        $res = $supplier_model->update_supplier_rank($update_arr, $w);
        if ($res) {
            clear_cache_files();
            make_json_result($val);
        } else {
            make_json_error($val);
        }
    }

}
?>

