<?php

/**
 * 淘玉php 后台资金统计类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台资金统计类
 * $Id: customer.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class customerControl extends BaseControl {

	/**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('customer,calendar,param');
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");       
    }

	/**
     * @return 客服列表
     */
    public function lists() {
    	admin_priv('customer');
    	/* 模板赋值 */
		Tpl::assign('full_page', 1);
		Tpl::assign('ur_here', L('customer_list'));
		Tpl::assign('action_link', array(
			'href' => 'index.php?act=customer&op=add', 'text' => L('add_customer')
		));			
		$result = $this->get_customer_list();			
		Tpl::assign('customer_list', $result['item']);
		Tpl::assign('filter', $result['filter']);
		Tpl::assign('record_count', $result['record_count']);
		Tpl::assign('page_count', $result['page_count']);
		
		$sort_flag = sort_flag($result['filter']);
		Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		
		/* 显示客服列表页面 */
		Tpl::display('customer_list.htm');
    }

    /**
     * @return 客服列表排序、分页、查询
     */
    public function query() {
    	admin_priv('customer');
		$list = $this->get_customer_list();	
		Tpl::assign('customer_list', $list['item']);
		Tpl::assign('filter', $list['filter']);
		Tpl::assign('record_count', $list['record_count']);
		Tpl::assign('page_count', $list['page_count']);		
		$sort_flag = sort_flag($list['filter']);
		Tpl::assign($sort_flag['tag'], $sort_flag['img']);		
		make_json_result(Tpl::fetch('customer_list.htm'), '', array(
			'filter' => $list['filter'], 'page_count' => $list['page_count']
		));
    }

    /**
     * @return 添加客服页面
     */
    public function add() {
    	admin_priv('chat_add');
    	/*取得所有管理员*/
    	/* 初始化/取得客服信息 */
		$customer = array(
			'cus_id' => 0, 'supp_id' => - 1, 'cus_type' => 0, 'cus_enable' => 1
		);
		/* 模板赋值 */
		Tpl::assign('customer', $customer);
		Tpl::assign('form_op', 'insert');
		Tpl::assign('ur_here', L('customer_list'));
		/* 显示模板 */		
		Tpl::display('customer_info.htm');
    }

    /**
     * @return 添加客服数据入库
     */
    public function insert() {
		admin_priv('chat_add');
		$user_id = intval($_POST['user_id']);
		/* 取得客服id */
		$cus_id = intval($_POST['cus_id']);
		$customer['supp_id'] = -1;
		$customer['user_id'] = $_POST['user_id'];
		$customer['cus_name'] = $_POST['cus_name'];
		$customer['cus_type'] = $_POST['cus_type'];
		$customer['cus_enable'] = $_POST['cus_enable'];
		$customer['cus_desc'] = $_POST['cus_desc'];
		$userinto = Model('users')->select_users_info('user_name,alias',"user_id = $user_id");
		$customer['of_username'] = $userinto['alias'] ? $userinto['alias'] : $userinto['user_name'];
		/*判断客服名称是否为空*/
		if(empty($customer['cus_name'])) {
			showMessage(L('error_cus_name_empty'));
		}
		/*判断用户名称是否为空*/
		if(empty($customer['of_username'])) {
			showMessage(L('error_of_username_empty'));
		}				
		/*检查管理员账户是否存在*/
		if($this->check_user_id_exist($user_id, $cus_id)) {
			showMessage(L('error_user_id_exist'));
		}
		$customer['add_time'] = gmtime();		
		Model('customer')->insert_chat_customer($customer);		
		admin_log(addslashes($customer['cus_name']), 'add', 'chat_customer');
		
		/* 提示信息 */
		$links = array(
			array('href' => 'index.php?act=customer&op=lists', 'text' => L('back_list')), 
			array('href' => 'index.php?act=customer&op=add', 'text' => L('continue_add'))
		);
		showMessage(L('add_success'), $links);
    }

    /**
     * @return 编辑客服页面
     */
    public function edit() {
    	/* 初始化/取得客服信息 */
		$cus_id = intval($_REQUEST['id']);
		if($cus_id <= 0) {
			die('invalid param');
		}
		$customer = Model('customer')->select_chat_customer_info('*','cus_id='.$cus_id);
		/* 格式化时间 */
		$customer['formated_add_time'] = local_date('Y-m-d H:i', $customer['add_time']);
		Tpl::assign('customer', $customer);		
		Tpl::assign('ur_here', L('add_customer'));
		Tpl::assign('form_op', 'update');
		/* 显示模板 */		
		Tpl::display('customer_info.htm');
    }

    /**
     * @return 编辑客服入库
     */
    public function update() {
    	admin_priv('customer');
		$user_id = intval($_POST['user_id']);
		/* 取得客服id */
		$cus_id = intval($_POST['cus_id']);
		$customer['supp_id'] = -1;
		$customer['user_id'] = $_POST['user_id'];
		$customer['cus_name'] = $_POST['cus_name'];
		$customer['cus_type'] = $_POST['cus_type'];
		$customer['cus_enable'] = $_POST['cus_enable'];
		$customer['cus_desc'] = $_POST['cus_desc'];
		$userinto = Model('users')->select_users_info('user_name,alias',"user_id = $user_id");
		$customer['of_username'] = $userinto['alias'] ? $userinto['alias'] : $userinto['user_name'];
		/*判断客服名称是否为空*/
		if(empty($customer['cus_name'])) {
			showMessage(L('error_cus_name_empty'));
		}
		/*判断用户名称是否为空*/
		if(empty($customer['of_username'])) {
			showMessage(L('error_of_username_empty'));
		}
		Model('customer')->update_chat_customer($customer,'cus_id='.$cus_id);		
		admin_log(addslashes($customer['cus_name']), 'edit', 'chat_customer');
		
		/* 提示信息 */
		$links = array(
			array('href' => 'index.php?act=customer&op=lists', 'text' => L('back_list')), 
		);
		showMessage(L('edit_success'), $links);	
    }

    /**
     * @return 删除客服
     */
    public function remove() {
    	check_authz_json('chat_del');
    	$id = $_REQUEST['id'];	
		$count = Model('customer')->delete_chat_customer(" cus_id = '" . $id . "'");		
		/* 提示信息 */
		$customer = Model('customer')->select_chat_customer_info('cus_name'," cus_id = '" . $id . "'");
		admin_log(addslashes($customer['cus_name']), 'add', 'chat_customer');
		$url = 'index.php?act=customer&op=query';
	    ecs_header("Location: $url\n");
	    exit;		
    }

    /**
     * @return 批量删除客服
     */
    public function batch_drop() {
    	admin_priv('chat_del');
    	/* 提示信息 */
		$links = array(
			array('href' => 'index.php?act=customer&op=lists', 'text' => L('back_list')), 
		);
		$ids = $_REQUEST['checkboxes'];
	    if (empty($ids) || count($ids) == 0) {
	        showMessage(L('remove_fail'), $link);
	    }
	    $ids = implode(',', $ids);
	    if (Model('customer')->delete_chat_customer(" cus_id in (" . $ids . ")")) {
	    	showMessage(L('remove_success'), $link);
	    } else {
	        showMessage(L('remove_fail'), $link);
	    }
    }

    /**
     * @return 搜索用户
     */
    public function search_user() {
    	/*添加修改客服时搜索用户*/
        admin_priv('chat_add');
        $arr['userinto'] = $this->get_user_list($_REQUEST['keyword']);
        make_json_result($arr);
    }

    /**
     * @return 根据关键词获取用户列表选择用户
     * @return array
     */
    private function get_user_list($keywords) {
        $keyword = isset($keywords) && trim($keywords) != '' ? trim($keywords) : '';
        if (!empty($keyword)) {
            $where = "  (users.alias like '%" . $keyword . "%' OR users.mobile_phone like '%" . $keyword . "%') ";
        } else {
            $where = "";
        }
        $where = "  (alias like '%" . $keyword . "%' OR mobile_phone like '%" . $keyword . "%') ";
        $field = 'alias, user_name,user_id';
        $row = Model('users')->get_users_list($field,$where,'',20);
        return $row;
    }

    /**
     * @return 获取客服列表
     */
    private function get_customer_list() {
    	$customer_model = Model('customer');
    	$result = get_filter();
	    if ($result === false) {
	        /* 过滤条件 */
	        $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
	        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
	            $filter['keyword'] = json_str_iconv($filter['keyword']);
	        }
	        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'cus_id' : trim($_REQUEST['sort_by']);
	        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	        $ext = " AND cus_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
	        $ext .= " OR of_username LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
	        $where = (!empty($filter['keyword'])) ? $ext: '';

	        $filter['record_count'] = $customer_model->get_chat_customer_count(" AND supp_id = -1 $where");

	        /* 分页大小 */
	        $filter = page_and_size($filter);

	        /* 查询 */
	        $sql = "SELECT a.*, b.user_name " . "FROM " . Model()->tablename('chat_customer') . " AS a LEFT JOIN " . Model()->tablename('admin_user') . " AS b ON (a.user_id = b.user_id) WHERE 1=1 AND supp_id = -1 $where " . " ORDER BY $filter[sort_by] $filter[sort_order] " ;

	        $filter['keyword'] = stripslashes($filter['keyword']);
	        set_filter($filter, $sql);
	    } else {
	        $sql = $result['sql'];
	        $filter = $result['filter'];
	    }
	    $list = get_all_page($sql, $filter['page_size'], $filter['start']);
	    if(!empty($list)){
        	foreach ($list as & $item) {
		        $item['formated_add_time'] = local_date('Y-m-d H:m', $item['add_time']);
		    }
        }	    
	    unset($item);

	    $arr = array(
	        'item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']
	    );

	    return $arr;
    }

    /**
	 * @return 检查user_id是否已经绑定了客服
	 * @param  int $user_id
	 * @return true:存在 false:不存在
	 *
	 */
	private function check_user_id_exist($user_id, $cus_id = null) {
	    if (isset($cus_id)) {
	        $where = " AND cus_id != '$cus_id'";
	    }
	    $count = Model('customer')->get_chat_customer_count("user_id = '$user_id' $where");
	    if ($count > 0) {
	        return true;
	    } else {
	        return false;
	    }
	}

}