<?php

/**
 * 淘玉 后台产品品牌管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萤火虫 $
 * 后台产品品牌管理
 * $Id: back.php  2018-04-07   萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class backControl extends BaseControl {

    private $back_type_arr = array('0'=>'退货-退回', '1'=>'<font color=#ff3300>换货-退回</font>', '2'=>'<font color=#ff3300>换货-换出</font>', '4'=>'退款-无需退货');

    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('back,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

	/**
     * @return 退货单列表
     */
    public function back_list() {
    	/* 检查权限 */
	    admin_priv('back_view');
	    $back_model = Model('back_order');
	    /*分类统计*/
	    if(intval($_REQUEST['supp']) > 0){
	    	$suppliers_list = Model('supplier')->get_supplier_list('*', 'status=1', 'supplier_name ASC');
	    	Tpl::assign('supp_list',   $suppliers_list);
	    	$cntSort[0] = $back_model->get_back_order_count('supplier_id > 0 AND status_back = 5');//审核中
			$cntSort[1] = $back_model->get_back_order_count('supplier_id > 0 AND status_back = 0');//审核通过
			$cntSort[2] = $back_model->get_back_order_count('supplier_id > 0 AND status_back IN(6,7,8,9) AND status_refund = 0');//申请被取消
			$cntSort[3] = $back_model->get_back_order_count('supplier_id > 0 AND status_refund =0 AND (status_back=1 AND back_type=1 OR back_type=4)');//退款
			$cntSort[4] = $back_model->get_back_order_count('supplier_id > 0');//全部
	    }else{
			$cntSort[0] = $back_model->get_back_order_count('supplier_id = 0 AND status_back = 5');//审核中
			$cntSort[1] = $back_model->get_back_order_count('supplier_id = 0 AND status_back = 0');//审核通过
			$cntSort[2] = $back_model->get_back_order_count('supplier_id = 0 AND status_back IN(6,7,8,9) AND status_refund = 0');//申请被取消
			$cntSort[3] = $back_model->get_back_order_count('supplier_id = 0 AND status_refund =0 AND (status_back=1 AND back_type=1 OR back_type=4)');//退款
			$cntSort[4] = $back_model->get_back_order_count('supplier_id = 0');//全部
		}
	    /* 查询退货单列表 */
	    $result = $this->get_back_list();
		
	    /* 模板赋值 */
	    Tpl::assign('ur_here', L('10_back_order'));
		Tpl::assign('cntSort', $cntSort);
	    Tpl::assign('os_unconfirmed',   OS_UNCONFIRMED);
	    Tpl::assign('cs_await_pay',     CS_AWAIT_PAY);
	    Tpl::assign('cs_await_ship',    CS_AWAIT_SHIP);
	    Tpl::assign('full_page',        1);
	    Tpl::assign('back_list',    $result['back']);
	    Tpl::assign('filter',       $result['filter']);
	    Tpl::assign('record_count', $result['record_count']);
	    Tpl::assign('page_count',   $result['page_count']);
	    Tpl::assign('sort_update_time', '<img src="templates/default/images/sort_desc.gif">');

	    /* 显示模板 */
	    Tpl::display('back_list_2.htm');
    }

    /**
     * @return 退货单列表排序、分页、查询
     */
    public function back_query() {
    	/* 检查权限 */
	    admin_priv('back_view');

	    $result = $this->get_back_list();
	    
		if(intval($_REQUEST['supp']) > 0){
	    	$suppliers_list = Model('supplier')->get_supplier_list('*', 'status=1', 'supplier_name ASC');
	    	Tpl::assign('supp_list',   $suppliers_list);
	    }

	    Tpl::assign('back_list',    $result['back']);
	    Tpl::assign('filter',       $result['filter']);
	    Tpl::assign('record_count', $result['record_count']);
	    Tpl::assign('page_count',   $result['page_count']);

	    $sort_flag = sort_flag($result['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('back_list_2.htm'), '', array('filter' => $result['filter'], 'page_count' => $result['page_count']));

    }

    /**
     * @return 退货单删除
     */
    public function remove_back() {
    	$back_id = $_REQUEST['back_id'];
		/* 删除退货单 */
		if(is_array($back_id)){
			$back_id_list = implode(",", $back_id);
			/*删除退换货用户上传的图片*/
			$where = "back_id in ($back_id_list)";
			Model('back_order')->delete_back_order($where);
			Model('back_goods')->delete_back_goods($where);
			Model('back_order')->delete_back_action($where);
		} else {
			/*删除退换货用户上传的图片*/
			$where = "back_id = '$back_id'";
			Model('back_order')->delete_back_order($where);
			Model('back_goods')->delete_back_goods($where);
			Model('back_order')->delete_back_action($where);
		}

	    /* 返回 */		
	    showMessage('恭喜，记录删除成功！',  array(array('href'=>'index.php?act=back&op=back_list' , 'text' =>'返回退款/退货及维修列表')));
    }

     /**
     * @return 退货单详情页
     */
    public function back_info() {
    	/* 检查权限 */
	    admin_priv('back_view');

	    $back_id = intval(trim($_REQUEST['back_id']));

	    /* 根据发货单id查询发货单信息 */
	    if (!empty($back_id)){
	        $back_order = $this->back_order_info($back_id);
	    } else {
	        die('order does not exist');
	    }

	    /*获取原订单基本信息*/
	    if($back_order) {
			$where = "order_id='$back_order[order_id]'";
			$old_order = Model('order')->select_order_info_info('*',$where);
	        $old_order['tel1'] = $old_order['tel'];
	        $old_order['tel'] = jiaMiPhone($old_order['tel']);
	        $old_order['mobile1'] = $old_order['mobile'];
	        $old_order['mobile'] = jiaMiPhone($old_order['mobile']);
			if($old_order) {
				$old_order['add_time'] =  local_date(C('time_format'), $old_order['add_time']);
				$old_order['shipping_time'] =  local_date(C('time_format'), $old_order['shipping_time']);
				$old_order['tel'] = $old_order['tel'] ? "电话：".$old_order['tel'] : "";
				$old_order['tel'] .= $old_order['tel'] ? "&nbsp;&nbsp;&nbsp;&nbsp;" : "";
				$old_order['tel'] .= $old_order['mobile'] ? "手机：".$old_order['mobile'] : "";
	            $old_order['tel1'] = $old_order['tel1'] ? "电话：".$old_order['tel1'] : "";
	            $old_order['tel1'] .= $old_order['tel1'] ? "&nbsp;&nbsp;&nbsp;&nbsp;" : "";
	            $old_order['tel1'] .= $old_order['mobile1'] ? "手机：".$old_order['mobile1'] : "";
				$old_order['insure_yn'] = $old_order['insure_fee']>0 ? 1 : 0;
				Tpl::assign('old_order', $old_order);
			}
		}else {
	        die('order does not exist');
	    }

	    /* 获取原订单商品信息 */
		$where = "order_id ='$back_order[order_id]' " . ($back_order['back_type'] == 4 ? "" : " and goods_id='$back_order[goods_id]' ");
		$order_goods = Model('order')->get_order_goods_list('*',$where);
		Tpl::assign('order_goods', $order_goods);


	    /* 取得用户名 */
	    if ($back_order['user_id'] > 0){
	        $user = user_info($back_order['user_id']);
	        if (!empty($user)){
	            $back_order['user_name'] = $user['alias'];
	        }
	    }

        /* 取得退换货商品 */
		$where = "back_id = " . $back_order['back_id'];
		$res_list = Model('back_goods')->get_back_goods_list('*',$where,'back_type asc');
	    $goods_list = array();
		foreach ($res_list as $row_list ){
			$row_list['back_type_name'] = $this->back_type_arr[$row_list['back_type']];
			$row_list['goods_url'] = WEB_PATH.'goods.php?id='.$row_list['goods_id'];
			$row_list['back_goods_money'] = price_format($row_list['back_goods_price'] * $row_list['back_goods_number'], false);
			$goods_list[] = $row_list;
		}

		/*取得退换商品收货人地址*/
        $back_order['region'] = get_province_city($back_order['province'],$back_order['city'],$back_order['district']);
        $back_order['address'] = $back_order['region'].' '.$back_order['address'];
		
		/* 取得能执行的操作列表 */
		$operable_list = $this->operable_list($back_order);
	    Tpl::assign('operable_list', $operable_list);

	    /* 退换货商品图片和退换货回复*/
	    $res = Model('back_order')->get_back_replay_list('*',"back_id = '$back_id'",'add_time ASC');
	    foreach ($res as $value){
	        $value['add_time'] = local_date(C('time_format'), $value['add_time']);
	        $back_replay[] = $value;
	    }
	    if ($back_order['imgs']){
	        $imgs = explode(",",$back_order['imgs']);
	    }
	    Tpl::assign('imgs', $imgs);
	    Tpl::assign('back_replay', $back_replay);

	    /* 取得订单操作记录 */
	    $act_list = array();
	    $res_act = Model('back_order')->get_back_action_list('*',"back_id = '$back_id'",'log_time DESC,action_id DESC');
	    foreach ($res_act as $row_act) {
	     	$row_act['status_back']    = L('bos')[$row_act['status_back']];
	        $row_act['status_refund']  = L('bps')[$row_act['status_refund']];
	        $row_act['action_time']    = local_date(C('time_format'), $row_act['log_time']);
	        $act_list[] = $row_act;
	    } 
	    Tpl::assign('action_list', $act_list);		
		Tpl::assign('back_order', $back_order);
	    Tpl::assign('exist_real_goods', 1);
	    Tpl::assign('goods_list', $goods_list);
	    Tpl::assign('back_id', $back_id); // 发货单id
	    Tpl::assign('ur_here', L('back_operate') . L('detail'));
	    Tpl::assign('action_link', array('href' => 'index.php?act=back&op=back_list&', 'text' => L('10_back_order')));
	    /* 显示模板 */
	    Tpl::display('back_info_2.htm');

    }

    /**
     * @return 退货回复
     */
    public function replay() {
    	$back_id = intval($_REQUEST['back_id']);
	    $message = $_POST['message'];
	    $add_time = gmtime();
	    $data['back_id'] = $back_id;
	    $data['message'] = $message;
	    $data['add_time'] = $add_time;
	    Model('back_order')->insert_back_replay($data);

	    /* 返回 */		
	    showMessage('回复成功！',  array(array('href'=>'index.php?act=back_info&back_id='.$back_id , 'text' =>'返回')));
    }
   
    /**
     * @return 退货单操作
     */
    public function operate() {
    	/* 检查权限 */
	    admin_priv('back_view');
	    //管理员信息
	    $sess = $this->admin_info;
	    $admin_id = $sess['user_id'];
	    $where = array('user_id'=>$admin_id);
	    $admin_info = Model('admin')->select_admin_info('*',$where);
	    $admin_name = $admin_info['user_name'];
	    $back_id = intval(trim($_REQUEST['back_id']));        // 退换货订单id
	    $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';

	    /* 查询订单信息 */
	    $order = $this->back_order_info($back_id);
	    /*查询产品信息*/
	    $field = 'back_order.order_sn ,back_order.add_time,back_order.goods_name,goods.original_img,goods.goods_sn';
	    $backorderinto = Model('back_order')->get_back_order_goods_list($field,"back_order.back_id='$back_id'");
	    $goodsimgs = get_imgurl_oss($backorderinto['original_img'],160,160,false,true);
	    /* 通过申请 */
	    if (isset($_POST['ok'])){
	        $status_back='5';
	        $magsmag = "![退款退货通过申请](".$goodsimgs.")<br/>管理员".$admin_name.":<通过>提交退款退货请求<br/>产品编号：".$backorderinto['goods_sn'];
	        $this->update_back($back_id, $status_back, $status_refund);
	        $this->back_action($back_id, 0, $order['status_refund'],  $action_note);
	    }
	    /* 拒绝申请 */
	    if (isset($_POST['no'])){
	        $status_back='6';
	        $magsmag = "![退款退货拒绝申请](".$goodsimgs.")<br/>管理员".$admin_name.":<拒绝>退款退货申请<br/>产品编号：".$backorderinto['goods_sn'];
	        $this->update_back($back_id, $status_back, $status_refund);
	        $this->back_action($back_id, $status_back, $order['status_refund'],  $action_note);

	    }
	    /* 售后 */
	    if (isset($_POST['after_service'])){
	    	
	        /* 记录log */
	        $magsmag = "![退款退货售后](".$goodsimgs.")<br/>管理员".$admin_name.":<售后>退货申请<br/>产品编号：".$backorderinto['goods_sn'];
	        $this->back_action($back_id, $order['status_back'], $order['status_refund'],  '[' . L('op_after_service') . '] ' . $action_note);
	    }
	    /* 确认（收到寄回的商品）*/
	    if (isset($_POST['confirm'])) {
	        $status_back='1';
	        $magsmag = "![退款退货确认](".$goodsimgs.")<br/>管理员".$admin_name.":<确认>退货申请<br/>产品编号：".$backorderinto['goods_sn'];
	        $this->update_back($back_id, $status_back, $status_refund);
	        $this->back_action($back_id, $status_back, $order['status_refund'],  $action_note);
	    }
	    /* 去退款 */
	    if (isset($_POST['refund'])) {
	        Tpl::assign('ur_here', L('back_operate') . '退款');
	        $field = 'back_order.* ,order_info.pay_id,order_info.shipping_fee,order_info.third_party_pay, order_info.balance_pay, order_info.taoyubi_pay';
	        $refund = Model('back_order')->get_back_order_order_info_list($field,"back_order.back_id='$back_id'");
	        Tpl::assign('back_id', $back_id);
	        Tpl::assign('refund', $refund);
	        Tpl::display('back_refund.htm');
	        exit;
	    }
	    /* 换出商品寄出*/
	    if (isset($_POST['backshipping'])) {
	        $status_back='2';
	        $this->update_back($back_id, $status_back, $status_refund);
	        $this->back_action($back_id, $status_back, $order['status_refund'],  $action_note);
	    }
	    /* 完成退换货 */
	    if (isset($_POST['backfinish'])){
	        $user_id = Model('back_order')->select_back_order_info('user_id',"back_id = " . $back_id)['user_id'];
	        $info = Model('users')->select_users_info('parent_id,level_id',"user_id=$user_id");
	        $level_id=$info['level_id'];
	        $inf= Model('user_level')->select_user_level_info('level_bili',"level_id=$level_id");
	        $level_bili=$inf['level_bili'];
	        if($info['parent_id'] > 0){
	            $yaoqin_bili = C('yqfanli');
	        }else{
	            $yaoqin_bili = 0;
	        }
	        $status_back='3';
	        $magsmag = "![退款退货完成](".$goodsimgs.")<br/>管理员".$admin_name.":<完成>退货申请<br/>产品编号：".$backorderinto['goods_sn'];
	        $this->update_back($back_id, $status_back, $status_refund,$yaoqin_bili,$level_bili);
	        $this->back_action($back_id, $status_back, $order['status_refund'],  $action_note);
	    }
	    /* 终止退款申请 */
	    if (isset($_POST['cancel_apply'])) {
	        $status_back='9';
	        $status_refund = '0';
	        $action_note = '管理员终止退款流程';
	        $magsmag = "![退款退货终止](".$goodsimgs.")<br/>管理员".$admin_name.":<终止>退货申请<br/>产品编号：".$backorderinto['goods_sn'];
	        $this->update_back($back_id, $status_back, $status_refund);
	        Model('order')->update_order_info(array('order_status'=>5),'order_id ='.$order['order_id']);
	        $this->back_action($back_id, $status_back, $order['status_refund'],  $action_note);
	    }
	    /* 恢复待收货状态*/
	    if (isset($_POST['recover_apply'])) {
	        $status_back='0';
	        $status_refund = '0';
	        $action_note = '管理员恢复待收货状态';
	        $magsmag = "![恢复待收货](".$goodsimgs.")<br/>管理员".$admin_name.":<恢复待收货><br/>产品编号：".$backorderinto['goods_sn'];
	        $this->update_back($back_id, $status_back, $status_refund);
	        Model('order')->update_order_info(array('order_status'=>4),'order_id ='.$order['order_id']);
	        $this->back_action($back_id, $status_back, $order['status_refund'],  $action_note);
	    }
	    /*操作结果提示*/
	    $magstitle = "申请退货退款操作结果";
	    $result = send_ding_msg($magstitle,$magsmag,'qita');
	    $links[] = array('text' => '返回退款/退货及维修详情', 'href' => 'index.php?act=back&op=back_info&back_id=' . $back_id);
	    showMessage('恭喜，成功操作！', $links);
    }

    /**
     * @return 去退款
     */
    public function operate_refund() {
    	/* 检查权限 */
	    admin_priv('back_view');
	    $status_refund = '1';
	    $back_id = intval(trim($_REQUEST['back_id']));/*退换货订单id*/
	    $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';
	    $order = $this->back_order_info($back_id);
	    $back_goods = Model('back_goods');
	    $back_order = Model('back_order');

    	$data = array('status_refund'=>$status_refund);
    	$where = "back_id='$back_id' and back_type in(1,4)";
    	$back_goods->update_back_goods($data,$where);

	    $refund_money_2 = $_REQUEST['refund_money_2'] + $_REQUEST['refund_shipping_fee'];
	    $refund_desc = $_REQUEST['refund_desc'] . ($_REQUEST['refund_shipping'] ? '\n（已退运费：'. $_REQUEST['refund_shipping_fee']. '）' : '');	    
    	$data = array(
    		'status_refund'=>$status_refund,
    		'refund_money_2'=>$refund_money_2,
    		'back_pay'=>$_REQUEST['refund_type'],
    		'refund_type'=>$_REQUEST['refund_type'],
    		'refund_desc'=>$refund_desc
    	);
    	$where = "back_id='$back_id'";
    	$back_order->update_back_order($data,$where);

    	$s_fee = $_REQUEST['refund_shipping_fee'];    	
	    if ($_REQUEST['refund_type'] == '1') {
	    	/* 退回用户余额 */
	        if(empty($refund_desc)){
	            $refund_desc = '退款操作';
	        }
	        log_account_change($order['user_id'], $refund_money_2,0,0,0, $refund_desc,3);
	        if($s_fee > 0){	            
	            $datai['shipping_fee'] = $s_fee;
	        	Model('order')->update_order_info_setDec("order_id = ".$order['order_id'],$datai);
	        }
	        /*是否开启余额变动给客户发短信-退款*/
	        if(C('sms_user_money_change') == 1) {
	            $users = Model('users')->select_users_info('user_money,mobile_phone','user_id ='.$order['user_id']);
	            if(!empty($users['mobile_phone'])) {
	                $param = array();
	                $param['good_pns'] = $order['order_sn'];
	                $param['yuan'] = $users['user_money'];
	                $param['site_name']	= C('shop_name');
	                $result = send_sms_msg($users['mobile_phone'],'back_true',$param);
	            }
	        }
	    } elseif ($_REQUEST['refund_type'] == '2') {
	    	/*线下退款*/
	        if($s_fee > 0){	            
	            $con['shipping_fee'] = $s_fee;
	        	Model('order')->update_order_info_setDec("order_id = ".$order['order_id'],$con);
	        }
        	/*是否开启给客户发短信-退款*/
	        if(C('sms_user_money_change') == 1) {
	            $orderSn = Model('order')->select_order_info_info('order_sn','order_id = '.$order['order_id'])['order_sn'];
	            $users = Model('users')->select_users_info('user_money,mobile_phone','user_id ='.$order['user_id']);
	            if(!empty($users['mobile_phone'])) {
	                $param = array();
	                $param['good_pns'] = $orderSn;
	                $param['yuan'] = $refund_money_2;
	                $param['site_name']	= C('shop_name');
	                $result = send_sms_msg($users['mobile_phone'],'back_true',$param);

	            }
	        }
	    } elseif ($_REQUEST['refund_type'] == '3') {
	    	/*按原支付方式退款*/
	        if(empty($refund_desc)){
	            $refund_desc = '退款操作';
	        }
	        if($s_fee > 0){	           
	            $content['shipping_fee'] = $s_fee;
	        	Model('order')->update_order_info_setDec("order_id = ".$order['order_id'],$content);
	        }
	        $field = 'order_info.pay_id, order_info.third_party_pay, order_info.balance_pay, order_info.taoyubi_pay, order_info.order_id';
	        $where = 'back_order.back_id = '.$back_id;
	        $zhifu = Model('back_order')->get_back_order_order_info_list($field,$where);
	        if($zhifu['pay_id'] == 11){
	            if($zhifu['taoyubi_pay'] >= $refund_money_2){
	                $taoyu_money = $refund_money_2*10;	                
	                $numb['taoyu_money'] = $taoyu_money;
	        		Model('users')->update_users_setInc("user_id = '".$order['user_id']."'",$numb);	               
	                $number['taoyubi_pay'] = $refund_money_2;
	        		Model('order_info')->update_order_info_setDec("order_id = '".$zhifu['order_id']."'",$number);
	                if(C('sms_user_money_change') == 1) {
	                	$users = Model('users')->select_users_info('user_money,mobile_phone,taoyu_money','user_id ='.$order['user_id']);
			            if(!empty($users['mobile_phone'])) {
			                $param = array();
			                $param['good_pns'] = $order['order_sn'];
			                $param['yuan'] = $users['taoyu_money'];
			                $param['site_name']	= C('shop_name');
			                $result = send_sms_msg($users['mobile_phone'],'taoyubi_back_true',$param);
			            }	                    
	                }
	            }else{
	                if(($zhifu['taoyubi_pay'] + $zhifu['balance_pay']) >= $refund_money_2){
	                    
	                    $taoyu_money = $zhifu['taoyubi_pay']*10;
	                    $user_money = $refund_money_2 - $zhifu['taoyubi_pay'];
	                    $taoyu_money_num['taoyu_money'] = $taoyu_money;
	                    $user_money_num['user_money'] = $user_money;
	                    Model('users')->update_users_setInc("user_id = '".$order['user_id']."'",$taoyu_money_num);
	                    Model('users')->update_users_setInc("user_id = '".$order['user_id']."'",$user_money_num);	                  
	                    $tm = $zhifu['taoyubi_pay'];
	                    $taoyubi_pay_num['taoyubi_pay'] = $tm;
	                    $balance_pay_num['balance_pay'] = $user_money;
	        			Model('order_info')->update_order_info_setDec("order_id = '".$zhifu['order_id']."'",$taoyubi_pay_num);
	        			Model('order_info')->update_order_info_setDec("order_id = '".$zhifu['order_id']."'",$balance_pay_num);	                   
	                    $account_log = array(
	                        'user_id'       => $order['user_id'],
	                        'user_money'    => $user_money,
	                        'taoyu_money'   => $taoyu_money,
	                        'frozen_money'  => 0,
	                        'rank_points'   => 0,
	                        'pay_points'    => 0,
	                        'change_time'   => gmtime(),
	                        'change_desc'   => $refund_desc,
	                        'change_type'   => 3
	                    );
	        			Model('accountlog')->insert_account_log($account_log);
	                    if(C('sms_user_money_change') == 1) {
	                        $users = Model('users')->select_users_info('user_money,mobile_phone,taoyu_money','user_id ='.$order['user_id']);
	                        if(!empty($users['mobile_phone'])) {
	                            $param = array();
	                            $param['good_pns'] = $order['order_sn'];
	                            $param['yuan'] = $users['taoyu_money'];
	                            $param['yue'] = $users['user_money'];
	                            $param['site_name']	= C('shop_name');
			                	$result = send_sms_msg($users['mobile_phone'],'hun_back_true',$param);
	                        }
	                    }
	                }else{
	                    $taoyu_money = $zhifu['taoyubi_pay']*10;
	                    $user_money = $zhifu['balance_pay'];	                   
	                    $taoyu_money_num['taoyu_money'] = $taoyu_money;
	                    $user_money_num['user_money'] = $user_money;
	                    Model('users')->update_users_setInc("user_id = '".$order['user_id']."'",$taoyu_money_num);
	                    Model('users')->update_users_setInc("user_id = '".$order['user_id']."'",$user_money_num);
	                    $account_log = array(
	                        'user_id'       => $order['user_id'],
	                        'user_money'    => $user_money,
	                        'taoyu_money'   => $taoyu_money,
	                        'frozen_money'  => 0,
	                        'rank_points'   => 0,
	                        'pay_points'    => 0,
	                        'change_time'   => gmtime(),
	                        'change_desc'   => $refund_desc,
	                        'change_type'   => 3
	                    );
	                    Model('accountlog')->insert_account_log($account_log);
	                    $tm = $zhifu['taoyubi_pay'];	                    
	                    $taoyubi_pay_num['taoyubi_pay'] = $tm;
	                    $balance_pay_num['balance_pay'] = $user_money;
	        			Model('order_info')->update_order_info_setDec("order_id = '".$zhifu['order_id']."'",$taoyubi_pay_num);
	        			Model('order_info')->update_order_info_setDec("order_id = '".$zhifu['order_id']."'",$balance_pay_num);	    
	                    if(C('sms_user_money_change') == 1) {
	                        
	                        $users = Model('users')->select_users_info('user_name,mobile_phone','user_id ='.$order['user_id']);
	                        if(!empty($users['mobile_phone'])) {	                            
	                            $param = array();
	                            $param['good_pns'] = $order['order_sn'];
	                            $param['yuan'] = $refund_money_2;
	                            $param['site_name']	= C('shop_name');
	                            $result = send_sms_msg($users['mobile_phone'],'backpay_success',$param);
	                        }
	                    }
	                }
	            }
	        } elseif ($zhifu['pay_id'] == 4){
	            log_account_change($order['user_id'], $refund_money_2,0,0,0, $refund_desc,3);
	            //是否开启余额变动给客户发短信-退款
	            if(C('sms_user_money_change') == 1) {
	                $users = Model('users')->select_users_info('user_name,mobile_phone','user_id ='.$order['user_id']);
	                if(!empty($users['mobile_phone'])) {	                   
	                    $param = array();
	                    $param['good_pns'] = $order['order_sn'];
	                    $param['yuan'] = $users['user_money'];
	                    $param['site_name']	= C('shop_name');
	                    $result = send_sms_msg($users['mobile_phone'],'back_true',$param);
	                }
	            }
	        } elseif ($zhifu['pay_id'] == 10){
	            $taoyu_money = $refund_money_2*10;
	            $numbers['taoyu_money'] = $taoyu_money;
	            Model('users')->update_users_setInc("user_id = '".$order['user_id']."'",$numbers);
	            $account_log = array(
	                'user_id'       => $order['user_id'],
	                'taoyu_money'    => $taoyu_money,
	                'frozen_money'  => 0,
	                'rank_points'   => 0,
	                'pay_points'    => 0,
	                'change_time'   => gmtime(),
	                'change_desc'   => $refund_desc,
	                'change_type'   => 3
	            );
	            Model('accountlog')->insert_account_log($account_log);
	            if(C('sms_user_money_change') == 1) {
	                $users = Model('users')->select_users_info('user_money,mobile_phone,taoyu_money','user_id ='.$order['user_id']);
	                if(!empty($users['mobile_phone'])) {
	                    $param = array();
	                    $param['good_pns'] = $order['order_sn'];
	                    $param['yuan'] = $users['taoyu_money'];
	                    $param['site_name']	= C('shop_name');
	                    $result = send_sms_msg($users['mobile_phone'],'taoyubi_back_true',$param);
	                }
	            }
	        }
	    } elseif ($_REQUEST['refund_type'] == '4') {
	        /*退回用户淘玉币*/
	        if(empty($refund_desc)){
	            $refund_desc = '退款操作';
	        }
	        if($s_fee > 0){
	            $cont['shipping_fee'] = $s_fee;
		        Model('order')->update_order_info_setDec("order_id = ".$order['order_id'],$cont);
	        }
	        $taoyu_money = $refund_money_2*10;	        
	        $num['taoyu_money'] = $taoyu_money;
	        Model('users')->update_users_setInc("user_id = '".$order['user_id']."'",$num);
	        $account_log = array(
	            'user_id'       => $order['user_id'],
	            'taoyu_money'    => $taoyu_money,
	            'frozen_money'  => 0,
	            'rank_points'   => 0,
	            'pay_points'    => 0,
	            'change_time'   => gmtime(),
	            'change_desc'   => $refund_desc,
	            'change_type'   => 3
	        );
	        Model('accountlog')->insert_account_log($account_log);
	        //是否开启余额变动给客户发短信-退款
	        if(C('sms_user_money_change') == 1) {	          
	            $users = Model('users')->select_users_info('user_money,mobile_phone,taoyu_money','user_id ='.$order['user_id']);
	            if(!empty($users['mobile_phone'])) {
	                $param = array();
	                $param['good_pns'] = $order['order_sn'];
	                $param['yuan'] = $users['taoyu_money'];
	                $param['site_name']	= C('shop_name');
	                $result = send_sms_msg($users['mobile_phone'],'back_true',$param);

	            }
	        }
	    }
	    /* 记录log */
	    $this->back_action($back_id, $order['status_back'], $status_refund,  $action_note);
	    $links[] = array('text' => '返回退款/退货及维修详情', 'href' => 'index.php?act=back&op=back_info&back_id=' . $back_id);
	    showMessage('操作成功',$links);
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
	 * @return 获取退货单列表信息
	 * @return array
	 */
	private function get_back_list(){
	    $result = get_filter();
	    if ($result === false){
	        $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

	        /* 过滤信息 */
			$filter['sort_index'] = empty($_REQUEST['sort_index']) ? -1 : trim($_REQUEST['sort_index']);
	        $filter['delivery_sn'] = empty($_REQUEST['delivery_sn']) ? '' : trim($_REQUEST['delivery_sn']);
	        $filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
	        $filter['order_id'] = empty($_REQUEST['order_id']) ? 0 : intval($_REQUEST['order_id']);
			$filter['order_type'] = intval($_REQUEST['order_type']);
			$filter['back_type'] = intval($_REQUEST['back_type']);
						
	        if ($aiax == 1 && !empty($_REQUEST['consignee'])){
	            $_REQUEST['consignee'] = json_str_iconv($_REQUEST['consignee']);
	        }
	        $filter['consignee'] = empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);

	        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'status_back ASC, update_time' : trim($_REQUEST['sort_by']);
	        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

	     	$filter['supp'] = (isset($_REQUEST['supp']) && !empty($_REQUEST['supp']) && intval($_REQUEST['supp'])>0) ? intval($_REQUEST['supp']) : 0;
	        
	        $filter['suppid'] = (isset($_REQUEST['suppid']) && !empty($_REQUEST['suppid']) && intval($_REQUEST['suppid'])>0) ? intval($_REQUEST['suppid']) : 0;

	        $where = ($filter['supp']>0) ? 'WHERE back_order.supplier_id > 0' : 'WHERE back_order.supplier_id = 0';
	        
	        if ($filter['suppid']){
	        	$where = 'WHERE back_order.supplier_id = '.$filter['suppid'];
	        }
	        if ($filter['order_sn']){
	            $where .= " AND order_sn LIKE '%" . mysql_like_quote($filter['order_sn']) . "%'";
	        }
	        if ($filter['consignee']){
	            $where .= " AND consignee LIKE '%" . mysql_like_quote($filter['consignee']) . "%'";
	        }
	        if ($filter['delivery_sn']){
	            $where .= " AND delivery_sn LIKE '%" . mysql_like_quote($filter['delivery_sn']) . "%'";
	        }
			if($filter['sort_index'] == -1){
				if ($filter['order_type'] == 2){
					$where .= " AND status_back < 6 AND status_back != 3 ";
				}
				
				if ($filter['order_type'] == 3){
					$where .= " AND status_back = 3 ";
				}
				
				if (empty($filter['order_type'])){
					$where .= " AND status_back != 3 ";
				}
				if ($filter['order_type'] == 4){
					$where .= " AND status_back > 5 ";
				}
				if ($filter['back_type'] == 1){
					$where .= " AND back_type = 1 ";
				}
				if ($filter['back_type'] == 4){
					$where .= " AND back_type = 4 ";
				}
			}else if(!empty($filter['sort_index']) && $filter['sort_index']<11){
				$sortIndex = $filter['sort_index'] == '10' ? '0' : $filter['sort_index'];
				$where .= " AND status_back = {$sortIndex} ";
			}else if(!empty($filter['sort_index']) && $filter['sort_index'] == 40){
				$where .= " AND status_refund = 0 AND (`status_back`=1 AND `back_type`=1 OR `back_type`=4) ";
			}else if(!empty($filter['sort_index']) && $filter['sort_index'] == 50){
				$where .= " AND `status_back` IN(6,7,8,9) AND `status_refund` = 0 ";
			}

	        /* 获取管理员信息 */
	        $sess = $this->admin_info;
            $admin_id = $sess['user_id'];
            $wherem = array('user_id'=>$admin_id);
            $admin_info = Model('admin')->select_admin_info('*',$wherem);

	        /* 如果管理员属于某个办事处，只列出这个办事处管辖的发货单 */
	        if ($admin_info['agency_id'] > 0){
	            $where .= " AND agency_id = '" . $admin_info['agency_id'] . "' ";
	        }

	        /* 如果管理员属于某个供货商，只列出这个供货商的发货单 */
	        if ($admin_info['suppliers_id'] > 0){
	            $where .= " AND suppliers_id = '" . $admin_info['suppliers_id'] . "' ";
	        }

	        /* 分页大小 */
	        $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

	        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0){
	            $filter['page_size'] = intval($_REQUEST['page_size']);
	        } elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) {
	            $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
	        } else {
	            $filter['page_size'] = 15;
	        }

	        /* 记录总数 */
            $filter['record_count']   = Model('back_order')->get_back_order_count($where);
	        $filter = page_and_size($filter);
	        /* 查询 */
	        if($filter['supp']){
	        	$sql = "SELECT back_order.*,supplier.supplier_name FROM " . Model()->tablename("back_order") . " AS back_order LEFT JOIN ". Model()->tablename("supplier") ." AS supplier ON back_order.supplier_id=supplier.supplier_id ".
				    " $where ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'];
	        }else{
	        	$sql = "SELECT * FROM " . Model()->tablename("back_order") . " AS back_order ".
				    " $where ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'];
	        }

	        set_filter($filter, $sql);
	    } else {
	        $sql    = $result['sql'];
	        $filter = $result['filter'];
	    }

	    $row = get_all_page($sql, $filter['page_size'], $filter['start']);

	    /* 格式化数据 */
	    if(!empty($row )){
	    	foreach ($row AS $key => $value) {
		        $row[$key]['return_time'] = local_date(C('time_format'), $value['return_time']);
		        $row[$key]['add_time'] = local_date(C('time_format'), $value['add_time']);
		        $row[$key]['update_time'] = local_date(C('time_format'), $value['update_time']);
				$row[$key]['refund_money_1'] = price_format($value['refund_money_1']);
				$row[$key]['refund_money_2'] = price_format($value['refund_money_2']);
				$row[$key]['status_back_val'] = L('bos')[(($value['back_type'] == 4) ? $value['back_type'] : $value['status_back'])]."-" . (($value['back_type'] == 3) ? "申请维修" : L('bps')[$value['status_refund']]);
				$row[$key]['goods_url'] = WEB_PATH.build_uri('goods', array('gid'=>$value['goods_id']), $value['goods_name']);

		        if ($value['status'] == 1) {
		            $row[$key]['status_name'] = L('delivery_status')[1];
		        } else {
		        	$row[$key]['status_name'] = L('delivery_status')[0];
		        }
				
				$row[$key]['goods_list'] = Model('back_goods')->get_back_goods_list('*',"back_id = " . $value['back_id']);
		        foreach($row[$key]['goods_list'] as $k1=>$v1 ){
		            $goods_id = $v1['goods_id'];
		            $where = array('goods_id' =>$goods_id);
	                $res = Model('goods')->select_goods_info('goods_sn ,original_img',$where);
		            $res['original_img'] = get_imgurl_oss($res['original_img'],50,50);
		            $res3[$k1] = $res;
		        }
		        $row[$key]['sn_img'] = $res3;
		    }
	    }	    	
		$arr = array('back' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
		if(!empty($row )){
			/*电话号码加密*/
		    foreach($arr['back'] as $k=>$v){
		        $arr['back'][$k]['tel1'] = $v['tel'];
		        $arr['back'][$k]['mobile1'] = $v['mobile'];
		        $arr['back'][$k]['tel'] = jiaMiPhone($v['tel']);
		        $arr['back'][$k]['mobile'] = jiaMiPhone($v['mobile']);
		    }		   
		}
		return $arr;    	    
	}

	/**
	 * @return  取得退货单信息
	 * @param   int     $back_id   退货单 id（如果 back_id > 0 就按 id 查，否则按 sn 查）
	 * @return  array   退货单信息（金额都有相应格式化的字段，前缀是 formated_ ）
	 */
	private function back_order_info($back_id){
	    $return_order = array();
	    if (empty($back_id) || !is_numeric($back_id)){
	        return $return_order;
	    }

	    $where = '';
	    /* 获取管理员信息 */
	    $admin_info = $this->admin_info();

	    /* 如果管理员属于某个办事处，只列出这个办事处管辖的发货单 */
	    if ($admin_info['agency_id'] > 0){
	        $where .= " AND agency_id = '" . $admin_info['agency_id'] . "' ";
	    }

	    /* 如果管理员属于某个供货商，只列出这个供货商的发货单 */
	    if ($admin_info['suppliers_id'] > 0){
	        $where .= " AND suppliers_id = '" . $admin_info['suppliers_id'] . "' ";
	    }
	 
	    $where .= "back_id = '$back_id'";
	    $back = Model('back_order')->select_back_order_info('*',$where,'','0,1');
	    if ($back){
	        /* 格式化金额字段 */
	        $back['formated_insure_fee']     = price_format($back['insure_fee'], false);
	        $back['formated_shipping_fee']   = price_format($back['shipping_fee'], false);

	        /* 格式化时间字段 */
	        $back['formated_add_time']       = local_date(C('time_format'), $back['add_time']);

			if ($back['back_type'] == 4){
				$wheres = "order_id = " . $back['order_id'];
				$back['money_paid'] = Model('order')->select_order_info_info('money_paid',$wheres)['money_paid'];
			}

			/* 退换货状态   退款状态 */
			
	        $return_order = $back;
	    }

	    return $return_order;
	}

	/**
	 * @return 取得管理员信息
	 * @return array 管理员信息
	 */
	private function admin_info(){
		/* 获取管理员信息 */
	    $sess = $this->admin_info;
        $admin_id = $sess['user_id'];
        $where = array('user_id'=>$admin_id);
        $admin_info = Model('admin')->select_admin_info('*',$where);
        return $admin_info;
	}

	/**
	 * @return 返回某个退换货订单可执行的操作列表
	 */
	private function operable_list($order){	
		$os = $order['status_back'];
		$ds = $order['status_refund'];
		/* 根据状态返回可执行操作 */
	    $list['ok'] = true;
	    $list['no'] = true;
	    $list['confirm'] = true;
	    $list['refund'] = true;
	    $list['backshipping'] = true;
	    $list['backfinish'] = true;	   
		if ($os != 5){
			$list['ok']=false;
			$list['no']=false;
		}
		if ($os == '1' || $os == '2' || $os == '3' || $ds == '1'){
			if($os == '1'){
				$list['confirm']=false;
			}
			
			if ($os=='2'){
				$list['backshipping']=false;
			}
			if ($os=='3'){
				$list['refund']=false;
				$list['backshipping']=false;
				$list['backfinish']=false;
			}
		}
		if($ds=='9' || $ds=='1'){
			$list['refund']=false;
		}
		return $list;
	}

	/**
	 * @return 更新退换货订单状态
	 */
	private function update_back($back_id, $status_back, $status_refund ,$yaoqin_bili='',$level_bili='') {
		$back_model  = Model('back_order');
		$goods_model = Model('back_goods');
		$order_model = Model('order');
	    $data = array();
	    if (isset($status_back)) {
	        $data['status_back'] = $status_back;
	    }
	    if (isset($status_refund)) {
	        $data['status_refund'] = $status_refund;
	    }	   
	    $back_model->update_back_order($data,"back_id='$back_id'");

	    if($status_back =='5') {
	    	/*通过申请*/
	    	$where = "back_id=$back_id";
	        $status_info = $back_model->select_back_order_info('back_type,order_sn',$where);
	        /*退货退款 1 退款（无需退货）4  2换货 3申请返修*/
	        $status_b = ($status_info['back_type'] == 4) ? 4 : 0;
	        $status_bo = $status_info['order_sn'];

	        $datae['order_status'] = '4';
	        $datae['to_buyer'] = '用户对订单内的部分或全部商品申请退款';
	        $order_model->update_order_info($datae,"order_sn ='$status_bo'");

	        $wheres = "back_id='$back_id'";
	        $datas['status_back'] = $status_b;	        
	        $goods_model->update_back_goods($datas,$wheres);
	        $back_model->update_back_order($datas,$wheres);	  
	    }
	    if($status_back =='6') {
	    	/*拒绝申请*/	       
	        $wheres = "back_id='$back_id'";
	        $datas['status_back'] = $status_back;	        
	        $goods_model->update_back_goods($datas,$wheres);
	        $back_model->update_back_order($datas,$wheres);	
	    }

	    if($status_back =='1' or $status_back =='3') {
	    	/*收到退换回的货物，完成退换货*/
	    	$wheres = "back_id='$back_id'";
	        $datas['status_back'] = $status_back;	        
	        $goods_model->update_back_goods($datas,$wheres);
	        $back_model->update_back_order($datas,$wheres);	        

	        $get_order_id = $back_model->select_back_order_info('order_id','back_id = ' . $back_id)['order_id'];
	        $wherem = 'back_id = ' . $back_id. " AND status_back = '3' AND back_type <> '3'";
	        $get_goods_id = $goods_model->get_back_goods_list('goods_id',$wherem);
	        $get_goods_info = $goods_model->select_back_goods_info('goods_id, back_type','back_id = ' . $back_id);
	        
	        if ($status_back == '3' && $get_goods_info['back_type'] != '3') {
	        	$field = "back_goods_number,goods_id,product_id,back_goods_price";
	            $get_back_goods_number = $goods_model->get_back_goods_list($field,'back_id = ' . $back_id);
	            foreach($get_back_goods_number as $value){
	                $wheren = "goods_id = '" . $value['goods_id'] . "' AND order_id = '" . $get_order_id . "'";
	                $datae['goods_number'] = $value['back_goods_number'];
	        		$order_model->update_order_goods_setDec($wheren,$datae);
	        		$order_model->update_order_goods(array('is_back'=>1),$wheren);

	        		$data1['is_back'] = 1;
	        		$order_model->update_order_goods($data1,$wheren);

	        		/*商品总金额、返利、返点修改*/
	                $tuikuan = $value['back_goods_number'] * $value['back_goods_price'];
	                $tui_fanpic = $value['back_goods_number'] * $value['back_goods_price'] * $level_bili;
	                $tui_yq_pic = $value['back_goods_number'] * $value['back_goods_price'] * $yaoqin_bili;
				    $wheree = "order_id = '" . $get_order_id . "'";           
				    $old_orderinfo = Model('order')->select_order_info_info('fanli_pic,fandian_yq_pic',$wheree);           
				    $fan_pic =  ($old_orderinfo['fanli_pic'] - $tui_fanpic > 0 ) ? ($old_orderinfo['fanli_pic'] - $tui_fanpic) : 0;
				    $fan_yq_pic =  ($old_orderinfo['fandian_yq_pic'] - $tui_yq_pic > 0 ) ? ($old_orderinfo['fandian_yq_pic'] - $tui_yq_pic) : 0;       
	                $fan_dec['fanli_pic'] = $fan_pic;
	                $fandian_dec['fandian_yq_pic'] = $fan_yq_pic;
	                $goods_amount_dec['goods_amount'] = $tuikuan;
	                $order_model->update_order_info_setDec($wheree,$fan_dec);
	                $order_model->update_order_info_setDec($wheree,$fandian_dec);
	                $result = $order_model->update_order_info_setDec($wheree,$goods_amount_dec);

	                /*退款完成后进行数据表中的返利和邀请人返利数据的修改*/
	                if($result){
	                    $whereo = " goods_id = '" . $value['goods_id'] . "' AND order_id = '" . $get_order_id . "'";
	                    $goods_info = $order_model->select_order_goods_info('goods_number,goods_price',$whereo);
	                    $fanpic = $goods_info['goods_price']*$goods_info['goods_number']*$level_bili;
	                    $fandian_yq_pic = $yaoqin_bili*$goods_info['goods_price']*$goods_info['goods_number'];
	                    $datao['fanli_pic'] = $fanpic;
	                    $datao['fandian_yq_pic'] = $fandian_yq_pic;
	                    $order_model->update_order_goods($datao,$whereo);
	                }
	            }
	            //退款完成后，进行返库
	            $back_type = $back_model->select_back_order_info('back_type',"back_id =" . $back_id)['back_type'];
	            $stock_dec_time = C('stock_dec_time');
	            if (in_array($back_type,array(1,4)) && $stock_dec_time == 1) {
	
	                foreach($get_back_goods_number as $back_g) {
	                    if ($back_g['product_id'] > 0) {	                        
	                        $datai['product_number'] = $back_g['back_goods_number'];
	        				Model('products')->update_products_setInc("product_id = " . $back_g['product_id'],$datai);
	                    }
	                    $dataj['goods_number'] = $back_g['back_goods_number'];
	        			Model('goods')->update_goods_setInc("goods_id = " . $back_g['goods_id'],$dataj);
	                    /*如果是典藏商品 让dc_show = 1;*/
	                    $res = Model('diancang')->select_capital_goods_info('capitalid',"goods_id = ".$back_g['goods_id'])['capitalid'];
	                    if(!empty($res)){
	                        Model('diancang')->update_capital_goods(array('dc_show'=>1),"goods_id = ".$back_g['goods_id']);
	                    }
	                }
	            }
	        }
	        if (count($get_goods_id) > 0) {
	            $goods_amount = Model('order')->select_order_info_info('goods_amount',"order_id = " .$get_order_id);
	            if($goods_amount['goods_amount'] == 0){
	                $datap['order_status'] = '2';
			        $order_model->update_order_info($datap,"order_id ='$get_order_id'");
	            }else{	                
	                $datau['order_status'] = '5';
			        $order_model->update_order_info($datau,"order_id ='$get_order_id'");
	            }
	        }
	    }
	    if($status_back =='2') {
	    	/*换出商品寄回*/	     
	        $wheres = "back_type in(1,2,3) and back_id='$back_id'";
	        $datas['status_back'] = $status_back;
	        $goods_model->update_back_goods($datas,$wheres);
	    }
	    if($status_refund=='1') {
	    	/*退款*/
	        $wheres = "back_type in(1,4)  and back_id='$back_id'";
	        $datas['status_refund'] = $status_refund;
	        $goods_model->update_back_goods($datas,$wheres);
	    }
	}

	/**
	 * @return 退换货操作记录
	 */
	private function back_action($back_id, $status_back, $status_refund,  $note = '', $username = null) {
	    if (is_null($username)){
	        $sess = $this->admin_info;
	        $admin_id = $sess['user_id'];
	        $where = array('user_id'=>$admin_id);
	        $admin_info = Model('admin')->select_admin_info('*',$where);
	        $username = $admin_info['user_name'];
	    }
    
	    $field = 'back_id,status_back,status_refund';
	    $where = "back_id = '$back_id'";
	    $data = Model('back_order')->select_back_order_info($field,$where);
	    $data['log_time'] = gmtime();
	    $data['action_user'] = $username;
	    $data['action_note'] = $note;
	    Model('back_order')->insert_back_action($data);

	    /**发送短信**/
	    if($status_back == 0){
	        /*获取订单id和user_id*/
	        $backOrder = Model('back_order')->select_back_order_info('user_id,order_id,back_type',"back_id=$back_id");
	        /*获取订单order_sn*/
	        $orderSn = Model('order')->select_order_info_info('order_sn','order_id ='.$backOrder['order_id'])['order_sn'];
	        $user_id = $backOrder['user_id'];
	        if(!empty($user_id)){
	            $users = Model('users')->select_users_info('user_money,mobile_phone','user_id ='.$user_id);
	            if(!empty($users['mobile_phone'])) {
	                /*退款（无需退货）*/	                
	                $param = array();
	                $param['good_pns'] = $orderSn;
	                $param['site_name']	= '淘玉商城';
	                if($backOrder['back_type'] == 1){
	                    $result = send_sms_msg('17550353616',"back_kaudi",$param);
	                }else{
	                    $result = send_sms_msg('17550353616','back_kaudi',$param);
	                }
	            }

	        }

	    }
	    if($status_back == 6){
	        /*获取订单id和user_id*/
	        $backOrder = Model('back_order')->select_back_order_info('user_id,order_id,back_type',"back_id=$back_id");
	        /*获取订单order_sn*/
	        $orderSn = Model('order')->select_order_info_info('order_sn','order_id ='.$backOrder['order_id'])['order_sn'];
	        $user_id = $backOrder['user_id'];
	        if(!empty($user_id)){
	           	$users = Model('users')->select_users_info('user_money,mobile_phone','user_id ='.$user_id);
	            if(!empty($users['mobile_phone'])) {	                
	                $param['tuihu_name'] = '属于订单号'.$orderSn.'的退款/退货申请';
	                $param['yuanying'] = $note;
	                $param['site_name']	= '淘玉商城';
	                $result = send_sms_msg($users['mobile_phone'],'tuihui_send',$param);
	            }

	        }
	    }

	}
}