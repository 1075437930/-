<?php

/**
 * 淘玉php 后台问答管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萧瑟 $
 * 后台问答管理类
 * $Id: askset.php  2018-04-07   萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class asksetControl extends BaseControl {
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('askset,calendar');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 问答列表
     */
    public function ask_list() {
    	/* 权限的判断 */
	    admin_priv('asklist');
		$class_id = empty($_REQUEST['class_id']) ? '' : trim($_REQUEST['class_id']);
		$ask_list = $this->get_ask_list();
		if($class_id){
			Tpl::assign('action_link2', array('href' => 'index.php?act=askset&op=ask_list', 'text' =>L('ask_list')));
		}
		Tpl::assign('ur_here', L('ask_list'));
		Tpl::assign('full_page', 1);
	    Tpl::assign('ask_list',      $ask_list['list']);
	    Tpl::assign('filter',          $ask_list['filter']);
	    Tpl::assign('record_count',    $ask_list['record_count']);
	    Tpl::assign('page_count',      $ask_list['page_count']);
	    $sort_flag  = sort_flag($ask_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		Tpl::assign('action_link', array('href' => 'index.php?act=askset&op=ask_add', 'text' =>L('ask_add')));
	    Tpl::display('ask_list.htm');
	}

	/**
     * @return 问答列表排序、分页、查询
     */
    public function ask_list_query() {
    	/* 权限判断 */
	    admin_priv('asklist');
		$class_id = empty($_REQUEST['class_id']) ? '' : trim($_REQUEST['class_id']);
	    $ask_list = $this->get_ask_list();
		if($class_id){
			Tpl::assign('action_link2', array('href' => 'index.php?act=askset&op=ask_list', 'text' =>L('ask_list')));
		}
	    Tpl::assign('ask_list',        $ask_list['list']);
	    Tpl::assign('filter',          $ask_list['filter']);
	    Tpl::assign('record_count',    $ask_list['record_count']);
	    Tpl::assign('page_count',      $ask_list['page_count']);
	    $sort_flag  = sort_flag($ask_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);

	    make_json_result(Tpl::fetch('ask_list.htm'), '',
	        array('filter' => $ask_list['filter'], 'page_count' => $ask_list['page_count']));
	}

	/**
     * @return 添加问答页面
     */
    public function ask_add() {
    	/* 权限判断 */
	    admin_priv('addask');
		$classlist = $this->get_classlei(0);
		Tpl::assign('ur_here', L('ask_add'));
		Tpl::assign('classlist', $classlist['classlist']);
		Tpl::assign('form_op','ask_insert');
		Tpl::assign('classid', $classlist['class_id']);
	    Tpl::assign('action_link', array('href' => 'index.php?act=askset&op=ask_list', 'text' => L('ask_list')));
	    Tpl::display('ask_into.htm');

    }

    /**
     * @return 添加问答到数据库
     */
    public function ask_insert() {
    	/* 权限判断 */
	    admin_priv('addask');
	    $questions_name = empty($_REQUEST['questions_name']) ? '' : trim($_REQUEST['questions_name']);
		$answers_cent = empty($_REQUEST['answers_cent']) ? '' : trim($_REQUEST['answers_cent']);
		$answers_key = empty($_REQUEST['questions_key']) ? '' : trim($_REQUEST['questions_key']);
		$classid = empty($_REQUEST['ask_class']) ? '0' : trim($_REQUEST['ask_class']);
		$sess = $this->admin_info;
		$admin_id = $sess['user_id'];
		$new_times = gmtime();
        $data['questions'] = $questions_name;
        $data['types'] = 2;
		$data['answers'] = $answers_cent;
		$data['class_id'] = $classid;
		$data['keyword'] = $answers_key;
		$data['user_id'] = $admin_id;
        /*插入数据，返回自增长id */
        $model = Model('askset');
		$new_id = $model->insert_que_ans($data);
        if($new_id ){
        	admin_log($questions_name, 'add', 'que_ans');
	        header("Location:index.php?act=askset&op=ask_list");
       	}else{
       		$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ask_add_failed',''), $link);
       	}
    }

    /**
     * @return 编辑问答
     */
    public function ask_edit() {
    	/* 权限判断 */
	    admin_priv('addask');
    	$ask_id = $_REQUEST['askid'];
    	$askset = Model('askset');
		$classlist = $this->get_classlei($ask_id);
		$where= array('ask_id'=>$ask_id);
        $askinto = $askset->select_que_ans_info('*',$where);
        Tpl::assign('ur_here', L('ask_edit'));
        Tpl::assign('form_op','ask_update');
		Tpl::assign('classlist', $classlist['classlist']);
		Tpl::assign('classid', $classlist['classid']);
	    Tpl::assign('askinto', $askinto);
	    Tpl::assign('action_link', array('href' => 'index.php?act=askset&op=ask_list', 'text' => L('ask_list')));
	    Tpl::display('ask_edit.htm');
    }

    /**
     * @return 编辑问答数据插入数据库
     */
    public function ask_update() {
    	/* 权限判断 */
	    admin_priv('addask');
	    $questions_name = empty($_REQUEST['questions_name']) ? '' : trim($_REQUEST['questions_name']);
		$answers_cent = empty($_REQUEST['answers_cent']) ? '' : trim($_REQUEST['answers_cent']);
		$answers_key = empty($_REQUEST['questions_key']) ? '' : trim($_REQUEST['questions_key']);
		$classid = empty($_REQUEST['ask_class']) ? '0' : trim($_REQUEST['ask_class']);
		$sess = $this->admin_info;
		$admin_id = $sess['user_id'];
    	$askid = trim($_REQUEST['askid']);
    	$where= array('ask_id'=>$askid);
		$data['questions'] = $questions_name;
		$data['answers'] = $answers_cent;
		$data['class_id'] = $classid;
		$data['keyword'] = $answers_key;
		$data['user_id'] = $admin_id;
	    $bool = Model('askset')->update_que_ans_info($data, $where);
		admin_log($questions_name, 'edit', 'que_ans');
		header("Location:index.php?act=askset&op=ask_list");
    }

    /**
     * @return 删除问答
     */
    public function ask_remove() {
    	admin_priv('addask');
	    $ask_id = intval($_REQUEST['id']);
	    $where = "ask_id = '$ask_id'";
		$askset = Model('askset');
		$result = $askset->delete_que_ans($where);
		if($result){
			admin_log($ask_id, 'remove', 'que_ans');
			$ask_list = $this->get_ask_list();
		    Tpl::assign('ask_list',        $ask_list['list']);
		    Tpl::assign('filter',          $ask_list['filter']);
		    Tpl::assign('record_count',    $ask_list['record_count']);
		    Tpl::assign('page_count',      $ask_list['page_count']);
		    $sort_flag  = sort_flag($ask_list['filter']);
		    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		    make_json_result(Tpl::fetch('ask_list.htm'), '',
		        array('filter' => $ask_list['filter'], 'page_count' => $ask_list['page_count']));
		}else{
			$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('ask_remove_failed'), $link);
		}
    }

    /**
     * @return 客服日志列表
     */
    public function cus_log_list() {
    	/* 权限判断 */
	    admin_priv('cus_log');
	    $cus_log_list = $this->get_cus_log();
	    Tpl::assign('ur_here', L('cus_log'));
	    Tpl::assign('full_page', 1);
	    Tpl::assign('cus_log_list',    $cus_log_list['list']);
	    Tpl::assign('filter',          $cus_log_list['filter']);
	    Tpl::assign('record_count',    $cus_log_list['record_count']);
	    Tpl::assign('page_count',      $cus_log_list['page_count']);
	    $sort_flag  = sort_flag($cus_log_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    Tpl::display('cus_log.htm');
	}

	/**
     * @return 客服日志列表排序、分页、查询
     */
    public function cus_log_query() {
    	/* 权限的判断 */
	    admin_priv('cus_log');
	    $cus_log_list = $this->get_cus_log();
	    Tpl::assign('ur_here', L('cus_log'));
	    Tpl::assign('cus_log_list',      $cus_log_list['list']);
	    Tpl::assign('filter',          $cus_log_list['filter']);
	    Tpl::assign('record_count',    $cus_log_list['record_count']);
	    Tpl::assign('page_count',      $cus_log_list['page_count']);
	    $sort_flag  = sort_flag($cus_log_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('cus_log.htm'), '', array('filter' => $cus_log_list['filter'], 'page_count' => $cus_log_list['page_count']));
	}

	/**
     * @return 问答分类列表
     */
    public function ask_class_list() {
    	/* 权限的判断 */
	    admin_priv('askclass');
		$class_list = $this->get_class_list();
		Tpl::assign('full_page', 1);
		Tpl::assign('ur_here', L('askclass'));
	    Tpl::assign('class_list',      $class_list['list']);
	    Tpl::assign('filter',          $class_list['filter']);
	    Tpl::assign('record_count',    $class_list['record_count']);
	    Tpl::assign('page_count',      $class_list['page_count']);
	    $sort_flag  = sort_flag($class_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
		Tpl::assign('action_link', array('href' => 'index.php?act=askset&op=ask_class_add', 'text' =>L('addclass')));
	    Tpl::display('ask_class.htm');
	}

	/**
     * @return 问答分类列表排序、分页、查询
     */
    public function ask_class_query() {
    	admin_priv('askclass');
	    $class_list = $this->get_class_list();
		Tpl::assign('ur_here', L('askclass'));
	    Tpl::assign('class_list',      $class_list['list']);
	    Tpl::assign('filter',          $class_list['filter']);
	    Tpl::assign('record_count',    $class_list['record_count']);
	    Tpl::assign('page_count',      $class_list['page_count']);
	    $sort_flag  = sort_flag($class_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);

	    make_json_result(Tpl::fetch('ask_class.htm'), '',
	        array('filter' => $class_list['filter'], 'page_count' => $class_list['page_count']));
	}

	/**
     * @return 问答分类添加页面
     */
    public function ask_class_add() {
	    Tpl::assign('ur_here', L('addclass'));
	    Tpl::assign('form_op', 'ask_class_insert');
	    Tpl::assign('action_link', array('href' => 'index.php?act=askset&op=ask_class_list', 'text' => L('askclass')));
	    Tpl::display('ask_class_into.htm');
	}

	/**
     * @return 插入问答分类到数据库
     */
    public function ask_class_insert() {
    	admin_priv('askclass');
	    $class_name = empty($_REQUEST['class_name']) ? '' : trim($_REQUEST['class_name']);
		$class_cent = empty($_REQUEST['class_cent']) ? '' : $_REQUEST['class_cent'];
		$sess = $this->admin_info;
		$admin_id = $sess['user_id'];
		$new_times = gmtime();
		$data['user_id'] = $admin_id;
		$data['types'] = '2';
		$data['class_name'] = $class_name;
		$data['add_time'] = $new_times;
		$data['class_cent'] = $class_cent;
		$model = Model('askset');
		$res = $model->insert_que_class($data);
	    admin_log($class_name, 'add', 'que_class');
	    header("Location:index.php?act=askset&op=ask_class_list");
    }

	/**
     * @return 问答分类编辑
     */
    public function ask_class_edit() {
    	admin_priv('askclass');
	    $class_id = $_REQUEST['classid'];
	    $where= array('class_id'=>$class_id);
	    $model = Model('askset');
        $classinfo = $model->select_que_class_info('*',$where);
	    Tpl::assign('ur_here', L('editclass'));
	    Tpl::assign('form_op', 'ask_class_update');
	    Tpl::assign('classinto', $classinfo);
	    Tpl::assign('action_link', array('href' => 'index.php?act=askset&op=ask_class_list', 'text' => L('askclass')));
	    Tpl::display('ask_class_edit.htm');
	}

	/**
	 * @return 问答分类编辑入库
	 * @param  $name
	 * @param  $id
	 * @return void
	 */
	public function ask_class_update(){
		admin_priv('askclass');
		$id = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
	    $class_name = empty($_REQUEST['class_name']) ? '' : trim($_REQUEST['class_name']);
		$class_cent = empty($_REQUEST['class_cent']) ? '' : $_REQUEST['class_cent'];
		$sess = $this->admin_info;
		$admin_id = $sess['user_id'];
		$data['user_id'] = $admin_id;
		$data['types'] = '2';
		$data['class_name'] = $class_name;
		$data['class_cent'] = $class_cent;
		$where['class_id'] = $id;
		$res = Model('askset')->update_que_class_info($data,$where);
	    admin_log($class_name, 'update', 'que_class');
	    header("Location:index.php?act=askset&op=ask_class_list");
	}

    /**
     * @return 问答分类删除
     */
    public function ask_class_remove() {
    	admin_priv('askclass');
	    $id = intval($_GET['id']);
	    /* 检查是否存在 */
	    $where= array('class_id'=>$id);
	    $model = Model('askset');
        $res = $model->select_que_ans_info('ask_id',$where);
		if(!empty($res)){
            make_json_result('', '该分类有问答，不能删除',array('error'=>1));
		}else{
			$where = "class_id = '$id'";
	    	$result = $model->delete_que_class($where); 
			if($result){
				admin_log($id, 'remove', 'que_class');
		        $class_list = $this->get_class_list();
				Tpl::assign('ur_here', L('askclass'));
			    Tpl::assign('class_list',      $class_list['list']);
			    Tpl::assign('filter',          $class_list['filter']);
			    Tpl::assign('record_count',    $class_list['record_count']);
			    Tpl::assign('page_count',      $class_list['page_count']);
			    $sort_flag  = sort_flag($class_list['filter']);
			    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
				make_json_result(Tpl::fetch('ask_class.htm'), '',
		        array('filter' => $class_list['filter'], 'page_count' => $class_list['page_count']));
				
			}else{
				$link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            	showMessage('删除问答分类失败', $link);
			}
		}

    }

	/**
	 * @return  获取问答分类列表记录
	 * @return  array
	 */
	private function get_class_list(){
		$result = get_filter();
    	$askset = Model('askset');
    	if ($result === false){
    		$filter = array();
    		$where = '';
		    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'al.class_id' : trim($_REQUEST['sort_by']);
		    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	 		$filter['record_count'] = $askset->get_que_class_count('');
		    $filter = page_and_size($filter);
		    $sql  = 'SELECT  al.*, u.user_name '.
		    		'FROM ' .Model()->tablename('que_class'). ' AS al '.
					'LEFT JOIN ' .Model()->tablename('admin_user'). ' AS u ON u.user_id = al.user_id '.
					'WHERE 1 '.$where.' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
	        $filter['keyword'] = stripslashes($filter['keyword']);
	        set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        if(!empty($row)){
        	foreach($row as $key=>$value){
		        $row[$key]['add_time'] = local_date("Y-m-d", $value['add_time']);
				$fenid = $value['class_id'];
				$row[$key]['counts'] = $askset->get_que_ans_count("class_id = '$fenid'");
		    }
        }	    
	    $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	    return $arr;
	}

    /**
	 * @return 获取客服日志列表记录
	 * @return array
	 */
    private function get_cus_log() {
    	$result = get_filter();
    	$askset = Model('askset');
    	if ($result === false){
    		$filter = array();
    		$where = '';
			$filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
			if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1){
                $filter['keyword'] = $filter['keyword'];
            }
			if(!empty($filter['keyword'])){
				$where = "users.alias like '%".$filter['keyword']."%' ";
				$wheres = "AND users.alias like '%".$filter['keyword']."%' ";
			}
		    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'cus_ser_log.log_id' : trim($_REQUEST['sort_by']);
		    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	 		$filter['record_count'] = $askset->get_cus_log_users_count($where);
		    $filter = page_and_size($filter);
		    $sql  = 'SELECT cus_ser_log.*, users.alias '.
		    		'FROM ' .Model()->tablename('cus_ser_log'). ' AS cus_ser_log '.
					'LEFT JOIN ' .Model()->tablename('users'). ' AS users ON users.user_id = cus_ser_log.user_id '.
					'WHERE 1 '.$wheres.' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
	        $filter['keyword'] = stripslashes($filter['keyword']);
	        set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        if(!empty($row)){
        	foreach($row as $key=>$value){
		        $row[$key]['log_time'] = local_date("Y-m-d", $value['log_time']);
		    }
        }
	    $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	    return $arr;
    }

    /**
     * 获取问答列表
     * @access  private
	 * @return  array
	 */
    private function get_ask_list() {
    	$result = get_filter();
    	if ($result === false){
    		$filter = array();
    		$where = '';
			$class_id = empty($_REQUEST['class_id']) ? '' : trim($_REQUEST['class_id']);
			$filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
			if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1){
                $filter['keyword'] = $filter['keyword'];
            }
			if(!empty($class_id)){
				$where = " AND que_ans.class_id = '$class_id' ";
				if(!empty($filter['keyword'])){
					$where .= " AND (que_ans.questions like '%".$filter['keyword']."%' OR que_ans.answers like '%".$filter['keyword']."%' )";
				}
			}else{
				if(!empty($filter['keyword'])){
					$where = " AND que_ans.questions like '%". $filter['keyword']."%' OR que_ans.answers like '%".$filter['keyword']."%' ";
				}else{
					$where = '';
				}
			}
		    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'que_ans.ask_id' : trim($_REQUEST['sort_by']);
		    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
			$askset = Model('askset');
	 		$filter['record_count'] = $askset->get_que_ans_count($where);
		    $filter = page_and_size($filter);
		    $sql  = 'SELECT que_ans.*, qc.class_name,qc.class_cent '.
		    		'FROM ' .Model()->tablename('que_ans'). ' AS que_ans '.
					'LEFT JOIN ' .Model()->tablename('que_class'). ' AS qc ON que_ans.class_id = qc.class_id '.
					'WHERE 1 '.$where.' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
	        $filter['keyword'] = stripslashes($filter['keyword']);
	        set_filter($filter, $sql);
    	}else{
    		$sql    = $result['sql'];
            $filter = $result['filter'];
    	}
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
	    $model = Model('askset');
	    if(!empty($row)){
	    	foreach($row as $key=>$value){
		        $row[$key]['add_time'] = local_date("Y-m-d", $value['add_time']);
				$user_id = $value['user_id'];
				if($value['types'] == 2 ){
					$where = array('user_id' => $user_id);
	       			$user_name = Model('admin')->select_admin_info('user_name',$where);
					$row[$key]['user_name'] = $user_name['user_name'];
				}else if($value['types'] == 1){
					$where = array('user_id' => $user_id);
	       			$user_name = Model('users')->select_users_info('alias',$where);
					$row[$key]['user_name'] = $user_name['alias'];
				}else{
					$row[$key]['user_name'] = '无名称';
				}
		    }
	    }	    
	    $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	    return $arr;
	   
    }

    /**
     * @return 添加或者编辑问答时获取问答分类信息
     * @param $ask_id int 默认为0，添加问答时使用，分类信息下拉框第一个分类被选择。
     * 大于0，编辑问答时使用，代表当前问答的id标识，分类信息下拉框当前问答所属分类被选择。
     * @return  array
     */
    private function get_classlei($ask_id = 0){
        $classlist = array();
        $model = Model('askset');
        $row = $model->get_que_class_list('*','1');
        if($ask_id > 0 ){
            $where = array('ask_id'=>$ask_id);
            $class_id = $model->select_que_ans_info('class_id',$where);
        }else{
            $class_id = 0;
        }
        if(!empty($row)){
	        foreach($row as $k=>$v){
	            $classlist[$v['class_id']] = htmlspecialchars($v['class_name']);
	        }
    	}
        return array('classlist' => $classlist, 'classid' => $class_id['class_id']);
    }
}