
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<?php echo self::smarty_insert_scripts(array('files'=>'js/transport.org.js,js/region.js')); ?>
<script language="JavaScript">
<!--

// 这里把JS用到的所有语言都赋值到这里

<?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";

<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

//-->
</script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="main-div">
<form action="index.php?act=order&op=lists" method="post" enctype="multipart/form-data" name="searchForm">
  <table cellspacing="1" cellpadding="3" width="100%">
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_order_sn']; ?></td>
      <td><input name="order_sn" type="text" id="order_sn" size="30"></td>
      <td align="right"><?php echo self::$_var['lang']['label_email']; ?></td>
      <td><input name="email" type="text" id="email" size="30"></td>
    </tr>
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_user_name']; ?></td>
      <td><input name="user_name" type="text" id="user_name" size="30"></td>
      <td align="right"><?php echo self::$_var['lang']['label_consignee']; ?></td>
      <td><input name="consignee" type="text" id="consignee" size="30"></td>
    </tr>
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_address']; ?></td>
      <td><input name="address" type="text" id="address" size="30"></td>
      <td align="right"><?php echo self::$_var['lang']['label_zipcode']; ?></td>
      <td><input name="zipcode" type="text" id="zipcode" size="30"></td>
    </tr>
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_tel']; ?></td>
      <td><input name="tel" type="text" id="tel" size="30"></td>
      <td align="right"><?php echo self::$_var['lang']['label_mobile']; ?></td>
      <td><input name="mobile" type="text" id="mobile" size="30"></td>
    </tr>
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_area']; ?></td>
      <td colspan="3"><select name="country" id="selCountries" onchange="region.changed(this, 1, 'selProvinces')">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
          <?php $_from = self::$_var['country_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'country');if (count($_from)):
    foreach ($_from AS self::$_var['country']):
?>
          <?php if (self::$_var['country']['parent_id'] == 0): ?><option value="<?php echo self::$_var['country']['region_id']; ?>"><?php echo self::$_var['country']['region_name']; ?></option><?php endif; ?>
          <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
      </select>
        <select name="province" id="selProvinces" onchange="region.changed(this, 2, 'selCities')">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        </select>
        <select name="city" id="selCities" onchange="region.changed(this, 3, 'selDistricts')">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        </select>
        <select name="district" id="selDistricts">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        </select></td>
      </tr>
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_shipping']; ?></td>
      <td><select name="shipping_id" id="select4">
        <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        <?php $_from = self::$_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'shipping');if (count($_from)):
    foreach ($_from AS self::$_var['shipping']):
?>
        <option value="<?php echo self::$_var['shipping']['shipping_id']; ?>"><?php echo self::$_var['shipping']['shipping_name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
            </select></td>
      <td align="right"><?php echo self::$_var['lang']['label_payment']; ?></td>
      <td><select name="pay_id" id="select5">
        <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        <?php $_from = self::$_var['pay_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'pay');if (count($_from)):
    foreach ($_from AS self::$_var['pay']):
?>
        <option value="<?php echo self::$_var['pay']['pay_id']; ?>"><?php echo self::$_var['pay']['pay_name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
            </select></td>
    </tr>
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_time']; ?></td>
      <td colspan="3">
      <input type="text" name="start_time" maxlength="60" size="30" readonly="readonly" id="start_time_id" />
      <input name="start_time_btn" type="button" id="start_time_btn" onclick="return showCalendar('start_time_id', '%Y-%m-%d %H:%M', '24', false, 'start_time_btn');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>
      ~      
      <input type="text" name="end_time" maxlength="60" size="30" readonly="readonly" id="end_time_id" />
      <input name="end_time_btn" type="button" id="end_time_btn" onclick="return showCalendar('end_time_id', '%Y-%m-%d %H:%M', '24', false, 'end_time_btn');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>  
      </td>
    </tr>
    <tr>
      <td align="right"><?php echo self::$_var['lang']['label_order_status']; ?></td>
      <td colspan="3">
        <select name="order_status" id="select9">
          <option value="-1"><?php echo self::$_var['lang']['select_please']; ?></option>
          <?php echo self::html_options(array('options'=>self::$_var['os_list'],'selected'=>'-1')); ?>
        </select>
        <?php echo self::$_var['lang']['label_pay_status']; ?>       
        <select name="pay_status" id="select11">
          <option value="-1"><?php echo self::$_var['lang']['select_please']; ?></option>
          <?php echo self::html_options(array('options'=>self::$_var['ps_list'],'selected'=>'-1')); ?>
        </select>
        <?php echo self::$_var['lang']['label_shipping_status']; ?>
        <select name="shipping_status" id="select10">
          <option value="-1"><?php echo self::$_var['lang']['select_please']; ?></option>
          <?php echo self::html_options(array('options'=>self::$_var['ss_list'],'selected'=>'-1')); ?>
        </select></td>
    </tr>
    <tr>
      <td colspan="4"><div align="center">
        <input name="query" type="submit" class="button" id="query" value="<?php echo self::$_var['lang']['button_search']; ?>" />
        <input name="reset" type="reset" class='button' value='<?php echo self::$_var['lang']['button_reset']; ?>' />
      </div></td>
      </tr>
  </table>
</form>
</div>


<script language="JavaScript">
region.isAdmin = false;

</script>

<?php echo self::fetch('pagefooter.htm'); ?>
