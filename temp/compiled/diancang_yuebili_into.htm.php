
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<div class="main-div">
<form method="post" action="index.php" name="theForm" onSubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label">月份(月)</td>
    <td>
	 <input name="yue" type="text" id="yue" value="<?php echo self::$_var['yubili_into']['yuefen']; ?>" placeholder="6"  maxlength="60" /><span class="require-field">*如3个月到期就添加3</span>
    </td>
  </tr>
  <tr>
    <td class="label">比例(统一年利率计算)单位%</td>
    <td>
	 <input name="bili" type="text" id="bili" value="<?php echo self::$_var['yubili_into']['bili']; ?>" placeholder="5.3"  maxlength="60" /><span class="require-field">*如5.30%比例就添加5.3</span>
    </td>
  </tr>
  <tr>
    <td class="label">收益类型</td>
    <td>
	 <label><input type="radio" name="stypes" value="2" <?php if (self::$_var['yubili_into']['stypes'] == 2): ?>checked="true"<?php endif; ?>/>余额</label>
     <label><input type="radio" name="stypes" value="1" <?php if (self::$_var['yubili_into']['stypes'] == 1): ?>checked="true"<?php endif; ?>/>淘金币</label>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="set_id" value="<?php echo self::$_var['yubili_into']['set_id']; ?>" /></td>
      <input type="hidden" name="act" value="dcyueset" />
      <input type="hidden" name="op" value="<?php echo self::$_var['insert_or_update']; ?>" />
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
    validator.required('yue', '月份不能为空');
	validator.required('bili', '比例不能为空');
	
    return validator.passed();
}

</script>

<?php echo self::fetch('pagefooter.htm'); ?>
