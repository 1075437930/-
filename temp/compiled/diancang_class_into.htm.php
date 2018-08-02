
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="js/calendar.php?lang=<?php echo self::$_var['cfg_lang']; ?>"></script>
<link href="js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="main-div">
  <form action="index.php" method="post" name="theForm" enctype="multipart/form-data" onSubmit="return validate()">
    <table width="100%" id="general-table">
      <tr>
        <td  class="label"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="分类名称">分类名称<span class="require-field">*</span></td>
        <td><input type="text" name="class_name" value="<?php echo self::$_var['dcclassinto']['class_name']; ?>" size="35"  />
        </td>
      </tr>
      <tr>
        <td class="label">分类图片(200*200)</td>
        <td>
			<input type='file' name='fenlei_img' size='35' />
			<?php if (self::$_var['dcclassinto']['cat_img']): ?>
			<a href="<?php echo self::$_var['dcclassinto']['imgurl']; ?>" target="_blank">
				<img src="templates/default/images/yes.gif" border="0" />
			</a>
			<?php else: ?>
			<img src="templates/default/images/no.gif" />
			<?php endif; ?>
			<input type="hidden" name="fenlei_img2" value="<?php echo self::$_var['dcclassinto']['cat_img']; ?>" size="35" /><span class="require-field">*</span>
		</td>
      </tr>
	  <tr>
		<td class="label">分类描述</td>
		<td><textarea rows="10" cols="30" name="contentsd" id="contentsd" maxlength="200" placeholder="请添加分类描述"><?php echo self::$_var['dcclassinto']['content']; ?></textarea><span class="require-field">*</span>
		</td>
	  </tr>
	  <tr>
        <td class="label">是否显示</td>
        <td><label>
          <input type="radio" name="dc_show" value="1" <?php if (self::$_var['dcclassinto']['dc_show'] == 1): ?>checked="true"<?php endif; ?>/>
          显示</label>
          <label>
          <input type="radio" name="dc_show" value="0" <?php if (self::$_var['dcclassinto']['dc_show'] == 0): ?>checked="true"<?php endif; ?>/>
          不显示</label>
        </td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td><input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" />
          <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
          <input type="hidden" name="act" value="dcclass" />
          <input type="hidden" name="op" value="<?php echo self::$_var['insert_or_update']; ?>" />
          <input type="hidden" name="dcclass_id" value="<?php echo self::$_var['dcclassinto']['dcclass_id']; ?>" />
        </td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script language="JavaScript">    
   
 /**     * 检查表单输入的数据     */    
 function validate()    
 {        
 validator = new Validator("theForm");        
 validator.required("class_name", '分类名称不能为空');        
 return validator.passed();    
 }    
 //-->    
 
 </script>
<?php echo self::fetch('pagefooter.htm'); ?>