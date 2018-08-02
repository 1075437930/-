<?php

/**
 * 淘玉php 支付方式管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 支付方式
 * $Id: payment.php 17217 2018年4月27日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class paymentControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('payment'); //载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 支付方式列表
     */
    public function lists() {
        admin_priv('payment');
        /* 查询数据库中启用的支付方式 */
        $pay_list = array();
        $res = Model('payment')->get_payment_list('*',"enabled = '1'",'pay_order');
        /*格式化数据*/
        foreach ($res as $key => $row) {
            $pay_list[$row['pay_code']] = $row;
            $pay_list[$row['pay_code']]['pay_desc'] = html_entity_decode(str_replace('&amp;', "&", $row['pay_desc']));
        }
        Tpl::assign('ur_here', L('02_payment_list'));
        Tpl::assign('pay_list', $pay_list);
        Tpl::display('payment_list.htm');
    }

    /**
     * @return 编辑支付方式页面
     */
    public function edit() {
        admin_priv('payment');
        $payment_model = Model('payment');
        /* 查询该支付方式内容 */
        if (isset($_REQUEST['code'])) {
            $_REQUEST['code'] = trim($_REQUEST['code']);
        } else {
            die('invalid parameter');
        }
        $where = "pay_code = '".$_REQUEST['code']."' AND enabled = 1";
        $pay = $payment_model->select_payment_info('*',$where);
        /*转义实体字符*/
        $pay['pay_desc'] = html_entity_decode(str_replace('&amp;', "&", $pay['pay_desc']));
        if (empty($pay)) {
            $links[] = array('text' => L('back_list'), 'href' => 'index.php?act=payment&op=lists');
            showMessage(L('payment_not_available'), $links);
        }
        /* 取得配置信息 */
        /* 取相应插件信息 */        
        $payment = new Payment($_REQUEST['code']);
        $_LANG = $payment->lang;
        $attr = $payment->module;
        $attribute = $attr[0];
        if (is_string($pay['pay_config'])) {
            $store = unserialize($pay['pay_config']);
            /* 取出已经设置属性的code */
            $code_list = array();
            foreach ($store as $key => $value) {
                $code_list[$value['name']] = $value['value'];
            }
            $pay['pay_config'] = array();
            /* 循环插件中所有属性 */
            foreach ($attribute['config'] as $key => $value) {
                $pay['pay_config'][$key]['desc'] = (isset($_LANG[$value['name'] . '_desc'])) ? $_LANG[$value['name'] . '_desc'] : '';
                $pay['pay_config'][$key]['label'] = $_LANG[$value['name']];
                $pay['pay_config'][$key]['name'] = $value['name'];
                $pay['pay_config'][$key]['type'] = $value['type'];

                if (isset($code_list[$value['name']])) {
                    $pay['pay_config'][$key]['value'] = $code_list[$value['name']];
                } else {
                    $pay['pay_config'][$key]['value'] = $value['value'];
                }

                if ($pay['pay_config'][$key]['type'] == 'select' ||
                    $pay['pay_config'][$key]['type'] == 'radiobox'
                ) {
                    $pay['pay_config'][$key]['range'] = $_LANG[$pay['pay_config'][$key]['name'] . '_range'];
                }
            }
        }       
        Tpl::assign('pay', $pay);
        Tpl::display('payment_edit.htm');
    }

    /**
     * @return 编辑支付方式数据入库
     */
    public function update() {
        admin_priv('payment');
        $payment_model = Model('payment');
        $links = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
        /* 检查输入 */
        if (empty($_POST['pay_name'])) {
            showMessage(L('payment_name') . L('empty'),$links);
        }
        $where = "pay_name = '$_POST[pay_name]' AND pay_code <> '$_POST[pay_code]'";
        if ($payment_model->get_payment_count($where) > 0) {
            showMessage(L('payment_name') . L('repeat'), $links);
        }

        /* 取得配置信息 */
        $pay_config = array();
        if (isset($_POST['cfg_value']) && is_array($_POST['cfg_value'])) {
            for ($i = 0; $i < count($_POST['cfg_value']); $i++) {
                $pay_config[] = array('name' => trim($_POST['cfg_name'][$i]),
                    'type' => trim($_POST['cfg_type'][$i]),
                    'value' => trim($_POST['cfg_value'][$i])
                );
            }
        }
        $pay_config = serialize($pay_config);      
        
        /*数据更新*/
        $pay_fee = $_REQUEST['fee'];
        if (empty($pay_fee)) {
            $pay_fee = 0;
        } else {
            if (strpos($pay_fee, '%') === false) {
                $pay_fee = floatval($pay_fee);
            } else {
                $pay_fee = floatval($pay_fee) . '%';
            }
        }
        $data['pay_fee'] = $pay_fee;
        $data['pay_name'] = $_POST['pay_name'];
        $data['pay_desc'] = $_POST['pay_desc'];
        $data['pay_order'] = intval($_POST['pay_order']);
        $data['pay_config'] = $pay_config;
        $wheres = "pay_code = '".$_POST['pay_code']."'";
        $payment_model->update_payment($data,$wheres);
        /* 记录日志 */
        admin_log($_POST['pay_name'], 'edit', 'payment');

        /*提示信息*/
        $link = array('text' => L('back_list'), 'href' => 'index.php?act=payment&op=lists');
        showMessage(L('edit_ok'),$link);
    }

    /**
     * @return 编辑支付方式数据入库
     */
    public function remove() {
        admin_priv('payment');
        $payment_model = Model('payment');
        $link = array('text' => L('back_list'), 'href' => 'index.php?act=payment&op=lists');
        if ($_REQUEST['code'] == 'balance') {
            showMessage("余额支付方式是产品必须要有的，强制删除后，用户下订单时会出现错误，请不要删除！",$link);
        }

        /* 把 enabled 设为 0 */       
        $wheres = "pay_code = '".$_REQUEST['code']."'";
        $payment_model->update_payment(array('enabled'=>0),$wheres);
        
        /* 记录日志 */
        admin_log($_REQUEST['code'], 'uninstall', 'payment');

        /*提示信息*/        
        showMessage(L('remove_ok'),$link);
    }

}
?>

