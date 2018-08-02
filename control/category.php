<?php

/**
 * 产品分类功能
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萤火虫 $
 * $Id: category.php  2018-04-07   萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class categoryControl extends BaseControl{

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('category');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 商品分类列表
     */
    public function cat_lists(){
        /* 获取分类列表 */
        $cat_list = cat_list(0, 0, false);
        foreach ($cat_list as $key => $cat){
            if(!empty($cat['cat_img'])){
                if(strpos($cat['cat_img'], 'http') === false){
                    $cat_list[$key]['img_urls'] = get_imgurl_oss($cat['cat_img'], 50, 50);
                }else{
                    $cat_list[$key]['img_urls'] = $cat['cat_img'];
                }
            }else{
                $cat_list[$key]['img_urls'] = "https://www.taoyumall.com/1.jpg";
            }
        }
        /* 模板赋值 */
        Tpl::assign('ur_here',      L('04_category_list'));
        Tpl::assign('action_link',  array('href' => 'index.php?act=category&op=cat_add', 'text' => L('cat_manage')));
        Tpl::assign('full_page',    1);
        Tpl::assign('cat_info',     $cat_list);
        Tpl::display('category_list.htm');
    }
    
    /**
     * @return 商品分类排序、分页、查询 Description
     */
    public function cat_query(){
        $cat_list = cat_list(0, 0, false);
        Tpl::assign('cat_info',     $cat_list);
        make_json_result(Tpl::fetch('category_list.htm'));
    }
    /**
     * @return 进入添加分类页面 Description
     */
    public function cat_add(){
        /* 权限检查 */
        admin_priv('cat_manage');
        /* 模板赋值 */
        Tpl::assign('ur_here',      L('cat_manage'));
        Tpl::assign('action_link',  array('href' => 'index.php?act=category&op=cat_lists', 'text' => L('04_category_list')));
        Tpl::assign('cat_select',   cat_list(0, 0, true));
        Tpl::assign('form_act',     'category');
        Tpl::assign('form_op',     'cat_insert');
        Tpl::assign('cat_info',     array('is_show' => 1));
        Tpl::display('category_info.htm');
    }
    
    /**
     * @return 插入分类数据到数据表中 Description
     */
    public function cat_insert(){
        /* 权限检查 */
        admin_priv('cat_manage');
        /* 初始化变量 */
        $allow_img_types = '|GIF|JPG|PNG|';
        /* 获取图片地址 */
        $img_url = '';
        if ((isset($_FILES['cat_img']['error']) && $_FILES['cat_img']['error'] == 0) || (!isset($_FILES['cat_img']['error']) && isset($_FILES['cat_img']['tmp_name']) && $_FILES['cat_img']['tmp_name'] != 'none')){
            // 检查图片格式
            if (!check_file_type($_FILES['cat_img']['tmp_name'], $_FILES['cat_img']['name'], $allow_img_types)){
                showMessage(L('invalid_file'));
            }
            // 复制图片
            $path = 'data/category_img';
            $res = upload_oss_img($_FILES['cat_img'], $path, 0);
            if ($res['status']){
                $img_url = $res['url'];
                $insert['cat_img'] = $img_url;
            }
        }
        if ($img_url == ''){
            $insert['cat_img'] = $_POST['cat_img_url'];
        }
        $insert['cat_name']   = !empty($_POST['cat_name'])     ? trim($_POST['cat_name'])     : '';
        $insert['path_name']   = !empty($_POST['path_name'])     ? trim($_POST['path_name'])     : '';
        $insert['parent_id']    = !empty($_POST['parent_id'])    ? intval($_POST['parent_id'])  : 0;
        $insert['sort_order']   = !empty($_POST['sort_order'])   ? intval($_POST['sort_order']) : 0;
        $insert['is_show']      = !empty($_POST['is_show'])      ? intval($_POST['is_show'])    : 0;
        $insert['show_in_nav']  = !empty($_POST['show_in_nav'])  ? intval($_POST['show_in_nav']): 0;
        $insert['keywords']     = !empty($_POST['keywords'])     ? trim($_POST['keywords'])     : '';
        $insert['cat_desc']     = !empty($_POST['cat_desc'])     ? $_POST['cat_desc']           : '';
        $insert['is_new_cat'] = 1;
        /* 代码增加_start  By   www.taoyumall.com  */
        
        if($insert['path_name'] != ''){
            $wheres['path_name'] = $insert['path_name'];
            $is_have = Model('category')->select_category_info('cat_id',$wheres);
            if ($is_have){
               $link[] = array('text' => L('go_back'), 'href' => 'javascript:history.back(-1)');
               showMessage(L('cat_file_repeat'), $link);
            }
        }
        
        /* 代码增加_end  By   www.taoyumall.com  */
         $cat_id = Model('category')->insert_category($insert);  
         if(!empty($cat_id)){
             admin_log($_POST['cat_name'], 'add', 'category');   // 记录管理员操作
         }else{
             showMessage(L('catadd_fail'));
         }
         clear_cache_files();    // 清除缓存
         /*添加链接*/
         $link[0]['text'] = L('continue_add');
         $link[0]['href'] = 'index.php?act=category&op=cat_add';
         $link[1]['text'] = L('back_list');
         $link[1]['href'] = 'index.php?act=category&op=cat_lists';
         showMessage(L('catadd_succed'),$link);
    }
    
    /**
     * @return 进入编辑产品分类页面 Description
     */
    public function cat_edit(){
        admin_priv('cat_manage');   // 权限检查
        $cat_id = intval($_REQUEST['cat_id']);
        $catmodel = Model('category');
        $wheres['cat_id'] = $cat_id;
        $cat_info = $catmodel->select_category_info('*',$wheres);  // 查询分类信息数据
        /* 模板赋值 */
        Tpl::assign('ur_here',     '编辑商品分类');
        Tpl::assign('action_link', array('text' => L('04_category_list'), 'href' => 'index.php?act=category&op=cat_lists'));
        Tpl::assign('cat_info',    $cat_info);
        Tpl::assign('form_act',    'category');
        Tpl::assign('form_op',    'cat_update');
        Tpl::assign('cat_select',  cat_list(0, $cat_info['parent_id'], true));
        //如果是虚拟商品则用虚拟分类模板
        Tpl::display('category_info.htm');
    }
    
    /**
     * @return 把需要修改的数据在数据表中修改 Description
     */
    public function cat_update(){
        /* 权限检查 */
        admin_priv('cat_manage');
        /* 初始化变量 */
        $allow_img_types = '|GIF|JPG|PNG|';
        $catmodel = Model('category');
        if(!empty($_POST['cat_id'])){
            $cat_id = $_POST['cat_id'];
        }else{
            showMessage(L('cat_move_empty'));
        }
        $old_cat_name = !empty($_POST['old_cat_name']) ? trim($_POST['old_cat_name'])  : '';
        $wheres_id['cat_id'] = $cat_id;
        $result_info = $catmodel->select_category_info('*',$wheres_id);
        /* 获取图片地址 */
        $img_url = '';
        if ((isset($_FILES['cat_img']['error']) && $_FILES['cat_img']['error'] == 0) || (!isset($_FILES['cat_img']['error']) && isset($_FILES['cat_img']['tmp_name']) && $_FILES['cat_img']['tmp_name'] != 'none')){
            // 检查图片格式
            if (!check_file_type($_FILES['cat_img']['tmp_name'], $_FILES['cat_img']['name'], $allow_img_types)){
                showMessage(L('invalid_file'));
            }
            // 复制图片
            $path = 'data/category_img';
            $res = upload_oss_img($_FILES['cat_img'], $path, 0);
            if ($res['status']){
                ossDeleteFileObject($result_info['cat_img']);
                $img_url = $res['url'];
                $update['cat_img'] = $img_url;
            }
        }
        if ($img_url == ''){
            $update['cat_img'] = $_POST['cat_img_url'];
        }
        $update['cat_name'] = !empty($_POST['cat_name'])? trim($_POST['cat_name']): '';
        if($update['cat_name'] != $old_cat_name){
            $wheres['cat_name'] = $update['cat_name'];
            $result = $catmodel->select_category_info('cat_name',$wheres);
            if(!empty($result)){
                showMessage(L('catname_exist'));
            }
        }
        $update['path_name']  = !empty($_POST['path_name'])     ? trim($_POST['path_name'])     : '';
        $update['parent_id']  = !empty($_POST['parent_id'])    ? intval($_POST['parent_id'])  : 0;
        $update['sort_order'] = !empty($_POST['sort_order'])   ? intval($_POST['sort_order']) : 0;
        $update['is_show']    = !empty($_POST['is_show'])      ? intval($_POST['is_show'])    : 0;
        $update['show_in_nav']  = !empty($_POST['show_in_nav'])  ? intval($_POST['show_in_nav']): 0;
        $update['keywords']     = !empty($_POST['keywords'])     ? trim($_POST['keywords'])     : '';
        $update['cat_desc']     = !empty($_POST['cat_desc'])     ? $_POST['cat_desc']           : '';
        $update['is_new_cat'] = 1;
        /* 代码增加_start  By   www.taoyumall.com  */
        
        if($update['path_name'] != ''){
            $wheressd = " path_name = '$update[path_name]' AND cat_id <> $cat_id ";
            $is_have = Model('category')->select_category_info('cat_id',$wheressd);
            if ($is_have){
               $link[] = array('text' => L('go_back'), 'href' => 'javascript:history.back(-1)');
               showMessage(L('cat_file_repeat'), $link);
            }
        }
        /* 代码增加_end  By   www.taoyumall.com  */
         $result = Model('category')->update_category($update,$wheres_id);  
         if(!empty($result)){
             admin_log($_POST['cat_name'], 'edit', 'category');   // 记录管理员操作
         }else{
             showMessage(L('catedit_fail'));
         }
         clear_cache_files();    // 清除缓存
         /*添加链接*/
         $link['text'] = L('back_list');
         $link['href'] = 'index.php?act=category&op=cat_lists';
         showMessage(L('catedit_succed'),$link);
    }
    
   /**
    * @return 删除商品分类 Description
    */
    public function cat_del(){
        if(!empty($_REQUEST['id'])){
            $cat_id = $_REQUEST['id'];
            $cat_model = Model('category');
            $wheres_id['cat_id'] = $cat_id;
            $result = $cat_model->select_category_info('*',$wheres_id);
            if(!empty($result)){
                $wheres_good = " cat_ids_new like '%".$cat_id."%'";
                $goodscoont = Model('goods')->get_goods_count($wheres_good);
                $wheres_p['parent_id'] = $cat_id;
                $paer_coont = $cat_model->get_category_count($wheres_p);
                if(!empty($goodscoont) && !empty($paer_coont) ){
                    make_json_error(L('cat_isleaf'));
                }else{
                    $cat_model->delete_category($wheres_id);
                    admin_log($result['cat_name'], 'remove', 'category');
                    $url = 'index.php?act=category&op=cat_query';
                    ecs_header("Location: $url\n");
                    exit;
                }
            }else{
              make_json_error(L('cat_move_empty'));
            }
        }else{
            make_json_error(L('cat_move_empty'));
        }
    }
    
    
    /**
     * @return 切换是否显示 Description
     */
    public function toggle_is_show(){
        check_authz_json('cat_manage');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $pearm['is_show'] = $val;
        $where['cat_id'] = $id;
        $result = Model('category')->update_category($pearm,$where);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($val);
        }else{
            make_json_error(L('hannr_errer'));
        }
    }
    
    /**
     * @return 修改排序参数 Description
     */
    public function edit_sort_order(){
        check_authz_json('cat_manage');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        
        $pearm['sort_order'] = $val;
        $where['cat_id'] = $id;
        $result = Model('category')->update_category($pearm,$where);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($val);
        }else{
            make_json_error(L('hannr_errer'));
        }
    }
    
    
    /**
     * @return 切换是否显示在导航栏 Description
     */
    public function toggle_show_in_nav(){
        check_authz_json('cat_manage');
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        
        $pearm['show_in_nav'] = $val;
        $where['cat_id'] = $id;
        $result = Model('category')->update_category($pearm,$where);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($val);
        }else{
            make_json_error(L('hannr_errer'));
        }
   
    }
    
}
