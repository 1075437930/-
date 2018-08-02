<?php

/**
 * 淘玉php 代金券管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 代金券管理
 * $Id: vouchers.php 17217 2018年5月5日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class vouchersControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('vouchers'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 拍卖活动列表
     */
    function lists() {
        admin_priv('vouchers');
        $pointlist = $this->get_vouchers_list();
        TPL::assign('full_page', 1);
        TPL::assign('action_link', array('href' => 'index.php?act=vouchers&op=add_point', 'text' => L('add_vouchers')));
        TPL::assign('ur_here', L('dc_vouchers'));
        TPL::assign('pointlist', $pointlist['pointlist']);
        TPL::assign('pointnum', $pointlist['pointnum']);
        TPL::assign('filter', $pointlist['filter']);
        TPL::assign('record_count', $pointlist['record_count']);
        TPL::assign('page_count', $pointlist['page_count']);
        $sort_flag = sort_flag($pointlist['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);
        TPL::display('diancang_point.htm');
    }

    /**
     * @return 分页、排序、查询
     */
    public function query() {
        $pointlist = $this->get_vouchers_list();
        TPL::assign('pointlist', $pointlist['pointlist']);
        TPL::assign('pointnum', $pointlist['pointnum']);
        TPL::assign('filter', $pointlist['filter']);
        TPL::assign('record_count', $pointlist['record_count']);
        TPL::assign('page_count', $pointlist['page_count']);
        $sort_flag = sort_flag($pointlist['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(TPL::fetch('diancang_point.htm'), '', array('filter' => $pointlist['filter'], 'page_count' => $pointlist['page_count']));
    }

    /**
     * @return 添加代金券页面
     */
    public function add_point() {
        admin_priv('vouchers');
        $pointinto = array();
        $point_id = 0;
        TPL::assign('ur_here', L('add_vouchers'));
        TPL::assign('action_link', array('href' => 'index.php?act=vouchers&op=lists', 'text' => L('back_vouchers_list')));
        TPL::assign('pointinto', $pointinto);
        if ($pointinto['send_start'] == 0) {
            $dc_style = $this->get_dc_style($pointinto['send_start_style'], 0);
        } else {
            $dc_style = $this->get_dc_style('', 0);
        }
        TPL::assign('stylesd', $dc_style['stylelitst']);
        TPL::assign('style_id', $dc_style['style_id']);
        TPL::assign('form_op', 'vouchers_insert');

        TPL::display('diancang_point_into.htm');
    }

    /**
     * @return 编辑代金券页面
     */
    public function edit_point() {
        admin_priv('vouchers');
        $voucher_model = Model('vouchers');
        $point_id = $_REQUEST['pointid'];
        $where = " point_id = " . $point_id;
        $pointinto = $voucher_model->select_capital_point_info('*',$where);
        TPL::assign('typesd', 1);
        TPL::assign('ur_here', L('edit_vouchers'));
        TPL::assign('action_link', array('href' => 'index.php?act=vouchers&op=lists', 'text' => L('back_vouchers_list')));
        TPL::assign('pointinto', $pointinto);
        TPL::assign('form_op', 'vouchers_update');

        if ($pointinto['send_start'] == 0) {
            $dc_style = $this->get_dc_style($pointinto['send_start_style'], 0);
        } else {
            $dc_style = $this->get_dc_style('', 0);
        }
        TPL::assign('stylesd', $dc_style['stylelitst']);
        TPL::assign('style_id', $dc_style['style_id']);
        TPL::display('diancang_point_into.htm');
    }

    /**
     * @return 添加代金券入库
     */
    public function vouchers_insert() {
        admin_priv('vouchers');
        $point_name = empty($_REQUEST['point_name']) ? '' : trim($_REQUEST['point_name']);
        $point_pic = empty($_REQUEST['point_pic']) ? '0' : floatval($_REQUEST['point_pic']);
        $buy_pic = empty($_REQUEST['buy_pic']) ? '0' : floatval($_REQUEST['buy_pic']);
        $valid_time = empty($_REQUEST['valid_time']) ? '0' : trim($_REQUEST['valid_time']);
        $types = empty($_REQUEST['types']) ? '1' : trim($_REQUEST['types']);
        $scope = empty($_REQUEST['scope']) ? '0' : trim($_REQUEST['scope']);
        $send_start = empty($_REQUEST['send_start']) ? '0' : trim($_REQUEST['send_start']);
        $point_brief = empty($_REQUEST['point_brief']) ? '' : trim($_REQUEST['point_brief']);
        $send_start_style = empty($_REQUEST['send_start_style']) ? '' : trim($_REQUEST['send_start_style']);
        if ($send_start == 1) {
            $send_start_style = '';
        }
        $admin_id = $this->admin_info['user_id'];
        $new_times = gmtime();
        $insert_arr['point_name'] = $point_name;
        $insert_arr['point_pic'] = $point_pic;
        $insert_arr['valid_time'] = $valid_time;
        $insert_arr['start'] = '1';
        $insert_arr['types'] = $types;
        $insert_arr['point_brief'] = $point_brief;
        $insert_arr['send_start'] = $send_start;
        $insert_arr['buy_pic'] = $buy_pic;
        $insert_arr['admin_id'] = $admin_id;
        $insert_arr['add_time'] = $new_times;
        $insert_arr['send_start_style'] = $send_start_style;
        $insert_arr['scope'] = $scope;
        $zhans = add_table_info('capital_point', $insert_arr);
        if (!empty($zhans)) {
            admin_log($point_name, 'add', 'capital_point');
            /* 清楚缓存文件 */
            clear_cache_files();
            $link[0]['text'] = L('back_vouchers_list');
            $link[0]['href'] = 'index.php?act=vouchers&op=lists';
            showMessage(L('add_vouchers_succ'), $link);
        } else {
            $link[0]['text'] = L('back_vouchers_list');
            $link[0]['href'] = 'index.php?act=vouchers&op=lists';
            showMessage(L('add_vouchers_fail'), $link);
        }
    }

    /**
     * @return 编辑代金券入库
     */
    public function vouchers_update() {
        admin_priv('vouchers');
        $point_name = empty($_REQUEST['point_name']) ? '' : trim($_REQUEST['point_name']);
        $point_pic = empty($_REQUEST['point_pic']) ? '0' : floatval($_REQUEST['point_pic']);
        $buy_pic = empty($_REQUEST['buy_pic']) ? '0' : floatval($_REQUEST['buy_pic']);
        $valid_time = empty($_REQUEST['valid_time']) ? '0' : trim($_REQUEST['valid_time']);
        $types = empty($_REQUEST['types']) ? '1' : trim($_REQUEST['types']);
        $scope = empty($_REQUEST['scope']) ? '0' : trim($_REQUEST['scope']);
        $send_start = empty($_REQUEST['send_start']) ? '0' : trim($_REQUEST['send_start']);
        $point_brief = empty($_REQUEST['point_brief']) ? '' : trim($_REQUEST['point_brief']);
        $send_start_style = empty($_REQUEST['send_start_style']) ? '' : trim($_REQUEST['send_start_style']);
        if ($send_start == 1) {
            $send_start_style = '';
        }
        $admin_id = $this->admin_info['user_id'];
        $new_times = gmtime();
        $update_arr['point_name'] = $point_name;
        $update_arr['point_pic'] = $point_pic;
        $update_arr['valid_time'] = $valid_time;
        $update_arr['types'] = $types;
        $update_arr['point_brief'] = $point_brief;
        $update_arr['send_start'] = $send_start;
        $update_arr['buy_pic'] = $buy_pic;
        $update_arr['add_time'] = $new_times;
        $update_arr['send_start_style'] = $send_start_style;
        $update_arr['scope'] = $scope;
        $point_id = trim($_REQUEST['pointid']);
        $where['point_id'] = $point_id;
        update_table_info('capital_point', $update_arr, $where);
        admin_log($point_name, 'edit', 'capital_point');
        /* 清楚缓存文件 */
        clear_cache_files();

        $link[0]['text'] = L('back_vouchers_list');
        $link[0]['href'] = 'index.php?act=vouchers&op=lists';
        showMessage(L('edit_vouchers_succ'), $link);
    }

    /**
     * @return 发送代金券页面
     */
    
    public function sent_point() {//发送代金券页面
        admin_priv('vouchers');
        TPL::assign('action_link', array('href' => 'vouchers_manage.php?act=list', 'text' => L('back_vouchers_list')));
        TPL::assign('ur_here', L('send_vouchers'));
        $dcrank = $this->get_dcrankselce();
        $point_id = intval($_REQUEST['pointid']);
        $where = " point_id = " . $point_id;
        $pointinto = Model('vouchers')->select_capital_point_info('*',$where);
        TPL::assign('pointinto', $pointinto);
        TPL::assign('dcrank', $dcrank['ranklist']);
        TPL::assign('dcrankid', $dcrank['rankid']);
        TPL::assign('form_op', 'insert_sent_point');
        TPL::display('diancang_point_sent.htm');
    }
/**
 * @return 发送代金券数据库操作
 */
    public function insert_sent_point() {
        admin_priv('vouchers');
        $point_id = empty($_REQUEST['pointid']) ? '0' : trim($_REQUEST['pointid']);
        $sent_types = empty($_REQUEST['sent_types']) ? '0' : trim($_REQUEST['sent_types']);
        $dcuser_id = empty($_REQUEST['dcuser_id']) ? '0' : trim($_REQUEST['dcuser_id']);
        $dcrank = $_REQUEST['dcrank'];
        
        $valid_time = empty($_REQUEST['valid_time']) ? '1' : trim($_REQUEST['valid_time']);
        $new_times = gmtime();
        $shijian = 60 * 60 * 24 * $valid_time;
        $en_times = $new_times + $shijian;
        if ($sent_types == 1) {//发送类型 0 单一用户发送
            $inert['capital_userid'] = $dcuser_id;
            $inert['point_id'] = $point_id;
            $inert['stater_time'] = $new_times;
            $inert['end_time'] = $en_times;
            $zhans = Model('diancang')->insert_capital_userpoint($inert);
            if (!empty($zhans)) {
                $w['point_id'] = $point_id;
                Model('diancang')->update_capital_point_setInc('send',$w);
                admin_log('发送给' . $dcuser_id . '成功', 'add', 'capital_point');
                $link[0]['text'] = L('back_vouchers_list');
                $link[0]['href'] = 'index.php?act=vouchers&op=lists';
                showMessage(L('send_vouchers_succ'), $link);
            } else {
                $link[0]['text'] = L('back_vouchers_list');
                $link[0]['href'] = 'index.php?act=vouchers&op=lists';
                showMessage(L('no_dc_user'), $link);
            }
        } else if ($sent_types == 2) {//发送类型 0 单一级别发送
            if(!empty($dcrank)){
                if($dcrank == 2){
                    $where1 =" capital_user.shows = 0 AND users.level_id = 10 ";
                } else if($dcrank == 3) {
                    $where1 =" capital_user.shows = 0 AND users.level_id < 10 AND users.level_id >= 1 ";
                }else if($dcrank == 1){
                    $where1 = " capital_user.shows = 0 ";
                }
                $dcuserlist = Model('diancang')->get_users_capital_user_list('capital_user.capital_userid',$where1);
                if (!empty($dcuserlist)) {
                    $nums = count($dcuserlist);
                    $dcusernum = $nums - 1;
                    foreach ($dcuserlist as $key => $value) {
                        if ($key == $dcusernum) {
                            $instinto .= ' ("' . $value['capital_userid'] . '","' . $point_id . '","' . $new_times . '","' . $en_times . '");';
                        } else {
                            $instinto .= ' ("' . $value['capital_userid'] . '","' . $point_id . '","' . $new_times . '","' . $en_times . '"),';
                        }
                    }
                    $instinto = Model('diancang')->insert_capital_userpoint_query($instinto);
                    if (!empty($instinto)) {
                        $wh['point_id'] = $point_id;
                        $par['send'] = $nums;
                        Model('diancang')->update_capital_point_setInc($par,$wh);
                        admin_log('发送' . $nums . '个', 'add', 'capital_point');
                        $link[0]['text'] = L('back_vouchers_list');
                        $link[0]['href'] = 'index.php?act=vouchers&op=lists';
                        showMessage(L('send_vouchers_succ') . $nums . '个', $link);
                    } else {
                        $link[0]['text'] = L('back_vouchers_list');
                        $link[0]['href'] = 'index.php?act=vouchers&op=lists';
                        showMessage(L('no_dc_user').'2', $link);
                    }
                } else {
                    $link[0]['text'] = L('back_vouchers_list');
                    $link[0]['href'] = 'index.php?act=vouchers&op=lists';
                    showMessage(L('no_dc_user'), $link);
                }
            }else{
                $link[0]['text'] = L('back_vouchers_list');
                $link[0]['href'] = 'index.php?act=vouchers&op=lists';
                showMessage('请选择用户级别', $link);
            }
        }
    }

    /**
     * @return 单人发送代金券时搜索用户
     */
    public function search_user() {//单人发送代金券时搜索用户
        admin_priv('vouchers');
        $arr['userinto'] = $this->get_userlist($_REQUEST['keyword']);
        make_json_result($arr);
    }

    /**
     * 自动拼接用户等级列表用户open使用
     * @return  array
     */
    function get_dcrankselce() {
        $rankinto = $this->get_dcrank();
        $ranklist = '';
        $rankid = '';
        foreach ($rankinto['ranklist'] as $key => $value) {
            if ($key == 0) {
                $rankid = $value['rank_id'];
            }
            $ranklist[$value['rank_id']] = htmlspecialchars($value['rank_name']);
        }
        return array('ranklist' => $ranklist, 'rankid' => $rankid);
    }

    /**
     * 获取典藏用户等级列表
     * @return  array
     */
    function get_dcrank() {
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'sort_rank' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);
        $filter['record_count'] = Model('diancang')->get_capital_rank_count();
        $filter = page_and_size($filter);
        $sql = 'SELECT * FROM ' . Model()->tablename('capital_rank') .
                " ORDER by $filter[sort_by] $filter[sort_order] ";
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        $arr = array('ranklist' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * 获取代金卷列表
     * @return  array
     */
    function get_vouchers_list() {
        $voucher_model = Model('vouchers');
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'point_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['record_count'] = $voucher_model->get_capital_point_count('');
        $filter = page_and_size($filter);
        $comment = ''; //存储所有月份为字符串逗号分割
        $sql = 'SELECT * FROM ' . Model()->tablename('capital_point') .
                " ORDER by $filter[sort_by] $filter[sort_order]";
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($row as $key => $value) {
            if ($value['types'] == 1) {
                $row[$key]['type_name'] = '单卷';
            } else {
                $row[$key]['type_name'] = '同卷';
            }

            if ($value['send_start'] == 0) {
                $row[$key]['send_name'] = '自动发放';
            } else {
                $row[$key]['send_name'] = '手动发放';
            }

            if ($value['scope'] == 0) {
                $row[$key]['scope_name'] = '典藏产品';
            } else if ($value['scope'] == 1) {
                $row[$key]['scope_name'] = '普通产品';
            } else {
                $row[$key]['scope_name'] = '所有产品';
            }
        }
        $post = $this->get_point_nums();
        $arr = array('pointlist' => $row, 'pointnum' => $post, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * 获取代金卷统计计数
     * @return  array
     */
    function get_point_nums() {
        $voucher_model = Model('vouchers');
        $zongrow = $voucher_model->get_capital_point_list();
        $post['zong_send'] = 0; //总发放个数
        $post['zong_make'] = 0; //总使用个数
        $post['zong_pic'] = 0; //使用后产生总金额
        $post['zong_makepic'] = 0; //使用后产生总金额
        $post['zong_sendpic'] = 0; //未使用金额
        $post['zong_nums'] = count($zongrow); //总个数
        foreach ($zongrow as $key => $value) {
            $post['zong_send'] = $post['zong_send'] + $value['send'];
            $sendpic = $value['send'] * $value['point_pic'];
            $post['zong_sendpic'] = $post['zong_sendpic'] + $sendpic;
            $post['zong_make'] = $post['zong_make'] + $value['make'];
            $makepic = $value['make'] * $value['point_pic'];
            $post['zong_makepic'] = $post['zong_makepic'] + $makepic;
            $pic = $value['make'] * $value['buy_pic'];
            $post['zong_pic'] = $post['zong_pic'] + $pic;
        }
        return $post;
    }

    /**
     * 获取代金卷或者声明样式 0代金卷 1声明
     * @access  public
     * @return  array
     */
    function get_dc_style($send_start_style = '', $types = 0) {
        $stylelist = '';
        $vouchers_model = Model('vouchers');
        $w['stats'] = $types;
        $row = $vouchers_model->get_capital_canshu_list('*',$w);
        if (!empty($send_start_style)) {
             $wh['types'] = $send_start_style;
            $types = $vouchers_model->select_capital_canshu_info('*',$wh);
            if (empty($types)) {
                $types = 0;
            }
        } else {
            $types = 0;
        }
        foreach ($row as $k => $v) {
            $stylelist[$v['types']] = htmlspecialchars($v['can_name']);
        }
        return array('stylelitst' => $stylelist, 'style_id' => $types);
    }

    /**
     * 根据关键词获取用户列表选择用户
     * @access  public
     * @return  array
     */
    function get_userlist($keywords) {
        $keyword = isset($keywords) && trim($keywords) != '' ? trim($keywords) : '';
        if (!empty($keyword)) {
            $where = "  (users.alias like '%" . $keyword . "%' OR users.mobile_phone like '%" . $keyword . "%') ";
        } else {
            $where = "  users.fenxiao_id=1 ";
        }
        $where.=" AND capital_user.capital_userid > 0";
        $field = 'capital_user.capital_userid,users.alias, users.user_name,users.user_id';
        $row = Model('diancang')->get_users_capital_user_list($field,$where,'',20);
        return $row;
    }

}
?>

