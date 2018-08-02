<?php

/**
 * 淘玉php 入驻商管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟$
 * 入驻商管理
 * $Id: supplier.php 17217 2018年5月5日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class supplierControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('supplier'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 入驻商申请列表
     */
    public function lists() {
        /* 检查权限 */
        admin_priv('supplier_manage');

        /* 查询 */
        $result = $this->suppliers_list();

        /* 模板赋值 */
        $ur_here_lang = $_REQUEST['status'] == '1' ? L('supplier_list') : L('supplier_reg_list');
        TPL::assign('ur_here', $ur_here_lang); // 当前导航
        TPL::assign('full_page', 1); // 翻页参数
        TPL::assign('status', $_REQUEST['status']);
        TPL::assign('supplier_list', $result['result']);
        TPL::assign('filter', $result['filter']);
        TPL::assign('record_count', $result['record_count']);
        TPL::assign('page_count', $result['page_count']);
        TPL::assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');
        $supplier_model = Model('supplier');
        $order = " sort_order ";
        $supplier_rank = $supplier_model->get_supplier_rank_list('*', '', $order, '');
        TPL::assign('supplier_rank', $supplier_rank);

        /* 显示模板 */
        TPL::display('supplier_list.htm');
    }

    /**
     * @return 分页、排序、查询
     */
    public function query() {
        check_authz_json('supplier_manage');

        $result = $this->suppliers_list();

        TPL::assign('supplier_list', $result['result']);
        TPL::assign('filter', $result['filter']);
        TPL::assign('record_count', $result['record_count']);
        TPL::assign('page_count', $result['page_count']);

        /* 排序标记 */
        $sort_flag = sort_flag($result['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        make_json_result(TPL::fetch('supplier_list.htm'), '', array('filter' => $result['filter'], 'page_count' => $result['page_count']));
    }

    /**
     * @return 查看、编辑供货商
     */
    public function edit() {
        /* 检查权限 */
        admin_priv('supplier_manage');
        $suppliers = array();
        $supplier_model = Model('supplier');
        /* 取得供货商信息 */
        $id = $_REQUEST['id'];
        $w['supplier_id'] = $id;
        $supplier = $supplier_model->select_supplier_info('*', $w);
        if (count($supplier) <= 0) {
            showMessage(L('supplier_not_exist'));
        }
        $supplier['contacts_phone_mi'] = jiaMiPhone($supplier['contacts_phone']);
        $supplier['handheld_idcard'] = get_imgurl_oss($supplier['handheld_idcard']);
        $supplier['idcard_front'] = get_imgurl_oss($supplier['idcard_front']);
        $supplier['idcard_reverse'] = get_imgurl_oss($supplier['idcard_reverse']);
        /* 省市县 */
        $supplier_country = $supplier['country'] ? $supplier['country'] : C('shop_country');
        TPL::assign('country_list', get_regions());
        TPL::assign('province_list', get_regions(1, $supplier_country));
        TPL::assign('city_list', get_regions(2, $supplier['province']));
        TPL::assign('district_list', get_regions(3, $supplier['city']));
        TPL::assign('supplier_country', $supplier_country);
        /* 供货商等级 */
        $w0['rank_id'] = $supplier['rank_id'];
        $rank_info = $supplier_model->select_supplier_rank_info('*', $w0);
        $supplier['rank_name'] = $rank_info['rank_name'];
        /* 店铺类型 */
        $wh['str_id'] = $supplier['type_id'];
        $street_info = Model('street_category')->select_street_category_info('*', $wh);
        $supplier['type_name'] = $street_info['type_name'];
        TPL::assign('ur_here', L('edit_supplier'));
        if ($_REQUEST['status'] == '1') {
            $lang_supplier_list = L('supplier_list');
            TPL::assign('action_link', array('href' => 'index.php?act=supplier&op=lists&status=1', 'text' => $lang_supplier_list));
        } else {
            $lang_supplier_list = L('supplier_reg_list');
            TPL::assign('action_link', array('href' => 'index.php?act=supplier&op=lists', 'text' => $lang_supplier_list));
        }
        TPL::assign('form_op', 'update');
        TPL::assign('supplier', $supplier);
        /*商品等级*/
        TPL::assign('rank_id', $supplier['rank_id']);
        TPL::assign('supplier_rank_list', $this->get_suprank_list());
        /* 代码增加 By  www.taoyumall.com End */
        TPL::display('supplier_info.htm');
    }

    /**
     * @return 提交添加、编辑供货商
     */
    public function update() {
        /* 检查权限 */
        admin_priv('supplier_manage');
        $supplier_model = Model('supplier');
        /* 提交值 */
        $supplier_id = intval($_POST['id']);
        $status_url = intval($_POST['status_url']);
        $supplier = array(
            'bank_account_name' => trim($_POST['bank_account_name']),
            'bank_account_number' => trim($_POST['bank_account_number']),
            'bank_name' => trim($_POST['bank_name']),
            'bank_code' => trim($_POST['bank_code']),
            'settlement_bank_account_name' => trim($_POST['settlement_bank_account_name']),
            'settlement_bank_account_number' => trim($_POST['settlement_bank_account_number']),
            'settlement_bank_name' => trim($_POST['settlement_bank_name']),
            'settlement_bank_code' => trim($_POST['settlement_bank_code']),
            'rank_id' => $_POST['rank_id'],
            'strategy' => $_POST['strategy'],
            'system_fee' => trim($_POST['system_fee']),
            'supplier_bond' => trim($_POST['supplier_bond']),
            'supplier_rebate' => trim($_POST['supplier_rebate']),
            'supplier_remark' => trim($_POST['supplier_remark']), 
            'status' => intval($_POST['status'])
        );
        /* 取得供货商信息 */
        $supplier_old = $supplier_model->get_supplier_users_info('supplier.user_id,supplier.supplier_id,supplier.add_time,supplier.contacts_phone,supplier.status,users.*','supplier_id='.$supplier_id);
        $contacts_phone = $supplier_old['contacts_phone'];
        if (empty($supplier_old['supplier_id'])) {
            showMessage(L('supplier_not_exist'));
        }

        if (empty($supplier_old['add_time']) && $supplier['status'] == 1) {
            /*审核通过时就是店铺创建成功的时间*/ 
            $supplier['add_time'] = gmtime();
            $magtiles = L('welcome_join_family');
            $rutle = '通过';
        }

        /*操作店铺商品与店铺街信息*/
        if ($supplier['status'] != $supplier_old['status'] && $supplier['status'] == -1) {
            /*审核不通过*/
            /*店铺街信息失效*/
            $magtiles = L('apply_fail_reason') . $supplier['supplier_remark'];
            $rutle = '未通过';
            $check_info = array(
                'is_groom' => 0,
                'is_show' => 0,
                'supplier_notice' => '',
                'status' => 0
            );
            $where['supplier_id'] = $supplier_id;
            Model('street_category')->update_supplier_street($check_info,$where);
            /*商品下架*/
            $good_info = array(
                'is_on_sale' => 0
            );
            Model('goods')->update_goods($good_info, $where);
            /*删除店铺所在的标签*/
            $supplier_model->delete_supplier_tag_map($where);
        }

        /*更新相关店铺的管理员状态*/
        $w['supplier_id'] = $supplier_id;
        $info = $supplier_model->get_supplier_admin_user_list('*', $w);
        if (count($info) > 0) {
            $update_arr['user_name'] = $supplier_old['user_name'];
            $update_arr['password'] = $supplier_old['password'];
            $update_arr['email'] = $supplier_old['email'];
            $update_arr['ec_salt'] = $supplier_old['ec_salt'];
            $update_arr['checked'] = intval($_POST['status']);
            $w['supplier_id'] = $supplier_old['supplier_id'];
            $w['uid'] = $supplier_old['user_id'];
            $supplier_model->update_supplier_admin_user($update_arr, $w);
        } else {
            $insert_arr['uid'] = $supplier_old['user_id'];
            $insert_arr['user_name'] = $supplier_old['user_name'];
            $insert_arr['email'] = $supplier_old['email'];
            $insert_arr['password'] = $supplier_old['password'];
            $insert_arr['ec_salt'] = $supplier_old['ec_salt'];
            $insert_arr['add_time'] = $supplier_old['last_login'];
            $insert_arr['last_login'] = $supplier_old['last_login'];
            $insert_arr['last_ip'] = $supplier_old['last_ip'];
            $insert_arr['action_list'] = 'all';
            $insert_arr['nav_list'] = '';
            $insert_arr['lang_type'] = '';
            $insert_arr['agency_id'] = 0;
            $insert_arr['supplier_id'] = $supplier_old['supplier_id'];
            $insert_arr['todolist'] = NULL;
            $insert_arr['role_id'] = NULL;
            $insert_arr['checked'] = intval($_POST['status']);
            $supplier_model->insert_supplier_admin_user($insert_arr);
        }

        /* 保存供货商信息 */
        $wh['supplier_id'] = $supplier_id;
        $supplier_model->update_supplier($supplier, $wh);
        if ($_POST['status'] != '1') {
            $up_arr['is_on_sale'] = 0;
            $whe['supplier_id'] = $supplier_id;
            Model('goods')->update_goods($up_arr, $whe);
        }

        /*根据是否有短信提示语判断是否发信息*/
        if (!empty($magtiles)) {
            /*发送短信*/
            $mobiles = $supplier_old['contacts_phone'];
            if (!empty($mobiles)) {
                $param = array();
                $param['site_name'] = '淘玉商城';
                $param['rutle'] = $rutle;
                $param['mags'] = $magtiles;
                $result = send_sms_msg($mobiles, 'shop_add', $param);  
            }
        }
        /* 清除缓存 */
        clear_cache_files();

        /* 提示信息 */
        $links[0] = array('href' => ($status_url > 0 ? 'index.php?act=supplier&op=lists&status=1' : 'index.php?act=supplier&op=lists'), 'text' => ($status_url > 0 ? L('back_supplier_list') : L('back_supplier_reg')));
        showMessage(L('edit_supplier_ok'), $links);
    }

    /**
     * @return 删除店铺信息
     */
    public function delete() {
        /* 检查权限 */
        admin_priv('supplier_manage');
        $supplier_id = intval($_GET['id']);
        $supplier_model = Model('supplier');
        $w['supplier_id'] = $supplier_id;
        $supplier = $supplier_model->select_supplier_info('*', $w);
        if (count($supplier) <= 0) {
            showMessage(L('supplier_not_exist'));
        }
        /*删除入驻商相关信息*/
        $res = delete_supplier_all($supplier_id);                    
        if (is_array($res) && count($res) > 0) {
            echo "错误提示:";
            echo "<pre>";
            print_r($res);
            sleep(10);
        }
        /* 提示信息 */
        $links[0] = array('href' => 'index.php?act=supplier&op=lists&status=' . $supplier['status'], 'text' => L('back_last_page'));
        showMessage(L('delete_succ'), $links);
    }

    /**
     * @return 检查是否有权限查看手机号码
     */
    public function check() {
        $a = check_look_phone("check_look_phone");
        if ($a) {
            $data['status'] = 1;
        } else {
            $data['status'] = -1;
        }
        echo json_encode($data);
    }

    /**
     * @return 获取供应商列表信息
     * @return array
     */
    private function suppliers_list() {
        $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;
        $supplier_model = Model('supplier');
        $result = get_filter();
        if ($result === false) {
            /* 过滤信息 */
            $filter['supplier_name'] = empty($_REQUEST['supplier_name']) ? '' : trim($_REQUEST['supplier_name']);
            $filter['rank_name'] = empty($_REQUEST['rank_name']) ? '' : trim($_REQUEST['rank_name']);
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'supplier_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);
            $filter['status'] = empty($_REQUEST['status']) ? '0' : intval($_REQUEST['status']);

            $where = 'WHERE applynum = 3 ';
            $where0 = 'WHERE applynum = 3 ';
            /*$_REQUEST['type'] == a  ,是页面做搜索时，对于未审核（状态值=0）的特殊处理*/
            if ($_REQUEST['type'] == 'a') {
                $where .= $filter['status'] == -1 ? " AND s.status = '" . $filter['status'] . "' " : " AND s.status = 0";
                $where0 .= $filter['status'] == -1 ? " AND status = '" . $filter['status'] . "' " : " AND status = 0";
            } else {
                $where .= $filter['status'] ? " AND s.status = '" . $filter['status'] . "' " : " AND s.status in('0','-1') ";
                $where0 .= $filter['status'] ? " AND status = '" . $filter['status'] . "' " : " AND status in('0','-1') ";
            }

            if ($filter['supplier_name']) {
                $where .= " AND supplier_name LIKE '%" . mysql_like_quote($filter['supplier_name']) . "%'";
            }
            if ($filter['rank_name']) {
                $where .= " AND rank_id = '$filter[rank_name]'";
            }

            /* 记录总数 */
            $filter['record_count'] = $supplier_model->get_supplier_count($where0);
            /* 分页大小 */
            $filter = page_and_size($filter);
            /* 查询 */
            $sql = "SELECT s.supplier_id,u.user_name,u.alias, s.jifen,s.rank_id, s.supplier_name, s.contacts_phone,s.contacts_name, s.system_fee, s.supplier_bond, 
                    s.supplier_rebate, s.supplier_remark, s.province,s.city,s.district, s.status " .
                    "FROM " . Model()->tablename("supplier") . " as s left join " . Model()->tablename("users") . " as u on s.user_id = u.user_id $where
                    ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'];
            $rankname_list = array();
            $res2 = $supplier_model->get_supplier_rank_list('1');
            if(!empty($res2)){
                foreach ($res2 as $row2) {
                    $rankname_list[$row2['rank_id']] = $row2['rank_name'];
                }
            }            
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $list = array();
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        if(!empty($res)){
            foreach ($res as $row) {
                $row['rank_name'] = $rankname_list[$row['rank_id']];
                $row['status_name'] = $row['status'] == '1' ? '通过' : ($row['status'] == '0' ? "未审核" : "未通过");
                $row['contacts_phone_mi'] = jiaMiPhone($row['contacts_phone']);
                $row['supplier_address'] = get_province_city($row['province'], $row['city'], $row['district']);
                $list[] = $row;
            }
        }
        
        $arr = array('result' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * @return 取得店铺等级列表
     * @return array 店铺等级列表 id => name
     */
    private function get_suprank_list() {
        $supplier_model = Model('supplier');
        $res = $supplier_model->get_supplier_rank_list('*', '', ' sort_order', '');

        $rank_list = array();
        if(!empty($res)){
            foreach ($res AS $row) {
                $rank_list[$row['rank_id']] = addslashes($row['rank_name']);
            }
        }
        return $rank_list;
    }
}
?>

