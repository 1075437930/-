<?php

/**
 * 淘玉php 用户等级
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 用户等级
 * $Id: userrank.php 17217 2018年5月2日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class userrankControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('user_rank'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 用户等级列表
     */
    function lists() {
        $ranks = array();
        $user_model = Model('users');
        $ranks = $user_model->get_user_rank_list('*','1');
        TPL::assign('ur_here', L('05_user_rank_list'));
        TPL::assign('action_link', array('text' => L('add_user_rank'), 'href' => 'index.php?act=userrank&op=add'));
        TPL::assign('full_page', 1);
        TPL::assign('user_ranks', $ranks);
        TPL::display('user_rank.htm');
    }

    /**
     * @return ajax返回用户列表
     */
    function query() {
        $ranks = array();
        $user_model = Model('users');
        $ranks = $user_model->get_user_rank_list();
        TPL::assign('user_ranks', $ranks);
        make_json_result(TPL::fetch('user_rank.htm'));
    }

    /**
     * @return 添加会员等级
     */
    function add() {
        admin_priv('user_rank');
        $rank['rank_id'] = 0;
        $rank['rank_special'] = 0;
        $rank['show_price'] = 1;
        $rank['min_points'] = 0;
        $rank['max_points'] = 0;
        $rank['discount'] = 100;
        $form_action = 'userrank';
        $form_op = 'insert';
        TPL::assign('rank', $rank);
        TPL::assign('ur_here', L('add_user_rank'));
        TPL::assign('action_link', array('text' => L('05_user_rank_list'), 'href' => 'index.php?act=userrank&op=lists'));
        TPL::assign('form_action', $form_action);
        TPL::assign('form_op', $form_op);
        TPL::display('user_rank_info.htm');
    }

    /**
     * @return 增加会员等级到数据库
     */
    public function insert() {
        admin_priv('user_rank');
        $user_model = Model('users');
        $special_rank = isset($_POST['special_rank']) ? intval($_POST['special_rank']) : 0;
        $_POST['min_points'] = empty($_POST['min_points']) ? 0 : intval($_POST['min_points']);
        $_POST['max_points'] = empty($_POST['max_points']) ? 0 : intval($_POST['max_points']);
        $recomm = isset($_POST['recomm']) ? intval($_POST['recomm']) : 0;
        /* 检查是否存在重名的会员等级 */
        if ($user_model->select_user_rank_info('rank_name', "rank_name = '".trim($_POST['rank_name'])."'")) {
            showMessage(sprintf(L('rank_name_exists'), trim($_POST['rank_name'])));
        }

        /* 非特殊会员组检查积分的上下限是否合理 */
        if ($_POST['min_points'] >= $_POST['max_points'] && $special_rank == 0) {
            showMessage(L('rank_name_exists.integral_max_small'));
        }

        /* 特殊等级会员组不判断积分限制 */
        if ($special_rank == 0) {
            /* 检查下限制有无重复 */
            if ($user_model->select_user_rank_info('min_points', "min_points = ".intval($_POST['min_points']))) {
                showMessage(sprintf(L('integral_min_exists'), intval($_POST['min_points'])));
            }
        }

        /* 特殊等级会员组不判断积分限制 */
        if ($special_rank == 0) {
            /* 检查上限有无重复 */
            if ($user_model->select_user_rank_info('max_points', "max_points = ".intval($_POST['max_points']))) {
                showMessage(sprintf(L('integral_max_exists'), intval($_POST['max_points'])));
            }
        }
        $param['rank_name'] = $_POST[rank_name];
        $param['min_points'] = intval($_POST['min_points']);
        $param['max_points'] = intval($_POST['max_points']);
        $param['discount'] = $_POST[discount];
        $param['special_rank'] = $special_rank;
        $param['is_recomm'] = $recomm;
        $param['show_price'] = intval($_POST['show_price']);
        $result = add_table_info('user_rank', $param);
        /* 管理员日志 */
        admin_log(trim($_POST['rank_name']), 'add', 'user_rank');
        clear_cache_files();
        $lnk[] = array('text' => L('back_list'), 'href' => 'index.php?act=userrank&op=lists');
        $lnk[] = array('text' => L('add_continue'), 'href' => 'index.php?act=userrank&op=add');
        showMessage(L('add_rank_success'), $lnk);
    }

    /**
     * @return 删除会员等级
     */
    public function remove() {
        check_authz_json('user_rank');
        $user_model = Model('users');
        $rank_id = intval($_GET['id']);
        $where['rank_id'] = $rank_id;
        $rank_info = $user_model->select_user_rank_info('*', $where);
        $rank_name = $rank_info['rank_name'];
        $result = delete_table_info('user_rank', $where);
        if ($result) {            
            admin_log($rank_name, 'remove', 'user_rank');
            clear_cache_files();
        }
        $url = 'index.php?act=userrank&op=query';
        ecs_header("Location: $url\n");
        exit;
    }

    /**
     * @return 在列表中编辑会员等级
     */
    public function edit_name() {
        $user_model = Model('users');
        $id = intval($_REQUEST['id']);
        $val = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
        check_authz_json('user_rank');
        if ($user_model->select_user_rank_info('rank_name', " rank_name = ".$val." AND rank_id <>".$id)) {
            $field['rank_name'] = $val;
            $where['rank_id'] = $id;
            if ($user_model->update_user_rank($field, $where)) {
                /* 管理员日志 */
                admin_log($val, 'edit', 'user_rank');
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
     * @return 在列表中编辑会员等级积分下限
     */
    public function edit_min_points() {
        $user_model = Model('users');
        check_authz_json('user_rank');
        $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
        $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);
        $where['rank_id'] = $rank_id;
        $rank = $user_model->select_user_rank_info('*', $where);
        if ($val >= $rank['max_points'] && $rank['special_rank'] == 0) {
            make_json_error(L('js_languages.integral_max_small'));
        }

        if ($rank['special_rank'] == 0 && $user_model->select_user_rank_info('min_points', " min_points = ".$val." AND rank_id <>". $rank_id)) {
            make_json_error(sprintf(L('integral_min_exists'), $val));
        }
        $field['min_points'] = $val;
        $where['rank_id'] = $rank_id;
        if ($user_model->update_user_rank($field, $where)) {
            $rank_name = $rank['rank_name'];
            admin_log(addslashes($rank_name), 'edit', 'user_rank');
            make_json_result($val);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 在列表中编辑会员等级积分上限
     */
    public function edit_max_points() {
        $user_model = Model('users');
        check_authz_json('user_rank');
        $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
        $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);
        $w['rank_id'] = $rank_id;
        $rank = $user_model->select_user_rank_info('*',$w);
        if ($val <= $rank['min_points'] && $rank['special_rank'] == 0) {
            make_json_error(L('js_languages.integral_max_small'));
        }

        if ($rank['special_rank'] == 0 && !$user_model->select_user_rank_info('max_points', " max_points = ".$val." AND rank_id <>". $rank_id)) {
            make_json_error(sprintf(L('integral_max_exists'), $val));
        }
        $field['max_points'] = $val;
        $where['rank_id'] = $id;
        if ($user_model->update_user_rank($field, $where)) {
            $rank_name = $rank['rank_name'];
            admin_log(addslashes($rank_name), 'edit', 'user_rank');
            make_json_result($val);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 在列表中编辑会员等级折扣率
     */
    public function edit_discount() {
        check_authz_json('user_rank');
        $user_model = Model('users');
        $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
        $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);
        $w['rank_id'] = $rank_id;
        $rank = $user_model->select_user_rank_info('*',$w);
        if ($val < 1 || $val > 100) {
            make_json_error(L('js_languages.discount_invalid'));
        }

        $field['discount'] = $val;
        $where['rank_id'] = $rank_id;
        if ($user_model->update_user_rank($field, $where)) {
            $rank_name = $rank['rank_name'];
            admin_log(addslashes($rank_name), 'edit', 'user_rank');
            clear_cache_files();
            make_json_result($val);
        } else {
            make_json_error($val);
        }
    }

    /**
     * @return 切换是否是特殊会员组
     */
    public function toggle_special() {
        check_authz_json('user_rank');
        $user_model = Model('users');
        $rank_id = intval($_POST['id']);
        $is_special = intval($_POST['val']);
        $w['rank_id'] = $rank_id;
        $rank = $user_model->select_user_rank_info('*',$w);
        $field['special_rank'] = $is_special;
        $where['rank_id'] = $rank_id;
        if ($user_model->update_user_rank($field, $where)) {
            $rank_name = $rank['rank_name'];
            admin_log(addslashes($rank_name), 'edit', 'user_rank');
            make_json_result($is_special);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 切换是否显示价格
     */
    public function toggle_showprice() {
        check_authz_json('user_rank');
        $user_model = Model('users');
        $rank_id = intval($_POST['id']);
        $is_show = intval($_POST['val']);
        $w['rank_id'] = $rank_id;
        $rank = $user_model->select_user_rank_info('*',$w);
        $field['show_price'] = $is_show;
        $where['rank_id'] = $rank_id;
        if ($user_model->update_user_rank($field, $where)) {
            $rank_name = $rank['rank_name'];
            admin_log(addslashes($rank_name), 'edit', 'user_rank');
            make_json_result($is_show);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 切换是否显示会员分成
     */
    public function toggle_is_recomm() {
        check_authz_json('user_rank');
        $user_model = Model('users');
        $rank_id = intval($_POST['id']);
        $is_show = intval($_POST['val']);
        $w['rank_id'] = $rank_id;
        $rank = $user_model->select_user_rank_info('*',$w);
        $field['is_recomm'] = $is_show;
        $where['rank_id'] = $rank_id;
        if ($user_model->update_user_rank($field, $where)) {
            $rank_name = $rank['rank_name'];
            admin_log(addslashes($rank_name), 'edit', 'user_rank');
            make_json_result($is_show);
        } else {
            make_json_error(Db::error());
        }
    }

}
?>

