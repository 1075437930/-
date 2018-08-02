
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<form action="index.php" method="post">
<div class="main-div">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label"><?php echo self::$_var['lang']['payment_name']; ?></td>
    <td><input name="pay_name" type="text" value="<?php echo htmlspecialchars(self::$_var['pay']['pay_name']); ?>" size="40" /></td>
  </tr>
  <tr>
    <td class="label"><?php echo self::$_var['lang']['payment_desc']; ?></td>
    <td><textarea name="pay_desc" cols="60" rows="8"><?php echo htmlspecialchars(self::$_var['pay']['pay_desc']); ?></textarea></td>
  </tr>
  <tr>
    <td class="label"><?php echo self::$_var['lang']['short_pay_fee']; ?></td>
    <td><input name="fee" type="text" value="<?php echo self::$_var['pay']['pay_fee']; ?>" size="40" /></td>
  </tr>
  <tr>
    <td class="label"><?php echo self::$_var['lang']['sort_order']; ?></td>
    <td><input name="pay_order" type="text" value="<?php echo self::$_var['pay']['pay_order']; ?>" size="40" /></td>
  </tr>
  <?php $_from = self::$_var['pay']['pay_config']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'config');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['config']):
?>
  <tr>
    <td class="label">
      <?php if (self::$_var['config']['desc']): ?>
      <a href="javascript:showNotice('notice<?php echo self::$_var['config']['name']; ?>');" title="<?php echo self::$_var['lang']['form_notice']; ?>"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>"></a>
      <?php endif; ?>
      <span class="label"><?php echo self::$_var['config']['label']; ?>
    </td>
    <td>
      <?php if (self::$_var['config']['type'] == "text"): ?>
      <input name="cfg_value[]" type="<?php echo self::$_var['config']['type']; ?>" value="<?php echo self::$_var['config']['value']; ?>" size="40" />
      <?php elseif (self::$_var['config']['type'] == "textarea"): ?>
      <textarea name="cfg_value[]" cols="80" rows="5"><?php echo self::$_var['config']['value']; ?></textarea>
      <?php elseif (self::$_var['config']['type'] == "select"): ?>
      <select name="cfg_value[]"><?php echo self::html_options(array('options'=>self::$_var['config']['range'],'selected'=>self::$_var['config']['value'])); ?></select>
      <?php endif; ?>
      <input name="cfg_name[]" type="hidden" value="<?php echo self::$_var['config']['name']; ?>" />
      <input name="cfg_type[]" type="hidden" value="<?php echo self::$_var['config']['type']; ?>" />
      <input name="cfg_lang[]" type="hidden" value="<?php echo self::$_var['config']['lang']; ?>" />
      <?php if (self::$_var['config']['desc']): ?>
      <br /><span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="notice<?php echo self::$_var['config']['name']; ?>"><?php echo self::$_var['config']['desc']; ?></span>
      <?php endif; ?>
      
      <?php if (self::$_var['key'] == "0"): ?>
      <?php if ($_GET['code'] == "tenpay"): ?><input align=""type="button" value="<?php echo self::$_var['lang']['ctenpay']; ?>" onclick="javascript:window.open('<?php echo self::$_var['lang']['ctenpay_url']; ?>')"/>
      <?php elseif ($_GET['code'] == "tenpayc2c"): ?> <input align=""type="button" value="<?php echo self::$_var['lang']['ctenpay']; ?>" onclick="javascript:window.open('<?php echo self::$_var['lang']['ctenpayc2c_url']; ?>')"/>
      <?php endif; ?>
      
      <?php endif; ?>
      
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?> 
  <tr>
    <td class="label"><?php echo self::$_var['lang']['payment_is_cod']; ?></td>
    <td><?php if (self::$_var['pay']['is_cod'] == "1"): ?><?php echo self::$_var['lang']['yes']; ?><?php else: ?><?php echo self::$_var['lang']['no']; ?><?php endif; ?></td>
  </tr>
   
  <tr>
    <td class="label"><?php echo self::$_var['lang']['payment_is_pickup']; ?></td>
    <td><?php if (self::$_var['pay']['is_pickup'] == "1"): ?><?php echo self::$_var['lang']['yes']; ?><?php else: ?><?php echo self::$_var['lang']['no']; ?><?php endif; ?></td>
  </tr>
  
  <tr>
    <td class="label"><?php echo self::$_var['lang']['payment_is_online']; ?></td>
    <td><?php if (self::$_var['pay']['is_online'] == "1"): ?><?php echo self::$_var['lang']['yes']; ?><?php else: ?><?php echo self::$_var['lang']['no']; ?><?php endif; ?></td>
  </tr>
  <tr align="center">
    <td colspan="2">
      <input type="hidden"  name="pay_id"       value="<?php echo self::$_var['pay']['pay_id']; ?>" />
      <input type="hidden"  name="pay_code"     value="<?php echo self::$_var['pay']['pay_code']; ?>" />
      <input type="hidden"  name="is_cod"       value="<?php echo self::$_var['pay']['is_cod']; ?>" />
      <input type="hidden"  name="act"       value="payment" />
      <input type="hidden"  name="op"       value="update" />
       
      <input type="hidden"  name="is_pickup"    value="<?php echo self::$_var['pay']['is_pickup']; ?>" />
      
      <input type="hidden"  name="is_online"    value="<?php echo self::$_var['pay']['is_online']; ?>" />
      <input type="submit" class="button" name="Submit"       value="<?php echo self::$_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button"  name="Reset"        value="<?php echo self::$_var['lang']['button_reset']; ?>" />
	  <input name="pay_fee" type="hidden" value="0" />
    </td>
  </tr>
</table>
</div>
</form>
<script type="Text/Javascript" language="JavaScript">
<!--
//-->
</script>
<?php echo self::fetch('pagefooter.htm'); ?>