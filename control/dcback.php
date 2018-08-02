<?php

/**
 * 淘玉php 后台典藏退货订单管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台典藏退货订单管理类
 * $Id: dcback.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class dcbackControl extends BaseControl {
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
     * @return 典藏退货订单列表
     */
    public function lists() {
    	/* 检查权限 */
        //admin_priv('dcbackor');
        $back_model = Model('diancang_back');
        /*分类统计*/
        $cntSort[0] = $back_model->get_capital_back_count('status_back = 5');//审核中
        $cntSort[1] = $back_model->get_capital_back_count('status_back = 0');//审核通过
        $cntSort[2] = $back_model->get_capital_back_count('status_back IN(6,7,8,9) AND status_refund = 0');//申请被取消
        $cntSort[3] = $back_model->get_capital_back_count('status_refund =0 AND status_back=1 ');//退款
        $cntSort[4] = $back_model->get_capital_back_count('1');//全部
        /* 查询退货单列表 */
        $result = $this->get_dcback_list();
        
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
        $sort_flag  = sort_flag($result['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);

        /* 显示模板 */
        Tpl::display('dcback_order_list.htm');
	}

	/**
     * @return 典藏退货订单列表排序、分页、查询
     */
    public function lists_query() {
        /* 检查权限 */
        //admin_priv('dcbackor');
        $back_model = Model('diancang_back');
        $result = $this->get_dcback_list();
        /*分类统计*/
        $cntSort[0] = $back_model->get_capital_back_count('status_back = 5');//审核中
        $cntSort[1] = $back_model->get_capital_back_count('status_back = 0');//审核通过
        $cntSort[2] = $back_model->get_capital_back_count('status_back IN(6,7,8,9) AND status_refund = 0');//申请被取消
        $cntSort[3] = $back_model->get_capital_back_count('status_refund =0 AND status_back=1 ');//退款
        $cntSort[4] = $back_model->get_capital_back_count('1');//全部
        /* 模板赋值 */
        Tpl::assign('cntSort', $cntSort);
        Tpl::assign('back_list',   $result['back']);
        Tpl::assign('filter',       $result['filter']);
        Tpl::assign('record_count', $result['record_count']);
        Tpl::assign('page_count',   $result['page_count']);
        $sort_flag  = sort_flag($result['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('dcback_order_list.htm'), '', 
            array('filter' => $result['filter'], 'page_count' => $result['page_count']));
	}

    /**
     * @return 查看典藏退货订单需详情
     */
    public function info() {
        /* 检查权限 */
        //admin_priv('dcbackor');
        $dcback_id = intval(trim($_REQUEST['dcback_id']));
        /* 根据发货单id查询发货单信息 */
        if (!empty($dcback_id)) {
            $dcback_order = $this->dcback_order_info($dcback_id);
        } else {
            die('order does not exist');
        }
        /* 取得区域名 */
        $dcback_order['region'] = get_province_city($dcback_order['province'],$dcback_order['city'],$dcback_order['district']);
        /*原订单商品信息*/
        $fields = 'goods_name,goods_sn,dc_goods_price,goods_image';
        $wh = 'capital_orderid = '.$dcback_order['capital_orderid'];
        $order_goods = Model('diancang_ordergoods')->get_capital_ordergoods_list($fields,$wh);
        if(!empty($order_goods)){
            foreach ($order_goods AS $key => $value){
                $order_goods[$key]['goods_url'] = get_imgurl_oss($value['goods_image'],30,30);
            }
        }       
        Tpl::assign('order_goods', $order_goods);

        /* 模板赋值 */
        Tpl::assign('dcback_order', $dcback_order);
        Tpl::assign('dcback_id', $dcback_id); // 发货单id

         /* 取得能执行的操作列表 */
        $operable_list = $this->operable_dcback_list($dcback_order);
        Tpl::assign('operable_list', $operable_list);

        /* 取得订单操作记录 */
        $act_list = array();
        $wheree = "back_id = '$dcback_id'";
        $oredrby = 'log_time DESC,action_id DESC';
        $res_act = Model('diancang_back')->get_capital_backaction_list('*',$wheree,$oredrby);
        if(!empty($res_act)){
            foreach ($res_act as $key => $row_act) {
                    $row_act['status_back']    = L('bos')[$row_act['status_back']];
                    $row_act['status_refund']  = L('bps')[$row_act['status_refund']];
                    $row_act['action_time']    = local_date(C('time_format'), $row_act['log_time']);
                    $act_list[] = $row_act;
            }
        }
        Tpl::assign('action_list', $act_list);
        /* 显示模板 */
        Tpl::assign('ur_here', L('back_operate') . L('detail'));
        Tpl::assign('action_link', array('text'=>'退货订单列表','href' => 'index.php?act=dcback&op=lists'));

        Tpl::display('diancang_dcback_info.htm');
    }

    /**
     * @return 删除典藏退货订单
     */
    public function remove() {
        /* 检查权限 */
        //admin_priv('dcbackor');
        if(isset($_REQUEST['dcback_id'])){
            $dcback_id = $_REQUEST['dcback_id'];
            $back_model = Model('diancang_back');
            $capital_orderid = $back_model->select_capital_back_info('capital_orderid',"back_id = '$dcback_id'");
            $where = 'capital_orderid = '.$capital_orderid['capital_orderid'];
            $result = $back_model->delete_capital_back($where);
            $data = array('order_status' => 5,'back_goods' => 0);
            $chengng = Model('diancang_order')->update_capital_order($data,$where);
            $param = array('is_back' => 0);
            $chengng = Model('diancang_ordergoods')->update_capital_ordergoods_info($param,$where);
        }else{
            die('order does not exist');
        }
    }

    /**
     * @return 典藏退货订单操作
     */
    public function operate() {
        $dcback_id = '';
        /* 检查权限 */
        //admin_priv('dcbackor');
        /*取得管理员信息*/
        $sess = $this->admin_info;
        $admin = $sess['user_name'];
        /* 取得订单id（可能是多个，多个sn）和操作备注（可能没有） */
        if(isset($_REQUEST['dcback_id'])){
            $dcback_id= $_REQUEST['dcback_id'];
            $dcback_into = $this->dcback_order_info($dcback_id);
        }else{
            die('order does not exist');
        }
        $backorder_finish = false;
        $action_note  = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';
        $userid = $dcback_into['user_id'];
        /*取得订单用户信息*/
        $field = 'user_name,alias,mobile_phone';    
        $usership =  Model('users')->select_users_info($field,"user_id = '$userid'");
        $user_names = empty($usership['alias']) ? $usership['user_name'] : $usership['alias'];
        $pay_timeds = local_date('Y-m-d H:i',gmtime());
        /*status_back:通过申请0:审核通过,1:收到寄回商品,2:换回商品已寄出,3:完成,4:退款/退货,5:审核中,6:申请被拒绝,7:管理员取消,8:用户自己取消,9.管理员终止退货流程*/
        if(!empty($action_note)){
            /* 确认 */
            if (isset($_POST['ok'])) {
                /*通过审核*/
                $arr['status_back']     = 0;
                $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                $this->back_dcaction($dcback_id,0,0,$action_note);
                $message['message_title'] = '还货收回本金提醒';
                $message['message_body'] = '亲爱的'.$user_names.'用户。你好！您的订单'.$dcback_into['order_sn'].'已于'.$pay_timeds.'系统通过还货申请在还货页面添加快递单号';
                $content = [
                    'title'=>'典藏退货确认',
                    'body'=>'您的订单:'.$dcback_into['order_sn'].', 已经确认还货请提交快递单号'
                ];
                $magstitle = "典藏退货确认";
                $magsmag = "![典藏退货确认](".$dcback_into['imgsurl1'].")<br/>操作人员".$admin.":<典藏退货确认><br/>产品编号：".$dcback_into['goods_sn'];
                $order_finish = true;
            } elseif (isset($_POST['no'])) {
                $arr['status_back']     = 6;//申请被拒绝6
                $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                $this->back_dcaction($dcback_id,6,0,$action_note);
                $message['message_title'] = '申请被拒绝';
                $message['message_body'] = '亲爱的'.$user_names.'用户。你好！您的订单'.$dcback_into['order_sn'].'已于'.$pay_timeds.'退货申请拒绝,原因：'.$action_note;
                $content = [
                    'title'=>'典藏退货申请拒绝',
                    'body'=>'您的订单:'.$dcback_into['order_sn'].', 典藏退货申请拒绝请到系统消息查看原因'
                ];
                $magstitle = "典藏退货拒绝";
                $magsmag = "![典藏退货拒绝](".$dcback_into['imgsurl1'].")<br/>操作人员".$admin.":<典藏退货拒绝><br/>产品编号：".$dcback_into['goods_sn'];
                $order_finish = true;
            } elseif (isset($_POST['confirm'])){//1:收到寄回商品
                $arr['status_back']     = 1;//收到寄回商品
                $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                $this->back_dcaction($dcback_id,1,0,$action_note);

                //add by wuyunlei 2018 01 16 收到寄回商品时修改商品库存以及是否已卖出字段
                $fields = 'capital_goods.goods_id,capital_goods.dc_names,capital_back.dcgoods_id';
                $goods_info = Model('diancang_back')->select_capital_back_goods_info($fields,"capital_back.back_id = '$dcback_id'");
                $updats = Model('goods')->update_goods_setInc('goods_id = '.$goods_info['goods_id'],array('goods_number'=>1));
                $updats = Model('diancang')->update_capital_goods(array('stats_buy'=>1),'capitalid = '.$goods_info['dcgoods_id']);
                /*收到寄回商品，发送短信，提醒对该商品添加看货提醒的用户可以看货*/
                $remind_info = Model('diancang_back')->get_cview_remind_list('*',"dc_goods_id = ".$goods_info['dcgoods_id']);
                foreach($remind_info as $value){
                    if(!empty($value['user_phone'])){
                        $param = array();
                        $param['site_name'] = '淘玉商城';
                        $param['goods_name'] = $goods_info['dc_names'];
                        $result = send_sms_msg($value['user_phone'],'view_goods_send',$param);
                    }
                }
                $message['message_title'] = '收到寄回商品';
                $message['message_body'] = '亲爱的'.$user_names.'用户。你好！您的订单'.$dcback_into['order_sn'].'已于'.$pay_timeds.'收到寄回商品,12小时内本金返还到余额中';
                $content = [
                    'title'=>'典藏收到寄回商品',
                    'body'=>'您的订单:'.$dcback_into['order_sn'].', 典藏收到寄回商品，等待本金返还中'
                ];
                $magstitle = "典藏收到寄回商品";
                $magsmag = "![典藏收到寄回商品](".$dcback_into['imgsurl1'].")<br/>操作人员".$admin.":<典藏收到寄回商品><br/>产品编号：".$dcback_into['goods_sn'];
                $order_finish = true;
            } elseif(isset($_POST['cancel_apply'])) {
                $arr['status_back']     = 7;//管理员取消7
                $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                $this->back_dcaction($dcback_id,7,0,$action_note);
                $order_finish = false;
            } elseif(isset($_POST['refund'])) {//3:完成
                $tui_pic = $dcback_into['refund_money'];
                $param['user_money'] = $tui_pic;
                if($dcback_into['status_back'] == 1 && $dcback_into['status_refund'] == 0 && $dcback_into['buy_sent'] == 0 ){
                    $chengng = Model('users')->update_users_setInc($param,"user_id = '$userid'");
                    if($chengng){
                        $change_des = '典藏订单'.$dcback_into['order_sn'].'退款成功,余额增加'.$tui_pic.'元';
                        $account_log = array(
                            'user_id'       => $userid,
                            'user_money'    => $tui_pic,
                            'taoyu_money'   => 0,
                            'frozen_money'  => 0,
                            'rank_points'   => 0,
                            'pay_points'    => 0,
                            'change_time'   => gmtime(),
                            'change_desc'   => $change_des,
                            'change_type'   => 12//典藏用12表示
                        );
                        $zhangsid = Model('accountlog')->insert_account_log($account_log);
                        $arr['status_back']     = 3;//3:完成
                        $arr['status_refund']     = 1;//已退款
                        $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                        $arr2['order_status']     = 4;//4:还货
                        $arr2['back_goods']     = 2;//完成还货
                        $orderjieguo = Model('diancang_order')->update_capital_order($arr2,"capital_orderid = ".$dcback_into['capital_orderid']);
                        $this->back_dcaction($dcback_id,3,1,$action_note);
                        $params = array('is_back' => 1);
                        $chengng = Model('diancang_ordergoods')->update_capital_ordergoods_info($params,"capital_orderid = ".$dcback_into['capital_orderid']);
                        $message['message_title'] = '还货完成本金返还到余额中';
                        $message['message_body'] = '亲爱的'.$user_names.'用户。你好！您的订单'.$dcback_into['order_sn'].'已于'.$pay_timeds.'收到寄回商品,12小时内本金返还到余额中';
                        $content = [
                            'title'=>'典藏还货完成本金返还余额',
                            'body'=>'您的订单:'.$dcback_into['order_sn'].', 典藏还货完成本金返还到余额中'
                        ];
                        $magstitle = "典藏还货完成";
                        $magsmag = "![典藏还货完成](".$dcback_into['imgsurl1'].")<br/>操作人员".$admin.":<典藏还货完成本金返还到余额中><br/>产品编号：".$dcback_into['goods_sn'];
                        $order_finish = true;
                    }else{
                        /* 退款失败请联系技术 */
                        $links[] = array('text' => '返回典藏退货订单详情', 'href' => 'index.php?act=dcback&op=info&dcback_id='.$dcback_id);
                        showMessage('退款失败请联系技术',$links);
                    }
                }else{
                    /* 本订单有没有完成的流程无法退本金 */
                    $links[] = array('text' => '返回典藏退货订单详情', 'href' => 'index.php?act=dcback&op=info&dcback_id='.$dcback_id);
                    showMessage('本订单有没有完成的流程无法退本金',$links);
                }
            }elseif(isset($_POST['backshipping'])){
                $arr['status_back']     = 2;//换回商品已寄出2
                $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                $this->back_dcaction($dcback_id,2,0,$action_note);
                $order_finish = false;
            }elseif(isset($_POST['backfinish'])){//完成退款订单但是不退钱
                if($dcback_into['status_refund'] == 1 && $dcback_into['status_back'] == 3 ){
                    /* 订单已经完成不用在点击 */
                    $links[] = array('text' => '返回订单详情', 'href' => 'index.php?act=dcback&op=info&dcback_id='.$dcback_id);
                    showMessage('订单已经完成不用在点击', $links);
                }else{
                    $arr['status_back']     = 3;//3:完成
                    $arr['status_refund']     = 1;//已退款
                    $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                    $arr2['order_status']     = 4;//4:还货
                    $arr2['back_goods']     = 2;//完成还货
                    $orderjieguo = Model('diancang_order')->update_capital_order($arr2,"capital_orderid = ".$dcback_into['capital_orderid']);
                    $this->back_dcaction($dcback_id,3,1,$action_note);
                    $chengng = Model('diancang_ordergoods')->update_capital_ordergoods_info(array('is_back'=>1),"capital_orderid = ".$dcback_into['capital_orderid']);
                    $order_finish = false;
                }
            }elseif(isset($_POST['recover_apply'])){//恢复待收货状态
                $arr['status_back']     = 2;//换回商品已寄出
                $jieguo = $this->update_dcbackorder($dcback_id, $arr);
                $this->back_dcaction($dcback_id,2,0,$action_note);
                $order_finish = false;
            }
        }else{
            /* 未添加备注 */
            $links[] = array('text' => '返回典藏退货订单详情', 'href' => 'index.php?act=dcback&op=info&dcback_id='.$dcback_id);
            showMessage('必须添加操作备注',$links);
        }

        /* 如果当前订单已经全部发货 */
        if ($order_finish) {
            /* 发推送通知2 */
            $cfg = C('send_ship_email');
            if ($cfg == '1'){
                $message['to_member_id'] = ','.$userid.',';
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($messageid)){
                    $platform = array("android", "ios");
                    //提交推送 查看是否符合条件推送
                    if(!empty($user_names)){
                         $res = send_jpush_message(1, $userid , $content,'',$platform,'');
                    }
                }
                /*发送钉钉*/
                $result =  send_ding_msg($magstitle,$magsmag,'qita');
            }
        }

        /* 清除缓存 */
        clear_cache_files();

        /* 操作成功 */
        $links[] = array('text' => '返回典藏退货订单详情', 'href' => 'index.php?act=dcback&op=info&dcback_id='.$dcback_id);
        $links[] = array('text' => '返回典藏退货订单列表', 'href' => 'index.php?act=dcback&op=lists');
        showMessage(L('act_ok'),$links);
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
     * @return 获取典藏退换货订单列表  
	 * @return array
	 */
    private function get_dcback_list() {
        $result = get_filter();
        $back_model = Model('diancang_back');
        if ($result === false) {
            $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;
            /* 过滤信息 */
            $filter['sort_index'] = empty($_REQUEST['sort_index']) ? -1 : trim($_REQUEST['sort_index']);
            $filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
            $filter['capital_orderid'] = empty($_REQUEST['capital_orderid']) ? 0 : intval($_REQUEST['capital_orderid']);
            $filter['status_refund'] = trim($_REQUEST['status_refund']);
            $filter['consignee'] = empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'back_id DESC, add_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $where = " capital_back.back_id > 0 ";
            if ($filter['order_sn']) {
                $where .= " AND capital_back.order_sn LIKE '%" . mysql_like_quote($filter['order_sn']) . "%'";
            }
            if ($filter['consignee']) {
                $where .= " AND capital_back.consignee LIKE '%" . mysql_like_quote($filter['consignee']) . "%'";
            }
            if ($filter['sort_index'] == -1) {
                if ($filter['status_refund'] == 0) {
                    $where .= " AND capital_back.status_back < 6 AND capital_back.status_refund = 0 ";
                }

                if ($filter['status_refund'] == 1) {
                    $where .= " AND capital_back.status_back = 3 AND capital_back.status_refund = 1 ";
                }

                if ($filter['status_refund'] == -1) {
                    $where .= "";
                }
                //0:审核通过,1:收到寄回商品,2:换回商品已寄出,3:完成,4:退款/退货,5:审核中,6:申请被拒绝,7:管理员取消,8:用户自己取消,9.管理员终止退货流程
            } else if (!empty($filter['sort_index']) && $filter['sort_index'] < 11) {//待商家审核
                $sortIndex = $filter['sort_index'] == '10' ? '0' : $filter['sort_index'];//审核通过带退回待收货
                $where .= " AND capital_back.status_back = {$sortIndex} ";
            } else if (!empty($filter['sort_index']) && $filter['sort_index'] == 40) {//待商家退款
                $where .= " AND capital_back.status_refund = 0 AND status_back=1  ";
            } else if (!empty($filter['sort_index']) && $filter['sort_index'] == 50) {//被取消申请
                $where .= " AND capital_back.status_back IN(6,7,8,9) AND capital_back.status_refund = 0 ";
            } else if (!empty($filter['sort_index']) && $filter['sort_index'] == 60) {//全部
                $where .= " ";
            }
            $getimts = gmtime();
            $last = strtotime("-1 month", $getimts);
            $last_lastday = date("Y-m-t", $last);//上个月最后一天
            $last_firstday = date('Y-m-01', $last);//上个月第一天
            //本月退回
            if (!empty($benyue_back)) {
                $where .= " AND capital_back.add_time >" . get_menyone($getimts) . " AND capital_back.add_time <" . get_menylast($getimts);
            }
            //上月退回
            if (!empty($shangyue_back)) {
                $where .= " AND capital_back.add_time >" . strtotime($last_firstday) . " AND capital_back.add_time <" . strtotime($last_lastday);
            }
            /* 记录总数 */
            $filter['record_count'] = $back_model->get_capital_back_count($where);
            /* 分页大小 */
            $filter = page_and_size($filter);
            /* 查询 */
            $sql = "SELECT capital_back.*,capital_order.order_amount,capital_order.look_goods,capital_order.goods_youpic,capital_order.consignee,capital_order.address,capital_ordergoods.goods_sn FROM " . 
                Model()->tablename("capital_back") . " AS capital_back " .
                " LEFT JOIN " . Model()->tablename('capital_order') . " AS capital_order ON capital_back.capital_orderid = capital_order.capital_orderid " .
                " LEFT JOIN " . Model()->tablename('capital_ordergoods') . " AS capital_ordergoods ON capital_order.capital_orderid = capital_ordergoods.capital_orderid " .
                " LEFT JOIN " . Model()->tablename('capital_goods') . " AS cg ON capital_ordergoods.dcgoods_id = cg.capitalid WHERE " . $where .
                " ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);

        /* 格式化数据 */
        if(!empty($row)){
            foreach ($row AS $key => $value) {
                $user_id = $value['user_id'];
                $userinto = Model('users')->select_users_info('alias,real_name,user_name',"user_id = '$user_id'");
                $row[$key]['add_time'] = local_date(C('time_format'), $value['add_time']);
                $row[$key]['refund_money'] = price_format($value['refund_money']);
                $row[$key]['goods_youpic'] = price_format($value['goods_youpic']);
                $row[$key]['order_amount'] = price_format($value['order_amount']);
                $row[$key]['goods_url'] = get_imgurl_oss($value['imgsurl'], 30, 30);
                $row[$key]['user_name'] = $userinto['alias'];
                if ($value['status_back'] == 0) {
                    $row[$key]['status_back_val'] = '通过审核等待邮寄商品';
                } else if ($value['status_back'] == 1) {
                    $row[$key]['status_back_val'] = '收到寄回商品等待退款';
                } else if ($value['status_back'] == 2) {
                    $row[$key]['status_back_val'] = '换回商品已寄出等待收货';
                } else if ($value['status_back'] == 3) {
                    if ($value['status_refund'] == 1) {
                        $row[$key]['status_back_val'] = '已完成退货流程';
                    } else {
                        $row[$key]['status_back_val'] = '已完成退货流程暂未退款';
                    }
                } else if ($value['status_back'] == 5) {
                    $row[$key]['status_back_val'] = '用户提交退货订单等待审核';
                } else if ($value['status_back'] == 6) {
                    $row[$key]['status_back_val'] = '申请被管理员拒绝';
                } else if ($value['status_back'] == 7) {
                    $row[$key]['status_back_val'] = '管理员取消退货流程';
                } else if ($value['status_back'] == 8) {
                    $row[$key]['status_back_val'] = '用户自己取消退货流程';
                }
            }
        }
        $arr = array('back' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * @return 获取典藏退换货订单信息
     * @param  int     $dcback_id   典藏退货 id（如果 back_id > 0 就按 id 查，否则按 sn 查）
     * @return array   退货单信息（金额都有相应格式化的字段，前缀是 formated_ ）
     */
    private function dcback_order_info($dcback_id) {
        $return_order = array();
        if (empty($dcback_id) || !is_numeric($dcback_id)){
            return $return_order;
        }
        /* 获取管理员信息 */
        $admin_info = $this->admin_info;
        $filed = " capital_back.*,capital_order.order_sn,capital_order.add_time AS order_time,capital_order.tel,capital_order.order_status,capital_order.look_goods,capital_order.consignee,
        capital_order.address,capital_order.shipping_name,capital_order.shipping_fee,capital_order.invoice_no AS order_nos,capital_order.shipping_time,capital_order.shipping_fee,
        capital_order.goods_amount,capital_order.goods_youpic,capital_order.order_amount,capital_order.surplus,capital_order.end_time,capital_order.back_time,capital_order.back_goods,capital_order.buy_sent,
        capital_order.province,capital_order.city,capital_order.district,
        capital_ordergoods.goods_sn,capital_ordergoods.goods_image ";
        $where = "back_id = '$dcback_id'";
        $back = Model('diancang_back')->get_capital_back_order_ordergoods_list($filed,$where);
        if(!empty($back)){
            $tui_order = time_adsu($back['end_time'],1,$back['back_time']);
            $user_ids = $back['user_id'];
            $fields = 'alias,real_name,user_name,mobile_phone';
            $wheres = "user_id = '$user_ids'";
            $userinto = Model('users')->select_users_info($fields,$wheres);
            $back['imgsurl'] = get_imgurl_oss($back['goods_image'],30,30);
            $back['imgsurl1'] = get_imgurl_oss($back['goods_image'],360,360);
            $back['add_time'] = local_date(C('time_format'), $back['add_time']);
            $back['order_time'] = local_date(C('time_format'), $back['order_time']);
            $back['look_end_time'] = local_date(C('time_format'), $back['shipping_time']+7*24*60*60);
            $back['shipping_time'] = local_date(C('time_format'), $back['shipping_time']);
            $back['new_end_time'] = local_date(C('time_format'), $back['end_time']);
            $back['new_end_time2'] = local_date(C('time_format'), $tui_order);
            $back['user_name'] = $userinto['alias'];
            $back['tel1'] = jiaMiPhone($back['tel']);
            $back['user_tel'] = $userinto['mobile_phone'];
            $back['user_tel1'] = jiaMiPhone($userinto['mobile_phone']);
            if($back['status_back'] == 0){
                 $back['status_back_val'] = '通过审核等待邮寄商品';
            }else if($back['status_back'] == 1){
                $back['status_back_val'] = '收到寄回商品等待退款';
            }else if($back['status_back'] == 2){
                $back['status_back_val'] = '换回商品已寄出等待收货';
            }else if($back['status_back'] == 3){
                if($back['status_refund'] == 1){
                    $back['status_back_val'] = '已完成退货流程';
                }else{
                    $back['status_back_val'] = '已完成退货流程暂未退款';
                }
            }else if($back['status_back'] == 5){
                $back['status_back_val'] = '用户提交退货订单等待审核';
            }else if($back['status_back'] == 6){
                $back['status_back_val'] = '申请被管理员拒绝';
            }else if($back['status_back'] == 7){
                $back['status_back_val'] = '管理员取消退货流程';
            }else if($back['status_back'] == 8){
                $back['status_back_val'] = '用户自己取消退货流程';
            }
            return $back;
        }else{
            return $return_order;
        }
    }

    /**
     * @return 返回某个订单可执行的操作列表
     * @return array
     * 0:审核通过,1:收到寄回商品,2:换回商品已寄出,3:完成,4:退款/退货,5:审核中,6:申请被拒绝,7:管理员取消,8:用户自己取消,9.管理员终止退货流程
     */
    private function operable_dcback_list($order) {
        $os = $order['status_back'];
        $ds = $order['status_refund'];
        /* 根据状态返回可执行操作 */
        $list = array(  
            'ok'           => true,
            'no'           => true,
            'confirm'      => true,
            'refund'       => true,
            'backshipping' => true,
            'backfinish'   => true 
        );
        if ($os != 5) {
            /*不在审核中*/
            $list['ok']=false;
            $list['no']=false;
        }
        if ($os == '1' || $os == '2' || $os == '3' || $ds == '1') {
            /*审核通过*/
            if($os == '1'){
                $list['confirm']=false;
            }

            if ($os=='2') {
                $list['backshipping']=false;
            }
            if ($os=='3') {
                $list['refund']=false;
                $list['backshipping']=false;
                $list['backfinish']=false;
            }
        }
        if($ds=='9' || $ds=='1') {
            $list['refund']=false;
        }
        return $list;
    }

    /**
     * 记录退款订单操作记录 2017/10/30 星期一
     * @param   string  $dcorder_id         典藏订单id
     * @param   integer $order_status       退款订单状态 0:审核通过,1:收到寄回商品,2:换回商品已寄出,3:完成,4:退款/退货,5:审核中,6:申请被拒绝,7:管理员取消,8:用户自己取消,9.管理员终止退货流程
     * @param   integer $shipping_status    退款结果 0:未退款,1:已退款
     * @param   string  $note               备注
     * @return  void
     */
    private function back_dcaction($dcback_id, $status_back, $status_refund, $note = '',$username = null) {
        if (is_null($username)) {
            $sess = $this->admin_info;
            $username = $sess['user_name'];
        }
        $data['back_id'] = $dcback_id;
        $data['action_user'] = $username;
        $data['status_back'] = $status_back;
        $data['status_refund'] = $status_refund;
        $data['action_note'] = $note;
        $data['log_time'] = gmtime();
        return Model('diancang_back')->insert_capital_backaction($data);  
    }

    /**
     * @return  修改退货订单
     * @param   int     $dcorder_id   典藏退款订单id
     * @param   array   $order      key => value
     * @return  bool
     */
    private function update_dcbackorder($dcback_id, $order){
        $where = "back_id = '$dcback_id'";
        return Model('diancang_back')->update_capital_back($order,$where);
    }

}
