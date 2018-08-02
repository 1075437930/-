<?php

/**
 * 淘玉php 管理员日志管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 管理员日志管理
 * $Id: adminlogs.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class adminlogsControl extends BaseControl {
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('adminlogs,calendar');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 管理员操作日志列表函数 
     */
    public function lists() {
        
        /* 权限的判断 */
        admin_priv('logs_manage');
        $user_id   = !empty($_REQUEST['id'])       ? intval($_REQUEST['id']) : 0;
        $admin_ip  = !empty($_REQUEST['ip'])       ? $_REQUEST['ip']         : '';
        $log_date  = !empty($_REQUEST['log_date']) ? $_REQUEST['log_date']   : '';
        /* 查询管理员列表 */
        $admin_list = array();
        $adminmodel = Model('admin');
        $res = $adminmodel->get_admin_list('user_id,user_name', '');
        foreach ($res as $key => $values ){
            $admin_list[$values['user_id']] = $values['user_name'];
        }
        /* 查询IP地址列表 */
        $ip_list = array();
        $ipres = $adminmodel->get_admin_log_list(' DISTINCT ip_address', '');
        foreach ($ipres as $key => $values ){
            $ip_list[$values['ip_address']] = $values['ip_address'];
        }
       
        Tpl::assign('ur_here',   L('admin_logs'));
        Tpl::assign('admin_list',   $admin_list);
        Tpl::assign('ip_list',   $ip_list);
        Tpl::assign('full_page', 1);
        $log_list = $this->get_admin_logs();
       
        Tpl::assign('log_list',        $log_list['list']);
        Tpl::assign('filter',          $log_list['filter']);
        Tpl::assign('record_count',    $log_list['record_count']);
        Tpl::assign('page_count',      $log_list['page_count']);
        $sort_flag  = sort_flag($log_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::display('admin_logs.htm');
    }
    
    /**
     * @return 管理员操作日志排序、分页、查询
     */
    public function logs_query() {
       $log_list = $this->get_admin_logs();
       Tpl::assign('log_list',        $log_list['list']);
       Tpl::assign('filter',          $log_list['filter']);
       Tpl::assign('record_count',    $log_list['record_count']);
       Tpl::assign('page_count',      $log_list['page_count']);
       $sort_flag  = sort_flag($log_list['filter']);
       Tpl::assign($sort_flag['tag'], $sort_flag['img']);
       make_json_result(Tpl::fetch('admin_logs.htm'), '',
        array('filter' => $log_list['filter'], 'page_count' => $log_list['page_count']));
    }
    
   /**
     * @return 批量删除日志记录
     */
    public function batch_drop() {
        admin_priv('logs_drop');
        $drop_type_date = isset($_POST['drop_type_date']) ? $_POST['drop_type_date'] : '';
        /* 按日期删除日志 */
        $adminmodel = Model('admin');
        if ($drop_type_date){
            if ($_POST['log_date'] == '0'){
                $link[] = array('text' => L('back_list'), 'href' => 'index.php?act=adminlogs&op=lists');
                showMessage(L('js_languages.select_date_value'), $link);
            }elseif ($_POST['log_date'] > '0'){
                $where = " ";
                switch ($_POST['log_date']){
                    case '1':
                        $a_week = gmtime()-(3600 * 24 * 7);
                        $where .= " log_time <= '".$a_week."'";
                        break;
                    case '2':
                        $a_month = gmtime()-(3600 * 24 * 30);
                        $where .= " log_time <= '".$a_month."'";
                        break;
                    case '3':
                        $three_month = gmtime()-(3600 * 24 * 90);
                        $where .= " log_time <= '".$three_month."'";
                        break;
                    case '4':
                        $half_year = gmtime()-(3600 * 24 * 180);
                        $where .= " log_time <= '".$half_year."'";
                        break;
                    case '5':
                        $a_year = gmtime()-(3600 * 24 * 365);
                        $where .= " log_time <= '".$a_year."'";
                        break;
                }
                $res = $adminmodel->delete_admin_log($where);
                if ($res){
                    admin_log('','remove', 'adminlog');
                    $link[] = array('text' => L('back_list'), 'href' => 'index.php?act=adminlogs&op=lists');
                    showMessage(L('drop_sueeccud'), $link);
                }
            }
        }else{/* 如果不是按日期来删除, 就按ID删除日志 */
            $count = 0;
            foreach ($_POST['checkboxes'] AS $key => $id){
                $wheres = " log_id = '$id'";
                $result = $adminmodel->delete_admin_log($wheres);
                $count++;
            }
            if ($result){
                admin_log('', 'remove', 'adminlog');
                $link[] = array('text' => L('back_list'), 'href' => 'index.php?act=adminlogs&op=lists');
                showMessage(sprintf(L('batch_drop_success'), $count), $link);
            }
        }
    }
    
    /* 获取管理员操作记录 */
   public function get_admin_logs(){

        $result = get_filter();
       
        if ($result === false){
            $filter['user_id'] = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id']         : '';
            $filter['add_time1']    = empty($_REQUEST['add_time1']) ? '' : (strpos($_REQUEST['add_time1'], '-') > 0 ?  local_strtotime($_REQUEST['add_time1']) : $_REQUEST['add_time1']);
            $filter['add_time2']    = empty($_REQUEST['add_time2']) ? '' : (strpos($_REQUEST['add_time2'], '-') > 0 ?  local_strtotime($_REQUEST['add_time2']) : $_REQUEST['add_time2']);
            $filter['ip'] = !empty($_REQUEST['ip']) ? $_REQUEST['ip']         : '';
            $filter['sort_by']      = empty($_REQUEST['sort_by']) ? 'admin_log.log_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order']   = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $adminmodel = Model('admin');
            //查询条件
            $where = " WHERE 1 ";

            if (!empty($filter['user_id']))
            {
                    $where .= " AND admin_log.user_id = '$filter[user_id]' ";
            }		
            if ($filter['add_time1'])
            {
                    $where .= " AND admin_log.log_time>=  '" . $filter['add_time1']."' ";
            }
            if ($filter['add_time2'])
            {
                    $where .= " AND admin_log.log_time<=  '" . $filter['add_time2']."' ";
            }
            if (!empty($filter['ip']))
            {
                    $where .= " AND admin_log.ip_address = '$filter[ip]' ";
            }

            /* 获得总记录数据 */
            $filter['record_count'] = $adminmodel->get_admin_log_count($where);
            $filter = page_and_size($filter);
            set_filter($filter, $sql);
            /* 获取管理员日志记录 */
            $list = array();
            $sql  = 'SELECT admin_log.*, u.user_name FROM ' .Model()->tablename('admin_log'). ' AS admin_log '.
                            'LEFT JOIN ' .Model()->tablename('admin_user'). ' AS u ON u.user_id = admin_log.user_id '.
                            $where .' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
       
        }else{       
            $sql    = $result['sql'];
            $filter = $result['filter'];		
	}
       //所有页面分页都按照这个来共有函数来get_all_page 特殊除外
        $res  = get_all_page($sql, $filter['page_size'], $filter['start']);
       
        foreach ($res as $key => $value) {
            $res[$key]['log_time'] = local_date(C('time_format'), $value['log_time']);
            $list[] = $res[$key];
        }
 
        return array('list' => $list, 'filter' => $filter, 'page_count' =>  $filter['page_count'], 'record_count' => $filter['record_count']);
    }

}
?>

