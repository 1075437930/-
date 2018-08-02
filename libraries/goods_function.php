<?php

/**
 * 淘玉php 产品公共方法
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 产品公共方法
 * $Id: goods_function.php 17217 2015-05-19 06:29:08Z 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!index');
    
 /**
 * @return 根据属性数组创建属性的表单string
 *
 * @access  public
 * @param   int     $cat_id     分类编号
 * @param   int     $goods_id   商品编号
 * @param   int     $bar_code   条形码加载
 * @return  string
 */
function build_attr_html($cat_id, $goods_id = 0 , $bar_code = 0)
{
    if (empty($cat_id))
    {
        $attr = array();
    }
   
    $files = "attribute.attr_id, attribute.attr_name, attribute.attr_input_type, attribute.attr_type,attribute.attr_txm, attribute.attr_values, goods_attr.attr_value, goods_attr.attr_price";
    $wheres = " attribute.cat_id = " . $cat_id ." OR attribute.cat_id = 0";
    $ons = "attribute.attr_id=goods_attr.attr_id AND goods_attr.goods_id = '$goods_id' ";
    $order = "attribute.sort_order,attribute.attr_type,attribute.attr_id,goods_attr.attr_price,goods_attr.goods_attr_id";
    $attr = Model('goods_attr')->get_attribute_goodsattr_list($files, $wheres,$ons,$order);
    
    $html = '<table width="100%" id="attrTable">';
    $spec = 0;
    foreach ($attr AS $key => $val)
    {
        $html .= "<tr><td class='label'>";		
        $html.=$val[attr_name]."：</td><td><input type='hidden' name='attr_id_list[]' value='$val[attr_id]' class='ctxm_$val[attr_txm]' />";

        if ($val['attr_input_type'] == 0)
        {
            $html .= '<input name="attr_value_list[]" type="text" value="' .htmlspecialchars($val['attr_value']). '" size="40" /> ';
        }
        elseif ($val['attr_input_type'] == 2)
        {
            $html .= '<textarea name="attr_value_list[]" rows="3" cols="40">' .htmlspecialchars($val['attr_value']). '</textarea>';
        }
        else
        {
           $html .= '<select class=attr_num_'.$val[attr_id].' name="attr_value_list[]" >';
           $html .= '<option value="">' .L('select_please'). '</option>';

            $attr_values = explode("\n", $val['attr_values']);
            
            foreach ($attr_values AS $opt)
            {
                $opt = trim(htmlspecialchars($opt));
                if($val['attr_value'] != $opt){
                    $html   .='<option value="' . $opt . '">' . $opt . '</option>';
                }else{
                    $html   .= '<option value="' . $opt . '" selected="selected">' . $opt . '</option>';
                }
            }
            $html .= '</select> ';
           
        }
        $html .= '</td></tr>';
    }
    $html .= '</table>';

 
    return $html;
}





/**
 * @return 根据标签分类查找对应的标签
 * @access  public
 * @param   int     $tagclass_id     分类编号
 * @param   int     $goods_id   商品编号
 * @param   int     $typesd   判断层级
 * @return  string
 * @Author 萤火虫 2017/9/2 星期六
 */


function build_tags_html($tagclass_id, $goods_id = 0,$typesd){
    if (empty($tagclass_id)){
        $taglist =  array();
    }else{
        if($typesd == 0){
                $andwher = "";
        }else{
                $andwher = " OR admintag.tag_class_id = 1 ";	
        }
        $files = "admintag.tag_words, admintag.tag_cent, admintag.tag_id, admintag.tag_class_id,goods_admintag.goods_centag,goods_admintag.goods_id";
        $wheres = " admintag.tag_class_id = " . $tagclass_id .$andwher;
        $order = "admintag.tag_id,goods_admintag.tags_id";
        $on = "admintag.tag_id = goods_admintag.tag_id AND goods_admintag.goods_id = '$goods_id' ";
        $taglist = Model('admintag')->get_admintag_goodstag_list($files,$wheres,$on,$order);
    }

    $html = '<table width="100%" id="tagslist" >';
    $spec = 0;
    foreach ($taglist AS $key => $val)
    {
        if(!empty($val['goods_centag'])){
                $goods_centag = $val['goods_centag'];
        }else{
                $goods_centag = '';
        }
        $html .= '<tr><td class="label">';		
		if(!empty($val['goods_id'])){
			$html.='<input type="checkbox" id="goodstagsd" name="goodstagsd[]" value="'.$val['tag_id'].'" checked="checked" onclick="handleBuy(this.checked);" />';
		}else{
			$html.='<input type="checkbox" id="goodstagsd" name="goodstagsd[]" value="'.$val['tag_id'].'" onclick="handleBuy(this.checked);" />';
		}
		$html.='<a title="'.$val['tag_cent'].'">'.$val['tag_words'].'</a></td>';
		$html.= '<td><input type="text" id="goodscommt_tag" name="goodscommt_tag['.$val['tag_id'].']" placeholder="勾选以后需要添加具体描述" value="'.$goods_centag.'" size="20" /></td>';
		$html.= '<td>'.$val['tag_cent'].'</td>';	
        $html.= '</tr>';
    }
    $html .= '</table>';
    return $html;
}


/** 
 * @return 格式化商品图片名称（按目录存储）boolean
 * @param file $source_img 图片file值
 * @param type $type 图片文件夹位置中最后一层名称判断是长图还是正方形图
 * @return boolean/
 */
function reformat_image_name($file_img,$type,$keys){
    $dir = 'images';
    if (defined('IMAGE_DIR'))
    {
        $dir = IMAGE_DIR;
    }
    $sub_dir = date('Ym', gmtime()).'/'.date('d', gmtime());
    if($type == 'chang'){
        $fileceng = "sourcechang_img";
    }else if($type == 'zheng'){
        $fileceng = "source_img";
    }else{
        $fileceng = "image";
    }
   
    $path = $dir.'/'.$sub_dir.'/'.$fileceng;
    $res = upload_oss_img($file_img, $path, $keys);
    if ($res['status']){
        return $res['url'];
    }else{
        return false;
    }
}

/** 
 * @return 商家产品格式化图片名称（按目录存储）boolean
 * @param file $source_img 图片file值
 * @param type $supplier 商家id 
 * @return boolean/  shangimages/71/201710/xqimg/
 */
function reformat_shopimage_name($file_img,$supplier,$keys){
    $path = 'shangimages/'.$supplier.'/'.date('Ym', gmtime()).'/xqimg';
    $res = upload_oss_img($file_img, $path, $keys);
    if ($res['status']){
        return $res['url'];
    }else{
        return false;
    }
}
