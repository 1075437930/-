<?php

/**
 * 淘玉php 入驻商积分管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 入驻商积分管理
 * $Id: supintegral.php 17217 2018年5月7日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class supintegralControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('supintegral,calendar'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 处罚分类列表
     */
    public function punish_list() {
        TPL::assign('ur_here', L('punish_cat'));
        TPL::assign('action_link', array('text' => L('add_punish_cat'), 'href' => 'index.php?act=supintegral&op=punish_add'));
        TPL::assign('full_page', '1');
        $punishlist = Model('supplier_points')->get_supplier_points_list('*','1');
        TPL::assign('punish_list', $punishlist);
        TPL::display('punish_list.htm');
    }

    /**
     * @return 处罚分类列表分页、排序、查询
     */
    public function punish_query() {
        $punish_reason = $_POST['punish_reason'];
        /* 清除缓存 */
        clear_cache_files();
        $where = " `reason` like '%" . $punish_reason . "%'";
        $punish = Model('supplier_points')->get_supplier_points_list('*', $where);
        TPL::assign('ur_here', L('punish_cat'));
        TPL::assign('action_link', array('text' => L('add_punish_cat'), 'href' => 'index.php?act=supintegral&op=punish_add'));
        TPL::assign('punish_list', $punish);
        make_json_result(TPL::fetch('punish_list.htm'), '', array());
    }

    /**
     * @return 添加处罚分类页面
     */
    public function punish_add() {
        admin_priv('supp_punish');
        TPL::assign('action_link', array('text' => L('punish_cat'), 'href' => 'index.php?act=supintegral&op=punish_list'));
        TPL::assign('ur_here', L('add_punish_cat'));
        TPL::assign('form_act', 'supintegral');
        TPL::assign('form_op', 'punish_insert');
        TPL::display('punish_info.htm');
    }

    /**
     * @return 添加处罚分类数据入库
     */
    public function punish_insert() {
        admin_priv('supp_punish');
        $supplier_model = Model('supplier_points');
        $punish_reason = !empty($_POST['punish_reason']) ? $_POST['punish_reason'] : '';
        $punish_count = !empty($_POST['punish_count']) ? $_POST['punish_count'] : '';
        $punish_beizhu = !empty($_POST['punish_beizhu']) ? $_POST['punish_beizhu'] : '';
        $punish_instructions = !empty($_POST['punish_instructions']) ? $_POST['punish_instructions'] : '';
        $insert['reason'] = $punish_reason;
        $insert['count'] = $punish_count;
        $insert['instructions'] = $punish_instructions;
        $insert['beizhu'] = $punish_beizhu;
        $ins = $supplier_model->insert_supplier_points($insert);
        if ($ins) {
            /* 清除缓存 */
            clear_cache_files();

            admin_log($_POST['punish_reason'], 'add', 'punish');

            $link[0]['text'] = L('punish_cat');
            $link[0]['href'] = 'index.php?act=supintegral&op=punish_list';
            $note = vsprintf(L('add_punish_cat_succ'), $_POST['punish_reason']);
            showMessage($note, $link);
        } else {
            die(Db::error());
        }
    }

    /**
     * @return 编辑处罚分类
     */
    public function punish_edit() {
        /* 权限判断 */
        admin_priv('supp_punish');
        $supplier_model = Model('supplier_points');
        $where['id'] = $_REQUEST[id];
        $punish = $supplier_model->select_supplier_points_info('*', $where);
        TPL::assign('ur_here', L('edit_punish_cat'));
        TPL::assign('action_link', array('text' => L('punish_cat'), 'href' => 'index.php?act=supintegral&op=punish_list'));
        TPL::assign('punish', $punish);
        TPL::assign('form_act', 'supintegral');
        TPL::assign('form_op', 'punish_update');
        TPL::display('punish_info.htm');
    }

    /**
     * @return 编辑处罚分类入库
     */
    public function punish_update() {
        /* 权限判断 */
        admin_priv('supp_punish');
        $supplier_model = Model('supplier_points');
        /* 对描述处理 */
        $punish_reason = !empty($_POST['punish_reason']) ? $_POST['punish_reason'] : '';
        $punish_count = !empty($_POST['punish_count']) ? $_POST['punish_count'] : '';
        $punish_beizhu = !empty($_POST['punish_beizhu']) ? $_POST['punish_beizhu'] : '';
        $punish_instructions = !empty($_POST['punish_instructions']) ? $_POST['punish_instructions'] : '';
        $param['reason'] = $punish_reason;
        $param['count'] = $punish_count;
        $param['instructions'] = $punish_instructions;
        $param['beizhu'] = $punish_beizhu;
        $where['id'] = $_REQUEST[id];
        $up = $supplier_model->update_supplier_points($param, $where);
        if ($up) {
            /* 清除缓存 */
            clear_cache_files();
            admin_log($_POST['punish_reason'], 'edit', 'punish');
            $link[0]['text'] = L('punish_cat');
            $link[0]['href'] = 'index.php?act=supintegral&op=punish_list';
            $note = vsprintf(L('edit_punish_cat_succ'), $_POST['punish_reason']);
            showMessage($note, $link);
        } else {
            $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('编辑处罚分类失败',''), $link);
        }
    }

    /**
     * @return 删除处罚分类
     */
    public function remove() {
        admin_priv('supp_punish');
        $supplier_model = Model('supplier_points');
        $id = intval($_REQUEST['id']);
        $where['id'] = $id;
        $del = $supplier_model->delete_supplier_points($where);
        if ($del) {
            /* 清除缓存 */
            clear_cache_files();
            $punish = $supplier_model->get_supplier_points_list('*');
            TPL::assign('ur_here', L('punish_cat'));
            TPL::assign('action_link', array('text' => L('add_punish_cat'), 'href' => 'index.php?act=supintegral&op=punish_add'));
            TPL::assign('punish_list', $punish);
            make_json_result(TPL::fetch('punish_list.htm'), '', array());
        }
    }

    /**
     * @return 获取处罚分类
     */
    public function huoqu_jifen() {
        $jifen_id = $_REQUEST['jifen_id'];
        $where['id'] = $jifen_id;
        $jifen_info = Model('supplier_points')->select_supplier_points_info('*',$where);
        make_json_result($jifen_info['count']);
    }



    /**
     * @return 入驻商积分详情列表
     */
    public function jifen_detail() {
        $supplier_id = $_REQUEST['id'];
        TPL::assign('supplier_id', $supplier_id);
        if (empty($_REQUEST['change_type']) || !in_array($_REQUEST['change_type'], array('2', '1'))) {
            $change_type = '';
        } else {
            $change_type = $_REQUEST['change_type'];
        }
        TPL::assign('change_type', $change_type);
        $jifenlist = $this->get_jifenlist($supplier_id, $change_type);
        $supplier_jiinfo = $this->supplier_jifen_count($supplier_id);
        TPL::assign('ur_here', L('supplier_jifen_detail'));
        TPL::assign('action_link', array('text' => L('supplier_list'), 'href' => 'index.php?act=supplier&op=lists&status=1'));
        TPL::assign('supp_info', $supplier_jiinfo['supp_info']);
        TPL::assign('zengjia', $supplier_jiinfo['count_jia']);
        TPL::assign('jianshao', $supplier_jiinfo['count_jian']);
        TPL::assign('jifen_list', $jifenlist['account']);
        TPL::assign('filter', $jifenlist['filter']);
        TPL::assign('record_count', $jifenlist['record_count']);
        TPL::assign('page_count', $jifenlist['page_count']);
        TPL::assign('full_page', '1');
        TPL::display('jifen_detail.htm');
    }

    /**
     * @return 入驻商积分详情列表分页、排序、查询
     */
    public function query() {
        /* 检查参数 */
        $supplier_id = $_REQUEST['id'];
        TPL::assign('supplier_id', $supplier_id);
        if (empty($_REQUEST['change_type']) || !in_array($_REQUEST['change_type'], array('2', '1'))) {
            $change_type = '';
        } else {
            $change_type = $_REQUEST['change_type'];
        }
        TPL::assign('change_type', $change_type);
        $supplier_jiinfo = $this->supplier_jifen_info($supplier_id);
        $jifenlist = $this->get_jifenlist($supplier_id, $change_type);
        TPL::assign('supp_info', $supplier_jiinfo['supp_info']);
        TPL::assign('zengjia', $supplier_jiinfo['count_jia']);
        TPL::assign('jianshao', $supplier_jiinfo['count_jian']);
        TPL::assign('jifen_list', $jifenlist['account']);
        TPL::assign('filter', $jifenlist['filter']);
        TPL::assign('record_count', $jifenlist['record_count']);
        TPL::assign('page_count', $jifenlist['page_count']);

        make_json_result(TPL::fetch('jifen_detail.htm'), '', array('filter' => $jifenlist['filter'], 'page_count' => $jifenlist['page_count']));
    }

    /**
     * @return 扣除入驻商积分页面
     */
    public function reduce_jifen() {
        $supplier_id = $_REQUEST['id'];
        $supplier_model = Model('supplier');
        $punish_reason_list = $supplier_model->get_supplier_points_list('id,reason,count');
        TPL::assign('ur_here', L('reduce_jifen'));
        TPL::assign('action_link', array('text' => L('supplier_list'), 'href' => 'index.php?act=supplier&op=lists&status=1'));
        TPL::assign('form_action', 'supintegral');
        TPL::assign('form_op', 'supplier_reduce');
        TPL::assign('supplier_id', $supplier_id);
        TPL::assign('punish_reason_list', $punish_reason_list);
        TPL::display('reduce_jifen.htm');
    }

    /**
     * @return 扣除入驻商积分数据入库
     */
    public function supplier_reduce() {
        $supplier_model = Model('supplier');
        $mail_model = Model('mail_templates');
        $supplier_id = $_REQUEST['id'];
        $punish_count = $_REQUEST['punish_count'];
        $jifen_id = $_REQUEST['punish_reason'];
        $param['jifen'] = $punish_count;
        $where['supplier_id'] = $supplier_id;
        $zhan = $supplier_model->update_supplier_setDec($param, $where);
        if ($zhan) {
            supplier_level($supplier_id);
            $change_time = gmtime();
            $where0['id'] = $jifen_id;
            $reason_arr = $supplier_model->select_supplier_points_info('reason', $where0);
            $reason = $reason_arr['reason'];
            $type = '0';
            $insert_arr['supplier_id'] = $supplier_id;
            $insert_arr['count'] = $punish_count;
            $insert_arr['reason'] = $reason;
            $insert_arr['type'] = $type;
            $insert_arr['change_time'] = $change_time;
            $supplier_model->insert_supplier_integral($insert_arr);
            clear_cache_files();
            /* 扣除积分短信通知入驻商 */
            $w['supplier_id'] = $supplier_id;
            $mobiles_arr = $supplier_model->select_supplier_info('contacts_phone', $w);
            $mobiles = $mobiles_arr['contacts_phone'];
            $param = array();
            $param['reduce_reson'] = $reason;
            $param['count'] = $punish_count;
            $result = send_sms_msg($mobiles, 'reduce_integral', $param);    
            admin_log($reason, 'kouchu', 'supplier_count');
            $link[0]['text'] = L('supplier_list');
            $link[0]['href'] = 'index.php?act=supplier&op=lists&status=1';
            $note = vsprintf(L('reduce_jifen_succ'), $reason);
            showMessage($note, $link);
        }
    }

    /**
     * @return 获取入驻商的积分统计
     */
    private function supplier_jifen_count($supplier_id) {
        $supplier_model = Model('supplier');
        $field = "supplier_name,jifen";
        $where['supplier_id'] = $supplier_id;
        $supp_info = $supplier_model->select_supplier_info($field, $where);
        $res = $supplier_model->get_supplier_integral_list('count,type', $where);

        foreach ($res as $key => $value) {
            if ($value['type'] == 1) {
                $zengjia += $value['count'];
            } else {
                $jianshao += $value['count'];
            }
        }
        $result['supp_info'] = $supp_info;
        $result['count_jia'] = $zengjia;
        $result['count_jian'] = $jianshao;
        return $result;
    }

    /**
     * @return 获取入驻商的积分详情列表
     */
    private function get_jifenlist($supplier_id, $change_type = '') {
        /* 检查参数 */
        $supplier_model = Model('supplier');
        $where = " WHERE supplier_id = '$supplier_id' ";
        if (in_array($change_type, array('2', '1'))) {
            if ($change_type == 1) {
                $where .= " AND type = '1' ";
            } else {
                $where .= " AND type = '0' ";
            }
        }

        /* 初始化分页参数 */
        $filter = array(
            'id' => $supplier_id,
            'change_type' => $change_type
        );

        /* 查询记录总数，计算分页数 */
        $filter['record_count'] = $supplier_model->get_supplier_integral_count($where);
        $filter = page_and_size($filter);
        /* 查询记录 */
        $sql = "SELECT * FROM " . Model()->tablename('supplier_integral') . $where .
                " ORDER BY id DESC";
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        $arr = array();
        foreach ($res as $row) {
            $row['change_time'] = local_date(C('time_format'), $row['change_time']);
            $arr[] = $row;
        }
        return array('account' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    }

}
?>

