<?php

/**
 * 淘玉php 后台广告管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台广告管理类
 * $Id: ads.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class adsControl extends BaseControl {
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
     * @return 广告列表
     */
    public function lists() {
    	/* 权限的判断 */
        admin_priv('ad_list');
	    Tpl::assign('ur_here',     L('ads_lists'));
	    Tpl::assign('action_link', array('text' => L('ads_add'), 'href' => 'index.php?act=ads&op=add'));
	    Tpl::assign('full_page',  1);
	    $ads_list = $this->get_ads_list();
	    Tpl::assign('ads_list',     $ads_list['list']);
	    Tpl::assign('filter',       $ads_list['filter']);
	    Tpl::assign('record_count', $ads_list['record_count']);
	    Tpl::assign('page_count',   $ads_list['page_count']);
	    $sort_flag  = sort_flag($ads_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    Tpl::display('ads_list.htm');

    }

    /**
     * @return 广告列表排序、分页、查询
     */
    public function ads_list_query() {
    	/* 权限判断 */
	    admin_priv('ad_list');
		$ads_list = $this->get_ads_list();
	    Tpl::assign('ads_list',        $ads_list['list']);
	    Tpl::assign('filter',          $ads_list['filter']);
	    Tpl::assign('record_count',    $ads_list['record_count']);
	    Tpl::assign('page_count',      $ads_list['page_count']);
	    $sort_flag  = sort_flag($ads_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('ads_list.htm'), '',
	        array('filter' => $ads_list['filter'], 'page_count' => $ads_list['page_count']));
    }

    /**
     * @return 修改产品显示不显示情况
     */
    public function toggle_showsd() {
    	/* 权限判断 */
	    admin_priv('ad_add');
    	$imgs_id = intval($_REQUEST['id']);
	    $is_value = intval($_REQUEST['val']);
	    $where= array('imgs_id'=>$imgs_id);
    	$data =  array(
    		'showsd'=>$is_value,
		);
	    $bool = Model('ads')->update_capital_brand_info($data, $where);
		admin_log($imgs_id.'显示修改', 'edit', 'capital_brand');
	    make_json_result($is_value);
    }

    /**
     * @return 进入添加广告页面
     */
    public function add() {
    	/* 权限判断 */
	    admin_priv('ad_add');
	    $model = Model('ads');
		$site_list = $this->get_site_list(0);
    	Tpl::assign('ur_here', L('ads_add'));
		Tpl::assign('action','add');
		Tpl::assign('action_link', array('href' => 'index.php?act=ads&op=lists', 'text' => L('ads_lists')));
		Tpl::assign('site_list', $site_list['site_list']);
    	Tpl::display('ads_into.htm');

    }

    /**
     * @return 插入广告到数据库
     */
    public function insert() {
    	/* 权限判断 */
    	admin_priv('ad_add');
    	$ad_name = empty($_POST['ad_name']) ? '' : trim($_POST['ad_name']);
		$links = empty($_POST['links']) ? '' : trim($_POST['links']);
		$canshu = empty($_POST['canshu']) ? '' : trim($_POST['canshu']);
		$links_name = empty($_POST['links_name']) ? '' : trim($_POST['links_name']);
		$start_time = local_strtotime($_POST['start_time']);
	    $end_time   = local_strtotime($_POST['end_time']);
		$siteid = empty($_POST['siteid']) ? '' : trim($_POST['siteid']);
		$showsd = trim($_POST['showsd']);
		$dcimgs = '';
		if(!empty($_FILES['imgurl'])){
			$uploaddir = 'data/ads/'.date('Y');
	 		$res = upload_oss_img($_FILES['imgurl'],$uploaddir);
	 		$path = $res['url'];
			if(!empty($path)){
				$dcimgs = $path;
			}
		}
		$data['siteid'] = $siteid;
		$data['showsd'] = $showsd;
		$data['imgurl'] = $dcimgs;
		$data['start_time'] = $start_time;
		$data['end_time'] = $end_time;
		$data['ad_name'] = $ad_name;
		$data['links'] = $links;
		$data['links_name'] = $links_name;
		$data['canshu'] = $canshu;
	    $bool = Model('ads')->insert_capital_brand($data);
	    admin_log($ad_name, 'add', 'capital_brand');
	    if($bool){
	    	header("Location:index.php?act=ads&op=lists");
	    }else{
	    	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ad_add_failed'), $link);
	    }
    }

    /**
     * @return 删除广告
     */
    public function remove() {
    	admin_priv('ad_del');
	    $imgs_id = intval($_REQUEST['id']);
	    $where = "imgs_id = '$imgs_id'";
	    $ads = Model('ads');
		$param = array('imgs_id'=>$imgs_id);
		$pic = $ads->select_capital_brand_info('imgurl',$param);
		$dcimgs = $pic['imgurl'];
		if($dcimgs){
			$res = ossdeleteObjects($pic);
		}
		$result = $ads->delete_capital_brand($where);
		if($result){
			admin_log($imgs_id, 'remove', 'capital_brand');
			$ads_list = $this->get_ads_list();
		    Tpl::assign('ads_list',     $ads_list['list']);
		    Tpl::assign('filter',       $ads_list['filter']);
		    Tpl::assign('record_count', $ads_list['record_count']);
		    Tpl::assign('page_count',   $ads_list['page_count']);
		    $sort_flag  = sort_flag($ads_list['filter']);
		    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		    make_json_result(Tpl::fetch('ads_list.htm'), '',
		        array('filter' => $ads_list['filter'], 'page_count' => $ads_list['page_count']));
		}else{
			$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ad_remove_failed'), $link);
		}
    }

    /**
     * @return 编辑广告
     */
    public function edit() {
    	admin_priv('ad_del');
	    $imgs_id = intval($_REQUEST['id']);
	    $where = "imgs_id = '$imgs_id'";
	    $model = Model('ads');
		$adinfo = $model->select_capital_brand_info('*',$where);
		$site_list = $this->get_site_list($imgs_id);
		$adinfo['start_time'] = local_date(C('time_format'), $adinfo['start_time']);
		$adinfo['end_time'] = local_date(C('time_format'), $adinfo['end_time']);
		Tpl::assign('ur_here', L('ads_edit'));
		Tpl::assign('action','add');
		Tpl::assign('action_link', array('href' => 'index.php?act=ads&op=lists', 'text' => L('ads_lists')));
		Tpl::assign('dcimginto', $adinfo);
		Tpl::assign('site_list', $site_list['site_list']);
		Tpl::assign('site_id', $site_list['site_id']);
    	Tpl::display('ads_edit.htm');

    }

    /**
     * @return 编辑广告数据插入数据库
     */
    public function update() {
    	/* 权限判断 */
    	admin_priv('ad_add');
    	$imgs_id = intval($_REQUEST['id']);
    	$ad_name = empty($_POST['ad_name']) ? '' : trim($_POST['ad_name']);
		$links = empty($_POST['links']) ? '' : trim($_POST['links']);
		$canshu = empty($_POST['canshu']) ? '' : trim($_POST['canshu']);
		$links_name = empty($_POST['links_name']) ? '' : trim($_POST['links_name']);
		$start_time = local_strtotime($_POST['start_time']);
	    $end_time   = local_strtotime($_POST['end_time']);
		$siteid = empty($_POST['siteid']) ? '' : trim($_POST['siteid']);
		$showsd = trim($_POST['showsd']);
		$ads = Model('ads');
		$param = array('imgs_id'=>$imgs_id);
		$pic = $ads->select_capital_brand_info('imgurl',$param);
		$dcimgs = $pic['imgurl'];
		if(!empty($_FILES['imgurl']['tmp_name'])){
			if($dcimgs){
				$res = ossdeleteObjects($pic);
			}
			$uploaddir = 'data/ads/'.date('Y');
	 		$res = upload_oss_img($_FILES['imgurl'],$uploaddir);
	 		$path = $res['url'];
			if(!empty($path)){
				//上传oss 图片后地址
				$dcimgs = $path;
			}
		}
		$data['siteid'] = $siteid;
		$data['showsd'] = $showsd;
		$data['imgurl'] = $dcimgs;
		$data['start_time'] = $start_time;
		$data['end_time'] = $end_time;
		$data['ad_name'] = $ad_name;
		$data['links'] = $links;
		$data['links_name'] = $links_name;
		$data['canshu'] = $canshu;
		$where = "imgs_id = $imgs_id";
	    $bool = $ads->update_capital_brand_info($data,$where);
	    if($bool){
	    	admin_log($ad_name, 'edit', 'capital_brand');
	    	header("Location:index.php?act=ads&op=lists");
	    }else{
	    	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ad_edit_failed'), $link);
	    }
    }

    /**
     * @return 获取广告列表
     * @return array  
	 */
    private function get_ads_list() {
    	$result = get_filter();
    	$model = Model('ads');
    	if ($result === false){
    		$filter = array();
    		$where = '';
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['select'] = empty($_REQUEST['select']) ? '' : trim($_REQUEST['select']);
            $filter['site_id'] = empty($_REQUEST['site_id']) ? '' : trim($_REQUEST['site_id']);
            if($filter['select'] == "all"){
            	if(empty($filter['keyword'])){
            		$where = '';
            	}else{
            		$where .= " AND capital_brand.ad_name like '%". $filter['keyword']."%' ";
            	}  	
            }
			if(!empty($filter['keyword']) && $filter['select'] == "1"){
	     		$where.=" AND capital_brand.ad_name like '%". $filter['keyword']."%' ";
	        }
	        if(empty($filter['keyword']) && $filter['select'] == "1"){
	        	$filter['page_size'] = 1;
	        	$filter['page'] = 1;
	        	$arr = array('list' => array(), 'filter' => $filter, 'page_count' => 1, 'record_count' => 0);
	    		return $arr;
	        }
	        if(!empty($filter['keyword']) && $filter['select'] == "2"){
	        	$where.=" AND capital_brand.imgs_id like '%". $filter['keyword']."%' ";
	        }
	        if(empty($filter['keyword']) && $filter['select'] == "2"){
	        	$filter['page_size'] = 1;
	        	$filter['page'] = 1;
	        	$arr = array('list' => array(), 'filter' => $filter, 'page_count' => 1, 'record_count' => 0);
	    		return $arr;
	        }
	        if(!empty($filter['site_id'])){
	        	$where.=" AND capital_brand.siteid like '%". $filter['site_id']."%' ";
	        }
		    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'ad_name' : trim($_REQUEST['sort_by']);
	        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	 		$filter['record_count'] = $model->get_capital_brand_count($where);
		    $filter = page_and_size($filter);
		    $sql = 'SELECT capital_brand.*, capital_brandarea.site_name '.
                'FROM ' .Model()->tablename('capital_brand'). ' AS capital_brand ' .
                'LEFT JOIN ' . Model()->tablename('capital_brandarea'). ' AS capital_brandarea ON capital_brandarea.site_id = capital_brand.siteid '.
                'WHERE 1 '.$where.' ORDER by capital_brand.'.$filter['sort_by'].' '.$filter['sort_order'];
	        $filter['keyword'] = stripslashes($filter['keyword']);
	        set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        /* 获得广告数据 */
        $arr = array();
        if(!empty($row)){
        	foreach($row as $key=>$value){
	            /* 格式化日期 */
	            $value['start_date']    = local_date(C('time_format'), $value['start_time']);
	            $value['end_date']      = local_date(C('time_format'), $value['end_time']);
	            $arr[] = $value;
		    }
        }	    
	    $arr = array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	    return $arr;
	}

	/**
     * @return 添加或者编辑广告时获取广告位置信息
     * @param $imgs_id int 默认为0，添加问答时使用，分类信息下拉框第一个分类被选择。
     * 大于0，编辑问答时使用，代表当前问答的id标识，分类信息下拉框当前问答所属分类被选择。
     * @return  array
     */
    private function get_site_list($imgs_id = 0){
        $site_list = array();
        $model = Model('ads');
        $row = $model->get_capital_brandarea_list('*','1');
        if($imgs_id > 0 ){
            $where = array('imgs_id'=>$imgs_id);
            $site_id = $model->select_capital_brand_info('siteid',$where);
        }else{
            $site_id = 0;
        }
        foreach($row as $k=>$v){
            $site_list[$v['site_id']] = htmlspecialchars($v['site_name']);
        }
        return array('site_list' => $site_list, 'site_id' => $site_id['siteid']);
    }
}
