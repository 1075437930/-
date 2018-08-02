<?php

/**
 * 淘玉php 删除公共方法
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 删除公共方法
 * $Id: article.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!index');
	
	/**
	 * @return 删除单个入驻商所有相关信息
     * @param  $supplier_id int 入驻商id
     * @return void,完全成功返回true，不完全成功返回一维数组，包含错误信息
     */
	function delete_supplier_all($supplier_id){
		if($supplier_id && $supplier_id >0){			
			$where['supplier_id'] = $supplier_id;
            $msg = array();
            /*删除入驻商管理员信息*/
            $res = delete_table_info('supplier_admin_user', $where);
            if(!$res){
            	$msg[] = '删除入驻商管理员信息失败';
            }
            /*删除入驻商上传文章及图片*/
            $article_pics = get_one_table_list('supplier_article', 'file_url', $where, '','', 'find')['file_url'];
            if(!empty($article_pics)){
            	$article_pics = explode(',',$article_pics);
            	//ossdeleteObjects($article_pics);
            }            
            $res = delete_table_info('supplier_article', $where);
            if(!$res){
            	$msg[] = '删除入驻商文章失败';
            }
            /*删除入驻商商品分类及图片*/
            $category_pics = get_one_table_list('supplier_category', 'cat_pic', $where, '','', 'find')['cat_pic'];
            if(!empty($category_pics)){
            	$category_pics = explode(',',$category_pics);
            	//ossdeleteObjects($article_pics);
            }
            $res = delete_table_info('supplier_category', $where);
            if(!$res){
            	$msg[] = '删除入驻商商品分类失败';
            }
            /*删除入驻商推荐分类*/
            $res = delete_table_info('supplier_cat_recommend', $where);
            if(!$res){
            	$msg[] = '删除入驻商推荐分类失败';
            }
            /*删除入驻商商品分类*/
            $res = delete_table_info('supplier_goods_cat', $where);
            if(!$res){
            	$msg[] = '删除入驻商商品分类关系失败';
            }
            /*删除入驻商关注*/
            $wheres['supplierid'] = $supplier_id;
            $res = delete_table_info('supplier_guanzhu', $wheres);
            if(!$res){
            	$msg[] = '删除入驻商关注失败';
            }
            /*删除入驻商资金变动日志*/
            $res = delete_table_info('supplier_money_log', $where);
            if(!$res){
            	$msg[] = '删除入驻商资金变动日志失败';
            }
            /*删除入驻商佣金支付记录*/
            $res = delete_table_info('supplier_rebate_log', $where);
            if(!$res){
            	$msg[] = '删除佣金支付日志失败';
            }
            /*删除入驻商导航栏配置*/
            $res = delete_table_info('supplier_nav', $where);
            if(!$res){
            	$msg[] = '删除入驻商导航栏配置失败';
            }
            /*删除入驻商店铺设置*/
            $res = delete_table_info('supplier_shop_config', $where);
            if(!$res){
            	$msg[] = '删除入驻商店铺设置失败';
            }
            /*删除入驻商店铺信息及图片*/
            $street_pics = get_one_table_list('supplier_street', 'logo', $where, '','', 'find')['logo'];
            if(!empty($street_pics)){
            	$street_pics = explode(',',$street_pics);
            	//ossdeleteObjects($street_pics);
            }
            $res = delete_table_info('supplier_street', $where);
            if(!$res){
            	$msg[] = '删除入驻商商品分类失败';
            }
            /*删除入驻商标签*/
            $res = delete_table_info('supplier_tag_map', $where);
            if(!$res){
            	$msg[] = '删除入驻商标签关系失败';
            }
            /*删除入驻商商品*/
            $goods_ids = Model('goods')->get_goods_list('goods_id', $where);
            foreach ($goods_ids as $key => $goods_id) {
            	delete_goods_all($goods_id['goods_id']);
            }
            /*删除入驻商*/                        
            if(empty($msg)){
            	$pic_fields = 'shop_logo,shop_header_text,zhizhao,handheld_idcard,idcard_front,idcard_reverse';            	
	            $supplier_pics = get_one_table_list('supplier', $pic_fields, $where, '','', 'find');
				$str = $supplier_pics['shop_logo'].','.$supplier_pics['shop_header_text'].','.$supplier_pics['zhizhao'].','
				.$supplier_pics['handheld_idcard'].','.$supplier_pics['idcard_front'].','.$supplier_pics['idcard_reverse'];
				if(!empty($str)){
					$supplier_pics = explode(',',$str);
					foreach ($supplier_pics as $key => $value) {
						if(!$value){
							unset($supplier_pics[$key]);
						}
					}
					if(!empty($supplier_pics)){
						//ossdeleteObjects($supplier_pics);
					}				
				}			
            	$res = delete_table_info('supplier', $where);
				if(!$res){
	            	$msg[] = '删除入驻商失败';
	            	return $msg;
	            }
            	return true;	
            } else {
            	return $msg;
            }
		} else {
			exit('param error');
		}
	}

	/**
	 * @return 删除单个商品所有相关信息
     * @param  $goods_id int 商品id
     * @return array
     */
	function delete_goods_all($goods_id){
		if($goods_id && $goods_id > 0){
            $where = " goods_id =" . $goods_id;
            $msg = array();
            /*删除商品促销活动*/
            $res = delete_table_info('goods_activity', $where);
            if(!$res){
            	$msg[] = '删除商品促销活动失败';
            }
            /*删除商品属性信息*/
            $res = delete_table_info('goods_attr', $where);
            if(!$res){
            	$msg[] = '删除商品属性失败';
            }
            /*删除商品拓展分类信息*/
            $res = delete_table_info('goods_cat', $where);
            if(!$res){
            	$msg[] = '删除商品拓展分类失败';
            }
            /*删除商品标签*/
            $res = delete_table_info('goods_tag', $where);
            if(!$res){
            	$msg[] = '删除商品标签失败';
            }
            /*删除商品货品信息*/
            $res = delete_table_info('products', $where);
            if(!$res){
            	$msg[] = '删除商品货品失败';
            }
            /*删除商品相册*/
            $field = 'img_url,thumb_url,img_original';
            $pic_urls = get_one_table_list('goods_gallery', $field, $where, '','', 'select');
            $tmp = array();
            foreach ($pic_urls as $key => $value) {            	
            	if(!empty($value['img_original'])) {
            		$tmp[] = $value['img_original'];
            	}            	
            }
            if(!empty($tmp)){
				//ossdeleteObjects($tmp);
			}
			$res = delete_table_info('goods_gallery', $where);
            if(!$res){
            	$msg[] = '删除商品相册失败';
            }
            /*删除商品*/                        
            if(empty($msg)){
            	/*取得商品的图片信息*/            	
	            $goods_pic = get_one_table_list('goods', 'original_img', $where, '','', 'find');
				/*删除商品的oss图片*/
				if(!empty($goods_pic)){
					//ossdeleteObjects($goods_pic);
				}
				/*删除商品数据库记录*/	
            	$res = delete_table_info('goods', $where);
				if(!$res){
	            	$msg[] = '删除商品失败';
	            	return $msg;
	            }
            	return true;	
            } else {
            	return $msg;
            }            
		} else {
			exit('param error');
		}
	}

    /*
    * 删除前台首页HTML静态文件（商品详情、文章详情、）
    */

    function clearhtml_index_file()
    {

        $dir = ROOT_PATH.'taoyuphp/mian_shop';
        $file = $dir. "/temp/compiled/index.htm.php";

        @unlink($file);
        echo 1;

    }

    /*
    *  删除前台所有HTML静态文件
    */
    function clearhtml_all()
    {
        $dir = ROOT_PATH.'taoyuphp/mian_shop/temp/compiled';
        deldir($dir);
        echo 1;
    }

    /*
    *  删除文件夹下所有的文件
    */
    function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    @unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }

        closedir($dh);
    }

   /*
    *  删除缓存
    */
    function clear_all_files(){
        $dir = ROOT_PATH.'taoyuphp/mian_shop/temp/static_caches';
        deldir($dir);
        $dir2 = ROOT_PATH.'taoyuphp/mian_shop/temp/caches';
        deldir($dir2);
        echo 1;
    }


