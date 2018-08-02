<?php

/**
 * 淘玉php 店铺街列表
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 店铺街列表
 * $Id: supplierstreet.php 17217 2018年5月5日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class supplierstreetControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('supplier_street'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 店铺街列表
     */
    function lists() {
        admin_priv('supplier_manage');
        /* 模板赋值 */
        $check = (isset($_REQUEST['check']) && $_REQUEST['check'] !== false) ? intval($_REQUEST['check']) : false;
        $name = L('supplier_street_list');
        if ($check !== false) {
            $name = ($check == 1) ? L('supplier_street_list_passed') : L('supplier_street_list_unchecked');
        }
        TPL::assign('ur_here', $name);
        TPL::assign('str_category', $this->get_street_type());
        $street_list = $this->get_street_list();
        TPL::assign('shops_list', $street_list['shops']);
        TPL::assign('filter', $street_list['filter']);
        TPL::assign('record_count', $street_list['record_count']);
        TPL::assign('page_count', $street_list['page_count']);
        TPL::assign('full_page', 1);
        /* 列表页面 */
        TPL::display('street_list.htm');
    }

    /**
     * @return 分页、排序、查询
     */
    public function street_query() {

        /* 检查权限 */
        admin_priv('supplier_manage');
        $street_list = $this->get_street_list();
        TPL::assign('shops_list', $street_list['shops']);
        TPL::assign('filter', $street_list['filter']);
        TPL::assign('record_count', $street_list['record_count']);
        TPL::assign('page_count', $street_list['page_count']);
        make_json_result(TPL::fetch('street_list.htm'), '', array('filter' => $street_list['filter'], 'page_count' => $street_list['page_count']));
    }

    /**
     * @return 编辑内容
     */
    public function edit_info() {
        admin_priv('supplier_manage');
        $supplier_model = Model('supplier');
        $suppid = $_REQUEST['supplier_id'];
        TPL::assign('ur_here', L('edit_supplier_street_info'));
        TPL::assign('action_link', array('href' => 'index.php?act=supplierstreet&op=lists', 'text' => L('back_to_supplier_street_list')));
        $where['supplier_id'] = $suppid;
        $info = $supplier_model->select_supplier_street_info('*', $where);
        TPL::assign('sinfo', $info);
        TPL::assign('stype', $this->get_street_type());
        /* 列表页面 */
        TPL::display('street_info.htm');
    }

    /**
     * @return 保存编辑内容
     */
    public function saveinfo() {
        admin_priv('supplier_manage');
        $supplier_model = Model('supplier');
        $suppid = intval($_REQUEST['suppid']);
        $save['supplier_type'] = intval($_REQUEST['supplier_type']);
        $save['supplier_name'] = addslashes(htmlspecialchars($_REQUEST['supplier_name']));
        $save['supplier_title'] = addslashes(htmlspecialchars($_REQUEST['supplier_title']));
        $save['supplier_notice'] = trim(addslashes(htmlspecialchars($_REQUEST['supplier_notice'])));
        $save['is_show'] = intval($_REQUEST['is_show']);
        $save['is_groom'] = intval($_REQUEST['is_groom']);
        $save['sort_order'] = intval($_REQUEST['sort_order']);
        if (empty($save['supplier_notice'])) {
            $link[] = array('text' => L('go_back'), 'href' => 'javascript:history.back(-1)');
            showMessage(L('check_notice_is_null'), $link);
        }
        $where['supplier_id'] = $suppid;
        $result = $supplier_model->update_supplier_street($save, $where);
        if ($result) {
            $link[] = array('text' => L('back_to_supplier_street_list'), 'href' => 'index.php?act=supplierstreet&op=lists');
            showMessage(L('attradd_succed'), $link);
        }
    }

    /**
     * @return 批量下线
     */
    public function remove_show() {
        admin_priv('supplier_manage');
        $supp_id = $_REQUEST['supplier_id'];
        /* 删除退货单 */
        $supplier_model = Model('supplier');
        $update['is_show'] = 0;
        $where = "supplier_id in($supp_id)";
        $supplier_model->update_supplier_street($update, $where);
        /* 返回 */
        $link[] = array('text' => L('back_to_supplier_street_list'), 'href' => 'index.php?act=supplierstreet&op=lists');
        showMessage(L('attradd_succed'), $link);
    }

    /**
     * @return 删除或批量删除
     */
    public function remove_supplier() {
        admin_priv('supplier_manage');
        $supplier_model = Model('supplier');
        $supp_id = $_REQUEST['supplier_id'];
        /* 删除退货单 */
        $where = '';
        if (is_array($supp_id)) {
            $supp_id_list = implode(",", $supp_id);
            $where = "supplier_id in($supp_id_list)";
        } else {
            $where = "supplier_id in($supp_id)";
        }
        $supplier_model->delete_supplier_street($where);
        /* 返回 */
        $link[] = array('text' => L('back_to_supplier_street_list'), 'href' => 'index.php?act=supplierstreet&op=lists');
        showMessage(L('attradd_succed'), $link);
    }

    /**
     * @return 编辑排序序号
     */
    public function edit_sort_order() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        if (empty($id)) {
            make_json_error(Db::error());
        }
        $up_arr['sort_order'] = $val;
        $where['supplier_id'] = $id;
        $result = $supplier_model->update_supplier_street($up_arr, $where);
        if ($result) {
            make_json_result($val);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 切换是否显示
     */
    public function toggle_is_show() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        if (empty($id)) {
            make_json_error(Db::error());
        }
        $up_arr['is_show'] = $val;
        $where['supplier_id'] = $id;
        $result = $supplier_model->update_supplier_street($up_arr, $where);
        if ($result) {
            make_json_result($val);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 切换是否推荐
     */
    public function toggle_is_groom() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        if (empty($id)) {
            make_json_error(Db::error());
        }
        $up_arr['is_groom'] = $val;
        $where['supplier_id'] = $id;
        $result = $supplier_model->update_supplier_street($up_arr, $where);
        if ($result) {
            make_json_result($val);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 切换审核状态
     */
    public function toggle_status() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        if (empty($id)) {
            make_json_error(Db::error());
        }
        $info['status'] = $val;
        $info['supplier_notice'] = '';
        if ($val > 0) {

            $info['supplier_notice'] = L('has_allowed');
        }
        $where['supplier_id'] = $id;
        $result = $supplier_model->update_supplier_street($info, $where);
        if ($result) {
            make_json_result($val);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @return 选择店铺标签
     */
    public function toggle_tag() {
        check_authz_json('supplier_manage');
        $supplier_model = Model('supplier');
        $tid = intval($_POST['tid']);
        $sid = intval($_POST['sid']);
        $val = intval($_POST['val']);
        $where['tag_id'] = $tid;
        $where['supplier_id'] = $sid;
        if ($val > 0) {
            //添加或者修改店铺的对应标签
            $result = $supplier_model->get_supplier_tag_map_list('*', $where);
            if (empty($result)) {
                $res = $supplier_model->insert_supplier_tag_map($where);
            } else {
                $res = $supplier_model->update_supplier_tag_map($where, $where);
            }
        } else {
            //删除店铺的对应标签记录
            $res = $supplier_model->delete_supplier_tag_map($where);
        }
        if ($res) {
            make_json_result($val);
        } else {
            make_json_error(Db::error());
        }
    }

    /**
     * @access  public
     * @param
     * @return 获取店铺街店铺列表
     */
    function get_street_list() {
        $result = get_filter();
        $supplier_model = Model('supplier');
        if ($result === false) {
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'supplier_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['supplier_type'] = empty($_REQUEST['supplier_type']) ? 0 : intval($_REQUEST['supplier_type']);
            $filter['supplier_name'] = empty($_REQUEST['supplier_name']) ? '' : trim(addslashes(htmlspecialchars($_REQUEST['supplier_name'])));
            $_REQUEST['is_show'] = (isset($_REQUEST['is_show']) && $_REQUEST['is_show'] !== false && $_REQUEST['is_show'] > -1) ? intval($_REQUEST['is_show']) : false;
            $_REQUEST['check'] = (isset($_REQUEST['check']) && $_REQUEST['check'] !== false) ? intval($_REQUEST['check']) : false;
            $where = " WHERE 1 ";
            $where0 = " WHERE 1 ";
            if ($filter['supplier_type']) {
                $where .= " AND supplier_type=" . $filter['supplier_type'];
                $where0 .= " AND supplier_type=" . $filter['supplier_type'];
            }
            if ($filter['supplier_name']) {
                $where .= " AND supplier_name LIKE '%" . mysql_like_quote($filter['supplier_name']) . "%'";
                $where0 .= " AND supplier_name LIKE '%" . mysql_like_quote($filter['supplier_name']) . "%'";
            }
            if ($_REQUEST['is_show'] !== false) {
                $where .= " AND ss.is_show=" . $_REQUEST['is_show'];
                $where0 .= " AND is_show=" . $_REQUEST['is_show'];
            }

            if ($_REQUEST['check'] !== false) {
                $where .= " AND status=" . $_REQUEST['check'];
                $where0 .= " AND status=" . $_REQUEST['check'];
            }
            /* 记录总数 */

            $filter['record_count'] = $supplier_model->get_supplier_street_count($where0);
//            $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;
            /* 分页大小 */
            $filter = page_and_size($filter);
            $sql = "SELECT ss.*,sc.str_name " .
                    " FROM " . Model()->tablename('supplier_street') . " AS ss " .
                    " LEFT JOIN" . Model()->tablename('street_category') . " AS sc " .
                    " ON supplier_type = sc.str_id " .
                    " $where" .
                    " ORDER BY $filter[sort_by] $filter[sort_order] ";
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $arr = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($arr as $k => $v) {
            $arr[$k]['taginfo'] = $this->get_tag_map($v['supplier_id']);
        }
        return array('shops' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    }

    /**
     * @return 获取店铺街分类列表
     * * @access  public
     * @param
     */
    function get_street_type() {

        $supplier_model = Model('supplier');

        $info = $supplier_model->get_street_category_list('*', 'is_show = 1');

        $ret = array();

        foreach ($info as $k => $v) {

            $ret[$v['str_id']] = $v['str_name'];
        }

        return $ret;
    }

    /**
     * @return 获取店铺已经选择的标签
     * @param int $suppid 店铺id
     */
    function get_tag_map($suppid) {
        $supplier_model = Model('supplier');
        $ret = array();
        $tag_info = $this->get_tag();
        $info = $supplier_model->get_supplier_tag_map_supplier_tag_list('supplier_tag_map.tag_id,supplier_tag.tag_name ', 'supplier_tag_map.supplier_id=' . $suppid);
        if (empty($info)) {
            return $tag_info;
        } else {
            foreach ($info as $key => $val) {
                $tag_info[$val['tag_id']] = array('tag_id' => $val['tag_id'], 'tag_name' => $val['tag_name'], 'select' => 1);
            }
            return $tag_info;
        }
    }

    /**

     * @return 获取店铺标签

     */
    function get_tag() {
        $supplier_model = Model('supplier');
        $ret = array();
        $query = $supplier_model->get_supplier_tag_list('tag_id,tag_name', 'is_groom=1', 'sort_order');
        foreach ($query as $row) {
            $ret[$row['tag_id']] = array('tag_id' => $row['tag_id'], 'tag_name' => $row['tag_name']);
        }
        return $ret;
    }

}
?>

