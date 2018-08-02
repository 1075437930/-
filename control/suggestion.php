<?php

/**
 * 淘玉php 会员建议
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 会员建议
 * $Id: suggestion.php 17217 2018年5月3日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class suggestionControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('suggestion'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 会员建议列表
     */
    public function lists() {
        admin_priv('user_yijian');

        $yijian_list = $this->yijian_list();

        TPL::assign('list_all', $yijian_list['yijian_list']);

        TPL::assign('fullpage', 1);

        TPL::assign('filter', $yijian_list['filter']);

        TPL::assign('record_count', $yijian_list['record_count']);

        TPL::assign('page_count', $yijian_list['page_count']);

        TPL::display('suggestion_list.htm');
    }

    /**
     * @return 会员建议列表排序、分页、查询
     */
    public function query() {
        admin_priv('user_yijian');
        $yijian_list = $this->yijian_list();

        TPL::assign('list_all', $yijian_list['yijian_list']);

        TPL::assign('filter', $yijian_list['filter']);

        TPL::assign('record_count', $yijian_list['record_count']);

        TPL::assign('page_count', $yijian_list['page_count']);

        $sort_flag = sort_flag($yijian_list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        make_json_result(TPL::fetch('suggestion_list.htm'), '', array(
            'filter' => $yijian_list['filter'], 'page_count' => $yijian_list['page_count']
        ));
    }

    /**
     * @return 处理
     */
    public function dispose() {

        $user_model = Model('users');

        admin_priv('user_yijian');

        $link[] = array(
            'text' => L('back_list'), 'href' => 'index.php?act=suggestion&op=lists'
        );
        if (!empty($_REQUEST['id'])) {
            $yijian_id = $_REQUEST['id'];
            $w['yijian_id'] = $yijian_id;
            $area_list = $user_model->select_yijian_info('*',$w);

            if ($area_list['type'] == 0) {

                $adminname = $this->admin_info['user_name'];

                $adminnameid = $this->admin_info['user_id'];

                $huifucommot = L('accept');

                $param['type'] = 1;
                $param['adminid'] = $adminnameid;
                $param['adminname'] = $adminname;
                $param['huifucommot'] = $huifucommot;
                $where['yijian_id'] = $yijian_id;
                $useridinto = $user_model->update_suggestion($param, $where);
                if (!empty($useridinto)) {
                    showMessage(L('attradd_succed'), $link);
                } else {
                    showMessage(L('accept_error'), $link);
                }
            } else {
                showMessage(L('accept_repeat'), $link);
            }
        } else {
            showMessage(L('suggest_error'), $link);
        }
    }

    /**
     * @return 关闭
     */
    public function closed() {
        admin_priv('user_yijian');
        $link[] = array(
            'text' => L('back_list'), 'href' => 'index.php?act=suggestion&op=lists'
        );
        if (!empty($_REQUEST['id'])) {
            $yijian_id = $_REQUEST['id'];

            /* 获取管理员信息 */
            $adminname = $this->admin_info['user_name'];
            $adminnameid = $this->admin_info['user_id'];
            $huifucommot = L('close_remark');
            $param['type'] = 2;
            $param['adminid'] = $adminnameid;
            $param['adminname'] = $adminname;
            $param['huifucommot'] = $huifucommot;
            $where['yijian_id'] = $yijian_id;
            $useridinto = Model('users')->update_suggestion($param, $where);;

            if (!empty($useridinto)) {
                showMessage(L('attradd_succed'), $link);
            } else {
                showMessage(L('accept_error'), $link);
            }
        } else {
            showMessage(L('suggest_error'), $link);
        }
    }

    /**
     * @return 删除建议
     */
    public function remove() {

        admin_priv('user_yijian');
        $user_model = Model('users');
        $link[] = array(
            'text' => L('back_list'), 'href' => 'index.php?act=suggestion&op=lists'
        );
        if (!empty($_REQUEST['id'])) {

            $yijian_id = $_REQUEST['id'];
            $where['yijian_id'] = $yijian_id;
            $deladdre = $user_model->delete_suggestion($where);
            if (!empty($deladdre)) {
                showMessage(L('remove_succ'), $link);
            } else {
                showMessage(L('remove_error'), $link);
            }
        } else {
            showMessage(L('suggest_error'), $link);
        }
    }

    /*
     * @access  public
     * @return 获取会员建议列表
     */

    private function yijian_list() {

        $user_model = Model('users');
        /* 过滤条件 */
        $filter['yijian_type'] = isset($_REQUEST['yijian_type']) ? intval($_REQUEST['yijian_type']) : -1;
        $where = '';
        if ($filter['yijian_type'] != -1) {
            $where .= " AND type = '$filter[yijian_type]' ";
        }
        
        $filter['record_count'] = $user_model->get_yijian_count($where);
        /* 分页大小 */
        $filter = page_and_size($filter);
        $sql = "SELECT yj.*,u.user_name,u.alias " .
                "FROM " . Model()->tablename('yijian') . " AS yj " .
                "LEFT JOIN " . Model()->tablename('users') . " AS u ON yj.yijian_userid=u.user_id " .
                " WHERE yj.type <= 2 " . $where . " ORDER by yj.yijian_id desc ";
        $yijian_list = get_all_page($sql, $filter['page_size'], $filter['start']);

        foreach ($yijian_list AS $key => $value) {
            if ($value['type'] == 0) {
                $yijian_list[$key]['typename'] = '未处理';
            } else if ($value['type'] == 1) {
                $yijian_list[$key]['typename'] = '已采纳';
            } else {
                $yijian_list[$key]['typename'] = '已关闭';
            }
            $yijian_list[$key]['add_time'] = local_date('Y-m-d H:i', $value['add_time']);
        }
        $arr = array('yijian_list' => $yijian_list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

}
?>

