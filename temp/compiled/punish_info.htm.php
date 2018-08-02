
<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
<form method="post" action="index.php" name="theForm" enctype="multipart/form-data" onsubmit="return check()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label">处罚原因</td>
    <td><input type="text" name="punish_reason" id = "punish_reason" maxlength="60" value="<?php echo self::$_var['punish']['reason']; ?>" /></td>
  </tr>
  <tr>
    <td class="label">处罚积分</td>
    <td><input type="number" name="punish_count" id = "punish_count" maxlength="40" size="15" placeholder="请输入正整数" value="<?php echo self::$_var['punish']['count']; ?>" /></td>
  </tr>
  <tr>
    <td class="label">违规说明</td>
    <td><textarea  name="punish_instructions" cols="60" rows="4"  ><?php echo self::$_var['punish']['instructions']; ?></textarea></td>
  </tr>
  <tr>
    <td class="label">备注</td>
    <td><textarea  name="punish_beizhu" cols="60" rows="4"  ><?php echo self::$_var['punish']['beizhu']; ?></textarea></td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><br />
      <input type="submit" class="button" value="确定"/>
      <input type="reset" class="button" value="重置" />
      <input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
      <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />
      <input type="hidden" name="id" value="<?php echo self::$_var['punish']['id']; ?>" />
    </td>
  </tr>
</table>
</form>
</div>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>

<script language="JavaScript">
<!--
document.forms['theForm'].elements['brand_name'].focus();
onload = function()
{
    // 开始检查订单
    startCheckOrder();
}
/**
 * 检查表单输入的数据
 */
function check()
{
    if(document.getElementById("punish_reason").value.length==0){
        alert('处罚原因不能为空');
        return false;
    }
    if(document.getElementById("punish_count").value.length==0){
        alert('处罚积分数值不能为空');
        return false;
    }

}
//-->
</script>

<?php echo self::fetch('pagefooter.htm'); ?>