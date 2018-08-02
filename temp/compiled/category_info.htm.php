

<?php echo self::fetch('pageheader.htm'); ?> 

<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>
<div class="main-div">
    <form action="index.php" method="post" name="theForm" enctype="multipart/form-data" onsubmit="return tijao()">
        <table width="100%" id="general-table">
            <tr>
              <td class="label"><?php echo self::$_var['lang']['cat_name']; ?>:</td>
              <td><input type='text' name='cat_name' id="cat_name" value='<?php echo self::$_var['cat_info']['cat_name']; ?>' />
                <font color="red">*</font> <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeCat_name"><?php echo self::$_var['lang']['notice_cat_name']; ?></span></td>
            </tr>
            <tr>
              <td class="label">目录名称:</td>
              <td>
                <input type='text' name='path_name' maxlength="20" value='<?php echo htmlspecialchars(self::$_var['cat_info']['path_name']); ?>' size='27' />
              </td>
            </tr>
      
            <tr>
                <td class="label"><?php echo self::$_var['lang']['parent_id']; ?>:</td>
                <td><select name="parent_id" id="parent_id" onchange="change_image();">
                <option value="0"><?php echo self::$_var['lang']['cat_top']; ?></option>
                  <?php echo self::$_var['cat_select']; ?>
                </select></td>
            </tr>
            <tr id="categroy_img">
                <td class="label">图片上传:</td>
                <td>
                    <input type="file" name="cat_img" id="cat_img"/>
                    图片地址：<input type="text" value="<?php echo self::$_var['cat_info']['cat_img']; ?>" name="cat_img_url" id="cat_img_url"/>
                </td>
                
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['sort_order']; ?>:</td>
                <td><input type="text" name='sort_order' <?php if (self::$_var['cat_info']['sort_order']): ?>value='<?php echo self::$_var['cat_info']['sort_order']; ?>'<?php else: ?> value="50"<?php endif; ?> size="15" /></td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['is_show']; ?>:</td>
                <td>
                    <input type="radio" name="is_show" value="1" <?php if (self::$_var['cat_info']['is_show'] != 0): ?> checked="true"<?php endif; ?>/><?php echo self::$_var['lang']['yes']; ?>
                    <input type="radio" name="is_show" value="0" <?php if (self::$_var['cat_info']['is_show'] == 0): ?> checked="true"<?php endif; ?> /><?php echo self::$_var['lang']['no']; ?> 
                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['show_in_nav']; ?>:</td>
                <td>
                    <input type="radio" name="show_in_nav" value="1" <?php if (self::$_var['cat_info']['show_in_nav'] != 0): ?> checked="true"<?php endif; ?>/><?php echo self::$_var['lang']['yes']; ?>
                    <input type="radio" name="show_in_nav" value="0" <?php if (self::$_var['cat_info']['show_in_nav'] == 0): ?> checked="true"<?php endif; ?> /><?php echo self::$_var['lang']['no']; ?> 
                </td>
            </tr>
<!--      <tr>
        <td class="label"><?php echo self::$_var['lang']['show_in_index']; ?>:</td>
        <td>
            <input type="checkbox" name="cat_recommend[]" value="1" <?php if (self::$_var['cat_recommend'] [ 1 ] == 1): ?> checked="true"<?php endif; ?>/>
            <?php echo self::$_var['lang']['index_best']; ?>
            <input type="checkbox" name="cat_recommend[]" value="2" <?php if (self::$_var['cat_recommend'] [ 2 ] == 1): ?> checked="true"<?php endif; ?> />
            <?php echo self::$_var['lang']['index_new']; ?>
            <input type="checkbox" name="cat_recommend[]" value="3" <?php if (self::$_var['cat_recommend'] [ 3 ] == 1): ?> checked="true"<?php endif; ?> />
            <?php echo self::$_var['lang']['index_hot']; ?> 
        </td>
      </tr>-->
      <tr>
        <td class="label"><?php echo self::$_var['lang']['keywords']; ?>:</td>
        <td><input type="text" name="keywords" value='<?php echo self::$_var['cat_info']['keywords']; ?>' size="50"></td>
      </tr>
      <tr>
        <td class="label"><?php echo self::$_var['lang']['cat_desc']; ?>:</td>
        <td><textarea name='cat_desc' rows="6" cols="48"><?php echo self::$_var['cat_info']['cat_desc']; ?></textarea></td>
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

 
<script language="JavaScript">

/**
 * 检查表单输入的数据
 */
function tijao(){
  var cat_name = document.getElementById('cat_name').value;
  if(cat_name){
      return true;
  }else{
      alert('分类名称不能为空');
      return false;
  }
}

function change_image(){
    var options=$("#parent_id option:selected");
    var value = options.val();
    if(value==0)
    {
        document.getElementById('categroy_img').style.display='none';
    }
    else
    {
        document.getElementById('categroy_img').style.display='table-row';
    }
}

</script> 

<?php echo self::fetch('pagefooter.htm'); ?>