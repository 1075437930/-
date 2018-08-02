
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<div class="main-div">
<form method="post" action="index.php" name="theForm" onSubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label">提问问题</td>
    <td>
        <textarea rows="5" cols="30" name="questions_name" id="questions_name" maxlength="200" placeholder="请添加想要问的问题"><?php echo self::$_var['askinto']['questions']; ?></textarea><span class="require-field">*</span>
    </td>
</tr>

    <tr>
        <td class="label">关键字</td>
        <td>
            <textarea rows="3" cols="30" name="questions_key" id="questions_key" maxlength="200" placeholder="请添加问题的关键字"><?php echo self::$_var['askinto']['keyword']; ?></textarea><span class="require-field">*关键字之间用 | 隔开 </span>
        </td>
    </tr>


	<tr>
	  <td class="label">问题分类</td>
	  <td><select name="ask_class" id="ask_class">
		<option value="0" selected="selected">请选择分类</option>
		<?php $_from = self::$_var['classlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'cls');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['cls']):
?>
      <?php $_from = self::$_var['cls']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'cl');if (count($_from)):
    foreach ($_from AS self::$_var['cl']):
?>
        <option value="<?php echo self::$_var['key']; ?>" ><?php echo self::$_var['cl']; ?></option>
      <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
	  </select></td>
	</tr>
  <tr>
    <td class="label">问题解答</td>
    <td><textarea rows="10" cols="30" name="answers_cent" id="answers_cent" maxlength="200" placeholder="请添问题解答答案"><?php echo self::$_var['askinto']['answers']; ?></textarea><span class="require-field">*</span>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="askid" value="<?php echo self::$_var['askinto']['ask_id']; ?>" /></td>
      <input type="hidden" name="act" value="askset" />
      <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />
  </tr>
</table>
</form>
</div>


<script language="JavaScript">

/**
 * 检查表单输入的数据
 */
function validate()
{
    validator = new Validator("theForm");
    validator.required('questions_name', '问题不能为空');
	validator.required('answers_cent', '答案不能为空');
	validator.required('questions_key', '关键字不能为空');
    return validator.passed();
}

//-->
</script>

<?php echo self::fetch('pagefooter.htm'); ?>
