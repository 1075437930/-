<?php

/**
 * 淘玉php 后台典藏标签管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台典藏标签管管理类
 * $Id: dctags.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class dctagsControl extends BaseControl {
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
     * @return 典藏标签列表
     */
    public function lists() {
    	admin_priv('dctags');
        $dctags_list = $this->get_dctags_list();
        Tpl::assign('ur_here', '典藏标签列表');
        Tpl::assign('full_page', 1);
        Tpl::assign('dctags_list', $dctags_list['dctags_list']);
        Tpl::assign('filter', $dctags_list['filter']);
        Tpl::assign('record_count', $dctags_list['record_count']);
        Tpl::assign('page_count', $dctags_list['page_count']);
        $sort_flag = sort_flag($dctags_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::assign('action_link', array('href' => 'index.php?act=dctags&op=add', 'text' => '添加典藏标签'));
        Tpl::display('diancang_tags.htm');
	}

	/**
     * @return 典藏标签列表排序、分页、查询
     */
    public function lists_query() {
        admin_priv('dctags');
    	$dctags_list = $this->get_dctags_list();
        Tpl::assign('dctags_list', $dctags_list['dctags_list']);
        Tpl::assign('filter', $dctags_list['filter']);
        Tpl::assign('record_count', $dctags_list['record_count']);
        Tpl::assign('page_count', $dctags_list['page_count']);
        $sort_flag = sort_flag($dctags_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('diancang_tags.htm'), '',
            array('filter' => $dctags_list['filter'], 'page_count' => $dctags_list['page_count']));
	}

	/**
     * @return 进入添加添加典藏标签页面
     */
    public function add() {
    	admin_priv('dcseting');
	    Tpl::assign('insert_or_update','insert');
	    $tags_into = array();
	    $tags_id = 0;
	    Tpl::assign('ur_here', '添加典藏标签');
	    Tpl::assign('action_link', array('href' => 'index.php?act=dctags&op=lists', 'text' => '返回典藏标签列表'));
	    Tpl::assign('tags_into', $tags_into);
	    Tpl::display('diancang_tags_into.htm');
    }

    /**
     * @return 添加典藏标签到数据库
     */
    public function insert() {
    	admin_priv('dctags');
    	$data = array();
    	$data['tags_name'] = empty($_REQUEST['tags_name']) ? '' : trim($_REQUEST['tags_name']);
        $res = Model('diancang_tagods')->insert_capital_tags($data);
        if($res){
        	admin_log($data['tags_name'], 'add', 'capital_tags');
	        /* 清楚缓存文件 */
	        clear_cache_files();
	        $link = array('text' => '返回典藏标签列表', 'href' => 'index.php?act=dctags&op=lists');
	        showMessage('典藏标签添加成功', $link);
        } else {
        	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
        	showMessage('典藏标签添加失败', $link);
        }       
    }

    /**
     * @return 删除典藏标签
     */
    public function remove() {
    	admin_priv('dctags');
        $model = Model('diancang_tagods');
        $tags_id = intval($_GET['id']);
        $zongrow = $model->get_capital_tagods_count("tags_id = '$tags_id'");
        if ($zongrow > 0) {
            make_json_result('','标签有产品使用不能删除',array('error'=>1));
        } else {
            $result = $model->delete_capital_tags("tags_id = '$tags_id'");;
            if ($result) {
                admin_log($tags_id, 'remove', 'capital_tags');
                $url = 'index.php?act=dctags&op=lists_query';
                ecs_header("Location: $url\n");
                exit;
            }
        }
    }

    /**
     * @return 进入添辑典藏标签页面
     */
    public function edit() {
    	admin_priv('dcseting');
        $model = Model('diancang_tagods');
        Tpl::assign('insert_or_update','update');
        $dctags_id = $_REQUEST['dctags_id'];
        $tags_into = $model->select_capital_tags_info('*','tags_id ='.$dctags_id);
        Tpl::assign('ur_here', '修改典藏标签');
        Tpl::assign('action_link', array('href' => 'index.php?act=dctags&op=lists', 'text' => '返回典藏标签列表'));
        Tpl::assign('tags_into', $tags_into);
        Tpl::display('diancang_tags_into.htm');
    }

    /**
     * @return 编辑典藏标签数据入库
     */
    public function update() {
    	admin_priv('dctags');
        $data = array();
        $data['tags_name'] = empty($_REQUEST['tags_name']) ? '' : trim($_REQUEST['tags_name']);
        $dctags_id = trim($_REQUEST['dctags_id']);
        $res = Model('diancang_tagods')->update_capital_tags_info($data,"tags_id ='$dctags_id'");
        if($res){
            admin_log($data['tags_name'], 'edit', 'capital_tags');
            /* 清楚缓存文件 */
            clear_cache_files();
            $link = array('text' => '返回典藏标签列表', 'href' => 'index.php?act=dctags&op=lists');
            showMessage('典藏标签修改成功', $link);
        } else {
            $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage('典藏标签修改失败', $link);
        }      
    }

    /**
     * @return 获取典藏标签列表  
	 * @return array
	 */
    private function get_dctags_list() {
    	$result = get_filter();
    	$model = Model('diancang_tagods');
    	if ($result === false){
    		$filter = array();           
    		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'tags_id' : trim($_REQUEST['sort_by']);
    		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);	        
	 		$filter['record_count'] = $model->get_capital_tags_count('1');
		    $filter = page_and_size($filter);
			$sql = 'SELECT * FROM ' . Model()->tablename('capital_tags') .
        	" ORDER by ".$filter['sort_by']." ".$filter['sort_order'];
        	set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
    	$row = get_all_page($sql, $filter['page_size'], $filter['start']);
	    if (!empty($row)) {
	        foreach ($row as $key => $value) {
                $tags_id = $value['tags_id'];
                $goodnums = $model->get_capital_tagods_count("tags_id = '$tags_id'");
                $row[$key]['goodnums'] = $goodnums;
            }
            $arr = array('dctags_list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
            return $arr; 
	    }       
	}
}
