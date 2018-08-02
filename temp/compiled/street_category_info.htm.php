



<?php echo self::fetch('pageheader.htm'); ?> 



<div class="main-div">

  <form action="index.php" method="post" name="theForm"  onsubmit="return validate()">

    <table width="100%" id="general-table">

      <tr>

        <td class="label">分类名称:</td>

        <td><textarea name='str_name' rows="3" cols="48"><?php echo htmlspecialchars(self::$_var['cat_info']['cat_name']); ?></textarea>

          <font color="red">*</font> <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeCat_name">添加多个分类请用半角中的逗号分隔</span></td>

      </tr>

	  <tr>

        <td class="label">前台显示样式类名:</td>

        <td><input type="text" name='str_style' value='<?php echo htmlspecialchars(self::$_var['cat_info']['str_style']); ?>'></td>

      </tr>

	  <tr>

        <td class="label">是否显示:</td>

        <td><input type="radio" name="is_show" value="1" <?php if (self::$_var['cat_info']['is_show'] != 0): ?> checked="true"<?php endif; ?>/>

          <?php echo self::$_var['lang']['yes']; ?>

          <input type="radio" name="is_show" value="0" <?php if (self::$_var['cat_info']['is_show'] == 0): ?> checked="true"<?php endif; ?> />

          <?php echo self::$_var['lang']['no']; ?> </td>

      </tr>

	  <tr>

        <td class="label">是否推荐:</td>

        <td><input type="radio" name="is_groom" value="1" <?php if (self::$_var['cat_info']['is_show'] != 0): ?> checked="true"<?php endif; ?>/>

          <?php echo self::$_var['lang']['yes']; ?>

          <input type="radio" name="is_groom" value="0" <?php if (self::$_var['cat_info']['is_show'] == 0): ?> checked="true"<?php endif; ?> />

          <?php echo self::$_var['lang']['no']; ?> </td>

      </tr>

      <tr>

        <td class="label">排序:</td>

        <td><input type="text" name='sort_order' <?php if (self::$_var['cat_info']['sort_order']): ?>value='<?php echo self::$_var['cat_info']['sort_order']; ?>'<?php else: ?> value="50"<?php endif; ?> size="15" /></td>

      </tr>



    </table>

    <div class="button-div">

      <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />

      <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />

    </div>

    <input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
    
    <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />

    <input type="hidden" name="old_cat_name" value="<?php echo self::$_var['cat_info']['cat_name']; ?>" />

    <input type="hidden" name="cat_id" value="<?php echo self::$_var['cat_info']['cat_id']; ?>" />

  </form>

</div>

<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>

 

<script language="JavaScript">

<!--

document.forms['theForm'].elements['str_name'].focus();

/**

 * 检查表单输入的数据

 */

function validate()

{

  validator = new Validator("theForm");

  validator.required("str_name",    "分类名称为空！");

  return validator.passed();

}

onload = function()

{

  // 开始检查订单

  startCheckOrder();

}



/**

 * 新增一个筛选属性

 */

function addFilterAttr(obj)

{

  var src = obj.parentNode.parentNode;

  var tbl = document.getElementById('tbody-attr');



  var validator  = new Validator('theForm');

  var filterAttr = document.getElementsByName("filter_attr[]");



  if (filterAttr[filterAttr.length-1].selectedIndex == 0)

  {

    validator.addErrorMsg(filter_attr_not_selected);

  }

  

  for (i = 0; i < filterAttr.length; i++)

  {

    for (j = i + 1; j <filterAttr.length; j++)

    {

      if (filterAttr.item(i).value == filterAttr.item(j).value)

      {

        validator.addErrorMsg(filter_attr_not_repeated);

      } 

    } 

  }



  if (!validator.passed())

  {

    return false;

  }



  var row  = tbl.insertRow(tbl.rows.length);

  var cell = row.insertCell(-1);

  cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addFilterAttr)(.*)(\[)(\+)/i, "$1removeFilterAttr$3$4-");

  filterAttr[filterAttr.length-1].selectedIndex = 0;

}



/**

 * 删除一个筛选属性

 */

function removeFilterAttr(obj)

{

  var row = rowindex(obj.parentNode.parentNode);

  var tbl = document.getElementById('tbody-attr');



  tbl.deleteRow(row);

}

//-->

</script> 



<?php echo self::fetch('pagefooter.htm'); ?>