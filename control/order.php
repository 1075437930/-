<?php

/**
 * 淘玉 后台订单管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萧瑟 $
 * 后台订单管理类
 * $Id: order.php  2018-04-07   萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class orderControl extends BaseControl {
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
     * @return 订单列表
     */
    public function lists() {
        /* 权限的判断 */
        admin_priv('order_view');
        /*入驻商列表*/
        if(intval($_REQUEST['supp']) > 0){
            $suppliers_list = Model('supplier')->get_supplier_list('*', 'status=1', 'supplier_name ASC');
            Tpl::assign('supp_list',   $suppliers_list);
        }
        Tpl::assign('ur_here',     L('order_lists'));
        Tpl::assign('action_link', array('text' => L('order_query'), 'href' => 'index.php?act=order&op=order_query'));
        /*订单列表*/
        $order_list = $this->get_order_info_list();
        /*支付渠道*/
        $payment_list = Model('payment')->get_payment_list('pay_id,pay_name','1');
        Tpl::assign('status_list', L('cs'));
        Tpl::assign('os_unconfirmed',   OS_UNCONFIRMED);
        Tpl::assign('cs_await_pay',     CS_AWAIT_PAY);
        Tpl::assign('cs_await_ship',    CS_AWAIT_SHIP);
        Tpl::assign('full_page',        1);
        Tpl::assign('is_rebate',check_authz('order_rebate') ? true : false);
        Tpl::assign('order_list',     $order_list['list']);
        Tpl::assign('filter',       $order_list['filter']);
        Tpl::assign('record_count', $order_list['record_count']);
        Tpl::assign('page_count',   $order_list['page_count']);
        Tpl::assign('payment_list',   $payment_list);
        $sort_flag  = sort_flag($order_list['filter']);
        Tpl::assign($sort_flag['tag'], $order_list['img']);
        Tpl::display('order_list.htm');
    }

    /**
     * @return 订单列表排序、分页、查询
     */
    public function lists_query() {
        /* 检查权限 */
        admin_priv('order_view');       
        if (!isset($_REQUEST['composite_status'])){
            $_REQUEST['composite_status'] = 0;
        }

        if(intval($_REQUEST['supp']) > 0){
            $suppliers_list = Model('supplier')->get_supplier_list('*', 'status=1', 'supplier_name ASC');
            Tpl::assign('supp_list',   $suppliers_list);
        }

        $order_list = $this->get_order_info_list();
        Tpl::assign('ur_here',     L('order_lists'));
        Tpl::assign('action_link', array('text' => L('order_query'), 'href' => 'index.php?act=order&op=order_query'));
        Tpl::assign('status_list', L('cs'));   // 订单状态
        Tpl::assign('os_unconfirmed',   OS_UNCONFIRMED);
        Tpl::assign('cs_await_pay',     CS_AWAIT_PAY);
        Tpl::assign('cs_await_ship',    CS_AWAIT_SHIP);
        Tpl::assign('is_rebate',check_authz('order_rebate') ? true : false);
        Tpl::assign('order_list',     $order_list['list']);
        Tpl::assign('filter',       $order_list['filter']);
        Tpl::assign('record_count', $order_list['record_count']);
        Tpl::assign('page_count',   $order_list['page_count']);
        $sort_flag  = sort_flag($order_list['filter']);
        Tpl::assign($sort_flag['tag'], $order_list['img']);
        make_json_result(Tpl::fetch('order_list.htm'), '',
            array('filter' => $order_list['filter'], 'page_count' => $order_list['page_count']));
    }

    /**
     * @return 订单删除（非批量移除，非订单详情移除，在订单列表操作中移除）
     */
    public function remove() {
        /* 检查权限 */
        admin_priv('order_edit');

        $order_id = intval($_REQUEST['id']);

        /* 检查权限 */
        check_authz_json('order_edit');

        /* 检查订单是否允许删除操作 */
        /* 查询订单信息 */
        $order_model = Model('order');
        $order = $order_model->select_order_info_info('*',"order_id=".$order_id);
        /* 检查能否操作 */
        $operable_list = $this->operable_list($order);
        if (!isset($operable_list['remove'])) {
            make_json_result('','1','Hacking attempt');
        }

        /*删除订单*/
        $res1 = $order_model->delete_order_info("order_id = '$order_id'");
        $res2 = $order_model->delete_order_goods("order_id = '$order_id'");
        $res3 = $order_model->delete_order_action("order_id = '$order_id'");
        $action_array = array('delivery', 'back');
        $res4 = $this->del_delivery($order_id, $action_array);
        if ($res1 && $res2 && $res3 && $res4 ) {
            /*记录日志 */
            admin_log($order['order_sn'], 'remove', 'order');
            $order_list = $this->get_order_info_list();
            Tpl::assign('status_list', L('cs'));   // 订单状态
            Tpl::assign('os_unconfirmed',   OS_UNCONFIRMED);
            Tpl::assign('cs_await_pay',     CS_AWAIT_PAY);
            Tpl::assign('cs_await_ship',    CS_AWAIT_SHIP);
            Tpl::assign('is_rebate',check_authz('order_rebate') ? true : false);
            Tpl::assign('order_list',     $order_list['list']);
            Tpl::assign('filter',       $order_list['filter']);
            Tpl::assign('record_count', $order_list['record_count']);
            Tpl::assign('page_count',   $order_list['page_count']);
            $sort_flag  = sort_flag($order_list['filter']);
            Tpl::assign($sort_flag['tag'], $order_list['img']);
            make_json_result(Tpl::fetch('order_list.htm'), '',
                array('filter' => $order_list['filter'], 'page_count' => $order_list['page_count']));
        } else {
            make_json_result('','1','删除订单失败');
        }
    }

    /**
     * @return 修改订单，显示页面
     */
    public function edit() {
        /* 检查权限 */
        admin_priv('order_edit');

        /* 取得参数 order_id */
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        Tpl::assign('order_id', $order_id);

        /* 取得参数 step */
        $step_list = array('user', 'goods', 'consignee', 'shipping', 'payment', 'other', 'money', 'invoice');
        $step = isset($_GET['step']) && in_array($_GET['step'], $step_list) ? $_GET['step'] : 'user';
        Tpl::assign('step', $step);

        /* 取得参数 op*/
        $op = $_GET['op'];
        Tpl::assign('ur_here', L('add_order'));
        Tpl::assign('step_op', $op);

        /* 取得订单信息 */
        if ($order_id > 0) {
            $order_model = Model('order');
            $order = $this->select_order_format_info($order_id,0);
            $order['tel1'] = $order['tel'];
            $order['mobile1'] = $order['mobile'];
            $order['tel'] = jiaMiPhone($order['tel']);
            $order['mobile'] = jiaMiPhone($order['mobile']);
            /* 发货单格式化 */
            $order['invoice_no'] = str_replace('<br>', ',', $order['invoice_no']);

            /* 如果已发货，不允许修改（配送方式和发货单号除外） */
            if ($order['shipping_status'] == SS_SHIPPED || $order['shipping_status'] == SS_RECEIVED) {
                if ($step != 'shipping') {
                    $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                    showMessage(L('cannot_edit_order_shipped',''), $link);
                } else {
                    $step = 'invoice';
                    Tpl::assign('step', $step);
                }
            }

            Tpl::assign('order', $order);
        } else {
            if ($op != 'add' || $step != 'user') {
                die('invalid params');
            }
        }
        if ('user' == $step) {
            // 无操作
        } elseif ('goods' == $step) {
            /*付款后的订单商品不能修改*/
            /*if($order['pay_status'] ==2){
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage('商品已经付款，不能修改', $link);
            }*/
            /*订单商品编辑页面*/
            Tpl::assign('ur_here', L('step')['goods']);
            /* 取得订单商品 */
            $goods_list = $this->get_order_goods($order);
            if (!empty($goods_list)) {
                foreach ($goods_list AS $key => $goods) {
                    /* 计算属性数 */
                    $attr = $goods['goods_attr'];
                    if ($attr == '') {
                        $goods_list[$key]['rows'] = 1;
                    } else {
                        $goods_list[$key]['rows'] = count(explode(chr(13), $attr));
                    }
                }
            }

            Tpl::assign('goods_list', $goods_list);

            /* 取得商品总金额 */
            Tpl::assign('goods_amount', $this->order_amount($order_id));
        } elseif ('consignee' == $step) {
            /*订单收货人编辑*/
            Tpl::assign('ur_here', L('step')['consignee']);
            Tpl::assign('exist_real_goods', 1);
            if ($order['user_id'] > 0) {
                Tpl::assign('address_list', Model('user_address')->get_user_address_list('*','user_id='.$order['user_id']));
                $address_id = isset($_REQUEST['address_id']) ? intval($_REQUEST['address_id']) : 0;
                if ($address_id > 0) {
                    $wherex = array('address_id'=>$address_id);
                    $address = Model('user_address')->select_user_address_info('*', $wherex)[0];
                    if ($address) {
                        $order['consignee'] = $address['consignee'];
                        $order['country'] = '1';
                        $order['province'] = $address['province'];
                        $order['city'] = $address['city'];
                        $order['district'] = $address['district'];
                        $order['address'] = $address['address'];
                        $order['zipcode'] = $address['zipcode'];
                        $order['tel'] = $address['tel'];
                        $order['mobile'] = $address['mobile'];
                        $order['sign_building'] = $address['sign_building'];
                        $order['best_time'] = $address['best_time'];
                        Tpl::assign('order', $order);
                    }
                }
            }
            Tpl::assign('country_list', get_regions(0,0));
            /* 取得省份 */
            Tpl::assign('province_list', get_regions(1,1));
            if ($order['province'] > 0) {
                /* 取得城市 */
                Tpl::assign('city_list', get_regions(2, $order['province']));
                if ($order['city'] > 0) {
                    /* 取得区域 */
                    Tpl::assign('district_list', get_regions(3, $order['city']));
                }
            }
        } elseif ('money' == $step) {
            Tpl::assign('ur_here', L('step')['money']);
            Tpl::assign('exist_real_goods', 1);
            /* 取得用户信息 */
            if ($order['user_id'] > 0) {
                $user = Model('users')->select_users_info('user_money,taoyu_money,pay_points','user_id='.$order['user_id']);
                /* 计算可用余额 */
                Tpl::assign('available_user_money', $order['surplus'] + $user['user_money']);
                Tpl::assign('available_taoyu_money', $user['taoyu_money']/10);
                /* 计算可用积分 */
                Tpl::assign('available_pay_points', $order['integral'] + $user['pay_points']);
            }
        }elseif ('other' == $step) {
            Tpl::assign('ur_here', L('step')['other']);
        }

        Tpl::display('order_step.htm');
    }

    /**
     * @return 修改订单，处理提交数据
     */
    public function update() {
        /* 检查权限 */
        admin_priv('order_edit');

        /* 取得参数 step */
        $step_list = array('user', 'edit_goods', 'add_goods', 'goods', 'consignee', 'shipping', 'payment', 'other', 'money', 'invoice');
        $step = isset($_REQUEST['step']) && in_array($_REQUEST['step'], $step_list) ? $_REQUEST['step'] : 'user';


        /* 取得参数 order_id */
        $order_id = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;
        if ($order_id > 0) {
            $old_order = $this->select_order_format_info($order_id);
        }

        /* 取得参数 step_op 添加还是编辑 */
        $step_op = isset($_REQUEST['step_op']) ? $_REQUEST['step_op'] : 'add';
        if ('user' == $step) {
            exit;
        } elseif ('edit_goods' == $step) {
        	/* 修改订单商品信息 */
            if (isset($_POST['rec_id'])) {
                $new_order = $this->select_order_format_info($order_id);
                if ($new_order['user_id']) {
                    $level_id = Model('users')->select_users_info('level_id',"user_id = " . $new_order['user_id'])['level_id'];
                    $level_bili = Model('user_level')->select_user_level_info('level_bili',"level_id = $level_id")['level_bili'];
                }

                foreach ($_POST['rec_id'] AS $key => $rec_id) {
                    $sql = "select order_goods.goods_id,order_goods.goods_attr_id,order_goods.exclusive,order_info.supplier_id " .
                        " from " . Model()->tablename('order_goods') . " as order_goods left join " . Model()->tablename('order_info') .
                        " as order_info on order_goods.order_id=order_info.order_id where order_goods.rec_id=" . $rec_id;
                    /* 取得参数 */
                    $goods_price = floatval($_POST['goods_price'][$key]);
                    $fanli_pic_goods = $goods_price * $level_bili;
                    /*接收数据*/
                    $exclusive = floatval($_POST['exclusive'][$key]);
                    $goods_number = intval($_POST['goods_number'][$key]);
                    $goods_attr = $_POST['goods_attr'][$key];
                    $product_id = intval($_POST['product_id'][$key]);
                    /*查询库存*/
                    if ($product_id) {
                        $where = "product_id =" . $_POST['product_id'][$key];
                        $goods_number_all = Model('products')->select_products_info('product_number',$where);
                    }
                    $where = "rec_id=" . $rec_id;
                    $goods_info = Model('order')->select_order_goods_info('goods_id,goods_number',$where);
                    $where = "goods_id=" . $goods_info['goods_id'];
                    $goods_number_all = Model('goods')->select_goods_info('goods_number',$where)['goods_number'];
                    if (($goods_number_all+$goods_info['goods_number']) >= $goods_number) {
                        /* 修改订单商品信息 */
                        $data['goods_price'] = $goods_price;
                        $data['goods_pay_price'] = $goods_price;
                        $data['exclusive'] = $exclusive;
                        $data['fanli_pic'] = $fanli_pic_goods;
                        $data['goods_number'] = $goods_number;
                        $data['goods_attr'] = $goods_attr;
                        $where = "rec_id = '$rec_id' LIMIT 1";
                        Model('order')->update_order_goods($data,$where);
                    } else {
                        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            			showMessage(L('goods_num_err',''), $link);
                    }
                }
	            $goods_amount = $this->order_amount($order_id);
	            if ($level_bili != 0) {
	                $fanli_pic = $goods_amount * $level_bili;
	            } else {
	                $fanli_pic = 0;
	            }
	            $conf = C('yqfanli');
	            if (!empty($conf)) {
	                $fanli_yq_pic = $goods_amount * (C('yqfanli'));
	            }
	            /* 更新商品总金额和订单总金额 */
                $this->update_order($order_id, array('goods_amount' => $goods_amount, 'fanli_pic' => $fanli_pic, 'fandian_yq_pic' => $fanli_yq_pic));
	            $this->update_order_amount($order_id);

	            /* 更新 pay_log */
	            $this->update_pay_log($order_id);
	            /* 记录日志 */
	            $sn = $old_order['order_sn'];

	            if ($old_order['total_fee'] != $new_order['total_fee']) {
	                $sn .= ',' . sprintf(L('order_amount_change'), $old_order['total_fee'], $new_order['total_fee']);
	            }
	            admin_log($sn, 'edit', 'order');
			}
			/* 跳回订单商品 */
	        ecs_header("Location: index.php?act=order&op=" . $step_op . "&order_id=" . $order_id . "&step=goods\n");
	        exit;
        } elseif ('goods' == $step) {
            /* 修改订单商品信息后将订单商品信息同步到订单详情 */
            if (isset($_POST['next'])) {
                ecs_header("Location: index.php?act=order&op=" . $step_op . "&order_id=" . $order_id . "&step=consignee\n");
                exit;
            } elseif (isset($_POST['finish'])) {
                /* 完成 */
                /* 初始化提示信息和链接 */
                $msgs = array();
                $links = array();

                /* 如果已付款，检查金额是否变动，并执行相应操作 */
                $order = $this->select_order_format_info($order_id);
                $this->handle_order_money_change($order, $msgs, $links);

                /* 显示提示信息 */
                if (!empty($msgs)) {
                    showMessage(join(chr(13), $msgs), $links);
                } else {
                    /* 跳转到订单详情 */
                    ecs_header("Location: index.php?act=order&op=info&order_id=" . $order_id . "\n");
                    exit;
                }
            }
        } elseif ('other' == $step) {
	        /* 保存订单 */
	        $order = array();
	        if (isset($_POST['pack']) && $_POST['pack'] > 0) {
	            $pack = pack_info($_POST['pack']);
	            $order['pack_id'] = $pack['pack_id'];
	            $order['pack_name'] = addslashes($pack['pack_name']);
	            $order['pack_fee'] = $pack['pack_fee'];
	        } else {
	            $order['pack_id'] = 0;
	            $order['pack_name'] = '';
	            $order['pack_fee'] = 0;
	        }
	        if (isset($_POST['card']) && $_POST['card'] > 0) {
	            $card = card_info($_POST['card']);
	            $order['card_id'] = $card['card_id'];
	            $order['card_name'] = addslashes($card['card_name']);
	            $order['card_fee'] = $card['card_fee'];
	            $order['card_message'] = $_POST['card_message'];
	        } else {
	            $order['card_id'] = 0;
	            $order['card_name'] = '';
	            $order['card_fee'] = 0;
	            $order['card_message'] = '';
	        }
	        $order['inv_content'] = $_POST['inv_content'];
	        $order['how_oos'] = $_POST['how_oos'];
	        $order['postscript'] = $_POST['postscript'];
	        $order['to_buyer'] = $_POST['to_buyer'];
	        $this->update_order($order_id, $order);
            $this->update_order_amount($order_id);

	        /* 更新 pay_log */
	        $this->update_pay_log($order_id);

	        /* 记录日志 */
	        $sn = $old_order['order_sn'];
	        admin_log($sn, 'edit', 'order');

	        if (isset($_POST['next'])) {
	            /* 下一步 */
	            ecs_header("Location: index.php?act=order&op=" . $step_op. "&order_id=" . $order_id . "&step=money\n");
	            exit;
	        } elseif (isset($_POST['finish'])) {
	            /* 完成 */
	            ecs_header("Location: index.php?act=order&op=info&order_id=" . $order_id . "\n");
	            exit;
	        }
	    } elseif ('consignee' == $step) {
	        /* 保存订单收货人信息 */
	        $order = $_POST;
	        unset($order['finish']);
            $this->update_order($order_id, $order);
	        /* 记录日志 */
	        $sn = $old_order['order_sn'];
	        admin_log($sn, 'edit', 'order');
	        ecs_header("Location: index.php?act=order&op=info&order_id=" . $order_id . "\n");	        
	    } elseif ('money' == $step) {
            $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            /* 取得订单信息 */
            $old_order = $this->select_order_format_info($order_id);
            /* 取得用户信息 */
            if ($old_order['user_id'] > 0) {                
                $user = user_info($old_order['user_id']);
            }
            /* 保存信息 */
            $order['goods_amount'] = $old_order['goods_amount'];
            $order['discount'] = isset($_POST['discount']) && floatval($_POST['discount']) >= 0 ? round(floatval($_POST['discount']), 2) : 0;
            $order['tax'] = round(floatval($_POST['tax']), 2);
            $order['shipping_fee'] = isset($_POST['shipping_fee']) && floatval($_POST['shipping_fee']) >= 0 ? round(floatval($_POST['shipping_fee']), 2) : 0;
            $order['insure_fee'] = isset($_POST['insure_fee']) && floatval($_POST['insure_fee']) >= 0 ? round(floatval($_POST['insure_fee']), 2) : 0;
            $order['pay_fee'] = floatval($_POST['pay_fee']) >= 0 ? round(floatval($_POST['pay_fee']), 2) : 0;
            $order['pack_fee'] = isset($_POST['pack_fee']) && floatval($_POST['pack_fee']) >= 0 ? round(floatval($_POST['pack_fee']), 2) : 0;
            $order['card_fee'] = isset($_POST['card_fee']) && floatval($_POST['card_fee']) >= 0 ? round(floatval($_POST['card_fee']), 2) : 0;
            $order['money_paid'] = $old_order['money_paid'];
            /* 计算待付款金额 */
            $order['order_amount'] = $order['goods_amount'] - $order['discount']
                + $order['tax']
                + $order['shipping_fee']
                + $order['insure_fee']
                + $order['pay_fee']
                + $order['pack_fee']
                + $order['card_fee']
                - $order['money_paid'];
            /* 更新订单*/    
            $this->update_order($order_id, $order);
            /* 更新 pay_log */
            $this->update_pay_log($order_id);
            /* todo 记录日志 */
            $sn = $old_order['order_sn'];
            $new_order = $this->select_order_format_info($order_id);
            if ($old_order['total_fee'] != $new_order['total_fee']) {
                $sn .= ',' . sprintf(L('order_amount_change'), $old_order['total_fee'], $new_order['total_fee']);
            }
            admin_log($sn, 'edit', 'order');

            if (isset($_POST['finish'])) {
                /* 完成 */
                if ($step_act == 'add') {
                    /* 订单改为已确认，（已付款） */
                    $arr['order_status'] = OS_CONFIRMED;
                    $arr['confirm_time'] = gmtime();
                    if ($order['order_amount'] <= 0) {
                        $arr['pay_status'] = PS_PAYED;
                        $arr['pay_time'] = gmtime();
                    }
                    $this->update_order($order_id, $arr);
                }
                /* 如果已付款，检查金额是否变动，并执行相应操作 */
                $order = $this->select_order_format_info($order_id);
                $this->handle_order_money_change($order, $msgs, $links);
                ecs_header("Location: index.php?act=order&op=info&order_id=" . $order_id . "\n");
                exit;
            }
             
        }

    }

    /**
     * @return 订单查询
     */
    public function order_query() {
        /* 检查权限 */
        admin_priv('order_view');
        $shiplist = Model('shipping')->get_shipping_list();
        /* 载入配送方式 */
        Tpl::assign('shipping_list', $shiplist);
        /* 载入支付方式 */
        Tpl::assign('pay_list', Model('payment')->get_payment_list('*','enabled = 1','pay_order'));
        /* 载入国家 */
        Tpl::assign('country_list', Model('region')->get_regoin_list());
        /* 载入订单状态、付款状态、发货状态 */
        Tpl::assign('os_list', $this->get_status_list('order'));
        Tpl::assign('ps_list', $this->get_status_list('payment'));
        Tpl::assign('ss_list', $this->get_status_list('shipping'));
        /* 模板赋值 */
        Tpl::assign('ur_here', L('order_query'));
        Tpl::assign('action_link', array('href' => 'index.php?act=order&op=lists', 'text' => L('order_lists')));
        /* 显示模板 */
        Tpl::display('order_query.htm');
    }

    /**
     * @return 删除订单商品
     */
    public function remove_order_goods() {
    	/* 编辑订单商品信息时删除订单商品 */
	    /* 检查权限 */
	    admin_priv('order_edit');

	    /* 取得参数 */
	    $rec_id = intval($_GET['rec_id']);
	    $step_op = $_GET['step_op'];
	    $order_id = intval($_GET['order_id']);

	    /* 如果使用库存，且下订单时减库存，则修改库存 */
	    if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE) {
	        $goods = Model('order')->select_order_goods_info('goods_id, goods_number',"rec_id = " . $rec_id);	          
	        $where = "goods_id = '" . $goods['goods_id'] . "' LIMIT 1";
	        //var_dump($where);
	        $data['goods_number'] = $goods['goods_number'];
	        Model('goods')->update_goods_setInc($where,$data);
	    }

	    /* 删除订单商品 */
	    Model('order')->delete_order_goods("rec_id = '$rec_id'");

	    /* 更新商品总金额和订单总金额 */
	    /*查询用户信息*/
	    $new_order = $this->select_order_format_info($order_id);
        if ($new_order['user_id']) {
            $level_id = Model('users')->select_users_info('level_id',"user_id = " . $new_order['user_id'])['level_id'];
            $level_bili = Model('user_level')->select_user_level_info('level_bili',"level_id = $level_id")['level_bili'];
        }
	    $goods_amount = $this->order_amount($order_id);
	    if ($level_bili != 0) {
	        $fanli_pic = $goods_amount * $level_bili;
	    } else {
	        $fanli_pic = 0;
	    }
	    $conf = C('yqfanli');
	    if (!empty($conf)) {
	        $fanli_yq_pic = $goods_amount * (C('yqfanli'));
	    }
	    /* 更新商品总金额和订单总金额 */
        $this->update_order($order_id, array('goods_amount' => $goods_amount, 'fanli_pic' => $fanli_pic, 'fandian_yq_pic' => $fanli_yq_pic));
        $this->update_order_amount($order_id);

	    /* 跳回订单商品 */
	    ecs_header("Location: index.php?act=order&op=" . $step_op . "&order_id=" . $order_id . "&step=goods\n");
	    exit;
    }

    /**
     * @return 查看订单详情
     */
    public function info() {
        $model = Model('order');
        $order = $this->get_order_info();
        Tpl::assign('result_content',$order['result_content']);
        /* 根据订单是否完成检查权限 */
        if ($this->order_finished($order)){
            admin_priv('order_view_finished');
        }else{
            admin_priv('order_view');
        }
        Tpl::assign('prev_id', $order['prev_id']);
        Tpl::assign('next_id', $order['next_id']);
        /* 取得用户名 */
        if ($order['user_id'] > 0){
            $user = user_info($order['user_id']);
            if (!empty($user)){
                $order['user_name'] = $user['alias'];
            }
        }
        /* 取得所有办事处 */
        Tpl::assign('agency_list', $model->get_agency_list('*','1'));
        /* 取得区域名 */
        $order['region'] = get_province_city($order['province'],$order['city'],$order['district']);
        Tpl::assign('order', $order);

        /* 取得用户信息 */
        if ($order['user_id'] > 0){
            /* 用户等级 */
            if ($user['user_rank'] > 0){
                $where = "rank_id = '$user[user_rank]' ";
            }else{
                $where = "min_points <= " . intval($user['rank_points']) . " ORDER BY min_points DESC ";
            }
            $user_model = Model('users');
            $user['rank_name'] = $user_model->select_user_rank_info('rank_name',$where);
            Tpl::assign('user', $user);
            /*地址信息*/
            $wherex = array('user_id'=>$order['user_id']);
            $address_info = Model('user_address')->get_user_address_list('*', $wherex);
            Tpl::assign('address_list', $address_info);
        }
        /* 取得订单商品及货品 */
        $goods_list = array();
        $goods_attr = array();
        $where = "order_goods.order_id = ".$order['order_id'];
        $field = "order_goods.*, IF(order_goods.product_id > 0, products.product_number, goods.goods_number) 
		AS storage, order_goods.goods_attr, goods.suppliers_id, IFNULL(brand.brand_name, '') 
		AS brand_name, products.product_sn";
        $res = $model->get_order_goods_products_goods_brand_list($field,$where);
        foreach($res as $key => $row){
            /* 手机专享价格*/
            if($row['exclusive'] == '-1'){
                $row['formated_subtotal']       = price_format($row['goods_price'] * $row['goods_number']);
                $row['formated_goods_price']    = price_format($row['goods_price']);
                $row['exclusive'] = "没有手机专享价格";
            }else{
                if($row['exclusive'] > $row['goods_price']){
                    $row['formated_subtotal']       = price_format($row['goods_price'] * $row['goods_number']);
                    $row['formated_goods_price']    = price_format($row['goods_price']);
                    $row['exclusive'] = "没有手机专享价格";
                }else{
                    $row['formated_subtotal']       = price_format($row['exclusive'] * $row['goods_number']);
                    $row['formated_goods_price']    = price_format($row['goods_price']);
                    $row['exclusive'] = price_format($row['exclusive']);
                }
            }
            $where = "back_order.order_id = " . $order['order_id'] .
                " AND back_goods.goods_id = " . $row['goods_id'] .
                " AND back_goods.product_id = " . $row['product_id'] .
                " AND back_goods.status_back < 6";
            $field = "back_goods.*,back_order.back_type";
            $back_info = Model('back_goods')->get_back_goods_back_order_list($field,$where);
            if (count($back_info['back_id']) > 0){
                switch ($back_info['status_back']){
                    case '3' : $sb = "已完成"; break;
                    case '5' : $sb = "已申请"; break;
                    default : $sb = "正在"; break;
                }
                switch ($back_info['back_type']){
                    case '1' : $bt = "退货"; break;
                    case '3' : $bt = "申请维修"; break;
                    case '4' : $bt = "退款"; break;
                    default : break;
                }
                $shouhou = $sb." ".$bt;
            }else{
                $shouhou = "正常";
            }
            $goods_attr[] = explode(' ', trim($row['goods_attr'])); /*将商品属性拆分为一个数组*/
            $row['shouhou'] = $shouhou;
            $goods_list[] = $row;
            $goods_list[$key]['goods_url'] = WEB_PATH.'goods.php?id='.$row['goods_id'];
        }
        $attr = array();
        $arr  = array();
        foreach ($goods_attr AS $index => $array_val){
            foreach ($array_val AS $value){
                $arr = explode(':', $value);/*以 : 号将属性拆开*/
                $attr[$index][] =  @array('name' => $arr[0], 'value' => $arr[1]);
            }
        }
        Tpl::assign('goods_attr', $attr);
        Tpl::assign('goods_list', $goods_list);
        /* 取得能执行的操作列表 */
        $operable_list = $this->operable_list($order);
        Tpl::assign('operable_list', $operable_list);
        /* 取得订单操作记录 */
        $act_list = array();
        $orderby = 'log_time DESC,action_id DESC';
        $where = 'order_id = ' .$order['order_id'];
        $res = $model->get_order_action_list('*',$where,$orderby);
        foreach ($res as $key => $row) {
            $row['order_status']    = L('os')[$row['order_status']];
            $row['pay_status']      = L('ps')[$row['pay_status']];
            $row['shipping_status'] = L('ss')[$row['shipping_status']];
            $row['action_time']     = local_date(C('time_format'), $row['log_time']);
            $act_list[] = $row;
        }
        Tpl::assign('action_list', $act_list);
        if (isset($_GET['print'])){
            /*打印订单 */
            Tpl::assign('shop_name', C('shop_name'));
            Tpl::assign('shop_url', WEB_PATH);
            Tpl::assign('shop_address', C('shop_address'));
            Tpl::assign('service_phone', C('service_phone'));
            Tpl::assign('print_time', local_date(C('time_format')));
            $sess = $this->admin_info;
            Tpl::assign('action_user',$sess['user_name']);
            Tpl::display('order_print.htm');
        }elseif(isset($_GET['print_edit'])){
            /*编辑供货单*/
            /* 模板赋值 */
            Tpl::assign('ur_here', '供货单打印');
            /* 供货单编辑*/
            Tpl::display('order_print_edit.htm');
        }elseif(isset($_GET['print_goodsin'])){
            /*打印供货单*/
            $supplierName = $_GET['supplier_name'];
            $startTime = $_GET['start_time'];
            $costPayValue = $_GET['cost_pay'];
            if(empty($supplierName)||empty($startTime)||empty($costPayValue)){
                exit('参数输入错误');
            }
            Tpl::assign('supplier_name_edit',   $supplierName);
            Tpl::assign('balance_time_edit',  $startTime);
            Tpl::assign('cost_pay_edit',   price_format($costPayValue));
            Tpl::assign('format_sub_edit',  price_format($order['money_paid']-$costPayValue));
            Tpl::assign('print_time',   local_date(C('time_format')));
            $sess = $this->admin_info;
            $admin_name = $sess['user_name'];
            Tpl::assign('action_user', $admin_name);
            /* 显示模板 */
            Tpl::display('order_print_goods_in.htm');
        }elseif(isset($_GET['shipping_print'])){
            /*打印快递单*/
            //发货地址所在地
            $region_array = array();
            $shop_country = C('shop_country');
            $shop_province = C('shop_province');
            $shop_city = C('shop_city');
            $shop_district = C('shop_district');
            $region_id = !empty($shop_country) ? C('shop_country') . ',' : '';
            $region_id .= !empty($shop_province) ? C('shop_province') . ',' : '';
            $region_id .= !empty($shop_city) ? C('shop_city') . ',' : '';
            $region_id .= !empty($shop_district) ? C('shop_district') . ',' : '';
            $region_id .= !empty($order['country']) ? $order['country'] . ',' : '';
            $region_id .= !empty($order['province']) ? $order['province'] . ',' : '';
            $region_id .= !empty($order['city']) ? $order['city'] . ',' : '';
            $region_id .= !empty($order['district']) ? $order['district'] . ',' : '';
            $region_id = substr($region_id, 0, -1);
            $region =Model('region')->get_regoin_list('*',"region_id IN ($region_id)");
            if (!empty($region)){
                foreach($region as $region_data){
                    $region_array[$region_data['region_id']] = $region_data['region_name'];
                }
            }
            Tpl::assign('shop_name',    C('shop_name'));
            Tpl::assign('order_id',    $_REQUEST['order_id']);
            Tpl::assign('province', $region_array[C('shop_province')]);
            Tpl::assign('city', $region_array[C('shop_city')]);
            Tpl::assign('shop_address', C('shop_address'));
            Tpl::assign('service_phone',C('service_phone'));
            $shipping = Model('shipping')->select_shipping_info('*',"shipping_id = " . $order['shipping_id'],'','');
            //打印单模式
            if ($shipping['print_model'] == 2){
                /* 取快递单背景宽高 */
                if (!empty($shipping['print_bg'])){
                    $_size = @getimagesize($shipping['print_bg']);
                    if ($_size != false){
                        $shipping['print_bg_size'] = array('width' => $_size[0], 'height' => $_size[1]);
                    }
                }

                if (empty($shipping['print_bg_size'])){
                    $shipping['print_bg_size'] = array('width' => '1024', 'height' => '600');
                }

                /* 标签信息 */
                $lable_box = array();
                $lable_box['t_shop_country'] = $region_array[C('shop_country')]; //网店-国家
                $lable_box['t_shop_city'] = $region_array[C('shop_city')]; //网店-城市
                $lable_box['t_shop_province'] = $region_array[C('shop_province')]; //网店-省份
                $lable_box['t_shop_name'] = C('shop_name'); //网店-名称
                $lable_box['t_shop_district'] = $region_array[C('shop_district')]; //网店-区/县
                $lable_box['t_shop_tel'] = C('service_phone'); //网店-联系电话
                $lable_box['t_shop_address'] = C('shop_address'); //网店-地址
                $lable_box['t_customer_country'] = $region_array[$order['country']]; //收件人-国家
                $lable_box['t_customer_province'] = $region_array[$order['province']]; //收件人-省份
                $lable_box['t_customer_city'] = $region_array[$order['city']]; //收件人-城市
                $lable_box['t_customer_district'] = $region_array[$order['district']]; //收件人-区/县
                $lable_box['t_customer_tel'] = $order['tel']; //收件人-电话
                $lable_box['t_customer_mobel'] = $order['mobile']; //收件人-手机
                $lable_box['t_customer_post'] = $order['zipcode']; //收件人-邮编
                $lable_box['t_customer_address'] = $order['address']; //收件人-详细地址
                $lable_box['t_customer_name'] = $order['consignee']; //收件人-姓名
                $gmtime_utc_temp = gmtime(); //获取 UTC 时间戳
                $lable_box['t_year'] = date('Y', $gmtime_utc_temp); //年-当日日期
                $lable_box['t_months'] = date('m', $gmtime_utc_temp); //月-当日日期
                $lable_box['t_day'] = date('d', $gmtime_utc_temp); //日-当日日期
                $lable_box['t_order_no'] = $order['order_sn']; //订单号-订单
                $lable_box['t_order_postscript'] = $order['postscript']; //备注-订单
                $lable_box['t_order_best_time'] = $order['best_time']; //送货时间-订单
                $lable_box['t_pigeon'] = '√'; //√-对号
                $lable_box['t_custom_content'] = ''; //自定义内容
                //标签替换
                $temp_config_lable = explode('||,||', $shipping['config_lable']);
                if (!is_array($temp_config_lable)){
                    $temp_config_lable[] = $shipping['config_lable'];
                }
                foreach ($temp_config_lable as $temp_key => $temp_lable){
                    $temp_info = explode(',', $temp_lable);
                    if (is_array($temp_info)){
                        $temp_info[1] = $lable_box[$temp_info[0]];
                    }
                    $temp_config_lable[$temp_key] = implode(',', $temp_info);
                }
                $shipping['config_lable'] = implode('||,||',  $temp_config_lable);
                Tpl::assign('shipping', $shipping);
                Tpl::display('print.htm');
            }
        }else{
            /* 模板赋值 */
            Tpl::assign('ur_here', L('order_info'));
            Tpl::assign('action_link', array('href' => 'index.php?act=order&op=lists', 'text' => L('order_lists')));
            $model = Model('order');
            $where = "order_id = " . $_REQUEST['order_id'];
            $tuihuo_info = Model('back_order')->select_back_order_info('status_back',$where);
            Tpl::assign('shenhe', $tuihuo_info['status_back']);
            Tpl::display('order_info.htm');
        }
    }

    /**
     * @return 操作订单状态,判断操作类型
     */
    public function operate(){
        $order_id = '';
        /* 检查权限 */
        admin_priv('order_os_edit');
        /* 取得订单id（可能是多个，多个sn）和操作备注（可能没有） */
        if(isset($_REQUEST['order_id'])){
            $order_id= $_REQUEST['order_id'];
        }
        /*是否批量处理*/
        $batch = isset($_REQUEST['batch']);
        $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';
        /* 确认订单操作 */
        if (isset($_POST['confirm'])){
            /* 确认订单操作 */
            $require_note   = true;
            $action = L('op_confirm');
            $operation = 'confirm';
        } elseif (isset($_POST['pay'])){
            /* 付款 */
            /* 检查权限 */
            admin_priv('order_ps_edit');
            $require_note   = C('order_pay_note') == 1;
            $action         = L('op_pay');
            $operation      = 'pay';
        }elseif (isset($_POST['unpay'])) {
            /* 未付款 */
            /* 检查权限 */
            admin_priv('order_ps_edit');
            $require_note   = C('order_unpay_note') == 1;
            $order          = $this->select_order_format_info($order_id);
            if ($order['money_paid'] > 0){
                $show_refund = true;
            }
            $anonymous      = $order['user_id'] == 0;
            $action         = L('op_unpay');
            $operation      = 'unpay';
        }elseif (isset($_POST['prepare'])) {
            /* 配货 */
            $require_note   = true;
            $action         = L('op_prepare');
            $operation      = 'prepare';
        } elseif (isset($_POST['unship'])){
            /* 未发货 */
            /* 检查权限 */
            admin_priv('order_ss_edit');
            $require_note   = C('order_unship_note') == 1;
            $action         = L('op_unship');
            $operation      = 'unship';
        } elseif (isset($_POST['receive'])){
            /* 收货确认 */
            $require_note   = C('order_receive_note') == 1;
            $action         = L('op_receive');
            $operation      = 'receive';
        }elseif (isset($_POST['cancel'])){
            /* 取消 */
            $require_note   = C('order_cancel_note') == 1;
            $action         = L('op_cancel');
            $operation      = 'cancel';
            $show_cancel_note   = true;
            $order          = Model('order')->select_order_info_info('money_paid',"order_id=$order_id");
            if ($order['money_paid'] > 0){
                $show_refund = true;
            }
            $anonymous      = $order['user_id'] == 0;
        }elseif (isset($_POST['invalid'])){
            /* 无效 */
            $require_note   = C('order_invalid_note') == 1;
            $action         = L('op_invalid');
            $operation      = 'invalid';
        }elseif (isset($_POST['after_service'])){
            /* 售后 */
            $require_note   = true;
            $action         = C('op_after_service');
            $operation      = 'after_service';
        }elseif (isset($_POST['remove'])){
            $require_note = false;
            $operation = 'remove';
            if (!$batch){
                /* 订单删除 */
                /* 检查能否操作 */
                $order = Model('order')->select_order_info_info('*',"order_id=$order_id");
                $operable_list = $this->operable_list($order);
                if (!$operable_list['remove']){
                    die('Hacking attempt');
                }
                /* 删除订单 */
                $order_model = Model('order');
                $order_model->delete_order_info("order_id = '$order_id'");
                $order_model->delete_order_goods("order_id = '$order_id'");
                $order_model->delete_order_action("order_id = '$order_id'");
                $action_array = array('delivery', 'back');
                $this->del_delivery($order_id, $action_array);

                /*记录日志 */
                admin_log($order['order_sn'], 'remove', 'order');

                /* 返回 */
                $links = array('text' => L('order_lists'), 'href' => 'index.php?act=order&op=lists');
                showMessage(L('act_ok'),$links);
            }
        } elseif (isset($_POST['assign'])){
            /* 指派 */
            /* 取得参数 */
            $new_agency_id  = isset($_POST['agency_id']) ? intval($_POST['agency_id']) : 0;
            if ($new_agency_id == 0){
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage(L('js_languages','')['pls_select_agency'], $link);
            }

            /* 查询订单信息 */
            $order = Model('order')->select_order_info_info('*',"order_id=$order_id");

            /* 如果管理员属于某个办事处，检查该订单是否也属于这个办事处 */
            $sess = $this->admin_info;
            $admin_id = $sess['user_id'];
            $where = "user_id = '$admin_id'";
            $admin_agency_id = Model('admin')->select_admin_info('agency_id',$where);
            if ($admin_agency_id['agency_id'] > 0){
                if ($order['agency_id'] != $admin_agency_id['agency_id']){
                    $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                    showMessage(L('priv_error',''), $link);
                }
            }

            /* 修改订单相关所属的办事处 */
            if ($new_agency_id != $order['agency_id']){
                $where = "order_id = '$order_id'";
                $data = array('agency_id'=>$new_agency_id);
                Model('order')->update_order_info($data,$where);
                Model('delivery_order')->update_delivery_order($data,$where);
                Model('back_order')->update_back_order($data,$where);
            }
            /* 操作成功 */
            $links = array('text' => L('order_lists'), 'href' => 'index.php?act=order&op=lists');
            showMessage(L('act_ok'),$links);
        } elseif (isset($_POST['to_shipping'])){
            /*一键发货*/
            /*必须填写操作备注*/
            if(empty($action_note)){
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage('必须填写操作备注', $link);
            }
            /*快递单号*/
            $invoice_no = empty($_REQUEST['invoice_no']) ? '' : trim($_REQUEST['invoice_no']);
            if (!empty($invoice_no)){
                $order_id = intval(trim($order_id));
                $action_note = trim($action_note);
                /* 查询：根据订单id查询订单信息 */
                if (!empty($order_id)){
                    $order = Model('order')->select_order_info_info('*',"order_id=$order_id");
                } else {
                    die('order does not exist');
                }
                /* 查询：根据订单是否完成 检查权限 */
                if ($this->order_finished($order)){
                    admin_priv('order_view_finished');
                } else {
                    admin_priv('order_view');
                }
                /* 查询：如果管理员属于某个办事处，检查该订单是否也属于这个办事处 */
                $sess = $this->admin_info;
                $admin_id = $sess['user_id'];
                $where = "user_id = '$admin_id'";
                $admin_agency_id = Model('admin')->select_admin_info('agency_id',$where);
                if ($admin_agency_id['agency_id'] > 0){
                    if ($order['agency_id'] != $admin_agency_id['agency_id']){
                        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                        showMessage(L('priv_error',''), $link);
                    }
                }
                /* 查询：取得用户名 */
                if ($order['user_id'] > 0){
                    $user = user_info($order['user_id']);
                    if (!empty($user)){
                        $order['user_name'] = $user['alias'];
                    }
                }
                /* 查询：其他处理 */
                $order['order_time']    = local_date(C('time_format'), $order['add_time']);
                $order['invoice_no']    = $order['shipping_status'] == SS_UNSHIPPED || $order['shipping_status'] == SS_PREPARING ? L('ss')[SS_UNSHIPPED] : $order['invoice_no'];
                /* 查询：是否保价 */
                $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;
                /* 查询：取得订单商品 */
                $_goods = $this->get_order_goods(array('order_id' => $order['order_id'], 'order_sn' =>$order['order_sn']));
                $attr = $_goods['attr'];
                $goods_list = $_goods;
                unset($_goods);
                /* 查询：商品已发货数量 此单可发货数量 */
                if ($goods_list){
                    foreach ($goods_list as $key=>$goods_value){
                        if (!$goods_value['goods_id']){
                            continue;
                        }
                        $goods_list[$key]['sended'] = $goods_value['goods_number'];
                        $goods_list[$key]['send'] = $goods_value['goods_number'] - $goods_value['send_number'];
                        $goods_list[$key]['readonly'] = '';
                        /* 是否缺货 */
                        if ($goods_value['storage'] <= 0 && C('use_storage') == '1'  && C('stock_dec_time') == SDT_SHIP){
                            $goods_list[$key]['send'] = L('act_good_vacancy');
                            $goods_list[$key]['readonly'] = 'readonly="readonly"';
                        }elseif ($goods_list[$key]['send'] <= 0){
                            $goods_list[$key]['send'] = L('act_good_delivery');
                            $goods_list[$key]['readonly'] = 'readonly="readonly"';
                        }
                    }
                }
                $suppliers_id = 0;
                $delivery['invoice_no'] = $invoice_no;
                $delivery['order_sn'] = trim($order['order_sn']);
                $delivery['user_id'] = intval(trim($order['user_id']));
                $delivery['how_oos'] = trim($order['how_oos']);
                $delivery['shipping_id'] = trim($order['shipping_id']);
                $delivery['shipping_fee'] = trim($order['shipping_fee']);
                $delivery['consignee'] = trim($order['consignee']);
                $delivery['address'] = trim($order['address']);
                $delivery['country'] = intval(trim($order['country']));
                $delivery['province'] = intval(trim($order['province']));
                $delivery['city'] = intval(trim($order['city']));
                $delivery['district'] = intval(trim($order['district']));
                $delivery['sign_building'] = trim($order['sign_building']);
                $delivery['email'] = trim($order['email']);
                $delivery['zipcode'] = trim($order['zipcode']);
                $delivery['tel'] = trim($order['tel']);
                $delivery['mobile'] = trim($order['mobile']);
                $delivery['best_time'] = trim($order['best_time']);
                $delivery['postscript'] = trim($order['postscript']);
                $delivery['how_oos'] = trim($order['how_oos']);
                $delivery['insure_fee'] = floatval(trim($order['insure_fee']));
                $delivery['shipping_fee'] = floatval(trim($order['shipping_fee']));
                $delivery['agency_id'] = intval(trim($order['agency_id']));
                $delivery['shipping_name'] = trim($order['shipping_name']);
                /* 检查能否操作 */
                $operable_list = $this->operable_list($order);
                /* 初始化提示信息 */
                $msg = '';
                /* 取得订单商品 */
                $_goods = $this->get_order_goods(array('order_id' => $order_id, 'order_sn' => $delivery['order_sn']));
                $goods_list = $_goods;
                /* 生成发货单 */
                /* 获取发货单号和流水号 */
                $delivery['delivery_sn'] = get_delivery_sn();
                $delivery_sn = $delivery['delivery_sn'];

                /* 获取当前操作员 */
                $sess = $this->admin_info;
                $delivery['action_user'] = $sess['user_name'];

                /* 获取发货单生成时间 */
                define('GMTIME_UTC', gmtime());
                $delivery['update_time'] = GMTIME_UTC;
                $delivery_time = $delivery['update_time'];
                $where = "order_sn = " . $delivery['order_sn'];
                $info = Model('order')->select_order_info_info('add_time',$where);
                $delivery['add_time'] = $info['add_time'];
                /* 获取发货单所属供应商 */
                $delivery['suppliers_id'] = $suppliers_id;

                /* 设置默认值 */
                $delivery['status'] = 2; // 正常
                $delivery['order_id'] = $order_id;

                /* 过滤字段项 */
                $filter_fileds = array(
                    'invoice_no','order_sn', 'add_time', 'user_id', 'how_oos', 'shipping_id', 'shipping_fee',
                    'consignee', 'address', 'country', 'province', 'city', 'district', 'sign_building',
                    'email', 'zipcode', 'tel', 'mobile', 'best_time', 'postscript', 'insure_fee',
                    'agency_id', 'delivery_sn', 'action_user', 'update_time',
                    'suppliers_id', 'status', 'order_id', 'shipping_name'
                );
                $_delivery = array();
                foreach ($filter_fileds as $value){
                    $_delivery[$value] = $delivery[$value];
                }
                /* 发货单入库 */
                $delivery_id = Model('delivery_order')->insert_delivery_order($_delivery);
                $where = "order_id = " . $order['order_id'] . " AND back_type = 4 AND status_back < 6 AND status_back != 3";
                /*如果发货商品是还货商品则更新其状态*/
                $sql_back_old = Model('back_order')->select_back_order_info('back_id',$where);
                if (!empty($sql_back_old['back_id'])) {
                    $wheres = "back_id = " . $sql_back_old['back_id'];
                    Model('back_order')->update_back_order(array('status_back'=>6),$wheres);
                    Model('back_goods')->update_back_goods(array('status_back'=>6),$wheres);
                }

                if ($delivery_id) {
                    $delivery_goods = array();
                    //发货单商品入库
                    if (!empty($goods_list)) {
                        foreach ($goods_list as $value) {
                            $delivery_goods = array(
                                'delivery_id' => $delivery_id,
                                'goods_id' => $value['goods_id'],
                                'product_id' => $value['product_id'],
                                'product_sn' => $value['product_sn'],
                                'goods_id' => $value['goods_id'],
                                'goods_name' => $value['goods_name'],
                                'brand_name' => $value['brand_name'],
                                'goods_sn' => $value['goods_sn'],
                                'send_number' => $value['goods_number'],
                                'parent_id' => 0,
                                'is_real' => $value['is_real'],
                                'goods_attr' => $value['goods_attr']
                            );
                            /* 如果是货品 */
                            if (!empty($value['product_id'])) {
                                $delivery_goods['product_id'] = $value['product_id'];
                            }
                            $query = Model('delivery_goods')->insert_delivery_goods($delivery_goods);
                            $where = "order_id = '" . $value['order_id'] . "' AND goods_id = '" . $value['goods_id'] . "' ";
                            Model('order')->update_order_goods(array('send_number'=>$value['goods_number']),$where);
                        }
                    }
                } else {
                    /* 操作失败 */
                    $links = array('text' => L('order_info'), 'href' => 'index.php?act=order&op=info&order_id=' . $order_id);
                    showMessage(L('act_false') . $msg,$links);
                }
                unset($filter_fileds, $delivery, $_delivery, $order_finish);
                if (true) {
                    /* 标记订单为已确认 "发货中" */
                    /* 更新发货时间 */
                    $order_finish = $this->get_order_finish($order_id);
                    $shipping_status = SS_SHIPPED_ING;
                    if ($order['order_status'] != OS_CONFIRMED && $order['order_status'] != OS_SPLITED && $order['order_status'] != OS_SPLITING_PART) {
                        $arr['order_status'] = OS_CONFIRMED;
                        $arr['confirm_time'] = gmtime();
                    }
                    $arr['order_status'] = $order_finish ? OS_SPLITED : OS_SPLITING_PART; // 全部分单、部分分单
                    $arr['shipping_status'] = $shipping_status;
                    $this->update_order($order_id, $arr);
                }

                /* 记录log */
                $this->order_action($order['order_sn'], $arr['order_status'], $shipping_status, $order['pay_status'], $action_note);

                /* 清除缓存 */
                clear_cache_files();

                /*开始发货*/
                /* 根据发货单id查询发货单信息 */
                if (!empty($delivery_id)) {
                    $delivery_order = $this->delivery_order_info($delivery_id);
                } elseif (!empty($order_sn)) {
                    $delivery_order = Model('delivery_order');
                    $where = "order_sn=".$order_sn;
                    $delivery_id = $delivery_order->select_delivery_order_info('delivery_id',$where);
                    $delivery_order = $this->delivery_order_info($delivery_id['delivery_id']);
                } else {
                    die('order does not exist');
                }
                /* 查询：如果管理员属于某个办事处，检查该订单是否也属于这个办事处 */
                $sess = $this->admin_info;
                $admin_id = $sess['user_id'];
                $where = "user_id = '$admin_id'";
                $admin_agency_id = Model('admin')->select_admin_info('agency_id',$where);
                if ($admin_agency_id['agency_id'] > 0){
                    if ($order['agency_id'] != $admin_agency_id['agency_id']){
                        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                        showMessage(L('priv_error',''), $link);
                    }
                    /* 取当前办事处信息 */
                    $where = "agency_id = ".$admin_agency_id['agency_id'];
                    $agency_name = Model('agency')->select_agency_info('agency_name',$where);
                    $delivery_order['agency_name'] = $agency_name['agency_name'];
                }
                /* 取得用户名 */
                if ($delivery_order['user_id'] > 0) {
                    $user = user_info($delivery_order['user_id']);
                    if (!empty($user)) {
                        $delivery_order['user_name'] = $user['alias'];
                    }
                }
                /* 取得区域名 */
                $delivery_order['region'] = get_province_city($delivery_order['province'],$delivery_order['city'],$delivery_order['district']);
                /* 是否保价 */
                $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;
                /* 取得发货单商品 */
                $where = "delivery_id = " . $delivery_order['delivery_id'];
                $goods_list = Model('delivery_goods')->get_delivery_goods_list('*',$where);
                /* 是否存在实体商品 */
                $exist_real_goods = 0;
                if ($goods_list) {
                    foreach ($goods_list as $value) {
                        if ($value['is_real']) {
                            $exist_real_goods++;
                        }
                    }
                }

                /* 取得订单操作记录 */
                $act_list = array();
                $orderby = 'log_time DESC,action_id DESC';
                $where = 'order_id = ' .$delivery_order['order_id'].' AND action_place = 1';
                $res = Model('order')->get_order_action_list('*',$where,$orderby);
                foreach ($res as $key => $row) {
                    $row['order_status']    = L('os')[$row['order_status']];
                    $row['pay_status']      = L('ps')[$row['pay_status']];
                    $row['shipping_status'] = L('ss')[$row['shipping_status']];
                    $row['action_time']     = local_date(C('time_format'), $row['log_time']);
                    $act_list[] = $row;
                }

                /*同步发货*/
                /*判断支付方式是否支付宝*/
                $alipay = false;
                $order = $this->select_order_format_info($delivery_order['order_id']);
                $payment = Model('payment')->select_payment_info('*','pay_id='.$order['pay_id']);
                /* 定义当前时间 */
                define('GMTIME_UTC', gmtime()); // 获取 UTC 时间戳
                /* 根据发货单id查询发货单信息 */
                if (!empty($delivery_id)) {
                    $delivery_order = $this->delivery_order_info($delivery_id);
                } else {
                    die('order does not exist');
                }
                /* 查询订单信息 */
                /* 检查此单发货商品库存缺货情况 */
                $field = "delivery_goods.goods_id, delivery_goods.is_real, delivery_goods.product_id, SUM(delivery_goods.send_number) AS sums, 
	            IF(delivery_goods.product_id > 0, products.product_number, goods.goods_number) AS storage, goods.goods_name, delivery_goods.send_number";
                $where = "delivery_goods.delivery_id = '$delivery_id' AND delivery_goods.product_id = products.product_id";
                $delivery_stock_result = Model('delivery_goods')->get_delivery_goods_goods_products_list($field,$where,'','','delivery_goods.product_id');
                /* 如果商品存在规格就查询规格，如果不存在规格按商品库存查询 */
                if (!empty($delivery_stock_result)) {
                    foreach ($delivery_stock_result as $value) {
                        if (($value['sums'] > $value['storage'] || $value['storage'] <= 0) && ((C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) || (C('use_storage') == '0' && $value['is_real'] == 0))) {
                            /* 操作失败 */
                            $links = array('text' => L('order_info',''), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
                            showMessage(sprintf(L('act_good_vacancy',''), $value['goods_name']),$links);
                            break;
                        }
                    }
                } else {
                    $field = "delivery_goods.goods_id, delivery_goods.is_real, SUM(delivery_goods.send_number) AS sums, goods.goods_number, goods.goods_name, delivery_goods.send_number";
                    $where = "delivery_goods.delivery_id = '$delivery_id'";
                    $delivery_stock_result = Model('delivery_goods')->get_delivery_goods_goods_list($field,$where,'','','delivery_goods.goods_id');
                    foreach ($delivery_stock_result as $value) {
                        if (($value['sums'] > $value['goods_number'] || $value['goods_number'] <= 0) && ((C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) || (C('use_storage') == '0' && $value['is_real'] == 0))) {
                            /* 操作失败 */
                            $links = array('text' => L('order_info',''), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
                            showMessage(sprintf(L('act_good_vacancy',''), $value['goods_name']),$links);
                            break;
                        }
                    }
                }

                /* 如果使用库存，且发货时减库存，则修改库存 */
                if (C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) {
                    foreach ($delivery_stock_result as $value) {
                        if ($value['is_real'] != 0) {
                            if (!empty($value['product_id'])) {
                                $data = array();
                                $data['product_number'] = $value['sums'];
                                Model('products')->update_products_setDec("product_id = " . $value['product_id'],$data);
                            }
                            $data = array();
                            $data['goods_number'] = $value['sums'];
                            Model('goods')->update_goods_setDec("goods_id = " . $value['goods_id'],$data);
                        }
                    }
                }

                /* 修改发货单信息 */
                $invoice_no = trim($invoice_no);
                $_delivery['invoice_no'] = $invoice_no;
                $_delivery['status'] = 0; // 0，为已发货
                $query = Model('delivery_order')->update_delivery_order($_delivery,"delivery_id = $delivery_id");
                if (!$query) {
                    $links = array('text' => L('delivery_sn','').L('detail',''), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
                    showMessage(sprintf(L('act_false',''), $value['goods_name']),$links);
                }

                /* 标记订单为已确认 "已发货" */
                /* 更新发货时间 */
                $order_finish = $this->get_all_delivery_finish($order_id);
                $shipping_status = ($order_finish == 1) ? SS_SHIPPED : SS_SHIPPED_PART;
                $arr['shipping_status'] = $shipping_status;
                $arr['shipping_time'] = gmtime(); // 发货时间
                $arr['invoice_no'] = trim($order['invoice_no'] . '<br>' . $invoice_no, '<br>');
                $this->update_order($order_id, $arr);

                /* 发货单发货记录log */
                $this->order_action($order['order_sn'], OS_CONFIRMED, $shipping_status, $order['pay_status'], $action_note, null, 1);

                /* 如果当前订单已经全部发货 */
                if ($order_finish) {
                    /* 如果订单用户不为空，计算发放积分*/
                    if ($order['user_id'] > 0) {
                        /* 取得用户信息 */
                        $user = user_info($order['user_id']);
                        /* 计算并发放积分 */
                        $integral = $this->integral_to_give($order);
                        log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf(L('order_gift_integral',''), $order['order_sn']));
                    }
                    /* 发推送通知 */
                    $cfg = C('send_ship_email');
                    if ($cfg == '1') {
                        $pay_timeds = local_date('Y-m-d H:i');
                        $userid = $order['user_id'];
                        /* 发送推送通知*/
                        $wheres = "user_id = '$userid'";
                        $userintos = Model('users')->select_users_info('user_name',$wheres);
                        $user_names = $userintos['user_name'];
                        $message['to_member_id'] = ',' . $userid . ',';
                        $message['message_title'] = '订单发货';
                        if (!empty($invoice_no)) {
                            $invoice_nos = $invoice_no;
                        } else {
                            $invoice_nos = '无';
                        }
                        $message['message_body'] = '亲爱的' . $user_names . '用户。你好！您的订单' . $order['order_sn'] . '已于' . $pay_timeds . '按照您的要求方式给您发货了。发货单号是：' . $invoice_nos;
                        $message['message_time'] = gmtime();
                        $message['message_type'] = 1;
                        $message['tuisong_type'] = 1;
                        $messageid = Model('message')->insert_message($message);
                        if(!empty($userintos)&& !empty($messageid)){
                            $content = '淘玉发货：您的订单:' . $order['order_sn'] . ', 已经发货,快递单号：' . $invoice_nos;
                            /*提交推送 查看是否符合条件推送*/
                            if(!empty($user_names)){
                                $res = send_jpush_message(1, $userid , ['title'=>'订单发货','body'=>$content]);
                            }
                        }
                    }

                    /* 如果需要，发短信 */
                    if (C('sms_order_shipped') == '1') {
                        $userid = $order['user_id'];
                        $wheres = "user_id = '$userid'";
                        $userintos = Model('users')->select_users_info('mobile_phone',$wheres);
                        $mobile_phones = $userintos['mobile_phone'];
                        if (!empty($mobile_phones)) {
                            $param = array();
                            $param['consignee'] = $order['consignee'];
                            if (!empty($invoice_no)) {
                                $invoice_nos = $invoice_no;
                            } else {
                                $invoice_nos = '无';
                            }
                            $param['invoice_no'] = $invoice_nos;
                            $param['address'] = $order['address'];
                            $result = send_sms_msg($mobile_phones,'order_send',$param);
                        }
                    }
                }
                /* 清除缓存 */
                clear_cache_files();
                /* 操作成功 */
                $links[] = array('text' => L('delivery_sn'). L('lists'), 'href' => 'index.php?act=order&op=delivery_list');
                $links[] = array('text' => L('delivery_sn') . L('detail'), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
                showMessage(L('act_ok'), $links);
            }
        }elseif (isset($_POST['ship'])) {
            /* 查询：检查权限 */
            admin_priv('order_ss_edit');
            $order_id = intval(trim($order_id));
            $action_note = trim($action_note);
            /* 查询：根据订单id查询订单信息 */
            if (!empty($order_id)){
                $order = $this->select_order_format_info($order_id,0);
            } else {
                die('order does not exist');
            }
            /* 查询：根据订单是否完成 检查权限 */
            if ($this->order_finished($order)){
                admin_priv('order_view_finished');
            } else {
                admin_priv('order_view');
            }
            /* 查询：如果管理员属于某个办事处，检查该订单是否也属于这个办事处 */
            $sess = $this->admin_info;
            $admin_id = $sess['user_id'];
            $where = "user_id = '$admin_id'";
            $admin_agency_id = Model('admin')->select_admin_info('agency_id',$where);
            if ($admin_agency_id['agency_id'] > 0){
                if ($order['agency_id'] != $admin_agency_id['agency_id']){
                    $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                    showMessage(L('priv_error',''), $link);
                }
            }
            /* 查询：取得用户名 */
            if ($order['user_id'] > 0){
                $user = user_info($order['user_id']);
                if (!empty($user)){
                    $order['user_name'] = $user['alias'];
                }
            }
            /* 查询：其他处理 */
            $order['order_time']    = local_date(C('time_format'), $order['add_time']);
            $order['invoice_no']    = $order['shipping_status'] == SS_UNSHIPPED || $order['shipping_status'] == SS_PREPARING ? L('ss')[SS_UNSHIPPED] : $order['invoice_no'];
            /* 查询：是否保价 */
            $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;
            /* 查询：取得订单商品 */
            $_goods = $this->get_order_goods(array('order_id' => $order['order_id'], 'order_sn' =>$order['order_sn']));
            $attr = $_goods['attr'];
            $goods_list = $_goods;
            unset($_goods);
            /* 查询：取得区域名 */
            $order['region'] = get_province_city($order['province'],$order['city'],$order['district']);
            /* 查询：商品已发货数量 此单可发货数量 */
            if ($goods_list) {
                foreach ($goods_list as $key => $goods_value) {
                    if (!$goods_value['goods_id']) {
                        continue;
                    }
                    $goods_list[$key]['sended'] = $goods_value['send_number'];
                    $goods_list[$key]['send'] = $goods_value['goods_number'] - $goods_value['send_number'];
                    $goods_list[$key]['readonly'] = '';
                    /* 是否缺货 */
                    if ($goods_value['storage'] <= 0 && C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) {
                        $goods_list[$key]['send'] = L('act_good_vacancy');
                        $goods_list[$key]['readonly'] = 'readonly="readonly"';
                    } elseif ($goods_list[$key]['send'] <= 0) {
                        $goods_list[$key]['send'] = L('act_good_delivery');
                        $goods_list[$key]['readonly'] = 'readonly="readonly"';
                    }

                }
            }

            /* 模板赋值 */
            Tpl::assign('order', $order);
            Tpl::assign('goods_attr', $attr);
            Tpl::assign('goods_list', $goods_list);
            Tpl::assign('order_id', $order_id); // 订单id
            Tpl::assign('operation', 'split'); // 订单id
            Tpl::assign('action_note', $action_note); // 发货操作信息
            /* 显示模板 */
            Tpl::assign('ur_here', L('order_operate') . L('op_split'));
            Tpl::display('order_delivery_info.htm');
            exit;
        }elseif (isset($_POST['print'])) {
            /*批量打印订单*/
            if (empty($_POST['order_id'])) {
                $link = array('text' => L('pls_select_order',''), 'href' => 'javascript:history.back(-1)');
                showMessage(L('edit_master_failed',''), $link);
            }

            /* 赋值公用信息 */
            Tpl::assign('shop_name', C('shop_name'));
            Tpl::assign('shop_url', WEB_PATH);
            Tpl::assign('shop_address', C('shop_address'));
            Tpl::assign('service_phone', C('service_phone'));
            Tpl::assign('print_time', local_date(C('time_format')));
            $sess = $this->admin_info;
            Tpl::assign('action_user',$sess['user_name']);
            $html = '';
            $order_sn_list = explode(',', $_POST['order_id']);
            foreach ($order_sn_list as $order_sn) {
                /* 取得订单信息 */
                $order = $this->select_order_format_info(0, $order_sn);
                if (empty($order)) {
                    continue;
                }

                /* 根据订单是否完成检查权限 */
                if ($this->order_finished($order)) {
                    if (!admin_priv('order_view_finished', '', false)) {
                        continue;
                    }
                } else {
                    if (!admin_priv('order_view', '', false)) {
                        continue;
                    }
                }

                /* 如果管理员属于某个办事处，检查该订单是否也属于这个办事处 */
                $sess = $this->admin_info;
                $admin_id = $sess['user_id'];
                $where = "user_id = '$admin_id'";
                $admin_agency_id = Model('admin')->select_admin_info('agency_id',$where);
                if ($admin_agency_id['agency_id'] > 0){
                    if ($order['agency_id'] != $admin_agency_id['agency_id']){
                        continue;
                    }
                }

                /* 取得用户名 */
                if ($order['user_id'] > 0) {
                    $user = user_info($order['user_id']);
                    if (!empty($user)) {
                        $order['user_name'] = $user['alias'];
                    }
                }

                /* 取得区域名 */
                $order['region'] = get_province_city($order['province'],$order['city'],$order['district']);

                /* 其他处理 */
                $order['order_time']    = local_date(C('time_format'), $order['add_time']);
                $order['pay_time']      = $order['pay_time'] > 0 ?
                    local_date(C('time_format'), $order['pay_time']) : L('ps')[PS_UNPAYED];
                $order['shipping_time'] = $order['shipping_time'] > 0 ?
                    local_date(C('time_format'), $order['shipping_time']) : L('ss')[SS_UNSHIPPED];
                $order['status']        = L('os')[$order['order_status']] . ',' . L('ps')[$order['pay_status']] . ',' . L('ss')[$order['shipping_status']];
                $order['invoice_no']    = $order['shipping_status'] == SS_UNSHIPPED || $order['shipping_status'] == SS_PREPARING ? L('ss')[SS_UNSHIPPED] : $order['invoice_no'];

                /* 此订单的发货备注(此订单的最后一条操作记录) */
                $where = 'order_id = '.$order['order_id'].' AND shipping_status = 1';
                $res = Model('order')->select_order_action_info('action_note',$where,'log_time DESC');
                var_dump($res);
                $order['invoice_note'] = $res['action_note'];
                /* 参数赋值：订单 */
                Tpl::assign('order', $order);

                /* 取得订单商品 */
                $goods_list = array();
                $goods_attr = array();
                $where = "order_goods.order_id = ".$order['order_id'];
                $field = "order_goods.*, goods.goods_number AS storage, order_goods.goods_attr, IFNULL(brand.brand_name, '') AS brand_name";
                $res = Model('order')->get_order_goods_products_goods_brand_list($field,$where);
                foreach ($res as $row) {
                    $row['formated_subtotal'] = price_format($row['goods_price'] * $row['goods_number']);
                    $row['formated_goods_price'] = price_format($row['goods_price']);

                    $goods_attr[] = explode(' ', trim($row['goods_attr'])); //将商品属性拆分为一个数组
                    $goods_list[] = $row;
                }
                $attr = array();
                $arr = array();
                foreach ($goods_attr AS $index => $array_val) {
                    foreach ($array_val AS $value) {
                        $arr = explode(':', $value);//以 : 号将属性拆开
                        $attr[$index][] = @array('name' => $arr[0], 'value' => $arr[1]);
                    }
                }

                Tpl::assign('goods_attr', $attr);
                Tpl::assign('goods_list', $goods_list);

                $html .= Tpl::fetch('order_print.htm') .
                    '<div style="PAGE-BREAK-AFTER:always"></div>';
            }

            echo $html;
            exit;
        } elseif (isset($_POST['to_delivery'])) {
            /* 去发货 */
            $url = 'index.php?act=order&op=delivery_list&order_sn=' . $_REQUEST['order_sn'];

            ecs_header("Location: $url\n");
            exit;
        }

        /* 直接处理还是跳到详细页面 */
        if (($require_note && $action_note == '') || isset($show_invoice_no) || isset($show_refund)){
            /* 模板赋值 */
            Tpl::assign('require_note', $require_note); /*是否要求填写备注*/
            Tpl::assign('action_note', $action_note);  /*备注*/
            Tpl::assign('show_cancel_note', isset($show_cancel_note)); /*是否显示取消原因*/
            Tpl::assign('show_invoice_no', isset($show_invoice_no)); /*是否显示发货单号*/
            Tpl::assign('show_refund', isset($show_refund)); /*是否显示退款*/
            Tpl::assign('anonymous', isset($anonymous) ? $anonymous : true); /*是否匿名*/
            Tpl::assign('order_id', $order_id); /*订单id*/
            Tpl::assign('batch', $batch);    /*是否批处理*/
            Tpl::assign('operation', $operation); /*操作*/
            /* 显示模板 */
            Tpl::assign('ur_here', L('order_operate') . $action);
            Tpl::display('order_operate.htm');
        }else{
            /* 直接处理 */
            if (!$batch){
                /* 一个订单 */
                ecs_header("Location: index.php?act=order&op=operate_post&order_id=" . $order_id .
                    "&operation=" . $operation . "&action_note=" . urlencode($action_note) . "\n");
                exit;
            }else{
                /* 多个订单 */
                ecs_header("Location: index.php?act=order&op=batch_operate_post&order_id=" . $order_id .
                    "&operation=" . $operation . "&action_note=" . urlencode($action_note) . "\n");
                exit;
            }
        }
    }

    /**
     * @return 操作订单状态,具体处理,单个操作处理
     */
    public function operate_post(){
        /* 检查权限 */
        admin_priv('order_os_edit');
        $order_model = Model('order');
        $payment_model = Model('payment');
        /* 取得参数 */
        $order_id   = intval(trim($_REQUEST['order_id']));
        /*订单操作*/
        $operation  = $_REQUEST['operation'];
        /* 查询订单信息 */
        $order = Model('order')->select_order_info_info('*',"order_id=".$order_id);
        /* 检查能否操作 */
        $operable_list = $this->operable_list($order);
        if (!isset($operable_list[$operation])){
            die('Hacking attempt');
        }
        /* 取得备注信息 */
        $action_note = $_REQUEST['action_note'];        
        if ('confirm' == $operation){
            /* 确认 */
            $order['order_status'] = OS_CONFIRMED;
            $order['confirm_time'] = gmtime();
            /* 标记订单为已确认 */
            $this->update_order($order_id,$order);
            /*更新订单总金额*/
            $this->update_order_amount($order_id);

            /* 记录log */
            $this->order_action($order['order_sn'], OS_CONFIRMED, SS_UNSHIPPED, PS_UNPAYED, $action_note);

            /* 如果原来状态不是“未确认”，且使用库存，且发货时减库存，则减少库存 */
            if ($order['order_status'] != OS_UNCONFIRMED && C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP){
                change_order_goods_storage($order_id, true, SDT_PLACE);
            }
            /* 发送推送通知4 */
            $cfg = C('send_confirm_email');
            if ($cfg == '1'){
                $where = "order_id = '$order_id'";
                $orderinfo = $order_model->select_order_info_info('*',$where);
                $userid = $orderinfo['user_id'];
                $pay_timesd = local_date('Y-m-d H:i',$orderinfo['pay_time']);
                /* 发送推送通知*/
                $wheres = "user_id = '$userid'";
                $userintos = Model('users')->select_users_info('user_name',$wheres);
                $user_names = $userintos['user_name'];
                $message['to_member_id'] = ','.$userid.',';
                $message['message_title'] = '客服确定订单';
                $message['message_body'] = '亲爱的'.$user_names.'用户，您在淘玉商城 '.$pay_timesd.' 购买的产品，我们正在为您配货，准备发货中。请注意查看订单状态变化';
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($userintos)&& !empty($messageid)){
                    $content = '我们已经收到您的订单:'.$orderinfo['order_sn'].', 正在配货中';
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $userid , ['title'=>'订单确认','body'=>$content]);
                    }
                }
            }
        }elseif ('pay' == $operation){
            /* 付款 */
            /* 检查权限 */
            admin_priv('order_ps_edit');
            /* 标记订单为已确认、已付款，更新付款时间和已支付金额，如果是货到付款，同时修改订单为“收货确认” */
            if ($order['order_status'] != OS_CONFIRMED){
                $arr['order_status']    = OS_CONFIRMED;
                $arr['confirm_time']    = gmtime();
            }
            $pay_name = Model('payment')->select_payment_info('pay_name','pay_id='.$order['pay_id'])['pay_name'];
            if(!$pay_name){
                $pay_name = $action_note;
            }
            $arr['pay_status']  = PS_PAYED;
            $arr['pay_name']  = $pay_name;
            $arr['pay_time']    = gmtime();
            $arr['money_paid']  = $order['money_paid'] + $order['order_amount'];
            $arr['order_amount']= 0;
            $where =  'pay_id = '.$order['pay_id'].' AND enabled = 1';
            $payment =  Model('payment')->select_payment_info('*',$where,'','');
            if ($payment['is_cod']){
                $arr['shipping_status'] = SS_RECEIVED;
                $order['shipping_status'] = SS_RECEIVED;
            }
            $this->update_order($order_id,$arr);
            /* 记录log */
            $this->order_action($order['order_sn'], OS_CONFIRMED, $order['shipping_status'], PS_PAYED, $action_note);
        } elseif ('unpay' == $operation){
            /* 设为未付款 */
            /* 检查权限 */
            admin_priv('order_ps_edit');
            /* 标记订单为未付款，更新付款时间和已付款金额 */
            $arr = array(
                'pay_status'    => PS_UNPAYED,
                'pay_time'      => 0,
                'money_paid'    => 0,
                'order_amount'  => $order['money_paid']
            );
            $this->update_order($order_id,$arr);
            /* 处理退款 */
            $refund_type = @$_REQUEST['refund'];
            $refund_note = @$_REQUEST['refund_note'];
            $this->order_refund($order, $refund_type, $refund_note);
            /* 记录log */
            $this->order_action($order['order_sn'], OS_CONFIRMED, SS_UNSHIPPED, PS_UNPAYED, $action_note);
        }elseif ('prepare' == $operation){
            /* 配货 */
            /* 标记订单为已确认，配货中 */
            if ($order['order_status'] != OS_CONFIRMED){
                $arr['order_status']    = OS_CONFIRMED;
                $arr['confirm_time']    = gmtime();
            }
            $arr['shipping_status']     = SS_PREPARING;
            $this->update_order($order_id, $arr);
            /* 记录log */
            $this->order_action($order['order_sn'], OS_CONFIRMED, SS_PREPARING, $order['pay_status'], $action_note);
            /* 清除缓存 */
            clear_cache_files();
        }elseif ('unship' == $operation){
            /* 设为未发货 */
            /* 检查权限 */
            admin_priv('order_ss_edit');
            /* 标记订单为“未发货”，更新发货时间, 订单状态为“确认” */
            $this->update_order($order_id, array('shipping_status' => SS_UNSHIPPED, 'shipping_time' => 0, 'invoice_no' => '', 'order_status' => OS_CONFIRMED));
            /* 记录log */
            $this->order_action($order['order_sn'], $order['order_status'], SS_UNSHIPPED, $order['pay_status'], $action_note);
            /* 如果使用库存，则增加库存 */
            if (C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP){
                $res = change_order_goods_storage($order['order_id'], false, SDT_SHIP);
            }

            /* 如果订单用户不为空，计算积分，并退回 */
            if ($order['user_id'] > 0){
                /* 取得用户信息 */
                $user = user_info($order['user_id']);
                /* 计算并退回积分 */
                $integral = $this->integral_to_give($order);
                log_account_change($order['user_id'], 0, 0, (-1) * intval($integral['rank_points']), (-1) * intval($integral['custom_points']), sprintf(L('return_order_gift_integral'), $order['order_sn']));
            }
            /* 删除发货单 */
            $where = "order_id = '$order_id'";
            $res = Model('delivery_order')->delete_delivery_order($where);
            /* 将订单的商品发货数量更新为 0 */
            $res = Model('order')->update_order_goods(array('send_number'=>0),$where);
            /* 清除缓存 */
            clear_cache_files();
        }elseif ('receive' == $operation){
            /* 收货确认 */
            /* 标记订单为“收货确认”，如果是货到付款，同时修改订单为已付款 */
            $arr = array('shipping_status' => SS_RECEIVED);
            $where =  "pay_id = ".$order['pay_id']." AND enabled = 1";
            $payment = $payment_model->select_payment_info('is_cod',$where,'','');
            if ($payment['is_cod']){
                $arr['pay_status'] = PS_PAYED;
                $order['pay_status'] = PS_PAYED;
            }
            $res = $this->update_order($order_id, $arr);
            /* 记录log */
            $this->order_action($order['order_sn'], $order['order_status'], SS_RECEIVED, $order['pay_status'], $action_note);
        }elseif ('cancel' == $operation){
            /* 取消 */
            /* 标记订单为“取消”，记录取消原因 */
            $cancel_note = isset($_REQUEST['cancel_note']) ? trim($_REQUEST['cancel_note']) : '';
            $arr['order_status']= OS_CANCELED;
            $arr['to_buyer']= $cancel_note;
            $arr['pay_status']= PS_UNPAYED;
            $arr['pay_time']= 0;
            $arr['money_paid']= 0;
            $arr['order_amount']= $order['money_paid'];
            $this->update_order($order_id, $arr);

            /* 处理退款 */
            if ($order['money_paid'] > 0){
                $refund_type = @$_REQUEST['refund'];
                $refund_note = @$_REQUEST['refund_note'];
                $this->order_refund($order, $refund_type, $refund_note);
            }

            /* 记录log */
            $this->order_action($order['order_sn'], OS_CANCELED, $order['shipping_status'], PS_UNPAYED, $action_note);
            /* 如果使用库存，且下订单时减库存，则增加库存 */
            if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE){
                change_order_goods_storage($order_id, false, SDT_PLACE);
            }

            /* 退还用户余额、积分 */
            $this->return_user_surplus_integral($order);
            /* 发送推送通知，订单取消*/
            $cfg = C('send_cancel_email');
            if ($cfg == '1'){
                $where = "order_id = '$order_id'";
                $orderinfo = $order_model->select_order_info_info('*',$where);
                $userid = $orderinfo['user_id'];
                /* 发送推送通知*/
                $wheres = "user_id = '$userid'";
                $userintos = Model('users')->select_users_info('user_name',$wheres);
                $user_names = $userintos['user_name'];
                $message['to_member_id'] = ','.$userid.',';
                $message['message_title'] = '客服取消订单';
                $message['message_body'] = '亲爱的'.$user_names.'用户，你好！您的编号为：'.$orderinfo['order_sn'].'的订单已取消';
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($userintos)&& !empty($messageid)){
                    $content = '您在淘玉编号为：'.$orderinfo['order_sn'].'的订单已取消';
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $userid , ['title'=>'订单取消','body'=>$content]);
                    }
                }
            }
        }elseif ('invalid' == $operation){
            /* 设为无效 */
            /* 标记订单为“无效”、“未付款” */
            $this->update_order($order_id, array('order_status' => OS_INVALID));

            /* 记录log */
            $this->order_action($order['order_sn'], OS_INVALID, $order['shipping_status'], PS_UNPAYED, $action_note);

            /* 如果使用库存，且下订单时减库存，则增加库存 */
            if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE){
                change_order_goods_storage($order_id, false, SDT_PLACE);
            }

            /* 发送推送通知，处理无效订单*/
            $cfg = C('send_invalid_email');
            if ($cfg){
                $where = "order_id = '$order_id'";
                $orderinfo = $order_model->select_order_info_info('*',$where);
                $userid = $orderinfo['user_id'];
                /* 发送推送通知*/
                $wheres = "user_id = '$userid'";
                $userintos = Model('users')->select_users_info('user_name',$wheres);
                $user_names = $userintos['user_name'];
                $message['to_member_id'] = ','.$userid.',';
                $message['message_title'] = '淘玉处理无效订单';
                $message['message_body'] = '亲爱的'.$user_names.'用户，你好！您的编号为：'.$orderinfo['order_sn'].'的订单无效。';
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($userintos)&& !empty($messageid)){
                    $content = '您在淘玉编号为：'.$orderinfo['order_sn'].'的订单无效。';
                    //提交推送 查看是否符合条件推送
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $userid , ['title'=>'无效订单','body'=>$content]);
                    }

                }
            }
            /* 退货用户余额、积分 */
            $this->return_user_surplus_integral($order);
        } elseif ('after_service' == $operation){
            /* 售后*/
            /* 记录log */
            $this->order_action($order['order_sn'], $order['order_status'], $order['shipping_status'], $order['pay_status'], '[' . L('op_after_service') . '] ' . $action_note);
        } elseif ('split' == $operation){
            /* 分单确认 */
            /* 检查权限 */
            admin_priv('order_ss_edit');

            /* 定义当前时间 */
            define('GMTIME_UTC', gmtime()); // 获取 UTC 时间戳

            /* 获取表单提交数据 */
            $suppliers_id = isset($_REQUEST['suppliers_id']) ? intval(trim($_REQUEST['suppliers_id'])) : '0';
            array_walk($_REQUEST['delivery'], 'trim_array_walk');
            $delivery = $_REQUEST['delivery'];
            array_walk($_REQUEST['send_number'], 'trim_array_walk');
            array_walk($_REQUEST['send_number'], 'intval_array_walk');
            $send_number = $_REQUEST['send_number'];
            $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';
            $delivery['user_id'] = intval($delivery['user_id']);
            $delivery['country'] = intval($delivery['country']);
            $delivery['province'] = intval($delivery['province']);
            $delivery['city'] = intval($delivery['city']);
            $delivery['district'] = intval($delivery['district']);
            $delivery['agency_id'] = intval($delivery['agency_id']);
            $delivery['insure_fee'] = floatval($delivery['insure_fee']);
            $delivery['shipping_fee'] = floatval($delivery['shipping_fee']);

            /* 订单是否已全部分单检查 */
            if ($order['order_status'] == OS_SPLITED) {
                /* 操作失败 */
                $links[] = array('text' => L('order_info'), 'href' => 'index.php?act=order&op=info&order_id=' . $order_id);
                showMessage(sprintf(L('order_splited_sms'), $order['order_sn'],
                    L('os')[OS_SPLITED], L('ss')[SS_SHIPPED_ING], C('shop_name')), $links);
            }

            /* 取得订单商品 */
            $_goods = $this->get_order_goods(array('order_id' => $order['order_id'], 'order_sn' =>$order['order_sn']));
            $goods_list = $_goods;
            /* 检查此单发货数量填写是否正确 合并计算相同商品和货品 */
            if (!empty($send_number) && !empty($goods_list)) {
                $goods_no_package = array();
                foreach ($goods_list as $key => $value) {
                    // 如果是货品则键值为商品ID与货品ID的组合
                    $_key = empty($value['product_id']) ? $value['goods_id'] : ($value['goods_id'] . '_' . $value['product_id']);
                    // 统计此单商品总发货数 合并计算相同ID商品或货品的发货数
                    if (empty($goods_no_package[$_key])) {
                        $goods_no_package[$_key] = $send_number[$value['rec_id']];
                    } else {
                        $goods_no_package[$_key] += $send_number[$value['rec_id']];
                    }
                    /* 去除 此单发货数量 等于 0 的商品 */
                    if ($send_number[$value['rec_id']] <= 0) {
                        unset($send_number[$value['rec_id']], $goods_list[$key]);
                        continue;
                    }
                    /*获取订单单个商品或货品的已发货数量*/
                    $exc = ($value['product_id'] > 0) ? " AND delivery_goods.product_id = ".$value['product_id'] : '';
                    $field = "SUM(delivery_goods.send_number) AS sums";
                    $where = 'delivery_order.status = 0
                    AND delivery_order.order_id = ' . $order_id . '
                    AND delivery_goods.extension_code <> "package_buy"
                    AND delivery_goods.goods_id = ' . $value['goods_id'].$exc;
                    $sum = Model('delivery_goods')->select_delivery_goods_order_info($field ,$where);
                    if (empty($sum['sums'])){
                        $sended = 0;
                    }else{
                        $sended = $sum['sums'];
                    }
                    /*发货数量不能超出订单商品数量*/                    
                    if (($value['goods_number'] - $sended - $send_number[$value['rec_id']]) < 0) {
                        /* 操作失败 */
                        $links[] = array('text' => L('order_info'), 'href' => 'index.php?act=order&op=info&order_id=' . $order_id);
                        showMessage(L('act_ship_num'),$links);
                    }
                }
            }
            /* 对上一步处理结果进行判断 兼容 上一步判断为假情况的处理 */
            if (empty($send_number) || empty($goods_list)) {
                /* 操作失败 */
                $links[] = array('text' => L('order_info'), 'href' => 'index.php?act=order&op=info&order_id=' . $order_id);
                showMessage('无此商品或者发货单数量输入不正确', $links);
            }
            /* 检查此单发货商品库存缺货情况 */
            foreach ($goods_list as $key => $value) {
                //如果是货品则键值为商品ID与货品ID的组合
                $_key = empty($value['product_id']) ? $value['goods_id'] : ($value['goods_id'] . '_' . $value['product_id']);
                if (empty($value['product_id'])) {
                    $result = Model('goods')->select_goods_info('goods_number',"goods_id = " . $value['goods_id']);
                    $num = $result['goods_number'];
                } else {
                    $where = "goods_id = '" . $value['goods_id'] . "' AND product_id = " . $value['product_id'];
                    $result = Model('products')->select_goods_info('product_number',$where);
                    $num = $result['product_number'];
                }
                if (($num < $goods_no_package[$_key]) && C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) {
                    /* 操作失败 */
                    $links[] = array('text' => L('order_info'), 'href' => 'index.php?act=order&op=info&order_id=' . $order_id);
                    showMessage(sprintf(L('act_good_vacancy'), $value['goods_name']), $links);
                }
            }
            /* 生成发货单 */
            /* 获取发货单号和流水号 */
            $delivery['delivery_sn'] = get_delivery_sn();
            $delivery_sn = $delivery['delivery_sn'];

            /* 获取当前操作员 */
            $sess = $this->admin_info;
            $delivery['action_user'] = $sess['user_name'];

            /* 获取发货单生成时间 */
            define('GMTIME_UTC', gmtime());
            $delivery['update_time'] = GMTIME_UTC;
            $delivery_time = $delivery['update_time'];
            $where = "order_sn = " . $delivery['order_sn'];
            $info = Model('order')->select_order_info_info('add_time',$where);
            $delivery['add_time'] = $info['add_time'];
            /* 获取发货单所属供应商 */
            $delivery['suppliers_id'] = $suppliers_id;

            /* 设置默认值 */
            $delivery['status'] = 2; // 正常
            $delivery['order_id'] = $order_id;

            /* 过滤字段项 */
            $filter_fileds = array(
                'invoice_no','order_sn', 'add_time', 'user_id', 'how_oos', 'shipping_id', 'shipping_fee',
                'consignee', 'address', 'country', 'province', 'city', 'district', 'sign_building',
                'email', 'zipcode', 'tel', 'mobile', 'best_time', 'postscript', 'insure_fee',
                'agency_id', 'delivery_sn', 'action_user', 'update_time',
                'suppliers_id', 'status', 'order_id', 'shipping_name'
            );
            $_delivery = array();
            foreach ($filter_fileds as $value){
                $_delivery[$value] = $delivery[$value];
            }
            /* 发货单入库 */
            $delivery_id = Model('delivery_order')->insert_delivery_order($_delivery);
            if ($delivery_id) {
                $delivery_goods = array();
                /*发货单商品入库*/
                if (!empty($goods_list)) {
                    foreach ($goods_list as $value) {
                        $delivery_goods = array(
                            'delivery_id' => $delivery_id,
                            'goods_id' => $value['goods_id'],
                            'product_id' => $value['product_id'],
                            'product_sn' => $value['product_sn'],
                            'goods_id' => $value['goods_id'],
                            'goods_name' => $value['goods_name'],
                            'brand_name' => $value['brand_name'],
                            'goods_sn' => $value['goods_sn'],
                            'send_number' => $value['goods_number'],
                            'parent_id' => 0,
                            'is_real' => $value['is_real'],
                            'goods_attr' => $value['goods_attr']
                        );
                        /* 如果是货品 */
                        if (!empty($value['product_id'])) {
                            $delivery_goods['product_id'] = $value['product_id'];
                        }
                        $query = Model('delivery_goods')->insert_delivery_goods($delivery_goods);
                        $where = "order_id = '" . $value['order_id'] . "' AND goods_id = '" . $value['goods_id'] . "' ";
                        Model('order')->update_order_goods(array('send_number'=>$value['goods_number']),$where);
                    }
                }
            } else {
                /* 操作失败 */
                $links = array('text' => L('order_info'), 'href' => 'index.php?act=order&op=info&order_id=' . $order_id);
                showMessage(L('act_false') . $msg,$links);
            }
            unset($filter_fileds, $delivery, $_delivery, $order_finish);
            /* 定单信息更新处理"发货中" */
            if (true) {
                /* 标记订单为已确认 "发货中" */
                /* 更新发货时间 */
                $order_finish = $this->get_order_finish($order_id);
                $shipping_status = SS_SHIPPED_ING;
                if ($order['order_status'] != OS_CONFIRMED && $order['order_status'] != OS_SPLITED && $order['order_status'] != OS_SPLITING_PART) {
                    $arr['order_status'] = OS_CONFIRMED;
                    $arr['confirm_time'] = gmtime();
                }
                $arr['order_status'] = $order_finish ? OS_SPLITED : OS_SPLITING_PART; // 全部分单、部分分单
                $arr['shipping_status'] = $shipping_status;
                $this->update_order($order_id, $arr);
            }

            /* 记录log */
            $this->order_action($order['order_sn'], $arr['order_status'], $shipping_status, $order['pay_status'], $action_note);

            /* 清除缓存 */
            clear_cache_files();
        }else{
            die('invalid params');
        }
        /* 操作成功 */
        $links = array('text' => L('order_info'), 'href' => 'index.php?act=order&op=info&order_id=' . $order_id);
        showMessage(L('act_ok'),$links);
    }

    /**
     * @return 操作订单状态,具体处理,批量操作处理
     */
    public function batch_operate_post(){
        /* 检查权限 */
        admin_priv('order_os_edit');

        /* 取得参数 */
        $order_id = $_REQUEST['order_id']; /*订单id（逗号格开的多个订单id）*/
        $operation = $_REQUEST['operation']; /*订单操作*/
        $action_note = $_REQUEST['action_note']; /*操作备注*/

        $order_id_list = explode(',', $order_id);

        /* 初始化处理的订单sn */
        $sn_list = array();
        $sn_not_list = array();
        $model = Model('order');
        if ('confirm' == $operation) {
            /*确认*/
            foreach ($order_id_list as $id_order) {
                $where = "order_sn = '$id_order'" ." AND order_status = '" . OS_UNCONFIRMED . "'";
                $order = $model->select_order_info_info('*',$where);
                if ($order) {
                    /* 检查能否操作 */
                    $operable_list = $this->operable_list($order);
                    if (!isset($operable_list[$operation])) {
                        $sn_not_list[] = $id_order;
                        continue;
                    }

                    $order_id = $order['order_id'];
                    $userid = $order['user_id'];
                    $pay_time = local_date('Y-m-d H:i', $order['pay_time']);

                    /* 标记订单为已确认 */
                    $this->update_order($order_id, array('order_status' => OS_CONFIRMED, 'confirm_time' => gmtime()));
                    $this->update_order_amount($order_id);

                    /* 记录log */
                    $this->order_action($order['order_sn'], OS_CONFIRMED, SS_UNSHIPPED, PS_UNPAYED, $action_note);

                    $sn_list[] = $order['order_sn'];
                } else {
                    $sn_not_list[] = $id_order;
                }
            }
            $sn_str = L('confirm_order');
        }elseif ('invalid' == $operation) {
            foreach ($order_id_list as $id_order) {
                $where = "order_sn = '$id_order'  " .$this->order_query_sql('unpay_unship');
                $order = $model->select_order_info_info('*',$where);
                if ($order) {
                    /* 检查能否操作 */
                    $operable_list = $this->operable_list($order);
                    if (!isset($operable_list[$operation])) {
                        $sn_not_list[] = $id_order;
                        continue;
                    }

                    $order_id = $order['order_id'];

                    /* 标记订单为“无效” */
                    $this->update_order($order_id, array('order_status' => OS_INVALID));

                    /* 记录log */
                    $this->order_action($order['order_sn'], OS_INVALID, SS_UNSHIPPED, PS_UNPAYED, $action_note);

                    /* 如果使用库存，且下订单时减库存，则增加库存 */
                    if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE) {
                        change_order_goods_storage($order_id, false, SDT_PLACE);
                    }

                    /* 退还用户余额、积分 */
                    $this->return_user_surplus_integral($order);

                    $sn_list[] = $order['order_sn'];
                } else {
                    $sn_not_list[] = $id_order;
                }
            }
            $sn_str = L('invalid_order');
        }elseif ('cancel' == $operation) {
            foreach ($order_id_list as $id_order) {
                $where = "order_sn = '$id_order'  " .$this->order_query_sql('unpay_unship');
                $order = $model->select_order_info_info('*',$where);
                if ($order) {
                    /* 检查能否操作 */
                    $operable_list = $this->operable_list($order);
                    if (!isset($operable_list[$operation])) {
                        $sn_not_list[] = $id_order;
                        continue;
                    }

                    $order_id = $order['order_id'];

                    /* 标记订单为“取消”，记录取消原因 */
                    $cancel_note = trim($_REQUEST['cancel_note']);
                    $this->update_order($order_id, array('order_status' => OS_CANCELED, 'to_buyer' => $cancel_note));

                    /* 记录log */
                    $this->order_action($order['order_sn'], OS_CANCELED, $order['shipping_status'], PS_UNPAYED, $action_note);

                    /* 如果使用库存，且下订单时减库存，则增加库存 */
                    if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE) {
                        change_order_goods_storage($order_id, false, SDT_PLACE);
                    }

                    /* 退还用户余额、积分 */
                    $this->return_user_surplus_integral($order);

                    $sn_list[] = $order['order_sn'];
                } else {
                    $sn_not_list[] = $id_order;
                }
            }
            $sn_str = L('cancel_order');
        }elseif ('remove' == $operation) {
            foreach ($order_id_list as $id_order) {
                /* 检查能否操作 */
                $order = $this->select_order_format_info('', $id_order);
                $operable_list = $this->operable_list($order);
                if (!isset($operable_list['remove'])) {
                    $sn_not_list[] = $id_order;
                    continue;
                }

                /* 删除订单 */
                $model->delete_order_info("order_id = ".$order['order_id']);
                $model->delete_order_goods("order_id = ".$order['order_id']);
                $model->delete_order_action("order_id = ".$order['order_id']);
                $action_array = array('delivery', 'back');
                $this->del_delivery($order['order_id'], $action_array);

                /* todo 记录日志 */
                admin_log($order['order_sn'], 'remove', 'order');

                $sn_list[] = $order['order_sn'];
            }

            $sn_str = L('remove_order');
        } else {
            die('invalid params');
        }

        /* 取得备注信息 */
        if (empty($sn_not_list)) {
            $sn_list = empty($sn_list) ? '' : L('updated_order') . join($sn_list, ',');
            $msg = $sn_list;
            $links[] = array('text' => L('return_list'), 'href' => 'index.php?act=order&op=lists');
            showMessage($msg, $links);
        } else {
            $order_list_no_fail = array();
            $where = "order_sn ".db_create_in($sn_not_list);
            $res = $model->get_order_info_list('*',$where);
            foreach($res as $row) {
                $order_list_no_fail[$row['order_id']]['order_id'] = $row['order_id'];
                $order_list_no_fail[$row['order_id']]['order_sn'] = $row['order_sn'];
                $order_list_no_fail[$row['order_id']]['order_status'] = $row['order_status'];
                $order_list_no_fail[$row['order_id']]['shipping_status'] = $row['shipping_status'];
                $order_list_no_fail[$row['order_id']]['pay_status'] = $row['pay_status'];

                $order_list_fail = '';
                foreach ($this->operable_list($row) as $key => $value) {
                    if ($key != $operation) {
                        $order_list_fail .= L('op_' . $key) . ',';
                    }
                }
                $order_list_no_fail[$row['order_id']]['operable'] = $order_list_fail;
            }

            /* 模板赋值 */
            Tpl::assign('order_info', $sn_str);
            Tpl::assign('action_link', array('href' => 'index.php?act=order&op=lists', 'text' => L('01_order_list')));
            Tpl::assign('order_list', $order_list_no_fail);

            /* 显示模板 */
            Tpl::display('order_operate_info.htm');
        }

    }

    /**
     * @return 发货单列表
     */
    public function delivery_list() {
        /* 检查权限 */
        //admin_priv('delivery_view');

        /* 查询 */
        $result = $this->get_delivery_list();

        /* 模板赋值 */
        Tpl::assign('ur_here', L('09_delivery_order'));

        Tpl::assign('os_unconfirmed', OS_UNCONFIRMED);
        Tpl::assign('cs_await_pay', CS_AWAIT_PAY);
        Tpl::assign('cs_await_ship', CS_AWAIT_SHIP);
        Tpl::assign('full_page', 1);

        Tpl::assign('delivery_list', $result['delivery']);
        Tpl::assign('filter', $result['filter']);
        Tpl::assign('record_count', $result['record_count']);
        Tpl::assign('page_count', $result['page_count']);
        Tpl::assign('sort_update_time', '<img src="templates/default/images/sort_desc.gif">');

        /* 显示模板 */
        Tpl::display('delivery_list.htm');
    }

    /**
     * @return 发货单列表搜索、排序、分页
     */
    public function delivery_query() {
        /* 检查权限 */
        //admin_priv('delivery_view');

        $result = $this->get_delivery_list();

        Tpl::assign('delivery_list', $result['delivery']);
        Tpl::assign('filter', $result['filter']);
        Tpl::assign('record_count', $result['record_count']);
        Tpl::assign('page_count', $result['page_count']);

        $sort_flag = sort_flag($result['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('delivery_list.htm'), '', array('filter' => $result['filter'], 'page_count' => $result['page_count']));

    }

    /**
     * @return 发货单详细
     */
    public function delivery_info() {
        /* 检查权限 */
        //admin_priv('delivery_view');

        $delivery_id = intval(trim($_REQUEST['delivery_id']));

        /* 根据发货单id查询发货单信息 */
        if (!empty($delivery_id)) {
            $delivery_order = $this->delivery_order_info($delivery_id);
        } else {
            die('order does not exist');
        }

        /* 如果管理员属于某个办事处，检查该订单是否也属于这个办事处 */
        $sess = $this->admin_info;
        $admin_id = $sess['user_id'];
        $where = "user_id = '$admin_id'";
        $admin_agency_id = Model('admin')->select_admin_info('agency_id',$where);
        if ($admin_agency_id['agency_id'] > 0){
            if ($delivery_order['agency_id'] != $admin_agency_id['agency_id']){
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage(L('priv_error',''), $link);
            }
            /* 取当前办事处信息 */
            $where = "agency_id = ".$admin_agency_id['agency_id'];
            $agency_name = Model('agency')->select_agency_info('agency_name',$where);
            $delivery_order['agency_name'] = $agency_name['agency_name'];
        }
        /* 取得用户名 */
        if ($delivery_order['user_id'] > 0) {
            $user = user_info($delivery_order['user_id']);
            if (!empty($user)) {
                $delivery_order['user_name'] = $user['alias'];
            }
        }
        /* 取得区域名 */
        $delivery_order['region'] = get_province_city($delivery_order['province'],$delivery_order['city'],$delivery_order['district']);
        /* 是否保价 */
        $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;
        /* 取得发货单商品 */
        $where = "delivery_id = " . $delivery_order['delivery_id'];
        $goods_list = Model('delivery_goods')->get_delivery_goods_list('*',$where);
        /* 是否存在实体商品 */
        $exist_real_goods = 0;
        if ($goods_list) {
            foreach ($goods_list as $value) {
                if ($value['is_real']) {
                    $exist_real_goods++;
                }
            }
        }

        /* 取得订单操作记录 */
        $act_list = array();
        $orderby = 'log_time DESC,action_id DESC';
        $where = 'order_id = ' .$delivery_order['order_id'].' AND action_place = 1';
        $res = Model('order')->get_order_action_list('*',$where,$orderby);
        foreach ($res as $key => $row) {
            $row['order_status']    = L('os')[$row['order_status']];
            $row['pay_status']      = L('ps')[$row['pay_status']];
            $row['shipping_status'] = L('ss')[$row['shipping_status']];
            $row['action_time']     = local_date(C('time_format'), $row['log_time']);
            $act_list[] = $row;
        }
        Tpl::assign('action_list', $act_list);

        /* 模板赋值 */
        //电话号码加密  代码增加  开始 by 吴博  2017-07-24 15.00
        $delivery_order['tel1'] = $delivery_order['tel'];
        $delivery_order['tel'] = jiaMiPhone($delivery_order['tel']);
        $delivery_order['mobile1'] = $delivery_order['mobile'];
        $delivery_order['mobile'] = jiaMiPhone($delivery_order['mobile']);
        //电话号码加密  代码增加  结束 by 吴博  2017-07-24 15.00
        Tpl::assign('delivery_order', $delivery_order);
        Tpl::assign('exist_real_goods', $exist_real_goods);
        Tpl::assign('goods_list', $goods_list);
        Tpl::assign('delivery_id', $delivery_id); // 发货单id

        /* 显示模板 */
        Tpl::assign('ur_here', L('delivery_operate') . L('detail'));
        Tpl::assign('action_link', array('href' => 'order.php?act=delivery_list&' . list_link_postfix(), 'text' => L('09_delivery_order')));
        Tpl::display('delivery_info.htm');
    }

    /**
     * @return 删除发货单
     */
    public function remove_invoice() {
        // 删除发货单
        $delivery_id = $_REQUEST['delivery_id'];
        $delivery_id = is_array($delivery_id) ? $delivery_id : array($delivery_id);

        foreach ($delivery_id as $value_is) {
            $value_is = intval(trim($value_is));

            // 查询：发货单信息
            $delivery_order = $this->delivery_order_info($value_is);

            // 如果status是已发货并且发货单号不为空
            if ($delivery_order['status'] == 0 && $delivery_order['invoice_no'] != '') {
                /* 更新：删除订单中的发货单号 */
                $this->del_order_invoice_no($delivery_order['order_id'], $delivery_order['invoice_no']);
            }

            // 更新：删除发货单
            $res = Model('delivery_order')->delete_delivery_order("delivery_id = '$value_is'");
        }
        /* 返回 */
        showMessage(L('tips_delivery_del'), array(array('href' => 'index.php?act=order&op=delivery_list', 'text' => '返回发货单列表')));

    }

    /**
     * @return 发货单发货
     */
    public function delivery_ship() {
        /* 检查权限 */
        //admin_priv('delivery_view');

        /* 定义当前时间 */
        define('GMTIME_UTC', gmtime()); // 获取 UTC 时间戳

        /* 取得参数 */
        $delivery = array();
        $order_id = intval(trim($_REQUEST['order_id']));        // 订单id
        $delivery_id = intval(trim($_REQUEST['delivery_id']));        // 发货单id
        $delivery['invoice_no'] = isset($_REQUEST['invoice_no']) ? trim($_REQUEST['invoice_no']) : '';
        $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';

        /* 根据发货单id查询发货单信息 */
        if (!empty($delivery_id)) {
            $delivery_order = $this->delivery_order_info($delivery_id);
        } else {
            die('order does not exist');
        }

        /* 查询订单信息 */
        $order = $this->select_order_format_info($order_id);
        /* 检查此单发货商品库存缺货情况 */
        $field = "delivery_goods.goods_id, delivery_goods.is_real, delivery_goods.product_id, SUM(delivery_goods.send_number) AS sums, 
	            IF(delivery_goods.product_id > 0, products.product_number, goods.goods_number) AS storage, goods.goods_name, delivery_goods.send_number";
        $where = "delivery_goods.delivery_id = '$delivery_id' AND delivery_goods.product_id = products.product_id";
        $delivery_stock_result = Model('delivery_goods')->get_delivery_goods_goods_products_list($field,$where,'','','delivery_goods.product_id');
        /* 如果商品存在规格就查询规格，如果不存在规格按商品库存查询 */
        if (!empty($delivery_stock_result)) {
            foreach ($delivery_stock_result as $value) {
                if (($value['sums'] > $value['storage'] || $value['storage'] <= 0) && ((C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) || (C('use_storage') == '0' && $value['is_real'] == 0))) {
                    /* 操作失败 */
                    $links = array('text' => L('order_info',''), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
                    showMessage(sprintf(L('act_good_vacancy',''), $value['goods_name']),$links);
                    break;
                }
            }
        } else {
            $field = "delivery_goods.goods_id, delivery_goods.is_real, SUM(delivery_goods.send_number) AS sums, goods.goods_number, goods.goods_name, delivery_goods.send_number";
            $where = "delivery_goods.delivery_id = '$delivery_id'";
            $delivery_stock_result = Model('delivery_goods')->get_delivery_goods_goods_list($field,$where,'','','delivery_goods.goods_id');
            foreach ($delivery_stock_result as $value) {
                if (($value['sums'] > $value['goods_number'] || $value['goods_number'] <= 0) && ((C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) || (C('use_storage') == '0' && $value['is_real'] == 0))) {
                    /* 操作失败 */
                    $links = array('text' => L('order_info',''), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
                    showMessage(sprintf(L('act_good_vacancy',''), $value['goods_name']),$links);
                    break;
                }
            }
        }

        /* 如果使用库存，且发货时减库存，则修改库存 */
        if (C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) {
            foreach ($delivery_stock_result as $value) {
                if ($value['is_real'] != 0) {
                    if (!empty($value['product_id'])) {
                        $data = array();
                        $data['product_number'] = $value['sums'];
                        Model('products')->update_products_setDec("product_id = " . $value['product_id'],$data);
                    }
                    $data = array();
                    $data['goods_number'] = $value['sums'];
                    Model('goods')->update_goods_setDec("goods_id = " . $value['goods_id'],$data);
                }
            }
        }

        /* 修改发货单信息 */
        $invoice_no = str_replace(',', '<br>', $delivery['invoice_no']);
        $invoice_no = trim($invoice_no, '<br>');
        $_delivery['invoice_no'] = $invoice_no;
        $_delivery['status'] = 0; // 0，为已发货
        $query = Model('delivery_order')->update_delivery_order($_delivery,"delivery_id = $delivery_id");
        if (!$query) {
            $links = array('text' => L('delivery_sn','').L('detail',''), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
            showMessage(sprintf(L('act_false',''), $value['goods_name']),$links);
        }

        /* 标记订单为已确认 "已发货" */
        /* 更新发货时间 */
        $order_finish = $this->get_all_delivery_finish($order_id);
        $shipping_status = ($order_finish == 1) ? SS_SHIPPED : SS_SHIPPED_PART;
        $arr['shipping_status'] = $shipping_status;
        $arr['shipping_time'] = gmtime(); // 发货时间
        $arr['invoice_no'] = trim($order['invoice_no'] . '<br>' . $invoice_no, '<br>');
        $this->update_order($order_id, $arr);

        /* 发货单发货记录log */
        $this->order_action($order['order_sn'], OS_CONFIRMED, $shipping_status, $order['pay_status'], $action_note, null, 1);

        /* 如果当前订单已经全部发货 */
        if ($order_finish) {
            /* 如果订单用户不为空，计算发放积分*/
            if ($order['user_id'] > 0) {
                /* 取得用户信息 */
                $user = user_info($order['user_id']);
                /* 计算并发放积分 */
                $integral = $this->integral_to_give($order);
                log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf(L('order_gift_integral',''), $order['order_sn']));
            }
            /* 发推送通知 */
            $cfg = C('send_ship_email');
            if ($cfg == '1') {
                $pay_timeds = local_date('Y-m-d H:i');
                $userid = $order['user_id'];
                /* 发送推送通知*/
                $wheres = "user_id = '$userid'";
                $userintos = Model('users')->select_users_info('user_name',$wheres);
                $user_names = $userintos['user_name'];
                $message['to_member_id'] = ',' . $userid . ',';
                $message['message_title'] = '订单发货';
                if (!empty($invoice_no)) {
                    $invoice_nos = $invoice_no;
                } else {
                    $invoice_nos = '无';
                }
                $message['message_body'] = '亲爱的' . $user_names . '用户。你好！您的订单' . $order['order_sn'] . '已于' . $pay_timeds . '按照您的要求方式给您发货了。发货单号是：' . $invoice_nos;
                $message['message_time'] = gmtime();
                $message['message_type'] = 1;
                $message['tuisong_type'] = 1;
                $messageid = Model('message')->insert_message($message);
                if(!empty($userintos)&& !empty($messageid)){
                    $content = '淘玉发货：您的订单:' . $order['order_sn'] . ', 已经发货,快递单号：' . $invoice_nos;
                    /*提交推送 查看是否符合条件推送*/
                    if(!empty($user_names)){
                        $res = send_jpush_message(1, $userid , ['title'=>'订单发货','body'=>$content]);
                    }
                }
            }

            /* 如果需要，发短信 */
            if (C('sms_order_shipped') == '1') {
                $userid = $order['user_id'];
                $wheres = "user_id = '$userid'";
                $userintos = Model('users')->select_users_info('mobile_phone',$wheres);
                $mobile_phones = $userintos['mobile_phone'];
                if (!empty($mobile_phones)) {
                    $param = array();
                    $param['consignee'] = $order['consignee'];
                    if (!empty($invoice_no)) {
                        $invoice_nos = $invoice_no;
                    } else {
                        $invoice_nos = '无';
                    }
                    $param['invoice_no'] = $invoice_nos;
                    $param['address'] = $order['address'];
                    //$result = send_sms_msg($mobile_phones,'order_send',$param);
                }
            }
        }
        /* 清除缓存 */
        clear_cache_files();
        /* 操作成功 */
        $links[] = array('text' => L('delivery_sn'). L('lists'), 'href' => 'index.php?act=order&op=delivery_list');
        $links[] = array('text' => L('delivery_sn') . L('detail'), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
        showMessage(L('act_ok'), $links);
    }

    /**
     * @return 发货单取消发货
     */
    public function delivery_cancel_ship() {
        /* 检查权限 */
        //admin_priv('delivery_view');

        /* 取得参数 */
        $delivery = '';
        $order_id = intval(trim($_REQUEST['order_id']));        // 订单id
        $delivery_id = intval(trim($_REQUEST['delivery_id']));        // 发货单id
        $delivery['invoice_no'] = isset($_REQUEST['invoice_no']) ? trim($_REQUEST['invoice_no']) : '';
        $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';

        /* 根据发货单id查询发货单信息 */
        if (!empty($delivery_id)) {
            $delivery_order = $this->delivery_order_info($delivery_id);
        } else {
            die('order does not exist');
        }

        /* 查询订单信息 */
        $order = $this->select_order_format_info($order_id);

        /* 取消当前发货单物流单号 */
        $_delivery['invoice_no'] = '';
        $_delivery['status'] = 2;
        $query = Model('delivery_order')->update_delivery_order($_delivery,"delivery_id = $delivery_id");
        if (!$query) {
            /* 操作失败 */
            $links[] = array('text' => L('delivery_sn').L('detail'), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
            showMessage(L('act_false'), $links);
        }

        /* 修改定单发货单号 */
        $invoice_no_order = explode('<br>', $order['invoice_no']);
        $invoice_no_delivery = explode('<br>', $delivery_order['invoice_no']);
        foreach ($invoice_no_order as $key => $value) {
            $delivery_key = array_search($value, $invoice_no_delivery);
            if ($delivery_key !== false) {
                unset($invoice_no_order[$key], $invoice_no_delivery[$delivery_key]);
                if (count($invoice_no_delivery) == 0) {
                    break;
                }
            }
        }
        $_order['invoice_no'] = implode('<br>', $invoice_no_order);
        $delivery_id = Model('delivery_order')->select_delivery_order_info('delivery_id',"order_id=" . $order_id);
        $delivery_id = $delivery_id['delivery_id'];
        /* 更新配送状态 */
        $order_finish = $this->get_all_delivery_finish($order_id);
        $shipping_status = ($order_finish == -1) ? SS_SHIPPED_PART : SS_SHIPPED_ING;
        $arr['shipping_status'] = $shipping_status;
        if ($shipping_status == SS_SHIPPED_ING) {
            $arr['shipping_time'] = ''; // 发货时间
        }
        $arr['invoice_no'] = $_order['invoice_no'];
        $this->update_order($order_id, $arr);
        /* 发货单取消发货记录log */
        $this->order_action($order['order_sn'], $order['order_status'], $shipping_status, $order['pay_status'], $action_note, null, 1);
        /* 如果使用库存，则增加库存 */
        if (C('use_storage') == '1' && C('stock_dec_time') == SDT_SHIP) {
            // 检查此单发货商品数量
            $field = "goods_id,product_id,SUM(send_number) AS sums";
            $where = "delivery_id = '$delivery_id' GROUP BY goods_id ";
            $delivery_stock_result = Model('delivery_goods')->get_delivery_goods_list($field,$where);
            foreach ($delivery_stock_result as $key => $value) {
                if (!empty($value['product_id'])) {
                    $wheres = "product_id = " . $value['product_id'];
                    Model('products')->update_products(array('product_number'=>"product_number + " . $value['sums']),$wheres);
                }
                $wheres = "goods_id = " . $value['goods_id'];
                Model('goods')->update_goods(array('goods_number'=>"goods_number + " . $value['sums']),$wheres);
            }
        }
        if ($order['user_id'] > 0){
            /* 取得用户信息 */
            $user = user_info($order['user_id']);
            /* 计算并退回积分 */
            $integral = $this->integral_to_give($order);
            log_account_change($order['user_id'], 0, 0, (-1) * intval($integral['rank_points']), (-1) * intval($integral['custom_points']), sprintf(L('return_order_gift_integral'), $order['order_sn']));
        }
        /* 清除缓存 */
        clear_cache_files();

        /* 操作成功 */
        $links[] = array('text' => L('delivery_sn') . L('detail'), 'href' => 'index.php?act=order&op=delivery_info&delivery_id=' . $delivery_id);
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
     * @return 获取快递物流信息
     */
    public function express(){
        $canshu = $_GET['nu'];
        $shipping_name = $_GET['com'];
        if(!empty($canshu)){
            include_once(BASE_API_PATH .DS. 'kuaidi'.DS.'kuaidi.php');
            $express = new Express();
            $result = $express->getorder($shipping_name,$canshu);
            $result['com'] =$shipping_name;
            $result['nu'] = $canshu;
            $kdintos = $result['data'];
            foreach($kdintos as $key=>$value){
                if(empty($value['ftime'])){
                    $result['data'][$key]['context'] = L('information_updating');
                }
            }
            $kddatas = $result['data'];
            $htmlkdlist = '';
            foreach($kddatas as $k=>$v){
                if($k == 0){
                    $yangshi1 = 'style="margin-top:10px;"';
                    $yangshi2 = '';
                    $yangshi3 = '';
                }else{
                    $yangshi1 = '';
                    $yangshi2 = 'style="background:#ccc;"';
                    $yangshi3 = 'style="color:#666"';
                }
                $htmlkdlist .= '<dl '.$yangshi1.'>';
                $htmlkdlist .='<dt '.$yangshi2.'></dt>';
                $htmlkdlist .='<dd>';
                $htmlkdlist .='<p '.$yangshi3.'>';
                $htmlkdlist .=$v['context'];
                $htmlkdlist .='</p>';
                $htmlkdlist .='<strong '.$yangshi3.'>'.$v['ftime'].'</strong>';
                $htmlkdlist .='</dd>';
                $htmlkdlist .='</dl>';
            }
            $htmlkdtop = '<div class="detail_top">';
            $htmlkdtop .='<dl>';
            $htmlkdtop .= '<dd>';
            $htmlkdtop .= '</dd>';
            $htmlkdtop .= '</dl>';
            $htmlkdtop .= '</div>';
            $htmlkdtop .= '<div class="kd_wl">';
            $htmlkdtop .= $htmlkdlist;
            $htmlkdtop .= '</div>';
            if(empty($result['message'])){
                echo L('order_not_exist',array('快递单号'));
            }
            echo $htmlkdtop;
        }else{
            echo L('order_not_exist',array($canshu));
        }
    }

    /**
     * @return 获取订单列表
     * @return array
     */
    private function get_order_info_list() {
        $result = get_filter();
        if ($result === false){
            /* 过滤信息 */
            $filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
            if (!empty($_GET['is_ajax']) && $_GET['is_ajax'] == 1){
                $_REQUEST['consignee'] = json_str_iconv($_REQUEST['consignee']);
            }
            $filter['consignee'] = empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);
            $filter['email'] = empty($_REQUEST['email']) ? '' : trim($_REQUEST['email']);
            $filter['address'] = empty($_REQUEST['address']) ? '' : trim($_REQUEST['address']);
            $filter['zipcode'] = empty($_REQUEST['zipcode']) ? '' : trim($_REQUEST['zipcode']);
            $filter['tel'] = empty($_REQUEST['tel']) ? '' : trim($_REQUEST['tel']);
            $filter['mobile'] = empty($_REQUEST['mobile']) ? 0 : intval($_REQUEST['mobile']);
            $filter['country'] = empty($_REQUEST['country']) ? 0 : intval($_REQUEST['country']);
            $filter['province'] = empty($_REQUEST['province']) ? 0 : intval($_REQUEST['province']);
            $filter['city'] = empty($_REQUEST['city']) ? 0 : intval($_REQUEST['city']);
            $filter['district'] = empty($_REQUEST['district']) ? 0 : intval($_REQUEST['district']);
            $filter['shipping_id'] = empty($_REQUEST['shipping_id']) ? 0 : intval($_REQUEST['shipping_id']);
            $filter['pay_id'] = empty($_REQUEST['pay_id']) ? 0 : intval($_REQUEST['pay_id']);
            $filter['order_status'] = isset($_REQUEST['order_status']) ? intval($_REQUEST['order_status']) : -1;
            $filter['shipping_status'] = isset($_REQUEST['shipping_status']) ? intval($_REQUEST['shipping_status']) : -1;
            $filter['pay_status'] = isset($_REQUEST['pay_status']) ? intval($_REQUEST['pay_status']) : -1;
            $filter['user_id'] = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
            $filter['user_name'] = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
            $filter['composite_status'] = isset($_REQUEST['composite_status']) ? intval($_REQUEST['composite_status']) : -1;
            $filter['group_buy_id'] = isset($_REQUEST['group_buy_id']) ? intval($_REQUEST['group_buy_id']) : 0;
            /*预售 */
            $filter['pre_sale_id'] = isset($_REQUEST['pre_sale_id']) ? intval($_REQUEST['pre_sale_id']) : 0;
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'add_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['start_time'] = empty($_REQUEST['start_time']) ? '' : (strpos($_REQUEST['start_time'], '-') > 0 ?  local_strtotime($_REQUEST['start_time']) : $_REQUEST['start_time']);
            $filter['end_time'] = empty($_REQUEST['end_time']) ? '' : (strpos($_REQUEST['end_time'], '-') > 0 ?  local_strtotime($_REQUEST['end_time']) : $_REQUEST['end_time']);
            $filter['payment'] = isset($_REQUEST['payment']) ? intval($_REQUEST['payment']) : 0;
            $filter['supp'] = (isset($_REQUEST['supp']) && !empty($_REQUEST['supp']) && intval($_REQUEST['supp'])>0) ? intval($_REQUEST['supp']) : 0;
            $filter['suppid'] = (isset($_REQUEST['suppid']) && !empty($_REQUEST['suppid']) && intval($_REQUEST['suppid'])>0) ? intval($_REQUEST['suppid']) : 0;
            $filter['inv_status'] = empty($_REQUEST['inv_status']) ? '' : trim($_REQUEST['inv_status']);
            $filter['inv_type'] = empty($_REQUEST['inv_type']) ? '' : trim($_REQUEST['inv_type']);
            $filter['vat_inv_consignee_name'] = empty($_REQUEST['vat_inv_consignee_name']) ? '' : trim($_REQUEST['vat_inv_consignee_name']);
            $filter['vat_inv_consignee_phone'] = empty($_REQUEST['vat_inv_consignee_phone']) ? '' : trim($_REQUEST['vat_inv_consignee_phone']);
            $filter['add_time'] = empty($_REQUEST['add_time']) ? '' : (strpos($_REQUEST['add_time'], '-') > 0 ?  local_strtotime($_REQUEST['add_time']) : $_REQUEST['add_time']);
            if(!empty($filter['add_time'])){
                $filter['start_time'] = $filter['add_time'];
                $filter['end_time'] = $filter['add_time'] + '86400';
            }
            $where = ($filter['supp']>0) ? ' where order_info.supplier_id > 0' : ' where order_info.supplier_id = 0';

            if ($filter['suppid']){
                $where = ' AND order_info.supplier_id = '.$filter['suppid'];
            }
            if ($filter['order_sn']){
                $where .= " AND order_info.order_sn LIKE '%" . mysql_like_quote($filter['order_sn']) . "%'";
            }
            if ($filter['consignee']){
                $where .= " AND order_info.consignee LIKE '%" . mysql_like_quote($filter['consignee']) . "%'";
            }
            if ($filter['email']){
                $where .= " AND order_info.email LIKE '%" . mysql_like_quote($filter['email']) . "%'";
            }
            if ($filter['address']){
                $where .= " AND order_info.address LIKE '%" . mysql_like_quote($filter['address']) . "%'";
            }
            if ($filter['zipcode']){
                $where .= " AND order_info.zipcode LIKE '%" . mysql_like_quote($filter['zipcode']) . "%'";
            }
            if ($filter['tel']){
                $where .= " AND order_info.tel LIKE '%" . mysql_like_quote($filter['tel']) . "%'";
            }
            if ($filter['mobile']){
                $where .= " AND order_info.mobile LIKE '%" .mysql_like_quote($filter['mobile']) . "%'";
            }
            if ($filter['country']){
                $where .= " AND order_info.country = '$filter[country]'";
            }
            if ($filter['province']){
                $where .= " AND order_info.province = '$filter[province]'";
            }
            if ($filter['city']){
                $where .= " AND order_info.city = '$filter[city]'";
            }
            if ($filter['district']){
                $where .= " AND order_info.district = '$filter[district]'";
            }
            if ($filter['shipping_id']){
                $where .= " AND order_info.shipping_id  = '$filter[shipping_id]'";
            }
            if ($filter['pay_id']){
                $where .= " AND order_info.pay_id  = '$filter[pay_id]'";
            }
            if ($filter['order_status'] != -1){
                $where .= " AND order_info.order_status  = '$filter[order_status]'";
            }
            if ($filter['shipping_status'] != -1){
                $where .= " AND order_info.shipping_status = '$filter[shipping_status]'";
            }
            if ($filter['pay_status'] != -1){
                $where .= " AND order_info.pay_status = '$filter[pay_status]'";
            }
            if ($filter['user_id']){
                $where .= " AND order_info.user_id = '$filter[user_id]'";
            }
            if ($filter['user_name']){
                $where .= " AND users.user_name LIKE '%" . mysql_like_quote($filter['user_name']) . "%'";
            }
            if ($filter['start_time']){
                $where .= " AND order_info.add_time >= '$filter[start_time]'";
            }
            if ($filter['end_time']){
                $where .= " AND order_info.add_time <= '$filter[end_time]'";
            }
            if ($filter['payment']){
                $where .= " AND order_info.pay_id = '$filter[payment]'";
            }
            /*增值税发票_添加_START_www.taoyumall.com*/
            if($filter['inv_status']){
                $where .= " AND order_info.inv_status = '$filter[inv_status]'";
            }
            if($filter['inv_type']){
                $where .= " AND order_info.inv_type = '$filter[inv_type]'";
            }
            if($filter['vat_inv_consignee_name']){
                $where .= " AND order_info.inv_consignee_name = '$filter[vat_inv_consignee_name]'";
            }
            if($filter['vat_inv_consignee_phone']){
                $where .= " AND order_info.inv_consignee_phone = '$filter[vat_inv_consignee_phone]'";
            }
            if($_REQUEST['act'] == 'invoice_list' || (isset($_REQUEST['act_detail'])&&$_REQUEST['act_detail']=='invoice_query')){
                $where .= " AND order_info.inv_type != ''";
            }            
            //综合状态
            switch($filter['composite_status']){
                case CS_AWAIT_PAY :
                    $where .= $this->order_query_sql('await_pay');
                    break;
                case CS_AWAIT_SHIP :
                    $where .= $this->order_query_sql('await_ship');
                    break;
                case CS_FINISHED :
                    $where .= $this->order_query_sql('finished');
                    break;

                case PS_PAYING :
                    if ($filter['composite_status'] != -1){
                        $where .= " AND order_info.pay_status = '$filter[composite_status]' ";
                    }
                    break;
                case OS_SHIPPED_PART :
                    if ($filter['composite_status'] != -1){
                        $where .= " AND order_info.shipping_status  = '$filter[composite_status]'-2 ";
                    }
                    break;
                default:
                    if ($filter['composite_status'] != -1){
                        $where .= " AND order_info.order_status = '$filter[composite_status]' ";
                    }
            }
            /* 如果管理员属于某个办事处，只列出这个办事处管辖的订单 */
            $sess = $this->admin_info;
            $admin_id = $sess['user_id'];
            $wherem = array('user_id'=>$admin_id);
            $agency_id = Model('admin')->select_admin_info('agency_id',$wherem);
            $agency_id = $agency_id['agency_id'];
            if ($agency_id > 0){
                $where .= " AND order_info.agency_id = '$agency_id' ";
            }

            /* 分页大小 */
            $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

            if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0){
                $filter['page_size'] = intval($_REQUEST['page_size']);
            }elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0){
                $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
            }else{
                $filter['page_size'] = 15;
            }
            /* 记录总数 */
            $model = Model('order');
            if ($filter['user_name']){
                $wheres = substr(trim($where), 6);
                $filter['record_count'] = $model->get_order_info_users_count($wheres);
            }else{
                $filter['record_count'] = $model->get_order_info_count($where);
            }

            $filter = page_and_size($filter);
            /* 查询 */
            if($filter['supp']){
                $sql = "SELECT order_info.order_id, order_info.order_sn, order_info.add_time, order_info.order_status, order_info.shipping_status, order_info.order_amount, order_info.money_paid,order_info.yaojiang,order_info.pay_name," .
                    "order_info.pay_status, order_info.consignee, order_info.address, order_info.email, order_info.tel, order_info.extension_code, order_info.extension_id,order_info.rebate_ispay,users.alias,users.real_name,users.parent_id, " .
                    "(" . $this->order_amount_field('order_info.') . ") AS total_fee, " .
                    "IFNULL(users.user_name,'anonymous') AS buyer,supplier_name,order_info.froms,is_pickup  ".
                    /*增值税发票_添加_START_www.taoyumall.com*/
                    ',order_info.mobile,order_info.inv_payee,order_info.inv_content,order_info.inv_type,order_info.vat_inv_company_name'.
                    ',order_info.vat_inv_taxpayer_id,order_info.vat_inv_registration_address,order_info.vat_inv_registration_phone'.
                    ',order_info.vat_inv_deposit_bank,order_info.vat_inv_bank_account'.
                    ',order_info.inv_consignee_name,order_info.inv_consignee_phone,order_info.inv_consignee_country'.
                    ',order_info.inv_consignee_province,order_info.inv_consignee_city,order_info.inv_consignee_district'.
                    ',order_info.inv_consignee_address,order_info.inv_status,order_info.inv_payee_type,order_info.inv_money'.
                    /*增值税发票_添加_END_www.taoyumall.com*/
                    " FROM " .  Model()->tablename('order_info') . " AS order_info " .
                    " LEFT JOIN " .  Model()->tablename('supplier') . " AS s ON s.supplier_id=order_info.supplier_id ".
                    " LEFT JOIN " . Model()->tablename('users'). " AS users ON users.user_id=order_info.user_id ". $where .
                    " ORDER BY $filter[sort_by] $filter[sort_order] ";

            }else{
                $sql = "SELECT order_info.order_id, order_info.order_sn, order_info.add_time, order_info.order_status, order_info.shipping_status, order_info.order_amount, order_info.money_paid,order_info.yaojiang,order_info.pay_name," .
                    "order_info.pay_status, order_info.consignee, order_info.address, order_info.email, order_info.tel, order_info.extension_code, order_info.extension_id,order_info.rebate_ispay,users.alias,users.real_name,users.parent_id," .
                    "(" . $this->order_amount_field('order_info.') . ") AS total_fee, " .
                    "IFNULL(users.user_name,'anonymous') AS buyer, order_info.froms , is_pickup ".
                    /*增值税发票_添加_START_www.taoyumall.com*/
                    ',order_info.mobile,order_info.inv_payee,order_info.inv_content,order_info.inv_type,order_info.vat_inv_company_name'.
                    ',order_info.vat_inv_taxpayer_id,order_info.vat_inv_registration_address,order_info.vat_inv_registration_phone'.
                    ',order_info.vat_inv_deposit_bank,order_info.vat_inv_bank_account'.
                    ',order_info.inv_consignee_name,order_info.inv_consignee_phone,order_info.inv_consignee_country'.
                    ',order_info.inv_consignee_province,order_info.inv_consignee_city,order_info.inv_consignee_district'.
                    ',order_info.inv_consignee_address,order_info.inv_status,order_info.inv_payee_type,order_info.inv_money'.
                    /*增值税发票_添加_END_www.taoyumall.com*/
                    " FROM " . Model()->tablename('order_info') . " AS order_info " .
                    " LEFT JOIN " .Model()->tablename('users'). " AS users ON users.user_id=order_info.user_id ". $where .
                    " ORDER BY $filter[sort_by] $filter[sort_order] ";
            }

            foreach (array('order_sn', 'consignee', 'email', 'address', 'zipcode', 'tel', 'user_name') AS $val){
                $filter[$val] = stripslashes($filter[$val]);
            }
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        /* 格式化数据 */
        if(!empty($row)){
            foreach ($row AS $key => $value){
                $row[$key]['tel'] = jiaMiPhone($value['tel']);
                $row[$key]['mobile'] = jiaMiPhone($value['mobile']);
                $row[$key]['formated_order_amount'] = price_format($value['order_amount']);
                $row[$key]['formated_money_paid'] = price_format($value['money_paid']);
                $row[$key]['formated_total_fee'] = price_format($value['total_fee']);
                $row[$key]['short_order_time'] = local_date('Y-m-d H:i', $value['add_time']);
                /*增值税发票_添加_START_www.taoyumall.com*/
                $row[$key]['formatted_add_time'] = local_date('Y-m-d H:i',$value['add_time']);
                $row[$key]['formatted_inv_money'] = price_format($value['inv_money']);
                if($value['parent_id'] == 0){
                    $row[$key]['parent_id'] = '玉林';
                }else{
                    $where = array('user_id' => $value['parent_id']);
                    $parent_idinto = Model('users')->select_users_info('user_name,alias,real_name',$where);
                    if(!empty($parent_idinto['real_name'])){
                        $row[$key]['parent_id']= $parent_idinto['real_name'];
                    }else{
                        if (!empty($parent_idinto['alias'])){
                            $row[$key]['parent_id'] = $parent_idinto['alias'];
                        }else{
                            $row[$key]['parent_id'] = $parent_idinto['user_name'];
                        }
                    }
                }
                if ($value['order_status'] == OS_INVALID || $value['order_status'] == OS_CANCELED){
                    $row[$key]['can_remove'] = 1;
                }else{
                    $row[$key]['can_remove'] = 0;
                }
                $where = array('order_sn' => $row[$key]['order_sn']);
                $tuihuan_info = Model('back_order')->select_back_order_info('*',$where,'back_id DESC',1);
                if (!empty($tuihuan_info)){
                    switch ($tuihuan_info['back_type']){
                        case 1 :
                            $back_type = "退货";
                            break;
                        case 3 :
                            $back_type = "申请维修";
                            break;
                        case 4 :
                            $back_type = "退款";
                            break;
                        default :
                            break;
                    }

                    switch ($tuihuan_info['status_back']){
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
                            $status_back = "退款(无需退货)";
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
                        default :
                            break;
                    }

                    $row[$key]['tuihuan']['back_type'] = $back_type;
                    $row[$key]['tuihuan']['status_back'] = $status_back;
                }
            }
        }
        if(!empty($row)){
                foreach($row as $k=> $v){
                $order_id = $v['order_id'];
                $res = Model('order')->get_order_goods_list('goods_id',"order_id = $order_id",'','');
                $res3 = [];
                foreach($res as $k1=>$v1) {
                    $goods_id = $v1['goods_id'];
                    $where = array('goods_id' =>$goods_id);
                    $res1 = Model('goods')->select_goods_info('goods_sn ,original_img',$where);
                    $res1['original_img'] = get_imgurl_oss($res1['original_img'],50,50);
                    $res3[$k1] = $res1;
                }
                $row[$k]['sn_img'] = $res3;
            }
        }        
        $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * @return 取得状态列表
     * @param  string  $type   类型：all | order | shipping | payment
     * @return array
     */
    private function get_status_list($type = 'all'){
        $list = array();
        if ($type == 'all' || $type == 'order'){
            $pre = $type == 'all' ? 'os_' : '';
            foreach (L('os') AS $key => $value){
                $list[$pre . $key] = $value;
            }
        }
        if ($type == 'all' || $type == 'shipping'){
            $pre = $type == 'all' ? 'ss_' : '';
            foreach (L('ss') AS $key => $value){
                $list[$pre . $key] = $value;
            }
        }
        if ($type == 'all' || $type == 'payment'){
            $pre = $type == 'all' ? 'ps_' : '';
            foreach (L('ps') AS $key => $value){
                $list[$pre . $key] = $value;
            }
        }
        return $list;
    }

    /**
     * @return  获取订单详情
     * @return  array
     */
    private function get_order_info() {
        $model = Model('order');
        /* 根据订单id或订单号查询订单信息 */
        if (isset($_REQUEST['order_id'])){
            $order_id = intval($_REQUEST['order_id']);
            /*获取订单格式化信息*/
            $order = $this->select_order_format_info($order_id);
        }elseif (isset($_REQUEST['order_sn'])){
            $order_sn = trim($_REQUEST['order_sn']);
            $order = $this->select_order_format_info(0, $order_sn);
        }else{
            /* 如果参数不存在，退出 */
            die('invalid parameter');
        }
        /*管理员权限检查*/
        $sess = $this->admin_info;
        $admin_id = $sess['user_id'];
        $admin_model = Model('admin');
        $where = array('user_id'=>$admin_id);
        $agency_id = $admin_model->select_admin_info('agency_id', $where);
        $agency_id = $agency_id['agency_id'];
        if ($agency_id > 0){
            if ($order['agency_id'] != $agency_id){
                showMessage(L('priv_error'));
            }
        }

        /* 取得上一个、下一个订单号 */
        if (!empty($_COOKIE['ECSCP']['lastfilter'])){
            $filter = unserialize(urldecode($_COOKIE['ECSCP']['lastfilter']));
            if (!empty($filter['composite_status'])){
                $wherek = '';
                //综合状态
                switch($filter['composite_status']){
                    case CS_AWAIT_PAY :
                        $wherek .= $this->order_query_sql('await_pay');
                        break;

                    case CS_AWAIT_SHIP :
                        $wherek .= $this->order_query_sql('await_ship');
                        break;

                    case CS_FINISHED :
                        $wherek .= $this->order_query_sql('finished');
                        break;

                    default:
                        if ($filter['composite_status'] != -1){
                            $wherek .= " AND order_info.order_status = '$filter[composite_status]' ";
                        }
                }
            }
        }
        $wherew = '';
        if ($agency_id > 0){
            $wherew .= " AND agency_id = '$agency_id'";
        }
        if (!empty($wherek)){
            $wherew .= $wherek;
        }
        $prev_id = $model->select_order_info_info("MAX(order_id) as prev_id",$wherew.'order_id <'. $order['order_id']);
        $next_id = $model->select_order_info_info("MIN(order_id) as next_id",$wherew.'order_id >'. $order['order_id']);
        $order['prev_id'] = $prev_id['prev_id'];
        $order['next_id'] = $next_id['next_id'];
        /* 取得用户名 */
        if ($order['user_id'] > 0){
            $user = user_info($order['user_id']);
            if (!empty($user)){
                $order['user_name'] = $user['alias'];
            }
        }
        /* 格式化金额 */
        if ($order['order_amount'] < 0){
            $order['money_refund'] = abs($order['order_amount']);
            $order['formated_money_refund'] = price_format(abs($order['order_amount']));
        }
        /*增值税发票_添加_START_www.taoyumall.com*/
        /*增值税发票收票地址*/
        if($order['inv_type'] == 'vat_invoice'){
            $order['inv_complete_address'] = $this->get_inv_complete_address($order);
        }
        /*发票金额*/
        $order['formatted_inv_money'] = price_format($order['inv_money']);
        /* 其他处理 */
        $order['order_time']    = local_date(C('time_format'), $order['add_time']);
        $order['pay_time']      = $order['pay_time'] > 0 ?
            local_date(C('time_format'), $order['pay_time']) : L('ps')[PS_UNPAYED];
        $order['shipping_time'] = $order['shipping_time'] > 0 ?
            local_date(C('time_format'), $order['shipping_time']) : L('ss')[SS_UNSHIPPED];
        $order['status']        = L('os')[$order['order_status']] . ',' . L('ps')[$order['pay_status']] . ',' . L('ss')[$order['shipping_status']];
        $order['invoice_no']    = $order['shipping_status'] == SS_UNSHIPPED || $order['shipping_status'] == SS_PREPARING ? L('ss')[SS_UNSHIPPED] : $order['invoice_no'];
        /* 取得订单的来源 */
        if ($order['from_ad'] == 0){
            $order['referer'] = empty($order['referer']) ? L('from_self_site') : $order['referer'];
        }elseif ($order['from_ad'] == -1){
            $order['referer'] = L('from_goods_js') . ' ('.L('from') . $order['referer'].')';
        }else{
            /* 查询广告的名称 */
            $ad_name = Model('ads')->select_capital_brand_info('ad_name','imgs_id='.$order['from_ad']);
            $order['referer'] = L('from_ad_js') . $ad_name . ' ('.L('from') . $order['referer'].')';
        }
        $wherez = array('order_id'=>$order['order_id'],'shipping_status'=>1);
        $action_note = Model()->table('order_action')->field('action_note')->where($wherez)->order('log_time DESC')->find();
        $order['invoice_note'] = $action_note['action_note'];
        /* 取得订单商品总重量 */
        $field = "SUM(goods.goods_weight * order_goods.goods_number) AS weight ";
        $where = "order_goods.order_id = ".$order['order_id'];
        $rows = Model('order')->get_order_goods_goods_list($field,$where);
        $rows['weight'] = floatval($rows['weight']);
        $order['total_weight'] = $rows['weight'];
        /* 参数赋值：订单 */
        $order['mobile1'] =  $order['mobile'];
        $order['tel1'] =  $order['tel'];
        $order['mobile'] =  jiaMiPhone($order['mobile']);
        $order['tel'] =  jiaMiPhone($order['tel']);
        return $order;
    }

    /**
     * @return  记录订单操作记录
     * @param   string  $order_sn           订单编号
     * @param   integer $order_status       订单状态
     * @param   integer $shipping_status    配送状态
     * @param   integer $pay_status         付款状态
     * @param   string  $note               备注
     * @param   string  $username           用户名，用户自己的操作则为 buyer
     * @return  void
     */
    private function order_action($order_sn, $order_status, $shipping_status, $pay_status, $note = '', $username = null, $place = 0){
        if (is_null($username)){
            $sess = $this->admin_info;
            $admin_name = $sess['user_name'];
            $username = $admin_name;
        }
        $order_model = Model('order');
        $data = $order_model->select_order_info_info('*',"order_sn = '$order_sn'");
        $param['action_user'] = $username;
        $param['action_place'] = $place;
        $param['action_note'] = $note;
        $param['log_time'] = gmtime();
        $param['order_id'] = $data['order_id'];
        $param['order_status'] = $data['order_status'];
        $param['shipping_status'] = $data['shipping_status'];
        $param['pay_status'] = $data['pay_status'];
        return $order_model->insert_order_action($param);
    }

    /**
     * @return  获取订单中商品
     */
    private function get_order_goods($order){
        $model = Model('order');
        /* 取得订单商品及货品 */
        $goods_list = array();
        $goods_attr = array();
        $field = "order_goods.*, IF(order_goods.product_id > 0, products.product_number, goods.goods_number) AS storage, order_goods.goods_attr, order_goods.goods_attr_id, 
        goods.suppliers_id, IFNULL(brand.brand_name, '') AS brand_name, products.product_sn, goods_attr.attr_value,goods.original_img";
        $where = "order_goods.order_id =".$order['order_id'];
        $res = $model->get_order_goods($field,$where);
        foreach($res as $row){
            $row['formated_subtotal']       = price_format($row['goods_price'] * $row['goods_number']);
            $row['formated_goods_price']    = price_format($row['goods_price']);
            $row['url'] = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
            $row['original_img'] = get_imgurl_oss($row['original_img'],80,80);
            $goods_attr[] = explode(' ', trim($row['goods_attr'])); //将商品属性拆分为一个数组
            if ($row['extension_code'] == 'package_buy'){
                $row['storage'] = '';
                $row['brand_name'] = '';
            }

            $goods_list[] = $row;
        }
        foreach ($goods_list as $goods_key => $goods_val){
            $where = "back_order.order_id = " . $order['order_id'] .
                " AND back_order.goods_id = " . $goods_val['goods_id'] .
                " AND back_goods.product_id = " . $goods_val['product_id'] .
                " AND back_order.status_back < 6";
            $field = "back_order.*,back_goods.product_id";
            $back_order = Model('back_goods')->get_back_goods_back_order_list($field,$where);
            $goods_list[$goods_key]['back_can'] =  count($back_order['order_id']) > 0 ? '0' : '1';

            switch ($back_order['status_back']){
                case '3' : $sb = "已完成"; break;
                case '5' : $sb = "已申请"; break;
                default : $sb = "正在"; break;
            }

            switch ($back_order['back_type']){
                case '1' : $bt = "退货"; break;
                case '3' : $bt = "申请维修"; break;
                case '4' : $bt = "退款"; break;
                default : break;
            }

            $goods_list[$goods_key]['back_can_no'] = $sb . " " . $bt;
        };
        return $goods_list;
    }

    /**
     * @return  取得发货单信息
     * @param   int     $delivery_order   发货单id（如果delivery_order > 0 就按id查，否则按sn查）
     * @param   string  $delivery_sn      发货单号
     * @return  array   发货单信息（金额都有相应格式化的字段，前缀是formated_）
     */
    private function delivery_order_info($delivery_id, $delivery_sn = ''){
        $return_order = array();
        if (empty($delivery_id) || !is_numeric($delivery_id)){
            return $return_order;
        }
        $where = '';
        if ($delivery_id > 0){
            $where .= " delivery_id = '$delivery_id'";
        }else{
            $where .= " delivery_sn = '$delivery_sn'";
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
        $delivery = Model('delivery_order')->select_delivery_order_info('*',$where);
        if ($delivery){
            /* 格式化金额字段 */
            $delivery['formated_insure_fee']     = price_format($delivery['insure_fee'], false);
            $delivery['formated_shipping_fee']   = price_format($delivery['shipping_fee'], false);

            /* 格式化时间字段 */
            $delivery['formated_add_time']       = local_date(C('time_format'), $delivery['add_time']);
            $delivery['formated_update_time']    = local_date(C('time_format'), $delivery['update_time']);

            $return_order = $delivery;
        }

        return $return_order;
    }

    /**
     * @return  处理编辑订单时订单金额变动
     * @param   array $order 订单信息
     * @param   array $msgs 提示信息
     * @param   array $links 链接信息
     */
    private function handle_order_money_change($order, &$msgs, &$links){
        $order_id = $order['order_id'];
        if ($order['pay_status'] == PS_PAYED || $order['pay_status'] == PS_PAYING) {
            /* 应付款金额 */
            $money_dues = $order['order_amount'];
            if ($money_dues > 0) {
                /* 修改订单为未付款 */
                $this->update_order($order_id, array('pay_status' => PS_UNPAYED, 'pay_time' => 0));
                $msgs[] = L('amount_increase');
                $links[] = array('text' => L('order_info'), 'href' => 'order.php?act=info&order_id=' . $order_id);
            } elseif ($money_dues < 0) {
                $anonymous = $order['user_id'] > 0 ? 0 : 1;
                $msgs[] = L('amount_decrease');
                $links[] = array('text' => L('refund'), 'href' => 'index.php?act=order&op=process&func=load_refund&anonymous=' .
                    $anonymous . '&order_id=' . $order_id . '&refund_amount=' . abs($money_dues));
            }
        }
    }

    /**
     * @return 获取发货单列表信息
     * @return void
     */
    private function get_delivery_list(){
        $model = Model('delivery_order');
        $result = get_filter();
        if ($result === false) {
            $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

            /* 过滤信息 */
            $filter['delivery_sn'] = empty($_REQUEST['delivery_sn']) ? '' : trim($_REQUEST['delivery_sn']);
            $filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
            $filter['order_id'] = empty($_REQUEST['order_id']) ? 0 : intval($_REQUEST['order_id']);
            if ($aiax == 1 && !empty($_REQUEST['consignee'])) {
                $_REQUEST['consignee'] = json_str_iconv($_REQUEST['consignee']);
            }
            $filter['consignee'] = empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);
            $filter['status'] = isset($_REQUEST['status']) ? $_REQUEST['status'] : -1;

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'update_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $where = 'WHERE 1 ';
            if ($filter['order_sn']) {
                $where .= " AND order_sn LIKE '%" . mysql_like_quote($filter['order_sn']) . "%'";
            }
            if ($filter['consignee']) {
                $where .= " AND consignee LIKE '%" . mysql_like_quote($filter['consignee']) . "%'";
            }
            if ($filter['status'] >= 0) {
                $where .= " AND status = '" . mysql_like_quote($filter['status']) . "'";
            }
            if ($filter['delivery_sn']) {
                $where .= " AND delivery_sn LIKE '%" . mysql_like_quote($filter['delivery_sn']) . "%'";
            }

            /* 获取管理员信息 */
            $sess = $this->admin_info;
            $admin_id = $sess['user_id'];
            $wheres = "user_id = '$admin_id'";
            $admin_info = Model('admin')->select_admin_info('*',$wheres);

            /* 如果管理员属于某个办事处，只列出这个办事处管辖的发货单 */
            if ($admin_info['agency_id'] > 0) {
                $where .= " AND agency_id = '" . $admin_info['agency_id'] . "' ";
            }

            /* 如果管理员属于某个供货商，只列出这个供货商的发货单 */
            if ($admin_info['suppliers_id'] > 0) {
                $where .= " AND suppliers_id = '" . $admin_info['suppliers_id'] . "' ";
            }

            /* 分页大小 */
            $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

            if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
                $filter['page_size'] = intval($_REQUEST['page_size']);
            } elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) {
                $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
            } else {
                $filter['page_size'] = 15;
            }

            /* 记录总数 */
            $filter['record_count'] = $model->get_delivery_order_count($where);;
            $filter = page_and_size($filter);

            /* 查询 */
            $sql = "SELECT delivery_id, delivery_sn, order_sn, order_id, add_time, action_user, consignee, country,
	                province, city, district, tel, status, update_time, email, suppliers_id FROM " . 
                    Model()->tablename("delivery_order") . " $where ORDER BY " . $filter['sort_by'] . " " . 
                    $filter['sort_order'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        /* 获取供货商列表 */
        $suppliers_list = Model('supplier')->get_supplier_list('*', 'status=1', 'supplier_name ASC');
        $_suppliers_list = array();
        foreach ($suppliers_list as $value) {
            $_suppliers_list[$value['suppliers_id']] = $value['suppliers_name'];
        }

        $row = get_all_page($sql, $filter['page_size'], $filter['start']);

        /* 格式化数据 */
        foreach ($row AS $key => $value) {
            $row[$key]['add_time'] = local_date(C('time_format'), $value['add_time']);
            $row[$key]['update_time'] =  local_date(C('time_format'), $value['update_time']);
            if ($value['status'] == 1) {
                $row[$key]['status_name'] = L('delivery_status')[1];
            } elseif ($value['status'] == 2) {
                $row[$key]['status_name'] = L('delivery_status')[2];
            } else {
                $row[$key]['status_name'] = L('delivery_status')[0];
            }
            $row[$key]['suppliers_name'] = isset($_suppliers_list[$value['suppliers_id']]) ? $_suppliers_list[$value['suppliers_id']] : '';
        }
        $arr = array('delivery' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

        return $arr;
    }

    /**
     * @return   删除发货单时删除其在订单中的发货单号
     * @param    int $order_id 定单id
     * @param    string $delivery_invoice_no 发货单号
     * @return   void
     */
    private function del_order_invoice_no($order_id, $delivery_invoice_no){
        /* 查询：取得订单中的发货单号 */
        $order_invoice_no = Model('order')->select_order_info_info('invoice_no',"order_id = '$order_id'");

        /* 如果为空就结束处理 */
        if (empty($order_invoice_no['invoice_no'])) {
            return;
        }

        /* 去除当前发货单号 */
        $order_array = explode('<br>', $order_invoice_no);
        $delivery_array = explode('<br>', $delivery_invoice_no);

        foreach ($order_array as $key => $invoice_no) {
            if ($ii = array_search($invoice_no, $delivery_array)) {
                unset($order_array[$key], $delivery_array[$ii]);
            }
        }

        $arr['invoice_no'] = implode('<br>', $order_array);
        $this->update_order($order_id, $arr);
    }

    /**
     * @return  更新订单对应的 pay_log，如果未支付，修改支付金额；否则，生成新的支付log
     * @param   int $order_id 订单id
     */
    private function update_pay_log($order_id){
        $order_id = intval($order_id);
        $pay_log = Model('pay_log');
        if ($order_id > 0) {
            $where = "order_id = '$order_id'";
            $order_amount = Model('order')->select_order_info_info('order_amount',$where);
            $order_amount = $order_amount['order_amount'];
            if (!is_null($order_amount)) {
                $where = "order_id = '$order_id'"." AND order_type = '" . PAY_ORDER . "'" ." AND is_paid = 0";
                $log_id = $pay_log->select_pay_log_info('log_id',$where);
                $log_id = $log_id['log_id'];
                if ($log_id > 0) {
                    /* 未付款，更新支付金额 */
                    $where = 'log_id = '.$log_id;
                    $data = array('order_amount'=>$order_amount);
                    $pay_log->update_pay_log($data,$where);
                } else {
                    /* 已付款，生成新的pay_log */
                    $data['order_id'] = $order_id;
                    $data['order_amount'] = $order_amount;
                    $data['order_type'] = PAY_ORDER;
                    $data['is_paid'] = 0;
                    $pay_log->insert_pay_log($data);
                }
            }
        }
    }    

    /**
     * @return  获取增值税发票完整地址信息
     * @param   int $order_id 订单id
     */
    private function get_inv_complete_address($order) {
        if ($order['inv_type'] == 'normal_invoice') {
            $address = trim($this->get_inv_complete_region($order, $order['inv_type']));
            if (empty($address)) {
                return $order['address'];
            } else {
                return '[' . $address . '] ' . $order['address'];
            }
        } elseif ($order['inv_type'] == 'vat_invoice') {
            $address = trim($this->get_inv_complete_region($order['order_id'], $order['inv_type']));
            if (empty($address)) {
                return $order['inv_consignee_address'];
            } else {
                return '[' . $address . '] ' . $order['inv_consignee_address'];
            }
        } else {
            return '';
        }
    }

    /**
     * @return  获取增值税发票地区信息
     * @param   int $order 订单信息
     */
    private function get_inv_complete_region($order, $inv_type) {
        if (!empty($order_id)) {
            if ($inv_type == 'normal_invoice') {
                $region = get_province_city($order['province'],$order['city'],$order['district']);
                return $region;
            } elseif ($inv_type == 'vat_invoice') {
                $region = get_province_city($order['inv_consignee_province'],$order['inv_consignee_city'],$order['inv_consignee_district']);
                return $region;
            } else {
                return ' ';
            }
        } else {
            return ' ';
        }
    }

    /**
     * @return  返回某个订单可执行的操作列表，包括权限判断
     * @param   array   $order      订单信息 order_status, shipping_status, pay_status
     * @param   bool    $is_cod     支付方式是否货到付款
     * @return  array   可执行的操作  confirm, pay, unpay, prepare, ship, unship, receive, cancel, invalid, return, drop
     * 格式 array('confirm' => true, 'pay' => true)
     */
    private function operable_list($order) {
        /* 取得订单状态、发货状态、付款状态 */
        $os = $order['order_status'];
        $ss = $order['shipping_status'];
        $ps = $order['pay_status'];
        /* 取得订单操作权限 */
        $sess = $this->admin_info;
        $admin_id = $sess['user_id'];
        $where = "user_id = '$admin_id'";
        $actions = Model('admin')->select_admin_info('action_list',$where)['action_list'];
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
        $pay_id = $order['pay_id'];
        $where = "pay_id = '$pay_id' AND enabled = 1";
        $payment = Model('payment')->select_payment_info('is_cod',$where);
        $is_cod = $payment['is_cod'] == 1;

        /* 根据状态返回可执行操作 */
        $list = array();
        if (OS_UNCONFIRMED == $os) {
            /* 状态：未确认 => 未付款、未发货 */
            if ($priv_list['os']) {
                $list['confirm'] = true; /*确认*/
                $list['invalid'] = true; /*无效*/
                $list['cancel'] = true;  /*取消*/
                if ($is_cod) {
                    /* 货到付款 */
                    if ($priv_list['ss']) {
                        $list['prepare'] = true; /*配货*/
                        $list['split'] = true;   /*分单*/
                    }
                } else {
                    /* 不是货到付款 */
                    if ($priv_list['ps']) {
                        $list['pay'] = true;  /*付款*/
                    }
                }

            }
        } elseif (OS_CONFIRMED == $os || OS_SPLITED == $os || OS_SPLITING_PART == $os) {
            /* 状态：已确认 */
            if (PS_UNPAYED == $ps) {
                /* 状态：已确认、未付款 */
                if (SS_UNSHIPPED == $ss || SS_PREPARING == $ss) {
                    /* 状态：已确认、未付款、未发货（或配货中） */
                    if ($priv_list['os']) {
                        $list['cancel'] = true;
                        $list['invalid'] = true;
                    }

                    if ($is_cod) {
                        /* 货到付款 */
                        if ($priv_list['ss']) {
                            if (SS_UNSHIPPED == $ss) {
                                $list['prepare'] = true;
                            }
                            $list['split'] = true; 
                        }
                    } else {
                        /* 不是货到付款 */
                        if ($priv_list['ps']) {
                            $list['pay'] = true;
                        }

                    }

                }
                /* 状态：已确认、未付款、发货中 */ 
                elseif (SS_SHIPPED_ING == $ss || SS_SHIPPED_PART == $ss) {
                    // 部分分单
                    if (OS_SPLITING_PART == $os) {
                        $list['split'] = true;
                    }
                    $list['to_delivery'] = true;/*去发货*/
                } else {
                    /* 状态：已确认、未付款、已发货或已收货 => 货到付款 */
                    if ($priv_list['ps']) {
                        $list['pay'] = true;
                    }
                    if ($priv_list['ss']) {
                        if (SS_SHIPPED == $ss) {
                            $list['receive'] = true;/*收货确认*/
                        }
                        $list['unship'] = true;/*设为未发货*/
                        if ($priv_list['os']) {
                            $list['return'] = true;/*退货*/
                        }
                    }
                }
            } else {
                /* 状态：已确认、已付款和付款中 */
                if (SS_UNSHIPPED == $ss || SS_PREPARING == $ss) {
                    /* 状态：已确认、已付款和付款中、未发货（配货中） => 不是货到付款 */
                    if ($priv_list['ss']) {

                        if (SS_UNSHIPPED == $ss) {;
                            $list['prepare'] = true;
                        }
                        $list['split'] = true;
                    }
                    if ($priv_list['ps']) {
                        $list['unpay'] = true;
                        if ($priv_list['os']) {
                            $list['cancel'] = true;
                        }
                    }
                }
                /* 状态：已确认、未付款、发货中 */
                elseif (SS_SHIPPED_ING == $ss || SS_SHIPPED_PART == $ss) {
                    // 部分分单
                    if (OS_SPLITING_PART == $os) {
                        $list['split'] = true;
                    }
                    $list['to_delivery'] = true;
                } else {
                    /* 状态：已确认、已付款和付款中、已发货或已收货 */
                    if ($priv_list['ss']) {
                        if (SS_SHIPPED == $ss) {
                            $list['receive'] = true;
                        }
                        if (!$is_cod) {
                            $list['unship'] = true;
                        }
                    }
                    if ($priv_list['ps'] && $is_cod) {
                        $list['unpay'] = true;
                    }
                    if ($priv_list['os'] && $priv_list['ss'] && $priv_list['ps']) {
                        $list['return'] = true;
                    }
                }
            }
        } elseif (OS_CANCELED == $os) {
            /* 状态：取消 */
            if ($priv_list['os']) {
                $list['confirm'] = true;
            }
            if ($priv_list['edit']) {
                $list['remove'] = true;
            }
        } elseif (OS_INVALID == $os) {
            /* 状态：无效 */
            if ($priv_list['os']) {
                $list['confirm'] = true;
            }
            if ($priv_list['edit']) {
                $list['remove'] = true;
            }
        } elseif (OS_RETURNED == $os) {
            /* 状态：退货 */
            if ($priv_list['os']) {
                $list['confirm'] = true;
            }
        }

        /* 修正发货操作 */
        if (!empty($list['split'])) {

            /* 如果部分发货 不允许 取消 订单 */
            $res = $this->order_deliveryed($order['order_id']);
            if ($res) {
                $list['return'] = true; // 退货（包括退款）
                unset($list['cancel']); // 取消
            }
        }

        /* 售后 */
        $list['after_service'] = true;
        return $list;
    }

    /**
     * @return  判断订单是否已发货（含部分发货）
     * @param   int     $order_id  订单 id
     * @return  int     1，已发货；0，未发货
     */
    private function order_deliveryed($order_id) {
        $return_res = 0;
        if (empty($order_id)) {
            return $return_res;
        }
        $field = "COUNT(delivery_id) as num";
        $where = "order_id = '" . $order_id . "'AND status = 0";
        $sum = Model('delivery_order')->select_delivery_order_info($field,$where);
        if ($sum['num']) {
            $return_res = 1;
        }
        return $return_res;
    }

    /**
     * @return  取得某订单应该赠送的积分数
     * @param   array   $order  订单
     * @return  int     积分数
     */
    private function integral_to_give($order){
        /* 判断是否团购 */
        if ($order['extension_code'] == 'group_buy'){
            $group_buy = group_buy_info(intval($order['extension_id']));
            return array('custom_points' => $group_buy['gift_integral'], 'rank_points' => $order['goods_amount']);
        }else{
            $where = " order_goods.order_id = '$order[order_id]' " .
                    "AND order_goods.goods_id > 0 " .
                    "AND order_goods.parent_id = 0 " .
                    "AND order_goods.is_gift = 0 AND order_goods.extension_code != 'package_buy'";
            $field = "SUM(order_goods.goods_number * IF(goods.give_integral > -1, goods.give_integral, order_goods.goods_price)) AS custom_points,
            SUM(order_goods.goods_number * IF(goods.rank_integral > -1, goods.rank_integral, order_goods.goods_price)) AS rank_points ";        
            return Model('order')->get_order_goods_goods_list($field,$where);
        }
    }

    /**
     * @return  取得订单总金额
     * @param   int     $order_id   订单id
     * @param   bool    $include_gift   是否包括赠品
     * @return  float   订单总金额
     */
    private function order_amount($order_id, $include_gift = true){
        $where = "order_id = '$order_id'";
        $field = "SUM(goods_price * goods_number) as price";
        if (!$include_gift){
            $where .= " AND is_gift = 0";
        }
        $price = Model('order')->select_order_goods_info($field,$where,'','');
        return floatval($price['price']);
    }

    /**
     * @return  订单中的商品是否已经全部发货
     * @param   int     $order_id  订单 id
     * @return  int     1，全部发货；0，未全部发货
     */
    private function get_order_finish($order_id){
        $return_res = 0;

        if (empty($order_id)){
            return $return_res;
        }

        $sum = Model('order')->get_order_goods_count('order_id = \'' . $order_id . '\' AND goods_number > send_number');
        if (empty($sum)){
            $return_res = 1;
        }

        return $return_res;
    }

    /**
     * @return  判断订单的发货单是否全部发货
     * @param   int     $order_id  订单 id
     * @return  int     1，全部发货；0，未全部发货；-1，部分发货；-2，完全没发货；
     */
    private function get_all_delivery_finish($order_id){
        $return_res = 0;
        if (empty($order_id)){
            return $return_res;
        }

        /* 未全部分单 */
        if (!$this->get_order_finish($order_id)){
            return $return_res;
        }else{   
            /* 已全部分单 */
            $sum = Model('delivery_order')->get_delivery_order_count("order_id = '$order_id' AND status = 2 ");
            /*全部发货*/
            if (empty($sum)){
                $return_res = 1;
            }else{
                /*未全部发货*/
                /* 订单全部发货中时：当前发货单总数 */
                $_sum = Model('delivery_order')->get_delivery_order_count("order_id = '$order_id' AND status <> 1");
                if ($_sum == $sum){
                    $return_res = -2; /*完全没发货*/
                }else{
                    $return_res = -1; /*部分发货*/
                }
            }
        }

        return $return_res;
    }

    /**
     * @return  删除订单所有相关单子
     * @param   int     $order_id      订单 id
     * @param   int     $action_array  操作列表 Array('delivery', 'back', ......)
     * @return  int     1，成功；0，失败
     */
    private function del_delivery($order_id, $action_array){
        $return_res = 0;

        if (empty($order_id) || empty($action_array)){
            return $return_res;
        }               
        $query_delivery = 1;
        $query_back = 1;
        if (in_array('delivery', $action_array)){  
            $delivery_id  = Model('delivery_order')->select_delivery_order_info('delivery_id',"order_id=".$order_id);
            if($delivery_id){
                $res = Model('delivery_order')->delete_delivery_order("order_id=".$order_id);
                $res2 = Model('delivery_goods')->delete_delivery_goods('delivery_id ='.$delivery_id['delivery_id']);
                $query_delivery = $res && $res2;
            }      
        }if (in_array('back', $action_array)){
            $back_id  = Model('back_order')->select_back_order_info('back_id',"order_id=".$order_id);
            if($back_id){
                $res = Model('back_order')->delete_back_order("order_id=".$order_id);
                $res2 = Model('back_goods')->delete_back_goods('back_id ='.$back_id['back_id']);
                $query_back = $res && $res2;
            }      
        }

        if ($query_delivery && $query_back){
            $return_res = 1;
        }

        return $return_res;
    }

    /**
     * @return  取得订单信息并格式化
     * @param   int     $order_id   订单id（如果order_id > 0 就按id查，否则按sn查）
     * @param   string  $order_sn   订单号
     * @return  array   订单信息（金额都有相应格式化的字段，前缀是formated_）
     */
    private function select_order_format_info($order_id, $order_sn = '') {
        /* 计算订单各种费用之和的语句 */
        $total_fee = " (goods_amount - discount + tax + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee) AS total_fee ";
        $order_id = intval($order_id);
        if ($order_id > 0) {
            $where = "order_info.order_id = '$order_id'";
        } else {
            $where = "order_info.order_sn = '$order_sn'";
        }
        $field = "order_info.*, pickup_point.shop_name, pickup_point.address as zt_address, pickup_point.phone, pickup_point.contact, " . $total_fee;
        $order = Model('order')->get_order_pickup_info($field,$where);

        /* 格式化金额字段 */
        if ($order) {
            $order['formated_goods_amount'] = price_format($order['goods_amount'], false);
            $order['formated_discount'] = price_format($order['discount'], false);
            $order['formated_tax'] = price_format($order['tax'], false);
            $order['formated_shipping_fee'] = price_format($order['shipping_fee'], false);
            $order['formated_insure_fee'] = price_format($order['insure_fee'], false);
            $order['formated_pay_fee'] = price_format($order['pay_fee'], false);
            $order['formated_pack_fee'] = price_format($order['pack_fee'], false);
            $order['formated_card_fee'] = price_format($order['card_fee'], false);
            $order['formated_total_fee'] = price_format($order['total_fee'], false);
            $order['formated_money_paid'] = price_format($order['money_paid'], false);
            $order['formated_bonus'] = price_format($order['bonus'], false);
            $order['formated_integral_money'] = price_format($order['integral_money'], false);
            $order['formated_balance_pay'] = price_format($order['balance_pay'], false);
            $order['formated_order_amount'] = price_format(abs($order['order_amount']), false);
            $order['formated_add_time'] = local_date(C('time_format'), $order['add_time']);
            $order['invoices'] = Model('delivery_order')->get_delivery_order_list('invoice_no,shipping_name',"order_id = " . $order['order_id'] . " AND status = 0");
            $wheres = "region_id in (" . $order['country'] . "," . $order['province'] . "," . $order['city'] . "," . $order['district'] . ")";
            $rows = Model('region')->get_regoin_list('region_id, region_name',$wheres);

            foreach ($rows as $row) {
                $region_id = $row['region_id'];
                $region_name = $row['region_name'];

                if ($region_id == $order['country']) {
                    $order['country_name'] = $region_name;
                } else if ($region_id == $order['province']) {
                    $order['province_name'] = $region_name;
                } else if ($region_id == $order['city']) {
                    $order['city_name'] = $region_name;
                } else if ($region_id == $order['district']) {
                    $order['district_name'] = $region_name;
                }
            }
        }

        return $order;
    }

    /**
     * 生成查询订单的查询条件语句
     * @param   string  $type   类型
     * @param   string  $alias  order表的别名（包括.例如 o.）
     * @return  string
     */
    private function order_query_sql($type = 'finished', $alias = '') {
        /* 已完成订单 */
        if ($type == 'finished') {
            return " AND {$alias}order_status " . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) .
                " AND {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
                " AND {$alias}pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . " ";
        } elseif ($type == 'await_ship') {
            /* 待发货订单 */
            return " AND   {$alias}order_status " .
                db_create_in(array(OS_CONFIRMED, OS_SPLITED, OS_SPLITING_PART)) .
                " AND   {$alias}shipping_status " .
                db_create_in(array(SS_UNSHIPPED, SS_PREPARING, SS_SHIPPED_ING)) .
                " AND ( {$alias}pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . ") ";
        } elseif ($type == 'await_pay') {
            /* 待付款订单 */
            return " AND   {$alias}order_status " . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) .
                " AND   {$alias}pay_status = '" . PS_UNPAYED . "'" .
                " AND ( {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . ") ";
        } elseif ($type == 'unconfirmed') {
            /* 未确认订单 */
            return " AND {$alias}order_status = '" . OS_UNCONFIRMED . "' ";
        } elseif ($type == 'unprocessed') {
            /* 未处理订单：用户可操作 */
            return " AND {$alias}order_status " . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) .
                " AND {$alias}shipping_status = '" . SS_UNSHIPPED . "'" .
                " AND {$alias}pay_status = '" . PS_UNPAYED . "' ";
        } elseif ($type == 'unpay_unship') {
            /* 未付款未发货订单：管理员可操作 */
            return " AND {$alias}order_status " . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) .
                " AND {$alias}shipping_status " . db_create_in(array(SS_UNSHIPPED, SS_PREPARING)) .
                " AND {$alias}pay_status = '" . PS_UNPAYED . "' ";
        } elseif ($type == 'shipped') {
            /* 已发货订单：不论是否付款 */
            return " AND {$alias}order_status = '" . OS_CONFIRMED . "'" .
                " AND {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . " ";
        } else {
            die('函数 order_query_sql 参数错误');
        }
    }

    /**
     * @return  判断订单是否已完成
     * @param   array   $order  订单信息
     * @return  bool
     */
    private function order_finished($order) {
        return $order['order_status'] == OS_CONFIRMED &&
        ($order['shipping_status'] == SS_SHIPPED || $order['shipping_status'] == SS_RECEIVED) &&
        ($order['pay_status'] == PS_PAYED || $order['pay_status'] == PS_PAYING);
    }

    /**
     * @return  订单退款
     * @param   array   $order          订单
     * @param   int     $refund_type    退款方式 1 到帐户余额 2 到退款申请（先到余额，再申请提款） 3 不处理
     * @param   string  $refund_note    退款说明
     * @param   float   $refund_amount  退款金额（如果为0，取订单已付款金额）
     * @return  bool
     */
    private function order_refund($order, $refund_type, $refund_note, $refund_amount = 0){
        /* 检查参数 */
        $user_id = $order['user_id'];
        if ($user_id == 0 && $refund_type == 1){
            die('anonymous, cannot return to account balance');
        }

        $amount = $refund_amount > 0 ? $refund_amount : $order['money_paid'];
        if ($amount <= 0){
            return true;
        }

        if (!in_array($refund_type, array(1, 2, 3))){
            die('invalid params');
        }

        /* 备注信息 */
        if ($refund_note){
            $change_desc = $refund_note;
        }else{
            $change_desc = sprintf(L('order_refund'), $order['order_sn']);
        }

        /* 处理退款 */
        if (1 == $refund_type){
            log_account_change($user_id, $amount, 0, 0, 0, $change_desc);

            return true;
        }elseif (2 == $refund_type){
            /* 如果非匿名，退回余额 */
            if ($user_id > 0){
                log_account_change($user_id, $amount, 0, 0, 0, $change_desc);
            }

            /* user_account 表增加提款申请记录 */
            $account = array(
                'user_id'      => $user_id,
                'amount'       => (-1) * $amount,
                'add_time'     => gmtime(),
                'user_note'    => $refund_note,
                'process_type' => SURPLUS_RETURN,
                'admin_user'   => $_SESSION['admin_name'],
                'admin_note'   => sprintf(L('order_refund'), $order['order_sn']),
                'is_paid'      => 0
            );
            Model('users')->insert_user_account($account);
            return true;
        }else{
            return true;
        }
    }

    /**
     * @return 更新订单总金额
     * @param  int $order_id 订单id
     * @return bool
     */
    private function update_order_amount($order_id){
        $where = "order_id=".$order_id;
        $order = Model('order')->select_order_info_info('*',$where);
        $amount = $order['goods_amount']+$order['tax']+$order['shipping_fee']+$order['insure_fee']+$order['pay_fee']
                +$order['pack_fee']+$order['card_fee'];
        $due = $order['money_paid']+$order['surplus']+$order['integral_money']+$order['bonus']+$order['discount'];
        $result = $amount - $due;
        $data = array('order_amount'=>$result);
        return Model('order')->update_order_info($data,$where);
    }

    /**
     * @return  生成查询订单总金额的字段
     * @param   string  $alias  order表的别名（包括.例如 o.）
     * @return  string
     */
    private function order_amount_field($alias = ''){
        return "   {$alias}goods_amount + {$alias}tax + {$alias}shipping_fee" .
        " + {$alias}insure_fee + {$alias}pay_fee + {$alias}pack_fee" .
        " + {$alias}card_fee ";
    }

    /**
     * @return 订单更新
     * @param  int $order_id 订单id
     * @param  array $order  订单新数据
     * @return bool
     */
    private function update_order($order_id, $order){

        if(isset($order['shipping_status']) && $order['shipping_status'] == SS_RECEIVED){
            /*收货确认的订单有可能发生佣金操作*/
            $this->get_pingtai_rebate_from_supplier($order_id);
            $order['rebate_ispay'] = 2;
        }
        $where = "order_id = '$order_id'";
        return Model('order')->update_order_info($order, $where);
    }

     /**
     * @return  退回余额、积分（取消、无效、退货时），把订单使用余额、积分设为0
     * @param   array $order 订单信息
     */
    private function return_user_surplus_integral($order){
        /* 处理余额、积分、红包 */
        if ($order['user_id'] > 0 && $order['surplus'] > 0){
            $surplus = $order['money_paid'] < 0 ? $order['surplus'] + $order['money_paid'] : $order['surplus'];
            log_account_change($order['user_id'], $surplus, 0, 0, 0, sprintf(L('return_order_surplus'), $order['order_sn']));
            $where = "order_id =". $order['order_id'];
            update_table_info('order_info', array('order_amount'=>0), $where);
        }

        if ($order['user_id'] > 0 && $order['integral'] > 0){
            log_account_change($order['user_id'], 0, 0, 0, $order['integral'], sprintf(L('return_order_integral'), $order['order_sn']));
        }       

        /* 修改订单 */
        $arr['bonus_id'] = 0;
        $arr['bonus'] = 0;
        $arr['integral'] = 0;
        $arr['integral_money'] = 0;
        $arr['surplus'] = 0;
        $this->update_order($order['order_id'], $arr);
    }

    /**
     * @return 入驻商订单佣金计算(订单收货确认后触发)
     * @param  int $order_id 订单id
     * @return bool
     */
    private function get_pingtai_rebate_from_supplier($order_id){
        $field = "*,sum(money_paid + surplus) as jisuan_money";
        $where = "order_id=".$order_id;
        $info = Model('order')->select_order_info_info($field, $where);
        $supplier_id = 0;
        $supplier_user_id = 0;
        $rebate = 0;
        if($info['supplier_id']>0){
            /*如果是入驻商的订单*/
            $field = "supplier_id,user_id,supplier_rebate";
            $where = "supplier_id=".$info['supplier_id']." and status=1";
            $supp_info = Model('supplier')->select_supplier_info($field,$where);
            if(!$supp_info){
              return true;
            }
        }else{
            return true;
        }
        if($info['pay_status'] == PS_PAYED){
            $supplier_id = $supp_info['supplier_id'];
            $supplier_user_id = $supp_info['user_id'];
            $rebate = $supp_info['supplier_rebate'];
            $order_id = $info['order_id'];
            $order_sn = $info['order_sn'];
            $pay_id = $info['pay_id'];
            $pay_name = $info['pay_name'];
            /*收货确认的订单*/
            $money = $info['jisuan_money'];//要计算的价钱
            $rebate_money = round(($money * $rebate)/100, 2);//返给平台方的价钱
            $result_money = $money - $rebate_money;//入驻商获取的价钱
            $texts = "订单支付";
            $add_time = gmtime();
            /*佣金订单日志*/
            $data['order_id'] = $order_id;
            $data['order_sn'] = $supplier_id;
            $data['supplier_id'] = $order_id;
            $data['all_money'] = $money;
            $data['rebate_money'] = $rebate_money;
            $data['result_money'] = $result_money;
            $data['pay_id'] = $pay_id;
            $data['pay_name'] = $pay_name;
            $data['texts'] = $texts;
            $data['add_time'] = $add_time;
            Model('supplier')->insert_supplier_rebate_log($data);
            /*入驻商绑定的会员帐户日志变动*/
            $change_desc = "订单:".$order_sn."返入驻商会员可用资金";
            log_account_change($supplier_user_id, $result_money, 0, 0, 0, $change_desc, ACT_ADJUSTING);
        }
        return true;
    } 

}    