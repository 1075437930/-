<?php

/**
 * 内部支付插件
 * ============================================================================
 * * 版权所有 2005-2012 淘玉商城，并保留所有权利。
 * 网站地址: http://www.taoyumall.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: douqinghua $
 * $Id: alipay.php 251230 2015-03-19 06:29:08Z douqinghua $
 */

if (!defined('TaoyuShop'))
{
    die('Hacking attempt');
}


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'neizhuan_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'TAOYU TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.taoyumall.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array();

    return;
}

/**
 * 类
 */
class neizhuan
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */

	/* 代码修改_start  By  www.taoyumall.com */
    function __construct()
    {
        $this->neizhuan();
    }

	function neizhuan()
    {
    }
	/* 代码修改_end  By  www.taoyumall.com */

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
		$pay_price = $order['order_amount'];
		$userid = $order['user_id'];
		$pay_sn = $order['order_sn'];
		$channel = '内部渠道支付';
		$pay_times = gmtime();
		$selerss = "SELECT oi.order_id, oi.pay_status, oi.order_amount, oi.consignee,og.goods_sn FROM " . $GLOBALS['ecs']->table('order_info') . ' AS oi '.
				" LEFT JOIN " . $GLOBALS['ecs']->table('order_goods') . " AS og ON og.order_id = oi.order_id ".
				" WHERE oi.order_sn = '$pay_sn' ";
		$oderpic = $GLOBALS['db']->getRow($selerss);
		$order_ids = $oderpic['order_id'];
		$goods_sn = $oderpic['goods_sn'];
		if(!empty($oderpic) && $oderpic['pay_status'] != 2 ){
			$consignee = $oderpic['consignee'];
		}
		if(!empty($pay_sn)){
			$appuserid = ','.$userid.',';
			$updatesql = "UPDATE " .$GLOBALS['ecs']->table('order_info'). 
					   " SET pay_status = 2,pay_time = $pay_times,pay_name = '$channel',pay_id = 8,money_paid = $pay_price ".
					   " WHERE order_sn = $pay_sn ";
			$edof = $GLOBALS['db']->query($updatesql);
			if(!empty($edof)){
				$note = $channel.'下单，支付成功，订单号: '.$pay_sn;
				$sqlinfo = "SELECT mobile_phone,user_name ".
				" FROM " .$GLOBALS['ecs']->table('users'). 
				" WHERE user_id = '$userid'";
				$orderinto = $GLOBALS['db']->getRow($sqlinfo);
				$mobiles = $orderinto['mobile_phone'];
				$username = $orderinto['user_name'];
				order_action_news($order_ids, 0, 0, 2,$note, $username, $channel);
				$sms = new smstao();
				if(!empty($mobiles)){
					$sql2  = 'SELECT *'.
					' FROM ' .$GLOBALS['ecs']->table('mail_templates').
					" WHERE template_code = 'order_pay'";
					$tpl_info = $GLOBALS['db']->getRow($sql2);
					$param = array();
					$param['good_pns'] = $pay_sn;
					$param['site_name']	= $_CFG['shop_name'];
					$message = ncReplaceText($tpl_info['template_content'],$param);
					$result = $sms->send($mobiles,$message);
				}
				$canshu = substr($goods_sn,0,1);
				if($canshu == 'f'){
					$shangjia_mob	= '1513901312';
				}else{
					$shangjia_mob	= $_CFG['shangjia_mob'];
				}
				$shangjia  = 'SELECT *'.
				' FROM ' .$GLOBALS['ecs']->table('mail_templates').
				" WHERE template_code = 'order_pay_admin2'";
				$shangjasms = $GLOBALS['db']->getRow($shangjia);
				$param2 = array();
				$param2['mobile'] = $consignee.':'.$mobiles;
				$param2['site_name']	= $_CFG['shop_name'];
				$message2 = ncReplaceText($shangjasms['template_content'],$param2);
				$result = $sms->send($shangjia_mob,$message2);
				$result2 = $sms->send('1513901312',$message2);
			}else{
				exit('支付成功，短信通知错误');
			}
		}else{
			exit('支付错误，产品订单号不存在');
		}
		
		
		order_paid($order['log_id'], 2);
    }

    /**
     * 响应操作
     */
    function respond()
    {

    }
}

?>