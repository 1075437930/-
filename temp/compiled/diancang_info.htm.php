
<?php echo self::fetch('pageheader.htm'); ?>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<div class="main-div">
<form method="post" action="index.php" name="theForm" enctype="multipart/form-data" onSubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label">典藏名称</td>
    <td>
	 <input name="dc_names" type="text" id="dc_names" value="<?php echo self::$_var['dcgoods']['dc_names']; ?>" maxlength="60" />
     <input type="hidden" name="goods_names" value="<?php echo self::$_var['dcgoods']['goods_name']; ?>" />
	 如果留空，取商品的名称
	 </td>
  </tr>
  
  <tr>
    <td class="label">典藏描述</td>
    <td><textarea  name="dc_brief" cols="60" rows="4" id="dc_brief"><?php echo self::$_var['dcgoods']['dc_brief']; ?></textarea></td>
  </tr>
	<tr>
	  <td class="label">典藏分类</td>
	  <td><select name="dc_class" id="dc_class">
		<option value="0" selected>请选择分类</option>
		<?php echo self::html_options(array('options'=>self::$_var['stylelitst'],'selected'=>self::$_var['class_id'])); ?>
	  </select></td>
	</tr>
  <tr>
  <tr>
    <td align="right">根据商品编号或名称搜索商品</td>
    <td><input name="keyword" type="text" id="keyword">
      <input name="search" type="button" id="search" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" onclick="searchGoods()" /></td>
  </tr>
  <tr>
    <td class="label">选择需要商品</td>
    <td>
	<select name="goods_lists" id="goods_lists" onclick="xuanzhongGoods()">
      <option value="<?php echo self::$_var['dcgoods']['goods_id']; ?>" selected="selected"><?php echo self::$_var['dcgoods']['goods_name']; ?></option>
    </select>    
  </tr>
  <tr>
    <td class="label">典藏产品价格</td>
    <td><input name="dc_price" type="text" id="dc_price" value="<?php echo self::$_var['dcgoods']['dc_price']; ?>" maxlength="60" <?php if (self::$_var['dcgoods']['typesd'] == 0): ?> disabled <?php endif; ?> />
     自动获取产品商城价格(手动可以改分销价格)</td>
  </tr>
   <tr>
    <td class="label">选着收益类型</td>
    <td>
     <label><input type="radio" name="xu_yuebili" value="2" checked disabled/>余额</label>
    </td>
  </tr>
   <tr>
    <td class="label">选着是否可以使用优惠卷</td>
    <td>
	 <label><input type="radio" name="juan_type" value="0" <?php if (self::$_var['dcgoods']['juan_type'] == 0): ?> checked <?php endif; ?>/>不可以</label>
     <label><input type="radio" name="juan_type" value="1" <?php if (self::$_var['dcgoods']['juan_type'] == 1): ?> checked <?php endif; ?> />可以</label>
    </td>
  </tr>
  <tr>
	<td id="tbody-goodsTag" colspan="2" style="padding:0"><?php echo self::$_var['dc_yuebili_html']; ?></td>
  </tr>
 
  <tr>
    <td colspan="2" align="center">
      <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />
	  <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />
	  <input type="hidden" name="act" value="diancang" />
    <input type="hidden" name="op" value="<?php echo self::$_var['insert_or_update']; ?>" />
    <input type="hidden" name="capitalid" value="<?php echo self::$_var['dcgoods']['capitalid']; ?>" />
	</td>
  </tr>
</table>
</form>
</div>


<script language="JavaScript">
<!--
var goodsstyle = 0;
/**
 * 检查表单输入的数据
 */
function validate()
{
    validator = new Validator("theForm");
    validator.required('dc_brief', '典藏描述不能为空');
    validator.required('dc_price', '典藏价格不能为空');
	validator.required('dc_class', '分类必须选择');
	
    return validator.passed();
}

function searchGoods()
{
  var filter = new Object;
  filter.keyword  = document.forms['theForm'].elements['keyword'].value;
 
  Ajax.call('index.php?is_ajax=1&act=diancang&op=search_goods&keyword='+filter.keyword, filter, searchGoodResponse, 'GET', 'JSON');
}



	/**
   * 切换商品标签类型
   */
  function xuanzhongGoods()
  {
	  var filter = new Object;
      var selGoodsType = document.forms['theForm'].elements['goods_lists'];
      if (selGoodsType != undefined)
      {
		  var goods_id = selGoodsType.options[selGoodsType.selectedIndex].value;
          filter.goodsid = goods_id;
		  if(goods_id == 0){
			return;
		  }else{
			 Ajax.call('index.php?is_ajax=1&act=diancang&op=xuan_goods&goods_id='+goods_id,filter, xuanResponse, "GET", "JSON");
		  }
         
      }
  }

function xuanResponse(result)
{

  if (result.error == '1' && result.message != '')
  {
    alert(result.message);
    return;
  }
  var dc_names = document.forms['theForm'].elements['dc_names'].value;
  if(!dc_names){
	 document.forms['theForm'].elements['goods_names'].value = result.content.goods_name;
  }
  document.forms['theForm'].elements['dc_price'].removeAttribute('disabled');
  document.forms['theForm'].elements['dc_price'].value = result.content.goodpic;
  
  return;
}

function searchGoodResponse(result)
{

  if (result.error == '1' && result.message != '')
  {
    alert(result.message);
    return;
  }

  var frm = document.forms['theForm'];
  var sel = frm.elements['goods_lists'];
  if (result.error == 0)
  {
    /* 清除 options */
    sel.length = 0;
    /* 创建 options */
    var goodsinto = result.content.goodsinto;
    if (goodsinto)
    {
      for (i = 0; i < goodsinto.length; i++)
      {
          var opt = document.createElement("OPTION");
          opt.value = goodsinto[i].goods_id;
          opt.text  = goodsinto[i].goods_name+',编号:'+goodsinto[i].goods_sn;
          sel.options.add(opt);
      }
    }
    else
    {
      var opt = document.createElement("OPTION");
      opt.value = 0;
      opt.text  = '暂未找到相关产品';
      sel.options.add(opt);
    }
  }
  return;
}



 

//-->
</script>

<?php echo self::fetch('pagefooter.htm'); ?>
