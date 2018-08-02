<?php

/**
 * 淘玉php 后台文章分类管理程序
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台文章分类管理程序
 * $Id: articlecat.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class articlecatControl extends BaseControl {
    
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('articlecat,article');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 文章分类列表函数 
     */
    public function lists() {
        /* 权限的判断 */
        admin_priv('article_manage');
        $articlecat = article_cat_list(0, 0, false);
        foreach ($articlecat as $key => $cat){
            $articlecat[$key]['type_name'] = L('type_name.'.$cat['cat_type']);
            if(!empty($cat['file_img'])){
                if(strpos($cat['file_img'], 'http') === false){
                    $articlecat[$key]['img_urls'] = get_imgurl_oss($cat['file_img'], 50, 50);
                }else{
                    $articlecat[$key]['img_urls'] = $cat['file_img'];
                }
            }else{
                $articlecat[$key]['img_urls'] = "https://www.taoyumall.com/1.jpg";
            }
        }
        Tpl::assign('ur_here',     L('02_articlecat_list'));
        Tpl::assign('action_link', array('text' => L('articlecat_add'), 'href' => 'index.php?act=articlecat&op=add'));
        Tpl::assign('full_page',   1);
        Tpl::assign('articlecat',        $articlecat);
        Tpl::display('articlecat_list.htm');
    }
    
    /**
     * @return 文章分类列表查询-排序-分页函数
     */
    public function query() {
        $articlecat = article_cat_list(0, 0, false);
        foreach ($articlecat as $key => $cat){
            $articlecat[$key]['type_name'] = L('type_name.'.$cat['cat_type']);
        }
        Tpl::assign('articlecat', $articlecat);
        make_json_result(Tpl::fetch('articlecat_list.htm'));
    }
    
    /**
     * @return 进入添加分类页面
     */
    public function add() {
        /* 权限判断 */
        admin_priv('article_cat_add');
        Tpl::assign('cat_select',  article_cat_list(0));
        Tpl::assign('ur_here',     L('articlecat_add'));
        Tpl::assign('action_link', array('text' => L('02_articlecat_list'), 'href' => 'index.php?act=articlecat&op=lists'));
        Tpl::assign('form_act', 'articlecat');
        Tpl::assign('form_op', 'insert');
        Tpl::display('articlecat_info.htm');
    }
    
    /**
     * @return 添加分类页面数据到数据库
     */
    public function insert() {
        /* 权限判断 */
        $allow_img_types = '|GIF|JPG|PNG|';
        admin_priv('article_cat_add');
        /*检查分类名是否重复*/
        $articlemodel = Model('article');
        $wheres['cat_name'] =  $_POST['cat_name'];
        $is_only = $articlemodel->select_article_cat_info('cat_name', $wheres);
        if (!empty($is_only)){
            showMessage(sprintf(L('catname_exist'), stripslashes($_POST['cat_name'])));
        }
        if($_POST['path_name'] != ''){
            $wheres1['path_name'] =  $_POST['path_name'];
            $is_only = $articlemodel->select_article_cat_info('path_name', $wheres1);
            if (!empty($is_only)){
                showMessage(sprintf(L('path_name_err'), stripslashes($_POST['path_name'])));
            }
        }
        $cat_type = 1;
        if ($_POST['parent_id'] > 0){
            $wheres2['cat_id'] = $_POST['parent_id'];
            $p_cat_type = $articlemodel->select_article_cat_info('cat_type',$wheres2);
            if ($p_cat_type['cat_type'] == 2 || $p_cat_type['cat_type'] == 3 || $p_cat_type['cat_type'] == 5){
                showMessage(L('not_allow_add'));
            }else if ($p_cat_type['cat_type'] == 4){
                $cat_type = 5;
            }
        }
        
        /* 获取图片地址 */
        $img_url = '';
       
        if ((isset($_FILES['img_file']['error']) && $_FILES['img_file']['error'] == 0) || (!isset($_FILES['img_file']['error']) && isset($_FILES['img_file']['tmp_name']) && $_FILES['img_file']['tmp_name'] != 'none')){
            // 检查图片格式
            if (!check_file_type($_FILES['img_file']['tmp_name'], $_FILES['img_file']['name'], $allow_img_types)){
                showMessage(L('invalid_file'));
            }
            // 复制图片
            $path = 'bdimages/article/'.date('Ymd');
            $res = upload_oss_img($_FILES['img_file'], $path, 0);
            if ($res['status']){
                $img_url = $res['url'];
                $insert['file_img'] = $img_url;
            }
        }
        if ($img_url == ''){
            $insert['file_img'] = $_POST['img_url'];
        }
           
        $insert['update_time'] = gmtime();
        $insert['cat_name'] = $_POST['cat_name'];
        $insert['cat_type'] = $cat_type;
        $insert['cat_desc'] = $_POST['cat_desc'];
        $insert['keywords'] = $_POST['keywords'];
        $insert['parent_id'] = $_POST['parent_id'];
        $insert['sort_order'] = $_POST['sort_order'];
        $insert['show_in_nav'] = $_POST['show_in_nav'];
        $insert['path_name'] = $_POST['path_name'];
        $articlemodel->insert_article_cat($insert);
        admin_log($_POST['cat_name'],'add','articlecat');
        $link[0]['text'] = L('continue_add');
        $link[0]['href'] = 'index.php?act=articlecat&op=add';
        $link[1]['text'] = L('back_list');
        $link[1]['href'] = 'index.php?act=articlecat&op=lists';
        showMessage($_POST['cat_name'].L('catadd_succed'),$link);
    }
    
    /**
     * @return 进入编辑分类页面
     */
    public function edit() {
        /* 权限判断 */
        admin_priv('article_cat_add');
        $cat_ids = $_REQUEST['cat_id'];
        $articlemodel = Model('article');
        $files = "cat_id, cat_name, cat_type, cat_desc, show_in_nav, keywords, parent_id,sort_order, path_name,file_img";
        $wheres['cat_id'] = $cat_ids;
        $cat = $articlemodel->select_article_cat_info($files,$wheres);
       
        if ($cat['cat_type'] == 2 || $cat['cat_type'] == 3 || $cat['cat_type'] ==4){
            Tpl::assign('disabled', 1);
        }
        $options    =   article_cat_list(0, $cat['parent_id'], false);
        $select     =   '';
        $selected   =   $cat['parent_id'];
        foreach ($options as $var){
            if ($var['cat_id'] == $cat_ids){
                continue;
            }
            $select .= '<option value="' . $var['cat_id'] . '" ';
            $select .= ' cat_type="' . $var['cat_type'] . '" ';
            $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0){
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars($var['cat_name']) . '</option>';
        }
        unset($options);
        Tpl::assign('cat', $cat);
        Tpl::assign('cat_select',  $select);
        Tpl::assign('ur_here',     L('articlecat_edit'));
        Tpl::assign('action_link', array('text' => L('02_articlecat_list'), 'href' => 'index.php?act=articlecat&op=lists'));
        Tpl::assign('form_act', 'articlecat');
        Tpl::assign('form_op', 'update');
        Tpl::display('articlecat_info.htm');
    }
    
    /**
     * @return 编辑分类页面数据到数据库
     */
    public function update() {
        /* 权限判断 */
        admin_priv('article_cat_add');
        /*检查重名*/
        $allow_img_types = '|GIF|JPG|PNG|';
        $cat_id = $_POST['cat_id'];
        $articlemodel = Model('article');
        if ($_POST['cat_name'] != $_POST['old_catname']){
            $wheres['cat_name'] =  $_POST['cat_name'];
            $wheres['cat_id'] =  $cat_id;
            $is_only = $articlemodel->select_article_cat_info('cat_name', $wheres);
            if (!empty($is_only)){
                showMessage(sprintf(L('catname_exist'), stripslashes($_POST['cat_name'])));
            }
        }
        /* 代码增加_start  By  www.taoyumall.com */
        if($_POST['path_name'] != '' && $_REQUEST['op'] =='insert'){
            $wheres1['path_name'] =  $_POST['path_name'];
            $wheres1['cat_id'] =  $cat_id;
            $is_only = $articlemodel->select_article_cat_info('path_name', $wheres1);
            if (!empty($is_only)){
                showMessage(sprintf(L('path_name_err'), stripslashes($_POST['path_name'])));
            }
        }
	/* 代码增加_start  By  www.taoyumall.com */
        if(!isset($_POST['parent_id'])){
            $_POST['parent_id'] = 0;
        }
        $wheres2['cat_id'] =  $cat_id;
        $row = $articlemodel->select_article_cat_info('cat_type, parent_id',$wheres2);
        $cat_type = $row['cat_type'];
        if ($cat_type == 3 || $cat_type ==4){
            $_POST['parent_id'] = $row['parent_id'];
        }
        /* 检查设定的分类的父分类是否合法 */
        $child_cat = article_cat_list($_POST['id'], 0, false);
        if (!empty($child_cat)){
            foreach ($child_cat as $child_data){
                $catid_array[] = $child_data['cat_id'];
            }
        }
//        if (in_array($_POST['parent_id'], $catid_array)){
//            showMessage(sprintf(L('parent_id_err'), stripslashes($_POST['cat_name'])));
//        }
        if ($cat_type == 1 || $cat_type == 5){
            if ($_POST['parent_id'] > 0){
                $wheres3['parent_id'] = $_POST['parent_id'];
                $p_cat_type = $articlemodel->select_article_cat_info('cat_type',$wheres2);
                if ($p_cat_type['cat_type'] == 4){
                    $cat_type = 5;
                }else{
                    $cat_type = 1;
                }
            }else{
                $cat_type = 1;
            }
        }
        
        /* 取得图片地址 */
        $img_url = '';
        if ((isset($_FILES['img_file']['error']) && $_FILES['img_file']['error'] == 0) || (!isset($_FILES['img_file']['error']) && isset($_FILES['img_file']['tmp_name']) && $_FILES['img_file']['tmp_name'] != 'none')){
            // 检查图片格式
             if (!check_file_type($_FILES['img_file']['tmp_name'], $_FILES['img_file']['name'], $allow_img_types)){
                showMessage(L('invalid_file'));
            }
            // 复制图片
            $path = 'bdimages/article/'.date('Ymd');
            $res = upload_oss_img($_FILES['img_file'], $path, 0);
            if ($res['status']){
                $img_url = $res['url'];
            }
        }
        if ($img_url == ''){
            $img_url = $_POST['img_url'];
        }
        var_dump();
        $wheres4['cat_id'] = $cat_id;
        $dat = $articlemodel->select_article_cat_info('cat_name, show_in_nav',$wheres4);
        $parm['cat_name'] = $_POST['cat_name'];
        $parm['update_time'] = gmtime();
        $parm['file_img'] = $img_url;
        $parm['cat_desc'] = $_POST['cat_desc'];
        $parm['keywords'] = $_POST['keywords'];
        $parm['parent_id'] = $_POST['parent_id'];
        $parm['cat_type'] = $cat_type;
        $parm['sort_order'] = $_POST['sort_order'];
        $parm['show_in_nav'] = $_POST['show_in_nav'];
        $result = $articlemodel->update_article_cat($parm,$wheres4);
        if (!empty($result)){
            $link[0]['text'] = L('back_list');
            $link[0]['href'] = 'index.php?act=articlecat&op=lists';
            $note = sprintf(L('catedit_succed'), $_POST['cat_name']);
            admin_log($_POST['cat_name'], 'edit', 'articlecat');
            showMessage($note,$link);
        }else{
            showMessage(L('编辑失败'));
        }
    }
    
    /**
     * @return 文章分类排序 Description
     */
    public function edit_sort_order(){
        check_authz_json('article_manage');
        $id    = intval($_POST['id']);
        $order = json_str_iconv(trim($_POST['val']));
        /* 检查输入的值是否合法 */
        if (!preg_match("/^[0-9]+$/", $order)){
            make_json_error(sprintf(L('enter_int'), $order));
        }else{
            $parms['sort_order'] = $order;
            $wheres['cat_id'] = $id;
            $result = Model('article')->update_article_cat($parms,$wheres);
            if ($result){
                make_json_result(stripslashes($order));
            }else{
                make_json_error(L('edit_fail'));
            }
        }
    }
    
    /**
     * @return 文章分类切换是否显示在导航栏 Description
     */
    public function toggle_show_in_nav(){
        check_authz_json('article_manage');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $articlemodel = Model('article');
        $where['cat_id'] = $id;
        $param['show_in_nav'] = $val;
        $result = $articlemodel->update_article_cat($param, $where);
        if($result){
            make_json_result($val);
        }else{
            make_json_error(L('edit_fail'));
        }
    }
    
    /**
     * @return 删除文章分类 Description
     */
    public function remove(){
        check_authz_json('article_manage_del');
        $id = intval($_GET['id']);
        $articlemodel = Model('article');
        $wheres['cat_id'] = $id;
        $cat_into = $articlemodel->select_article_cat_info('cat_type,cat_name',$wheres);
        if ($cat_into['cat_type'] == 2 || $cat_into['cat_type'] == 3 || $cat_into['cat_type'] ==4){
            /* 系统保留分类，不能删除 */
            make_json_error(L('not_allow_remove'));
        }
        $wheres1['parent_id'] = $id;
        $cat_counts = $articlemodel->get_article_cat_count($wheres1);
        if ($cat_counts > 0){
            /* 还有子分类，不能删除 */
            make_json_error(L('is_fullcat'));
        }
        /* 非空的分类不允许删除 */
        $article_counts = $articlemodel->get_article_count($wheres);
        if ($article_counts > 0){
            make_json_error(L('not_emptycat'));
        }else{
            $articlemodel->delete_article_cat($wheres);
            admin_log($cat_into['cat_name'], 'remove', 'category');
        }
        $url = 'index.php?act=articlecat&op=query&' . str_replace('op=remove', '', $_SERVER['QUERY_STRING']);
        ecs_header("Location: $url\n");
        exit;
    }
}
?>

