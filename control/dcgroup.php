<?php

/**
 * 淘玉php 后台典藏组队管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台典藏组队管理类
 * $Id: dcgroup.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class dcgroupControl extends BaseControl {
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
     * @return 典藏组队列表
     */
    public function lists() {
    	admin_priv('dcindex');
	    $team_list = $this->get_dc_team_stage_list();
	    Tpl::assign('ur_here', '典藏小分队列表');
	    Tpl::assign('full_page', 1);
	    Tpl::assign('team_list', $team_list['team_list']);
	    Tpl::assign('filter', $team_list['filter']);
	    Tpl::assign('record_count', $team_list['record_count']);
	    Tpl::assign('page_count', $team_list['page_count']);
	    $sort_flag = sort_flag($team_list['filter']);
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcgroup&op=add', 'text' => '添加小分队'));
	    Tpl::display('diancang_team_stage_list.htm');
	}

	/**
     * @return 典藏组队列表排序、分页、查询
     */
    public function lists_query() {
	    admin_priv('dcindex');
	    $team_list = $this->get_dc_team_stage_list();
	    Tpl::assign('team_list', $team_list['team_list']);
	    Tpl::assign('filter', $team_list['filter']);
	    Tpl::assign('record_count', $team_list['record_count']);
	    Tpl::assign('page_count', $team_list['page_count']);
	    $sort_flag = sort_flag($team_list['filter']);	   
	    make_json_result(Tpl::fetch('diancang_team_stage_list.htm'), '',
	    array('filter' => $team_list['filter'], 'page_count' => $team_list['page_count']));
	}

	/**
     * @return 进入添加添加典藏组队页面
     */
    public function add() {
    	admin_priv('dcindex');
    	$model = Model('diancang_team');
	    /*随机生成期号*/
	    do{
	        $stage_sn = $this->make_rand_code();
	        $stage_info = $model->select_capital_team_stage_info('*',"stage_sn= '$stage_sn'");
	    }while(!empty($stage_info['id']));
	    
	    /*默认比例*/
	    $arr = "0|1|0.5,1|5|1.0,5|10|1.3,10|30|1.5,30|120|2.0,120|0|2.5";
	    $radio = $this->get_team_radio($arr);
	    $stage['stage_sn'] = $stage_sn;
	    Tpl::assign('radio_list', $radio);
	    Tpl::assign('ur_here', '添加典藏小分队');
	    Tpl::assign('form_op', 'insert');
	    Tpl::assign('stage', $stage);
	    Tpl::display('diancang_team_stage_add.htm');
    }

    /**
     * @return 添加典藏组队到数据库
     */
    public function insert() {
    	admin_priv('dcindex');
    	$model = Model('diancang_team');	    
	    $data['stage_sn'] = $_POST['stage_sn'];
	    $data['team_reward_day'] = $_POST['team_reward_day'];
	    $data['team_money_limit'] = $_POST['team_money_limit'];
	    $data['back_day'] = $_POST['back_day'];
	    $data['start_time'] = local_strtotime($_POST['start_time']);
	    $data['end_time'] = local_strtotime($_POST['end_time']);
	    $data['is_open'] = 1;
	    $data['captain_radio'] = $_POST['captain_radio'];
	    $data['team_person_limit'] = $_POST['team_person_limit'];
	    $extra = $_POST['extra'];
	    
	    $ext = '';
	    foreach ($extra as $key => $value) {
	        if(!empty($value)){
	            $ext .= $value.',';
	        }
	    }

	    $ext = substr($ext, 0,strlen($ext)-1);
	    $data['extra_ratio'] = $ext;

	    $res = $model->insert_capital_team_stage($data);
	    if($res){
	    	$link = array('text' => '典藏小分队列表', 'href' => 'index.php?act=dcgroup&op=lists');
        	showMessage('典藏小分队添加成功', $link);
	    } else {
	    	$link = array('text' => '返回', 'href' => 'index.php?act=dcgroup&op=add');
        	showMessage('典藏小分队添加失败', $link);
	    }
    }

    /**
     * @return 删除典藏组队
     */
    public function remove() {
    	$stage_id = $_REQUEST['id'];
	   	Model('diancang_team')->delete_capital_team_stage("id = $stage_id");
	    admin_log($stage_id, 'remove', 'capital_team_stage');
	    $url = 'index.php?act=dcgroup&op=lists_query';
	    ecs_header("Location: $url\n");
	    exit;
    }

    /**
     * @return 进入添辑典藏组队页面
     */
    public function edit() {
    	admin_priv('dcindex');
	    $stage_id = $_REQUEST['stage_id'];
	    $stage = Model('diancang_team')->select_capital_team_stage_info('*',"id = $stage_id");
	    $stage['start_time'] = local_date('Y-m-d H:i:s', $stage['start_time']);
	    $stage['end_time'] = local_date('Y-m-d H:i:s', $stage['end_time']);
	    $radio = $this->get_team_radio($stage['extra_ratio']);	    
	    Tpl::assign('radio_list', $radio);
	    Tpl::assign('form_op', 'update');
	    Tpl::assign('stage', $stage);
	    Tpl::display('diancang_team_stage_add.htm');
    }

    /**
     * @return 编辑典藏组队数据入库
     */
    public function update() {
    	admin_priv('dcindex');
    	$model = Model('diancang_team');
    	$stage_id = $_POST['stage_id'];	    
	    $data['stage_sn'] = $_POST['stage_sn'];
	    $data['team_reward_day'] = $_POST['team_reward_day'];
	    $data['team_money_limit'] = $_POST['team_money_limit'];
	    $data['back_day'] = $_POST['back_day'];
	    $data['start_time'] = local_strtotime($_POST['start_time']);
	    $data['end_time'] = local_strtotime($_POST['end_time']);
	    $data['is_open'] = 1;
	    $data['captain_radio'] = $_POST['captain_radio'];
	    $data['team_person_limit'] = $_POST['team_person_limit'];
	    $extra = $_POST['extra'];
	    
	    $ext = '';
	    foreach ($extra as $key => $value) {
	        if(!empty($value)){
	            $ext .= $value.',';
	        }
	    }

	    $ext = substr($ext, 0,strlen($ext)-1);
	    $data['extra_ratio'] = $ext;

	    $res = $model->update_capital_team_stage_info($data,"id=$stage_id");
	    if($res){
	    	$link = array('text' => '典藏小分队列表', 'href' => 'index.php?act=dcgroup&op=lists');
        	showMessage('典藏小分队修改成功', $link);
	    } else {
	    	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
        	showMessage('典藏小分队修改失败', $link);
	    }
    }

    /**
     * @return 编辑典藏组队数据入库
     */
    public function close_stage() {
    	admin_priv('dcindex');
    	$model = Model('diancang_team');
	    $stage_id = $_REQUEST['stage_id'];
	    $where['id'] = $stage_id;
	    $stage = Model('diancang_team')->select_capital_team_stage_info('*',$where);
	    if($stage){
	        $data['is_open'] = 0;
	        $res = $model->update_capital_team_stage_info($data,$where);
	        if($res){
	            $link = array('text' => '典藏小分队列表', 'href' => 'index.php?act=dcgroup&op=lists');
        		showMessage('活动关闭成功', $link);
	        }else{
	            $link = array('text' => '典藏小分队列表', 'href' => 'index.php?act=dcgroup&op=lists');
        		showMessage('修改失败', $link);
	        }
	    }else{
	        $link = array('text' => '典藏小分队列表', 'href' => 'index.php?act=dcgroup&op=lists');
        	showMessage('该活动不存在，修改失败', $link);
	    }
    }

    /**
     * @return 获取典藏组队列表  
	 * @return array
	 */
    private function get_dc_team_stage_list() {
    	$result = get_filter();
    	$model = Model('diancang_team');
    	if ($result === false){
    		$filter = array();           
    		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
    		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);	        
	 		$filter['record_count'] = $model->get_capital_team_stage_count('1');
		    $filter = page_and_size($filter);
			$sql = 'SELECT * FROM ' . Model()->tablename('capital_team_stage') .
        	" ORDER by ".$filter['sort_by']." ".$filter['sort_order'];
        	set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
    	$row = get_all_page($sql, $filter['page_size'], $filter['start']);
    	if(!empty($row)){
    		foreach ($row as $key => $value) {
		    	$row[$key]['start_time'] = local_date('Y-m-d H:m:i',$value['start_time']);
		        $row[$key]['end_time'] = local_date('Y-m-d H:m:i',$value['end_time']);
		        $row[$key]['is_open'] = ($value['is_open'] == 1) ? '开放':'未开放';
		    }
		    $arr = array('team_list' => $row,'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    		return $arr;
    	}        
	}

	/**
	 * @return 根据日期生成 随机码
	 */
	private function make_rand_code(){
	     mt_srand((double) microtime() * 1000000);
	    return @date('Ymd') . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
	}


	/**
	 * @return 根据团队比例，处理为数组形式
	 * @param  string $arr 比例字符串
	 * @return array     数组
	 */
	private function get_team_radio($arr){
	    //$arr = "0|1|0.5,1|5|1.0,5|10|1.3,10|30|1.5,30|120|2.0,120|0|2.5";
	    $radio_arr = explode(',', $arr);
	    return $radio_arr;
	}
}
