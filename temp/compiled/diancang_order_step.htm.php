

<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,../js/transport.org.js,validator.js')); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<?php if (self::$_var['step'] == "consignee"): ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/region.js"></script>
<?php endif; ?>

<?php if (self::$_var['step'] == "consignee"): ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/region.js"></script>
<script type="text/javascript">
region.isAdmin=false;
</script>
<form name="theForm" action="index.php?act=dcorder&op=update&step=<?php echo self::$_var['step']; ?>&dcorder_id=<?php echo self::$_var['dcorder_id']; ?>&step_act=<?php echo self::$_var['step_act']; ?>" method="post" onsubmit="return checkConsignee()">
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <?php if (self::$_var['address_list']): ?>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['address_list']; ?></th>
    <td><select onchange="loadAddress(this.value)"><option value="0" selected><?php echo self::$_var['lang']['select_please']; ?></option>
      <?php $_from = self::$_var['address_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'address');if (count($_from)):
    foreach ($_from AS self::$_var['address']):
?><option value="<?php echo self::$_var['address']['address_id']; ?>" <?php if ($_GET['address_id'] == self::$_var['address']['address_id']): ?>selected<?php endif; ?>><?php echo htmlspecialchars(self::$_var['address']['consignee']); ?> <?php echo htmlspecialchars(self::$_var['address']['address']); ?> <?php echo htmlspecialchars(self::$_var['address']['tel']); ?></option><?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </select></td>
  </tr>
  <?php endif; ?>
  <tr>
    <th width="150" align="left"><?php echo self::$_var['lang']['label_consignee']; ?></th>
    <td><input name="consignee" type="text" value="<?php echo self::$_var['dcorder']['consignee']; ?>" />
      <?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_area']; ?></th>
    <td><select name="country" id="selCountries" onChange="region.changed(this, 1, 'selProvinces')">
        <option value="0" selected="true"><?php echo self::$_var['lang']['select_please']; ?></option>
        <?php $_from = self::$_var['country_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'country');if (count($_from)):
    foreach ($_from AS self::$_var['country']):
?>
        <option value="<?php echo self::$_var['country']['region_id']; ?>" <?php if (self::$_var['dcorder']['country'] == self::$_var['country']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['country']['region_name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
      </select> 
	  <select name="province" id="selProvinces" onChange="region.changed(this, 2, 'selCities')">
		  <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
		  <?php $_from = self::$_var['province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'province');if (count($_from)):
    foreach ($_from AS self::$_var['province']):
?>
		  <option value="<?php echo self::$_var['province']['region_id']; ?>" <?php if (self::$_var['dcorder']['province'] == self::$_var['province']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['province']['region_name']; ?></option>
		  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
      </select> 
	  <select name="city" id="selCities" onchange="region.changed(this, 3, 'selDistricts')">
		  <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
		  <?php $_from = self::$_var['city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'city');if (count($_from)):
    foreach ($_from AS self::$_var['city']):
?> 
		  <option value="<?php echo self::$_var['city']['region_id']; ?>" <?php if (self::$_var['dcorder']['city'] == self::$_var['city']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['city']['region_name']; ?></option>
		   <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?> 
	  </select>
	  <select name="district" id="selDistricts">
		  <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
		  <?php $_from = self::$_var['district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'district');if (count($_from)):
    foreach ($_from AS self::$_var['district']):
?>
		  <option value="<?php echo self::$_var['district']['region_id']; ?>" <?php if (self::$_var['dcorder']['district'] == self::$_var['district']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['district']['region_name']; ?></option>
		  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?> 
	  </select>
        <?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_address']; ?></th>
    <td><input name="address" type="text" value="<?php echo self::$_var['dcorder']['address']; ?>" size="40" />
    <?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_tel']; ?></th>
    <td><input name="tel" type="text" value="<?php echo self::$_var['dcorder']['tel1']; ?>" />
    </td>
  </tr>
</table>
</div>

<div align="center">
  <p>
    <input name="<?php if (self::$_var['step_act'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_act'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="history.back()" />
  </p>
</div>
</form>
<?php elseif (self::$_var['step'] == "invoice_no"): ?>

<form name="theForm" action="diancang_order.php?act=step_post&step=<?php echo self::$_var['step']; ?>&dcorder_id=<?php echo self::$_var['dcorder_id']; ?>&step_act=<?php echo self::$_var['step_act']; ?>" method="post">
<div class="list-div">
	<table cellpadding="3" cellspacing="1">
	  <tr>
		<td colspan="3"><strong><?php echo self::$_var['lang']['shipping_note']; ?></strong></td>
	  </tr>
	  <tr>
		<td colspan="3"><a href="javascript:showNotice('noticeinvoiceno');" title="<?php echo self::$_var['lang']['form_notice']; ?>"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>"></a><strong><?php echo self::$_var['lang']['label_invoice_no']; ?></strong><input name="invoice_no" type="text" value="<?php echo self::$_var['dcorder']['invoice_no']; ?>" size="30"/><br/><span class="notice-span" id="noticeinvoiceno" style="display:block;"><?php echo self::$_var['lang']['invoice_no_mall']; ?></span></td>
	  </tr>
	</table>
</div>
  <p align="center">
    <input name="<?php if (self::$_var['step_act'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_act'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="history.back()" />
  </p>
</form>
<?php endif; ?>
<script language="JavaScript">
  var step = '<?php echo self::$_var['step']; ?>';
  var orderId = <?php echo self::$_var['dcorder_id']; ?>;
  var act = '<?php echo $_GET['act']; ?>';

 
  function checkConsignee()
  {
    var eles = document.forms['theForm'].elements;

    if (eles['country'].value <= 0)
    {
      alert(pls_select_area);
      return false;
    }
    if (eles['province'].options.length > 1 && eles['province'].value <= 0)
    {
      alert(pls_select_area);
      return false;
    }
    if (eles['city'].options.length > 1 && eles['city'].value <= 0)
    {
      alert(pls_select_area);
      return false;
    }
    if (eles['district'].options.length > 1 && eles['district'].value <= 0)
    {
      alert(pls_select_area);
      return false;
    }
	if (eles['tel'].value=="") {
		alert("手机号和电话至少填一项！");
		return false;
	} else {
		var msg="";
		if ((eles['tel'].value!="") && !checkPhone(eles['tel'].value)) {
			msg+="电话号格式不正确！";
		}
		if(msg!=""){
			alert(msg);
			return false;
		}
		
	}
    validator = new Validator("theForm");
    validator.required("consignee",  "收货人为空！");
    validator.required("address",  "地址为空！");
    validator.required("tel",  "电话为空！");
    return validator.passed();
  }
 
  function checkPhone( mobile )
  {
  	var reg = /^1\d{10}$/; //11位数字，以1开头。
  	return reg.test( mobile );
  }
	/**
   * 载入收货地址
   * @param int addressId 收货地址id
   */
  function loadAddress(addressId)
  {


    location.href = 'index.php?act=dcorder&op=edit&dcorder_id=<?php echo $_GET['dcorder_id']; ?>&step=<?php echo $_GET['step']; ?>&address_id=' + addressId;

  }
  
</script>


<?php echo self::fetch('pagefooter.htm'); ?> 