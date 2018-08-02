<?php

/**
 * 淘玉php 后台第三方客服管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台第三方客服管理
 * $Id: customer.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class third_customerControl extends BaseControl {

	/**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('third_customer,calendar,param');
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");       
    }

    /**
     * @return 第三方客服列表
     */
    public function lists() {
    	admin_priv('third_customer');
    	/* 模板赋值 */
		Tpl::assign('full_page', 1);
		Tpl::assign('ur_here', L('third_customer'));
		Tpl::assign('action_link', array(
			'href' => 'index.php?act=third_customer&op=add', 'text' => L('add_third_customer')
		));			
		$result = $this->get_third_customer_list();			
		Tpl::assign('third_customer_list', $result['item']);
		Tpl::assign('filter', $result['filter']);
		Tpl::assign('record_count', $result['record_count']);
		Tpl::assign('page_count', $result['page_count']);
		
		$sort_flag = sort_flag($result['filter']);
		Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		
		/* 显示客服列表页面 */
		Tpl::display('third_customer_list.htm');
    }

    /**
     * @return 第三方客服列表排序、分页、查询
     */
    public function query() {
    	admin_priv('third_customer');
		$list = $this->get_third_customer_list();	
		Tpl::assign('third_customer_list', $list['item']);
		Tpl::assign('filter', $list['filter']);
		Tpl::assign('record_count', $list['record_count']);
		Tpl::assign('page_count', $list['page_count']);		
		$sort_flag = sort_flag($list['filter']);
		Tpl::assign($sort_flag['tag'], $sort_flag['img']);		
		make_json_result(Tpl::fetch('third_customer_list.htm'), '', array(
			'filter' => $list['filter'], 'page_count' => $list['page_count']
		));
    }

    /**
     * @return 是否切换主客服
     */
    public function toggle_master() {
    	check_authz_json('third_customer');
	    $cus_id = intval($_POST['id']);
	    $is_master = intval($_POST['val']);
	    $data = array('is_master'=>$is_master);
	    Model('third_customer')->update_third_customer($data,'cus_id='.$cus_id);
	    clear_cache_files();
	    make_json_result($is_master);
    }

    /**
     * @return 删除第三方客服
     */
    public function remove() {
    	check_authz_json('third_customer');
	    $id = intval($_GET['id']);
	    $name = Model('third_customer')->select_third_customer_info('cus_name','cus_id='.$id)['cus_name'];
	    $res = Model('third_customer')->delete_third_customer('cus_id='.$id);
	    if ($res) {
	    	clear_cache_files();
	        admin_log(addslashes($name), 'remove', 'third_customer');
	    }
	    $url = 'index.php?act=third_customer&op=query&';
	    ecs_header("Location: $url\n");
	    exit;
	}

	/**
     * @return 批量删除第三方客服
     */
    public function batch_drop() {
    	check_authz_json('third_customer');
    	if (isset($_POST['checkboxes'])) {
	        $count = 0;
	        foreach ($_POST['checkboxes'] AS $key => $id) {
	            Model('third_customer')->delete_third_customer("cus_id = $id");
	            $count++;
	        }
	        admin_log($count, 'remove', 'third_customer');
	        clear_cache_files();
	        /* 提示信息 */
	        $link[] = array('text' => L('back_third_customer_list'), 'href' => 'index.php?act=third_customer&op=lists');
	        showMessage(sprintf(L('drop_success'), $count),$link);
	    } else {
	        $link[] = array('text' => L('back_third_customer_list'), 'href' => 'index.php?act=third_customer&op=lists');
	        showMessage(L('no_select_tag'), $link);
	    }

    }

    /**
     * @return 添加第三方客服页面
     */
    public function add() {
    	admin_priv('third_customer');
    	/* 初始化/取得客服信息 */
	    $third_customer = array('cus_id'=>0,'cus_type'=>0,'is_master'=>0);
	    $link = array('href' => 'index.php?act=third_customer&op=lists', 'text' => L('third_customer'));	    
	    Tpl::assign('third_customer', $third_customer);
	    Tpl::assign('ur_here', L('add_third_customer'));
	    Tpl::assign('action_link', $link);
	    Tpl::assign('form_op', 'insert');
	    /* 显示模板 */
	    Tpl::display('third_customer_info.htm');
    }

    /**
     * @return 添加第三方客服数据入库
     */
    public function insert() {
    	admin_priv('third_customer');
    	/*接收数据*/
    	$third_customer['cus_name'] = $_POST['cus_name'];
		$third_customer['cus_no'] = $_POST['cus_no'];
		$third_customer['cus_type'] = $_POST['cus_type'];
		$third_customer['is_master'] = $_POST['is_master'];
		$third_customer['supplier_id'] = 0;
		$third_customer['agentid'] = $_POST['cus_agentid'];
		$third_customer['zuid'] = $_POST['cus_agentid'];
		$third_customer['zuname'] = $_POST['cus_zuname'];
		$third_customer['beizhu'] = $_POST['cus_beizhu'];
		
		/*数据验证*/
	    if (isset($_FILES['cus_imgs']) && $_FILES['cus_imgs']['tmp_name'] != '') {	       
	        $uploaddir = 'data/kefu';
 			$res = upload_oss_img($_FILES['cus_imgs'],$uploaddir);
 			$third_customer['kefu_avatar'] = $res['url'];
	    }

	    /*判断客服名称是否为空*/
	    if (empty($third_customer['cus_name'])) {
	        showMessage(L('error_cus_name_empty'));
	    }

		/*判断客服号码是否为空*/
	    if (empty($third_customer['cus_no'])) {
	        showMessage(L('error_cus_no_empty'));
	    }

		$third_customer['add_time'] = gmtime();
		/*插入数据*/
		Model('third_customer')->insert_third_customer($third_customer);
		clear_cache_files();	    
		/* 提示信息 */
	    $links = array(
	        array('href' => 'index.php?act=third_customer&op=add', 'text' => L('add_third_customer')), 
	        array('href' => 'index.php?act=third_customer&op=lists', 'text' => L('back_third_customer_list'))
		);
		showMessage(L('add_success'), $links);
	}

	/**
     * @return 编辑第三方客服页面
     */
    public function edit() {
    	admin_priv('third_customer');
    	/* 取得客服信息 */
    	$cus_id = intval($_GET['cus_id']);
    	var_dump($cus_id);
	    $third_customer = Model('third_customer')->select_third_customer_info('*','cus_id='.$cus_id);
	    $third_customer['kefu_avatar'] = get_imgurl_oss($third_customer['kefu_avatar'], 100, 100);
	    $third_customer['formated_add_time'] = local_date('Y-m-d H:i', $third_customer['add_time']);
	    $link = array('href' => 'index.php?act=third_customer&op=lists', 'text' => L('third_customer'));	    
	    Tpl::assign('third_customer', $third_customer);
	    Tpl::assign('ur_here', L('edit_third_customer'));
	    Tpl::assign('action_link', $link);
	    Tpl::assign('form_op', 'update');
	    /* 显示模板 */
	    Tpl::display('third_customer_info.htm');
    }

    /**
     * @return 编辑第三方客服数据入库
     */
    public function update() {
    	admin_priv('third_customer');
    	$model = Model('third_customer');
    	$cus_id = $_POST['cus_id'];
    	/*接收数据*/
    	$third_customer['cus_name'] = $_POST['cus_name'];
		$third_customer['cus_no'] = $_POST['cus_no'];
		$third_customer['cus_type'] = $_POST['cus_type'];
		$third_customer['is_master'] = $_POST['is_master'];
		$third_customer['supplier_id'] = 0;
		$third_customer['agentid'] = $_POST['cus_agentid'];
		$third_customer['zuid'] = $_POST['cus_agentid'];
		$third_customer['zuname'] = $_POST['cus_zuname'];
		$third_customer['beizhu'] = $_POST['cus_beizhu'];
		/*数据验证*/
	    if (isset($_FILES['cus_imgs']) && $_FILES['cus_imgs']['tmp_name'] != '') {
	    	/*如果上传新图,且老图存在,删除老图*/
	    	$old_img = $model->select_third_customer_info('kefu_avatar','cus_id='.$cus_id);
	    	if($old_img){
	    		ossdeleteObjects($old_img);
	    	}
	        $uploaddir = 'data/kefu';
 			$res = upload_oss_img($_FILES['cus_imgs'],$uploaddir);
 			$third_customer['kefu_avatar'] = $res['url'];
	    }

	    /*判断客服名称是否为空*/
	    if (empty($third_customer['cus_name'])) {
	        showMessage(L('error_cus_name_empty'));
	    }

		/*判断客服号码是否为空*/
	    if (empty($third_customer['cus_no'])) {
	        showMessage(L('error_cus_no_empty'));
	    }

	    /*更新数据*/
	    $model->update_third_customer($third_customer,'cus_id='.$cus_id);
	    clear_cache_files();
	    /*提示信息*/
        $links = array('href' => 'index.php?act=third_customer&op=lists' , 'text' => L('back_third_customer_list'));
        showMessage(L('edit_success'), $links);

    }





















	/**
	 * @return 分页获取三方客服列表
	 * @return array
	 */

	private function get_third_customer_list() {
		$model = Model('third_customer');
	    $result = get_filter();
	    if ($result === false) {
	        $filter = array();
	        $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
	        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
	            $filter['keyword'] = json_str_iconv($filter['keyword']);
	        }
	        $filter['cus_id'] = empty($_REQUEST['cus_id']) ? 0 : intval($_REQUEST['cus_id']);
	        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'cus_id' : trim($_REQUEST['sort_by']);
	        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	        $where = (!empty($filter['keyword'])) ? " AND cus_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'" : '';
	        $filter['record_count'] = $model->get_third_customer_count("supplier_id = 0 $where");
	        /* 分页大小 */
	        $filter = page_and_size($filter);
	        /* 查询 */
	        $sql = "SELECT * FROM " . Model()->tablename('chat_third_customer') .
	            " WHERE supplier_id = 0 $where " . " ORDER BY $filter[sort_by] $filter[sort_order] ";
	        $filter['keyword'] = stripslashes($filter['keyword']);
	        set_filter($filter, $sql);
	    } else {
	        $sql = $result['sql'];
	        $filter = $result['filter'];
	    }
	    $list = get_all_page($sql, $filter['page_size'], $filter['start']);
	    foreach ($list as & $item) {
	        $item['formated_add_time'] = local_date('Y-m-d H:i:s', $item['add_time']);
	    }
	    unset($item);
	    $arr = array(
	        'item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']
	    );
	   return $arr;
	}


}