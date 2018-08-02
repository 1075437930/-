<?php

/**
 * 入驻商商品管理功能
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萤火虫 $
 * 入驻商商品管理功能
 * $Id: shop.php  2018年6月5日15:24:57   萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class shopControl extends BaseControl{
    
    public function __construct() {
        Language::read('goods,calendar');
        parent::__construct();
        header("Expires:  Mon,26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

   /**
    * @return  商家商品列表
    */
    public function shop_lists(){
        $goodsModel = Model('goods');
        $brandModel = Model('brand');
        $supplierModel = Model('supplier');
        admin_priv('goods_manage');
        Tpl::assign('ur_here',      L('s_goods_manage'));
        Tpl::assign('action_link',  array('text' => L('goods_supplier_add'), 'href' => 'index.php?act=shop&op=shop_add'));
        Tpl::assign('full_page',1);
        $rowlist = $brandModel->get_brand_list('brand_id,brand_name','');
        $brand_list = array();
        foreach ($rowlist AS $row){
            $brand_list[$row['brand_id']] = addslashes($row['brand_name']);
        }
        $supplielist = $supplierModel->get_supplier_list('supplier_id,supplier_name','');
        foreach ($supplielist AS $row){
            $supplie_list[$row['supplier_id']] = addslashes($row['supplier_name']);
        }
        /* 模板赋值 */
        Tpl::assign('cat_list',     cat_list(0, $cat_id));
        Tpl::assign('brand_list',   $brand_list);
        Tpl::assign('supplie_list',   $supplie_list);
        $goods_list = $this->ShopList();
        Tpl::assign('goods_list',   $goods_list['goods']);
        Tpl::assign('filter',       $goods_list['filter']);
        Tpl::assign('record_count', $goods_list['record_count']);
        Tpl::assign('page_count',   $goods_list['page_count']);
        $sort_flag  = sort_flag($goods_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);     
        Tpl::display('goods_shop_list.htm');
    }
    
    /**
     * @return 商品列表排序、分页、查询
     */
    public function shop_query() {
        $goodsModel = Model('goods');
        $brandModel = Model('brand');
        $supplierModel = Model('supplier');
        admin_priv('goods_manage');
        Tpl::assign('ur_here',      L('s_goods_manage'));
        Tpl::assign('action_link',  array('text' => L('goods_supplier_add'), 'href' => 'index.php?act=shop&op=shop_add'));
        $rowlist = $brandModel->get_brand_list('brand_id,brand_name','');
        $brand_list = array();
        foreach ($rowlist AS $row){
            $brand_list[$row['brand_id']] = addslashes($row['brand_name']);
        }
        $supplielist = $supplierModel->get_supplier_list('supplier_id,supplier_name','');
        foreach ($supplielist AS $row){
            $supplie_list[$row['supplier_id']] = addslashes($row['supplier_name']);
        }
        Tpl::assign('supplie_list',   $supplie_list);
        /* 模板赋值 */
        Tpl::assign('cat_list',     cat_list(0, $cat_id));
        Tpl::assign('brand_list',   $brand_list);
        $goods_list = $this->ShopList();
        Tpl::assign('goods_list',    $goods_list['goods']);
        Tpl::assign('filter',          $goods_list['filter']);
        Tpl::assign('record_count',    $goods_list['record_count']);
        Tpl::assign('page_count',      $goods_list['page_count']);
        $sort_flag  = sort_flag($goods_list['filter']);
        Tpl::assign($sort_flag['tag'],$sort_flag ['img']);
        make_json_result(Tpl::fetch('goods_shop_list.htm'), '',
            array('filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']));
    }
    
     /**
      *  @return 切换产品标签分类 
      */
     public function get_tags(){
        admin_priv('goods_manage');
        $goods_id   = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
        $goods_tag = empty($_GET['goods_tag']) ? 0 : intval($_GET['goods_tag']);
        $content = build_tags_html($goods_tag, $goods_id,"1");
        make_json_result($content);
     }
    /**
      *  @return 切换产品属性分类 
      */
     public function get_attr(){
        admin_priv('goods_manage');
        $goods_id   = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
        $goods_type = empty($_GET['goods_type']) ? 0 : intval($_GET['goods_type']);
        $content    = build_attr_html($goods_type, $goods_id,0);
        make_json_result($content);
     }
     
     /**
      * @return 检查输入的goods_sn是否重复
      */
     public function check_goods_sn(){
        admin_priv('goods_manage');
        $goods_id = intval($_REQUEST['goods_id']);
        $goods_sn = htmlspecialchars(json_str_iconv(trim($_REQUEST['goods_sn'])));
        /* 检查是否重复 */
        $wheres['goods_sn'] = $goods_sn;
        $jieguo = Model('goods')->select_goods_info('goods_sn',$wheres);
        if (!empty($jieguo)){
            make_json_error(L('goods_sn_exists'));
        }
        make_json_result('');
     }
    /**
     * @return 进入添加商品页面 Description
     */
     public function shop_add() {
         /* 权限判断 */
        admin_priv('goods_manage');
        /*初始化管理员数据*/
        $admin_into = $this->getAdminInfo();  
        /*初始化产品数据*/
        $goods = array(
            'goods_id'      => 0,
            'goods_desc'    => '',
            'cat_id'        => $last_choose[0],
            'brand_id'      => $last_choose[1],
            'is_on_sale'    => '1',
            'is_alone_sale' => '1',
            'is_shipping' => '0',
	    'cost_price'  => 0,
            'other_cat'     => array(), // 扩展分类
            'new_cat'     => array(), // 新分类
            'goods_type'    => 0,       // 商品类型
            'shop_price'    => 0,
            'promote_price' => 0,
            'market_price'  => 0,
	    'goods_fenxiao_price'  => 0,
            'integral'      => 0,
            'goods_number'  => C('default_storage'),
            'warn_number'   => 1,
            /* 代码修改 By  www.taoyumall.com 促销商品时间精确到时分 Start */
            'promote_start_date' => local_date('Y-m-d H:i'),
            'promote_end_date'   => local_date('Y-m-d H:i', local_strtotime('+1 month')),
            /* 代码修改 By  www.taoyumall.com 促销商品时间精确到时分 End */
            'goods_weight'  => 0,
            'goods_size' =>'',
            'goods_cailiao' =>'',
            'goods_area' =>'',
            'goods_dengji' =>'',
            'give_integral' => -1,
	    'exclusive' => -1,//手机专享价格   app  jx   
            'rank_integral' => -1,
            'goods_number' => 1
        );
        $this->ShopPage($goods);
        Tpl::assign('goods', $goods);
        Tpl::assign('ur_here',     L('goods_supplier_add'));
        Tpl::assign('action_link', array('text' => L('s_goods_manage'), 'href' => 'index.php?act=shop&op=shop_lists'));
        Tpl::assign('form_act', 'shop');
        Tpl::assign('form_op', 'shop_insert');
        Tpl::display('goods_shop_info.htm');
     }
     
    /**
     * @return 插入新添加产品数据到数据库 
    */
    public function shop_insert() {
        $allow_img_types = '|GIF|JPG|PNG|';
        $goodsModel = Model('goods');
        /* 主要传递参数的处理 */
        $goods_sns = trim($_POST['goods_sn']);
        $insert['goods_sn'] = $goods_sns;
        $insert['goods_name'] = trim($_POST['goods_name']);
        $insert['supplier_id'] = $_POST['supplier_id'];
        
        /* 检查权限 */
        admin_priv('goods_manage'); 
        if(empty($insert['goods_name'])){
            showMessage(L('goods_name_null'));
        }
        
        if(empty($insert['supplier_id'])){
            showMessage(L('shop_id_null'));
        }
         /* 检查货号是否重复 */
        if (!empty($goods_sns)) {
            $where = " goods_sn = '$goods_sns' AND is_delete = 0 ";
            $goodscounts = $goodsModel->get_goods_count($where);
            if ($goodscounts > 0){
                showMessage(L('goods_sn_exists'));
            }
        }
        
        /* 取得视频文件地址 */
        $file_url = $_POST['goods_video_url'];
        /* 取得主图片地址 */
        $img_url = '';
        if ((isset($_FILES['goods_img']['error']) && $_FILES['goods_img']['error'] == 0) || (!isset($_FILES['goods_img']['error']) && isset($_FILES['goods_img']['tmp_name']) && $_FILES['goods_img']['tmp_name'] != 'none')){
            // 检查图片格式
            if (!check_file_type($_FILES['goods_img']['tmp_name'], $_FILES['goods_img']['name'], $allow_img_types)){
                showMessage(L('invalid_goods_img'));
            }
            $numsd = rand(0,99);
            // 格式化图片地址直接存入oss
            $res = reformat_shopimage_name($_FILES['goods_img'],$insert['supplier_id'],$numsd);
            if (!empty($res)){
                $img_url = $res;
            }else{
                showMessage(L('upload_img_mian_errer'));
            }
        }
        if ($img_url == ''){
            if(preg_match('/(.jpg|.png|.gif|.jpeg)$/',$_POST['goods_img_url'])){
                $img_url = $_POST['goods_img_url'];
            }else{
                showMessage(L('invalid_goods_img'));
            }
        }
        
        /* 处理商品数据 */
        $insert['shop_price'] = !empty($_POST['shop_price']) ? $_POST['shop_price'] : 0;//商城价格
        $insert['market_price'] = !empty($_POST['market_price']) ? $_POST['market_price'] : 0;//市场价格
        $insert['promote_price'] = !empty($_POST['promote_price']) ? floatval($_POST['promote_price'] ) : 0;//促销价格
        $insert['fenxiao'] = empty($_POST['fenxiao']) ? 0 : 1;//是否开启分销根据分销价格判断
        $insert['coupons_type'] = $_POST['coupons_n'];//是否允许使用代金券
        $insert['goods_fenxiao_price'] = !empty($_POST['fenxiao']) ? floatval($_POST['fenxiao'] ) : 0;//分销价格
        $new_cat_list = $_POST['new_cat'];
        $cat_ids_new = '';
        foreach($new_cat_list  as $key => $value){
            if($key==0){
                $cat_ids_new .= $value;
            }else{
                $cat_ids_new .= ','.$value;
            }
        }
        $insert['cat_ids_new'] = $cat_ids_new;
        $insert['original_img'] = $img_url;
        $insert['is_promote'] = empty($promote_price) ? 0 : 1;
        $insert['zhekou'] = ($promote_price == 0 ? 10.0 : (number_format(($promote_price/$shop_price),2))*10);
        $insert['promote_start_date'] = ($is_promote && !empty($_POST['promote_start_date'])) ? local_strtotime($_POST['promote_start_date']) : 0;
        $insert['promote_end_date'] = ($is_promote && !empty($_POST['promote_end_date'])) ? local_strtotime($_POST['promote_end_date']) : 0;
        $insert['goods_weight'] = !empty($_POST['goods_weight']) ? $_POST['goods_weight']: 0;
        $insert['goods_size'] = !empty($_POST['goods_size']) ? $_POST['goods_size']: '';
        $insert['goods_cailiao'] = !empty($_POST['goods_cailiao']) ? $_POST['goods_cailiao']: '';
        $insert['goods_area'] = !empty($_POST['goods_area']) ? $_POST['goods_area']: '';
        $insert['goods_dengji'] = !empty($_POST['goods_dengji']) ? $_POST['goods_dengji']: '';
        $insert['is_on_sale'] = isset($_POST['is_on_sale']) ? 1 : 0;
        $insert['is_shipping'] = 1;//直接默认包邮
        $insert['goods_number'] = isset($_POST['goods_number']) ? $_POST['goods_number'] : 0;
        $insert['goods_type'] = isset($_POST['goods_type']) ? $_POST['goods_type'] : 0;
        $insert['brand_id'] = empty($_POST['goods_brand']) ? '' : intval($_POST['goods_brand']);
        $insert['add_time']= gmtime();
        $insert['last_update']= gmtime();
        $insert['keywords'] = $_POST['keywords'];
        $insert['goods_brief'] = $_POST['goods_brief'];
        $insert['goods_weight'] = $_POST['goods_weight'];
        $insert['goods_verify'] = 1;
        /* 插入数据 */
        $goodsids = $goodsModel->insert_goods($insert);
        if(empty($goodsids)){
          showMessage(L('save_goods_mian_errer'));
        }
        
        /* ------------------      在上传产品以后获取goodsid后处理        ------------------*/
        
        /* 获取商品内部标签处理 */
        $this->GoodsTagSet($goodsids);
        
        /* 获取商品内部属性处理 */
        $this->GoodsAttrSet($goodsids);
	
        /* 如果有图片，把商品图片加入图片相册 */
        if (!empty($img_url)){
            $insert_gallery['goods_id'] = $goodsids;
            $insert_gallery['img_original'] = $img_url;
            $goodsModel->insert_goods_gallery($insert_gallery);
        }
        /* 记录日志 */
        admin_log($insert['goods_name'].'('.$goods_sns.') '.'分销价格：'.$insert['goods_fenxiao_price'], 'add', 'shopgoods');
        $link = array();
        $link['text'] = L('back_lists');
        $link['href'] = 'index.php?act=shop&op=shop_lists';
        showMessage(L('add_shop_ok'),$link);
    }
    
   /**
    * @return 插入新编辑产品数据到数据库 
    */
    public function shop_update() {
        $allow_img_types = '|GIF|JPG|PNG|';
        $goodsModel = Model('goods');
        /* 主要传递参数的处理 */
        $goods_sns = trim($_POST['goods_sn']);
        $insert['goods_sn'] = $goods_sns;
        $goods_ids = intval($_POST['goods_id']);
        $insert['goods_name'] = trim($_POST['goods_name']);
        $supplier_id = intval($_POST['supplier_id']);
        $insert['supplier_id'] = $supplier_id;
        /* 检查权限 */
        admin_priv('goods_manage'); 
        if(empty($insert['goods_name'])){
            showMessage(L('goods_name_null'));
        }

         /* 检查货号是否重复 */
        if (!empty($goods_sns)) {
            $where = " goods_sn = '$goods_sns' AND is_delete = 0 AND goods_id <> '$goods_ids'";
            $goodscounts = $goodsModel->get_goods_count($where);
            if ($goodscounts > 0){
                showMessage(L('goods_sn_exists'));
            }
        }
        /* 取得视频文件地址 */
        $file_url = $_POST['goods_video_url'];
        
        /* 取得主图片地址 */
        $img_url = '';
        if ((isset($_FILES['goods_img']['error']) && $_FILES['goods_img']['error'] == 0) || (!isset($_FILES['goods_img']['error']) && isset($_FILES['goods_img']['tmp_name']) && $_FILES['goods_img']['tmp_name'] != 'none')){
            // 检查图片格式
            if (!check_file_type($_FILES['goods_img']['tmp_name'], $_FILES['goods_img']['name'], $allow_img_types)){
                showMessage(L('invalid_goods_img'));
            }
            $numsd = rand(0,99);
            // 格式化图片地址直接存入oss
            $res = reformat_shopimage_name($_FILES['goods_img'],$supplier_id,$numsd);
            if (!empty($res)){
                $img_url = $res;
                /* 如果有图片，把商品图片加入图片相册 */
                $pram_gallery['img_original'] = $res;
                $whers_imgs['img_original'] = $_POST['goods_img_url'];
                $whers_imgs['goods_id'] = $goods_ids;
                $jieguo = $goodsModel->select_goods_gallery_info('img_original',$whers_imgs);
                if(!empty($jieguo)){
                   ossDeleteFileObject($jieguo['img_original']);
                   $goodsModel->update_goods_gallery($pram_gallery,$whers_imgs);
                }else{
                   $pram_gallery['goods_id'] = $goods_ids;
                   $goodsModel->insert_goods_gallery($pram_gallery);  
                }
            }else{
                showMessage(L('upload_img_mian_errer'));
            }
        }
        if ($img_url == ''){
            if(preg_match('/(.jpg|.png|.gif|.jpeg)$/',$_POST['goods_img_url'])){
                $img_url = $_POST['goods_img_url'];
            }else{
                showMessage(L('invalid_goods_img'));
            }
        }
        
        /* 处理商品数据 */
        $insert['shop_price'] = !empty($_POST['shop_price']) ? $_POST['shop_price'] : 0;//商城价格
        $insert['market_price'] = !empty($_POST['market_price']) ? $_POST['market_price'] : 0;//市场价格
        $insert['promote_price'] = !empty($_POST['promote_price']) ? floatval($_POST['promote_price'] ) : 0;//促销价格
        $insert['fenxiao'] = empty($_POST['fenxiao']) ? 0 : 1;//是否开启分销根据分销价格判断
        $insert['coupons_type'] = $_POST['coupons_n'];//是否允许使用代金券
        $insert['goods_fenxiao_price'] = !empty($_POST['fenxiao']) ? floatval($_POST['fenxiao'] ) : 0;//分销价格
        $new_cat_list = $_POST['new_cat'];
        $cat_ids_new = '';
        foreach($new_cat_list  as $key => $value){
            if($key==0){
                $cat_ids_new .= $value;
            }else{
                $cat_ids_new .= ','.$value;
            }
        }
        $insert['cat_ids_new'] = $cat_ids_new;
        $insert['original_img'] = $img_url;
        $insert['is_promote'] = empty($promote_price) ? 0 : 1;
        $insert['zhekou'] = ($promote_price == 0 ? 10.0 : (number_format(($promote_price/$shop_price),2))*10);
        $insert['promote_start_date'] = ($is_promote && !empty($_POST['promote_start_date'])) ? local_strtotime($_POST['promote_start_date']) : 0;
        $insert['promote_end_date'] = ($is_promote && !empty($_POST['promote_end_date'])) ? local_strtotime($_POST['promote_end_date']) : 0;
        $insert['goods_weight'] = !empty($_POST['goods_weight']) ? $_POST['goods_weight']: 0;
        $insert['goods_size'] = !empty($_POST['goods_size']) ? $_POST['goods_size']: '';
        $insert['goods_cailiao'] = !empty($_POST['goods_cailiao']) ? $_POST['goods_cailiao']: '';
        $insert['goods_area'] = !empty($_POST['goods_area']) ? $_POST['goods_area']: '';
        $insert['goods_dengji'] = !empty($_POST['goods_dengji']) ? $_POST['goods_dengji']: '';
        $insert['is_on_sale'] = isset($_POST['is_on_sale']) ? 1 : 0;
        $insert['is_shipping'] = 1;//直接默认包邮
        $insert['goods_number'] = isset($_POST['goods_number']) ? $_POST['goods_number'] : 0;
        $insert['goods_type'] = isset($_POST['goods_type']) ? $_POST['goods_type'] : 0;
        $insert['brand_id'] = empty($_POST['goods_brand']) ? '' : intval($_POST['goods_brand']);
        $insert['last_update']= gmtime();
        $insert['keywords'] = $_POST['keywords'];
        $insert['goods_brief'] = $_POST['goods_brief'];
        $insert['goods_weight'] = $_POST['goods_weight'];
        $insert['goods_verify'] = 1;
        /* 修改数据 */
        $wheres['goods_id'] = $goods_ids;
       
        $jieguo = $goodsModel->update_goods($insert,$wheres);
       
        /* ------------------      在上传产品以后获取goodsid后处理        ------------------*/
        
        /* 获取商品内部标签处理 */
        $this->GoodsTagSet($goods_ids,'update');
        
        /* 获取商品内部属性处理 */
        $this->GoodsAttrSet($goods_ids);

        /* 记录日志 */
        admin_log($insert['goods_name'].'('.$goods_sns.') '.'分销价格：'.$insert['goods_fenxiao_price'], 'add', 'shopgoods');
        $link = array();
        $link['text'] = L('back_lists');
        $link['href'] = 'index.php?act=shop&op=shop_lists';
        admin_log($article_title,'edit','article');
        showMessage(L('edit_goods_ok'),$link);
    }
     
   /**
    * @return 进入编辑商品页面 Description
    */
    public function shop_edit() {
        /* 权限判断 */
        admin_priv('goods_manage');
        /*初始化产品数据*/
        $goodsModel = Model('goods');
        $goods_id = $_REQUEST['goods_id'];
        $where['goods_id'] = $goods_id;
        $goods = $goodsModel->select_goods_info($field = '*', $where);
        $this->ShopPage($goods);
        /*初始化管理员数据*/
        $admin_into = $this->getAdminInfo();    
        Tpl::assign('goods', $goods);
        Tpl::assign('ur_here',L('edit_goods_info'));
        Tpl::assign('action_link', array('text' => L('01_supplier_list'), 'href' => 'index.php?act=shop&op=shop_lists'));
        Tpl::assign('form_act', 'shop');
        Tpl::assign('form_op', 'shop_update');
        Tpl::display('goods_shop_info.htm');
    }
    
    /**
    * @return 删除对应id商品页面
    */
    public function shop_remove() {
        /* 检查权限 */
        admin_priv('remove_back');
        $goods_id = intval($_REQUEST['id']);
        /* 代码增加 By  www.taoyumall.com Start */
        $wheres['goods_id'] = $goods_id;
        $diangoods = Model('diancang')->select_capital_goods_info('*',$wheres);
        if(!empty($diangoods)){
            $sales_pro_list = ' [典藏产品] ';
        }
        $wheres_pai['act_type'] = 2;
        $wheres_pai['goods_id'] = $goods_id;
        $goods_activity = Model('auction')->select_goods_activity_info('act_id',$wheres_pai);
        if(!empty($goods_activity)){
            $sales_pro_list .= ' [拍卖] ';
        }
       
        if(!empty($sales_pro_list))
        {
            make_json_error('此商品参加了' . $sales_pro_list . '，不能放入回收站。');
            $url = 'index.php?act=goods&op=goods_query';
            ecs_header("Location: $url\n");
            exit;
        }
        /* 代码增加 By  www.taoyumall.com End */
        $pram['is_delete'] = 1;
        $resule = Model('goods')->update_goods($pram,$wheres);
        if ($resule){
            clear_cache_files();
            $goods_row = Model('goods')->select_goods_info('goods_id,goods_sn,goods_name,goods_number',$wheres);
            admin_log(addslashes($goods_row['goods_name']), 'trash', 'shopgoods'); // 记录日志
            $url = 'index.php?act=goods&op=goods_query';
            ecs_header("Location: $url\n");
            exit;
        }
    }
    
   /**
    * @return 修改上架状态 Description  
    */
    public function toggle_on_sale() {
        check_authz_json('goods_manage');
        $goods_id       = intval($_POST['id']);
        $on_sale        = intval($_POST['val']);
        /* 代码增加_start  By  www.taoyumall.com */
        $goodModel = Model('goods');
        $wheres['goods_id'] = $goods_id;
        $goods_row = $goodModel->select_goods_info('supplier_id,supplier_status',$wheres);
        if ($goods_row['supplier_id']>0 && $goods_row['supplier_status'] <=0 ){
            make_json_error('对不起，该商品还未审核通过！不能上架！');
        }
        /* 代码增加_end  By  www.taoyumall.com */
        $pram['is_on_sale'] = $on_sale;
        $result = $goodModel->update_goods($pram,$wheres);
        if(!empty($result)){
            clear_cache_files();
            make_json_result($on_sale,'',array('append'=>local_date('Y-m-d',gmtime())));
        }
    }

   /**
    * @return 修改精品推荐状态 Description  
    */
    public function toggle_best(){
        check_authz_json('goods_manage');
        $goods_id       = intval($_POST['id']);
        $is_best        = intval($_POST['val']);
        $pram['is_best'] = $is_best;
        $wheres['goods_id'] = $goods_id;
        $result = Model('goods')->update_goods($pram,$wheres);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($is_best);
        }
    }

   /**
    * @return 修改新品推荐状态 Description  
    */
    public function toggle_new(){
        check_authz_json('goods_manage');
        $goods_id   = intval($_POST['id']);
        $is_new    = intval($_POST['val']);
        $pram['is_new'] = $is_new;
        $wheres['goods_id'] = $goods_id;
        $result = Model('goods')->update_goods($pram,$wheres);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($is_new);
        }
    }


   /**
    * @return 修改热销推荐状态 Description  
    */
    public function toggle_hot(){
        check_authz_json('goods_manage');
        $goods_id   = intval($_POST['id']);
        $is_hot         = intval($_POST['val']);
        $pram['is_hot'] = $is_hot;
        $wheres['goods_id'] = $goods_id;
        $result = Model('goods')->update_goods($pram,$wheres);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($is_hot);
        }
    }

    /**
    * @return 修改app特价推荐状态 Description  
    */
    public function toggle_tiejia(){
        check_authz_json('goods_manage');
        $goods_id    = intval($_POST['id']);
        $is_tiejia     = intval($_POST['val']);
        $pram['is_tiejia'] = $is_tiejia;
        $wheres['goods_id'] = $goods_id;
        $result = Model('goods')->update_goods($pram,$wheres);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($is_tiejia);
        }
    }
    
    /**
     * @return  修改产品排序 Description
     */
    public function edit_sort_order(){
        check_authz_json('goods_manage');
        $goods_id    = intval($_POST['id']);
        $sort_order     = intval($_POST['val']);
        $pram['sort_order'] = $sort_order;
        $wheres['goods_id'] = $goods_id;
        $result = Model('goods')->update_goods($pram,$wheres);
        if (!empty($result)){
            clear_cache_files();
            make_json_result($sort_order);
        }
    }
    
    /**
    * @return 修改产品库存 Description
    */
    public function edit_goods_number(){
        check_authz_json('goods_manage');
        $goods_id   = intval($_POST['id']);
        $goods_num  = intval($_POST['val']);
        if($goods_num < 0 ){
            make_json_error(L('goods_number_error'));
        }
        $wheres['goods_id'] = $goods_id;
        $goods_info = Model('goods')->select_goods_info('goods_id,goods_sn,goods_name,goods_number',$wheres);
        if(!empty($goods_info)){
            $pram['goods_number'] = $goods_num;
            $result = Model('goods')->update_goods($pram,$wheres);
            if (!empty($result)){
                 //添加修改产品编号日志
                admin_log($goods_info['goods_name'].'('.$goods_info['goods_sn'].')'.' 库存从'.$goods_info['goods_number'].'改为：'.$goods_num, 'edit', 'shopgoods');
                clear_cache_files();
                make_json_result($goods_num);
            }
        }else{
            make_json_error(L('goods_number_error'));
        }
    }
    
    
    /**
     * @return 修改产品货号 Description
     */
    public function edit_goods_sn(){
        check_authz_json('goods_manage');
        $goods_id = intval($_POST['id']);
        $goods_sn = json_str_iconv(trim($_POST['val']));
        /* 检查是否重复 */
        $goodsmodel = Model('goods');
        $wheres['goods_sn'] = $goods_sn;
        $goods_info = $goodsmodel->select_goods_info('goods_id,goods_sn,goods_fenxiao_price,goods_name',$wheres);
        if (!empty($goods_info)){
            make_json_error(L('goods_sn_exists'));
        }
        $pram['goods_sn'] = $goods_sn;
        $whereids['goods_id'] = $goods_id;
        $result = $goodsmodel->update_goods($pram,$whereids);
        if (!empty($result)){
            //添加修改产品编号日志
            admin_log($goods_info['goods_name'].'('.$goods_sn.')'.' 原产品编号：'.$goods_info['goods_sn'], 'edit', 'shopgoods');
            clear_cache_files();
            make_json_result(stripslashes($goods_sn));
        }
    }
    
   /**
    * @return  批量操作产品 Description
    */
    public function shop_batch(){
        /* 取得要操作的商品编号 */
        $goods_id = !empty($_POST['checkboxes']) ? join(',', $_POST['checkboxes']) : 0;
        
        if (isset($_POST['type'])){
            $goodsmodel = Model('goods');
            /* 放入回收站 */
            $wheres = " goods_id in (".$goods_id.")";
            if ($_POST['type'] == 'trash'){
                /* 检查权限 */
                admin_priv('remove_back');
                /* 代码增加 By  www.taoyumall.com Start */
                $diangoods = Model('diancang')->select_capital_goods_info('*',$wheres);
                if(!empty($diangoods)){
                    $sales_pro_list = ' [典藏产品] ';
                }
                $wheres_pai = " goods_id in (".$goods_id.") AND act_type = 2";
                $goods_activity = Model('auction')->select_goods_activity_info('act_id',$wheres_pai);
                if(!empty($goods_activity)){
                    $sales_pro_list .= ' [拍卖] ';
                }
                if(!empty($sales_pro_list)){
                    $link[] = array('href' => 'index.php?act=shop&op=shop_lists', 'text' => L('01_supplier_list'));
                    sys_msg('所选商品包含参加促销活动的商品，不能加入回收站。', $link);
                }else{
                    $pram['is_delete'] = 1;
                    $result = $goodsmodel->update_goods($pram,$wheres);
                }
                /* 记录日志 */
                admin_log('', 'batch_trash', 'goods');
            }elseif ($_POST['type'] == 'on_sale'){/* 上架 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_on_sale = 1',$wheres);
            }elseif ($_POST['type'] == 'not_on_sale'){/* 下架 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_on_sale = 0',$wheres);
            }elseif ($_POST['type'] == 'best'){/* 设为精品 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_best = 1',$wheres);
            }elseif ($_POST['type'] == 'not_best'){/* 取消精品 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_best = 0',$wheres);
            }elseif ($_POST['type'] == 'new'){/* 设为新品 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_new = 1',$wheres);
            }elseif ($_POST['type'] == 'not_new'){/* 取消新品 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_new = 0',$wheres);
            }elseif ($_POST['type'] == 'hot'){/* 设为热销 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_hot = 1',$wheres);
            }elseif ($_POST['type'] == 'not_hot'){/* 取消热销 */
                /* 检查权限 */
                admin_priv('goods_manage');
                $result = $goodsmodel->update_goods('is_hot = 0',$wheres);
            }
        }
        /* 清除缓存 */
        clear_cache_files();
        $link = array('href' => 'index.php?act=shop&op=shop_lists', 'text' => L('01_supplier_list'));
        /*代码修改 by guo  start*/
        if(!empty($result)){
             showMessage(L('batch_handle_ok'),$link);
        }else{
           showMessage(L('batch_handle_errer'),$link);
        }
        /*代码修改 by guo  end*/
    }

    /**
     * @return 商家产品列表处理
     */
    private function ShopList(){
         /* 过滤条件 */
        $result = get_filter();
        if ($result === false){   
            $filter = array();
            $goodsmodel = Model('goods');
            $filter['cat_id']    = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
            $filter['brand_id']  = empty($_REQUEST['brand_id']) ? 0 : intval($_REQUEST['brand_id']);
            $filter['supplier_id']  = empty($_REQUEST['supplier_id']) ? 0 : intval($_REQUEST['supplier_id']);
            $filter['supplier_status']  = empty($_REQUEST['supplier_status']) ? 0 : intval($_REQUEST['supplier_status']);
            $filter['keyword']  = empty($_REQUEST['keyword']) ? '' : urldecode(trim($_REQUEST['keyword']));
            $filter['sort_by']  = empty($_REQUEST['sort_by']) ? 'goods.goods_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
             /* 默认实物产品 */
            $where = ' goods.is_real = 1 AND goods.is_chou = 0 AND goods.is_delete = 0 AND goods.supplier_id > 0 ';
            /* 品牌 */
            if (!empty($filter['brand_id'])) {
                $where .= " AND goods.brand_id='$filter[brand_id]'";
            }
            /* 分类 */
            if (!empty($filter['cat_id'])) {
                $where .= " AND (goods.cat_ids_new LIKE '%,".$filter['cat_id']."%' OR goods.cat_ids_new LIKE '%".$filter['cat_id'].",%')";
            }
            /* 商家 */
            if (!empty($filter['supplier_id'])) {
                $where .= " AND goods.supplier_id = '$filter[supplier_id]'";
            } 
            /* 审核状态 */
            if ($filter['supplier_status'] != '') {
                $where .= " AND goods.supplier_status = '$filter[supplier_status]'";
            }
            /* 关键字 */
            if (!empty($filter['keyword'])) {
                $where .= " AND (goods.goods_sn LIKE '%" . mysql_like_quote($filter['keyword']) . "%' OR goods.goods_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%')";
            }
            /* 记录总数 */
            $filter['record_count'] = $goodsmodel->get_goods_count($where);
            /* 分页大小 */
            $filter = page_and_size($filter);
            $sql = "SELECT goods.goods_id, goods.goods_name, goods.add_time,goods.last_update, goods.goods_type, goods.goods_sn,goods.goods_thumb,goods.original_img,goods.shop_price, goods.is_on_sale, goods.is_best, goods.is_new, goods.is_hot, goods.is_tiejia, goods.sort_order, goods.goods_number,goods.exclusive, goods.integral,goods.goods_chou_id, " .
                    "goods.supplier_status, goods.supplier_id,supplier.supplier_name ".
                    " FROM " . Model()->tablename('goods') . " AS goods ".
        	    " LEFT JOIN " . Model()->tablename('supplier') . " AS supplier ON supplier.supplier_id = goods.supplier_id ".
                    " WHERE ".$where.
                    " ORDER BY $filter[sort_by] $filter[sort_order] ";
            set_filter($filter, $sql);
        }else {
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
 
        foreach($row as $k=>$v) {
            //修改图片路径
            $row[$k]['goods_thumb'] = get_imgurl_oss($v['original_img'],40,40,false,true);
            $row[$k]['add_time'] = local_date('Y-m-d',$v['add_time']);
            $row[$k]['last_update'] = local_date('Y-m-d',$v['last_update']);
        }
        return array('goods' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    }
    
    
    
    /**
     * @return 获取商品内部标签处理 Description
     * @param int $goods_id 产品id
     * @param string $is_insert 空-新添加/有值-编辑
     */
    private function GoodsTagSet($goods_id,$is_insert){
        $where['goods_id'] = $goods_id;
        if(!empty($_POST['goodstagsd'])){
            $goodstag = $_POST['goodstagsd'];
            foreach ($goodstag as $ktag => $valtag){
                $insert[$ktag]['goods_id']=$goods_id;
                $insert[$ktag]['tag_id']=$valtag;
                $insert[$ktag]['goods_centag']=$_POST['goodscommt_tag'][$valtag];
            }
            if(empty($is_insert)){
                Model('admintag')->insert_goods_admintag_all($insert);
            }else{
                Model('admintag')->delete_goods_admintag($where);
                Model('admintag')->insert_goods_admintag_all($insert);
            }
	}else{
           Model('admintag')->delete_goods_admintag($where);
	}
    }
    
    
    /**
     * @return 获取商品内部属性处理 Description
     * @param int $goods_id 产品id
     * @param string $types 空-新添加/有值-编辑
     */
    private function GoodsAttrSet($goods_id){
        $goods_type = isset($_POST['goods_type']) ? $_POST['goods_type'] : 0;
        $attrModel = Model('goods_attr');
        $wheresd['goods_id'] = $goods_id;
        $zhans = $attrModel->delete_goods_attr($wheresd);
        if(!empty($goods_type)){
            $attr_id_list = $_POST['attr_id_list'];
            $attr_value_list = $_POST['attr_value_list'];
            foreach ($attr_id_list as $key => $value_list){
                if(!empty($attr_value_list[$key])){
                    $insert['attr_id'] = $value_list;
                    $insert['goods_id'] = $goods_id;
                    $insert['attr_value'] = $attr_value_list[$key];
                    $insert['attr_price'] = 0;
                    $attrModel->insert_goods_attr($insert);
                }
            }
        }
    }


    /**
     * @return 同步购物车中的商品价格(修改编辑产品后时候使用)
    */
    function tongbu_cart_price($goods_id){
        $cartModel = Model('cart');
        $field = "cart.rec_id,cart.goods_id,cart.goods_attr_id,cart.user_id,cart.session_id,goods.market_price,goods.goods_fenxiao_price,goods.goods_price";
        $wheres = "cart.goods_id = ".$goods_id." AND cart.rec_type = ".CART_GENERAL_GOODS;
        $result = $cartModel->get_cart_goods_list($field,$wheres);
        if(!empty($result)){
            foreach ($result as $key => $value) {
                $where_user['user_id'] = $value['user_id'];
                $user_into = Model('users')->select_users_info('level_id',$where_user);
                if($user_into['level_id'] != 10){
                   $param['goods_price'] = $value['goods_fenxiao_price'];
                }else{
                   $param['goods_price'] = $value['goods_price'];
                }
                $param['market_price'] = $value['market_price'];
                $where_cart['rec_id'] = $value['rec_id'];
                $cartModel->update_cart($param,$where_cart);
            }

        }
    }


    /**
     * @return 添加修改产品公共数据包 
     * 
     */
    private function ShopPage($goods){
        if(!empty($goods['goods_id'])){//编辑
            $goods_infos = $goods;
            //产品标签分类id获取
            $fiels = "admintag.tag_class_id";
            $wherestag = "goods_admintag.goods_id = ".$goods['goods_id'];
            $tagclassinfo = Model('admintag')->select_admintag_goodstag_info($fiels,$wherestag);
            $goods_new_cats = explode(',',$goods_infos['cat_ids_new']);
            if(count($goods_new_cats)>3){
                $goods_new_cats = array_slice($goods_new_cats,0,3,true);
            }
            $goodscat['new_cats0'] = $goods_new_cats[0];
            $goodscat['new_cats1'] = $goods_new_cats[1];
            $goodscat['new_cats2'] = $goods_new_cats[2];
            Tpl::assign('goodscat', $goodscat);
        }else{//添加
            $goods_infos['goods_type'] = 0;
            $goods_infos['goods_id'] = 0;
            $tagclassinfo['tag_class_id'] = 1;
        }
        
        
        /* 属性 */
        Model('goods_attr')->delete_goods_attr('goods_id = 0');
	/* 标签 */
        Model('admintag')->delete_goods_admintag('goods_id = 0');
        /* 商家 */
        $whers_shop['status'] = 1;
        $supplielist = Model('supplier')->get_supplier_list('supplier_id,supplier_name','');
        Tpl::assign('Loading_object', $supplielist);
        
        /* 分类 */
        $whers_category['parent_id'] = 0;
        $whers_category['is_new_cat'] = 1;
        $new_cat_list = Model('category')->get_category_list('cat_id,cat_name',$whers_category);
        foreach($new_cat_list as $key => $value){
            $whers_category['parent_id'] = $value['cat_id'];
            $new_cat_list[$key]['new_cat_values'] = Model('category')->get_category_list('cat_id,cat_name',$whers_category);
        }
       
        Tpl::assign('new_cat_list', $new_cat_list);
        /*商品品牌*/
        $brand_info = Model('brand')->get_brand_list('brand_id,brand_name','');
        foreach ($brand_info AS $row){
            $brand_list[$row['brand_id']] = $row['brand_name'];
        }
        Tpl::assign('brand_list', $brand_list);
        
        /* 商品属性 */
        $goods_type_list = Model('goods')->get_goods_type_list('cat_id, cat_name','enabled = 1');
        $lst = '';
        foreach ($goods_type_list AS $value){
            $lst .= "<option value='$value[cat_id]'";
            $lst .= ($goods_infos['goods_type'] == $value['cat_id']) ? ' selected="true"' : '';
            $lst .= '>' . htmlspecialchars($value['cat_name']). '</option>';
        }
        Tpl::assign('goods_type_list', $lst);
         
        /* 商品标签 */
        $goods_tags = Model('admintag')->get_admintag_class_list('*','tag_class_id <> 1');
        $lsttag = '';
        foreach ($goods_tags AS $value){
            $lsttag .= "<option value='$value[tag_class_id]'";
            $lsttag .= ($tagclassinfo['tag_class_id'] == $value['tag_class_id']) ? ' selected="true"' : '';
            $lsttag .= '>' . htmlspecialchars($value['class_name']). '</option>';
        }
        Tpl::assign('goods_tagclass_list', $lsttag);
        
        /* 商品属性二级 */
        Tpl::assign('goods_attr_html', build_attr_html($goods_infos['goods_type'], $goods_infos['goods_id'],''));
        
        /* 商品标签二级 */
        Tpl::assign('goods_tags_html', build_tags_html($tagclassinfo['tag_class_id'], $goods_infos['goods_id'],'1'));
    }
}
