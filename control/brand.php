<?php

/**
 * 淘玉php 后台品牌管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台品牌管理类
 * $Id: brand.php  2018年6月7日16:00:42 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class brandControl extends BaseControl {
    
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('brand');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 品牌列表函数 
     */
    public function brand_lists() {
        /* 权限的判断 */
        admin_priv('cat_drop');
        Tpl::assign('ur_here',      L('brand_manage'));
        Tpl::assign('action_link',  array('text' => L('brand_add'), 'href' => 'index.php?act=brand&op=brand_add'));
        Tpl::assign('full_page',    1);
        $brand_list = $this->GetBrandList();
        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);
        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);
        Tpl::display('brand_list.htm');
    }
    
    /**
     * @return 品牌列表排序、分页、查询
     */
    public function brand_query() {
        $brand_list = $this->GetBrandList();
        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);
        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);
        $sort_flag  = sort_flag($brand_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('brand_list.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }
    
    /**
     * @return 进入添加品牌页面 Description
     */
     public function brand_add() {
         /* 权限判断 */
        admin_priv('cat_drop');
        Tpl::assign('ur_here',     L('brand_add'));
        Tpl::assign('action_link', array('text' => L('brand_list'), 'href' => 'index.php?act=brand&op=brand_lists'));
        Tpl::assign('form_act', 'brand');
        Tpl::assign('form_op', 'brand_insert');
        Tpl::assign('brand', array('sort_order'=>50, 'is_show'=>1));
        Tpl::display('brand_info.htm');
     }
     
     /**
      * @return 插入品牌数据到数据库 Description 
      */
     public function brand_insert(){
        /* 权限判断 */
        admin_priv('cat_drop');
        /* 允许上传的图片类型 */
        $allow_img_types = '|GIF|JPG|PNG|';
        /*参数初始赋值*/
        $brandmodel = Model('brand');
        $brand_name = trim($_POST['brand_name']);
        $insert['brand_desc'] = $_POST['brand_desc'];
        $insert['is_show'] = $_POST['is_show'];
        $insert['sort_order'] = $_POST['sort_order'];
        /* 检查名词是否重复 */
        $where['brand_name'] = $brand_name;
        $result = $brandmodel->select_brand_info('*', $where);
        if(!empty($result)){
            showMessage(L('brand_name_errer'));
        }
        $insert['brand_name'] = $brand_name;
        /* 取得图片地址 */
        $img_url = '';
        if ((isset($_FILES['brand_logo']['error']) && $_FILES['brand_logo']['error'] == 0) || (!isset($_FILES['brand_logo']['error']) && isset($_FILES['brand_logo']['tmp_name']) && $_FILES['brand_logo']['tmp_name'] != 'none')){
            /* 检查图片格式 */
            if (!check_file_type($_FILES['brand_logo']['tmp_name'], $_FILES['brand_logo']['name'], $allow_img_types)){
                showMessage(L('invalid_file'));
            }
            /* 上传图片 */
            $path = 'data/brandlogo';
            $res = upload_oss_img($_FILES['brand_logo'], $path, 0);
            if ($res['status']){
                $img_url = $res['url'];
            }
        }
        if ($img_url == ''){
            $img_url = $_POST['brand_logo_url'];
        }
        $insert['brand_logo'] = $img_url;
        /*插入数据*/
        $brand_ids = $brandmodel->insert_brand($insert);

        $link[1]['text'] = L('brand_add');
        $link[1]['href'] = 'index.php?act=brand&op=brand_add';
        $link[0]['text'] = L('brand_list');
        $link[0]['href'] = 'index.php?act=brand&op=brand_lists';
        admin_log($brand_name,'add','brand');
        showMessage(L('brand_add_success'),$link);
     }
     
      /**
     * @return 编辑品牌页面 Description
     */
     public function brand_edit() {
         /* 权限判断 */
        admin_priv('cat_drop');
        $brand_id = $_REQUEST['brand_id'];
        $brandmodel = Model('brand');       
        /* 取文章数据 */
        $whers['brand_id'] = $brand_id;
        $brand = $brandmodel->select_brand_info('*',$whers);
        Tpl::assign('brand', $brand);
        Tpl::assign('ur_here',     L('brand_edit'));
        Tpl::assign('action_link', array('text' => L('brand_list'), 'href' => 'index.php?act=brand&op=brand_lists'));
        Tpl::assign('form_act', 'brand');
        Tpl::assign('form_op', 'brand_update');
        Tpl::display('brand_info.htm');
     }
     
     
     /**
      * @return 修改品牌内容到数据库 Description
      */
     public function brand_update(){
         /* 权限判断 */
        admin_priv('cat_drop');
        /* 允许上传的图片类型 */
        $allow_img_types = '|GIF|JPG|PNG|';
        /*参数初始赋值*/
        $brandmodel = Model('brand');
        $brand_id = $_POST['brand_id'];
        $brand_name = trim($_POST['brand_name']);
        $old_brandlogo = $_POST['old_brandlogo'];
        $old_brandname = trim($_POST['old_brandname']);
        $update['brand_desc'] = $_POST['brand_desc'];
        $update['is_show'] = $_POST['is_show'];
        $update['sort_order'] = $_POST['sort_order'];
        /* 检查重复 */
        if($old_brandname != $brand_name){
            $where = " brand_name = '".$brand_name."' AND brand_id <> $brand_id";
            $result = $brandmodel->select_brand_info('*', $where);
            if(!empty($result)){
                showMessage(L('brand_name_errer'));
            }
            $update['brand_name'] = $brand_name;
        }
        
        /* 取得图片地址 */
        $img_url = '';
        if ((isset($_FILES['brand_logo']['error']) && $_FILES['brand_logo']['error'] == 0) || (!isset($_FILES['brand_logo']['error']) && isset($_FILES['brand_logo']['tmp_name']) && $_FILES['brand_logo']['tmp_name'] != 'none')){
            /* 检查图片格式 */
            if (!check_file_type($_FILES['brand_logo']['tmp_name'], $_FILES['brand_logo']['name'], $allow_img_types)){
                showMessage(L('invalid_file'));
            }
            /* 上传图片 */
            $path = 'data/brandlogo';
            $res = upload_oss_img($_FILES['brand_logo'], $path, 0);
            if ($res['status']){
                ossDeleteFileObject($old_brandlogo);
                $img_url = $res['url'];
            }
        }
        if ($img_url == ''){
            $img_url = $_POST['brand_logo_url'];
        }
        $update['brand_logo'] = $img_url;
        /*插入数据*/
        $where_id['brand_id'] = $brand_id;
        $result = $brandmodel->update_brand($update,$where_id);
        $link['text'] = L('brand_list');
        $link['href'] = 'index.php?act=brand&op=brand_lists';
        admin_log($brand_name,'edit','brand');
        showMessage(L('brand_edit_success'),$link);
     }

   /**
    * @return 删除对应品牌 Description
    */
   public function brand_remove(){
        check_authz_json('cat_drop');
        $brand_id = intval($_GET['id']);
        /* 删除该品牌的图标 */
        $brandmodel = Model('brand');
        $where_id['brand_id'] = $brand_id;
        $logo_info = $brandmodel->select_brand_info('brand_logo',$where_id);
        if (!empty($logo_info)){
            ossDeleteFileObject($logo_info['brand_logo']);
        }
        $result = $brandmodel->delete_brand($where_id);
        if(!empty($result)){
            $pram['brand_id'] = 0;
            $where_goods['brand_id'] = $brand_id;
            Model('goods')->update_goods($pram,$where_goods);
            $url = 'index.php?act=brand&op=brand_query';
            ecs_header("Location: $url\n");
            exit;
        }else{
           make_json_error(L('del_brand_fail'));
        }
       
   }
   
   /**
    * @return 编辑品牌名称 Description
    */
   public function edit_brand_name(){
        check_authz_json('cat_drop');
        $brand_id = intval($_POST['id']);
        $name   = json_str_iconv(trim($_POST['val']));
        $where_name = " brand_name= '".$name."' AND brand_id <> $brand_id ";
        $result = Model('brand')->select_brand_info('*',$where_name);
        if(!empty($result)){
           make_json_error(L('brand_name_errer'));
        }else{
            $pearm['brand_name'] = $name;
            $where['brand_id'] = $brand_id;
            $result = Model('brand')->update_brand($pearm,$where);
            if (!empty($result)){
                clear_cache_files();
                admin_log('修改品牌名称为：'.addslashes($name).'id为'.$brand_id,'edit','brand');
                make_json_result($name);
            }else{
                make_json_error(L('hannr_errer'));
            }
        }
   }
   
   /**
    * @return 编辑排序序号 Description
    */
   public function edit_sort_order(){
        check_authz_json('cat_drop');
        $brand_id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $pearm['sort_order'] = $val;
        $where['brand_id'] = $brand_id;
        $result = Model('brand')->update_brand($pearm,$where);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($val);
        }else{
            make_json_error(L('hannr_errer'));
        }
   }
   
   /**
    * @return 切换是否显示 Description
    */
   public function toggle_show(){
        check_authz_json('cat_drop');
        $brand_id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $pearm['is_show'] = $val;
        $where['brand_id'] = $brand_id;
        $result = Model('brand')->update_brand($pearm,$where);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($val);
        }else{
            make_json_error(L('hannr_errer'));
        }
   }

      /**
    * @return 获取品牌列表 
    * @access  public
    * @return  array
    */

   private function GetBrandList(){
       $result = get_filter();
       if ($result === false){
           /* 分页大小 */
           $filter = array();
           /* 记录总数以及页数 */
           $brandmodel = Model('brand');
           $filter['brand_name'] = !empty($_POST['brand_name']) ? $_POST['brand_name']:'';
           $where_brand = " 1 ";
           if($filter['brand_name']){
               $where_brand .= " AND brand_name like '%".$_POST['brand_name']."%'";
           }
           $filter['record_count'] =$brandmodel->get_brand_count($where_brand);
           $filter = page_and_size($filter);
           /* 查询记录 */
           $sql = "SELECT * FROM ".Model()->tablename('brand')." WHERE ".$where_brand." ORDER BY sort_order ASC,brand_id DESC ";
           set_filter($filter, $sql);
       }else{
           $sql    = $result['sql'];
           $filter = $result['filter'];
       }
       
       $res = get_all_page($sql, $filter['page_size'], $filter['start']);
       foreach ($res as $key => $value) {
           $res[$key]['brand_logo'] = get_imgurl_oss($value['brand_logo'],50,'',true);
       }
       return array('brand' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

   }
}
?>

