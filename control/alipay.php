<?php



/**

 * ECSHOP 支付宝插件

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



$payment_lang = './api/payment/language/alipay.php';



 global $_LANG;



    $_LANG['alipay'] = '支付宝';

$_LANG['alipay_desc'] = '支付宝网站(www.alipay.com) 是国内先进的网上支付平台。<br/>支付宝收款接口：在线即可开通，<font color="red"><b>零预付，免年费</b></font>，单笔阶梯费率，无流量限制。<br/><font color="red">注意:申请时请选择"即时到帐"业务进行申请</font><br/><a href="http://cloud.ecshop.com/payment_apply.php?mod=alipay" target="_blank"><font color="red">立即在线申请</font></a>';

$_LANG['alipay_account'] = '支付宝帐户';

$_LANG['alipay_key'] = '交易安全校验码';

$_LANG['alipay_partner'] = '合作者身份ID';

$_LANG['pay_button'] = '去支付';



/* 模块的基本信息 */

if (isset($set_modules) && $set_modules == TRUE)

{

    $i = isset($modules) ? count($modules) : 0;



    /* 代码 */

    $modules[$i]['code']    = basename(__FILE__, '.php');



    /* 描述对应的语言项 */

    $modules[$i]['desc']    = 'alipay_desc';



    /* 是否支持货到付款 */

    $modules[$i]['is_cod']  = '0';



    /* 是否支持在线支付 */

    $modules[$i]['is_online']  = '1';



    /* 作者 */

    $modules[$i]['author']  = 'ECSHOP TEAM';



    /* 网址 */

    $modules[$i]['website'] = 'http://www.alipay.com';



    /* 版本号 */

    $modules[$i]['version'] = '1.0.2';



    /* 配置信息 */

    $modules[$i]['config']  = array(

        array('name' => 'alipay_account',           'type' => 'text',   'value' => ''),

        array('name' => 'alipay_key',               'type' => 'text',   'value' => ''),

        array('name' => 'alipay_partner',           'type' => 'text',   'value' => ''),

        array('name' => 'alipay_pay_method',        'type' => 'select', 'value' => '')

    );



    return;

}



/**

 * 类

 */

class alipay

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

        $this->alipay();

    }



	 function alipay()

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

        if (!defined('EC_CHARSET'))

        {

            $charset = 'utf-8';

        }

        else

        {

            $charset = EC_CHARSET;

        }



        $real_method = $payment['alipay_pay_method'];



        switch ($real_method){

            case '0':

                $service = 'trade_create_by_buyer';

                break;

            case '1':

                $service = 'create_partner_trade_by_buyer';

                break;

            case '2':

                $service = 'create_direct_pay_by_user';

                break;

        }



        $extend_param = 'isv^sh22';



        $parameter = array(

            'extend_param'      => $extend_param,

            'service'           => $service,

            'partner'           => $payment['alipay_partner'],

            //'partner'           => ALIPAY_ID,

            '_input_charset'    => $charset,

            'notify_url'        => return_url(basename(__FILE__, '.php')),

            'return_url'        => return_url(basename(__FILE__, '.php')),

            /* 业务参数 */

            'subject'           => $order['order_sn'],

            'out_trade_no'      => $order['order_sn'] . $order['log_id'],

            'price'             => $order['order_amount'],

            'quantity'          => 1,

            'payment_type'      => 1,

            /* 物流参数 */

            'logistics_type'    => 'EXPRESS',

            'logistics_fee'     => 0,

            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',

            /* 买卖双方信息 */

            'seller_email'      => $payment['alipay_account']

        );



        ksort($parameter);

        reset($parameter);



        $param = '';

        $sign  = '';



        foreach ($parameter AS $key => $val)

        {

            $param .= "$key=" .urlencode($val). "&";

            $sign  .= "$key=$val&";

        }



        $param = substr($param, 0, -1);

        $sign  = substr($sign, 0, -1). $payment['alipay_key'];

        //$sign  = substr($sign, 0, -1). ALIPAY_AUTH;



        $button = '<div style="text-align:center"><input type="button" onclick="window.open(\'https://mapi.alipay.com/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5\')" value="' .$GLOBALS['_LANG']['pay_button']. '" class="main-btn main-btn-large"/></div>';



        return $button;

    }



    /**

     * 响应操作

     */

    function respond()

    {

        if (!empty($_POST))

        {

            foreach($_POST as $key => $data)

            {

                $_GET[$key] = $data;

            }

        }

        $payment  = get_payment($_GET['code']);

        $seller_email = rawurldecode($_GET['seller_email']);

        $order_sn = str_replace($_GET['subject'], '', $_GET['out_trade_no']);

        $order_sn = trim($order_sn);



        /* 检查数字签名是否正确 */

        ksort($_GET);

        reset($_GET);



        $sign = '';

        foreach ($_GET AS $key=>$val)

        {

            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')

            {

                $sign .= "$key=$val&";

            }

        }



        $sign = substr($sign, 0, -1) . $payment['alipay_key'];

        //$sign = substr($sign, 0, -1) . ALIPAY_AUTH;

        if (md5($sign) != $_GET['sign'])

        {

            return false;

        }

        /* 检查支付的金额是否相符 */

        if (!check_money($order_sn, $_GET['total_fee']))

        {

            return false;

        }

        if ($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS')

        {

            /* 改变订单状态 */

            order_paid($order_sn, 2);



            return true;

        }

        elseif ($_GET['trade_status'] == 'TRADE_FINISHED')

        {

            /* 改变订单状态 */

            order_paid($order_sn);



            return true;

        }

        elseif ($_GET['trade_status'] == 'TRADE_SUCCESS')

        {

            /* 改变订单状态 */

            order_paid($order_sn, 2);



            return true;

        }

        else

        {

            return false;

        }

    }

}



?>