<?php

/**
 * 淘玉php 邀请人统计管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 邀请人统计管理
 * $Id: inviter.php 17217 2018年5月7日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class inviterControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('users,calendar'); //载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 邀请人列表
     */
    public function lists() {
    	/* 权限判断 */
	    admin_priv('user_vip_manage');
    	$inviter_list = $this->get_inviter_list();
    	Tpl::assign('ur_here', '邀请人统计');
    	TPL::assign('full_page', '1');
    	TPL::assign('inviter_list', $inviter_list['list']);
    	Tpl::assign('filter',       $inviter_list['filter']);
	    Tpl::assign('record_count', $inviter_list['record_count']);
	    Tpl::assign('page_count',   $inviter_list['page_count']);
	    $sort_flag  = sort_flag($inviter_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
    	Tpl::display('inviter_list.htm');
    }

    /**
     * @return 邀请人列表排序、分页、查询
     */
    public function query() {
    	/* 权限判断 */
	    admin_priv('user_vip_manage');
		$inviter_list = $this->get_inviter_list();
    	TPL::assign('inviter_list', $inviter_list['list']);
    	Tpl::assign('filter',       $inviter_list['filter']);
	    Tpl::assign('record_count', $inviter_list['record_count']);
	    Tpl::assign('page_count',   $inviter_list['page_count']);
	    $sort_flag  = sort_flag($inviter_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('inviter_list.htm'), '',
	        array('filter' => $inviter_list['filter'], 'page_count' => $inviter_list['page_count']));
    }

    /**
     * @return 邀请人邀请会员列表
     */
    public function info() {
    	/* 权限判断 */
	    admin_priv('user_vip_manage');
	    /*邀请人信息*/
	    $parent_id = empty($_REQUEST['parent_id']) ? '' : trim($_REQUEST['parent_id']);
	    $parent_info = Model('users')->select_users_info('user_name,alias','user_id='.$parent_id);
	    $parent_name = $parent_info['alias'] ? $parent_info['alias'] : $parent_info['user_name'];
    	$inviter_list = $this->get_inviter_info_list();
    	Tpl::assign('ur_here', '['.$parent_name.']邀请的会员');
        Tpl::assign('action_link', array('href' => 'index.php?act=inviter&op=lists', 'text' => '返回邀请人统计'));
    	TPL::assign('full_page', '1');
    	TPL::assign('inviter_list', $inviter_list['list']);
    	Tpl::assign('filter',       $inviter_list['filter']);
	    Tpl::assign('record_count', $inviter_list['record_count']);
	    Tpl::assign('page_count',   $inviter_list['page_count']);
	    $sort_flag  = sort_flag($inviter_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
    	Tpl::display('inviter_info.htm');
    }

    /**
     * @return 邀请人邀请会员列表排序、分页、查询
     */
    public function info_query() {
    	/* 权限判断 */
	    admin_priv('user_vip_manage');
		$inviter_list = $this->get_inviter_info_list();
    	TPL::assign('inviter_list', $inviter_list['list']);
    	Tpl::assign('filter',       $inviter_list['filter']);
	    Tpl::assign('record_count', $inviter_list['record_count']);
	    Tpl::assign('page_count',   $inviter_list['page_count']);
	    $sort_flag  = sort_flag($inviter_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('inviter_info.htm'), '',
	        array('filter' => $inviter_list['filter'], 'page_count' => $inviter_list['page_count']));
    }


    /**
     * @return 获取邀请人列表
     * @return array  
	 */
    private function get_inviter_list() {
    	$result = get_filter();
    	$model = Model('users');
    	if ($result === false){
    		$filter = array();    		
    		$tmp = array();
    		$parent_ids = Model('users')->get_users_list('DISTINCT(parent_id)','parent_id<>0');
	    	foreach ($parent_ids as $key => $value) {
	    		$tmp[] = $value['parent_id'];
	    	}
	    	$str = implode(',',$tmp);
	    	$where = "user_id IN($str)";
	    	$wheret = "b.user_id IN($str)";
            $filter['user_name'] = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
	        if(!empty($filter['user_name'])){
	        	$where.=" AND alias like '%". $filter['user_name']."%' ";
	        	$where.=" OR  user_name like '%". $filter['user_name']."%' ";
	        	$where.=" OR  mobile_phone like '%". $filter['user_name']."%' ";
	        	$wheret .= " AND b.alias like '%". $filter['user_name']."%' ";
	        	$wheret .= " OR  b.user_name like '%". $filter['user_name']."%' ";
	        	$wheret .= " OR  b.mobile_phone like '%". $filter['user_name']."%' ";
	        }
		    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'user_count' : trim($_REQUEST['sort_by']);
	        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	 		$filter['record_count'] = $model->get_users_count($where);
		    $filter = page_and_size($filter);
		    $sql = 'SELECT a.parent_id,b.user_name,b.user_id,b.alias,b.user_money,b.taoyu_money,b.reg_time,b.mobile_phone,
		    b.validity_period ,count(*) as user_count FROM '.Model()->tablename('users').' as a left join '.
		    Model()->tablename('users').' as b on a.parent_id=b.user_id where a.parent_id > 0  AND '.$wheret.
		    'group by a.parent_id  ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
	        $filter['user_name'] = stripslashes($filter['user_name']);
	        set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        /*邀请人信息查询*/
        if(!empty($row)){
            foreach ($row as $key => $value) {
                $user_info = $model->get_users_user_level_info('users.*,user_level.level_name','parent_id='.$value['user_id']);
                $row[$key]['user_money'] = $user_info['user_money'];
                $row[$key]['taoyu_money'] = $user_info['taoyu_money'];
                $row[$key]['level_name'] = $user_info['level_name'];
                $row[$key]['phone'] = $user_info['mobile_phone'] ? $user_info['mobile_phone'] : '无';
                $row[$key]['add_time'] = local_date(C('date_format'), $user_info['reg_time']);
                $row[$key]['validity_period'] = $user_info['validity_period'] ? local_date(C('date_format'), $user_info['validity_period']):'无';            
            }
            /* 获得数据 */
            $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
            return $arr;
        }        
	}

	/**
     * @return 获取邀请人邀请会员列表
     * @return array  
	 */
    private function get_inviter_info_list() {
    	$parent_id = empty($_REQUEST['parent_id']) ? '' : trim($_REQUEST['parent_id']);
    	if(!$parent_id){
    		exit('param eror');
    	}
    	$result = get_filter();
    	$model = Model('users');
    	if ($result === false){
    		$filter = array();
    		$filter['parent_id'] = $parent_id;  		    	
	    	$where = "parent_id = ".$filter['parent_id'];
            $filter['user_name'] = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
	        if(!empty($filter['user_name'])){
	        	$where.=" AND alias like '%". $filter['user_name']."%' ";
	        	$where.=" OR  user_name like '%". $filter['user_name']."%' ";
	        	$where.=" OR  mobile_phone like '%". $filter['user_name']."%' ";	        	
	        }
		    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'user_id' : trim($_REQUEST['sort_by']);
	        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	 		$filter['record_count'] = $model->get_users_count($where);
		    $filter = page_and_size($filter);
		    $sql = 'SELECT * FROM '.Model()->tablename('users').' WHERE 1 AND '.
		    $where.' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
	        $filter['user_name'] = stripslashes($filter['user_name']);
	        set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        /*邀请人信息查询*/
        if(!empty($row)){
            foreach ($row as $key => $value) {
                $user_info = $model->get_users_user_level_info('users.*,user_level.level_name','user_id='.$value['user_id']);
                $row[$key]['user_money'] = $user_info['user_money'];
                $row[$key]['taoyu_money'] = $user_info['taoyu_money'];
                $row[$key]['level_name'] = $user_info['level_name'];
                $row[$key]['phone'] = $user_info['mobile_phone'] ? $user_info['mobile_phone'] : '无';
                $row[$key]['add_time'] = local_date(C('date_format'), $user_info['reg_time']);
                $row[$key]['validity_period'] = $user_info['validity_period'] ? local_date(C('date_format'), $user_info['validity_period']):'无';            
            }
            /* 获得数据 */
            $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
            return $arr; 
        }        
    }

}