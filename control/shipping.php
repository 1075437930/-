<?php

/**
 * 淘玉php 配送方式
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 配送方式
 * $Id: shipping.php 17217 2018年4月28日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class shippingControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('shipping'); //载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 配送方式列表
     */
    public function lists() {
        admin_priv('ship_manage');
        /* 查询数据库中启用的配送方式 */
        $shipping_list = array();
        $res = Model('shipping')->get_shipping_list('*',"enabled = '1' and supplier_id=0",'shipping_order');
        /*格式化数据*/
        foreach ($res as $key => $row) {
            $shipping_list[$row['shipping_code']] = $row;
            $shipping_list[$row['shipping_code']]['shipping_desc'] = html_entity_decode(str_replace('&amp;', "&", $row['shipping_desc']));
        }
        Tpl::assign('ur_here', L('03_shipping_list'));
        Tpl::assign('shipping_list', $shipping_list);
        Tpl::display('shipping_list.htm');
    }

    /**
     * @return 删除配送方式
     */
    public function remove() {
        admin_priv('shipping');
        $shipping_model = Model('shipping');  
        $link = array('text' => L('go_back'), 'href'=>'index.php?act=shipping&op=lists');      
        /*获取配送方式信息*/
        $code = $_GET['code'];
        $field = 'shipping_id,shipping_code, shipping_name, print_bg, is_default_show';
        $where = "shipping_code='".$code."' and supplier_id=0";
        $shipping_info = $shipping_model->select_shipping_info($field,$where);
        $shipping_id = $shipping_info['shipping_id'];
        $shipping_name = $shipping_info['shipping_name'];
        /*默认配送方式不能删除*/
        if($shipping_info['is_default_show']){        
            showMessage('默认配送方式不可以删除,请先设置别的配配送方式为默认后再删除',$link);
        }
        /*删除shipping、shipping_area以及area_region表中的数据 */
        if($shipping_id){
            $fields = 'shipping_area_id';
            $wheres = "shipping_id='$shipping_id'";
            $info = $shipping_model->get_shipping_area_list($fields,$wheres);
            $tmp = array();
            if(!empty($info)){
                foreach ($info as $key => $value) {
                    $tmp[] = $value['shipping_area_id'];
                }
                $str = db_create_in(join(',', $tmp)); 
            }
            $shipping_model->delete_area_region("shipping_area_id $str");
            $shipping_model->delete_shipping_area($wheres);
            $shipping_model->delete_shipping($wheres);

            /*记录管理员操作*/
            admin_log(addslashes($shipping_name), 'uninstall', 'shipping');

            /*提示信息*/
            showMessage(sprintf(L('uninstall_success'), $shipping_name),$link);
        }
    }

    /**
     * @return 设置配送方式为默认前台显示
     */
    public function is_default_show () {
        admin_priv('ship_manage');
        $shipping_model = Model('shipping');
        $link = array('text' => L('go_back'), 'href'=>'index.php?act=shipping&op=lists');      
        $fields = 'shipping_id,shipping_name';
        $wheres = "is_default_show=0 and supplier_id=0 and enabled=1 and shipping_code='".$_REQUEST['code']."'";
        $info = $shipping_model->select_shipping_info($fields,$wheres);
        if($info['shipping_id']){
            $shipping_name = $info['shipping_name'];
            $shipping_id = $info['shipping_id'];
            $rr = $this->set_default_show($shipping_id,0);
            /*记录管理员操作*/
            admin_log($shipping_name, 'is_default_show', 'shipping');
            showMessage(sprintf(L('is_default_show'), $shipping_name),$link);
        }else{
            showMessage('已经是默认显示配送地址',$link);
        }
    }

    /**
     * @return 编辑打印配送方式快递单模板
     */
    public function edit_print_template () {
        exit();
        var_dump($_REQUEST);
    }


    /**
     * @return 设置成默认配送方式所涉及到的操作
     * @param  int $sid 配送方式id
     * @return bool
     */
    private function set_default_show($sid,$supplier_id=0){
        $shipping_model = Model('shipping');

        /*修改所有其他可用的配送方式为非默认*/
        $where = "supplier_id=".$supplier_id." and enabled=1 and shipping_code not in('pups','tc_express')";
        $shipping_model->update_shipping(array('is_default_show'=>0),$where);

        /*修改当前配送地址为默认*/
        $where = "shipping_id=".$sid." and supplier_id=".$supplier_id;
        $shipping_model->update_shipping(array('is_default_show'=>1),$where);

        /*删除非默认配送方式下的数据*/
        $ret_del = $this->del_no_default($supplier_id);
        /*非默认配送方式下数据与默认配送方式的数据做同步*/
        $tb = $this->tongbu_default($sid,$supplier_id);
    }

    /**
     * @return 删除非默认配送方式下对应表ecs_shipping_area和ecs_area_region下的数据
     * @param  int $supplier_id 入驻商id
     * @return bool
     */
    private function del_no_default($supplier_id = 0) {
        $shipping_model = Model('shipping');
        $shipping_ids = $this->get_all_install_shipping($supplier_id);
        if (count($shipping_ids) > 0) {
            $str = db_create_in(join(',', $shipping_ids));
            $where = "shipping_id $str";
            $shipping_area_ids = $shipping_model->get_shipping_area_list('shipping_area_id',$where);
            if (empty($shipping_area_ids)) {
                return true;
            }

            $tmp = array();
            foreach ($shipping_area_ids as $key => $value) {
                $tmp[] = $value['shipping_area_id'];
            }
            $in = db_create_in(join(',', $tmp));
            $shipping_model->delete_area_region("shipping_area_id $in");
            $shipping_model->delete_shipping_area("shipping_area_id $in");           
        }

        return true;
    }

    /**
     * @return 用设置的配送方式的数据同步非默认配送方式下的数据
     * @param  int $sid 配送方式id
     * @param  int $supplier_id 入驻商id
     * @return bool
     */
    private function tongbu_default($shpping_id, $supplier_id = 0) {
        $shipping_model = Model('shipping');
        $where = "shipping_id = $shpping_id";
        $tmp = $shipping_model->get_shipping_area_list('shipping_area_id',$where);
        $shipping_area_ids = array();
        foreach ($tmp as $key => $value) {
            $shipping_area_ids[] = $value['shipping_area_id'];
        }
        $area_region_info = array();
        if (count($shipping_area_ids) > 0) {
            $not_default_shipping_ids = $this->get_all_install_shipping($supplier_id);
            
            foreach ($shipping_area_ids as $key => $val) {
                foreach ($not_default_shipping_ids as $k => $v) {
                    $data = $shipping_model->select_shipping_area_info('shipping_area_name,configure',"shipping_area_id=" . $val);
                    $data['shipping_id'] = $v;
                    $area_region_info[$val][] = $shipping_model->insert_shipping_area($data);
                }
            }

        }

        if (count($area_region_info) > 0) {

            foreach ($area_region_info as $key => $val) {
                foreach ($val as $k => $v) {
                    $datas = $shipping_model->select_area_region_info('region_id',"shipping_area_id=" . $key);
                    $datas['shipping_area_id'] = $v;
                    $shipping_model->insert_area_region($datas);
                }

            }

        }

        return true;
    }

    /**
     * @return 获取当前平台下所有已经安装的非默认，非同城快递，非门店自提配送地址
     * @param  int $supplier_id 入驻商id
     * @return array
     */
    private function get_all_install_shipping($supplier_id=0){
        $shipping_model = Model('shipping');
        $fields = 'shipping_id';
        $wheres = "supplier_id=".$supplier_id." and is_default_show=0 and enabled=1 and shipping_code not in('pups','tc_express')";
        $info = $shipping_model->get_shipping_list($fields,$wheres);
        $tmp = array();
        if(!empty($info)){
            foreach ($info as $key => $value) {
                $tmp[] = intval($value['shipping_id']);
            }             
        }
        return $tmp;
    }












}
?>

