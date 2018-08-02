<?php

/**
 * 淘玉php 后台大师管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台大师管理类
 * $Id: master.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class masterControl extends BaseControl {
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('master,adminlogs,calendar');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 大师列表
     */
    public function lists() {
    	/* 权限的判断 */
        admin_priv('master_list');
        $master_list = $this->get_master_lists();
	    Tpl::assign("keywords",$master_list['keywords']);
	    Tpl::assign("newpage", $master_list['newpage']);
	    Tpl::assign("addpage", $master_list['addpage']);
	    Tpl::assign("lowpage", $master_list['lowpage']);
	    Tpl::assign("pagesize",  $master_list['pagesize']);
	    Tpl::assign("totpage", $master_list['totpage']);
	    Tpl::assign("len", $master_list['len']);
	    Tpl::assign("res", $master_list['list']);
	    Tpl::display("master_list.htm");
    }

    /**
     * @return 查看大师介绍
     */
    public function show() {
    	/* 权限的判断 */
        admin_priv('master_list');
		$id = $_POST['id'];
	    $master = Model('master');
	    $where = array('id'=>$id);
	    $master_detail = $master->select_master_info('master_detail',$where);
	    if($master_detail != ""){
	        $data['res'] = $master_detail ;
	    }else{
	        $data['res'] = "";
	    }
	    echo json_encode($data);
	}
	/**
     * @return 查看大师简介
     */
	public function show1() {
		/* 权限的判断 */
        admin_priv('master_list');
		$id = $_POST['id'];
	    $master = Model('master');
	    $where = array('id'=>$id);
	    $master_summary = $master->select_master_info('master_summary',$where);
	    if($master_summary != ""){
	        $data['res'] = $master_summary;
	    }else{
	        $data['res'] = "";
	    }
	    echo json_encode($data);
	}

	/**
     * @return 编辑大师
     */
	public function edit() {
		/* 权限的判断 */
        admin_priv('master_list');
		$id = $_GET['id'];
	    $master = Model('master');
	    $where = array('id'=>$id);
	    $master_info = $master->select_master_info('*',$where);
	    $master_type =$master->get_master_type_list();
	    Tpl::assign("master_info", $master_info);
	    Tpl::assign("master_type", $master_type);
	    create_html_editor('artistic',htmlspecialchars_decode($master_info['artistic']));
	    Tpl::display("master_update_master.htm");
	}

	/**
     * @return 编辑大师入库
     */
	public function update() {
		/* 权限的判断 */
        admin_priv('master_list');
		$id = $_GET['id'];
	    $artistic = $_POST['artistic'];
	    $master_name = $_POST['master_name'];
		$types_num = $_POST['types_num'];
	    $master_summary = $_POST['master_summary'];
	    $master_detail = $_POST['master_detail'];
	    $type = $_POST['type'];
	    $pic = $_FILES['head_portrait'];
	    $master = Model('master');
	    if ($pic['error'] == "4") {
	    	 /*格式化数据*/
	        $data = array(
	            'types_num'=>$types_num,
	            'name'=>$master_name,
	            'master_summary'=>$master_summary,
	            'master_detail'=>$master_detail,
	            'type_id'=>$type,
	            'artistic'=>$artistic,
	        );
	    } else {
 			$uploaddir = 'bdimages/master/'.date('Ym');
 			$res = upload_oss_img($pic,$uploaddir);
 			$path = $res['url'];
 			$where = array('id'=>$id);
	        $oldpath = $master->select_master_info('head_portrait',$where);
	        if ($oldpath['head_portrait']) {
	            ossdeleteObjects($oldpath);
	        }
	        $data = array(
	            'types_num'=>$types_num,
	            'name'=>$master_name,
	            'master_summary'=>$master_summary,
	            'master_detail'=>$master_detail,
	            'type_id'=>$type,
	            'artistic'=>$artistic,
	            'head_portrait'=>$path,
	        );
	    }
	    $where['id'] = $id;
	    $res = $master->update_master_info($data,$where);
	    if ($res) {
	    	admin_log($master_name,'edit', 'master');
	        header("Location:index.php?act=master&op=lists");
	    } else {
	        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('edit_master_failed',''), $link);
	    }
	  
	}

	/**
     * @return 删除大师
     */
	public function remove() {
		/* 权限的判断 */
        admin_priv('master_add');
		$id = $_POST['id'];
		$master = Model('master');
		/*删除大师图片*/
		$where = array('id'=>$id);
	    $master_info = $master->select_master_info('name',$where);
	    $master_pic = $master->select_master_info('head_portrait',$where);

	      if ($master_pic['head_portrait']) {
	        ossdeleteObjects($master_pic);
	    }
	    /*删除大师奖项图片*/
	    $where = array('master_id'=>$id);
        $res3 =  $master->get_master_prize_list('prize_pic',$where);
	    foreach ($res3 as $v) {
	        if ($v['prize_pic']) {
	            ossdeleteObjects($v);
	        }
	    }
	    /*删除大师奖项*/
	    $where = " master_id = '" . intval($id) . "'";
        $bool = $master->delete_master_prize($where);
	    /*删除大师*/
	    $where = " id = '" . intval($id) . "'";
	    $bool = $master->delete_master($where);
	    if ($bool) {
	    	admin_log($master_info['name'],'remove', 'master');
	        $data['status'] = 1;
	    } else {
	        $data['status'] = -1;
	    }
	    echo json_encode($data);
	}

	/**
     * @return 添加大师
     */
	public function add() {
		/* 权限的判断 */
        admin_priv('master_add');
		$master = Model('master');
 		$master_type =$master->get_master_type_list();
	    create_html_editor('artistic',htmlspecialchars($res['artistic']));
	    Tpl::assign("master_type", $master_type);
	    Tpl::display("master_add.htm");
	}

	/**
     * @return 添加大师入库
     */
	public function insert() {
		/* 权限的判断 */
        admin_priv('master_add');
		$artistic = $_POST['artistic'];
	    $master_name = $_POST['master_name'];
		$types_num = $_POST['types_num'];
	    $master_summary = $_POST['master_summary'];
	    $master_detail = $_POST['master_detail'];
	    $type = $_POST['type'];
	    $head_portrait = $_FILES['head_portrait'];
	    if ($head_portrait['error'] != 4) {
 			$uploaddir = 'bdimages/master/'.date('Ym');
 			$res = upload_oss_img($head_portrait,$uploaddir);
 			$path = $res['url'];
	    } else {
	        $path = null;
	    }
	    $time = time();
	    $data = array(
            'name'=>trim($master_name),
            'master_summary'=>$master_summary,
            'master_detail'=>$master_detail,
            'head_portrait'=>$path,
            'list_image'=>null,
            'addtime'=>$time,
            'type_id'=>$type,
            'detail_img'=>null,
            'artistic'=>$artistic,
            'types_num'=>$types_num,
        );
        /*插入数据，返回自增长id */
        $master = Model('master');
        $new_id = $master->insert_master($data);
	    if ($new_id) {
	    	admin_log($master_name,'add', 'master');
	        header("Location:index.php?act=master&op=lists");
	    } else {
	        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('add_master_failed',''), $link);
	    }
	}

	/**
     * @return 大师分类列表
     */
	public function master_type_list() {
		/* 权限的判断 */
        admin_priv('master_type_list');
		$type_list = $this->get_master_type_lists();
	    Tpl::assign("keywords",$type_list['keywords']);
	    Tpl::assign("newpage", $type_list['newpage']);
	    Tpl::assign("addpage", $type_list['addpage']);
	    Tpl::assign("lowpage", $type_list['lowpage']);
	    Tpl::assign("pagesize", $type_list['pagesize']);
	    Tpl::assign("totpage", $type_list['totpage']);
	    Tpl::assign("len", $type_list['len']);
	    Tpl::assign("res", $type_list['list']);
	    Tpl::display("master_type_list.htm");
	}

	/**
     * @return 添加大师分类页面
     */
	public function master_type_add() {
		/* 权限的判断 */
        admin_priv('master_type_add');
		Tpl::display("master_add_type_list.htm");
	}

	/**
     * @return 添加大师分类入库
     */
	public function master_type_insert() {
		/* 权限的判断 */
        admin_priv('master_type_add');
		$rolename = $_POST['rolename'];
	    $pic = $_FILES['pic'];
	    $uploaddir = 'bdimages/master/'.date('Ym');
 		$res = upload_oss_img($pic,$uploaddir);
 		$path = $res['url'];
	    $time = time();
	    $data = array(
            'master_type'=>trim($rolename),
            'pic'=>$path,
            'addtime'=>$time,
        );
        /*插入数据，返回自增长id */
        $master = Model('master');
        $new_id = $master->insert_master_type($data);
	    if ($new_id) {
	    	/*记录操作*/
        	admin_log($rolename,'add', 'master_type');
	        header("Location:index.php?act=master&op=master_type_list");
	    } else {
	        $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage(L('add_type_failed',''), $link);
	    }
	}

	/**
     * @return 编辑大师分类
     */
	public function master_type_edit() {
		/* 权限的判断 */
        admin_priv('master_type_add');
		$id = $_GET['id'];
		$master = Model('master');
		$where = array('id'=>$id);
	    $master_type_info = $master->select_master_type_info('master_type',$where);
	    Tpl::assign("master_type_info", $master_type_info['master_type']);
	    Tpl::assign("type_id", $id);
	    Tpl::display("master_update_master_type.htm");
	}

	/**
     * @return 编辑大师分类入库
     */
	public function master_type_update() {
		/* 权限的判断 */
        admin_priv('master_type_add');
		$id = $_GET['id'];
	    $pic = $_FILES['pic'];
	    $rolename = $_POST['rolename'];
	    $master = Model('master');
	    if ($pic['error'] != 4) {
	        $master = Model('master');
	        $where = array('id'=>$id);
	    	$master_type_info = $master->select_master_type_info('pic',$where);
	        if ($master_type_info['pic']) {
	            ossdeleteObjects($master_type_info);
	        }
		    $uploaddir = 'bdimages/master/'.date('Ym');
	 		$res = upload_oss_img($pic,$uploaddir);
	 		$path = $res['url'];
		    $data = array(
	            'master_type'=>trim($rolename),
	            'pic'=>$path,
	        );
	        $where['id'] = $id;
	        $bool = $master->update_master_type($data, $where);
	        if ($bool) {
	        	/*记录操作*/
        		admin_log($rolename,'edit', 'master_type');
	            header("Location:index.php?act=master&op=success");
	        } else {
	            $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            	showMessage(L('edit_type_failed',''), $link);
	        }
	    }else{
	        $data = array(
	            'master_type'=>trim($rolename),
	        );
	        $where['id'] = $id;
	        $bool = $master->update_master_type($data, $where);
	        if ($bool) {
	        	/*记录操作*/
        		admin_log($rolename,'edit', 'master_type');
	            header("Location:index.php?act=master&op=success");
	        } else {
	            $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            	showMessage(L('edit_type_failed',''), $link);
	        }
	    }
	}

	/**
     * @return 删除大师分类       
     */
	public function master_type_remove() {
		/* 权限的判断 */
        admin_priv('master_type_add');
		$id = intval($_POST['id']);
		/*验证该分类下是否有大师*/
		$master = Model('master');
		$where = array('type_id'=>$id);
	    $master_info = $master->select_master_info('id',$where);
	    if (empty($master_info)) {
	        $where = array('id'=>$id);
	    	$master_type_info = $master->select_master_type_info('pic,master_type',$where);
	        if ($master_type_info) {
	            ossdeleteObjects($master_type_info);
	        }
	        $where = " id = '" . intval($id) . "'";
	        $bool = $master->delete_master_type($where);
	        if ($bool) {
	        	admin_log($master_type_info['master_type'],'remove', 'master_type');
	            $data['status'] = 1;
	        } else {
	            $data['status'] = -1;
	        }
	    } else {
	        $data['status'] = -2;
	    }
	    echo json_encode($data);
	}

	/**
     * @return 修改大师分类显示状态
     */
	public function update_type_status() {
		/* 权限的判断 */
        admin_priv('master_type_add');
		$id = $_POST['id'];
		$where = "id=$id";
		$status = $_POST['status'];
	    $bool = Model('master')->update_master_type(array('status'=>$status), $where);
	    if($bool){
	            $data['status'] = 1;
	        }else{
	            $data['status'] = 0;
	        }
	    echo json_encode($data);
	}

	/**
     * @return 修改大师显示状态
     */
	public function update_master_status() {
		/* 权限的判断 */
        admin_priv('master_add');
		$id = $_POST['id'];
		$where = "id=$id";
		$status = $_POST['status'];
	    $bool = Model('master')->update_master_info(array('status'=>$status), $where);
	    if($bool){
	            $data['status'] = 1;
	        }else{
	            $data['status'] = 0;
	        }
	    echo json_encode($data);
	}

	/**
     * @return 查看大师艺术成就
     */
	public function look_artistic() {
		/* 权限的判断 */
        admin_priv('master_list');
		$id = $_POST['id'];
		$master = Model('master');
		$where = array('id'=>$id);
	    $master_artistic = $master->select_master_info('artistic',$where);
	    if($master_artistic != ""){
	        $data['res'] = htmlspecialchars_decode($master_artistic['artistic']);
	    }else{
	        $data['res'] = "";
	    }
	    echo json_encode($data);
	}

	/**
     * @return 查看大师作品列表
     */
	public function works_list() {
		/* 权限的判断 */
        admin_priv('master_list');
        $id = $_GET['id'];
	    $master = Model('master');
	    $where = array('id'=>$id);
	    $master_info = $master->select_master_info('name',$where);
	    $name = $master_info['name'];
		$works_list = $this->get_master_works_lists();
	    Tpl::assign("keywords",$works_list['keywords']);
	    Tpl::assign("newpage", $works_list['newpage']);
	    Tpl::assign("addpage", $works_list['addpage']);
	    Tpl::assign("lowpage", $works_list['lowpage']);
	    Tpl::assign("pagesize", $works_list['pagesize']);
	    Tpl::assign("totpage", $works_list['totpage']);
	    Tpl::assign("len", $works_list['len']);
	    Tpl::assign("name", $name);
	    Tpl::assign("res", $works_list['list']);
	    Tpl::assign("id", $id);
	    Tpl::display("master_works_list.htm");
	}

	/**
     * @return 添加大师作品页面
     */
	public function add_works() {
		/* 权限的判断 */
        admin_priv('master_add');
		$id = $_GET['id'];
	    $cat_list = cat_list(0, 0, false);
	    foreach ($cat_list as $k => $v) {
	        $cat_list[$k]['cat_name'] = str_pad("", $v['level'] * 3, "---", STR_PAD_LEFT) . $v['cat_name'];
	    }
	    Tpl::assign('res', $cat_list);
	    Tpl::assign("master_id", $id);
	    Tpl::display("master_add_works.htm");
	}

	/**
     * @return 添加大师作品入库
     */
	public function insert_works() {
		/* 权限的判断 */
        admin_priv('master_add');
		$goods_id = $_POST['goods_id'];
	    $master_id = $_POST['master_id'];
	    $master = Model('master');
	    $where = "goods_id=$goods_id and master_id = $master_id";
	    $res = $master->select_master_goods_info('id',$where);
	    if ($res['id'] == "") {
	        $data = array(
	            'master_id'=>$master_id,
	            'goods_id'=>$goods_id,
	        );
	        $bool = $master->insert_master_goods($data);
	        if ($bool) {
	            $data['status'] = 1;
	        } else {
	            $data['status'] = -1;
	        }
	    } else {
	        $data['status'] = 0;
	    }
	    echo json_encode($data);

	}

	/**
     * @return 按分类、关键字、货号搜索作品，添加大师作品使用
     */
	public function search() {
		$page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
	    $pagesize = 15;
	    $limit = (($page-1)*$pagesize).",".$pagesize;
	    $cat_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : "";
	    $keywords = isset($_REQUEST['keywords']) ? $_REQUEST['keywords'] : "";
	    $works_id = isset($_REQUEST['works_id']) ? $_REQUEST['works_id'] : "";
	    $where = '';
	    if($cat_id){
	        $where .= "cat_ids_new = $cat_id";
	    }
	    if($keywords){
	        $where .= "( goods_name like '%$keywords%' or goods_brief like '%$keywords%' or goods_desc like '%$keywords%'  )";
	    }
	    if($works_id){
	        $where .= "goods_sn = '$works_id' ";
	    }
	    $master = Model('master');
	    $filed = "goods_id";
	    $goods_model = Model('goods');
	    $max  = $goods_model->get_goods_list($filed,$where);
	    $max = count($max); 
	    $fileds = "goods_id ,goods_sn , goods_name ,original_img";
	    $res = $goods_model->get_goods_list($fileds,$where,'goods_id desc',$limit);
	    foreach($res as $k=>$v){
	        $res[$k]['goods_thumb'] = get_imgurl_oss($v['original_img'],80,80);
	    }
	    $pages = ($page-1);
	    $pagex = $page+1;
	    if($pages < 0 ){
	        $pages = 0;
	    }

	    if($pagex > ceil($max/$pagex) ){
	        $pagex = ceil($max/$pagex);
	    }
	    $mags['keywords'] = $keywords;
	    $mags['cat_id'] = $cat_id;
	    $mags['works_id'] = $works_id;
	    $mags['yema']['pagex'] =$pagex;
	    $mags['yema']['pages'] =$pages;
	    $mags['res'] = $res;
	    echo json_encode($mags);
	}

	/**
     * @return 删除大师作品
     */
	public function work_remove() {
		/* 权限的判断 */
        admin_priv('master_add');
		$id= $_POST['id'];
	    $master_id = $_POST['master_id'];
	    $where = "master_id = $master_id and goods_id = '$id'";
	    $res = Model('master')->delete_master_goods($where); 
	    if($res){
	        $data['status'] = 1;
	    }else{
	        $data['status'] = -1 ;
	    }
	    echo json_encode($data);
	}

	/**
     * @return 修改大师作品状态
     */
	public function update_works_status() {
		$master_id = $_POST['id'];
	    $goods_id = $_POST['goods_id'];
	    $status = $_POST['status'];
	    if($master_id !="" && $status != "" && $goods_id !=""){
	        $where = "goods_id = $goods_id and master_id = $master_id";
	        $bool = Model('master')->update_master_goods_info(array('status'=>$status), $where);
	        if($bool){
	            $data['status'] = 1;
	        }else{
	            $data['status'] = 0;
	        }
	        echo json_encode($data);
	    }
	}

	/**
     * @return 查看大师奖项列表
     */
	public function prize_list() {
		/* 权限的判断 */
        admin_priv('master_list');
        $id = $_GET['id'];
	    $master = Model('master');
	    $where = array('id'=>$id);
	    $master_info = $master->select_master_info('name',$where);
	    $name = $master_info['name'];
		$prize_list = $this->get_master_prize_lists();
	    Tpl::assign("keywords",$prize_list['keywords']);
	    Tpl::assign("newpage", $prize_list['newpage']);
	    Tpl::assign("addpage", $prize_list['addpage']);
	    Tpl::assign("lowpage", $prize_list['lowpage']);
	    Tpl::assign("pagesize", $prize_list['pagesize']);
	    Tpl::assign("totpage", $prize_list['totpage']);
	    Tpl::assign("len", $prize_list['len']);
	    Tpl::assign("res", $prize_list['list']);
	    Tpl::assign("name", $name);
		Tpl::assign("id", $id);
	    Tpl::display("master_prize_list.htm");
	}

	/**
     * @return 添加大师奖项页面
     */
	public function add_prize() {
		$id = $_GET['id'];
	    Tpl::assign("id", $id);
	    Tpl::display("master_add_prize.htm");
	}

	/**
     * @return 添加大师奖项入库
     */
	public function insert_prize() { 
		$id = $_GET['id'];
	    $prize_describe = $_POST['prize'];
	    $pic = $_FILES['pic'];
	    $uploaddir = 'bdimages/master/'.date('Ym');
 		$res = upload_oss_img($pic,$uploaddir);
 		$path = $res['url'];
	    $field = array(
	    	'master_id'=>$id,
	    	'prize_pic'=>$path,
	    	'prize_describe'=>$prize_describe,
		);
	    $bool = Model('master')->insert_master_prize($field);
	    if ($bool) {
	        header("Location:index.php?act=master&op=success");
	    } else {
	        echo "添加失败";
	    }
	}

	/**
     * @return 删除大师奖项
     */
	public function remove_prize() {
		$id = $_POST['id'];
		$where = array('id'=>$id);
		$master = Model('master');
	    $prize_info = $master->select_master_prize_info('prize_pic',$where);
	    $path = $prize_info['prize_pic'];
	    if ($path) {
	        ossdeleteObjects($prize_info);
	    }
	    $bool = $master->delete_master_prize($where);
	    if ($bool) {
	        $data['status'] = 1;
	    } else {
	        $data['status'] = -1;
	    }
	    echo json_encode($data);
	}

	/**
     * @return 编辑大师奖项
     */
	public function edit_prize() {
		$id = $_GET['id'];
	    $where = array('id'=>$id);
		$master = Model('master');
	    $prize_info = $master->select_master_prize_info('*',$where);
	    Tpl::assign("id", $id);
	    Tpl::assign("res", $prize_info);
	    Tpl::display("master_update_prize.htm");
	}

	/**
     * @return 编辑大师奖项入库
     */
	public function update_prize() {
		$id = $_GET['id'];
	    $prize_describe = $_POST['prize'];
	    $prize_pic = $_FILES['pic'];
	    $where = array('id'=>$id);
		$master = Model('master');
		$prize_info = $master->select_master_prize_info('prize_pic',$where);
		$oldpath = $prize_info['prize_pic'];
	    if (!empty($prize_pic['tmp_name'])) {
	        if ($oldpath) {
	            ossdeleteObjects($prize_info);
	        }
	        $uploaddir = 'bdimages/master/'.date('Ym');
	 		$res = upload_oss_img($prize_pic,$uploaddir);
	 		$path = $res['url'];
	        $newpath = $path;
	    } else {
	        $newpath = $oldpath;
	    }
	    $data = array(
	        'prize_describe'=>$prize_describe,
	        'prize_pic'=>$newpath,
	    );
	    $where['id'] = $id;
	    $bool = Model('master')->update_master_prize_info( $data, $where);
	    if ($bool) {
	        header("Location:index.php?act=master&op=success");
	    } else {
	        echo "编辑失败";
	    }
	}

	/**
     * @return 编辑大师奖项状态
     */
	public function update_prize_status() {
	    $prize_id = $_POST['id'];
	    $status = $_POST['status'];
	    if($prize_id !="" && $status != ""){
	        $where = " id = $prize_id ";
	        $bool = Model('master')->update_master_prize_info(array('status'=>$status), $where);
	        if($bool){
	            $data['status'] = 1;
	        }else{
	            $data['status'] = 0;
	        }
	        echo json_encode($data);
	    }
	}

	/**
     * @return 操作成功提示页面
     */
	public function success() {
		Tpl::display("success.htm");
	}

	/**
     * @return 大师列表
     */
    private function get_master_lists() {
    	$keywords = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:'';
        if($keywords){
	        $where = "name like '%$keywords%' ";
	    }else{
	        $where = "";
	    }
	    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
	    if ($sort == "") {
	        $sort = "desc";
	        $order= "id desc ";
	        Tpl::assign("sort", $sort);
	    } elseif ($sort == "desc") {
	        $sort = "asc";
	        $order = "id asc";
	        Tpl::assign("sort", $sort);
	    } else {
	        $sort = "desc";
	        $order= "id desc";
	        Tpl::assign("sort", $sort);
	    }
	    /*分页参数*/
	    $pagesize = isset($_REQUEST['pagesize'])?$_REQUEST['pagesize']:"10";
	    $newpage = isset($_REQUEST['newpage'])?$_REQUEST['newpage']:"1";
	    if ($newpage <= 0) {
	        $newpage = 1;
	    }
	    $master = Model('master');
	    $res3 = $master->get_master_list('*','1');
	    $len = count($res3);
	    if($len <= 0){
	      $totpage = 0 ;
	      $newpage = 1;
	    }else{
	        $totpage = ceil($len / $pagesize);
	        if ($newpage > $totpage) {
	            $newpage = $totpage;
	        }
	    }
	    $offset = ($newpage - 1) * $pagesize;
	    /*分页获取数据*/
	    $limit = $offset.','.$pagesize;
	    $res = $master->get_master_list('*',$where,$order,$limit);
	    /*大师分类*/
	    $res1 = $master->get_master_type_list('*','1');
	    foreach ($res as $k => $v) {
	        $res[$k]['head_portrait'] = get_imgurl_oss($v['head_portrait'],80,80);
	        $res[$k]['addtime'] = date("Y-m-d H:i:s", $v['addtime']);
	        foreach ($res1 as $v1) {
	            if ($v['type_id'] == $v1['id']) {
	                $res[$k]['type_id'] = $v1['master_type'];
	            }
	        }
	    }
	    foreach($res as $k=>$c ){
	        $res[$k]['master_summary'] = mb_strlen($c['master_summary'], 'utf-8') > 12 ? mb_substr($c['master_summary'], 0, 12, 'utf-8').'......<br><a href="#"style="color:#1A9BD5;" >查看更多</a>' : $c['master_summary'];
	        $res[$k]['master_detail'] =  mb_strlen($c['master_detail'], 'utf-8') > 12 ? mb_substr($c['master_detail'], 0, 12, 'utf-8').'......<br><a href="#"style="color:#1A9BD5;" >查看更多</a>' : $c['master_detail'];
	    }
	    return array('list'=>$res,'keywords'=>$keywords,'newpage'=>$newpage
	    	,'addpage'=>$newpage+1,'lowpage'=>$newpage-1,'pagesize'=>$pagesize,'totpage'=>$totpage,'len'=>$len);
	}

	/**
     * @return 大师分类列表
     */
    private function get_master_type_lists() {
    	$keywords = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:"";
	    if($keywords){
	        $where = " master_type like '%$keywords%' ";
	    }else{
	        $where = "";
	    }
	    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
	    if ($sort == "") {
	        $sort = "desc";
	        $order = "id desc";
	        Tpl::assign("sort", $sort);
	    } elseif ($sort == "desc") {
	        $sort = "asc";
	        $order = " id asc";
	        Tpl::assign("sort", $sort);
	    } else {
	        $sort = "desc";
	        $order = " id desc";
	        Tpl::assign("sort", $sort);
	    }
	    $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : "10";
	    $newpage = isset($_REQUEST['newpage']) ? $_REQUEST['newpage'] : "1";
	    if ($newpage <= 0) {
	        $newpage = 1;
	    }
	    $master = Model('master');
 		$res3 = $master->get_master_type_list('*',$where);
	    $len = count($res3);
	    if($len <= 0){
	        $totpage = 0 ;
	        $newpage = 1;
	    }else{
	        $totpage = ceil($len / $pagesize);
	        if ($newpage > $totpage) {
	            $newpage = $totpage;
	        }
	    }
	    $offset = ($newpage - 1) * $pagesize;
	    $limit = $offset.','.$pagesize;
	    $res = $master->get_master_type_list('*',$where,$order,$limit);
	    foreach ($res as $k => $v) {
	        $res[$k]['pic'] = get_imgurl_oss($v['pic'],80,80);
	        $res[$k]['addtime'] = date("Y-m-d H:i:s", $v['addtime']);
	    }
	    return array('list'=>$res,'keywords'=>$keywords,'newpage'=>$newpage
	    	,'addpage'=>$newpage+1,'lowpage'=>$newpage-1,'pagesize'=>$pagesize,'totpage'=>$totpage,'len'=>$len);
    }

    /**
     * @return 大师作品列表
     */
    private function get_master_works_lists() {
    	$id = $_GET['id'];
	    $master = Model('master');
	    $where = array('id'=>$id);
	    $master_info = $master->select_master_info('name',$where);
	    $name = $master_info['name'];
	    $keywords = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:"";
	    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
	    if ($sort == "") {
	        $sort = "desc";
	        $order = "goods_id desc";
	        Tpl::assign("sort", $sort);
	    } elseif ($sort == "desc") {
	        $sort = "asc";
	        $order = "goods_id asc";
	        Tpl::assign("sort", $sort);
	    } else {
	        $sort = "desc";
	        $order = "goods_id desc";
	        Tpl::assign("sort", $sort);
	    }
	    $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : "10";
	    $newpage = isset($_REQUEST['newpage']) ? $_REQUEST['newpage'] : "1";
	    if ($newpage <= 0) {
	        $newpage = 1;
	    }
	    $where = array('master_id'=>$id);
    	$res = $master->get_master_goods_list('goods_id',$where);
	    foreach ($res as $key => $value) {
		   	$arr[] = $value['goods_id'];
		}
		$str = implode(",", $arr);
		if($str){
			$param = "goods_id in ($str)";
			if($keywords){
	        	$param .= " and goods_name like '%$keywords%' ";
		    }else{
		        $param  .= "";
		    }
			$goods_model = model('goods');
			$goods = $goods_model->get_goods_list('goods_id',$param);
			$len = count($goods);
		    if($len){
		    	if($len <=0 ){
			        $newpage = 1;
			        $totpage = 0;
			    }else{
			        $totpage = ceil($len / $pagesize);
			        if ($newpage > $totpage) {
			            $newpage = $totpage;
			        }
			    }
			    $offset = ($newpage - 1) * $pagesize;
			    /*分页获取数据*/
		    	$limit = $offset.','.$pagesize;
		    	if($limit<=0){
		    		$limit = 0;
		    	}
			    $res2 = $goods_model->get_goods_list('*',$param, $order, $limit);
				foreach($res2 as $v){
				    $arr[] = $v['goods_id'];
				}
				$str = implode(",", $arr);
				$where = "master_id = $id and goods_id in ($str)";
				$filed = 'status,goods_id';
				$crr = $master->get_master_goods_list($filed,$where);
				foreach($crr as $b){
				    $drr[$b['goods_id']] = $b['status'];
				}
				foreach($res2 as $k1=>$v){
				    $res2[$k1]['original_img'] = get_imgurl_oss($v['original_img'],80,80);
				    foreach($drr as $k=>$v1){
				        if($res2[$k1]['goods_id'] == $k){
				            $res2[$k1]["status"] = $v1;
				        }
				    }
				}
				return array('list'=>$res2,'keywords'=>$keywords,'newpage'=>$newpage
	    		,'addpage'=>$newpage+1,'lowpage'=>$newpage-1,'pagesize'=>$pagesize,'totpage'=>$totpage,'len'=>$len,'name'=>$name,'id'=>$id);
			}else{
				$len = 0;
			}
		}
    }

    /**
     * @return 大师奖项列表
     */
    private function get_master_prize_lists(){
    	$id = $_GET['id'];
	    $master = Model('master');
	    $where = array('id'=>$id);
	    $master_info = $master->select_master_info('name',$where);
	    $name = $master_info['name'];
    	$keywords = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:"";
	    if($keywords){
	        $where = " and prize_describe like '%$keywords%' ";
	    }else{
	        $where = "";
	    }
	    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
	    if ($sort == "") {
	        $sort = "desc";
	        $order = "id desc";
	        Tpl::assign("sort", $sort);
	    } elseif ($sort == "desc") {
	        $sort = "asc";
	        $order = "id asc";
	        Tpl::assign("sort", $sort);
	    } else {
	        $sort = "desc";
	        $order = "id desc";
	        Tpl::assign("sort", $sort);
	    }
	    $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : "10";
	    $newpage = isset($_REQUEST['newpage']) ? $_REQUEST['newpage'] : "1";
	    if ($newpage <= 0) {
	        $newpage = 1;
	    }
	    $where = "master_id=$id".$where;
    	$res3 = $master->get_master_prize_list('*',$where);
    	$len = count($res3);
	    if($len <= 0){
	        $newpage = 1;
	        $totpage = 0;
	    }else{
	        $totpage = ceil($len / $pagesize);
	        if ($newpage > $totpage) {
	            $newpage = $totpage;
	        }
	    }
	    $offset = ($newpage - 1) * $pagesize;
	    /*分页获取数据*/
		$limit = $offset.','.$pagesize;
		$res = $master->get_master_prize_list('*',$where, $order, $limit);
	    foreach($res as $k=>$v){
	        $res[$k]['prize_pic'] = get_imgurl_oss($v['prize_pic'],80,80);
	    }
	    return array('list'=>$res,'keywords'=>$keywords,'newpage'=>$newpage
	    		,'addpage'=>$newpage+1,'lowpage'=>$newpage-1,'pagesize'=>$pagesize,'totpage'=>$totpage,'len'=>$len,'name'=>$name,'id'=>$id);

    }
}