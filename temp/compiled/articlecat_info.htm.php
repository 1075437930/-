
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>
<div class="main-div">
<form method="post" action="index.php" name="theForm" enctype="multipart/form-data" onsubmit="return tijao()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label"><?php echo self::$_var['lang']['cat_name']; ?>：</td>
    <td><input type="text" name="cat_name" id="cat_name" maxlength="60" size = "30" value="<?php echo htmlspecialchars(self::$_var['cat']['cat_name']); ?>" /><?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  
	  <tr>
        <td class="label">目录名称：</td>
        <td>
          <input type='text' name='path_name' maxlength="20" value='<?php echo self::$_var['cat']['path_name']; ?>' size='27' />
		  <span class="notice-span" style="display:block"  id="noticePathname">生成的【真静态HTML文件】将保存到该目录下<br>例如：在这里输入 changshi，根目录下就会生成一个 articlecat-changshi 的二级目录用来保存纯静态HTML文件，<br>articlecat- 属于默认前缀部分</span>
        </td>
      </tr>
  
  <tr>
    <td class="label"><?php echo self::$_var['lang']['parent_cat']; ?>：</td>
    <td>
      <select name="parent_id" onchange="catChanged()" <?php if (self::$_var['disabled']): ?>disabled="disabled"<?php endif; ?> >
        <option value="0"><?php echo self::$_var['lang']['cat_top']; ?></option>
        <?php echo self::$_var['cat_select']; ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="label"><?php echo self::$_var['lang']['sort_order']; ?>：</td>
    <td>
      <input type="text" name='sort_order' <?php if (self::$_var['cat']['sort_order']): ?>value='<?php echo self::$_var['cat']['sort_order']; ?>'<?php else: ?> value="50"<?php endif; ?> size="15" />
    </td>
  </tr>
    <tr>
    <td class="label"><?php echo self::$_var['lang']['show_in_nav']; ?>：</td>
    <td>
      <input type="radio" name="show_in_nav" value="1" <?php if (self::$_var['cat']['show_in_nav'] != 0): ?> checked="true"<?php endif; ?>/> <?php echo self::$_var['lang']['yes']; ?>
      <input type="radio" name="show_in_nav" value="0" <?php if (self::$_var['cat']['show_in_nav'] == 0): ?> checked="true"<?php endif; ?> /> <?php echo self::$_var['lang']['no']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="narrow-label">分类图片上传</td>
    <td>
      <input type="file" name="img_file">
      <span class="narrow-label"><?php echo self::$_var['lang']['file_url']; ?>
      <input name="img_url" type="text" value="<?php echo self::$_var['cat']['file_img']; ?>" size="30" maxlength="255" />
      </span>
    </td>
  </tr>
  <tr>
    <td class="label"><a href="javascript:showNotice('notice_keywords');" title="<?php echo self::$_var['lang']['form_notice']; ?>">
        <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>"></a><?php echo self::$_var['lang']['cat_keywords']; ?>：</td>
    <td><input type="text" name="keywords" maxlength="60" size="50" value="<?php echo htmlspecialchars(self::$_var['cat']['keywords']); ?>" />
    <br /><span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="notice_keywords"><?php echo self::$_var['lang']['notice_keywords']; ?></span>
    </td>
  </tr>
  <tr>
    <td class="label"><?php echo self::$_var['lang']['cat_desc']; ?>：</td>
    <td><textarea  name="cat_desc" cols="60" rows="4"><?php echo htmlspecialchars(self::$_var['cat']['cat_desc']); ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><br />
      <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
      <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />
      <input type="hidden" name="cat_id" value="<?php echo self::$_var['cat']['cat_id']; ?>" />
      <input type="hidden" name="old_catname" value="<?php echo self::$_var['cat']['cat_name']; ?>" />
    </td>
  </tr>
</table>
</form>
</div>


<script language="JavaScript">
listTable.act = "articlecat";
/**
 * 检查表单输入的数据
 */
function tijao(){
  var cat_name = document.getElementById('cat_name').value;
  if(cat_name){
      return true;
  }else{
      alert('分类名称不能为空');
      return false
  }
}

/**
 * 选取上级分类时判断选定的分类是不是底层分类
 */
function catChanged()
{
  var obj = document.forms['theForm'].elements['parent_id'];

  cat_type = obj.options[obj.selectedIndex].getAttribute('cat_type');
  if (cat_type == undefined)
  {
    cat_type = 1;
  }

  if ((obj.selectedIndex > 0) && (cat_type == 2 || cat_type == 3 || cat_type == 5))
  {
    alert(sys_hold);
    obj.selectedIndex = 0;
    return false;
  }

  return true;
}


</script>

<?php echo self::fetch('pagefooter.htm'); ?>