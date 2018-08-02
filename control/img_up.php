<?php

/**
 * 淘玉php 后台产品正方形上传图片类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萤火虫 $
 * 后台产品正方形上传图片类
 * $Id: img_up.php  2018-04-07   萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');
class img_upControl extends BaseControl {
    protected $nums = 0;
    public function __construct() {
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
   /**
    *  @return 起时判断 
    */
   public function index(){
       $goods_id = $_REQUEST['goods_id'] ? $_REQUEST['goods_id'] : 0;
       $shop_id = $_REQUEST['shop_id'] ? $_REQUEST['shop_id'] : 0;//商家id
       if(!$goods_id ){
            echo '<center><br>请先提交第一步后，再来添加产品相册</center>';
            exit;
        }else{
            /* 载入上传图片插件带页面 */
           $this->updateimg_html($goods_id,$shop_id);
        }
   } 
   
    /**
    * @return 上传属性相册  
    */
   public function upload(){
       $goods_id = $_REQUEST['goods_id'] ? $_REQUEST['goods_id'] : 0;
       $shop_id = $_REQUEST['shop_id'] ? $_REQUEST['shop_id'] : 0;
       $array = array('error'=>1,'goods_id'=>0,'info'=>'fail');
       $nums = rand(100,999);
       $array = $this->handle_gallery_image_attr($goods_id, $_FILES['file'],$nums,$shop_id);
       die(json_encode($array));
   } 
   /**
    * @return 删除图片  
    */
   public function drop_image(){
        $img_id = empty($_REQUEST['img_id']) ? 0 : intval($_REQUEST['img_id']);
         /* 删除图片文件 */
        $goodsmodel = Model('goods');
        $where['img_id'] = $img_id;
        $row = $goodsmodel->select_goods_gallery_info('img_original', $where);
        if ($row['img_original'] != ''){
            $where_img['original_img'] = $row['img_original'];
            $jieguo = $goodsmodel->select_goods_info('original_img', $where_img);
            if(!empty($jieguo)){
                 echo '<br>对不起，该图片为属性图片不能删除';
            }else{
                 ossDeleteFileObject($row['img_original']);
                 $goodsmodel->delete_goods_gallery($where);
            }
        }
        /* 删除数据 */
       $goods_id = $_REQUEST['goods_id'] ? $_REQUEST['goods_id'] : 0;
       $this->updateimg_html($goods_id);
   } 
   
   
   /**
    * @return 设置图片排序  
    */
   public function set_sort_img(){
       $sort = (isset($_POST['sort']) && intval($_POST['sort']) > 0) ? intval($_POST['sort']) : 0;
       $param['img_sort'] = $sort;
       $where['img_id'] = $_POST['img_id'];
       Model('goods')->update_goods_gallery($param,$where);
       $goods_id = $_REQUEST['goods_id'] ? $_REQUEST['goods_id'] : 0;
       $this->updateimg_html($goods_id);
   } 
   
   /**
    * @return 保存某商品的相册图片
    * @param   int     $goods_id  商品id
    * @param   int     $goods_attr_id 商品属性id
    * @param   filee   $image_files  商品图片信息
    * @param   int   $xiao  上传图片的随机数
    * @param   int   $shop  是否是商家上传 1是 0不是
    */

   private function handle_gallery_image_attr($goods_id,$image_files,$xiao,$shop_id){
        /* 是否成功上传 */
       if(empty($shop_id)){
          $img_original = reformat_image_name($image_files,'zheng',$xiao);
       }else{
          $img_original = reformat_shopimage_name($image_files,$shop_id,$xiao);
       }

       if (empty($img_original)){
            showMessage(L('upload_img_mian_errer'));
        }else{
            $insert['goods_id'] = $goods_id;
            $insert['img_original'] = $img_original;
            $insert['goods_attr_id'] = 0;
            $zhangs = Model('goods')->insert_goods_gallery($insert);
        }           
        return array('error'=>0,'goods_id'=>$goods_id,'info'=>'succesful');
    }
    
    /**
     * @return 加载相册上传图片内容 Description
     * @param int $goods_id 产品
     */
    private function updateimg_html($goods_id,$shop_id){
        $goods_attr_id = 0;
        $htmlarray = '';
        $goodsModel = Model('goods');
        $wheres['goods_id'] = $goods_id;
        $order = " img_sort ASC,img_id ASC ";
        $img_list = $goodsModel->get_goods_gallery_list('*',$wheres,$order);
        if (!empty($img_list)){
            foreach($img_list as $img){
                $htmlarray .= "<div class='gallery_img_box'>";
                $img['img_original'] = get_imgurl_oss($img['img_original'],100,100);
                $htmlarray .= '<img src="'.$img['img_original'].'"  class="gallery_img">';
                $htmlarray .= '<br><br><a href="index.php?act=img_up&op=drop_image&img_id='. $img['img_id'] .'&goods_id='. $goods_id.'" onclick="javascript: return  (confirm(\'确认删除此图片吗\'))">[删除]</a> <br>顺序:<input type="text" name="sort" value="'.$img['img_sort'].'" style="width:20px;" onblur="set_sort(this.value,'.$img['img_id'].')">';
                $htmlarray .= '</div>';
            }
        }else{
            echo '<br>对不起，该属性下还未上传任何图片！';
            exit;
        }
        $htmls =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <title>上传</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head>
        <style>
        *{ margin:0; padding:0;}
        body{font-size:12px;}
        .gallery_box{width:100%;height:auto; margin:10px 15px 0px;}
        .gallery_img_box{float:left;width:120px;border:1px solid #eeeeee;padding:10px;margin-right:11px;margin-bottom:11px;text-align:center;position:relative;}
        .gallery_img{width:100px;height:100px;}
        .blank{height:10px; line-height:10px; clear:both; visibility:hidden;}
        .shuxingtupian{color:#fff;background:#ff3300;}
        #box{ margin:0px 15px; width:97%; min-height:35px; background:#FF9; margin-top:10px;}
        </style>
        <script src="'.ADMIN_TEMPLATES_URL.'/js/diyUpload/js/jquery.js"></script>
        <link rel="stylesheet" type="text/css" href="'.ADMIN_TEMPLATES_URL.'/js/diyUpload/css/webuploader.css">
        <link rel="stylesheet" type="text/css" href="'.ADMIN_TEMPLATES_URL.'/js/diyUpload/css/diyUpload.css">
        <script type="text/javascript" src="'.ADMIN_TEMPLATES_URL.'/js/diyUpload/js/webuploader.html5only.min.js"></script>
        <script type="text/javascript" src="'.ADMIN_TEMPLATES_URL.'/js/diyUpload/js/diyUploadxiang.js"></script>
        <body>
        <div id="box">
            <div id="test" ></div>
        </div>
        <div class="gallery_box">'
        .$htmlarray
        .'</div>
            <form name="setsort" id="setsort" action="index.php" method="post">
                <input type="hidden" name="goods_id" id="goods_id" value="'.$goods_id.'">
                <input type="hidden" name="img_id" id="img_id" value="">
                <input type="hidden" name="sort" id="sort" value="">
                <input type="hidden" name="act" value="img_up">
                <input type="hidden" name="op" value="set_sort_img">
            </form>
        </body><script type="text/javascript">'
        ."$('#test').diyUpload({
            url:'index.php?act=img_up&op=upload&goods_id=$goods_id&shop_id=$shop_id ',
            success:function( data ) {
                    console.info( data );
            },
            finished:function(){
                    window.location.reload();
            },
            error:function( err ) {
                    console.info( err );
            }
        });
        function set_sort(value,id){
            $('#img_id').val(id);
            $('#sort').val(value);
            $('#setsort').submit()
        }
        </script>
        </html>";
        echo $htmls;
        exit;
    }
}

