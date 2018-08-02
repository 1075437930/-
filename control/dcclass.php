<?php

/**
 * 淘玉php 后台典藏分类管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台典藏分类管理类
 * $Id: dcclass.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class dcclassControl extends BaseControl {
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
     * @return 典藏分类列表
     */
    public function lists() {
    	admin_priv('dcclass');
	    $class_list = $this->get_dcclass_list();
	    Tpl::assign('full_page', 1);
	    Tpl::assign('dc_class', $class_list['dclass_list']);
	    Tpl::assign('ur_here', '典藏分类');
	    Tpl::assign('filter', $class_list['filter']);
	    Tpl::assign('record_count', $class_list['record_count']);
	    Tpl::assign('page_count', $class_list['page_count']);
	    $sort_flag = sort_flag($class_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcclass&op=add', 'text' => '添加分类设置'));
	    Tpl::display('diancang_class.htm');
	}

	/**
     * @return 典藏分类列表排序、分页、查询
     */
    public function lists_query() {
    	$class_list = $this->get_dcclass_list();
	    Tpl::assign('dc_class', $class_list['dclass_list']);
	    Tpl::assign('filter', $class_list['filter']);
	    Tpl::assign('record_count', $class_list['record_count']);
	    Tpl::assign('page_count', $class_list['page_count']);
	    $sort_flag = sort_flag($class_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('diancang_class.htm'), '',
	        array('filter' => $class_list['filter'], 'page_count' => $class_list['page_count']));
	}

	/**
     * @return 进入添加添加典藏分类页面
     */
    public function add() {
    	admin_priv('dcclass');
	    Tpl::assign('insert_or_update', 'insert');
	   	$class_into = array();
	    $class_id = 0;
	    Tpl::assign('ur_here', '添加分类');
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcclass&op=lists', 'text' => '返回分类列表'));
	    Tpl::assign('dcclassinto', $class_into);
	    Tpl::display('diancang_class_into.htm');
    }

    /**
     * @return 添加典藏分类到数据库
     */
    public function insert() {
    	admin_priv('dcclass');
	    $class_name = empty($_REQUEST['class_name']) ? '' : trim($_REQUEST['class_name']);
	    $dc_show = empty($_REQUEST['dc_show']) ? '' : trim($_REQUEST['dc_show']);
	    $contentsd = empty($_REQUEST['contentsd']) ? '' : trim($_REQUEST['contentsd']);
	    $data['class_name'] = $class_name;
	    $data['dc_show'] = $dc_show;
	    $data['content'] = $contentsd;
	    $flimgs = '';
	    if (!empty($_FILES['fenlei_img'])) {
	        $lujing = 'data/diancang/imges';
	        $file = ossUploadFile($_FILES['fenlei_img'], $lujing);
	        if (!empty($file)) {
	            $flimgs = $file['url'];
	            $data['cat_img'] = $flimgs;
	        }
	    }
	  	/*插入数据*/
	  	Model('diancang_class')->insert_capital_class($data);
	    admin_log($class_name, 'add', 'capital_class');
	    /* 清楚缓存文件 */
	    clear_cache_files();
	    $link = array('text' => '返回分类列表', 'href' => 'index.php?act=dcclass&op=lists');
        showMessage('典藏分类添加成功', $link);
    }

    /**
     * @return 删除典藏分类
     */
    public function remove() {
    	admin_priv('dcclass');
    	$class_model = Model('diancang_class');
	    $class_id = intval($_GET['id']);
	    $zongrow = Model('diancang')->get_capital_goods_count("dc_classid = '$class_id'");
	    if ($zongrow > 0) {
	        make_json_result('','本分类有产品不能删除',array('error'=>1));
	    } else {
	        $classinto = $class_model->select_capital_class_info('cat_img,class_name',"dcclass_id = '$class_id'");
	        if (!empty($classinto)) {
	            $result = $class_model->delete_capital_class("dcclass_id = '$class_id'");
	            if ($result) {
	                unset($classinto['class_name']);
	                ossdeleteObjects($classinto);
	                admin_log($class_id, 'remove', 'capital_class');
	                $url = 'index.php?act=dcclass&op=lists_query';
	                ecs_header("Location: $url\n");
	                exit;
	            }
	        } else {
	            $url = 'index.php?act=dcclass&op=lists_query';
	            ecs_header("Location: $url\n");
	        }
    	}
    }

    /**
     * @return 进入添辑典藏分类页面
     */
    public function edit() {
    	admin_priv('dcclass');
	    Tpl::assign('insert_or_update', 'update');
	   	$dcclass_id = $_REQUEST['dcclass_id'];
        $class_into = Model('diancang_class')->select_capital_class_info('*',"dcclass_id = '$dcclass_id'");
        $class_into['imgurl'] = get_imgurl_oss($row['cat_img'], 30, 30, false, true);
	    Tpl::assign('ur_here', '修改分类');
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcclass&op=lists', 'text' => '返回分类列表'));
	    Tpl::assign('dcclassinto', $class_into);
	    Tpl::display('diancang_class_into.htm');
    }

    /**
     * @return 编辑典藏分类数据入库
     */
    public function update() {
    	admin_priv('dcclass');
    	$model = Model('diancang_class');
	    $class_name = empty($_REQUEST['class_name']) ? '' : trim($_REQUEST['class_name']);
	    $dc_show = empty($_REQUEST['dc_show']) ? '' : trim($_REQUEST['dc_show']);
	    $fenlei_img2 = empty($_REQUEST['fenlei_img2']) ? '' : $_REQUEST['fenlei_img2'];
	    $contentsd = empty($_REQUEST['contentsd']) ? '' : trim($_REQUEST['contentsd']);
	    $data['class_name'] = $class_name;
	    $data['dc_show'] = $dc_show;
	    $data['content'] = $contentsd;
	    $flimgs = '';
	    if ($_FILES['fenlei_img']['error'] != 4) {
	        $lujing = 'data/diancang/imges';
	        $file = ossUploadFile($_FILES['fenlei_img'], $lujing);
	        if (!empty($file)) {
	            $flimgs = $file['url'];
	        }
	    }
	    $dcclass_id = trim($_REQUEST['dcclass_id']);
        if (!empty($flimgs)) {
            $updataimg = $flimgs;
            $rows = $model->select_capital_class_info('cat_img',"dcclass_id = '$dcclass_id'");
            if (!empty($rows['cat_img'])) {
                ossdeleteObjects($rows);
            }
        } else {
            if (!empty($fenlei_img2)) {
                $updataimg = $fenlei_img2;
            }
        }
        $data['cat_img'] = $updataimg;
        $model->update_capital_class_info($data,"dcclass_id = '$dcclass_id'");
        admin_log($class_name, 'edit', 'capital_class');
        /* 清楚缓存文件 */
        clear_cache_files();       
        $link = array('text' => '返回分类列表', 'href' => 'index.php?act=dcclass&op=lists');
        showMessage('典藏分类修改成功', $link);
    }

    /**
     * @return 编辑典藏分类状态（是否显示）
     */
    public function toggle_dclass_show() {
    	admin_priv('dcclass');
    	$model = Model('diancang_class');
	    $class_id = intval($_REQUEST['id']);
	    $is_value = intval($_REQUEST['val']);
	    $data['dc_show'] = $is_value;
	    $model->update_capital_class_info($data,"dcclass_id = '$class_id'");;
	    admin_log($class_id . '显示修改属性', 'edit', 'capital_class');
	    clear_cache_files();
	    make_json_result($is_value);
    }

    /**
     * @return 编辑典藏分类状态（是否热门）
     */
    public function toggle_class_hot() {
    	admin_priv('dcclass');
    	$model = Model('diancang_class');
	    $class_id = intval($_REQUEST['id']);
	    $is_value = intval($_REQUEST['val']);
	    $data['class_hot'] = $is_value;
	    $model->update_capital_class_info($data,"dcclass_id = '$class_id'");;
	    admin_log($class_id . '显示修改属性', 'edit', 'capital_class');
	    clear_cache_files();
	    make_json_result($is_value);
    }

    /**
     * @return 编辑典藏分类状态（是否推荐）
     */
    public function toggle_class_best() {
    	admin_priv('dcclass');
    	$model = Model('diancang_class');
	    $class_id = intval($_REQUEST['id']);
	    $is_value = intval($_REQUEST['val']);
	    $data['class_best'] = $is_value;
	    $model->update_capital_class_info($data,"dcclass_id = '$class_id'");;
	    admin_log($class_id . '显示修改属性', 'edit', 'capital_class');
	    clear_cache_files();
	    make_json_result($is_value);
    }

    /**
     * @return 获取典藏分类列表  
	 * @return array
	 */
    private function get_dcclass_list() {
    	$result = get_filter();
    	$model = Model('diancang_class');
    	if ($result === false){
    		$filter = array();           
    		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'dcclass_id' : trim($_REQUEST['sort_by']);
    		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);	        
	 		$filter['record_count'] = $model->get_capital_class_count('1');
		    $filter = page_and_size($filter);
			$sql = 'SELECT * FROM ' . Model()->tablename('capital_class') .
        	" ORDER by ".$filter['sort_by']." ".$filter['sort_order'];
        	set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
    	$row = get_all_page($sql, $filter['page_size'], $filter['start']);
    	if(!empty($row)){
    		foreach ($row as $key => $value) {
		        $class_id = $value['dcclass_id'];
		        $row[$key]['imgurl'] = get_imgurl_oss($value['cat_img'], 30, 30, false, true);
		        $goodnums = Model('diancang')->get_capital_goods_count("dc_classid = '$class_id'");
		        $row[$key]['goodnums'] = $goodnums;
		    }
		    $arr = array('dclass_list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
		    return $arr;
    	}        
	}
}
