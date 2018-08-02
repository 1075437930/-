<?php

/**
 * 分销管理功能
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萤火虫 $
 * $Id: fxusers.php  2018-04-07   萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class fxusersControl extends BaseControl{
    public function __construct() {
        parent::__construct();
        Language::read('fxusers');
        $lang = Language::getLangContent();
        Tpl::assign('lang', $lang);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * 分销信息的列表
     */
    public function lists(){
        /* 检查权限 */
        admin_priv('fx_set');
        //查询分销返点信息
        $user_level = $this->get_users_level_list();
        Tpl::assign('ur_here', L('fx_set'));
        Tpl::assign('full_page', 1);
        Tpl::assign('level_list', $user_level['level_list']);
        Tpl::assign('filter', $user_level['filter']);
        Tpl::assign('record_count', $user_level['record_count']);
        Tpl::assign('page_count', $user_level['page_count']);
        Tpl::assign('action_link', array('text' => L('add_fx_level'), 'href' => 'index.php?act=fxusers&op=add'));
        Tpl::display('fxusers_list.htm');
    }

    public function list_query(){
        /* 检查权限 */
        admin_priv('fx_set');
        $user_level = $this->get_users_level_list();
        Tpl::assign('level_list', $user_level['level_list']);
        Tpl::assign('filter', $user_level['filter']);
        Tpl::assign('record_count', $user_level['record_count']);
        Tpl::assign('page_count', $user_level['page_count']);
        make_json_result(Tpl::fetch('fxusers_list.htm'), '',
            array('filter' => $user_level['filter'], 'page_count' => $user_level['page_count']));
    }

    /**
     * 调取添加分销信息的页面
     */
    public function add(){
        /* 检查权限 */
        admin_priv('fx_set');
        Tpl::assign('type','add');
        Tpl::display('fxusers_info.htm');
    }

    /**
     * 调取编辑分销的页面
     */
    public function edit(){
        /* 检查权限 */
        admin_priv('fx_set');
        $level_id = $_REQUEST['id'];
        $link = array('href'=>'index.php?act=fxusers&op=lists', 'text' => L('back_fxusers_list'));
        if(empty($level_id)){
            showMessage(L('cannot_found_param'),$link);
        }else{
            $obj = Model('user_level');
            $result = $obj->select_user_level_info('*','level_id='.$level_id);
            Tpl::assign('type','edit');
            Tpl::assign('result',$result);
            Tpl::display('fxusers_info.htm');
        }
    }

    /**
     * 处理添加分销信息和修改分销信息的方法
     */
    public function add_query(){
        /* 检查权限 */
        admin_priv('fx_set');
        $model = Model('user_level');
        $type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
        $level['level_name'] = trim($_REQUEST['level_name']);
        $level['level_bili'] = trim($_REQUEST['level_bili']);
        $level['remark'] = trim($_REQUEST['remark']);
        $level['yaoqin_bili'] = $_REQUEST['yaoqing_bili'];
        $level['beizhu'] = trim($_REQUEST['beizhu']);
        $level['level_name'] = trim($_REQUEST['level_name']);
        $link = array('href'=>'index.php?act=fxusers&op=lists', 'text' => L('back_fxusers_list'));
        if($type == 'add'){
            //添加分销信息
            $res = $model->insert_user_level($level);
            if($res){
                showMessage(L('add_success'),$link);
            }else{
                showMessage(L('add_fail'),$link);
            }
        }else if($type == 'edit'){
            //修改分销信息
            $where['level_id']= $_REQUEST['level_id'];
            if(empty($where['level_id'])){
                showMessage(L('cannot_found_param'),$link);
            }else{
                $res = $model->update_user_level($level,$where);
                if($res){
                    showMessage(L('edit_success'),$link);
                }else{
                    showMessage(L('edit_fail'),$link);
                }
            }
        }
    }

    public function remove(){
        /* 检查权限 */
        check_authz_json('fx_set');
        $where['level_id'] =  $_REQUEST['id'];
        $res = Model('user_level')->delete_user_level($where);
        $url = 'index.php?act=fxusers&op=list_query';
        ecs_header("Location: $url\n");
        exit;
    }

    /**
     * @return array获取分销信息的列表
     */
    public function get_users_level_list(){
        /* 检查权限 */
        admin_priv('fx_set');
        $result = get_filter();
        if ($result === false) {
            /* 分页大小 */
            $filter = array();
            /* 记录总数以及页数 */
            $obj = Model('user_level');
            $filter['record_count'] = $obj->get_user_level_count('1');
            $filter = page_and_size($filter);
            /* 查询记录 */
            $sql = "SELECT * " ." FROM " . Model()->tablename('user_level');
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $all = get_all_page($sql, $filter['page_size'], $filter['start']);
        return array('level_list' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    }

}
