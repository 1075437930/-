

<?php echo self::fetch('pageheader.htm'); ?>



<div class="main-div">

<form action="index.php" method="post" name="theForm" onsubmit="return validate()">

<table width="100%">

  <tr>

    <td class="label"><?php echo self::$_var['lang']['rank_name']; ?>: </td>

    <td><input type="text" name="rank_name" value="<?php echo self::$_var['rank']['rank_name']; ?>"  maxlength="20" /><?php echo self::$_var['lang']['require_field']; ?></td>

  </tr> 

  <tr>

    <td class="label"><?php echo self::$_var['lang']['sort_order']; ?>: </td>

    <td><input type="text" name="sort_order" value="<?php echo self::$_var['rank']['sort_order']; ?>" maxlength="20" /><?php echo self::$_var['lang']['require_field']; ?></td>

  </tr> 

  <tr>

    <td colspan="2" align="center">

      <input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
      
      <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />

      <input type="hidden" name="id" value="<?php echo self::$_var['rank']['rank_id']; ?>" />

      <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" />

      <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />

    </td>

  </tr>

</table>

</form>

</div>

<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>



<script language="JavaScript">

<!--

document.forms['theForm'].elements['rank_name'].focus();
/**

 * 检查表单输入的数据

 */

function validate()
{
    validator = new Validator("theForm");

    validator.required('rank_name', rank_name_empty);

    validator.isInt('sort_order', sort_order_invalid, true);

    return validator.passed();

}

//-->

</script>



<?php echo self::fetch('pagefooter.htm'); ?>