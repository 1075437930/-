<?php

/**
 * 淘玉 后台会员帐户变动明细管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萧瑟 $
 * 后台会员帐户变动明细管理类
 * $Id: account_log.php  2018-04-07   萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class account_logControl extends BaseControl {
    
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('accountlog,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }


    /**
     * @return 会员帐户变动明细列表
     */
    public function lists() {
    	/* 接收参数 */
	    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
	    if ($user_id <= 0) {
	        die('invalid params');
	    }
	    $user = user_info($user_id);
	    if (empty($user)) {
	        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('user_not_exist',''), $link);
	    }
	    Tpl::assign('user', $user);

	    if (empty($_REQUEST['account_type']) || !in_array($_REQUEST['account_type'],
	        array('user_money', 'frozen_money', 'rank_points', 'pay_points', 'taoyu_money'))
	    ) {
	        $account_type = '';
	    } else {
	        $account_type = $_REQUEST['account_type'];
	    }
	    Tpl::assign('account_type', $account_type);

	    Tpl::assign('ur_here', L('account_list'));
	    Tpl::assign('action_link', array('text' => L('add_account'), 'href' => 'index.php?act=account_log&op=add&user_id=' . $user_id));
	    Tpl::assign('full_page', 1);

	    $account_list = $this->get_account_list($user_id, $account_type);
	    Tpl::assign('account_list', $account_list['account']);
	    Tpl::assign('filter', $account_list['filter']);
	    Tpl::assign('record_count', $account_list['record_count']);
	    Tpl::assign('page_count', $account_list['page_count']);
	    Tpl::display('account_list.htm');
    }

    /**
     * @return 会员帐户变动明细列表排序、分页、查询
     */
    public function query() {
    	/* 接收参数 */
	    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
	    if ($user_id <= 0) {
	        die('invalid params');
	    }
	    $user = user_info($user_id);
	    if (empty($user)) {
	        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('user_not_exist',''), $link);
	    }
	    Tpl::assign('user', $user);

	    if (empty($_REQUEST['account_type']) || !in_array($_REQUEST['account_type'],
	        array('user_money', 'frozen_money', 'rank_points', 'pay_points', 'taoyu_money'))
	    ) {
	        $account_type = '';
	    } else {
	        $account_type = $_REQUEST['account_type'];
	    }
	    Tpl::assign('account_type', $account_type);
	    $account_list = $this->get_account_list($user_id, $account_type);
	    Tpl::assign('account_list', $account_list['account']);
	    Tpl::assign('filter', $account_list['filter']);
	    Tpl::assign('record_count', $account_list['record_count']);
	    Tpl::assign('page_count', $account_list['page_count']);

	    make_json_result(Tpl::fetch('account_list.htm'), '',
	        array('filter' => $account_list['filter'], 'page_count' => $account_list['page_count']));
    }

    /**
     * @return 调节会员帐户页面
     */
    public function add() {
    	/* 检查权限 */
	    /*admin_priv('account_manage');*/

	    /* 接收参数 */
	    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);	    
	    if ($user_id <= 0) {
	        die('invalid params');
	    }
	    $user = user_info($user_id);
	    if (empty($user)) {
	        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('user_not_exist',''), $link);
	    }
	    Tpl::assign('user', $user);

	    /* 显示模板 */
	    Tpl::assign('ur_here', L('add_account'));
	    Tpl::assign('action_link', array('href' => 'index.php?act=account_log&op=lists&user_id=' . $user_id, 'text' => L('account_list')));
	    Tpl::display('account_info.htm');
    }

    /**
     * @return 调节会员帐户数据入库
     */
    public function insert() {
    	/* 检查权限 */
	    /*admin_priv('account_manage');*/

	    /* 接收参数 */
	    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);	    
	    if ($user_id <= 0) {
	        die('invalid params');
	    }
	    $user = user_info($user_id);
	    if (empty($user)) {
	        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('user_not_exist',''), $link);
	    }

	    /* 接收参数 */
	    $change_desc = sub_str($_POST['change_desc'], 255, false);
	    $user_money = floatval($_POST['add_sub_user_money']) * abs(floatval($_POST['user_money']));
	    $frozen_money = floatval($_POST['add_sub_frozen_money']) * abs(floatval($_POST['frozen_money']));
	    $rank_points = floatval($_POST['add_sub_rank_points']) * abs(floatval($_POST['rank_points']));
	    $pay_points = floatval($_POST['add_sub_pay_points']) * abs(floatval($_POST['pay_points']));
	    $taoyu_money = floatval($_POST['add_sub_taoyu_money']) * abs(floatval($_POST['taoyu_money']))*10;
	    /*数据验证*/
	    if ($user_money == 0 && $frozen_money == 0 && $rank_points == 0 && $pay_points == 0 && $taoyu_money == 0) {
	    	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('no_account_change',''), $link);
	    }

	    /* 保存变动信息*/
	    if($taoyu_money != 0){
	    	log_account_change_taoyu($user_id,$taoyu_money,0,0,0,$change_desc,$change_type = ACT_OTHER);
	    }
	    if ($user_money != 0 || $frozen_money != 0 || $rank_points != 0 || $pay_points != 0 ){
	    	log_account_change($user_id, $user_money, $frozen_money, $rank_points, $pay_points, $change_desc, ACT_ADJUSTING);
		}	   	    

	    /*发送推送*/
	    if (1) {
	        if (abs($user_money) > 0) {
	            if ($_POST['add_sub_user_money'] > 0) {
	                $user_money = '+' . $user_money;
	            } else {
	                $user_money = '-' . $user_money;
	            }
				$users = Model('users')->select_users_info('user_name,user_money','user_id ='.$user_id);
				$user_names = $users['user_name'];	           
	            $message['to_member_id'] = ','.$user_id.',';
                $message['message_title'] = '资金变动信息';
                $message['message_body'] = '亲爱的'.$user_names.'用户，您的可用资金帐户'.$user_money.'元。当前余额为'.$users['user_money'];
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($users['user_name'])&& !empty($messageid)){
                    $content = '亲爱的'.$user_names.'用户，您的可用资金帐户：'.$user_money.'元。当前为'.$users['user_money'];
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $user_id , ['title'=>'资金变动信息','body'=>$content]);
                    }
                }
	            
	        }
	        if (abs($frozen_money) > 0) {
	            if ($_POST['add_sub_frozen_money'] > 0) {
	                $frozen_money = '+' . $frozen_money;
	            } else {
	                $frozen_money = '-' . $frozen_money;
	            }
				$users = Model('users')->select_users_info('user_name,frozen_money','user_id ='.$user_id);
				$user_names = $users['user_name'];	           
	            $message['to_member_id'] = ','.$user_id.',';
                $message['message_title'] = '资金变动信息';
                $message['message_body'] = '亲爱的'.$user_names.'用户，您的冻结资金帐户'.$frozen_money.'元。当前为'.$users['frozen_money'];
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($users['user_name'])&& !empty($messageid)){
                    $content = '亲爱的'.$user_names.'用户，您的冻结资金帐户：'.$frozen_money.'元。当前为'.$users['frozen_money'];
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $user_id , ['title'=>'资金变动信息','body'=>$content]);
                    }
                }
	            
	        }
	        if (abs($rank_points) > 0) {
	            if ($_POST['add_sub_rank_points'] > 0) {
	                $rank_points = '+' . $rank_points;
	            } else {
	                $rank_points = '-' . $rank_points;
	            }
				$users = Model('users')->select_users_info('user_name,rank_points','user_id ='.$user_id);
				$user_names = $users['user_name'];	           
	            $message['to_member_id'] = ','.$user_id.',';
                $message['message_title'] = '资金变动信息';
                $message['message_body'] = '亲爱的'.$user_names.'用户，您的等级积分帐户'.$rank_points.'元。当前为'.$users['rank_points'];
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($users['user_name'])&& !empty($messageid)){
                    $content = '亲爱的'.$user_names.'用户，您的等级积分帐户：'.$rank_points.'元。当前为'.$users['rank_points'];
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $user_id , ['title'=>'资金变动信息','body'=>$content]);
                    }
                }
	            
	        }
	        if (abs($pay_points) > 0) {
	            if ($_POST['add_sub_pay_points'] > 0) {
	                $pay_points = '+' . $pay_points;
	            } else {
	                $pay_points = '-' . $pay_points;
	            }
				$users = Model('users')->select_users_info('user_name,pay_points','user_id ='.$user_id);
				$user_names = $users['user_name'];	           
	            $message['to_member_id'] = ','.$user_id.',';
                $message['message_title'] = '资金变动信息';
                $message['message_body'] = '亲爱的'.$user_names.'用户，您的消费积分帐户'.$pay_points.'元。当前为'.$users['pay_points'];
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($users['user_name'])&& !empty($messageid)){
                    $content = '亲爱的'.$user_names.'用户，您的消费积分帐户：'.$pay_points.'元。当前为'.$users['pay_points'];
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $user_id , ['title'=>'资金变动信息','body'=>$content]);
                    }
                }
	            
	        }
	        if (abs($taoyu_money) > 0) {
	            if ($_POST['add_sub_pay_points'] > 0) {
	                $taoyu_money = '+' . $taoyu_money;
	            } else {
	                $taoyu_money = '-' . $taoyu_money;
	            }
				$users = Model('users')->select_users_info('user_name,taoyu_money','user_id ='.$user_id);
				$user_names = $users['user_name'];	           
	            $message['to_member_id'] = ','.$user_id.',';
                $message['message_title'] = '资金变动信息';
                $message['message_body'] = '亲爱的'.$user_names.'用户，您的淘玉币帐户'.$taoyu_money.'币。当前为'.$users['taoyu_money'];
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($users['user_name'])&& !empty($messageid)){
                    $content = '亲爱的'.$user_names.'用户，您的淘玉币帐户：'.$taoyu_money.'币。当前为'.$users['taoyu_money'];
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $user_id , ['title'=>'资金变动信息','body'=>$content]);
                    }
                }
	            
	        }
	    }
	    /* 提示信息 */
	    $link = array('text' => L('account_list',''), 'href' => 'index.php?act=account_log&op=lists&user_id=' . $user_id);
        showMessage(L('log_account_change_ok',''), $link);
    }




    /**
	 * @return  取得帐户明细列表
	 * @param   int $user_id 用户id
	 * @param   string $account_type 帐户类型：空表示所有帐户，user_money表示可用资金，
	 * frozen_money表示冻结资金，rank_points表示等级积分，pay_points表示消费积分
	 * @return  array
	 */
	function get_account_list($user_id, $account_type = '') {
		$account = Model('accountlog');
		$result = get_filter();
		if ($result === false){
			$where = "AND user_id = '$user_id' ";
		    if (in_array($account_type, array('user_money', 'frozen_money', 'rank_points', 'pay_points', 'taoyu_money'))) {
		        $where .= " AND $account_type <> 0 ";
		    }

		    /* 初始化分页参数 */
		    $filter = array(
		        'user_id' => $user_id,
		        'account_type' => $account_type
		    );

		    /* 查询记录总数，计算分页数 */
		    $filter['record_count'] = $account->get_account_log_count($where);
		    $filter = page_and_size($filter);

		    /* 查询记录 */
		    $sql = "SELECT * FROM " . Model()->tablename('account_log') . 'WHERE 1 '.$where .
		        " ORDER BY log_id DESC";
		    set_filter($filter, $sql);    
		} else {
			$sql    = $result['sql'];
            $filter = $result['filter'];
		}
	    
	    $res = get_all_page($sql, $filter['page_size'], $filter['start']);

	    $arr = array();
	    if(!empty($row)){
	    	foreach ($res as $row) {
		        $row['change_time'] = local_date(C('time_format'), $row['change_time']);
		        $arr[] = $row;
		    }
	    }
	    return array('account' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	}
       


































}