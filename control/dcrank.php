<?php

/**
 * 淘玉php 后台典藏等级管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台典藏等级管理类
 * $Id: dcrank.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class dcrankControl extends BaseControl {
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('diancang,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 典藏等级列表
     */
    public function lists() {
    	admin_priv('dcrank');
	    $dcrank = $this->get_rank_list();
	    Tpl::assign('full_page', true);
	    Tpl::assign('filter', $dcrank['filter']);
	    Tpl::assign('record_count', $dcrank['record_count']);
	    Tpl::assign('page_count', $dcrank['page_count']);
	    $sort_flag = sort_flag($dcrank['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcrank&op=add', 'text' => '添加典藏等级'));
	    Tpl::assign('ur_here', '典藏等级设置');
	    Tpl::assign('dc_ranks', $dcrank['ranklist']);
	    Tpl::display('diancang_rank.htm');
	}

	/**
     * @return 典藏等级列表排序、分页、查询
     */
    public function lists_query() {
    	$dcrank = $this->get_rank_list();
	    Tpl::assign('filter', $dcrank['filter']);
	    Tpl::assign('record_count', $dcrank['record_count']);
	    Tpl::assign('page_count', $dcrank['page_count']);
	    Tpl::assign('dc_ranks', $dcrank['ranklist']);
	    $sort_flag = sort_flag($dcrank['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('diancang_rank.htm'), '',
	        array('filter' => $dcrank['filter'], 'page_count' => $dcrank['page_count']));
	}

	/**
     * @return 进入添加添加典藏等级页面
     */
    public function add() {
    	admin_priv('dcrank');
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcrank&op=lists', 'text' => '返回典藏等级'));
	    Tpl::assign('ur_here', '典藏等级添加');
	    Tpl::assign('form_action', 'rank_insert');
	    Tpl::display('diancang_rank_info.htm');
    }

    /**
     * @return 添加典藏等级到数据库
     */
    public function insert() {
    	admin_priv('dcrank');
	    $rank_name = empty($_REQUEST['rank_name']) ? '' : trim($_REQUEST['rank_name']);
	    $min_points = empty($_REQUEST['min_points']) ? '0' : trim($_REQUEST['min_points']);
	    $max_points = empty($_REQUEST['max_points']) ? '0' : trim($_REQUEST['max_points']);
	    $data['rank_name']  = $rank_name;
	    $data['min_points'] = $min_points;
	    $data['max_points'] = $max_points;
	    Model('diancang_rank')->insert_capital_rank($data);
	    admin_log($rank_name, 'add', 'capital_rank');
	    /* 清除缓存文件 */
	    clear_cache_files();
	    $link = array('text' => L('go_back',''), 'href' => 'index.php?act=dcrank&op=lists');
        showMessage('典藏等级添加成功', $link);
    }

    /**
     * @return 删除典藏等级
     */
    public function remove() {
    	admin_priv('dcrank');
	    $rank_id = intval($_GET['id']);
	    $result = Model('diancang_rank')->delete_capital_rank("rank_id = '$rank_id'");
	    if ($result) {
	        admin_log($rank_id, 'remove', 'capital_rank');
	        $url = 'index.php?act=dcrank&op=lists_query';
	        ecs_header("Location: $url\n");
	        exit;
	    }
    }

    /**
     * @return 编辑会员等级名称
     */
    public function edit_name() {
    	admin_priv('dcrank');
    	$id = intval($_REQUEST['id']);
	    $val = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
	    Model('diancang_rank')->update_capital_rank_info(array('rank_name'=>$val),"rank_id = '$id'");
	    admin_log($val, 'edit', 'capital_rank');
	    clear_cache_files();
	    make_json_result(stripcslashes($val));
    }

    /**
     * @return 编辑积分下限
     */
    public function edit_min_points() {
    	admin_priv('dcrank');
    	$model = Model('diancang_rank');
	    $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	    $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);
	    $rank = $model->select_capital_rank_info('max_points,rank_name',"rank_id = '$rank_id'");
	    if ($val >= $rank['max_points']) {
	        make_json_error('不能大于最大积分');
	    }
	    $model->update_capital_rank_info(array('min_points'=>$val),"rank_id = '$rank_id'");
	    admin_log(addslashes($rank['rank_name']), 'edit', 'capital_rank');
	    clear_cache_files();
	    make_json_result(stripcslashes($val));
    }

    /**
     * @return 编辑积分上限
     */
    public function edit_max_points() {
    	admin_priv('dcrank');
    	$model = Model('diancang_rank');
	    $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	    $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);
	    $rank = $model->select_capital_rank_info('min_points,rank_name',"rank_id = '$rank_id'");
	    if ($val <= $rank['min_points']) {
	        make_json_error('不能小于等于最小积分');
	    }

	    $model->update_capital_rank_info(array('max_points'=>$val),"rank_id = '$rank_id'");
	    admin_log(addslashes($rank['rank_name']), 'edit', 'capital_rank');
	    clear_cache_files();
	    make_json_result(stripcslashes($val));
    }


    /**
     * @return 获取典藏等级列表  
	 * @return array
	 */
    private function get_rank_list() {
    	$result = get_filter();
    	$model = Model('diancang_rank');
    	if ($result === false){
    		$filter = array();
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'sort_rank' : trim($_REQUEST['sort_by']);
    		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);	        
	 		$filter['record_count'] = $model->get_capital_rank_count('1');
		    $filter = page_and_size($filter);
			$sql = 'SELECT * FROM ' . Model()->tablename('capital_rank') .
        	" ORDER by ".$filter['sort_by']." ".$filter['sort_order'];
        	set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        $arr = array('ranklist' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    	return $arr;
	}
}
