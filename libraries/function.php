<?php

/**
 * 淘玉php mian公共方法
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 公共方法
 * $Id: index.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!index');

/**
 * @return 记录库存管理员的操作内容
 * @param string $sn         -唯一值
 * @param string $action          - 执行的操作（增删改/login,add,delete,update）
 * @param string $content         - 具体操作内容
 */
function admin_log($sn, $action, $content) {
    $log_info = L("log_action." . $action) . L("log_action." . $content) . ': ' . addslashes($sn);
    $admin = unserialize(decrypt(cookie('sys_key'), MD5_KEY));
    $admin_name = $admin['user_name'];
    $user_id = $admin['user_id'];
    if (empty($admin_name) || empty($user_id)) {
        @header('Location: index.php?act=login&op=login');
        exit;
    }
    $data = array(
        'log_time' => gmtime(),
        'user_id' => $user_id,
        'log_info' => stripslashes($log_info),
        'ip_address' => getIp(),
        'url' => $_REQUEST['act'] . '&' . $_REQUEST['op']
    );
    Model('admin_log')->insert($data);
}

/**
 * @return 判断管理员对某一个操作是否有权限。
 *
 * 根据当前对应的action_code，然后再和用户session里面的action_list做匹配，以此来决定是否可以继续执行。
 * @param     string    $priv_str    操作对应的priv_str
 * @param     string    $msg_type       返回的类型
 * @return true/false
 */
function admin_priv($priv_str, $msg_type = '', $msg_output = true) {
    if ($_SESSION['action_list'] == 'all') {
        return true;
    }
    if (strpos(',' . $_SESSION['action_list'] . ',', ',' . $priv_str . ',') === false) {
        $link[] = array('text' => L('go_back'), 'href' => 'javascript:history.back(-1)');
        if ($msg_output) {
            showMessage(L('priv_error'), $link);
        }
        return false;
    } else {
        return true;
    }
}

/**
 * @return 检查管理员权限
 *
 * @access  public
 * @param   string  $authz
 * @return  boolean
 */
function check_authz($authz) {
    return (preg_match('/,*' . $authz . ',*/', $_SESSION['action_list']) || $_SESSION['action_list'] == 'all');
}

/**
 * @return 检查管理员权限，返回JSON格式数剧
 *
 * @access  public
 * @param   string  $authz
 * @return  void
 */
function check_authz_json($authz) {
    if (!check_authz($authz)) {
        make_json_error(L('priv_error'));
    }
}

/**
 * @return 获取权限的分组数据并处理字段格式
 * @param strings $priv_str 用户自身权限
 */
function get_actions($priv_str) {
    /* 获取权限的分组数据 */
    $adminModel = Model('admin');
    $flied = 'action_id, parent_id, action_code,relevance';
    $where = 'parent_id = 0';
    $priv_arr;
    $action_list = $adminModel->get_action_list($flied, $where);
    foreach ($action_list AS $key => $values) {
        $priv_arr[$values['action_id']] = $values;
    }

    /* 按权限组查询底级的权限名称 */
    $where = "parent_id " . db_create_in(array_keys($priv_arr));
    $action_list = $adminModel->get_action_list($flied, $where);
    foreach ($action_list AS $key => $values) {
        $priv_arr[$values["parent_id"]]["priv"][$values["action_code"]] = $values;
    }

    // 将同一组的权限使用 "," 连接起来，供JS全选
    foreach ($priv_arr AS $action_id => $action_group) {
        $priv_arr[$action_id]['priv_list'] = join(',', @array_keys($action_group['priv']));

        foreach ($action_group['priv'] AS $key => $val) {
            $priv_arr[$action_id]['priv'][$key]['cando'] = (strpos($priv_str, $val['action_code']) !== false || $priv_str == 'all') ? 1 : 0;
        }
    }
    return $priv_arr;
}

/**
 * @return 根据过滤条件获得排序的标记
 *
 * @access  public
 * @param   array   $filter
 * @return  array
 */
function sort_flag($filter) {
    $flag['tag'] = 'sort_' . preg_replace('/^.*\./', '', $filter['sort_by']);
    $flag['img'] = '<img src="templates/default/images/' . ($filter['sort_order'] == "DESC" ? 'sort_desc.gif' : 'sort_asc.gif') . '"/>';
    return $flag;
}

/**
 * 根据商家的积分对商家进行等级划分
 * @param   int     $supplier_id        商家id
 */
function supplier_level($supplier_id) {
    $supplier_model = Model('supplier');
    $w['supplier_id'] = $supplier_id;
    $supplier_integral = $supplier_model->select_supplier_info('*',$w);
    $w= "rank_jifen <= " . $supplier_integral['jifen'];
    $order = "rank_jifen DESC";
    $limit = '1' ;
    $rank_id = $supplier_model->get_supplier_rank_list('*',$w,$order,$limit);
    $param['rank_id'] = $rank_id['rank_id'];
    $where['supplier_id'] = $supplier_id;
    $result = $supplier_model->update_supplier($param, $where);
    return $result;
}

//检查是否有权限查看手机号码
function check_look_phone($priv_str, $msg_type = '', $msg_output = true) {
    if ($_SESSION['action_list'] == 'all') {
        return true;
    }

    if (strpos(',' . $_SESSION['action_list'] . ',', ',' . $priv_str . ',') === false) {
        return false;
    } else {
        return true;
    }
}


function get_modules() {
    include_once( './api/payment/alipay.php');
    $payment = new alipay(true);
    var_dump($payment);
    return $payment->module;
}