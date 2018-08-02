<?php

/**
 * 淘玉php 后台广告位管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台广告位管理类
 * $Id: article.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class ad_positionControl extends BaseControl {
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('ads,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 广告位列表
     */
    public function lists() {
    	/* 权限的判断 */
        admin_priv('ad_position');
	    Tpl::assign('ur_here',     L('ad_position_lists'));
	    Tpl::assign('action_link', array('text' => L('ad_position_add'), 'href' => 'index.php?act=ad_position&op=add'));
	    Tpl::assign('full_page',  1);
	    $ad_position_list = $this->get_ad_position_list();
	    Tpl::assign('dcimgsite',     $ad_position_list['list']);
	    Tpl::assign('filter',       $ad_position_list['filter']);
	    Tpl::assign('record_count', $ad_position_list['record_count']);
	    Tpl::assign('page_count',   $ad_position_list['page_count']);
	    $sort_flag  = sort_flag($ad_position_list['filter']);
	    Tpl::assign($sort_flag['tag'], $ad_position_list['img']);
	    Tpl::display('ad_position_list.htm');
	}

	/**
     * @return 广告位列表排序、分页、查询
     */
    public function lists_query() {
    	/* 权限的判断 */
        admin_priv('ad_position');
	    $ad_position_list = $this->get_ad_position_list();
	    Tpl::assign('dcimgsite',     $ad_position_list['list']);
	    Tpl::assign('filter',       $ad_position_list['filter']);
	    Tpl::assign('record_count', $ad_position_list['record_count']);
	    Tpl::assign('page_count',   $ad_position_list['page_count']);
	    $sort_flag  = sort_flag($ad_position_list['filter']);
	    Tpl::assign($sort_flag['tag'], $ad_position_list['img']);
	    make_json_result(Tpl::fetch('ad_position_list.htm'), '',
	        array('filter' => $ad_position_list['filter'], 'page_count' => $ad_position_list['page_count']));
	}

	/**
     * @return 进入添加添加广告位页面
     */
    public function add() {
    	/* 权限判断 */
	    admin_priv('ad_position');
	    $model = Model('ad_position');
    	Tpl::assign('ur_here', L('ad_position_add'));
		Tpl::assign('action_link', array('href' => 'index.php?act=ad_position&op=lists', 'text' => L('ad_position_lists')));
    	Tpl::display('ad_position_into.htm');
    }

    /**
     * @return 添加广告位到数据库
     */
    public function insert() {
    	/* 权限判断 */
	    admin_priv('ad_position');
	    $site_name = empty($_REQUEST['site_name1']) ? '' : trim($_REQUEST['site_name1']);
		$width_w = empty($_REQUEST['width_w']) ? '' : trim($_REQUEST['width_w']);
		$high_h = empty($_REQUEST['high_h']) ? '' : trim($_REQUEST['high_h']);
		$media_type = empty($_REQUEST['media_type']) ? '' : trim($_REQUEST['media_type']);
		$dic_comt = empty($_REQUEST['dic_comt']) ? '' : trim($_REQUEST['dic_comt']);	
		if(!empty($_FILES['default_img']['tmp_name'])){
			$uploaddir = 'data/ads/'.date('Y');
	 		$res = upload_oss_img($_FILES['default_img'],$uploaddir);
	 		$path = $res['url'];
			if(!empty($path)){
				$dcimgs = $path;
			}
		}
	    $data['showsd'] = '1';
		$data['site_name'] = $site_name;
		$data['width_w'] = $width_w;
		$data['high_h'] = $high_h;
		$data['dic_comt'] = $dic_comt;
		$data['default_img'] = $dcimgs;
		$data['media_type'] = $media_type;
	    $model = Model('ad_position');
	    $bool = $model->insert_capital_brandarea($data);
	    if($bool){
	    	admin_log($site_name, 'add', 'capital_brandarea');
	    	header("Location:index.php?act=ad_position&op=lists");
	    }else{
	    	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ad_position_add_failed'), $link);
	    }
    }

    /**
     * @return 删除广告位
     */
    public function remove() {

    	/* 权限判断 */
    	admin_priv('ad_position');
	    $site_id = intval($_REQUEST['id']);
		$model = Model('ad_position');
		/*判断广告位下是否有广告*/
		$wheres = array('siteid'=>$site_id);
		$res = Model('ads')->select_capital_brand_info('imgs_id',$wheres);
		if($res){
			make_json_result('', '该广告位有广告，不能删除',array('error'=>1));
		}
		$param = array('site_id'=>$site_id);
		$pic = $model->select_capital_brandarea_info('default_img',$param);

		$dcimgs = $pic['default_img'];
		if($dcimgs){
			$res = ossdeleteObjects($pic);
		}
		$where = "site_id = '$site_id'";
		$result = $model->delete_capital_brandarea($where);
		if($result){
			admin_log($site_id, 'remove', 'capital_brandarea');
			$ad_position_list = $this->get_ad_position_list();
		    Tpl::assign('dcimgsite',     $ad_position_list['list']);
		    Tpl::assign('filter',       $ad_position_list['filter']);
		    Tpl::assign('record_count', $ad_position_list['record_count']);
		    Tpl::assign('page_count',   $ad_position_list['page_count']);
		    $sort_flag  = sort_flag($ad_position_list['filter']);
		    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		    make_json_result(Tpl::fetch('ad_position_list.htm'), '',
		        array('filter' => $ad_position_list['filter'], 'page_count' => $ad_position_list['page_count']));
		}else{
			$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ad_remove_failed'), $link);
		}
    }

    /**
     * @return 编辑广告位
     */
    public function edit() {
    	admin_priv('ad_position');
	    $site_id = intval($_REQUEST['id']);
	    $model = Model('ad_position');
	    $where = "site_id = '$site_id'";
		$info = $model->select_capital_brandarea_info('*',$where);
		Tpl::assign('ur_here', L('ad_position_edit'));
		Tpl::assign('action_link', array('href' => 'index.php?act=ad_position&op=lists', 'text' => L('ad_position_lists')));
		Tpl::assign('dcimgsiteinto', $info);
    	Tpl::display('ad_position_edit.htm');
    }

    /**
     * @return 编辑广告位数据插入数据库
     */
    public function update() {
    	/* 权限判断 */
	    admin_priv('ad_position');
	    $site_id = intval($_REQUEST['site_id']);
	    $site_name = empty($_REQUEST['site_name1']) ? '' : trim($_REQUEST['site_name1']);
		$width_w = empty($_REQUEST['width_w']) ? '' : trim($_REQUEST['width_w']);
		$high_h = empty($_REQUEST['high_h']) ? '' : trim($_REQUEST['high_h']);
		$media_type = empty($_REQUEST['media_type']) ? '' : trim($_REQUEST['media_type']);
		$dic_comt = empty($_REQUEST['dic_comt']) ? '' : trim($_REQUEST['dic_comt']);	
		$model = Model('ad_position');
		$param = array('site_id'=>$site_id);
		$pic = $model->select_capital_brandarea_info('default_img',$param);
		$dcimgs = $pic['default_img'];
		if(!empty($_FILES['default_img']['tmp_name'])){
			if($dcimgs){
				$res = ossdeleteObjects($pic);
			}
			$uploaddir = 'data/ads/'.date('Y');
	 		$res = upload_oss_img($_FILES['default_img'],$uploaddir);
	 		$path = $res['url'];
			if(!empty($path)){
				$dcimgs = $path;
			}
		}
	    $data['showsd'] = '1';
		$data['site_name'] = $site_name;
		$data['width_w'] = $width_w;
		$data['high_h'] = $high_h;
		$data['dic_comt'] = $dic_comt;
		$data['default_img'] = $dcimgs;
		$data['media_type'] = $media_type;
	    $where = "site_id = $site_id";
	    $bool = $model->update_capital_brandarea_info($data,$where);
	    if($bool){
	    	admin_log($site_name, 'edit', 'capital_brandarea');
	    	header("Location:index.php?act=ad_position&op=lists");
	    }else{
	    	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ad_position_add_failed'), $link);
	    }
    }

    /**
     * @return 修改显示不显示情况
     */
    public function toggle_siteshowsd() {
    	/* 权限判断 */
	    admin_priv('ad_position');
    	$site_id = intval($_REQUEST['id']);
	    $is_value = intval($_REQUEST['val']);
	    $where= array('site_id'=>$site_id);
    	$data['showsd'] = $is_value;
		$model = Model('ad_position');
		$model->update_capital_brandarea_info($data,$where);
		admin_log($site_id.'显示修改', 'edit', 'capital_brandarea');
	    make_json_result($is_value);
    }

    /**
     * @return 获取广告位置列表  
	 * @return array
	 */
    private function get_ad_position_list() {
    	$result = get_filter();
    	$model = Model('ad_position');
    	if ($result === false){
    		$filter = array();
    		$where = '';
            $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['select']    = empty($_REQUEST['select']) ? '' : trim($_REQUEST['select']);
            if($filter['select'] == "all"){
            	if(empty($filter['keyword'])){
            		$where = '';
            	}else{
            		$where .= "AND site_name like '%". $filter['keyword']."%' ";
            	}  	
            }else{
            	if(!empty($filter['keyword']) && !empty($filter['select'])){
		        	$where.=" AND media_type=".$filter['select']." and site_name like '%". $filter['keyword']."%' ";
		        }
		        if(empty($filter['keyword']) && !empty($filter['select'])){
		        	$where.=" AND media_type=".$filter['select'];
		        }
            }
	        
	 		$filter['record_count'] = $model->get_capital_brandarea_count($where);
		    $filter = page_and_size($filter);
		    $sql = 'SELECT * '.'FROM ' .Model()->tablename('capital_brandarea'). ' WHERE 1 '.$where;
	        $filter['keyword'] = stripslashes($filter['keyword']);
	        set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($row as $k => $v) {
        	if($v['media_type'] == 1){
        		$row[$k]['media_type'] = 'app';
        	}elseif ($v['media_type'] == 2) {
        		$row[$k]['media_type'] = 'pc';
        	}elseif ($v['media_type'] == 3) {
        		$row[$k]['media_type'] = 'wap';
        	}
        }
	    $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	    return $arr;
	}
}
