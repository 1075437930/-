<?php

/**
 * 淘玉php 后台收益设置管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台收益设置管理类
 * $Id: dcgroup.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class dcyuesetControl extends BaseControl {
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
     * @return 月份比例列表
     */
    public function lists() {
    	admin_priv('dcseting');
	    $yubili_list = $this->get_bili_list();
        Tpl::assign('ur_here', '月份比例列表');
	    Tpl::assign('full_page', 1);
	    Tpl::assign('yuebili_list', $yubili_list['bililist']);
	    Tpl::assign('filter', $yubili_list['filter']);
	    Tpl::assign('record_count', $yubili_list['record_count']);
	    Tpl::assign('page_count', $yubili_list['page_count']);
	    $sort_flag = sort_flag($yubili_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcyueset&op=add', 'text' => '添加月份设置比例'));
	    Tpl::display('diancang_yuebili.htm');
	}

	/**
     * @return 月份比例列表排序、分页、查询
     */
    public function lists_query() {
    	admin_priv('dcseting');
	    $yubili_list = $this->get_bili_list();
	    Tpl::assign('yuebili_list', $yubili_list['bililist']);
	    Tpl::assign('filter', $yubili_list['filter']);
	    Tpl::assign('record_count', $yubili_list['record_count']);
	    Tpl::assign('page_count', $yubili_list['page_count']);
	    $sort_flag = sort_flag($yubili_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('diancang_yuebili.htm'), '',
	        array('filter' => $yubili_list['filter'], 'page_count' => $yubili_list['page_count']));
	}

	/**
     * @return 进入添加添加月份比例页面
     */
    public function add() {
    	admin_priv('dcseting');
	    Tpl::assign('insert_or_update','insert');
	    $yubili_into = array();
	    $set_id = 0;
	    Tpl::assign('ur_here', '添加月份比例');
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcyueset&op=lists', 'text' => '返回月份比例列表'));
	    Tpl::assign('yubili_into', $yubili_into);
	    Tpl::display('diancang_yuebili_into.htm');
    }

    /**
     * @return 添加月份比例到数据库
     */
    public function insert() {
    	admin_priv('dcseting');
    	$data = array();
    	$data['yuefen'] = empty($_REQUEST['yue']) ? '' : trim($_REQUEST['yue']);
    	$data['stypes'] = empty($_REQUEST['stypes']) ? '' : trim($_REQUEST['stypes']);
    	$data['bili']   = empty($_REQUEST['bili']) ? '' : sprintf("%.2f", trim($_REQUEST['bili']));
        $res = Model('yuebili')->insert_capital_bili($data);
        if($res){
        	admin_log($data['yuefen'], 'add', 'capital_bili');
	        /* 清楚缓存文件 */
	        clear_cache_files();
	        $link = array('text' => '返回月份比例列表', 'href' => 'index.php?act=dcyueset&op=lists');
	        showMessage('典藏月份比例添加成功', $link);
        } else {
        	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
        	showMessage('典藏月份比例添加失败', $link);
        }       
    }

    /**
     * @return 删除月份比例
     */
    public function remove() {
    	admin_priv('dcseting');
	    $set_id = intval($_GET['id']);
	    $result = Model('yuebili')->delete_capital_bili("set_id = '$set_id'");
	    if ($result) {
	        admin_log($set_id, 'remove', 'capital_bili');
	        $url = 'index.php?act=dcyueset&op=lists_query';
	        ecs_header("Location: $url\n");
	        exit;
	    }
    }

    /**
     * @return 进入添辑月份比例页面
     */
    public function edit() {
    	admin_priv('dcseting');
	    Tpl::assign('insert_or_update','update');
	    $set_id = $_REQUEST['set_id'];
        $yubili_into = Model('yuebili')->select_capital_bili_info('*',"set_id = '$set_id'");
	    Tpl::assign('ur_here', '添加月份比例');
	    Tpl::assign('action_link', array('href' => 'index.php?act=dcyueset&op=lists', 'text' => '返回月份比例列表'));
	    Tpl::assign('ur_here', '月份比例管理');
	    Tpl::assign('yubili_into', $yubili_into);
	    Tpl::display('diancang_yuebili_into.htm');
    }

    /**
     * @return 编辑月份比例数据入库
     */
    public function update() {
    	admin_priv('dcseting');
    	$data = array();
    	$set_id = trim($_REQUEST['set_id']);
    	$data['yuefen'] = empty($_REQUEST['yue']) ? '' : trim($_REQUEST['yue']);
    	$data['stypes'] = empty($_REQUEST['stypes']) ? '' : trim($_REQUEST['stypes']);
    	$data['bili']   = empty($_REQUEST['bili']) ? '' : sprintf("%.2f", trim($_REQUEST['bili']));
        $res = Model('yuebili')->update_capital_bili_info($data,"set_id = '$set_id'");
        if($res){
        	admin_log($data['yuefen'], 'edit', 'capital_bili');
	        /* 清楚缓存文件 */
	        clear_cache_files();
	        $link = array('text' => '返回月份比例列表', 'href' => 'index.php?act=dcyueset&op=lists');
	        showMessage('典藏月份比例修改成功', $link);
        } else {
        	$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
        	showMessage('典藏月份比例修改失败', $link);
        } 
    }

    /**
     * @return 获取月份比例列表  
	 * @return array
	 */
    private function get_bili_list() {
    	$result = get_filter();
    	$model = Model('yuebili');
    	if ($result === false){
    		$filter = array();           
    		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'set_id' : trim($_REQUEST['sort_by']);
    		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);	        
	 		$filter['record_count'] = $model->get_capital_bili_count('1');
		    $filter = page_and_size($filter);
			$sql = 'SELECT * FROM ' . Model()->tablename('capital_bili') .
        	" ORDER by ".$filter['sort_by']." ".$filter['sort_order'];
        	set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
    	$row = get_all_page($sql, $filter['page_size'], $filter['start']);
    	/*存储所有月份为字符串逗号分割*/
    	$comment = '';
	    if (!empty($row)) {
	        foreach ($row as $key => $value) {
	            $comment .= $value['yuefen'] . '个月/' . $value['bili'] . '% , ';

	        }
	    } else {
	        $comment = '暂未设置典藏对应月份比例';
	    }
	    $arr = array('bililist' => $row, 'bilicommt' => $comment, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	    return $arr;        
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
