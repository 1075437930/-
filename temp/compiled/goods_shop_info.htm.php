


<?php echo self::fetch('pageheader_bd.htm'); ?> 
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/selectzone_bd.js,js/colorselector.js')); ?>

<script language="JavaScript">

<?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";

<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


</script>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<?php echo self::smarty_insert_scripts(array('files'=>'js/jquery.ztree.all-3.5.min.js,js/validator.js')); ?> 
<?php if (self::$_var['warning']): ?>
<ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
	<li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;"><?php echo self::$_var['warning']; ?></li>
</ul>
<?php endif; ?>

<div class="tab-div">
	
	<div id="tabbar-div">
            <p>
                <span class="tab-front" id="general-tab"><?php echo self::$_var['lang']['tab_general']; ?></span>
                <span class="tab-back" id="mix-tab"><?php echo self::$_var['lang']['tab_mix']; ?></span>
                <span class="tab-back" id="properties-tab"><?php echo self::$_var['lang']['tab_properties']; ?></span>
                <span class="tab-back" id="goodstag-tab" >商品标签</span>
                <span class="tab-back" id="gallery-tab"><?php echo self::$_var['lang']['tab_gallery']; ?></span>
                
<!--                <span class="tab-back" id="gallerychang-tab"><?php echo self::$_var['lang']['tab_gallerychang']; ?></span> -->
            </p>
	</div>
	
	<div id="tabbody-div">
		<form enctype="multipart/form-data" action="index.php" method="post" name="theForm">
   
      		
			<table width="100%" id="general-table" align="center">
				<tr>
                                    <td class="label"><?php echo self::$_var['lang']['lab_goods_name']; ?></td>
                                    <td>
                                        <input type="text" name="goods_name" value="<?php echo htmlspecialchars(self::$_var['goods']['goods_name']); ?>" size="20" />
                                        <?php echo self::$_var['lang']['require_field']; ?>
                                    </td>
				</tr>
				<tr>
                                    <td class="label">
                                        <a href="javascript:showNotice('noticeGoodsSN');" title="<?php echo self::$_var['lang']['form_notice']; ?>">
                                                <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>">
                                        </a>
                                        <?php echo self::$_var['lang']['lab_goods_sn']; ?>
                                    </td>
                                    <td>
                                        <input type="text" name="goods_sn" value="<?php echo htmlspecialchars(self::$_var['goods']['goods_sn']); ?>" size="20" onblur="checkGoodsSn(this.value,'<?php echo self::$_var['goods']['goods_id']; ?>')" />
                                        <span id="goods_sn_notice"></span>
                                        <br />
                                        <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeGoodsSN"><?php echo self::$_var['lang']['notice_goods_sn']; ?></span>
                                    </td>
				</tr>
                                <tr>
                                    <td class="label"><?php echo self::$_var['lang']['lab_new_cat']; ?></td>
                                    <td>
                                        <select name="new_cat[]">
                                            <option value="0"><?php echo self::$_var['lang']['select_qixing']; ?></option>
                                            <?php $_from = self::$_var['new_cat_list']['0']['new_cat_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'new_cat_values');if (count($_from)):
    foreach ($_from AS self::$_var['new_cat_values']):
?>
                                                <?php if (self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats0'] || self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats1'] || self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats2']): ?>
                                                        <option value="<?php echo self::$_var['new_cat_values']['cat_id']; ?>" selected="selected"><?php echo self::$_var['new_cat_values']['cat_name']; ?></option>
                                                <?php else: ?>
                                                        <option value="<?php echo self::$_var['new_cat_values']['cat_id']; ?>"><?php echo self::$_var['new_cat_values']['cat_name']; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                                        </select>
                                        <select name="new_cat[]">
                                            <option value="0"><?php echo self::$_var['lang']['select_cailiao']; ?></option>
                                            <?php $_from = self::$_var['new_cat_list']['1']['new_cat_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'new_cat_values');if (count($_from)):
    foreach ($_from AS self::$_var['new_cat_values']):
?>
                                                <?php if (self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats0'] || self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats1'] || self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats2']): ?>
                                                    <option value="<?php echo self::$_var['new_cat_values']['cat_id']; ?>" selected="selected"><?php echo self::$_var['new_cat_values']['cat_name']; ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo self::$_var['new_cat_values']['cat_id']; ?>"><?php echo self::$_var['new_cat_values']['cat_name']; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                                        </select>
                                        <select name="new_cat[]">
                                            <option value="0"><?php echo self::$_var['lang']['select_gongyi']; ?></option>
                                            <?php $_from = self::$_var['new_cat_list']['2']['new_cat_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'new_cat_values');if (count($_from)):
    foreach ($_from AS self::$_var['new_cat_values']):
?>
                                                <?php if (self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats0'] || self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats1'] || self::$_var['new_cat_values']['cat_id'] == self::$_var['goodscat']['new_cats2']): ?>
                                                    <option value="<?php echo self::$_var['new_cat_values']['cat_id']; ?>" selected="selected"><?php echo self::$_var['new_cat_values']['cat_name']; ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo self::$_var['new_cat_values']['cat_id']; ?>"><?php echo self::$_var['new_cat_values']['cat_name']; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                                        </select>
                                    </td>
				</tr>
                                
                                <tr>
                                    <td class="label"><?php echo self::$_var['lang']['lab_goods_brand']; ?></td>
                                    <td>
                                        <select name="goods_brand">
                                            <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
                                            <?php echo self::html_options(array('options'=>self::$_var['brand_list'],'selected'=>self::$_var['goods']['brand_id'])); ?>
                                        </select>
                                    </td>
				</tr>
                                <tr>
                                    <td class="label"><?php echo self::$_var['lang']['lab_shop_price']; ?></td>
                                    <td>
                                        <input type="text" name="shop_price" value="<?php echo self::$_var['goods']['shop_price']; ?>" size="20" onblur="priceSetted()"/>
                                        <input type="button" value="<?php echo self::$_var['lang']['compute_by_mp']; ?>" onclick="marketPriceSetted()" />
                                        <?php echo self::$_var['lang']['require_field']; ?>
                                    </td>
				</tr>
                                <tr>
                                    <td class="label"><?php echo self::$_var['lang']['lab_market_price']; ?></td>
                                    <td>
                                        <input type="text" name="market_price" value="<?php echo self::$_var['goods']['market_price']; ?>" size="20" />
                                        <input type="button" value="<?php echo self::$_var['lang']['integral_market_price']; ?>" onclick="integral_market_price()" />
                                    </td>
				</tr>
                                
				<tr>
                                    <td class="label">
                                        <a href="javascript:showNotice('fenxiao');" title="<?php echo self::$_var['lang']['form_notice']; ?>">
                                            <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>">
                                        </a>
                                        <?php echo self::$_var['lang']['fenxiaopic']; ?>
                                    </td>
                                    <td>
                                        <input type="text" name="fenxiao" value="<?php echo self::$_var['goods']['goods_fenxiao_price']; ?>" size="20" /><br />
                                        <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="fenxiao"><?php echo self::$_var['lang']['fenxiao_integral']; ?></span>
                                    </td>
				</tr>
                                <tr>
                                    <td class="label">
                                        <?php echo self::$_var['lang']['Loading_object']; ?>
                                    </td>
                                    <td>
                                        <select name="supplier_id" id="supplier_id" >
                                                <?php $_from = self::$_var['Loading_object']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'object');if (count($_from)):
    foreach ($_from AS self::$_var['object']):
?>
                                                    <?php if (self::$_var['goods']['supplier_id'] == self::$_var['object']['supplier_id']): ?>
                                                        <option value="<?php echo self::$_var['object']['supplier_id']; ?>" selected="selected"><?php echo self::$_var['object']['supplier_name']; ?></option>
                                                    <?php else: ?>
                                                        <option value="<?php echo self::$_var['object']['supplier_id']; ?>"><?php echo self::$_var['object']['supplier_name']; ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                                         </select>
                                    </td>
				</tr>    
                                 <tr>
                                    <td class="label">
                                        是否允许使用代金券
                                    </td>
                                    <td>
                                        <input type="radio" name="coupons_n" value="0" <?php if (self::$_var['goods']['coupons_type'] == 0): ?>checked="checked" <?php endif; ?> />
                                            不允许
                                        <input type="radio" name="coupons_n" value="1" <?php if (self::$_var['goods']['coupons_type'] == 1): ?>checked="checked" <?php endif; ?> />
                                            允许
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="label"><?php echo self::$_var['lang']['lab_picture']; ?></td>
                                    <td>
                                        <input type="file" name="goods_img" size="35" />
                                       <?php echo self::$_var['lang']['lab_picture_url']; ?><input type="text" size="40" value="<?php echo self::$_var['goods']['original_img']; ?>" name="goods_img_url" />
                                    </td>
                                </tr>
                                <tr id="auto_thumb_1">
                                    <td class="label"><?php echo self::$_var['lang']['lab_video']; ?></td>
                                    <td id="auto_thumb_3">
                                        <input type="text" size="40" value="<?php echo self::$_var['goods']['goods_video']; ?>"  name="goods_video_url" />
                                    </td>
                                </tr>
				<tr>
                                    <td class="label">
                                        <label for="is_promote">
                                            <input type="checkbox" id="is_promote" name="is_promote" value="1" <?php if (self::$_var['goods']['is_promote']): ?>checked="checked" <?php endif; ?> onclick="handlePromote(this.checked);" />
                                            <?php echo self::$_var['lang']['lab_promote_price']; ?>
                                        </label>
                                    </td>
                                    <td id="promote_3">
                                        <input type="text" id="promote_1" name="promote_price" value="<?php echo self::$_var['goods']['promote_price']; ?>" size="20" />
                                    </td>
				</tr>                                
				<tr id="promote_4">
                                    <td class="label" id="promote_5"><?php echo self::$_var['lang']['lab_promote_date']; ?></td>
                                    <td id="promote_6">
                                        <input name="promote_start_date" type="text" id="promote_start_date" size="12" value='<?php echo self::$_var['goods']['promote_start_date']; ?>' readonly="readonly" onfocus="return showCalendar('promote_start_date', '%Y-%m-%d', false, false, 'promote_start_date');" />
                                        -
                                        <input name="promote_end_date" type="text" id="promote_end_date" size="12" value='<?php echo self::$_var['goods']['promote_end_date']; ?>' readonly="readonly" onfocus="return showCalendar('promote_end_date', '%Y-%m-%d', false, false, 'promote_end_date');" />
                                    </td>
                                 </tr>
                                 <tr>
					<td class="label">审核状态</td>
					<td>
						<input type="radio" name="supplier_status" value="1" <?php if (self::$_var['goods']['supplier_status'] == '1'): ?>checked=checked<?php endif; ?>>
						审核通过
						<input type="radio" name="supplier_status" value="0" <?php if (self::$_var['goods']['supplier_status'] == '0'): ?>checked=checked<?php endif; ?>>
						未审核
						<input type="radio" name="supplier_status" value="-1" <?php if (self::$_var['goods']['supplier_status'] == '-1'): ?>checked=checked<?php endif; ?>>
						审核未通过
					</td>
				</tr>
				<tr>
					<td class="label">审核消息</td>
					<td>
						<textarea id="supplier_status_txt" name="supplier_status_txt" rows=4 cols=50><?php echo self::$_var['goods']['supplier_status_txt']; ?></textarea>
					</td>
				</tr>
			</table>
                        
			<table width="90%" id="mix-table" style="display:none" align="center">
                            <tr>
                                <td class="label"><?php echo self::$_var['lang']['lab_goods_weight']; ?></td>
                                <td>
                                    <input type="text" name="goods_weight" value="<?php echo self::$_var['goods']['goods_weight']; ?>" size="20" />克
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo self::$_var['lang']['lab_goods_size']; ?></td>
                                <td>
                                    <input type="text" name="goods_size" value="<?php echo self::$_var['goods']['goods_size']; ?>" placeholder="格式：30.6*50.9*11.6mm" size="20" />
                                </td>
                            </tr>
                            <tr>
                                <td class="label">材料</td>
                                <td>
                                    <input type="text" name="goods_cailiao" value="<?php echo self::$_var['goods']['goods_cailiao']; ?>" placeholder="格式：山流水" size="20" />
                                </td>
                            </tr>
                            <tr>
                                <td class="label">产地</td>
                                <td>
                                    <input type="text" name="goods_area" value="<?php echo self::$_var['goods']['goods_area']; ?>" placeholder="格式：俄罗斯" size="20" />
                                </td>
                            </tr>
                            <tr>
                                <td class="label">等级</td>
                                <td>
                                    <input type="text" name="goods_dengji" value="<?php echo self::$_var['goods']['goods_dengji']; ?>" placeholder="格式：1级白" size="20" />
                                </td>
                            </tr>
                            <tr>
                                <td class="label">库存</td>
                                <td>
                                    <input type="text" name="goods_number" value="<?php if (self::$_var['goods']['goods_number']): ?><?php echo self::$_var['goods']['goods_number']; ?><?php else: ?>1 <?php endif; ?>" placeholder="1" size="20" />
                                </td>
                            </tr>
                            <tr id="alone_sale_1">
                                    <td class="label" id="alone_sale_2"><?php echo self::$_var['lang']['lab_is_on_sale']; ?></td>
                                    <td id="alone_sale_3">
                                            <input type="checkbox" name="is_on_sale" value="1" <?php if (self::$_var['goods']['is_on_sale']): ?>checked="checked" <?php endif; ?> />
                                            <?php echo self::$_var['lang']['on_sale_desc']; ?>
                                    </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo self::$_var['lang']['lab_keywords']; ?></td>
                                <td>
                                    <input type="text" name="keywords" value="<?php echo htmlspecialchars(self::$_var['goods']['keywords']); ?>" size="40" />
                                    <?php echo self::$_var['lang']['notice_keywords']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo self::$_var['lang']['lab_goods_brief']; ?></td>
                                <td>
                                    <textarea name="goods_brief" cols="40" rows="3"><?php echo htmlspecialchars(self::$_var['goods']['goods_brief']); ?></textarea>
                                </td>
                            </tr>
                        </table>
      		 
			<table width="100%" id="properties-table" style="display:none" align="center">
				<tr>
                                    <td class="label">
                                            <a href="javascript:showNotice('noticeGoodsType');" title="<?php echo self::$_var['lang']['form_notice']; ?>">
                                                    <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>">
                                            </a>
                                            <?php echo self::$_var['lang']['lab_goods_type']; ?>
                                    </td>
                                    <td>
                                        <select name="goods_type" onchange="getAttrList(<?php echo self::$_var['goods']['goods_id']; ?>)">
                                            <option value="0"><?php echo self::$_var['lang']['sel_goods_type']; ?></option>
                                            <?php echo self::$_var['goods_type_list']; ?>
                                        </select>
                                        <br />
                                        <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeGoodsType"><?php echo self::$_var['lang']['notice_goods_type']; ?></span>
                                    </td>
				</tr>
				<tr>
                                    <td id="tbody-goodsAttr" colspan="2" style="padding:0"><?php echo self::$_var['goods_attr_html']; ?></td>
				</tr>
			</table>
			
			<table width="100%" id="gallery-table" style="display:none" align="center">
				
				<tr>
                                    <td>
                                        <style>
                                            .attr-color-div {
                                                width: 100%;
                                                background: #BBDDE5;
                                                text-indent: 10px;
                                                height: 26px;
                                                padding-top: 1px;
                                            }

                                            .attr-front {
                                                background: #CCFF99;
                                                line-height: 24px;
                                                font-weight: bold;
                                                padding: 6px 15px 6px 18px;
                                            }

                                            .attr-back {
                                                color: #FF0000;
                                                font-weight: bold;
                                                line-height: 24px;
                                                padding: 6px 15px 6px 18px;
                                                border-right: 1px solid #FFF;
                                            }
                                        </style>
       
                                            <font color=#ff3300>上传正方形图片</font>
                                            （本图片用于列表等展示）
                                            <br>
                                            <br>
                                            <div class="attr-color-div">
                                                <span class="attr-back" id="color_1"><a>正方形图</a></span>
                                            </div>
                                            <iframe name="attr_upload" src="index.php?act=img_up&goods_id=<?php echo self::$_var['goods']['goods_id']; ?>&shop_id=<?php echo self::$_var['goods']['supplier_id']; ?>" frameborder=1 scrolling=no width="100%" height="auto" style="min-height:630px; border:1px #eee solid; margin-top:5px;"> </iframe>
					</td>
				</tr>
				<tr>
                                    <td>&nbsp;</td>
				</tr>
			</table>

			
<!--
			代码修改 by guo  start
			<table width="100%" id="gallerychang-table" style="display:none" align="center">
				 图片列表 
				<tr>
                                    <td>
                                        <style>
                                                .attr-color-div {
                                                        width: 100%;
                                                        background: #BBDDE5;
                                                        text-indent: 10px;
                                                        height: 26px;
                                                        padding-top: 1px;
                                                }

                                                .attr-front {
                                                        background: #CCFF99;
                                                        line-height: 24px;
                                                        font-weight: bold;
                                                        padding: 6px 15px 6px 18px;
                                                }

                                                .attr-back {
                                                        color: #FF0000;
                                                        font-weight: bold;
                                                        line-height: 24px;
                                                        padding: 6px 15px 6px 18px;
                                                        border-right: 1px solid #FFF;
                                                }
                                        </style>

                                        <font color=#ff3300>上传长方形图片</font>
                                        （本图片用于用户下载原图和详细展示使用）
                                        <br>
                                        <br>
                                        <div class="attr-color-div">
                                            <span class="attr-back" id="color_1"><a href="" target="attr_upload">长方形图</a></span>
                                        </div>
                                        <iframe name="attr_upload" src="index.php?act=imgchang_up&goods_id=<?php echo self::$_var['goods']['goods_id']; ?>" frameborder=1 scrolling=no width="100%" height="auto" style="min-height:630px; border:1px #eee solid; margin-top:5px;"> </iframe>
                                    </td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>-->
			 
			<table width="100%" id="goodstag-table" style="display:none" align="center">
				<tr>
                                    <td class="label">
                                        <a href="javascript:showNotice('tag_class_mags');" title="<?php echo self::$_var['lang']['form_notice']; ?>">
                                                <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>">
                                        </a>
                                        商品标签分类
                                    </td>
                                    <td>
                                        <select name="goods_tag" onchange="getTagList(<?php echo self::$_var['goods']['goods_id']; ?>)">
                                                <option value="0">请选择对应分类</option>
                                                <?php echo self::$_var['goods_tagclass_list']; ?>
                                        </select>
                                        <br />
                                        <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="tag_class_mags">选择商品标签分类，然后添加相关标签</span>
                                    </td>
				</tr>
				<tr>
                                    <td id="tbody-goodsTag" colspan="2" style="padding:0"><?php echo self::$_var['goods_tags_html']; ?></td>
				</tr>
			</table>
			
			<div class="button-div">
				<input type="hidden" name="goods_id" value="<?php echo self::$_var['goods']['goods_id']; ?>" />
				
				
				<input id="goods_info_submit" type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" onclick="return shujupanduan()"/>
				
				<input id="goods_info_reset" type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
				

			</div>
			<input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
                        <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />
		</form>
	</div>
</div>


<script language="JavaScript">
    document.getElementById("tabbar-div").onmouseover = function(e){
        var obj = Utils.srcElement(e);
        if (obj.className == "tab-back"){
            obj.className = "tab-hover";
        }
    }
    document.getElementById("tabbar-div").onmouseout = function(e){
        var obj = Utils.srcElement(e);
        if (obj.className == "tab-hover"){
            obj.className = "tab-back";
        }
    }
    document.getElementById("tabbar-div").onclick = function(e){
        var obj = Utils.srcElement(e);
        if (obj.className == "tab-front"){
            return;
        }else{
            objTable = obj.id.substring(0, obj.id.lastIndexOf("-")) + "-table";
            var tables = document.getElementsByTagName("table");
            var spans  = document.getElementsByTagName("span");
            for (i = 0; i < tables.length; i++){
                if (tables[i].id == objTable){
                    tables[i].style.display = (Browser.isIE) ? "block" : "table";
                }else{
                    var qita = tables[i].id.indexOf('-');
                    if(qita != -1){
                        tables[i].style.display = "none";
                    }
                }
            }
            for (i = 0; spans.length; i++){
                if (spans[i].className == "tab-front"){
                    spans[i].className = "tab-back";
                    obj.className = "tab-front";
                    break;
                }
            }
        }
    }
    //代码修改 by guo  start
    function shujupanduan()
    {
            var newcat = document.forms['theForm'].elements['new_cat[]'];

            if(newcat[0].value==0 ||newcat[1].value==0||newcat[2].value==0){
                    alert("新版分类三个均为必选项！");
                    return false;
            }
    }
    //代码修改 by guo  end
    var goodsId = '<?php echo self::$_var['goods']['goods_id']; ?>';
    var elements = document.forms['theForm'].elements;
    var marketPriceRate = <?php echo empty(self::$_var['setting_config']['market_price_rate']) ? '1' : self::$_var['setting_config']['market_price_rate']; ?>;
    var integralPercent = <?php echo empty(self::$_var['setting_config']['integral_percent']) ? '0' : self::$_var['setting_config']['integral_percent']; ?>;
    
    onload = function()
    {
        handlePromote(document.forms['theForm'].elements['is_promote'].checked);

        if (document.forms['theForm'].elements['auto_thumb'])
        {
            handleAutoThumb(document.forms['theForm'].elements['auto_thumb'].checked);
        }

        document.forms['theForm'].reset();
    }
  
  /**
   * 切换商品类型
   */
  function getAttrList(goodsId)
  {
      var selGoodsType = document.forms['theForm'].elements['goods_type'];
      if (selGoodsType != undefined)
      {
          var goodsType = selGoodsType.options[selGoodsType.selectedIndex].value;
          Ajax.call('index.php?act=shop&op=get_attr', 'goods_id=' + goodsId + "&goods_type=" + goodsType, setAttrList, "GET", "JSON");
      }
  }


    /**
   * 切换商品标签类型
   */
  function getTagList(goodsId)
  {
      var selGoodsType = document.forms['theForm'].elements['goods_tag'];
	
      if (selGoodsType != undefined)
      {
          var goodsType = selGoodsType.options[selGoodsType.selectedIndex].value;
          
          Ajax.call('index.php?act=shop&op=get_tags', 'goods_id=' + goodsId + "&goods_tag=" + goodsType, setTagList, "GET", "JSON");
      }
  }

 function setTagList(result, text_result)
  {
    document.getElementById('tbody-goodsTag').innerHTML = result.content;
  }
  function setAttrList(result, text_result)
  {
    document.getElementById('tbody-goodsAttr').innerHTML = result.content;
  }

    function array_search_value(arrayinfo,value){
        for(i in arrayinfo){
            if(arrayinfo[i] == value){
                    return false;
            }
        }
        return true;
    }


    /**
   * 设置了一个商品价格，改变市场价格、积分以及会员价格
   */
  function priceSetted()
  {
    computePrice('market_price', marketPriceRate);
  }

  /**
   * 根据市场价格，计算并改变商店价格、积分以及会员价格
   */
  function marketPriceSetted()
  {
    computePrice('shop_price', 1/marketPriceRate, 'market_price');
  }
  
  
  /**
   * 按比例计算价格
   * @param   string  inputName   输入框名称
   * @param   float   rate        比例
   * @param   string  priceName   价格输入框名称（如果没有，取shop_price）
   */
  function computePrice(inputName, rate, priceName)
  {
      var shopPrice = priceName == undefined ? document.forms['theForm'].elements['shop_price'].value : document.forms['theForm'].elements[priceName].value;
      shopPrice = Utils.trim(shopPrice) != '' ? parseFloat(shopPrice)* rate : 0;
      if(inputName == 'integral')
      {
          shopPrice = parseInt(shopPrice);
      }
      shopPrice += "";

      n = shopPrice.lastIndexOf(".");
      if (n > -1)
      {
          shopPrice = shopPrice.substr(0, n + 3);
      }

      if (document.forms['theForm'].elements[inputName] != undefined)
      {
          document.forms['theForm'].elements[inputName].value = shopPrice;
      }
      else
      {
          document.getElementById(inputName).value = shopPrice;
      }
  }
  

  function handlePromote(checked)
  {
      document.forms['theForm'].elements['promote_price'].disabled = !checked;
      // 代码修改 By  www.taoyumall.com 促销商品时间精确到时分 Start
//      document.forms['theForm'].elements['selbtn1'].disabled = !checked;
//      document.forms['theForm'].elements['selbtn2'].disabled = !checked;
      document.forms['theForm'].elements['promote_start_date'].disabled = !checked;
      document.forms['theForm'].elements['promote_end_date'].disabled = !checked;
      
  }

/**
   * 新增一个规格
   */
  function addSpec(obj)
  {
      var src   = obj.parentNode.parentNode;
      var idx   = rowindex(src);
      var tbl   = document.getElementById('attrTable');
      var row   = tbl.insertRow(idx + 1);
      var cell1 = row.insertCell(-1);
      var cell2 = row.insertCell(-1);
      var regx  = /<a([^>]+)<\/a>/i;

      cell1.className = 'label';
      cell1.innerHTML = src.childNodes[0].innerHTML.replace(/(.*)(addSpec)(.*)(\[)(\+)/i, "$1removeSpec$3$4-");
      cell2.innerHTML = src.childNodes[1].innerHTML.replace(/readOnly([^\s|>]*)/i, '');
  }

  /**
   * 删除规格值
   */
  function removeSpec(obj)
  {
      var row = rowindex(obj.parentNode.parentNode);
      var tbl = document.getElementById('attrTable');

      tbl.deleteRow(row);
  }
  /**
   * 处理规格
   */
  function handleSpec()
  {
      var elementCount = document.forms['theForm'].elements.length;
      for (var i = 0; i < elementCount; i++)
      {
          var element = document.forms['theForm'].elements[i];
          if (element.id.substr(0, 5) == 'spec_')
          {
              var optCount = element.options.length;
              var value = new Array(optCount);
              for (var j = 0; j < optCount; j++)
              {
                  value[j] = element.options[j].value;
              }

              var hiddenSpec = document.getElementById('hidden_' + element.id);
              hiddenSpec.value = value.join(String.fromCharCode(13)); // 鐢ㄥ洖杞﹂敭闅斿紑姣忎釜瑙勬牸
          }
      }
      return true;
  }
  /**
   * 新增一个图片
   */
  function addImg(obj)
  {
      var src  = obj.parentNode.parentNode;
      var idx  = rowindex(src);
      var tbl  = document.getElementById('gallery-table');
      var row  = tbl.insertRow(idx + 1);
      var cell = row.insertCell(-1);
      cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addImg)(.*)(\[)(\+)/i, "$1removeImg$3$4-");
  }

  /**
   * 删除图片上传
   */
  function removeImg(obj)
  {
      var row = rowindex(obj.parentNode.parentNode);
      var tbl = document.getElementById('gallery-table');

      tbl.deleteRow(row);
  }



  function dropImgResponse(result)
  {
      if (result.error == 0)
      {
          document.getElementById('gallery_' + result.content).style.display = 'none';
      }
  }

  /**
   * 将市场价格取整
   */
  function integral_market_price()
  {
    document.forms['theForm'].elements['market_price'].value = parseInt(document.forms['theForm'].elements['market_price'].value);
  }




  /**
  * 检查货号是否存在
  */
  function checkGoodsSn(goods_sn, goods_id)
  {
    if (goods_sn == '')
    {
        document.getElementById('goods_sn_notice').innerHTML = "";
        return;
    }

    var callback = function(res)
    {
      if (res.error > 0)
      {
        document.getElementById('goods_sn_notice').innerHTML = res.message;
        document.getElementById('goods_sn_notice').style.color = "red";
      }
      else
      {
        document.getElementById('goods_sn_notice').innerHTML = "";
      }
    }
    Ajax.call('index.php?act=shop&op=check_goods_sn', "goods_sn=" + goods_sn + "&goods_id=" + goods_id, callback, "GET", "JSON");
  }

  
  
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
