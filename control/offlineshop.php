<?php

/**
 * 淘玉php 实体店设置
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 实体店设置
 * $Id: offlineshop.php 17217 2018年5月4日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class offlineshopControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('offlineshop'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 实体店记录列表
     */
    public function lists() {
        /* 权限的判断 */
        admin_priv('shop_set');
        $shop_list = $this->get_offlineshop_list();

        TPL::assign('ur_here', L('store_list'));
        TPL::assign('action_link', array('href' => 'index.php?act=offlineshop&op=offshop_add', 'text' => L('add_hotel_info')));
        TPL::assign('full_page', 1);
        TPL::assign('shop_list', $shop_list['list']);
        TPL::assign('filter', $shop_list['filter']);
        TPL::assign('record_count', $shop_list['record_count']);
        TPL::assign('page_count', $shop_list['page_count']);
        $sort_flag = sort_flag($shop_list['filter']);

        TPL::assign($sort_flag['tag'], $sort_flag['img']);
        TPL::display('offlineshop_list.htm');
    }

    /**
     * @return 搜索查询 分页列表显示
     */
    public function offline_query() {
        /* 权限的判断 */
//        admin_priv('shop_set');
        $shop_list = $this->get_offlineshop_list();
        TPL::assign('ur_here', L('store_list'));
        TPL::assign('action_link', array('href' => 'index.php?act=offlineshop&op=offshop_add', 'text' => L('add_hotel_info')));
        TPL::assign('shop_list', $shop_list['list']);
        TPL::assign('filter', $shop_list['filter']);
        TPL::assign('record_count', $shop_list['record_count']);
        TPL::assign('page_count', $shop_list['page_count']);
        $sort_flag = sort_flag($shop_list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(TPL::fetch('offlineshop_list.htm'), '', array('filter' => $shop_list['filter'], 'page_count' => $shop_list['page_count']));
    }

    /**
     * @return 添加实体店信息
     */
    public function offshop_add() {
        admin_priv('shop_set');
        $act_model = Model('activity');
        $offline_into = array();
        $offline_id = 0;
        TPL::assign('ur_here', L('add_hotel_info'));
        TPL::assign('country_list', get_regions());
        /* 载入国家 */
        TPL::assign('action_link', array('href' => 'index.php?act=offlineshop&op=lists', 'text' => L('back_store_list')));
        TPL::assign('offline', $offline_into);
        TPL::assign('form_op', 'offshop_insert');
        TPL::assign('form_act', 'offlineshop');
        $province_list = get_regions(1, $offline_into['country']);
        $city_list = get_regions(2, $offline_into['province']);
        $district_list = get_regions(3, $offline_into['city']);
        TPL::assign('province_list', $province_list);
        TPL::assign('city_list', $city_list);
        TPL::assign('district_list', $district_list);
        TPL::display('offlineshop_info.htm');
    }

    /**
     * @return 编辑实体店信息
     */
    public function offshop_edit() {
        admin_priv('shop_set');
        $act_model = Model('activity');
        $offline_id = $_REQUEST['offline_id'];
        $where['offline_id'] = $offline_id;
        $offline_into = $act_model->select_offlineshop_list_info('*',$where);
        TPL::assign('ur_here', L('edit_store'));
        TPL::assign('form_op', 'offshop_update');
        TPL::assign('form_act', 'offlineshop');
        TPL::assign('country_list', get_regions());
        /* 载入国家 */
        TPL::assign('action_link', array('href' => 'index.php?act=offlineshop&op=lists', 'text' => L('back_store_list')));
        TPL::assign('offline', $offline_into);

        $province_list = get_regions(1, $offline_into['country']);
        $city_list = get_regions(2, $offline_into['province']);
        $district_list = get_regions(3, $offline_into['city']);
        TPL::assign('province_list', $province_list);
        TPL::assign('city_list', $city_list);
        TPL::assign('district_list', $district_list);
        TPL::display('offlineshop_info.htm');
    }

    /**
     * @return 添加实体店信息入库
     */
    public function offshop_insert() {
        admin_priv('shop_set');
        $offline_name = empty($_REQUEST['offline_name']) ? '' : trim($_REQUEST['offline_name']);
        $offline_tel = empty($_REQUEST['offline_tel']) ? '' : trim($_REQUEST['offline_tel']);
        $country = empty($_REQUEST['country']) ? '' : trim($_REQUEST['country']);
        $province = empty($_REQUEST['province']) ? '' : trim($_REQUEST['province']);
        $city = empty($_REQUEST['city']) ? '' : trim($_REQUEST['city']);
        $district = empty($_REQUEST['district']) ? '' : trim($_REQUEST['district']);
        $address = $_REQUEST['address'];
        $offline_desc = $_REQUEST['offline_desc'];
        $offline_style = $_REQUEST['style_id'];
        $new_times = gmtime();
        $insert_arr['offline_name'] = $offline_name;
        $insert_arr['offline_tel'] = $offline_tel;
        $insert_arr['offline_desc'] = $offline_desc;
//        $insert_arr['offline_style'] = $offline_style;
        $insert_arr['add_time'] = $new_times;
        $insert_arr['update_time'] = $new_times;
        $insert_arr['country'] = $country;
        $insert_arr['province'] = $province;
        $insert_arr['city'] = $city;
        $insert_arr['district'] = $district;
        $insert_arr['address'] = $address;
        $offlineshopid = add_table_info('offlineshop_list', $insert_arr);
        if (!empty($offlineshopid)) {
            $imgurl = $this->offlineshop_qrcode($offlineshopid); //酒店二维码
            if (!empty($imgurl)) {
                $update_arr['offline_imgs'] = $imgurl;
                $where['offline_id'] = $offlineshopid;
                update_table_info('offlineshop_list', $update_arr, $where);
                admin_log($offline_name, 'add', 'offlineshop_list');
                /* 清楚缓存文件 */
                clear_cache_files();
                $link[0]['text'] = L('back_store_list');
                $link[0]['href'] = 'index.php?act=offlineshop&op=lists';
                showMessage(L('add_store_succ'), $link);
            } else {
                $where1['offline_id'] = $offlineshopid;
                $result = delete_table_info('offlineshop_list', $where1);
                $link[0]['text'] = L('add_store_again');
                $link[0]['href'] = 'index.php?act=offlineshop&op=offshop_add';
                showMessage(L('add_store_fail'), $link);
            }
        } else {
            $link[0]['text'] = L('add_store_again');
            $link[0]['href'] = 'index.php?act=offlineshop&op=offshop_add';
            showMessage(L('add_store_fail'), $link);
        }
    }

    /**
     * @return 编辑实体店信息入库
     */
    public function offshop_update() {
        admin_priv('shop_set');
        $offline_name = empty($_REQUEST['offline_name']) ? '' : trim($_REQUEST['offline_name']);
        $offline_tel = empty($_REQUEST['offline_tel']) ? '' : trim($_REQUEST['offline_tel']);
        $country = empty($_REQUEST['country']) ? '' : trim($_REQUEST['country']);
        $province = empty($_REQUEST['province']) ? '' : trim($_REQUEST['province']);
        $city = empty($_REQUEST['city']) ? '' : trim($_REQUEST['city']);
        $district = empty($_REQUEST['district']) ? '' : trim($_REQUEST['district']);
        $address = $_REQUEST['address'];
        $offline_desc = $_REQUEST['offline_desc'];
        $offline_style = $_REQUEST['style_id'];
        $new_times = gmtime();

        $offline_id = trim($_REQUEST['offline_id']);
        $update_arr['offline_name'] = $offline_name;
        $update_arr['offline_tel'] = $offline_tel;
        $update_arr['offline_desc'] = $offline_desc;
//        $update_arr['offline_style'] = $offline_style;
        $update_arr['update_time'] = $new_times;
        $update_arr['country'] = $country;
        $update_arr['province'] = $province;
        $update_arr['city'] = $city;
        $update_arr['district'] = $district;
        $update_arr['address'] = $address;
        $where['offline_id'] = $offline_id;
        $zhans = update_table_info('offlineshop_list', $update_arr, $where);
        if (!empty($zhans)) {
            admin_log($offline_name, 'edit', 'offlineshop_list');
            /* 清楚缓存文件 */
            clear_cache_files();
            $link[0]['text'] = L('back_store_list');
            $link[0]['href'] = 'index.php?act=offlineshop&op=lists';
            showMessage(L('edit_store_succ'), $link);
        } else {
            $link[0]['text'] = L('edit_store_again');
            $link[0]['href'] = 'index.php?act=offlineshop&op=offshop_edit';
            showMessage(L('edit_store_fail'), $link);
        }
    }

    /**
     * @return 对应酒店绑定用户列表
     */
    public function offuser_list() {
        admin_priv('shop_set');
        $act_model = Model('activity');
        $offline_id = $_REQUEST['offline_id'];
        if (!empty($offline_id)) {
            $shop_list = $this->get_offlineuser_list($offline_id);
            TPL::assign('ur_here', L('store_user_list'));
            TPL::assign('full_page', 1);
            TPL::assign('shop_list', $shop_list['list']);
            TPL::assign('filter', $shop_list['filter']);
            TPL::assign('record_count', $shop_list['record_count']);
            TPL::assign('page_count', $shop_list['page_count']);
            $sort_flag = sort_flag($shop_list['filter']);
            TPL::assign($sort_flag['tag'], $sort_flag['img']);
//            assign_query_info();
            TPL::display('offlineuser_list.htm');
            $field = " offlineshop_user.add_time,offlineshop_user.starts_stype,users.user_name,users.alias,users.reg_time,users.parent_id,offlineshop_list.offline_name,offlineshop_list.offline_tel";
            $w = "offlineshop_user.offline_id = " . $offline_id;
            $row = $act_model->get_offlineshop_user_users_offlineshop_list_list($field, $w);

            if (!empty($row)) {
                $deleimg = ossDeleteFileObject($secofflin['offline_imgs']);
                if ($deleimg['stasus'] == 'success') {
                    $where['offline_id'] = $offline_id;
                    $result = delete_table_info('offlineshop_list', $where);
                    if ($result) {
                        admin_log($offline_id, 'remove', 'offlineshop_list');
                        $url = 'index.php?act=offlineshop&op=offline_query';
                        ecs_header("Location: $url\n");
                        exit;
                    } else {
                        $link[0]['text'] = L('delete_data_fail');
                        $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
                        showMessage(L('delete_data_fail'), $link);
                    }
                } else {
                    $link[0]['text'] = L('remove_qrcode_faile');
                    $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
                    showMessage(L('remove_qrcode_faile'), $link);
                }
            } else {
                $link[0]['text'] = L('bind_exist');
                $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
                showMessage(L('bind_exist'), $link);
            }
        } else {
            $link[0]['text'] = L('data_error');
            $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
            showMessage(L('data_error'), $link);
        }
    }

    /**
     * @return 删除单个酒店信息
     */
    public function remove_offline() {
        admin_priv('shop_set');
        $act_model = Model('activity');
        $offline_id = intval($_GET['id']);
        $where0 = " offline_id = " . $offline_id;
        $secofflin = $act_model->select_offlineshop_list_info('*',$where0);
        if (!empty($secofflin)) {
            $where = " where offline_id = " . $offline_id;
            $count = $act_model->get_offlineshop_user_count($where);
            if ($count == 0) {
                $deleimg = ossDeleteFileObject($secofflin['offline_imgs']);
                if ($deleimg['stasus'] == 'success') {
                    $wheres['offline_id'] = $offline_id;
                    $result = $act_model->delete_offlineshop($wheres);
                    if ($result) {
                        admin_log($offline_id, 'remove', 'offlineshop_list');
                        $url = 'index.php?act=offlineshop&op=offline_query';
                        ecs_header("Location: $url\n");
                        exit;
                    } else {
                        $link[0]['text'] = L('remove_data_faile');
                        $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
                        showMessage(L('remove_data_faile'), $link);
                    }
                } else {
                    $link[0]['text'] = L('remove_qrcode_faile');
                    $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
                    showMessage(L('remove_qrcode_faile'), $link);
                }
            } else {
                $link[0]['text'] = L('bind_exist');
                $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
                showMessage(L('bind_exist'), $link);
            }
        } else {
            $link[0]['text'] = L('not_exist');
            $link[0]['href'] = 'index.php?act=offlineshop&op=offline_query';
            showMessage(L('not_exist'), $link);
        }
    }

    /**
     * @return 获取线下酒店信息列表
     */
    function get_offlineshop_list() {
        $act_model = Model('activity');
        $wheres = '';
        $words = empty($_REQUEST['words']) ? '' : trim($_REQUEST['words']);
        if (!empty($words)) {
            $wheres = " WHERE offline_name like '%" . $words . "%' OR offline_tel like '%" . $words . "%' ";
        } else {
            $wheres = '';
        }

        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'offline_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['record_count'] = $act_model->get_offlineshop_list_count($wheres);

        $filter = page_and_size($filter);
        $sql = 'SELECT * FROM ' . Model()->tablename('offlineshop_list') . $wheres .
                " ORDER by $filter[sort_by] $filter[sort_order] ";
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($row as $key => $value) {
            $row[$key]['add_time'] = local_date("Y-m-d", $value['add_time']);
            $row[$key]['man_address'] = get_province_city($value['province'], $value['city'], $value['district']);
            $offlineshop_id = $value['offline_id'];
            $where = " where offline_id = " . $offlineshop_id;
            $count = $act_model->get_offlineshop_user_count($where);
            $row[$key]['imgurl'] = get_imgurl_oss($value['offline_imgs'], 40, 40, false, true);
            $row[$key]['daimgurl'] = get_imgurl_oss($value['offline_imgs'], 600, 600, false, true);
            if (!empty($count)) {
                $row[$key]['counts'] = $count['nums'];
            } else {
                $row[$key]['counts'] = 0;
            }
        }
        $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /* 获取线下酒店绑定用户列表 */

    function get_offlineuser_list($offline_id) {
        $activity_model = Model('activity');
        $wheres = '';
        $words = empty($_REQUEST['words']) ? '' : trim($_REQUEST['words']);
        if (!empty($words)) {
            $wheres = " WHERE offline_name like '%" . $words . "%' OR offline_tel like '%" . $words . "%' ";
        } else {
            $wheres = '';
        }

        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'offline_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['record_count'] = $activity_model->get_offlineshop_list_count($wheres);

        $filter = page_and_size($filter);
        $sql = 'SELECT * FROM ' . Model()->tablename('offlineshop_list') . $wheres .
                " ORDER by $filter[sort_by] $filter[sort_order]";
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($row as $key => $value) {
            $row[$key]['add_time'] = local_date("Y-m-d", $value['add_time']);
            $row[$key]['man_address'] = get_province_city($value['province'], $value['city'], $value['district']);
            $offlineshop_id = $value['offline_id'];
            $w = " WHERE offline_id = " . $offlineshop_id;
            $count = $activity_model->get_offlineshop_user_count($w);
            $row[$key]['imgurl'] = get_imgurl_oss($value['offline_imgs'], 40, 40, false, true);
            $row[$key]['daimgurl'] = get_imgurl_oss($value['offline_imgs'], 600, 600, false, true);
            if (!empty($count)) {
                $row[$key]['counts'] = $count['nums'];
            } else {
                $row[$key]['counts'] = 0;
            }
        }
        $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * 生成线下酒店二维码
     * zhangxiao 2018/1/19 星期五
     */
    function offlineshop_qrcode($offline_id) {
        $filename = creat_qr("https://www.taoyumall.com/mobile/goods.php?id=$offline_id&qr_code_type=jiudian", 'data/offline_qrcodr', '16.1', $offline_id);
        $ossfile = ossUploadFileByUrl($filename);
        $imgurl = '';
        if ($ossfile['stasus'] == 'success') {
            $imgurl = $ossfile['url'];
            @unlink(ROOT_PATH . $filename);
            return $imgurl;
        } else {
            return false;
        }
    }

}
?>

