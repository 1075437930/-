<?php

/**
 * 淘玉php 后台典藏订单管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台典藏订单管理类
 * $Id: dcorder.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class dcorderControl extends BaseControl {
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('order,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 典藏订单列表
     */
    public function lists() {
    	admin_priv('dcorder');
		$dcorder_list = $this->get_dcorder_list();
		Tpl::assign('full_page', 1);
		Tpl::assign('ur_here', '典藏订单列表');
	    Tpl::assign('dcorder_list',      $dcorder_list['orderlist']);
		Tpl::assign('timesd',      $dcorder_list['timesd']);
	    Tpl::assign('filter',          $dcorder_list['filter']);
	    Tpl::assign('record_count',    $dcorder_list['record_count']);
	    Tpl::assign('page_count',      $dcorder_list['page_count']);
	    $sort_flag  = sort_flag($dcorder_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    Tpl::display('diancang_order_list.htm');
	}

	/**
     * @return 典藏订单列表排序、分页、查询
     */
    public function lists_query() {
    	admin_priv('dcorder');
		$dcorder_list = $this->get_dcorder_list();
	    Tpl::assign('dcorder_list',      $dcorder_list['orderlist']);
		Tpl::assign('timesd',      $dcorder_list['timesd']);
	    Tpl::assign('filter',          $dcorder_list['filter']);
	    Tpl::assign('record_count',    $dcorder_list['record_count']);
	    Tpl::assign('page_count',      $dcorder_list['page_count']);
	    $sort_flag  = sort_flag($dcorder_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		make_json_result(Tpl::fetch('diancang_order_list.htm'), '', 
			array('filter' => $dcorder_list['filter'], 'page_count' => $dcorder_list['page_count']));
	}

	/**
     * @return 典藏订单删除
     */
    public function remove() {
    	admin_priv('dcorder');
	    $dc_id = intval($_GET['id']);
	    $diancang_model = Model('diancang');
	    $back_model  = Model('diancang_back');
	    $order_model = Model('diancang_order');
	    $back_model  = Model('diancang_back');
	    /*获取典藏订单信息*/
	    $where = "capital_orderid = '$dc_id'";
	    $field = 'pay_status,shipping_status,order_status,userpoint_id,pay_name,order_sn';
	    $order_pay_status = $order_model->select_capital_order_info($field,$where);
	    /*获取back_id*/
	    $back_id = $back_model->select_capital_back_info('back_id',$where);
	    if ($order_pay_status['pay_status'] == 0 && $order_pay_status['shipping_status'] == 0) {
	        $wheres = 'id = '.$order_pay_status['userpoint_id'];
	        $diancang_model->update_capital_userpoint(array('types'=>0),$wheres);
	    }
	    /*删除*/
	    $order_model->delete_capital_order($where);
	    $back_model->delete_capital_back($where);
	    Model('diancang_ordergoods')->delete_capital_ordergoods($where);
	    if (empty($back_id)) {
	        $order_sn = '订单号为：' . $order_pay_status['order_sn'] . ' 订单ID：' . $dc_id;
	    } else {
	        $order_sn = '订单号为：' . $order_pay_status['order_sn'] . ' 订单ID：' . $dc_id . ' back_id:' . $back_id;
	    }
	    admin_log($order_sn, 'remove', 'capital_order');
	    $url = 'index.php?act=dcorder&op=lists_query';
	    ecs_header("Location: $url\n");
	    exit;
    }

    /**
     * @return 典藏订单编辑页面
     */
    public function edit() {
    	/* 检查权限 */
	    admin_priv('dcorder');

	    /* 取得参数 order_id */
	    $dcorder_id = isset($_GET['dcorder_id']) ? intval($_GET['dcorder_id']) : 0;
	    Tpl::assign('dcorder_id', $dcorder_id);

	    /* 取得参数step，确定所要编辑的内容 */
	    $step_list = array('goods', 'consignee', 'shipping', 'payment', 'invoice_no', 'money');
	    $step = isset($_GET['step']) && in_array($_GET['step'], $step_list) ? $_GET['step'] : 'user';
	    Tpl::assign('step', $step);
	    /* 取得订单信息 */
	    if ($dcorder_id > 0) {
	        $dcorder = $this->dcorder_info_order($dcorder_id);
	        $dcorder['tel1']  = $dcorder['tel'];
	        $dcorder['tel'] = jiaMiPhone($dcorder['tel']);
	        Tpl::assign('dcorder', $dcorder);
	    }

	    /*修改收货人信息*/
	    if ('consignee' == $step) {
	        Tpl::assign('ur_here','修改收货人信息');
	        /* 取得收货地址列表 */
	        if ($dcorder['user_id'] > 0) {
	        	$address_list = Model('user_address')->get_user_address_list('*','user_id='.$dcorder['user_id']);
	            Tpl::assign('address_list',$address_list);
				$address_id = isset($_REQUEST['address_id']) ? intval($_REQUEST['address_id']) : 0;
	            if ($address_id > 0) {
	                $address = Model('user_address')->select_user_address_info('*',"address_id = '$address_id'");
	                if ($address) {
	                    $dcorder['consignee']     = $address['consignee'];
	                    $dcorder['country']       = $address['country'];
	                    $dcorder['province']      = $address['province'];
	                    $dcorder['city']          = $address['city'];
	                    $dcorder['district']      = $address['district'];
	                    $dcorder['address']       = $address['address'];
	                    $dcorder['tel']           = $address['tel'];
	                    Tpl::assign('dcorder', $dcorder);
	                }
	            }
	        }
			/* 取得国家 */
			Tpl::assign('country_list', get_regions());
			if ($dcorder['country'] > 0) {
				/* 取得省份 */
				Tpl::assign('province_list', get_regions(1, $dcorder['country']));
				if ($dcorder['province'] > 0) {
					/* 取得城市 */
					Tpl::assign('city_list', get_regions(2, $dcorder['province']));
					if ($dcorder['city'] > 0) {
						/* 取得区域 */
						Tpl::assign('district_list', get_regions(3, $dcorder['city']));
					}
				}
			}

	    }
	    /* 显示模板 */
	    Tpl::display('diancang_order_step.htm');
    }

    /**
     * @return 典藏订单编辑数据提交
     */
    public function update() {
    	/* 检查权限 */
	    admin_priv('dcorder');

	    /* 取得参数 step */
	    $step_list = array('user', 'edit_goods', 'add_goods', 'goods', 'consignee', 'shipping', 'payment', 'other', 'money', 'invoice_no');
	    $step = isset($_REQUEST['step']) && in_array($_REQUEST['step'], $step_list) ? $_REQUEST['step'] : 'user';

	    /* 取得参数 dcorder_id */
	    $dcorder_id = isset($_REQUEST['dcorder_id']) ? intval($_REQUEST['dcorder_id']) : 0;
	    if ($dcorder_id > 0) {
	        $old_dcorder = $this->dcorder_info_order($dcorder_id);
	    }
	    
	    if ('consignee' == $step) {
	    	/* 编辑收货人信息 */
	        /* 保存订单 */
	        $order = $_POST;
	        unset($order['finish']);
	        /*修改订单信息*/
	        Model('diancang_order')->update_capital_order($order, "capital_orderid = '$dcorder_id'");
	        /* todo 记录日志 */
	        $sn = $old_dcorder['order_sn'].'收货人信息';
	        admin_log($sn, 'edit', 'capital_order');
			if (isset($_POST['finish'])) {
	            ecs_header("Location: index.php?act=dcorder&op=info&dcorder_id=" . $dcorder_id . "\n");
	            exit;
	        }
	    } elseif ('invoice_no' == $step) {
	    	/* 编辑发货后的配送方式和发货单号 */
	        /* 保存订单 */
	        $invoice_no     = trim($_POST['invoice_no']);
			$shipping_time  = gmtime();
	        $order = array('invoice_no'=> $invoice_no,'shipping_time'=>$shipping_time );
	        /*修改订单信息*/
	        Model('diancang_order')->update_capital_order($order, "capital_orderid = '$dcorder_id'");
	        /* todo 记录日志 */
	        $sn = $old_dcorder['order_sn'].'发货单号';
	        admin_log($sn, 'edit', 'capital_order');

	        if (isset($_POST['finish'])) {
	            ecs_header("Location: diancang_order.php?act=dcorder_info&dcorder_id=" . $dcorder_id . "\n");
	            exit;
	        }
	    }
    }

     /**
     * @return 典藏订单操作
     */
    public function operate() {
    	/* 检查权限 */
	    admin_priv('dcorder');
	    /*取得管理员信息*/
	    $sess = $this->admin_info;
	    $admin = $sess['user_name'];
	    /* 取得订单信息*/
	    if(isset($_REQUEST['dcorder_id'])){
	        $dcorder_id= $_REQUEST['dcorder_id'];
			$dcorder = $this->dcorder_info_order($dcorder_id);
	    }else{
			die('order does not exist');
		}
		/*初始化订单完成状态*/
		$order_finish = false;
	    $action_note  = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';
		$userid = $dcorder['user_id'];
		/*取得订单用户信息*/
		$field = 'user_name,alias,mobile_phone';	
		$usership =  Model('users')->select_users_info($field,"user_id = '$userid'");
		$user_names = empty($usership['alias']) ? $usership['user_name'] : $usership['alias'];
		$pay_timeds = local_date('Y-m-d H:i',gmtime());
		/*操作备注存在则允许操作*/
		if(!empty($action_note)){
			if (isset($_POST['confirm'])){
	            /* 确认 */
				$arr['order_status']     = OS_CONFIRMED;/*确认订单1*/
				$arr['confirm_time']     = gmtime();
				if($dcorder['order_status'] == 4){
					/* 未添加备注 */
					$links[] = array('text' => '返回订单详情', 'href' => 'index.php?act=dcorder&op=info&dcorder_id='.$dcorder_id);
					$links[] = array('text' => '去退款列表', 'href' => 'index.php?act=dcorder&op=back_lists');
					showMessage('退款产品请到退款订单列表操作', 1, $links);
				}else{
					$res = $this->update_dcorder($dcorder_id, $arr);
					$this->order_dcaction($dcorder_id,OS_CONFIRMED,$dcorder['shipping_status'], $dcorder['pay_status'], $action_note, null,$dcorder['pay_name'],'商家');
				}
			}elseif(isset($_POST['to_shipping'])){
	            /*一键发货*/
				$invoice_no = empty($_REQUEST['invoice_no']) ? '' : trim($_REQUEST['invoice_no']);  //快递单号
				if(!empty($invoice_no)){
					$invoice_nos = $invoice_no;
				}else{
					$invoice_nos = '无';
				}
				$dcorder_id = intval(trim($dcorder_id));
				$action_note = trim($action_note);
				/* 标记订单为已确认 "发货中" */
				/* 更新发货时间 */
				$arr['shipping_status']     = SS_SHIPPED;//已发货1
				$arr['shipping_time']    = gmtime();
				$arr['invoice_no']    = $invoice_no;
				$res = $this->update_dcorder($dcorder_id, $arr);

				/* 如果订单用户不为空，计算积分，并发给用户；发红包 */
				if ($res > 0 && $dcorder['user_id']>0) {
					/* 修改典藏用户等级积分 */
					$jifen = $this->update_dcuser($dcorder['user_id'],$dcorder['goods_amount']);
					$order_finish = true;
				}
				/* 记录log */
				$this->order_dcaction($dcorder_id,$dcorder['order_status'],1,$dcorder['pay_status'], $action_note, null,$dcorder['pay_name'],'商家');
				$message['message_title'] = '订单发货提醒';
				$message['message_body'] = '亲爱的'.$user_names.'用户。你好！您的订单'.$dcorder['order_sn'].'已于'.$pay_timeds.'按照您的要求方式给您发货了。发货单号是：'.$invoice_nos;
				$content = [
		            'title'=>'淘玉发货',
		            'body'=>'您的订单:'.$dcorder['order_sn'].', 已经发货,快递单号：'.$invoice_nos
		        ];
				$magstitle = "订单已发货";
				$magsmag = "![订单已发货](".$dcorder['imgurl'].")<br/>操作人员".$admin.":<订单已发货><br/>产品编号：".$dcorder['goods_sn'];
			}elseif(isset($_POST['cancel'])){
				/*取消订单*/
				$arr['order_status']     = OS_CANCELED;
				$res = $this->update_dcorder($dcorder_id, $arr);
				$this->order_dcaction($dcorder_id,OS_CANCELED,$dcorder['shipping_status'], $dcorder['pay_status'], $action_note, null,$dcorder['pay_name'],'商家');
				$order_finish = true;
				$message['message_title'] = '订单取消提醒';
				$message['message_body'] = '亲爱的'.$user_names.'用户。你好！您的订单'.$dcorder['order_sn'].'已于'.$pay_timeds.'管理员设置为订单取消';
				$content = [
		            'title'=>'订单取消',
		            'body'=>'您的订单:'.$dcorder['order_sn'].', 已经订单取消'
		        ];
				$magstitle = "订单取消";
				$magsmag = "![订单取消](".$dcorder['imgurl'].")<br/>操作人员".$admin.":<订单取消><br/>产品编号：".$dcorder['goods_sn'];
				$updats = Model('goods')->update_goods_setInc('goods_id = '.$dcorder['goods_id'],array('goods_number'=>1));
	            $updats = Model('diancang')->update_capital_goods(array('stats_buy'=>1),'capitalid = '.$dcorder['dcgoods_id']);
			}elseif(isset($_POST['invalid'])){
				/*设置订单无效*/
				$arr['order_status']     = OS_INVALID;
				$res = $this->update_dcorder($dcorder_id, $arr);
				$this->order_dcaction($dcorder_id,OS_CANCELED,$dcorder['shipping_status'], $dcorder['pay_status'], $action_note, null,$dcorder['pay_name'],'商家');
				$order_finish = true;
				$message['message_title'] = '订单设置无效提醒';
				$message['message_body'] = '亲爱的'.$user_names.'用户。你好！您的订单'.$dcorder['order_sn'].'已于'.$pay_timeds.'管理员设置为无效订单';
				$content = [
		            'title'=>'订单设置无效',
		            'body'=>'您的订单:'.$dcorder['order_sn'].', 订单设置无效'
		        ];
				$magstitle = "订单设置无效";
				$magsmag = "![订单设置无效](".$dcorder['imgurl'].")<br/>操作人员".$admin.":<订单设置无效><br/>产品编号：".$dcorder['goods_sn'];
			}elseif(isset($_POST['butui'])){
				/*设置为不退货*/
				$arr['buy_sent']     = 1;
				$res = $this->update_dcorder($dcorder_id, $arr);
				$this->order_dcaction($dcorder_id,$dcorder['order_status'],$dcorder['shipping_status'],$dcorder['pay_status'],$action_note, null,$dcorder['pay_name'],'商家');
				$order_finish = false;
			}elseif(isset($_POST['unpay'])){
				/*设置未付款*/
				$arr['pay_status']     = 0;
				$res = $this->update_dcorder($dcorder_id, $arr);
				$this->order_dcaction($dcorder_id,$dcorder['order_status'],$dcorder['shipping_status'],0,$action_note, null,$dcorder['pay_name'],'商家');
				$order_finish = false;
			}elseif(isset($_POST['unship'])){
				/*设置未发货*/
				$arr['shipping_status']     = 0;
				$res = $this->update_dcorder($dcorder_id, $arr);
				$this->order_dcaction($dcorder_id,$dcorder['order_status'],0,$dcorder['pay_status'],$action_note, null,$dcorder['pay_name'],'商家');
				$order_finish = false;
			}elseif(isset($_POST['remove'])){
				$order_finish = false;
				/*获取典藏订单信息*/
			    $where = "capital_orderid = '$dcorder_id''";
			    $field = 'pay_status,shipping_status,order_status,userpoint_id,pay_name,order_sn';
			    $order_pay_status = $order_model->select_capital_order_info($field,$where);
			    /*获取back_id*/
			    $back_id = $back_model->select_capital_back_info('back_id',$where);
			    if ($order_pay_status['pay_status'] == 0 && $order_pay_status['shipping_status'] == 0) {
			        $wheres = 'id = '.$order_pay_status['userpoint_id'];
			        $diancang_model->update_capital_userpoint(array('types'=>0),$wheres);
			    }
			    /*删除*/
			    Model('diancang_order')->delete_capital_order($where);
			    Model('diancang_back')->delete_capital_back($where);
			    Model('diancang_ordergoods')->delete_capital_ordergoods($where);
			    if (empty($back_id)) {
			        $order_sn = '订单号为：' . $order_pay_status['order_sn'] . ' 订单ID：' . $dc_id;
			    } else {
			        $order_sn = '订单号为：' . $order_pay_status['order_sn'] . ' 订单ID：' . $dc_id . ' back_id:' . $back_id;
			    }
				admin_log($order_sn, 'remove', 'capital_order');
			}elseif(isset($_POST['pay'])){
				$field = 'capital_order.*,capital_ordergoods.goods_sn,capital_ordergoods.dcgoods_id,capital_ordergoods.goods_number,capital_ordergoods.goods_image,capital_ordergoods.goods_name,capital_ordergoods.dc_goods_price,capital_ordergoods.yue_fen,capital_ordergoods.bili_price';
				$where = "capital_order.capital_orderid = '$dcorder_id'";
				$order_intos = Model('diancang_order')->get_capital_order_ordergoods_list($field,$where);
				$order_into = $order_intos[0];
				$pay_times = gmtime();
				$pay_price = $order_into['order_amount'] - $order_into['surplus'];
				$yue_times = $order_into['yue_fen']*30*24*60*60;
				$end_time = $pay_times+$yue_times;
				$field_order['pay_status']      = 1;
				$field_order['pay_time']        = $pay_times;
				$field_order['pay_name']        = '线下转账';
				$field_order['pay_id']          = 9;
				$field_order['money_paid']      = $pay_price;
				$field_order['third_pay_id']    = 0;
				$field_order['end_time']  = $end_time;
				$edof = $this->update_dcorder($dcorder_id, $field_order);
				if(!empty($edof)){
					$mobiles = $usership['mobile_phone'];
					if(!empty($usership['alias'])){
						$usernamssd = $usership['alias'];
						$username = $usership['alias'];
					}else{
						$usernamssd = '无昵称';
						$username = $usership['user_name'];
					}
					$this->order_dcaction($dcorder_id,$dcorder['order_status'],$dcorder['shipping_status'],1,$action_note, null,'线下转账','商家');
					$order_finish = true;
					$message['message_title'] = '订单设置支付完成提醒';
					$message['message_body'] = '亲爱的'.$usernamssd.'用户。你好！您的订单'.$dcorder['order_sn'].'已于'.$pay_times.'管理员设置为已付款';
					$content = [
		            	'title'=>'订单设置已付款',
		            	'body'=>'您的订单:'.$dcorder['order_sn'].', 订单设置已付款'
		        	];
					$magstitle = "订单设置已付款";
					$magsmag = "![订单设置已付款](".$dcorder['imgurl'].")<br/>操作人员".$admin.":<订单设置已付款><br/>产品编号：".$dcorder['goods_sn'];
				}else{
					$links[] = array('text' => '返回典藏订单详情', 'href' => 'index.php?act=dcorder&op=info&dcorder_id='.$dcorder_id);
					showMessage('程序有错误无法点击支付成功',$links);
				}
			}
		}else{
			/* 未添加备注 */
			$links[] = array('text' => '返回典藏订单详情', 'href' => 'index.php?act=dcorder&op=info&dcorder_id='.$dcorder_id);
			showMessage('必须添加操作备注',$links);
		}

		/*操作完成*/
		/* 如果当前订单已经全部发货 */
	    if ($order_finish) {
	        /* 发推送通知*/
	        $cfg = C('send_ship_email');
	        if ($cfg == '1'){
				$message['to_member_id'] = ','.$userid.',';
				$message['message_time'] = gmtime();
				$message['message_type'] = 1;
				$message['tuisong_type'] = 1;
				$messageid = Model('message')->insert_message($message);
				if(!empty($messageid)){
                    /*提交推送 查看是否符合条件推送*/
                    $platform = array("android", "ios");
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $userid , $content,'',$platform,'');
                    }
				}
				/*发送钉钉*/
				$result =  send_ding_msg($magstitle,$magsmag,'qita');
	        }
	        /* 如果需要，发短信 */
	        if (C('sms_order_shipped') == '1' && isset($_POST['to_shipping'])) {
				$userid = $dcorder['user_id'];
                $wheres = "user_id = '$userid'";
                $userintos = Model('users')->select_users_info('user_name,mobile_phone',$wheres);
				$user_names = $userintos['user_name'];
				$mobile_phones = $userintos['mobile_phone'];
				if(!empty($mobile_phones)){
					$param = array();
					$param['consignee'] = $dcorder['consignee'];
					$param['site_name']	= '淘玉商城';
                    if (!empty($invoice_no)) {
                        $invoice_nos = $invoice_no;
                    } else {
                        $invoice_nos = '无';
                    }
                    $param['invoice_no'] = $invoice_nos;
                    $param['address'] = $dcorder['address'];
                    $result = send_sms_msg($mobile_phones,'order_send',$param);
				}
	        }
	    }

	    /* 清除缓存 */
	    clear_cache_files();

	    /* 操作成功 */
	    $links[] = array('text' => '返回典藏订单详情', 'href' => 'index.php?act=dcorder&op=info&dcorder_id='.$dcorder_id);
	    $links[] = array('text' => '返回典藏订单列表', 'href' => 'index.php?act=dcorder&op=lists');
	    showMessage(L('act_ok'),$links);
    }

	/**
     * @return 订单列表手动发送代金券-界面
     */
    public function dcorder_send_point() {
    	admin_priv('sent_point');
	    $dc_order_id = $_REQUEST['dcorder_id'];
	    $user_id = $_REQUEST['user_id'];
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcorder&op=lists', 'text' => '返回订单列表'));
	    Tpl::assign('ur_here', '代金卷发送2');
	    /*获取手动发送的点券列表*/
	    $point_list = Model('diancang')->get_capital_point_list('*',"send_start = 1");
	    /*获取用户信息*/
	    $user = $this->dcuser_orderinfo($user_id);
	    Tpl::assign('point_list', $point_list);
	    Tpl::assign('alias', $user['alias']);
	    Tpl::assign('user_id', $user_id);
	    Tpl::assign('capital_userid', $user['capital_userid']);
	    Tpl::assign('dc_order_id', $dc_order_id);
	    Tpl::assign('insert_or_update', 'dc_send_point');
	    Tpl::display('diancang_order_sent_point.htm');
    }

    /**
     * @return 订单列表手动发送代金券-数据处理
     */
    public function dc_send_point() {
    	admin_priv('sent_point');
	    $point_id = $_REQUEST['point'];
	    $capital_userid = $_REQUEST['capital_userid'];
	    $dc_order_id = $_REQUEST['dc_order_id'];
	    $user_id = $_REQUEST['user_id'];
	    if (empty($point_id) || empty($capital_userid)) {
	        $link = array('text' => '返回代金券发送界面', 'href' => 'index.php?act=dcorder&op=dcorder_send_point&dcorder_id=' . $dcorder_id . '&user_id=' . $user_id);
        	showMessage('操作失败', $link);
	    }

	    /*优惠券信息*/
	    $point_info = Model('diancang')->select_capital_point_info('*',"point_id = $point_id");
	    if(!empty($point_info)){
	    	$point_info['end_time'] = gmtime() + ($point_info['valid_time'] * 24 * 3600);
	    }	    
	    if ($point_info) {
	        /*插入用户优惠券表*/
	        $data = array(
	            'capital_userid' => $capital_userid,
	            'point_id' => $point_id,
	            'stater_time' => gmtime(),
	            'end_time' => $point_info['end_time'],
	            'types' => 0
	        );

	        /*插入用户代金券表*/
	        Model('diancang')->insert_capital_userpoint($data);
	        /*修改订单信息*/
	        Model('diancang_order')->update_capital_order("capital_orderid = '$dc_order_id'", ['send_point' => 1]);
	        $link = array('text' => '返回典藏订单列表', 'href' => 'index.php?act=dcorder&op=lists');
        	showMessage(L('act_ok'), $link);
	    } else {
	        $link = array('text' => '返回代金券发送界面', 'href' => 'index.php?act=dcorder&op=dcorder_send_point&dcorder_id=' . $dcorder_id . '&user_id=' . $user_id);
        	showMessage('获取优惠券信息错误', $link);
	    }
    }

	/**
     * @return 典藏订单详情
     */
    public function info() {
    	/* 根据订单id或订单号查询订单信息 */
	    if (isset($_REQUEST['dcorder_id']) && !empty($_REQUEST['dcorder_id'])) {
	        $dcorder_id = intval($_REQUEST['dcorder_id']);
	        $dcorder = $this->dcorder_info_order($dcorder_id);
	    } elseif (isset($_REQUEST['order_sn'])) {
	        $order_sn = trim($_REQUEST['order_sn']);
	        $dcorder = $this->dcorder_info_order(0, $order_sn);
	    } else {
	        /* 如果参数不存在，退出 */
	        die('invalid parameter');
	    }

	    /* 如果订单不存在，退出 */
	    if (empty($dcorder)) {
	        die('order does not exist');
	    }
	    /* 根据订单是否完成检查权限 */
	    admin_priv('dcorder');
	    $dc_order = Model('diancang_order');
	    /* 取得上一个、下一个订单号 */
	    $prev_id = $dc_order->select_capital_order_info('MAX(capital_orderid) as prev_id','capital_orderid < '.$dcorder['capital_orderid']);
	    Tpl::assign('prev_id', $prev_id['prev_id']);
	    $next_id = $dc_order->select_capital_order_info('MIN(capital_orderid) as next_id','capital_orderid > '.$dcorder['capital_orderid']);
	    Tpl::assign('next_id', $next_id['next_id']);
	    /* 取得用户名 */
	    if ($dcorder['user_id'] > 0) {
	        $user = $this->dcuser_orderinfo($dcorder['user_id']);
	        $dcorder['user_name'] = $user;
	        Tpl::assign('user', $user);
	        $dcorder['goods_list'] = $this->dcgoods_orderinfo($dcorder['user_id']);
	    }

	    /* 取得区域名 */
	    $dcorder['region'] = get_province_city($dcorder['province'],$dcorder['city'],$dcorder['district']);

	    /* 其他处理 */
	    $dcorder['order_time'] = local_date(C('time_format'), $dcorder['add_time']);
	    $dcorder['pay_time'] = $dcorder['pay_time'] > 0 ?
	        local_date(C('time_format'), $dcorder['pay_time']) : L('ps')[PS_UNPAYED];
	    $dcorder['shipping_time'] = $dcorder['shipping_time'] > 0 ?
	        local_date(C('time_format'), $dcorder['shipping_time']) : L('ss')[SS_UNSHIPPED];
	    if($dcorder['pay_status']==0){
	    	$pay_statu = L('ps')[PS_UNPAYED];
	    }elseif($dcorder['pay_status']==1){
	    	$pay_statu = L('ps')[PS_PAYED];
	    }elseif($dcorder['pay_status']==s){
	    	$pay_statu = L('ps')[PS_PAYING];
	    }
	    $dcorder['status'] = L('os')[$dcorder['order_status']] . ',' .$pay_statu . ',' . L('ss')[$dcorder['shipping_status']];
	    $dcorder['invoice_no'] = $dcorder['shipping_status'] == SS_UNSHIPPED || $dcorder['shipping_status'] == SS_PREPARING ? L('ss')[SS_UNSHIPPED] : $dcorder['invoice_no'];
	    /* 此订单的发货备注(此订单的最后一条操作记录) */
	    $dcorder['invoice_note'] = $dc_order->select_capital_orderaction_info('action_note', 'capital_orderid = '.$dcorder['capital_orderid'],'log_time DESC')['action_note'];
	    /*电话加密*/
	    $dcorder['tel1'] = $dcorder['tel'];
	    $dcorder['tel'] = jiaMiPhone($dcorder['tel']);
	    Tpl::assign('dcorder', $dcorder);

	    /* 取得订单商品 */
	    $goods_list = array();
	    $goods_into = $dc_order->get_capital_order_ordergoods_list('capital_order.*,capital_ordergoods.*','capital_order.capital_orderid = '.$dcorder['capital_orderid'])[0];
	    if (!empty($goods_into)) {
	        $goods_into['imgurls'] = get_imgurl_oss($goods_into['goods_image'],30,30);
	        if ($goods_into['order_status'] == 4) {
	            $wher = "capital_orderid = " . $dcorder['capital_orderid'] . " AND status_back < 6 ";
	            $back_info = Model('diancang_back')->select_capital_back_info('*',$wher);
	            if (count($back_info['back_id']) > 0) {
	                switch ($back_info['status_back']) {
	                	/*0:审核通过,1:收到寄回商品,2:换回商品已寄出,3:完成,4:退款/退货,5:审核中,6:申请被拒绝,7:管理员取消,8:用户自己取消,9.管理员终止退货流程*/
	                    case '0' :
	                        $sb = "退货审核通过";
	                        break;
	                    case '1' :
	                        $sb = "收到寄回商品";
	                        break;
	                    case '2' :
	                        $sb = "换回商品已寄出";
	                        break;
	                    case '3' :
	                        $sb = "已完成退货";
	                        break;
	                    case '5' :
	                        $sb = "退货审核中";
	                        break;
	                    case '6' :
	                        $sb = "退货申请被拒绝";
	                        break;
	                    case '7' :
	                        $sb = "管理员取消退货";
	                        break;
	                    default :
	                        $sb = "正在";
	                        break;
	                }
	                $shouhou = $sb;

	            } else {
	                $shouhou = "正常";
	            }
	            Tpl::assign('shenhe', $back_info['status_back']);
	        } else {
	            $shouhou = "正常";
	        }
	        $goods_into['shouhou'] = $shouhou;
	        $dcorder['order_goods_xiang'] = $goods_into;

	    } else {
	        $dcorder['order_goods_xiang'] = '';
	    }
	    Tpl::assign('goods_intos', $dcorder['order_goods_xiang']);
	    /* 取得能执行的操作列表 */
	    $operable_list = $this->operable_statrlist($dcorder);
	    Tpl::assign('operable_list', $operable_list);
	    /* 取得订单操作记录 */
	    $act_list = array();
	    $res = $dc_order->get_capital_orderaction_list('*','capital_orderid = '.$dcorder['capital_orderid'],'log_time DESC,action_id DESC');
	    if(!empty($res)){
	    	foreach ($res as $key => $row) {
	    		if($row['pay_status']==0){
			    	$pay_statu = L('ps')[PS_UNPAYED];
			    }elseif($row['pay_status']==1){
			    	$pay_statu = L('ps')[PS_PAYED];
			    }elseif($row['pay_status']==s){
			    	$pay_statu = L('ps')[PS_PAYING];
			    }
		     	$row['order_status'] = L('os')[$row['order_status']];
		        $row['pay_status'] = $pay_statu;
		        $row['shipping_status'] = L('ss')[$row['shipping_status']];
		        $row['action_time'] = local_date(C('time_format'), $row['log_time']);
		        $act_list[] = $row;
	        }	  
	    }
	    
	    Tpl::assign('action_list', $act_list);
	    /* 模板赋值 */
	    Tpl::assign('ur_here', L('order_info'));
	    Tpl::assign('action_link', array('text'=>'典藏订单列表','href' => 'index.php?act=dcorder&op=lists'));
	    /* 显示模板 */
	    Tpl::display('diancang_order_info.htm');
    }

    /**
     * @return 检查是否有权限查看订单电话
     */
    public function check(){
        $res  = check_look_phone("check_look_phone");
        if($res == 1 ){
            $data['status'] = 1 ;
        }else{
            $data['status'] = -1;
        }
        echo json_encode($data);
    }
	
    /**
     * @return 获取典藏订单列表  
	 * @return array
	 */
    private function get_dcorder_list() {
    	$result = get_filter();
    	$model = Model('diancang_order');
    	if ($result === false){
    		/*过滤参数*/
    		$filter = array();
    		$filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
			$filter['consignee']= empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);
			$filter['order_status'] = $_REQUEST['order_status'];
			$filter['shipping_status'] = $_REQUEST['shipping_status'];
			$filter['pay_status'] = $_REQUEST['pay_status'];
			$filter['tian7'] = $_REQUEST['tian7'];
			$filter['daoqi'] = $_REQUEST['daoqi'];
			$filter['benyue_daoqi'] = $_REQUEST['benyue_daoqi'];
			$filter['nots_tui'] = $_REQUEST['nots_tui'];
			$filter['out_sell'] = $_REQUEST['out_sell'];
			$filter['total_sell'] = $_REQUEST['total_sell'];
			$filter['today_tou'] = $_REQUEST['today_tou'];
			$filter['benyue_tou'] = $_REQUEST['benyue_tou'];
			$filter['benzhou_tou'] = $_REQUEST['benzhou_tou'];
			$filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'capital_orderid' : trim($_REQUEST['sort_by']);
		    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);           
	 		
	 		/*搜索条件*/
	 		$wheresd = " WHERE user_id > 0 ";
			$dangqian_time = gmtime();
			$t_strat = time_adsu($dangqian_time,1,8);/*8天后的00:00:00*/
			if (!empty($filter['order_sn'])) {
				$wheresd .= " AND order_sn like '%". $filter['order_sn'] ."%'  ";
			}
			
			if (!empty($filter['consignee'])) {
				$wheresd .= " AND consignee like '%". $filter['consignee'] ."%'  ";
			}

			if ($filter['order_status'] >= 0 && $filter['order_status'] != null) {
				$wheresd .= " AND order_status = ".$filter['order_status'];
			}

			if (!empty($filter['order_status']) && $filter['shipping_status'] >= 0) {
				$wheresd .= " AND shipping_status = '".$filter['shipping_status']."'";
			}

			if (!empty($filter['order_status']) && $filter['pay_status'] >= 0) {
				$wheresd .= " AND pay_status = '".$filter['pay_status']."' ";
			}
			/*总卖出*/
			if (!empty($filter['total_sell'])) {
				$wheresd .= " AND pay_status = 1 AND order_status IN (1,4,5) ";
			}
			/*总投资（在外）*/
			if (!empty($filter['out_sell']))
			{
				$wheresd .= " AND pay_status = 1 AND order_status IN (1,5) AND back_goods = 0 ";
			}
			/*七天内到期*/
			if(!empty($filter['tian7'])){
				$wheresd .= " AND pay_status = 1 AND end_time < " .$t_strat." AND end_time > ".$dangqian_time;
			}
			/*到期未退*/
			if(!empty($filter['daoqi'])){
				$wheresd .= " AND pay_status = 1 AND end_time <= " . $dangqian_time . " AND end_time < ".$t_strat;
			}
			/*到期不退*/
			if(!empty($filter['nots_tui'])){
				$wheresd .= " AND pay_status = 1 AND end_time < ".time_adsu($dangqian_time,2,7);
			}

			/*本月到期*/
			if(!empty($filter['benyue_daoqi'])){
				$wheresd .= " AND pay_status = 1 AND order_status IN (1,5) AND end_time < ".get_menylast($dangqian_time)." AND end_time >= ".get_menyone($dangqian_time);
			}

			/*今日投资*/
			if(!empty($filter['today_tou'])){
				$wheresd .= " AND pay_status = 1 AND order_status IN (1,5) AND pay_time >= ".mktime(0,0,0,date('m'),date('d'),date('Y'))." AND pay_time <".strtotime(date('Y-m-d',strtotime('+1 day')));
			}
			/*本周投资*/
			$zhou_start =  strtotime(date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"))));
			$zhou_end = strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))));
			if(!empty($filter['benzhou_tou'])){
				$wheresd .= " AND pay_status = 1 AND order_status IN (1,5) AND pay_time >= ".$zhou_start." AND pay_time <".$zhou_end;
			}
			/*本月投资*/
			if(!empty($filter['benyue_tou'])){
				$wheresd .= " AND pay_status = 1 AND order_status IN (1,5) AND pay_time >= ".get_menyone($dangqian_time)." AND pay_time < ".get_menylast($dangqian_time);
			}

		    $filter['record_count'] = $model->get_capital_order_count($wheresd);
		    $filter = page_and_size($filter);	
			$sql = "SELECT co.*,cog.goods_sn,cog.goods_image,cog.yue_fen,cog.goods_bili FROM " . Model()->tablename('capital_order') ." AS co ".
				" LEFT JOIN " . Model()->tablename('capital_ordergoods') . " AS cog ON co.capital_orderid = cog.capital_orderid ".$wheresd .	
				" ORDER by ".$filter['sort_by'].' '.$filter['sort_order'];
			set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
    	$row = get_all_page($sql, $filter['page_size'], $filter['start']);

    	/*格式化数据*/
    	$tian_nums = 0;
		$tui_nums = 0;
		$not_nums = 0;
		$order_lists = '';
		if(!empty($row)){
			foreach($row as $key=>$value){
				$order_snds = $value['order_sn'];
				$row[$key]['imgurls'] = get_imgurl_oss($value['goods_image'],30,30);
				$row[$key]['tian_time'] = local_date('Y-m-d H:i',$value['add_time']);
				$row[$key]['jie_time'] = local_date('Y-m-d H:i',$value['end_time']);
				/*优惠途径*/
				if($value['youpic_id'] == 2){
					$row[$key]['youpic_name'] = '优惠劵';
				}else if($value['youpic_id'] == 1){
					$row[$key]['youpic_name'] = '活动';
				}else{
					$row[$key]['youpic_name'] = '无';
				}
				/*操作选项*/
				if ($value['order_status'] == 2 || $value['order_status'] == 3) {
		            /* 如果该订单为无效或取消则显示删除链接 */
		            $row[$key]['can_remove'] = 1;
		        }elseif($value['order_status'] == 4 && $value['back_goods']==2 && $value['look_goods']==1){
					/* 如果该订单为看货订单并且已经完成退货则显示删除链接 */
					$row[$key]['can_remove'] = 1;
				} else {
		            $row[$key]['can_remove'] = 0;
		        }
				/*0:审核通过,1:收到寄回商品,2:换回商品已寄出,3:完成,4:退款/退货,5:审核中,6:申请被拒绝,7:管理员取消,8:用户自己取消,9.管理员终止退货流程*/
				if($value['back_goods'] == 1){
					$back_info = Model('diancang_back')->select_capital_back_info('*',"order_sn = '$order_snds'");
					if (!empty($back_info)) {
						switch ($back_info['status_refund']) {
							case 0 :
								$back_type = "未退款";
								break;
							case 1 :
								$back_type = "已退款";
								break;
							default :
								break;
						}

						switch ($back_info['status_back']) {
							case 0 :
								$status_back = "已通过申请";
								break;
							case 1 :
								$status_back = "已收到换回商品";
								break;
							case 2 :
								$status_back = "换出商品已寄回";
								break;
							case 3 :
								$status_back = "已完成";
								break;
							case 4 :
								$status_back = "还货";
								break;
							case 5 :
								$status_back = "申请中";
								break;
							case 6 :
								$status_back = "已拒绝申请";
								break;
							case 7 :
								$status_back = "系统自动取消";
								break;
							case 8 :
								$status_back = "用户自行取消";
								break;
							case 9 :
								$status_back = "管理员终止";
								break;
							default :
								break;
						}
						$row[$key]['back_name'] = '还货';
						$row[$key]['tuihuan']['status_back'] = $status_back;
						$row[$key]['tuihuan']['back_type'] = $back_type;
						
					}
				}

				if(!empty($filter['daoqi'])){
					if($value['end_time']<=$dangqian_time && $dangqian_time<$not_strat){
						$order_lists[$key] = $row[$key];
					}
				}else{
					if(!empty($filter['nots_tui'])){
						if($not_strat < $dangqian_time){
							$order_lists[$key] = $row[$key];
						}
					}else{
						if(!empty($filter['tian7'])){
							if($t_strat > $value['end_time'] && $value['end_time'] > $dangqian_time){
								$order_lists[$key] = $row[$key];
							}
						}else{
							$order_lists[$key] = $row[$key];
						}
					}
				}			
			}					
		}

		/*头部统计数据*/
		$data = $model->get_capital_order_list('*','1');
		if(!empty($data)){
			foreach ($data as $k => $v) {
				if(!empty($v['end_time'])){
					/*到期后的8天后的00:00:00*/
					$not_strat = time_adsu($v['end_time'],1,8);
					/*7天到期订单个数*/
					if($t_strat > $v['end_time'] && $v['end_time'] > $dangqian_time ){
						$tian_nums = $tian_nums+1;
					}

					/*到期未退*/
					if($v['end_time']<=$dangqian_time && $dangqian_time<$not_strat){
						$tui_nums = $tui_nums+1;
					}

					/*过期不退*/
					if($not_strat < $dangqian_time ){
						$not_nums = $not_nums+1;
					}
				}
			}
			$timesd['tian_nums'] = $tian_nums; 
			$timesd['tui_nums'] = $tui_nums; 
			$timesd['not_nums'] = $not_nums;	
		}			 
		$arr = array('orderlist' => $order_lists,'timesd' => $timesd,'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	    return $arr;   	
	}

	/**
	 * @return 取得订单信息
	 * @param  int $dcorder_id 典藏订单id（如果order_id > 0 就按id查，否则按sn查）
	 * @param  string $order_sn 典藏订单号
	 * @return array   订单信息（金额都有相应格式化的字段，前缀是formated_）
	 */
	private function dcorder_info_order($dcorder_id, $order_sn = '') {
	    /* 计算订单各种费用之和的语句 */
	    $dcorder_id = intval($dcorder_id);
	    if ($dcorder_id > 0) {
	        $where = "capital_ordergoods.capital_orderid = '$dcorder_id'";
	    } else {
	        $where = "capital_ordergoods.order_sn = '$order_sn'";
	    }

	    $dcorder = Model('diancang_order')->select_capital_order_ordergoods_goods_info('capital_order.*,capital_ordergoods.*,capital_goods.goods_id',$where);
	    /* 格式化金额字段 */
	    if ($dcorder) {
	        $dcorder['formated_goods_amount'] = price_format($dcorder['goods_amount'], false);
	        $dcorder['formated_goods_youpic'] = price_format($dcorder['goods_youpic'], false);
	        $dcorder['formated_shipping_fee'] = price_format($dcorder['shipping_fee'], false);
	        $dcorder['formated_insure_fee'] = price_format($dcorder['insure_fee'], false);
	        $dcorder['formated_money_paid'] = price_format($dcorder['money_paid'], false);
	        $dcorder['formated_surplus'] = price_format($dcorder['surplus'], false);
	        $dcorder['formated_order_amount'] = price_format(abs($dcorder['order_amount']), false);
	        $dcorder['formated_add_time'] = local_date(C('time_format'), $dcorder['add_time']);
	        $dcorder['imgurl'] = get_imgurl_oss($dcorder['goods_image'], 360, 360);
	        if (!empty($dcorder['end_time'])) {
	            $dcorder['formated_end_time'] = local_date(C('time_format'), $dcorder['end_time']);
	        }

	        $fields = 'region_id, region_name';
	        $wheres = " region_id in (" . $dcorder['country'] . "," . $dcorder['province'] . "," . $dcorder['city'] . "," . $dcorder['district'] . ")";
	        $rows = Model('region')->get_regoin_list($fields,$wheres);

	        foreach ($rows as $row) {
	            $region_id = $row['region_id'];
	            $region_name = $row['region_name'];

	            if ($region_id == $dcorder['country']) {
	                $dcorder['country_name'] = $region_name;
	            } else if ($region_id == $dcorder['province']) {
	                $dcorder['province_name'] = $region_name;
	            } else if ($region_id == $dcorder['city']) {
	                $dcorder['city_name'] = $region_name;
	            } else if ($region_id == $dcorder['district']) {
	                $dcorder['district_name'] = $region_name;
	            }
	        }
	    }

	    return $dcorder;
	}

	/**
	 * @return  返回某个订单可执行的操作列表，包括权限判断
	 * @param   array $order 订单信息 order_status, shipping_status, pay_status
	 * @param   bool $is_cod 支付方式是否货到付款
	 * @return  array   可执行的操作  confirm, pay, unpay,  ship, unship, receive, cancel, invalid, return, drop
	 * 格式 array('confirm' => true, 'pay' => true)
	 */
	private function operable_statrlist($order) {
	    /* 取得订单状态、发货状态、付款状态 */
	    $os = $order['order_status'];
	    $ss = $order['shipping_status'];
	    $ps = $order['pay_status'];
	    $bs = $order['back_goods'];
	    $ls = $order['look_goods'];
	    /* 取得订单操作权限 */
	    $actions = $_SESSION['action_list'];

	    if ($actions == 'all') {
	        $priv_list = array('os' => true, 'ss' => true, 'ps' => true, 'edit' => true);
	    } else {
	        $actions = ',' . $actions . ',';
	        $priv_list = array(
	            'os' => strpos($actions, ',order_os_edit,') !== false,
	            'ss' => strpos($actions, ',order_ss_edit,') !== false,
	            'ps' => strpos($actions, ',order_ps_edit,') !== false,
	            'edit' => strpos($actions, ',order_edit,') !== false
	        );
	    }

	    /* 取得订单支付方式是否货到付款 */
	    $where = "pay_id = '".$order['pay_id']."' AND enabled = 1";
	    $payment = Model('payment')->select_payment_info('*',$where);

	    $is_cod = $payment['is_cod'] == 1;

	    /* 根据状态返回可执行操作 */
	    $list = array();
	    if (OS_UNCONFIRMED == $os) {
	    	//未确认0
	        /* 状态：未确认 => 未付款、未发货 */
	        if ($priv_list['os']) {
	            $list['confirm'] = true; // 确认
	            $list['invalid'] = true; // 无效
	            $list['cancel'] = true; // 取消
	            /* 不是货到付款 */
	            if ($priv_list['ps'] && $ps != 1) {
	                $list['pay'] = true;  // 付款
	            }

	        }
	    } elseif (OS_CONFIRMED == $os || OS_SPLITED == $os || OS_SPLITING_PART == $os) {
	    	//确认1 //已分单5 //部分分单6
	        /* 状态：已确认 */
	        if (PS_UNPAYED == $ps){
	        	//未付款0
	            /* 状态：已确认、未付款 */
	            if (SS_UNSHIPPED == $ss)//未发货0
	            {
	                /* 状态：已确认、未付款、未发货（或配货中） */
	                if ($priv_list['os']) {
	                    $list['cancel'] = true; // 取消
	                    $list['invalid'] = true; // 无效
	                }

	                /* 不是货到付款 */
	                if ($priv_list['ps']) {
	                    $list['pay'] = true; // 付款
	                }

	            } else if (SS_SHIPPED == $ss) {
	            	//SS_SHIPPED 已发货
	                /* 状态：已确认、未付款、已发货 */
	                if ($priv_list['ps']) {
	                    $list['pay'] = true; // 付款
	                }
	                if ($priv_list['ss']) {
	                    if (SS_SHIPPED == $ss) {
	                    	// 已发货1
	                        $list['receive'] = true; // 收货确认
	                    }
	                    $list['unship'] = true; // 设为未发货
	                    if ($priv_list['os']) {
	                        $list['return'] = true; // 退货
	                    }
	                }
	            } else {
	                if (2 == $ss) {
	                	// 已收货确认2
	                    $list['butui'] = true; // 收货确认
	                    $list['return'] = true; // 强制退货
	                }
	            }
	        } else {
	        	//已付款1 ps = 1 ps = 2
	            /* 状态：已确认、已付款和付款中 */
	            if (SS_UNSHIPPED == $ss) {
	            	// 未发0
	                /* 状态：已确认、已付款和付款中、未发货（配货中） => 不是货到付款 */
	                if ($priv_list['ps']) {
	                    $list['unpay'] = true; // 设为未付款
	                    if ($priv_list['os']) {
	                        $list['cancel'] = true; // 取消
	                    }
	                }
	            } else if (SS_SHIPPED == $ss) {
	            	//SS_SHIPPED 已发货
	                /* 状态：已确认、已付款和付款中、已发货或已收货 */
	                if ($priv_list['ss']) {
	                    if (SS_SHIPPED == $ss) // 已发货1
	                    {
	                        $list['receive'] = true; // 收货确认
	                    }
	                    if (!$is_cod) {
	                        $list['unship'] = true; // 设为未发货
	                    }
	                }
	                if ($priv_list['ps'] && $is_cod) {
	                    $list['unpay'] = true; // 设为未付款
	                }
	                if ($priv_list['os'] && $priv_list['ss'] && $priv_list['ps']) {
	                    $list['return'] = true; // 退货（包括退款）
	                }
	            } else {
	                if (2 == $ss) {
	                	// 已收货确认2
	                    $list['butui'] = true; // 不退货
	                    $list['return'] = true; // 强制退货
	                }
	            }
	        }
	    } elseif (OS_CANCELED == $os) {
	    	// 已取消2
	        /* 状态：取消 */
	        if ($priv_list['os']) {
	            $list['confirm'] = true;
	        }
	        if ($priv_list['edit']) {
	            $list['remove'] = true;
	        }
	    } elseif (OS_INVALID == $os) {
	    	// 无效3
	        /* 状态：无效 */
	        if ($priv_list['os']) {
	            $list['confirm'] = true;
	        }
	        if ($priv_list['edit']) {
	            $list['remove'] = true;
	        }
	    } elseif (OS_RETURNED == $os) {
	    	// 退货4
	        /* 状态：退货 */
	        if ($priv_list['os']) {
	            $list['confirm'] = true;
	        }
	    }
	    if (OS_RETURNED == $os && $bs == 2 && $ls == 1) {
	    	// 退货4
	        /* 状态：退货 */
	        if ($priv_list['os']) {
	            $list['remove'] = true;
	        }
	    }
	    /* 售后 */
	    $list['after_service'] = true;
	    return $list;
	}

	/**
	 * @return  获取典藏订单对应用户信息
	 * @param   int $user_id 用户id
	 * @return  array
	 */
	private function dcuser_orderinfo($user_id) {
	    $dangqian_time = time_adsu(gmtime(), 1, 1);//明天的00:00:00;
	    $file_type = " capital_user.*,users.user_name,users.alias,users.alias,users.headimg,users.user_money ";
	    $wheres = " capital_user.user_id = '$user_id' ";
	    $user_into = Model('diancang_users')->select_capital_user_users_info($file_type,$wheres);
	    if (!empty($user_into)) {
	        $user_into['formated_user_money'] = price_format($user_into['user_money'], false);
	        $field = 'SUM(goods_amount) AS allpic,SUM(goods_youpic) AS youpic,SUM(gyongjing_pic) AS shouyi_pic';
	        $wheres = "user_id = '$user_id' AND pay_status = 1 AND end_time > '$dangqian_time' AND order_status in (0,1,5)";
	        $order_pic = Model('diancang_order')->select_capital_order_info($field,$wheres);
	        if (!empty($order_pic)) {
	            $user_into['allpic'] = price_format($order_pic['allpic'], false);
	            $user_into['youpic'] = price_format($order_pic['youpic'], false);
	            $user_into['shouyi_pic'] = price_format($order_pic['shouyi_pic'], false);
	        } else {
	            $user_into['allpic'] = '0.00';
	            $user_into['youpic'] = '0.00';
	            $user_into['shouyi_pic'] = '0.00';
	        }
	        return $user_into;
	    } else {
	        return '';
	    }
	}

	/**
	 * @return  获取典藏订单对应用户产品信息
	 * @param   int $user_id 用户id
	 * @return  array
	 */
	private function dcgoods_orderinfo($user_id) {
	    $dangqian_time = time_adsu(gmtime(), 1, 1);//明天的00:00:00;
	    $file_type = " capital_user.*,users.user_name,users.alias,users.alias,users.headimg,users.user_money ";
	    $wheres = " capital_user.user_id = '$user_id' ";
	    $user_into = Model('diancang_users')->select_capital_user_users_info($file_type,$wheres);
	    if (!empty($user_into)) {
	        $field = 'capital_order.goods_amount,capital_order.gyongjing_pic,capital_ordergoods.goods_sn,capital_ordergoods.goods_image,capital_ordergoods.goods_bili,capital_ordergoods.yue_fen';
	        $where = "capital_order.user_id = '$user_id' AND capital_order.pay_status = 1 AND capital_order.end_time > '$dangqian_time' AND capital_order.order_status in (0,1,5) ";
	        $goods_into = Model('diancang_order')->select_capital_order_ordergoods_goods_info($field,$where);
	        if (!empty($goods_into)) {
	            foreach ($goods_into as $key => $value) {
	            	if(is_array($value)){
	            		$goods_into[$key]['imgurls'] = get_imgurl_oss($value['goods_image'], 30, 30);
		                $goods_into[$key]['goods_amount'] = price_format($value['goods_amount'], false);
		                $goods_into[$key]['gyongjing_pic'] = price_format($value['gyongjing_pic'], false);
	            	}
	                
	            }

	            return $goods_into;
	        } else {
	            return '';
	        }
	    } else {
	        return '';
	    }
	}

	/**
	 * @return  修改典藏订单
	 * @param   int     $dcorder_id   典藏订单id
	 * @param   array   $order 数组形式的数据
	 * @return  bool
	 */
	private function update_dcorder($dcorder_id, $order){
		$where = "capital_orderid = '$dcorder_id'";
	    return Model('diancang_order')->update_capital_order($order,$where);
	}

	/**
	 * @return  记录订单操作记录 zhang 2017/10/29 星期日
	 * @param   string  $dcorder_id         典藏订单id
	 * @param   integer $order_status       订单状态
	 * @param   integer $shipping_status    配送状态
	 * @param   integer $pay_status         付款状态
	 * @param   string  $note               备注
	 * @param   string  $username           用户名，用户自己的操作则为 buyer
	 * @param   string  $place              支付途径
	 * @return  void
	 */
	private function order_dcaction($dcorder_id, $order_status, $shipping_status, $pay_status, $note = '', $username = null, $place = '未知') {
	    if (is_null($username)) {
	    	$sess = $this->admin_info;
	        $username = $sess['user_name'];
	    }
	    $data['capital_orderid'] = $dcorder_id;
	    $data['action_user'] = $username;
	    $data['order_status'] = $order_status;
	    $data['shipping_status'] = $shipping_status;
	    $data['pay_status'] = $pay_status;
	    $data['action_place'] = $place;
	    $data['action_note'] = $note;
	    $data['log_time'] = gmtime();
	    $data['log_role'] = '商家';
	    return Model('diancang_order')->insert_capital_orderaction($data);		
	}

	/**
	 * @return  修改典藏用户等级积分
	 * @param   int     $user_id   用户id
	 * @param   array   $dcgoods_pic     订单产品价格
	 * @return  bool
	 */
	private function update_dcuser($user_id,$dcgoods_pic){
		if($dcgoods_pic >0 ){
			$add_points = round($dcgoods_pic/100);
		}else{
			$add_points = 0;
		}
		/*查询用户等级信息*/
		$field = 'capital_user.*,capital_rank.max_points';
		$where = "capital_user.user_id = '$user_id'";        
	    $dc_user_into = Model('diancang')->select_capital_user_rank_info($field,$where);
		if(!empty($dc_user_into)){
			$rank_points = $dc_user_into['rank_points'];
			$new_points = $rank_points+$add_points;
			if($new_points>$dc_user_into['max_points']){
				$data = array('capital_rankid'=>$dc_user_into['capital_rankid']+1,'rank_points'=>$new_points);
			}else{
				$data = array('rank_points'=>$new_points);
			}
			$jieguo = Model('diancang')->update_capital_user($data,"user_id = '$user_id'");
			if(!empty($jieguo)){
				return  $add_points;
			}else{
				return  0;
			}
		}else{
			return  false;
		}
	}


}
