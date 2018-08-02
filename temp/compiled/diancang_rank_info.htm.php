
<?php echo self::fetch('pageheader.htm'); ?>

<div class="main-div">
<form action="index.php" method="post" name="theForm" onsubmit="return validate()">
<table width="100%">
  <tr>
    <td class="label">会员等级名称: </td>
    <td><input type="text" name="rank_name" value="<?php echo self::$_var['rank']['rank_name']; ?>" maxlength="20" /><?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">积分下限: </td>
    <td><input type="text" name="min_points" value="<?php echo self::$_var['rank']['min_points']; ?>" size="10" maxlength="20" /></td>
  </tr>
  <tr>
    <td class="label">积分上限: </td>
    <td><input type="text" name="max_points" value="<?php echo self::$_var['rank']['max_points']; ?>" size="10" maxlength="20" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="act" value="dcrank" />
      <input type="hidden" name="op" value="insert" />
      <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" />
      <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
    </td>
  </tr>
</table>
</form>
</div>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script language="JavaScript">

document.forms['theForm'].elements['rank_name'].focus();


/**
 * 检查表单输入的数据
 */
function validate()
{
   
	if (Utils.trim(document.forms['theForm'].elements['min_points'].value) == '' ||
		!Utils.isInt(document.forms['theForm'].elements['min_points'].value))
	{
		alert(integral_min_invalid);
		return false;
	}

	if (Utils.trim(document.forms['theForm'].elements['max_points'].value) == '' ||
		!Utils.isInt(document.forms['theForm'].elements['max_points'].value))
	{
		alert(integral_max_invalid);
		return false;
	}

	if (!document.forms['theForm'].elements['special_rank'].checked &&
		(parseInt(document.forms['theForm'].elements['max_points'].value) <=
		parseInt(document.forms['theForm'].elements['min_points'].value)))
	{
		alert(integral_max_small);
		return false;
	}
    

    validator = new Validator("theForm");
    validator.required('rank_name', '等级名称不能为空');
    return validator.passed();
}



</script>
<?php echo self::fetch('pagefooter.htm'); ?>