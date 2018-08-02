<?php

/**
 * 淘玉php 付费功能审核
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 付费功能审核
 * $Id: suppaymodule.php 17217 2018年5月12日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class suppaymoduleControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('suppaymodule');
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 入驻商付费模块功能列表
     */
    public function lists() {
        admin_priv('supplier_pay_module');
        $apply_list = $this->apply_supp_list();
        /* 模板赋值 */
        TPL::assign('filter', $apply_list['filter']);
        TPL::assign('record_count', $apply_list['record_count']);
        TPL::assign('page_count', $apply_list['page_count']);
        TPL::assign('full_page', 1);
        TPL::assign('apply_list', $apply_list['shops']);
        TPL::assign('ur_here', L('pay_module'));
        TPL::display('supplier_pay_module_list.htm');
    }

    /**
     * @return 入驻商付费模块功能列表分页、排序、查询
     */
    public function query() {
        $apply_list = $this->apply_supp_list();
        TPL::assign('filter', $apply_list['filter']);
        TPL::assign('record_count', $apply_list['record_count']);
        TPL::assign('page_count', $apply_list['page_count']);
        TPL::assign('full_page', 1);
        TPL::assign('apply_list', $apply_list['shops']);
        make_json_result(TPL::fetch('supplier_pay_module_div.htm'), '', array('filter' => $apply_list['filter'], 'page_count' => $apply_list['page_count']));
    }

    /**
     * @return 拒绝申请
     */
    public function refuse() {
        /* 权限检查 */
        admin_priv('supplier_pay_module');
        $id = $_REQUEST['id'];
        $supplier_model = Model('supplier');
        //检查是否需要返回费用
        $this->checkApplayIsReturn($id);
        $up_arr['apply_status'] = '2';
        $up_arr['pay_status'] = 2;
        $where['id'] = $id;
        $query = $supplier_model->update_supplier_module_apply($up_arr, $where);
        $link[] = array('href' => 'index.php?act=suppaymodule&op=lists', 'text' => L('back_to_apply_list'));
        if ($query) {
            showMessage(L('operate_succ'), $link);
        } else {
            showMessage(L('operate_fail'), $link);
        }
    }

    /**
     * @return 通过申请
     */
    public function pass() {
        /* 权限检查 */
        admin_priv('supplier_pay_module');
        $id = $_REQUEST['id'];
        $supplier_model = Model('supplier');
        $field = "supplier_module_apply.*,users.mobile_phone ";
        $where = " supplier_module_apply.id = " . $id;
        $app_info = $supplier_model->get_supplier_module_apply_users_list($field, $where);
        $link[] = array('href' => 'index.php?act=suppaymodule&op=lists', 'text' => L('back_to_apply_list'));
        if (empty($app_info)) {
            showMessage(L('operate_fail'), $link);
        }
        $up_arr['open_start_time'] = gmtime();
        $up_arr['apply_status'] = '1';
        $wh['id'] = $id;
        $query = $supplier_model->update_supplier_module_apply($up_arr, $wh);
        if ($query) {
            /*申请通过后，发送短信*/
            $mobiles = $app_info['mobile_phone'];
            if (!empty($mobiles)) {
                $param = array();
                $param['shop_name'] = $app_info['supplier_name'];
                $param['site_name'] = C('shop_name');
                $result = send_sms_msg($mobiles, 'shop_today_module', $param); 
            }
            showMessage(L('operate_succ'), $link);
        } else {
            showMessage(L('operate_fail'), $link);
        }
    }

    /**
     * @return 取消申请（相当于关闭该功能）
     */
    public function close() {
        /* 权限检查 */
        admin_priv('supplier_pay_module');
        $id = $_REQUEST['id'];
        $supplier_model = Model('supplier');
        $up_arr['apply_status'] = '3';
        $wh['id'] = $id;
        $query = $supplier_model->update_supplier_module_apply($up_arr, $wh);
        $link[] = array('href' => 'index.php?act=suppaymodule&op=lists', 'text' => L('back_to_apply_list'));
        if ($query) {
            showMessage(L('operate_succ'), $link);
        } else {
            showMessage(L('operate_fail'), $link);
        }
    }

    /**
     * @return 删除申请）
     */
    public function remove() {
        /* 权限检查 */
        admin_priv('supplier_pay_module');
        $id = $_REQUEST['id'];
        $supplier_model = Model('supplier');
        //检查是否需要返回费用
        $apply_info = $this->checkApplayIsReturn($id);
        $link[] = array('href' => 'index.php?act=suppaymodule&op=lists', 'text' => L('back_to_apply_list'));
        /* 删除数据库中的数据 */
        if ($apply_info['apply_status'] == 0 && $apply_info['pay_status'] == 0) {
            $where['id'] = $id;
            $query = $supplier_model->delete_supplier_module_apply($where);
        } else {
            $up_arr['is_delete'] = '1';
            $wh['id'] = $id;
            $query = $supplier_model->update_supplier_module_apply($up_arr, $wh);
        }

        if ($query) {
            clear_cache_files();
            admin_log($shop_info['supplier_name'], 'remove', 'supplier_module_apply');
            showMessage(L('operate_succ'), $link);
        } else {
            showMessage(L('operate_fail'), $link);
        }
    }

    /**
     * @return 申请列表
     * @return array
     */
    private function apply_supp_list() {
        $supplier_model = Model('supplier');
        $where = '  where is_delete = 0  ';
        $filter['shop_name'] = empty($_REQUEST['shop_name']) ? '' : trim($_REQUEST['shop_name']);
        if (!empty($filter['shop_name'])) {
            $where .= " and `supplier_name` LIKE '%" . mysql_like_quote($filter['shop_name']) . "%' ";
        }
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $filter['pay_status'] = empty($_REQUEST['pay_status']) ? '' : trim($_REQUEST['pay_status']);
        $filter['apply_status'] = empty($_REQUEST['apply_status']) ? '' : trim($_REQUEST['apply_status']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
            $filter['shop_name'] = json_str_iconv($filter['shop_name']);
        }

        /*支付状态*/
        if (!empty($filter['pay_status'])) {
            $where .= " and pay_status =  " . (intval($filter['pay_status']) - 1);
        }

        /*审核状态*/
        if (!empty($filter['apply_status'])) {
            $where .= " and apply_status =  " . (intval($filter['apply_status']) - 1);
        }
        /* 记录总数 */
        $filter['record_count'] = $supplier_model->get_supplier_module_apply_count($where);

        /* 分页大小 */
        $filter = page_and_size($filter);

        $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;
        $sql = "SELECT * " ." FROM " . Model()->tablename('supplier_module_apply') ." " . $where .
               " ORDER BY $filter[sort_by] $filter[sort_order] ";
        set_filter($filter, $sql);
        $arr = get_all_page($sql, $filter['page_size'], $filter['start']);

        $pay_type = array('余额', '微信', '支付宝');
        $pay_status_arr = array('未支付', '已支付', '已退款');
        $apply_status_arr = array('未审核', '通过', '拒绝', '取消');
        $module_arr = array('今日特价', '', '');

        foreach ($arr as $key => $value) {
            $arr[$key]['pay_type'] = $pay_type[$value['pay_type'] - 1];
            $arr[$key]['pay_status_name'] = $pay_status_arr[$value['pay_status']];
            $arr[$key]['apply_status_name'] = $apply_status_arr[$value['apply_status']];
            $arr[$key]['apply_time'] = local_date('Y-m-d H:i:s', $value['apply_time']);
            $arr[$key]['module'] = $module_arr[$value['module_type'] - 1];
            if (!empty($value['open_start_time'])) {
                $last_time = $value['open_start_time'] + $value['over_time'] * 30 * 24 * 3600;
                $arr[$key]['open_start_time'] = local_date('Y-m-d H:i:s', $value['open_start_time']);
                $arr[$key]['open_end_time'] = local_date('Y-m-d H:i:s', $last_time);
            } else {
                $arr[$key]['open_start_time'] = '';
                $arr[$key]['open_end_time'] = '';
            }
        }

        return array('shops' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    }

    /**
     * @return 拒绝或删除申请时，根据申请ID，检查是否需要退还费用
     * @param  int $apply_id 申请id
     * @return array 申请对象
     */
    private function checkApplayIsReturn($apply_id) {
        $supplier_model = Model('supplier');
        $users_model = Model('users');
        $apply_info = $supplier_model->select_supplier_module_apply_info('pay_status,pay_type,user_id,apply_money', ' id = ' . $apply_id);
        /*返还余额支付的金额( 其他支付方式，只能由管理员手动返还了 )*/
        if ($apply_info['pay_status'] == 1 && $apply_info['pay_type'] == 1) {
            $param['user_money'] = $apply_info['apply_money'];
            $where['user_id'] = $apply_info['user_id'];
            $up_user_info = $users_model->update_users_setInc($param, $where);
            if ($up_user_info) {
                $change_desc = '今日特价申请被拒，退还金额' . $apply_info['apply_money'] . '元';
                log_account_change($apply_info['user_id'], $apply_info['apply_money'], 0, 0, 0, 0, gmtime(), $change_desc, 3);
            }
        }
        return $apply_info;
    }

}

?>
