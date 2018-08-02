

<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<?php if (self::$_var['step'] == "consignee"): ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/region.js"></script>
<?php endif; ?>

<?php if (self::$_var['step'] == "user"): ?>
<form name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post" onsubmit="return checkUser()">
<div class="main-div" style="padding: 15px">
  <label><input type="radio" name="anonymous" value="1" checked /> <?php echo self::$_var['lang']['anonymous']; ?></label><br />
  <label><input type="radio" name="anonymous" value="0" id="user_useridname" /> <?php echo self::$_var['lang']['by_useridname']; ?></label>
  <input name="keyword" type="text" value="" />
  <input type="button" class="button" name="search" value="<?php echo self::$_var['lang']['button_search']; ?>" onclick="searchUser();" />
  <select name="user"></select>
  <p><?php echo self::$_var['lang']['notice_user']; ?></p>
</div>
<div style="text-align:center">
  <p>
    <input name="submit" type="submit" class="button" value="<?php echo self::$_var['lang']['button_next']; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='index.php?act=order&op=info&func=cancel_order&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</div>
</form>

<?php elseif (self::$_var['step'] == "goods"): ?>
<form name="theForm" action="index.php?act=order&op=update&step=edit_goods&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post">
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th scope="col"><?php echo self::$_var['lang']['goods_name']; ?></th>
    <th scope="col"><?php echo self::$_var['lang']['goods_sn']; ?></th>
    <th scope="col"><?php echo self::$_var['lang']['goods_price']; ?></th>
	<th scope="col">手机专享价格</th>
    <th scope="col"><?php echo self::$_var['lang']['goods_number']; ?></th>
    <th scope="col"><?php echo self::$_var['lang']['goods_attr']; ?></th>
    <th scope="col"><?php echo self::$_var['lang']['subtotal']; ?></th>
    <th scope="col"><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods');self::$_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if (self::$_foreach['goods']['total'] > 0):
    foreach ($_from AS self::$_var['goods']):
        self::$_foreach['goods']['iteration']++;
?>
  <tr>
    <td>
    <?php if (self::$_var['goods']['goods_id'] > 0 && self::$_var['goods']['extension_code'] != 'package_buy'): ?>
    <a href="#" onclick="getGoodsInfo(<?php echo self::$_var['goods']['goods_id']; ?>);"><?php echo self::$_var['goods']['goods_name']; ?></a>
    <?php elseif (self::$_var['goods']['goods_id'] > 0 && self::$_var['goods']['extension_code'] == 'package_buy'): ?>
    <?php echo self::$_var['goods']['goods_name']; ?>
    <?php endif; ?>
    </td>
    <td><?php echo self::$_var['goods']['goods_sn']; ?><input name="rec_id[]" type="hidden" value="<?php echo self::$_var['goods']['rec_id']; ?>" /></td>
    <td><input name="goods_price[]" type="text" style="text-align:right" value="<?php echo self::$_var['goods']['goods_price']; ?>" size="10" />
        <input name="goods_id[]" type="hidden" style="text-align:right" value="<?php echo self::$_var['goods']['goods_id']; ?>" size="10" /></td>
    
	<td><input name="exclusive[]" type="text" style="text-align:right" value="<?php echo self::$_var['goods']['exclusive']; ?>" size="10" />
	 
	<td><input name="goods_number[]" type="text" style="text-align:right" value="<?php echo self::$_var['goods']['goods_number']; ?>" size="6" /></td>
    <td><textarea name="goods_attr[]" cols="30" rows="<?php echo self::$_var['goods']['rows']; ?>"><?php echo self::$_var['goods']['goods_attr']; ?></textarea></td>
    <td align="right"><?php echo self::$_var['goods']['formated_subtotal']; ?></td>
    <td><a href="javascript:confirm_redirect(confirm_drop, 'index.php?act=order&op=remove_order_goods&rec_id=<?php echo self::$_var['goods']['rec_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>&order_id=<?php echo self::$_var['order_id']; ?>')"><?php echo self::$_var['lang']['drop']; ?></a></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <td colspan="5"><span class="require-field"><?php echo self::$_var['lang']['price_note']; ?></span></td>
    <td align="right"><strong><?php echo self::$_var['lang']['label_total']; ?></strong></td>
    <td align="right"><?php echo self::$_var['goods_amount']; ?></td>
    <td><?php if (self::$_foreach['goods']['total'] > 0): ?><input name="edit_goods"  class="button" type="submit" value="更新商品" /><?php endif; ?>
    <input name="goods_count" type="hidden" value="<?php echo self::$_foreach['goods']['total']; ?>" /></td>
  </tr>
</table>
</div>
</form>


<form action="index.php?act=order&op=update&step=goods&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post" onsubmit="return checkGoods()">
  <p align="center">
    <input name="<?php if (self::$_var['step_op'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_op'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='index.php?act=order&op=info&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</form>

<?php elseif (self::$_var['step'] == "consignee"): ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/transport.org.js,js/region.js')); ?>
<script type="text/javascript">
region.isAdmin=false;
</script>
<form name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post" onsubmit="return checkConsignee()">
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <?php if (self::$_var['address_list']): ?>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['address_list']; ?></th>
    <td><select onchange="loadAddress(this.value)"><option value="0" selected><?php echo self::$_var['lang']['select_please']; ?></option>
      <?php $_from = self::$_var['address_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'address');if (count($_from)):
    foreach ($_from AS self::$_var['address']):
?><option value="<?php echo self::$_var['address']['address_id']; ?>" <?php if ($_GET['address_id'] == self::$_var['address']['address_id']): ?>selected<?php endif; ?>><?php echo htmlspecialchars(self::$_var['address']['consignee']); ?> <?php echo self::$_var['address']['email']; ?> <?php echo htmlspecialchars(self::$_var['address']['address']); ?> <?php echo htmlspecialchars(self::$_var['address']['tel']); ?></option><?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </select></td>
  </tr>
  <?php endif; ?>
  <tr>
    <th width="150" align="left"><?php echo self::$_var['lang']['label_consignee']; ?></th>
    <td><input name="consignee" type="text" value="<?php echo self::$_var['order']['consignee']; ?>" />
      <?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  <?php if (self::$_var['exist_real_goods']): ?>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_area']; ?></th>
    <td><select name="country" id="selCountries" onChange="region.changed(this, 1, 'selProvinces')">
        <option value="0" ><?php echo self::$_var['lang']['select_please']; ?></option>
        <?php $_from = self::$_var['country_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'country');if (count($_from)):
    foreach ($_from AS self::$_var['country']):
?>
        <option value="<?php echo self::$_var['country']['region_id']; ?>" selected="true"><?php echo self::$_var['country']['region_name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
      </select> <select name="province" id="selProvinces" onChange="region.changed(this, 2, 'selCities')">
        <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        <?php $_from = self::$_var['province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'province');if (count($_from)):
    foreach ($_from AS self::$_var['province']):
?>
        <option value="<?php echo self::$_var['province']['region_id']; ?>" <?php if (self::$_var['order']['province'] == self::$_var['province']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['province']['region_name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
      </select> <select name="city" id="selCities" onchange="region.changed(this, 3, 'selDistricts')">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
          <?php $_from = self::$_var['city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'city');if (count($_from)):
    foreach ($_from AS self::$_var['city']):
?>
          <option value="<?php echo self::$_var['city']['region_id']; ?>" <?php if (self::$_var['order']['city'] == self::$_var['city']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['city']['region_name']; ?></option>
          <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>
        <select name="district" id="selDistricts">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
          <?php $_from = self::$_var['district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'district');if (count($_from)):
    foreach ($_from AS self::$_var['district']):
?>
          <option value="<?php echo self::$_var['district']['region_id']; ?>" <?php if (self::$_var['order']['district'] == self::$_var['district']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['district']['region_name']; ?></option>
          <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>
        <?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  <?php endif; ?>
 
  <?php if (self::$_var['exist_real_goods']): ?>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_address']; ?></th>
    <td><input name="address" type="text" value="<?php echo self::$_var['order']['address']; ?>" size="40" />
    <?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_zipcode']; ?></th>
    <td><input name="zipcode" type="text" value="<?php echo self::$_var['order']['zipcode']; ?>" /></td>
  </tr>
  <?php endif; ?>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_tel']; ?></th>
    <td><input name="tel" type="text" value="<?php echo self::$_var['order']['tel']; ?>" />
    </td>
  </tr>
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_mobile']; ?></th>
    <td><input name="mobile" type="text" value="<?php echo self::$_var['order']['mobile']; ?>" /></td>
  </tr>
  <?php if (self::$_var['exist_real_goods']): ?>
<!--  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_sign_building']; ?></th>
    <td><input name="sign_building" type="text" value="<?php echo self::$_var['order']['sign_building']; ?>" size="40" /></td>
  </tr>-->
  <tr>
    <th align="left"><?php echo self::$_var['lang']['label_best_time']; ?></th>
    <td><input name="best_time" type="text" value="<?php echo self::$_var['order']['best_time']; ?>" size="40" /></td>
  </tr>
  <?php endif; ?>
</table>
</div>

<div align="center">
  <p>
    <?php if (self::$_var['step_op'] == "add"): ?><?php if (self::$_var['step_op'] == "add"): ?><input type="button" value="<?php echo self::$_var['lang']['button_prev']; ?>" class="button" onclick="history.back()" /><?php endif; ?><?php endif; ?>
    <input name="<?php if (self::$_var['step_op'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_op'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='order.php?act=process&func=cancel_order&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</div>
</form>

<?php elseif (self::$_var['step'] == "shipping"): ?>
<form name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post" onsubmit="return checkShipping()">
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th width="5%">&nbsp;</th>
    <th width="25%"><?php echo self::$_var['lang']['name']; ?></th>
    <th><?php echo self::$_var['lang']['desc']; ?></th>
    <th width="15%"><?php echo self::$_var['lang']['shipping_fee']; ?></th>
    <th width="15%"><?php echo self::$_var['lang']['free_money']; ?></th>
  <th width="15%"><?php echo self::$_var['lang']['insure']; ?></th>
  </tr>
  <?php $_from = self::$_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'shipping');if (count($_from)):
    foreach ($_from AS self::$_var['shipping']):
?>
  <tr>
    <td><input name="shipping" type="radio" value="<?php echo self::$_var['shipping']['shipping_id']; ?>" <?php if (self::$_var['order']['shipping_id'] == self::$_var['shipping']['shipping_id']): ?>checked<?php endif; ?> onclick="" /></td>
    <td><?php echo self::$_var['shipping']['shipping_name']; ?></td>
    <td><?php echo self::$_var['shipping']['shipping_desc']; ?></td>
    <td><div align="right"><?php echo self::$_var['shipping']['format_shipping_fee']; ?></div></td>
    <td><div align="right"><?php echo self::$_var['shipping']['free_money']; ?></div></td>
  <td><div align="right"><?php echo self::$_var['shipping']['insure']; ?></div></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <?php if (self::$_var['pickup_point_list']): ?>
	<tr>
    <th width="5%">&nbsp;</th>
    <th width="25%">门店自提点列表</th>
    <th colspan="4">详细描述</th>
    </tr>
  <?php $_from = self::$_var['pickup_point_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'shipping');if (count($_from)):
    foreach ($_from AS self::$_var['shipping']):
?>
  <tr>
    <td><input name="pickup_point" type="radio" value="<?php echo self::$_var['shipping']['id']; ?>" <?php if (self::$_var['order']['pickup_point'] == self::$_var['shipping']['id']): ?>checked<?php endif; ?> onclick="" /></td>
    <td><?php echo self::$_var['shipping']['shop_name']; ?></td>
    <td colspan="4">地址:<?php echo self::$_var['shipping']['address']; ?>&nbsp;&nbsp;联系人:<?php echo self::$_var['shipping']['contact']; ?>&nbsp;&nbsp;联系方式:<?php echo self::$_var['shipping']['phone']; ?></td>
    </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
  <?php endif; ?>
</table>
</div>

<p align="right"><input name="insure" type="checkbox" value="1" <?php if (self::$_var['order']['insure_fee'] > 0): ?>checked<?php endif; ?> />
<?php echo self::$_var['lang']['want_insure']; ?></p>

  <p align="center">
    <?php if (self::$_var['step_op'] == "add"): ?><input type="button" value="<?php echo self::$_var['lang']['button_prev']; ?>" class="button" onclick="history.back()" /><?php endif; ?>
    <input name="<?php if (self::$_var['step_op'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_op'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='order.php?act=process&func=cancel_order&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</form>

<?php elseif (self::$_var['step'] == "payment"): ?>
<form name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post" onsubmit="return checkPayment()">
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th width="5%">&nbsp;</th>
    <th width="20%"><?php echo self::$_var['lang']['name']; ?></th>
    <th><?php echo self::$_var['lang']['desc']; ?></th>
    <th width="15%"><?php echo self::$_var['lang']['pay_fee']; ?></th>
  </tr>
  <?php $_from = self::$_var['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'payment');if (count($_from)):
    foreach ($_from AS self::$_var['payment']):
?>
  <tr>
    <td><input type="radio" name="payment" value="<?php echo self::$_var['payment']['pay_id']; ?>" <?php if (self::$_var['order']['pay_id'] == self::$_var['payment']['pay_id']): ?>checked<?php endif; ?> /></td>
    <td><?php echo self::$_var['payment']['pay_name']; ?></td>
    <td><?php echo self::$_var['payment']['pay_desc']; ?></td>
    <td align="right"><?php echo self::$_var['payment']['pay_fee']; ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
</div>
  <p align="center">
    <?php if (self::$_var['step_op'] == "add"): ?><input type="button" value="<?php echo self::$_var['lang']['button_prev']; ?>" class="button" onclick="history.back()" /><?php endif; ?>
    <input name="<?php if (self::$_var['step_op'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_op'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='order.php?act=process&func=cancel_order&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</form>

<?php elseif (self::$_var['step'] == "other"): ?>
<form name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post">
<div class="list-div">
<?php if (self::$_var['exist_real_goods'] && ( self::$_var['pack_list'] || self::$_var['card_list'] )): ?>
<table cellpadding="3" cellspacing="1">
  <?php if (self::$_var['pack_list']): ?>
  <tr>
    <th colspan="4" scope="col"><?php echo self::$_var['lang']['select_pack']; ?></th>
    </tr>
  <tr>
    <td width="5%" scope="col">&nbsp;</td>
    <td width="35%" scope="col"><div align="center"><strong><?php echo self::$_var['lang']['name']; ?></strong></div></td>
    <td width="22%" scope="col"><div align="center"><strong><?php echo self::$_var['lang']['pack_fee']; ?></strong></div></td>
    <td width="22%" scope="col"><div align="center"><strong><?php echo self::$_var['lang']['free_money']; ?></strong></div></td>
    </tr>
  <tr>
    <td><input type="radio" name="pack" value="0" <?php if (self::$_var['order']['pack_id'] == 0): ?>checked<?php endif; ?> /></td>
    <td><?php echo self::$_var['lang']['no_pack']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <?php $_from = self::$_var['pack_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'pack');if (count($_from)):
    foreach ($_from AS self::$_var['pack']):
?>
  <tr>
    <td><input type="radio" name="pack" value="<?php echo self::$_var['pack']['pack_id']; ?>" <?php if (self::$_var['order']['pack_id'] == self::$_var['pack']['pack_id']): ?>checked<?php endif; ?> /></td>
    <td><?php echo self::$_var['pack']['pack_name']; ?></td>
    <td><div align="right"><?php echo self::$_var['pack']['format_pack_fee']; ?></div></td>
    <td><div align="right"><?php echo self::$_var['pack']['format_free_money']; ?></div></td>
    </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <?php endif; ?>
  <?php if (self::$_var['card_list']): ?>
  <tr>
    <th colspan="4" scope="col"><?php echo self::$_var['lang']['select_card']; ?></th>
    </tr>
  <tr>
    <td scope="col">&nbsp;</td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['name']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['card_fee']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['free_money']; ?></strong></div></td>
    </tr>
  <tr>
    <td><input type="radio" name="card" value="0" <?php if (self::$_var['order']['card_id'] == 0): ?>checked<?php endif; ?> /></td>
    <td><?php echo self::$_var['lang']['no_card']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <?php $_from = self::$_var['card_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'card');if (count($_from)):
    foreach ($_from AS self::$_var['card']):
?>
  <tr>
    <td><input type="radio" name="card" value="<?php echo self::$_var['card']['card_id']; ?>" <?php if (self::$_var['order']['card_id'] == self::$_var['card']['card_id']): ?>checked<?php endif; ?> /></td>
    <td><?php echo self::$_var['card']['card_name']; ?></td>
    <td><div align="right"><?php echo self::$_var['card']['format_card_fee']; ?></div></td>
    <td><div align="right"><?php echo self::$_var['card']['format_free_money']; ?></div></td>
    </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <?php endif; ?>
</table>
<?php endif; ?>
</div><br />
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <?php if (self::$_var['exist_real_goods']): ?>
  <?php if (self::$_var['card_list']): ?>
  <tr>
    <th><?php echo self::$_var['lang']['label_card_message']; ?></th>
    <td><textarea name="card_message" cols="60" rows="3"><?php echo self::$_var['order']['card_message']; ?></textarea></td>
  </tr>
  <?php endif; ?>
   
  <!--<tr>
    <th><?php echo self::$_var['lang']['label_inv_type']; ?></th>
    <td><input name="inv_type" type="text" id="inv_type" value="<?php echo self::$_var['order']['inv_type']; ?>" size="40" /></td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['label_inv_payee']; ?></th>
    <td><input name="inv_payee" value="<?php echo self::$_var['order']['inv_payee']; ?>" size="40" text="text" /></td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['label_inv_content']; ?></th>
    <td><input name="inv_content" value="<?php echo self::$_var['order']['inv_content']; ?>" size="40" text="text" /></td>
  </tr>-->
  
  <?php endif; ?>
  <tr>
    <th><?php echo self::$_var['lang']['label_postscript']; ?></th>
    <td><textarea name="postscript" cols="60" rows="3"><?php echo self::$_var['order']['postscript']; ?></textarea></td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['label_how_oos']; ?></th>
    <td><input name="how_oos" type="text" value="<?php echo self::$_var['order']['how_oos']; ?>" size="40" /></td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['label_to_buyer']; ?></th>
    <td><textarea name="to_buyer" cols="60" rows="3"><?php echo self::$_var['order']['to_buyer']; ?></textarea></td>
  </tr>
</table>
</div>
  <p align="center">
    <?php if (self::$_var['step_op'] == "add"): ?><input type="button" value="<?php echo self::$_var['lang']['button_prev']; ?>" class="button" onclick="history.back()" /><?php endif; ?>
    <input name="<?php if (self::$_var['step_op'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_op'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='order.php?act=process&func=cancel_order&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</form>

<?php elseif (self::$_var['step'] == "money"): ?>
<form name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post">
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th width="120"><?php echo self::$_var['lang']['label_goods_amount']; ?></th>
    <td width="150" colspan="3"><?php echo self::$_var['order']['formated_goods_amount']; ?></td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['label_tax']; ?></th>
    <td><input name="tax" type="text" id="tax" value="<?php echo self::$_var['order']['tax']; ?>" size="15" /></td>
    <th><?php echo self::$_var['lang']['label_order_amount']; ?></th>
    <td><?php echo self::$_var['order']['formated_total_fee']; ?></td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['label_shipping_fee']; ?></th>
    <td><?php if (self::$_var['exist_real_goods']): ?><input name="shipping_fee" type="text" value="<?php echo self::$_var['order']['shipping_fee']; ?>" size="15"><?php else: ?>0<?php endif; ?></td>
  <th width="120"><?php echo self::$_var['lang']['label_money_paid']; ?></th>
    <td><?php echo self::$_var['order']['formated_money_paid']; ?> </td>
  </tr>
    <tr>
        <th><?php echo self::$_var['lang']['label_pay_fee']; ?></th>
        <td><input name="pay_fee" type="text" value="<?php echo self::$_var['order']['pay_fee']; ?>" size="15"></td>
        <th><?php echo self::$_var['lang']['label_surplus']; ?></th>
        <td>
        <?php if (self::$_var['order']['user_id'] > 0): ?>
            <?php echo self::$_var['order']['balance_pay']; ?>
             <?php echo self::$_var['lang']['available_surplus']; ?><?php echo empty(self::$_var['available_user_money']) ? '0' : self::$_var['available_user_money']; ?>
        <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th><?php if (self::$_var['order']['order_amount'] >= 0): ?> <?php echo self::$_var['lang']['label_money_dues']; ?> <?php else: ?> <?php echo self::$_var['lang']['label_money_refund']; ?> <?php endif; ?></th>
        <td><?php echo self::$_var['order']['formated_order_amount']; ?></td>
        <th>使用淘玉币金额：</th>
        <td>
        <?php if (self::$_var['order']['user_id'] > 0): ?>
            <?php echo self::$_var['order']['taoyubi_pay']; ?>
            可用淘玉币金额：<?php echo self::$_var['available_taoyu_money']; ?>
        <?php endif; ?>
        </td>
    </tr>
    <!--
  <tr>
    <th><?php echo self::$_var['lang']['label_insure_fee']; ?></th>
    <td><?php if (self::$_var['exist_real_goods']): ?><input name="insure_fee" type="text" value="<?php echo self::$_var['order']['insure_fee']; ?>" size="15"><?php else: ?>0<?php endif; ?></td>
  <th><?php echo self::$_var['lang']['label_surplus']; ?></th>
    <td><?php if (self::$_var['order']['user_id'] > 0): ?>
        <input name="surplus" type="text" value="<?php echo self::$_var['order']['surplus']; ?>" size="15">
  <?php endif; ?> <?php echo self::$_var['lang']['available_surplus']; ?><?php echo empty(self::$_var['available_user_money']) ? '0' : self::$_var['available_user_money']; ?></td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['label_pay_fee']; ?></th>
    <td><input name="pay_fee" type="text" value="<?php echo self::$_var['order']['pay_fee']; ?>" size="15"></td>
  <th><?php echo self::$_var['lang']['label_integral']; ?></th>
    <td><?php if (self::$_var['order']['user_id'] > 0): ?>
        <input name="integral" type="text" value="<?php echo self::$_var['order']['integral']; ?>" size="15">
  <?php endif; ?> <?php echo self::$_var['lang']['available_integral']; ?><?php echo empty(self::$_var['available_pay_points']) ? '0' : self::$_var['available_pay_points']; ?></td>
  </tr>
  -->
 <!-- <tr>
    <th><?php echo self::$_var['lang']['label_pack_fee']; ?></th>
    <td><?php if (self::$_var['exist_real_goods']): ?>
      <input name="pack_fee" type="text" value="<?php echo self::$_var['order']['pack_fee']; ?>" size="15">
      <?php else: ?>0<?php endif; ?></td>-->
       <!--<th><?php echo self::$_var['lang']['label_card_fee']; ?></th>
    <td><?php if (self::$_var['exist_real_goods']): ?>
      <input name="card_fee" type="text" value="<?php echo self::$_var['order']['card_fee']; ?>" size="15">
      <?php else: ?>0<?php endif; ?></td>
     
  </tr>-->
    <!--
  <tr>
    <th><?php echo self::$_var['lang']['label_bonus']; ?></th>
    <td>
      <select name="bonus_id">
        <option value="0" <?php if (self::$_var['order']['bonus_id'] == 0): ?>selected<?php endif; ?>><?php echo self::$_var['lang']['select_please']; ?></option>

          <?php $_from = self::$_var['available_bonus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'bonus');if (count($_from)):
    foreach ($_from AS self::$_var['bonus']):
?>

        <option value="<?php echo self::$_var['bonus']['bonus_id']; ?>" <?php if (self::$_var['order']['bonus_id'] == self::$_var['bonus']['bonus_id']): ?>selected<?php endif; ?> money="<?php echo self::$_var['bonus']['type_money']; ?>"><?php echo self::$_var['bonus']['type_name']; ?> - <?php echo self::$_var['bonus']['type_money']; ?></option>

          <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

      </select>    </td>
     <th><?php if (self::$_var['order']['order_amount'] >= 0): ?> <?php echo self::$_var['lang']['label_money_dues']; ?> <?php else: ?> <?php echo self::$_var['lang']['label_money_refund']; ?> <?php endif; ?></th>
    <td><?php echo self::$_var['order']['formated_order_amount']; ?></td>
   
  </tr>
    -->
</table>
</div>
  <p align="center">
    <?php if (self::$_var['step_op'] == "add"): ?><input type="button" value="<?php echo self::$_var['lang']['button_prev']; ?>" class="button" onclick="history.back()" /><?php endif; ?>
    <input name="finish" type="submit" class="button" value="<?php echo self::$_var['lang']['button_finish']; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='order.php?act=process&func=cancel_order&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</form>

<?php elseif (self::$_var['step'] == "invoice"): ?>

<!--form name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post" onsubmit="return checkShipping()">
<div class="list-div">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th width="5%">&nbsp;</th>
    <th width="25%"><?php echo self::$_var['lang']['name']; ?></th>
    <th><?php echo self::$_var['lang']['desc']; ?></th>
    </tr>
  <?php $_from = self::$_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'shipping');if (count($_from)):
    foreach ($_from AS self::$_var['shipping']):
?>
  <tr>
    <td><input name="shipping" type="radio" value="<?php echo self::$_var['shipping']['shipping_id']; ?>" <?php if (self::$_var['order']['shipping_id'] == self::$_var['shipping']['shipping_id']): ?>checked<?php endif; ?> onclick="" /></td>
    <td><?php echo self::$_var['shipping']['shipping_name']; ?></td>
    <td><?php echo self::$_var['shipping']['shipping_desc']; ?></td>
    </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <?php if (self::$_var['pickup_point_list']): ?>
	<tr>
    <th width="5%">&nbsp;</th>
    <th width="25%">门店自提点列表</th>
    <th>详细描述</th>
    </tr>
  <?php $_from = self::$_var['pickup_point_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'shipping');if (count($_from)):
    foreach ($_from AS self::$_var['shipping']):
?>
  <tr>
    <td><input name="pickup_point" type="radio" value="<?php echo self::$_var['shipping']['id']; ?>" <?php if (self::$_var['order']['pickup_point'] == self::$_var['shipping']['id']): ?>checked<?php endif; ?> onclick="" /></td>
    <td><?php echo self::$_var['shipping']['shop_name']; ?></td>
    <td>地址:<?php echo self::$_var['shipping']['address']; ?>&nbsp;&nbsp;联系人:<?php echo self::$_var['shipping']['contact']; ?>&nbsp;&nbsp;联系方式:<?php echo self::$_var['shipping']['phone']; ?></td>
    </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
  <?php endif; ?>
    <td colspan="3"><strong><?php echo self::$_var['lang']['shipping_note']; ?></strong></td>
    </tr>
  <tr>
    <td colspan="3"><a href="javascript:showNotice('noticeinvoiceno');" title="<?php echo self::$_var['lang']['form_notice']; ?>"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>"></a><strong><?php echo self::$_var['lang']['label_invoice_no']; ?></strong><input name="invoice_no" type="text" value="<?php echo self::$_var['order']['invoice_no']; ?>" size="30"/><br/><span class="notice-span" id="noticeinvoiceno" style="display:block;"><?php echo self::$_var['lang']['invoice_no_mall']; ?></span></td>
  </tr>
</table>
</div>

  <p align="center">
    <?php if (self::$_var['step_op'] == "add"): ?><input type="button" value="<?php echo self::$_var['lang']['button_prev']; ?>" class="button" onclick="history.back()" /><?php endif; ?>
    <input name="<?php if (self::$_var['step_op'] == 'add'): ?>next<?php else: ?>finish<?php endif; ?>" type="submit" class="button" value="<?php if (self::$_var['step_op'] == 'add'): ?><?php echo self::$_var['lang']['button_next']; ?><?php else: ?><?php echo self::$_var['lang']['button_submit']; ?><?php endif; ?>" />
    <input type="button" value="<?php echo self::$_var['lang']['button_cancel']; ?>" class="button" onclick="location.href='order.php?act=process&func=cancel_order&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>'" />
  </p>
</form-->


<script lang='javascript' type='text/javascript'>
  function $(id)
  {
    return document.getElementById(id);
  }
</script>

<?php if (self::$_var['step_detail'] == 'edit'): ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/region.js')); ?>
<script lang='javascript' type='text/javascript'>
  region.isAdmin = false;
 function check_input()
  {
    var arr = new Array();
    var key = '';
    var inv_content = '';
    if($('inv_type').value == 'normal_invoice')
    {
      arr = new Array();
      key = 'n';
    }
    else if($('inv_type').value == 'vat_invoice')
    {
      arr = new Array('selProvinces','selCities','selDistricts');
      key = 'v';
    }

    var stop = 'no';

    for(var i=1;stop!='yes';i++)
    {
      if($('inv_input_'+key+i)==null)
      {
      stop = 'yes';
      }
      else
      {
        arr.push('inv_input_'+key+i);
      }
    }

    while(id = arr.pop())
    {
       if($(id).value =='' || $(id).value == '0')
        {
          alert('请输入全部信息！');
          return false;
        }
    }
    if(document.forms['theForm']['inv_payee_type'].value == 'vat_invoice')
    {
      if(document.forms['theForm']['inv_payee'].value == '' || document.forms['theForm']['inv_payee'].value == '0')
      {
        return false;
      }
    }

    if(document.forms['theForm']['inv_type'].value == 'normal_invoice')
    {
       inv_content = document.forms['theForm']['inv_content_n'].value;
    }
    else
    {
      inv_content = document.forms['theForm']['inv_content_v'].value;
    }

    var input = document.createElement('input');
    input.name = 'inv_content';
    input.type = 'hidden';
    input.value = inv_content;
    document.forms['theForm'].appendChild(input);
    return true;
  }

  function change_inv_type(inv_type)
  {
    if(inv_type.value == 'vat_invoice')
    {
      $('vat_invoice_tbody').style.display='';
      $('normal_invoice_tbody').style.display='none';
    }
    else
    {
      $('vat_invoice_tbody').style.display='none';
      $('normal_invoice_tbody').style.display='';
    }
  }

  function change_inv_payee_type(inv_payee_type)
  {
    if(inv_payee_type.value == 'individual')
    {
      $('inv_company_area').style.display='none';
    }
    else if(inv_payee_type.value == 'unit')
    {
      $('inv_company_area').style.display='';
    }
  }

  function check_taxpayer_id(t,id)
  {
      if(!check_preg_match(t.value,'taxpayer_id'))
      {
          document.getElementById(id).innerHTML='纳税人识别号错误，请检查！';
      }
      else
      {
          document.getElementById(id).innerHTML='';
      }
  }

  function check_bank_account(t,id)
  {
      if(!check_preg_match(t.value,'back_account'))
      {
          document.getElementById(id).innerHTML='银行账户含有非法字符！';
      }
      else
      {
          document.getElementById(id).innerHTML='';
      }
  }

  function check_phone_number(t,id)
  {
      if(!check_preg_match(t.value,'phone_number'))
      {
          document.getElementById(id).innerHTML='手机号码格式不正确！';
      }
      else
      {
          document.getElementById(id).innerHTML='';
      }
  }

  function check_preg_match(v,type)
  {
      var pattern = '';
      switch(type)
      {
          case 'taxpayer_id':
              pattern = '^[0-9]{15,}$';
              break;
          case 'back_account':
              pattern = '^[0-9A-z]+ *[0-9A-z]+$';
              break;
          case 'phone_number':
              pattern = '^1[0-9]{10}$';
      }
      var preg = new RegExp(pattern);
      return preg.test(v);
  }
</script>
<form id='invocie_form' name="theForm" action="index.php?act=order&op=update&step=<?php echo self::$_var['step']; ?>&order_id=<?php echo self::$_var['order_id']; ?>&step_op=<?php echo self::$_var['step_op']; ?>" method="post" onsubmit="return check_input()">
<div class='list-div'>
<table width='100%' cellspacing='1'>
<tr><th colspan='2'><strong><?php echo self::$_var['lang']['inv_info']; ?><strong><em style='font-size:12px;color:#f00;'>（所有信息为必填）</em></th><td colspan='1'>&nbsp</td></tr>
<tr>
<th width='13%'><?php echo self::$_var['lang']['label_inv_type']; ?></th>
<td width='40%'>
  <select id='inv_type' onchange='javascript:change_inv_type(this)' name='inv_type'>
    <option value='normal_invoice' <?php if (self::$_var['order']['inv_type'] == 'normal_invoice'): ?>selected='selected'<?php endif; ?>><?php echo self::$_var['lang']['normal_invoice']; ?></option>
    <option value='vat_invoice' <?php if (self::$_var['order']['inv_type'] == 'vat_invoice'): ?>selected='selected'<?php endif; ?>><?php echo self::$_var['lang']['vat_invoice']; ?></option>
  </select>
</td>
<td>&nbsp</td>
</tr>

<tbody id='normal_invoice_tbody' <?php if (self::$_var['order']['inv_type'] == 'vat_invoice'): ?>style='display:none'<?php endif; ?>>
<tr><th><?php echo self::$_var['lang']['label_inv_payee']; ?></th>
<td>
    <select id='inv_input_n1' name = 'inv_payee_type' onchange='change_inv_payee_type(this)'>
      <option value='individual' <?php if (self::$_var['order']['inv_payee_type'] == 'individual'): ?>selected='selected'<?php endif; ?>><?php echo self::$_var['lang']['individual']; ?></option>
      <option value='unit' <?php if (self::$_var['order']['inv_payee_type'] == 'unit'): ?>selected='selected'<?php endif; ?>><?php echo self::$_var['lang']['unit']; ?></option>
    </select>
  </td>
  <td>&nbsp</td>
</tr>
<tr id='inv_company_area' <?php if (self::$_var['order']['inv_payee_type'] != 'unit'): ?>style='display:none'<?php endif; ?>>
  <th><?php echo self::$_var['lang']['label_company_name1']; ?></th>
  <td><input id='inv_company' name='inv_payee' type='text' <?php if (self::$_var['order']['inv_payee_type'] == 'unit'): ?>value='<?php echo self::$_var['order']['inv_payee']; ?>' <?php endif; ?>/></td>
  <td>&nbsp</td>
</tr>
<tr>
  <th><?php echo self::$_var['lang']['label_inv_content']; ?></th>
  <td>
  <select id='inv_input_n2' name='inv_content_n'>
    <option value=''><?php echo self::$_var['lang']['please_select']; ?></option>
    <?php $_from = self::$_var['cfg']['invoice_content']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    <option value='<?php echo self::$_var['item']; ?>' <?php if (self::$_var['item'] == self::$_var['order']['inv_content']): ?>selected='selected'<?php endif; ?>><?php echo self::$_var['item']; ?></option>
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  </select>
</td>
<td>&nbsp</td>
</tr>
</tbody>

<tbody id='vat_invoice_tbody' <?php if (self::$_var['order']['inv_type'] != 'vat_invoice'): ?>style='display:none'<?php endif; ?>>
<tr>
<th><?php echo self::$_var['lang']['label_company_name1']; ?></th>
<td><input id='inv_input_v1' name='vat_inv_company_name' value='<?php echo self::$_var['order']['vat_inv_company_name']; ?>'/></td>
<td>&nbsp</td>
</tr>
<tr>
<th><?php echo self::$_var['lang']['label_taxpayer_id']; ?></th>
<td valign='center'><input id='inv_input_v2' name='vat_inv_taxpayer_id' value='<?php echo self::$_var['order']['vat_inv_taxpayer_id']; ?>' onblur='javascript:check_taxpayer_id(this,"taxpayer_notice")' style='float:left;'/><div id='taxpayer_notice' style='font-size:12px;color:#f00;float:left;'></div></td>
<td>&nbsp</td>
</tr>
<tr>
<th><?php echo self::$_var['lang']['label_registration_address']; ?></th>
<td><input id='inv_input_v3' name='vat_inv_registration_address' value='<?php echo self::$_var['order']['vat_inv_registration_address']; ?>'/></td>
<td>&nbsp</td>
</tr>
<tr>
<th><?php echo self::$_var['lang']['label_registration_phone']; ?></th>
<td><input id='inv_input_v4' name='vat_inv_registration_phone' value='<?php echo self::$_var['order']['vat_inv_registration_phone']; ?>'/></td>
<td>&nbsp</td>
</tr>
<tr>
<th><?php echo self::$_var['lang']['label_deposit_bank']; ?></th>
<td><input id='inv_input_v5' name='vat_inv_deposit_bank' value='<?php echo self::$_var['order']['vat_inv_deposit_bank']; ?>'/></td>
<td>&nbsp</td>
</tr>
<tr>
<th><?php echo self::$_var['lang']['label_bank_account']; ?></th>
<td><input id='inv_input_v6' name='vat_inv_bank_account' value='<?php echo self::$_var['order']['vat_inv_bank_account']; ?>' onblur='javascript:check_bank_account(this,"bank_account_notice")' style='float:left;'/><div id='bank_account_notice' style='font-size:12px;color:#f00;float:left;'></div></td>
<td>&nbsp</td>
</tr>
<th><?php echo self::$_var['lang']['label_inv_content']; ?></th>
<td>
  <select id='inv_input_v7' name='inv_content'>
    <option value=''><?php echo self::$_var['lang']['please_select']; ?></option>
    <?php $_from = self::$_var['cfg']['invoice_content']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    <option value='<?php echo self::$_var['item']; ?>' <?php if (self::$_var['item'] == self::$_var['order']['inv_content']): ?>selected='selected'<?php endif; ?>><?php echo self::$_var['item']; ?></option>
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  </select>
</td>
<td>&nbsp</td>
<tr>
<th><?php echo self::$_var['lang']['label_inv_consignee_name']; ?></th>
<td><input id='inv_input_v8' name='inv_consignee_name' value='<?php echo self::$_var['order']['inv_consignee_name']; ?>'/></td>
<td>&nbsp</td>
</tr>
<tr>
<th><?php echo self::$_var['lang']['label_inv_consignee_phone']; ?></th>
<td><input id='inv_input_v9' name='inv_consignee_phone' value='<?php echo self::$_var['order']['inv_consignee_phone']; ?>' onblur='javascript:check_phone_number(this,"phone_number_notice")' style='float:left;'/>
<div id='phone_number_notice' style='font-size:12px;color:#f00;float:left;'></div></td>
<td>&nbsp</td>
</tr>
<tr>
<th>收票人省份：</th>
<td>
<select name="inv_consignee_province" id="selProvinces" onChange="region.changed(this, 2, 'selCities')" style='width:70px;'>
  <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
  <?php $_from = self::$_var['order']['inv_consignee_regions']['inv_consignee_province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'province');if (count($_from)):
    foreach ($_from AS self::$_var['province']):
?>
  <option value="<?php echo self::$_var['province']['region_id']; ?>" <?php if (self::$_var['order']['inv_consignee_province'] == self::$_var['province']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['province']['region_name']; ?></option>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</select>
<select name="inv_consignee_city" id="selCities" onchange="region.changed(this, 3, 'selDistricts')" style='width:70px;'>
  <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
  <?php $_from = self::$_var['order']['inv_consignee_regions']['inv_consignee_city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'city');if (count($_from)):
    foreach ($_from AS self::$_var['city']):
?>
  <option value="<?php echo self::$_var['city']['region_id']; ?>" <?php if (self::$_var['order']['inv_consignee_city'] == self::$_var['city']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['city']['region_name']; ?></option>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</select>
<select name="inv_consignee_district" id="selDistricts" style='width:70px;'>
  <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
  <?php $_from = self::$_var['order']['inv_consignee_regions']['inv_consignee_district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'district');if (count($_from)):
    foreach ($_from AS self::$_var['district']):
?>
  <option value="<?php echo self::$_var['district']['region_id']; ?>" <?php if (self::$_var['order']['inv_consignee_district'] == self::$_var['district']['region_id']): ?>selected<?php endif; ?>><?php echo self::$_var['district']['region_name']; ?></option>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</select>
</td>
<td>&nbsp</td>
</tr>
<tr>
<th><?php echo self::$_var['lang']['label_inv_consignee_address']; ?></th>
<td><input id='inv_input_v10' name='inv_consignee_address' size='30' value='<?php echo self::$_var['order']['inv_consignee_address']; ?>'/></select></td>
<td>&nbsp</td>
</tr>
</tbody>
<tr>
<th colspan='2' align='center'><div style='height:30px;'>
<input class='button' type='submit' value='确定' style='margin-top:5px;'/>
<input class='button' type='button' value='取消' onclick='javascript:history.back()' style='margin-top:5px;'/>
</th>
<td>&nbsp</td>
</tr>
</table>
</div>
</form>

<?php elseif (self::$_var['step_detail'] == 'info'): ?>
<?php if (self::$_var['order']['inv_type'] == 'vat_invoice'): ?>
<?php if (self::$_var['order']['inv_status'] == 'provided'): ?>
<form id='invocie_form' name="theForm" action="order.php?act=unprovide_invoice&act_from=invoice_info&order_id=<?php echo self::$_var['order']['order_id']; ?>&order_sns=<?php echo self::$_var['order']['order_sn']; ?>" method="post">
<?php else: ?>
<form id='invocie_form' name="theForm" action="order.php?act=provide_invoice&act_from=invoice_info&order_id=<?php echo self::$_var['order']['order_id']; ?>&order_sns=<?php echo self::$_var['order']['order_sn']; ?>" method="post">
<?php endif; ?>
<div class='list-div'>
<table width='100%' cellspacing='1'>
<tbody id='order_info_tbody'>
<tr><th colspan='4'><strong><?php echo self::$_var['lang']['order_info']; ?></strong></td><td colspan='2'>&nbsp</td></tr>
<tr>
<td align='right' width='13%'><?php echo self::$_var['lang']['label_order_sn']; ?></td>
<td width='20%'><?php echo self::$_var['order']['order_sn']; ?></td>
<td align='right' width='13%'><?php echo self::$_var['lang']['label_order_time']; ?></td>
<td width='20%'><?php echo self::$_var['order']['order_time']; ?></td>
<td colspan='2'></td>
</tr>
</tbody>
<tbody id='inv_info_tbody'>
<tr><th colspan='4'><strong><?php echo self::$_var['lang']['inv_info']; ?></strong></td><td colspan='4'>&nbsp</td></tr>
<td align='right'><?php echo self::$_var['lang']['label_inv_type']; ?></td>
<td><?php echo self::$_var['lang'][self::$_var['order']['inv_type']]; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_inv_content']; ?></td>
<td><?php echo self::$_var['order']['inv_content']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_company_name1']; ?></td>
<td><?php echo self::$_var['order']['vat_inv_company_name']; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_taxpayer_id']; ?></td>
<td><?php echo self::$_var['order']['vat_inv_taxpayer_id']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_registration_address']; ?></td>
<td><?php echo self::$_var['order']['vat_inv_registration_address']; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_registration_phone']; ?></td>
<td><?php echo self::$_var['order']['vat_inv_registration_phone']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_deposit_bank']; ?></td>
<td><?php echo self::$_var['order']['vat_inv_deposit_bank']; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_bank_account']; ?></td>
<td><?php echo self::$_var['order']['vat_inv_bank_account']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
<tr>
<td align='right' width='13%'><?php echo self::$_var['lang']['label_inv_money']; ?></td>
<td width='20%'><?php echo self::$_var['order']['formatted_inv_money']; ?></td>
<td colspan='4'>&nbsp</td>
</tr>
</tbody>
<tbody id='inv_consignee_info_tbody'>
<tr><th colspan='4'><strong><?php echo self::$_var['lang']['inv_consignee_info']; ?></strong></td><td colspan='4'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_inv_consignee_name']; ?></td>
<td><?php echo self::$_var['order']['inv_consignee_name']; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_inv_consignee_phone']; ?></div></td>
<td><?php echo self::$_var['order']['inv_consignee_phone']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_inv_consignee_address']; ?></td>
<td><?php echo self::$_var['order']['inv_complete_address']; ?></td>
<td colspan='4'>&nbsp</td>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_action_note2']; ?></td>
<td colspan='3'><textarea id='inv_remark' name='inv_remark' type='text' style='height:150px;width:98%;border:none;'><?php echo self::$_var['order']['inv_remark']; ?></textarea></td>
<td colspan='2'>&nbsp</td>
</tr>
</tbody>
<tr>
<th colspan='4' align='center'><div style='height:30px;'>
<?php if (self::$_var['order']['inv_status'] == 'provided'): ?>
<input class='button' type='submit' value='取消开票' style='margin-top:5px;'/>
<?php else: ?>
<input class='button' type='submit' value='开票' style='margin-top:5px;'/>
<?php endif; ?>
</th>
<th colspan='2' >&nbsp</th>
</tr>
</table>
</div>
</form>

<?php elseif (self::$_var['order']['inv_type'] == 'normal_invoice'): ?>
<?php if (self::$_var['order']['inv_status'] == 'provided'): ?>
<form id='invocie_form' name="theForm" action="order.php?act=unprovide_invoice&act_from=invoice_info&order_id=<?php echo self::$_var['order']['order_id']; ?>&order_sns=<?php echo self::$_var['order']['order_sn']; ?>" method="post">
<?php else: ?>
<form id='invocie_form' name="theForm" action="order.php?act=provide_invoice&act_from=invoice_info&order_id=<?php echo self::$_var['order']['order_id']; ?>&order_sns=<?php echo self::$_var['order']['order_sn']; ?>" method="post">
<?php endif; ?>
<div class='list-div'>
<table width='100%' cellspacing='1'>
<tbody id='order_info_tbody'>
<tr><th colspan='4'><strong><?php echo self::$_var['lang']['order_info']; ?></strong></th><td colspan='2'>&nbsp</td></tr>
<tr>
<td align='right' width='13%'><?php echo self::$_var['lang']['label_order_sn']; ?></td>
<td width='20%'><?php echo self::$_var['order']['order_sn']; ?></td>
<td align='right' width='13%'><?php echo self::$_var['lang']['label_order_time']; ?></td>
<td width='20%'><?php echo self::$_var['order']['order_time']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
</tbody>
<tbody id='inv_info_tbody'>
<tr><th colspan='4'><strong><?php echo self::$_var['lang']['inv_info']; ?></strong></th><td colspan='2'>&nbsp</td></tr>
</tr>
<td align='right'><?php echo self::$_var['lang']['label_inv_type']; ?></td>
<td><?php echo self::$_var['lang'][self::$_var['order']['inv_type']]; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_inv_payee']; ?></td>
<td><?php echo self::$_var['order']['inv_payee']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_inv_content']; ?></td>
<td><?php echo self::$_var['order']['inv_content']; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_inv_money']; ?></td>
<td><?php echo self::$_var['order']['formatted_inv_money']; ?></td>
<td colspan='2'>&nbsp</td>
</tr>
</tbody>
<tbody id='inv_consignee_info_tbody'>
<tr><th colspan='4'><strong><?php echo self::$_var['lang']['inv_consignee_info']; ?></strong></th><td colspan='2'>&nbsp</td></tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_inv_consignee_name']; ?></td>
<td><?php echo self::$_var['order']['consignee']; ?></td>
<td align='right'><?php echo self::$_var['lang']['label_inv_consignee_phone']; ?></td>
<td><span style="display: block"><?php echo self::$_var['order']['mobile']; ?></span><span style="display: none"><?php echo self::$_var['order']['mobile1']; ?></span>
  <a href="javascript:(0)" id="showphone">点击查看</a>
</td>

<td colspan='2'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_inv_consignee_address']; ?></td>
<td><?php echo self::$_var['order']['inv_complete_address']; ?></td>
<td colspan='4'>&nbsp</td>
</tr>
<tr>
<td align='right'><?php echo self::$_var['lang']['label_action_note2']; ?></td>
<td colspan='3'><textarea id='inv_remark' name='inv_remark' type='text' style='height:150px;width:98%;border:none;'><?php echo self::$_var['order']['inv_remark']; ?></textarea></td>
<td colspan='2'>&nbsp</td>
</tr>
</tbody>
<tr>
<th colspan='4' align='center'>
<?php if (self::$_var['order']['inv_status'] == 'provided'): ?>
<input class='button' type='submit' value='取消开票' style='margin-top:5px;'/>
<?php else: ?>
<input class='button' type='submit' value='开票' style='margin-top:5px;'/>
<?php endif; ?>
</th>
<th colspan='2'>&nbsp</th>
</tr>
</table>
</div>
</form>
<?php endif; ?>
  <script src="js/jquery-1.10.1.js"></script>
<script language='javascript' type='text/javascript'>
  $("#showphone").click(function(){
    $.ajax({
      type: "post",
      url: "custom_common.php?act=check",
      data: {},
      dataType: "text",
      success: function(data){
        if(data == '{"status":1}'){
          $("#showphone").prev().css("display",'block');
          $("#showphone").prev().prev().css("display",'none');
        }else{
          alert("你没有权限");
        }
      }
    });
  })
var origin_inv_remark = '<?php echo self::$_var['order']['inv_remark']; ?>';
$('inv_remark').onblur=function(){
  if($('inv_remark').value != origin_inv_remark)
  {
    Ajax.call('?act=save_inv_remark','order_id='+<?php echo self::$_var['order']['order_id']; ?>+'&inv_remark='+$('inv_remark').value,save_inv_remark_response,'GET','POST',false,true);
  }
}
function save_inv_remark_response(result)
{
  if(result.error == '1')
  {
    alert('保存失败\n'+result.msg);
  }
  else
  {
    origin_inv_remark = $('inv_remark').value;
    alert('保存成功');
  }
}
</script>
<?php endif; ?>

<?php endif; ?>

<script language="JavaScript">
  var step = '<?php echo self::$_var['step']; ?>';
  var orderId = <?php echo self::$_var['order_id']; ?>;
  var act = '<?php echo $_GET['act']; ?>';

  function checkUser()
  {
    var eles = document.forms['theForm'].elements;

    /* 如果搜索会员，检查是否找到 */
    if (document.getElementById('user_useridname').checked && eles['user'].options.length == 0)
    {
      alert(pls_search_user);
      return false;
    }
    return true;
  }

  function checkGoods()
  {
    var eles = document.forms['theForm'].elements;

    if (eles['goods_count'].value <= 0)
    {
      alert(pls_search_goods);
      return false;
    }
    return true;
  }

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
	if ((eles['mobile'].value=="") && (eles['tel'].value=="")) {
		alert("手机号和电话至少填一项！");
		return false;
	} else {
		var msg="";
		if ((eles['mobile'].value!="") && !checkPhone(eles['mobile'].value)) {
			msg="手机号格式不正确！\n";
		}
		if ((eles['tel'].value!="") && !checkTel(eles['tel'].value)) {
			msg+="电话号格式不正确！";
		}
		if(msg!=""){
			alert(msg);
			return false;
		}
		
	}
    validator = new Validator("theForm");
    validator.required("consignee",  "收货人为空！");
    validator.required("email",  "电子邮件为空！");
    validator.required("address",  "地址为空！");
   // validator.required("tel",  "电话为空！");
    validator.isEmail("email",  "请输入有效邮件地址！");
   // validator.isMobile("tel",  "请输入有效电话！");
    return validator.passed();
  }
  function checkTel( tel )
  {
    var reg = /^[\d|\-|\s|\_]+$/; //只允许使用数字-空格等
    reg = /^(0[0-9]{2,3}-)?([0-9]{6,7})+(-[0-9]{1,4})?$/;
    return reg.test( tel );
  }
  function checkPhone( mobile )
  {
  	var reg = /^1\d{10}$/; //11位数字，以1开头。
  	return reg.test( mobile );
  }
  function checkShipping()
  {
    if (!radioChecked('shipping'))
    {
      alert(pls_select_shipping);
      return false;
    }
    return true;
  }

  function checkPayment()
  {
    if (!radioChecked('payment'))
    {
      alert(pls_select_payment);
      return false;
    }
    return true;
  }

  /**
   * 返回某 radio 是否被选中一个
   * @param string radioName
   */
  function radioChecked(radioName)
  {
    var eles = document.forms['theForm'].elements;

    for (var i = 0; i < eles.length; i++)
    {
      if (eles[i].name == radioName && eles[i].checked)
      {
        return true;
      }
    }
    return false;
  }

  /**
   * 按用户编号或用户名搜索用户
   */
  function searchUser()
  {
    var eles = document.forms['theForm'].elements;

    /* 填充列表 */
    var idName = Utils.trim(eles['keyword'].value);
    if (idName != '')
    {
      Ajax.call('order.php?act=search_users&id_name=' + idName, '', searchUserResponse, 'GET', 'JSON');
    }
  }

  function searchUserResponse(result)
  {
    if (result.message.length > 0)
    {
      alert(result.message);
    }

    if (result.error == 0)
    {
      var eles = document.forms['theForm'].elements;

      /* 清除列表 */
      var selLen = eles['user'].options.length;
      for (var i = selLen - 1; i >= 0; i--)
      {
        eles['user'].options[i] = null;
      }
      var arr = result.userlist;
      var userCnt = arr.length;

      for (var i = 0; i < userCnt; i++)
      {
        var opt = document.createElement('OPTION');
        opt.value = arr[i].user_id;
        opt.text = arr[i].user_name;
        eles['user'].options.add(opt);
      }
    }
  }

  /**
   * 按商品编号或商品名称或商品货号搜索商品
   */
  function searchGoods()
  {
    var eles = document.forms['goodsForm'].elements;

    /* 填充列表 */
    var keyword = Utils.trim(eles['keyword'].value);
    if (keyword != '')
    {
      Ajax.call('index.php?act=order&op=search_goods&keyword=' + keyword, '', searchGoodsResponse, 'GET', 'JSON');
    }
  }

  function searchGoodsResponse(result)
  {
    if (result.message.length > 0)
    {
      alert(result.message);
    }

    if (result.error == 0)
    {
      var eles = document.forms['goodsForm'].elements;

      /* 清除列表 */
      var selLen = eles['goodslist'].options.length;
      for (var i = selLen - 1; i >= 0; i--)
      {
        eles['goodslist'].options[i] = null;
      }

      var arr = result.goodslist;
      var goodsCnt = arr.length;
      if (goodsCnt > 0)
      {
        for (var i = 0; i < goodsCnt; i++)
        {
          var opt = document.createElement('OPTION');
          opt.value = arr[i].goods_id;
          opt.text = arr[i].name;
          eles['goodslist'].options.add(opt);
        }
        getGoodsInfo(arr[0].goods_id);
      }
      else
      {
        getGoodsInfo(0);
      }
    }
  }

  /**
   * 取得某商品信息
   * @param int goodsId 商品id
   */
  function getGoodsInfo(goodsId)
  {
    if (goodsId > 0)
    {
      Ajax.call('index.php?act=order&op=json&func=get_goods_info', 'goods_id=' + goodsId, getGoodsInfoResponse, 'get', 'json');
    }
    else
    {
      document.getElementById('goods_name').innerHTML = '';
      document.getElementById('goods_sn').innerHTML = '';
      document.getElementById('goods_cat').innerHTML = '';
      document.getElementById('goods_brand').innerHTML = '';
      document.getElementById('add_price').innerHTML = '';
      document.getElementById('goods_attr').innerHTML = '';
    }
  }
  function getGoodsInfoResponse(result)
  {
    var eles = document.forms['goodsForm'].elements;

    // 显示商品名称、货号、分类、品牌
    document.getElementById('goods_name').innerHTML = result.goods_name;
    document.getElementById('goods_sn').innerHTML = result.goods_sn;
    document.getElementById('goods_cat').innerHTML = result.cat_name;
    document.getElementById('goods_brand').innerHTML = result.brand_name;

    // 显示价格：包括市场价、本店价（促销价）、会员价
    var priceHtml = '<input type="radio" name="add_price" value="' + result.market_price + '" />市场价 [' + result.market_price + ']<br />' +
      '<input type="radio" name="add_price" value="' + result.shop_price + '" checked />本店价 [' + result.shop_price + ']<br />';
    /*for (var i = 0; i < result.user_price.length; i++)
    {
      priceHtml += '<input type="radio" name="add_price" value="' + result.user_price[i].user_price + '" />' + result.user_price[i].rank_name + ' [' + result.user_price[i].user_price + ']<br />';
    }*/
    priceHtml += '<input type="radio" name="add_price" value="user_input" />' + input_price + '<input type="text" name="input_price" value="" /><br />';
    document.getElementById('add_price').innerHTML = priceHtml;

    // 显示属性
    var specCnt = 0; // 规格的数量
    var attrHtml = '';
    var attrType = '';
    var attrTypeArray = '';
    var attrCnt = result.attr_list.length;
    for (i = 0; i < attrCnt; i++)
    {
      var valueCnt = result.attr_list[i].length;

      // 规格
      if (valueCnt > 1)
      {
        attrHtml += result.attr_list[i][0].attr_name + ': ';
        for (var j = 0; j < valueCnt; j++)
        {
          switch (result.attr_list[i][j].attr_type)
          {
            case '0' :
            case '1' :
              attrType = 'radio';
              attrTypeArray = '';
            break;

            case '2' :
              attrType = 'checkbox';
              attrTypeArray = '[]';
            break;
          }
          attrHtml += '<input type="' + attrType + '" name="spec_' + specCnt + attrTypeArray + '" value="' + result.attr_list[i][j].goods_attr_id + '"';
          if (j == 0)
          {
            attrHtml += ' checked';
          }
          attrHtml += ' />' + result.attr_list[i][j].attr_value;
          if (result.attr_list[i][j].attr_price > 0)
          {
            attrHtml += ' [+' + result.attr_list[i][j].attr_price + ']';
          }
          else if (result.attr_list[i][j].attr_price < 0)
          {
            attrHtml += ' [-' + Math.abs(result.attr_list[i][j].attr_price) + ']';
          }
        }
        attrHtml += '<br />';
        specCnt++;
      }
      // 属性
      else
      {
        attrHtml += result.attr_list[i][0].attr_name + ': ' + result.attr_list[i][0].attr_value + '<br />';
      }
    }
    eles['spec_count'].value = specCnt;
    document.getElementById('goods_attr').innerHTML = attrHtml;
  }

  /**
   * 把商品加入订单
   */
  function addToOrder()
  {
    var eles = document.forms['goodsForm'].elements;

    // 检查是否选择了商品
    if (eles['goodslist'].options.length <= 0)
    {
      alert(pls_search_goods);
      return false;
    }
    return true;
  }

  /**
   * 载入收货地址
   * @param int addressId 收货地址id
   */
  function loadAddress(addressId)
  {

    location.href = 'index.php?act=order&op=edit&order_id=<?php echo $_GET['order_id']; ?>&step=<?php echo $_GET['step']; ?>&address_id=' + addressId;

  }
</script>


<?php echo self::fetch('pagefooter.htm'); ?>