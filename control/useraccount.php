<?php

/**
 * 淘玉php 充值和提现申请
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 充值和提现申请
 * $Id: useraccount.php 17217 2018年5月2日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class useraccountControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('user_account'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 会员余额记录列表
     */
    public function lists() {
        /* 权限判断 */
        admin_priv('surplus_manage');
        $sys_model = Model('systemset');
        $user_model = Model('users');
        /* 指定会员的ID为查询条件 */
        $user_id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        /* 获得支付方式列表 */
        $payment = array();
        $res = Model('payment')->get_payment_list('*',"enabled = '1' AND pay_code != 'cod'",'pay_order');
        foreach ($res as $value) {
            $payment[$value['pay_name']] = $value['pay_name'];
        }

        /* 模板赋值 */
        if (isset($_REQUEST['process_type'])) {
            TPL::assign('process_type_' . intval($_REQUEST['process_type']), 'selected="selected"');
        }
        if (isset($_REQUEST['is_paid'])) {
            TPL::assign('is_paid_' . intval($_REQUEST['is_paid']), 'selected="selected"');
        }
        TPL::assign('ur_here', L('09_user_account'));
        TPL::assign('id', $user_id);
        TPL::assign('payment_list', $payment);

        $list = $this->account_list();
        TPL::assign('list', $list['list']);
        TPL::assign('filter', $list['filter']);
        TPL::assign('record_count', $list['record_count']);
        TPL::assign('page_count', $list['page_count']);
        TPL::assign('full_page', 1);
        TPL::display('user_account_list.htm');
    }

    /**
     * @return ajax删除一条信息
     */
    public function remove() {
        /* 检查权限 */
        check_authz_json('surplus_manage');
        $id = @intval($_REQUEST['id']);
        $user_model = Model('users');
        $wheres = 'user_account.id='.$id;
        $user_name = $user_model->get_user_account_users_info('user_name',$wheres)['user_name'];
        $where['id'] = $id;
        if ($user_model->delete_user_account($where)) {
            admin_log(addslashes($user_name), 'remove', 'user_surplus');
            $url = 'index.php?act=useraccount&op=query&';
            ecs_header("Location: $url\n");
            exit;
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 帐户信息列表排序、分页、查询
     */
    public function query() {
        $list = $this->account_list();
        TPL::assign('list', $list['list']);
        TPL::assign('filter', $list['filter']);
        TPL::assign('record_count', $list['record_count']);
        TPL::assign('page_count', $list['page_count']);

        $sort_flag = sort_flag($list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        make_json_result(TPL::fetch('user_account_list.htm'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
    }
     
    /**
     * @return 编辑会员余额页面
     */
    public function info() {
        admin_priv('surplus_manage'); //权限判断
        $sys_model = Model('systemset');
        $user_model = Model('users');
        $ur_here = L('surplus_edit');
        $form_act = 'useraccount';
        $form_op = 'update';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        /* 获得支付方式列表, 不包括“货到付款” */
        $payment = array();
        $res = Model('payment')->get_payment_list('*',"enabled = '1' AND pay_code != 'cod'",'pay_order');
        foreach ($res as $value) {
            $payment[$value['pay_name']] = $value['pay_name'];
        }
        /* 取得余额信息 */
        $w['id'] = $id;
        $user_account = $user_model->select_user_account_info('*',$w);

        // 如果是负数，去掉前面的符号
        $user_account['amount'] = str_replace('-', '', $user_account['amount']);

        /* 取得会员名称 */
        $wh['user_id'] = $user_account['user_id'];
        $user_name_info = $user_model->select_users_info('user_name,alias', $wh);
        $user_name = empty($user_name_info['alias']) ? $user_name_info['user_name'] : $user_name_info['alias'];
        /* 模板赋值 */
        TPL::assign('ur_here', $ur_here);
        TPL::assign('form_act', $form_act);
        TPL::assign('form_op', $form_op);
        TPL::assign('payment_list', $payment);
        TPL::assign('action', $_REQUEST['act']);
        TPL::assign('user_surplus', $user_account);
        TPL::assign('user_name', $user_name);
        $href = 'index.php?act=useraccount&op=lists';
        TPL::assign('action_link', array('href' => $href, 'text' => L('09_user_account')));
        TPL::display('user_account_info.htm');
    }

    /**
     * @return 审核会员余额页面
     */
    public function check() {
        /* 检查权限 */
        admin_priv('surplus_manage');
        $user_model = Model('users');
        /* 初始化 */
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        /* 如果参数不合法，返回 */
        if ($id == 0) {
            ecs_header("Location: index.php?act=useraccount&op=lists\n");
            exit;
        }

        /* 查询当前的预付款信息 */
        $account = array();
        $w['id'] = $id;
        $account = $user_model->select_user_account_info('*',$w);
        $account['add_time'] = local_date(C('time_format'), $account['add_time']);

        //余额类型:预付款，退款申请，购买商品，取消订单
        if ($account['process_type'] == 0) {
            $process_type = L('surplus_type_0');
        } elseif ($account['process_type'] == 1) {
            $process_type = L('surplus_type_1');
        } elseif ($account['process_type'] == 2) {
            $process_type = L('surplus_type_2');
        } else {
            $process_type = L('surplus_type_3');
        }
        $wh['user_id'] = $account['user_id'];
        $user_name_info = $user_model->select_users_info('user_name', $wh);
        $user_name = $user_name_info['user_name'];
        $user_name = empty($user_name['alias']) ? $user_name['user_name'] : $user_name['alias'];

        /* 模板赋值 */
        TPL::assign('ur_here', L('check'));
        $account['user_note'] = htmlspecialchars($account['user_note']);
        TPL::assign('surplus', $account);
        TPL::assign('process_type', $process_type);
        TPL::assign('user_name', $user_name);
        TPL::assign('id', $id);
        TPL::assign('action_link', array('text' => L('09_user_account'),
            'href' => 'index.php?act=useraccount&op=lists&'));

        /* 页面显示 */
        TPL::display('user_account_check.htm');
    }

    /**
     * @return 更新会员余额的状态
     */
    public function action() {
        /* 检查权限 */
        admin_priv('surplus_manage');
        $user_model = Model('users');
        $sess = $this->admin_info;
        /* 初始化 */
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $is_paid = isset($_POST['is_paid']) ? intval($_POST['is_paid']) : 0;
        $admin_note = isset($_POST['admin_note']) ? trim($_POST['admin_note']) : '无消息提示';

        /* 如果参数不合法，返回 */
        if ($id == 0 || empty($admin_note)) {
            ecs_header("Location: index.php?act=useraccount&op=lists\n");
            exit;
        }

        /* 查询当前的预付款信息 */
        $account = array();
        $w['id'] = $id;
        $account = $user_model->select_user_account_info('*',$w);
        $amount = $account['amount'];
        if ($account['process_type'] == 1) {
            if ($is_paid == '1' && $account['is_tx'] == '0') {
                $wh['user_id'] = $account['user_id'];
                $user_account_info = $user_model->select_users_info('*',$wh);
                $user_account = $user_account_info['frozen_money'];
                $fmt_amount = str_replace('-', '', $amount);
                /*提现后扣除用户账户余额，资金进入冻结余额，如果提现的金额大于冻结余额，不允许提现*/
                if ($fmt_amount > $user_account) {
                    $link[] = array('text' => L('go_back'), 'href' => 'javascript:history.back(-1)');
                    showMessage(L('surplus_amount_error'), $link);
                }

                $param['admin_user'] = $sess['user_name'];
                $param['paid_time'] = gmtime();
                $param['admin_note'] = $admin_note;
                $param['is_tx'] = $is_paid;
                $where['id'] = $id;
                $user_model->update_user_account_info($param, $where);

                /*更新会员余额数量*/
                log_tixian_change($account['user_id'], $amount, 0, 0, 0, L('surplus_type_1'), ACT_DRAWING);
                /*是否开启余额变动给客户发短信 -提现*/
                if(!empty($user_account_info['mobile_phone'])) {
                    $param = array();
                    $param['tixian_sn'] = $fmt_amount . '元';
                    $param['yuanying'] = $admin_note;
                    $param['site_name'] = C('shop_name');
                    $result = send_sms_msg($user_account_info['mobile_phone'],'tixian_send',$param);
                }
            } elseif ($is_paid == '0' && $account['is_tx'] == '0') {
                /* 否则更新信息 */
                $param1 = array();                    
                $param1['admin_user'] = $sess['user_name'];
                $param1['admin_note'] = $admin_note;
                $param1['is_tx'] = 0;
                $where1['id'] = $id;
                $user_model->update_user_account_info($param1, $where1);
            } elseif ($is_paid == '2' && $account['is_tx'] == '0') {
                /* 否则更新信息 */
                $param2 = array();
                $param2['admin_user'] = $sess['user_name'];
                $param2['admin_note'] = $admin_note;
                $param2['is_tx'] = 2;
                $where2['id'] = $id;
                $rulersd = $user_model->update_user_account_info($param2, $where2);
                $fmt_amount = str_replace('-', '', $amount);
                log_account_change($account['user_id'], $fmt_amount, $amount, 0, 0, '提现退回', ACT_DRAWING);

                if (!empty($rulersd)) {
                    $w['user_id'] = $account['user_id'];
                    $users = $user_model->select_users_info('*', $w);
                    $param3 = array();
                    $param3['tuihu_name'] = '提现' . $id . '编号';
                    $param3['yuanying'] = $admin_note;
                    $param3['site_name'] = C('shop_name');
                    if (!empty($users['mobile_phone'])) {
                        $result3 = send_sms_msg($users['mobile_phone'],'tuihui_send',$param3);
                    }
                    $insert_msg['to_member_id'] = ',' . $account['user_id'] . ',';
                    $insert_msg['message_title'] = '提现退回';
                    $insert_msg['message_body'] = $param3['tuihu_name'].$param3['yuanying'];
                    $insert_msg['message_time'] = gmtime();
                    $insert_msg['message_update_time'] = gmtime();
                    $insert_msg['message_state'] = 0;
                    $insert_msg['message_type'] = 1;
                    $insert_msg['read_member_id'] = '';
                    $insert_msg['del_member_id'] = '';
                    $insert_msg['from_member_name'] = '';
                    $insert_msg['from_member_name'] = '';
                    $res = insert_message($insert_msg);
                }
            }
        } else {
            if ($is_paid == '1') {
                //如果是充值，并且已完成, 更新此条记录，增加相应的余额
                $param4['admin_user'] = $sess['user_name'];
                $param4['amount'] = $amount;
                $param4['paid_time'] = gmtime();
                $param4['admin_note'] = $admin_note;
                $param4['is_tx'] = $is_paid;
                $where4['id'] = $id;
                $user_model->update_user_account_info($param4, $where4);
                //更新会员余额数量
                log_account_change($account['user_id'], $amount, 0, 0, 0, L('surplus_type_0'), ACT_SAVING);
                $wh['user_id'] = $account['user_id'];
                $users = $user_model->select_users_info('*', $wh);
                $timesd = local_date('Y-m-d H:i:s');
                if(!empty($users['mobile_phone'])) {
                    $param5 = array();
                    $param5['user_name'] = $sess['user_name'];
                    $param5['user_time'] = $timesd;
                    $param5['site_name'] = C('shop_name');
                    $result = send_sms_msg($users['mobile_phone'],'pay_chong',$param5);
                }               
            } else {
                /* 否则更新信息 */
                $param6['admin_user'] = $sess['user_name'];
                $param6['admin_note'] = $admin_note;
                $param6['is_tx'] = $is_paid;
                $where6['id'] = $id;
                $user_model->update_user_account_info($param6, $where6);
            }
        }

        /* 记录管理员日志 */
        admin_log('(' . addslashes(L('check')) . ')' . $admin_note, 'edit', 'user_surplus');

        /* 提示信息 */
        $link[0]['text'] = L('back_list');
        $link[0]['href'] = 'index.php?act=useraccount&op=lists&';

        showMessage(L('attradd_succed'), $link);
    }

    /**
     * @return  充值和提现申请列表
     * @return array
     */
    private function account_list() {
        $result = get_filter();
        $user_model = Model('users');
        if ($result === false) {
            /* 过滤列表 */
            $filter['user_id'] = !empty($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
            $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
                $filter['keywords'] = json_str_iconv($filter['keywords']);
            }

            $filter['process_type'] = isset($_REQUEST['process_type']) ? intval($_REQUEST['process_type']) : -1;
            $filter['payment'] = empty($_REQUEST['payment']) ? '' : trim($_REQUEST['payment']);
            $filter['is_paid'] = isset($_REQUEST['is_paid']) ? intval($_REQUEST['is_paid']) : -1;
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'add_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['start_date'] = empty($_REQUEST['start_date']) ? '' : local_strtotime($_REQUEST['start_date']);
            $filter['end_date'] = empty($_REQUEST['end_date']) ? '' : (local_strtotime($_REQUEST['end_date']) + 86400);

            $where = " WHERE 1 ";
            if ($filter['user_id'] > 0) {
                $where .= " AND user_account.user_id = '$filter[user_id]' ";
            }
            if ($filter['process_type'] != -1) {
                $where .= " AND user_account.process_type = '$filter[process_type]' ";
            } else {
                $where .= " AND user_account.process_type " . db_create_in(array(SURPLUS_SAVE, SURPLUS_RETURN));
            }
            if ($filter['payment']) {
                $where .= " AND user_account.payment = '$filter[payment]' ";
            }
            if ($filter['is_paid'] != -1) {
                $where .= " AND user_account.is_paid = '$filter[is_paid]' ";
            }

            if ($filter['keywords']) {
                $where .= " AND users.user_name LIKE '%" . mysql_like_quote($filter['keywords']) . "%'";
            }
            /* 　时间过滤　 */
            if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
                $where .= "AND paid_time >= " . $filter['start_date'] . " AND paid_time < '" . $filter['end_date'] . "'";
            }
            $wheres = substr(trim($where),6);
            $filter['record_count'] = $user_model->get_user_account_count($wheres);

            /* 分页大小 */
            $filter = page_and_size($filter);

            /* 查询数据 */
            $sql = 'SELECT user_account.*, users.user_name,users.alias FROM ' .
                    Model()->tablename('user_account') . ' AS user_account LEFT JOIN ' .
                    Model()->tablename('users') . ' AS users ON user_account.user_id = users.user_id' .
                    $where . "ORDER by " . $filter['sort_by'] . " " . $filter['sort_order'];

            $filter['keywords'] = stripslashes($filter['keywords']);
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $list = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($list AS $key => $value) {
            $list[$key]['surplus_amount'] = price_format(abs($value['amount']), false);
            $list[$key]['add_date'] = local_date(C('time_format'), $value['add_time']);
            $list[$key]['process_type_name'] = L('surplus_type_' . $value['process_type']);
        }
        $arr = array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

        return $arr;
    }

}
?>

