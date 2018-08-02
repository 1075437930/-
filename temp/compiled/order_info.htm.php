

<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/topbar.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/selectzone.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/common.js"></script>
<?php if (self::$_var['user']): ?>
<div id="topbar">
  <div align="right"><a href="" onclick="closebar(); return false"><img src="templates/default/images/close.gif" border="0" /></a></div>
  <table width="100%" border="0">
    <caption><strong> <?php echo self::$_var['lang']['buyer_info']; ?> </strong></caption>
    <tr>
      <td> <?php echo self::$_var['lang']['email']; ?> </td>
      <td> <a href="mailto:<?php echo self::$_var['user']['email']; ?>"><?php echo self::$_var['user']['email']; ?></a> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['user_money']; ?> </td>
      <td> <?php echo self::$_var['user']['formated_user_money']; ?> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['pay_points']; ?> </td>
      <td> <?php echo self::$_var['user']['pay_points']; ?> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['rank_points']; ?> </td>
      <td> <?php echo self::$_var['user']['rank_points']; ?> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['rank_name']; ?> </td>
      <td> <?php echo self::$_var['user']['rank_name']; ?> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['bonus_count']; ?> </td>
      <td> <?php echo self::$_var['user']['bonus_count']; ?> </td>
    </tr>
  </table>

  <?php $_from = self::$_var['address_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'address');if (count($_from)):
    foreach ($_from AS self::$_var['address']):
?>
  <table width="100%" border="0">
    <caption><strong> <?php echo self::$_var['lang']['consignee']; ?> : <?php echo htmlspecialchars(self::$_var['address']['consignee']); ?> </strong></caption>
    <tr>
      <td> <?php echo self::$_var['lang']['email']; ?> </td>
      <td> <a href="mailto:<?php echo self::$_var['address']['email']; ?>"><?php echo self::$_var['address']['email']; ?></a> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['address']; ?> </td>
      <td> <?php echo htmlspecialchars(self::$_var['address']['address']); ?> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['zipcode']; ?> </td>
      <td> <?php echo htmlspecialchars(self::$_var['address']['zipcode']); ?> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['tel']; ?> </td>
      <td> <?php echo htmlspecialchars(self::$_var['address']['tel']); ?> </td>
    </tr>
    <tr>
      <td> <?php echo self::$_var['lang']['mobile']; ?> </td>
      <td> <?php echo htmlspecialchars(self::$_var['address']['mobile']); ?> </td>
    </tr>
  </table>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</div>
<?php endif; ?>

<form action="index.php?act=order&op=operate" method="post" name="theForm">
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <td colspan="4">
      <div align="center">
        <input name="prev" type="button" class="button" onClick="location.href='index.php?act=order&op=info&order_id=<?php echo self::$_var['prev_id']; ?>';" value="<?php echo self::$_var['lang']['prev']; ?>" <?php if (! self::$_var['prev_id']): ?>disabled<?php endif; ?> />
        <input name="next" type="button" class="button" onClick="location.href='index.php?act=order&op=info&order_id=<?php echo self::$_var['next_id']; ?>';" value="<?php echo self::$_var['lang']['next']; ?>" <?php if (! self::$_var['next_id']): ?>disabled<?php endif; ?> />
        <input type="button" onclick="window.open('index.php?act=order&op=info&order_id=<?php echo self::$_var['order']['order_id']; ?>&print=1')" class="button" value="<?php echo self::$_var['lang']['print_order']; ?>" />
		<input type="button" onclick="location.href='index.php?act=order&op=info&order_id=<?php echo self::$_var['order']['order_id']; ?>&print_edit=1';" class="button" value="<?php echo self::$_var['lang']['print_good_in']; ?>" />
	</div></td>
  </tr>
  <tr>
    <th colspan="4"><?php echo self::$_var['lang']['base_info']; ?></th>
  </tr>
  <tr>
    <td width="18%"><div align="right"><strong><?php echo self::$_var['lang']['label_order_sn']; ?></strong></div></td>
    <td width="34%"><?php echo self::$_var['order']['order_sn']; ?><?php if (self::$_var['order']['extension_code'] == "group_buy"): ?><a href="group_buy.php?act=edit&id=<?php echo self::$_var['order']['extension_id']; ?>"><?php echo self::$_var['lang']['group_buy']; ?></a><?php elseif (self::$_var['order']['extension_code'] == "exchange_goods"): ?><a href="exchange_goods.php?act=edit&id=<?php echo self::$_var['order']['extension_id']; ?>"><?php echo self::$_var['lang']['exchange_goods']; ?></a><?php endif; ?></td>
    <td width="15%"><div align="right"><strong><?php echo self::$_var['lang']['label_order_status']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['status']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_user_name']; ?></strong></div></td>
    <td><?php echo empty(self::$_var['order']['user_name']) ? self::$_var['lang']['anonymous'] : self::$_var['order']['user_name']; ?> <?php if (self::$_var['order']['user_id'] > 0): ?><?php endif; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_order_time']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['formated_add_time']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_payment']; ?></strong></div></td>
    <td><?php if (self::$_var['order']['pay_id'] > 0): ?><?php echo self::$_var['order']['pay_name']; ?><?php else: ?><?php echo self::$_var['lang']['require_field']; ?><?php endif; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_pay_time']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['pay_time']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_shipping']; ?></strong></div></td>
    <td><?php if (self::$_var['order']['shipping_id'] > 0): ?><span id="shipping_name"><?php echo self::$_var['order']['shipping_name']; ?></span><?php else: ?><?php echo self::$_var['lang']['require_field']; ?><?php endif; ?>&nbsp;&nbsp;<input type="button" onclick="window.open('index.php?act=order&op=info&order_id=<?php echo self::$_var['order']['order_id']; ?>&shipping_print=1')" class="button" value="<?php echo self::$_var['lang']['print_shipping']; ?>"> <?php if (self::$_var['order']['insure_fee'] > 0): ?>（<?php echo self::$_var['lang']['label_insure_fee']; ?><?php echo self::$_var['order']['formated_insure_fee']; ?>）<?php endif; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_shipping_time']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['shipping_time']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_invoice_no']; ?></strong></div></td>
    <td><?php if (self::$_var['order']['shipping_id'] > 0 && self::$_var['order']['shipping_status'] > 0): ?><span><?php if (self::$_var['order']['invoice_no']): ?><span id="invoice_no"><?php echo self::$_var['order']['invoice_no']; ?></span><?php else: ?>N/A<?php endif; ?></span><a href="index.php?act=order&op=edit&order_id=<?php echo self::$_var['order']['order_id']; ?>&step=shipping" class="special"><?php echo self::$_var['lang']['edit']; ?></a><?php endif; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['from_order']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['referer']; ?></td>
  </tr>
   
  
  <tr>
    <th colspan='4'>发票信息
    <?php if (self::$_var['order']['inv_status'] == 'provided'): ?>
    <a href='javascript:void(0)' class='special' disabled='true' style='color:#ccc'>已开票</a>
    <?php else: ?>
    <a href='index.php?act=order&op=edit&order_id=<?php echo self::$_var['order']['order_id']; ?>&step=invoice&step_detail=edit' class='special'><?php if (self::$_var['order']['inv_type'] == ''): ?><?php echo self::$_var['lang']['add']; ?><?php else: ?><?php echo self::$_var['lang']['edit']; ?><?php endif; ?></a>
    <?php endif; ?>
    </th>
  </tr>
  <?php if (self::$_var['order']['inv_type'] == 'normal_invoice'): ?>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_type']; ?></strong></div></td>
    <td><?php echo self::$_var['lang'][self::$_var['order']['inv_type']]; ?></td>
    <td><div align='right'><strong><?php echo self::$_var['lang']['label_inv_payee']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_payee']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_content']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_content']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_money']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['formatted_inv_money']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_action_note2']; ?></strong></div></td>
    <td colspan='2'><?php echo self::$_var['order']['inv_remark']; ?></td>
    <td>&nbsp</td>
  </tr>
  
  <?php elseif (self::$_var['order']['inv_type'] == 'vat_invoice'): ?>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_type']; ?></strong></div></td>
    <td><?php echo self::$_var['lang'][self::$_var['order']['inv_type']]; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_content']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_content']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_money']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['formatted_inv_money']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_company_name1']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['vat_inv_company_name']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_taxpayer_id']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['vat_inv_taxpayer_id']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_registration_address']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['vat_inv_registration_address']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_registration_phone']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['vat_inv_registration_phone']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_deposit_bank']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['vat_inv_deposit_bank']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_bank_account']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['vat_inv_bank_account']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_consignee_name']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_consignee_name']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_consignee_phone']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_consignee_phone']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_consignee_address']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_complete_address']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_action_note2']; ?></strong></div></td>
    <td colspan='2'><?php echo self::$_var['order']['inv_remark']; ?></td>
    <td>&nbsp</td>
  </tr>
  <?php else: ?>
  <tr>
    <td colspan='4'><div align="center" style='height:50px;line-height:50px;'><strong>没有发票信息</strong></div></td>
  </tr>
  <?php endif; ?>
  
  
  
  
  <tr>
    <th colspan="4"><?php echo self::$_var['lang']['other_info']; ?><a href="index.php?act=order&op=edit&order_id=<?php echo self::$_var['order']['order_id']; ?>&step=other" class="special"><?php echo self::$_var['lang']['edit']; ?></a></th>
    </tr>
 
  <!--<tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_type']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_type']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_payee']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_payee']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_inv_content']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['inv_content']; ?></td>
  </tr>-->
  
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_postscript']; ?></strong></div></td>
    <td colspan="3"><?php echo self::$_var['order']['postscript']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_how_oos']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['how_oos']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
<!--   <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_pack']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['pack_name']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_card']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['card_name']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_card_message']; ?></strong></div></td>
    <td colspan="3"><?php echo self::$_var['order']['card_message']; ?></td>
  </tr> -->
  
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_to_buyer']; ?></strong></div></td>
    <td colspan="3"><?php echo self::$_var['order']['to_buyer']; ?></td>
  </tr>
  <tr>
    <th colspan="4"><?php echo self::$_var['lang']['consignee_info']; ?><a href="index.php?act=order&op=edit&order_id=<?php echo self::$_var['order']['order_id']; ?>&step=consignee" class="special"><?php echo self::$_var['lang']['edit']; ?></a></th>
    </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_consignee']; ?></strong></div></td>
    <td><?php echo htmlspecialchars(self::$_var['order']['consignee']); ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_email']; ?></strong></div></td>
    <td><?php echo self::$_var['order']['email']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_address']; ?></strong></div></td>
    <td>[<?php echo self::$_var['order']['region']; ?>] <?php echo htmlspecialchars(self::$_var['order']['address']); ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_zipcode']; ?></strong></div></td>
    <td><?php echo htmlspecialchars(self::$_var['order']['zipcode']); ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_tel']; ?></strong></div></td>
    <td><span style="display: block"><?php echo self::$_var['order']['tel']; ?></span><span style="display: none"><?php echo self::$_var['order']['tel1']; ?></span><a href="javascript:" id="showtel">点击查看</a></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_mobile']; ?></strong></div></td>
    <td><span style="display: block"><?php echo htmlspecialchars(self::$_var['order']['mobile']); ?></span> <span style="display: none"><?php echo htmlspecialchars(self::$_var['order']['mobile1']); ?></span><a href="javascript:" id="showphone">点击查看</a> </td>
  </tr>
  <tr>
    <!--<td><div align="right"><strong><?php echo self::$_var['lang']['label_sign_building']; ?></strong></div></td>
    <td><?php echo htmlspecialchars(self::$_var['order']['sign_building']); ?></td>-->
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_best_time']; ?></strong></div></td>
    <td colspan="3"><?php echo htmlspecialchars(self::$_var['order']['best_time']); ?></td>
  </tr>
</table>
</div>

<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="10" scope="col"><?php echo self::$_var['lang']['goods_info']; ?><a href="index.php?act=order&op=edit&order_id=<?php echo self::$_var['order']['order_id']; ?>&step=goods" class="special"><?php echo self::$_var['lang']['edit']; ?></a></th>
    </tr>
  <tr>
    <td scope="col" width="30%"><div align="center"><strong><?php echo self::$_var['lang']['goods_name_brand']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_shouhou']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_sn']; ?></strong></div></td>
    <td scope="col" colspan="2"><div align="center"><strong><?php echo self::$_var['lang']['goods_price']; ?></strong></div></td>
	  <td scope="col"><div align="center"><strong>手机专享价格</strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_number']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_attr']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['storage']; ?></strong></div></td>
    <td scope="col" colspan="2"><div align="center"><strong><?php echo self::$_var['lang']['subtotal']; ?></strong></div></td>
  </tr>
  <?php $_from = self::$_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS self::$_var['goods']):
?>
  <tr>
    <td >
    <?php if (self::$_var['goods']['goods_id'] > 0 && self::$_var['goods']['extension_code'] != 'package_buy'): ?>
    <a href="<?php echo self::$_var['goods']['goods_url']; ?>" target="_blank"><?php echo self::$_var['goods']['goods_name']; ?> <?php if (self::$_var['goods']['brand_name']): ?>[ <?php echo self::$_var['goods']['brand_name']; ?> ]<?php endif; ?>
    <?php if (self::$_var['goods']['is_gift']): ?><?php if (self::$_var['goods']['goods_price'] > 0): ?><?php echo self::$_var['lang']['remark_favourable']; ?><?php else: ?><?php echo self::$_var['lang']['remark_gift']; ?><?php endif; ?><?php endif; ?>
    <?php if (self::$_var['goods']['parent_id'] > 0): ?><?php echo self::$_var['lang']['remark_fittings']; ?><?php endif; ?></a>
    <?php elseif (self::$_var['goods']['goods_id'] > 0 && self::$_var['goods']['extension_code'] == 'package_buy'): ?>
   	
    <a href="javascript:void(0)" onclick="setSuitShow(<?php echo self::$_var['goods']['goods_id']; ?>)" style="font-size:15px;color:#ff0000;font-weight:bold;" ><?php echo self::$_var['goods']['goods_name']; ?><span style="color:#FF0000;"><?php echo self::$_var['lang']['remark_package']; ?></span></a>
    <div id="suit_<?php echo self::$_var['goods']['goods_id']; ?>" style="padding-left:20px;">
	包括以下商品：<br />
        <?php $_from = self::$_var['goods']['package_goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'package_goods_list');if (count($_from)):
    foreach ($_from AS self::$_var['package_goods_list']):
?>
          <a href="index.php?act=goods&op=info&id=<?php echo self::$_var['package_goods_list']['goods_id']; ?>" target="_blank"><?php echo self::$_var['package_goods_list']['goods_name']; ?></a><br />
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </div>
	
    <?php endif; ?>
    </td>
    <td><?php echo self::$_var['goods']['shouhou']; ?></td>
    <td><?php echo self::$_var['goods']['goods_sn']; ?></td>
    <td colspan="2"><div align="right"><?php echo self::$_var['goods']['formated_goods_price']; ?></div></td>
	<td><div align="right"><?php echo self::$_var['goods']['exclusive']; ?></div></td>
    <td><div align="right"><?php echo self::$_var['goods']['goods_number']; ?>
    </div></td>
    <td><?php echo nl2br(self::$_var['goods']['goods_attr']); ?></td>
    <td><div align="right"><?php echo self::$_var['goods']['storage']; ?></div></td>
    <td colspan="2"><div align="right"><?php echo self::$_var['goods']['formated_subtotal']; ?></div></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2"><?php if (self::$_var['order']['total_weight']): ?><div align="right"><strong><?php echo self::$_var['lang']['label_total_weight']; ?>
    </strong></div><?php endif; ?></td>
    <td><?php if (self::$_var['order']['total_weight']): ?><div align="right"><?php echo self::$_var['order']['total_weight']; ?>克
    </div><?php endif; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_total']; ?></strong></div></td>
    <td ><div align="right"><?php echo self::$_var['order']['formated_goods_amount']; ?></div></td>
  </tr>
</table>
</div>

<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th><?php echo self::$_var['lang']['fee_info']; ?><a href="index.php?act=order&op=edit&order_id=<?php echo self::$_var['order']['order_id']; ?>&step=money" class="special"><?php echo self::$_var['lang']['edit']; ?></a></th>
  </tr>
  <tr>
    <td><div align="right"><?php echo self::$_var['lang']['label_goods_amount']; ?><strong><?php echo self::$_var['order']['formated_goods_amount']; ?></strong>
- <?php echo self::$_var['lang']['label_discount']; ?><strong><?php echo self::$_var['order']['formated_discount']; ?></strong>     + <?php echo self::$_var['lang']['label_tax']; ?><strong><?php echo self::$_var['order']['formated_tax']; ?></strong>
      + <?php echo self::$_var['lang']['label_shipping_fee']; ?><strong><?php echo self::$_var['order']['formated_shipping_fee']; ?></strong>
<!--       + <?php echo self::$_var['lang']['label_insure_fee']; ?><strong><?php echo self::$_var['order']['formated_insure_fee']; ?></strong> -->
      + <?php echo self::$_var['lang']['label_pay_fee']; ?><strong><?php echo self::$_var['order']['formated_pay_fee']; ?></strong>
<!--      + <?php echo self::$_var['lang']['label_pack_fee']; ?><strong><?php echo self::$_var['order']['formated_pack_fee']; ?></strong>
      + <?php echo self::$_var['lang']['label_card_fee']; ?><strong><?php echo self::$_var['order']['formated_card_fee']; ?></strong>-->
      </div></td>
  <tr>
    <td><div align="right"> = <?php echo self::$_var['lang']['label_order_amount']; ?><strong><?php echo self::$_var['order']['formated_total_fee']; ?></strong></div></td>
  </tr>
  <tr>
    <td><div align="right">
      - <?php echo self::$_var['lang']['label_money_paid']; ?><strong><?php echo self::$_var['order']['formated_money_paid']; ?></strong>&nbsp;&nbsp;&nbsp;
      - 支付方式：<strong><?php echo self::$_var['order']['pay_name']; ?></strong>&nbsp;&nbsp;&nbsp;
      - <?php echo self::$_var['lang']['label_surplus']; ?><strong><?php echo self::$_var['order']['formated_balance_pay']; ?></strong>&nbsp;&nbsp;&nbsp;
      - 使用淘玉币金额：<strong><?php echo self::$_var['order']['taoyubi_pay']; ?></strong>
      <!--- <?php echo self::$_var['lang']['label_integral']; ?> <strong><?php echo self::$_var['order']['formated_integral_money']; ?></strong>-->
    </div></td>
  </tr> 
  <tr>
    <td><div align="right"> = <?php if (self::$_var['order']['order_amount'] >= 0): ?><?php echo self::$_var['lang']['label_money_dues']; ?><strong><?php echo self::$_var['order']['formated_order_amount']; ?></strong>
      <?php else: ?><?php echo self::$_var['lang']['label_money_refund']; ?><strong><?php echo self::$_var['order']['formated_money_refund']; ?></strong>
      <input name="refund" type="button" value="<?php echo self::$_var['lang']['refund']; ?>" onclick="location.href='index.php?act=order&op=process&func=load_refund&anonymous=<?php if (self::$_var['order']['user_id'] <= 0): ?>1<?php else: ?>0<?php endif; ?>&order_id=<?php echo self::$_var['order']['order_id']; ?>&refund_amount=<?php echo self::$_var['order']['money_refund']; ?>'" />
      <?php endif; ?><?php if (self::$_var['order']['extension_code'] == "group_buy"): ?><br /><?php echo self::$_var['lang']['notice_gb_order_amount']; ?><?php endif; ?></div></td>
  </tr>
</table>
</div>

<div class="list-div" style="margin-bottom: 5px">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="6"><?php echo self::$_var['lang']['action_info']; ?></th>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_action_note']; ?></strong></div></td>
  <td colspan="5"><textarea name="action_note" cols="80" rows="3"></textarea><span style="color:#ff0063">（必填）</span></td>
    </tr>
  <tr>
    <td><div align="right"></div>
      <div align="right"><strong><?php echo self::$_var['lang']['label_operable_act']; ?></strong> </div></td>
	  
    <?php if (self::$_var['order']['pay_status'] == 2 && self::$_var['order']['shipping_status'] != 1 && self::$_var['order']['order_status'] == 1): ?>
    <td colspan="2">
    
    <?php if (self::$_var['is_pre_sale'] == 0 || self::$_var['pre_sale_success'] == 1): ?>
   
	<input type="text" onblur="if(this.value=='') this.value='请输入快递单号';"  onfocus="if(this.value=='请输入快递单号') this.value='';" value="请输入快递单号"   name="invoice_no"  style="color:#00F">    
    <input name="to_shipping" type="submit" style="color:#F00" value="一键发货" class="button" />
    <?php endif; ?>
    </td>
    <td><div align="right"><strong style="color:#c00">若一键发货请忽略此操作</strong></div></td><td colspan="2">
    <?php else: ?>
    <td colspan="5">
    <?php endif; ?>
      
    <!-- 
    op_confirm-<?php echo self::$_var['lang']['op_confirm']; ?></br>
    op_pay-<?php echo self::$_var['lang']['op_pay']; ?></br>
    op_unpay-<?php echo self::$_var['lang']['op_unpay']; ?></br>
    op_prepare-<?php echo self::$_var['lang']['op_prepare']; ?></br>
    op_split-<?php echo self::$_var['lang']['op_split']; ?></br>
    op_unship-<?php echo self::$_var['lang']['op_unship']; ?></br>
    op_receive-<?php echo self::$_var['lang']['op_receive']; ?></br>
    op_cancel-<?php echo self::$_var['lang']['op_cancel']; ?></br>
    op_invalid-<?php echo self::$_var['lang']['op_invalid']; ?></br>
     -->
    <?php if (self::$_var['operable_list']['confirm']): ?>
      <input name="confirm" type="submit" value="<?php echo self::$_var['lang']['op_confirm']; ?>" class="button" />
        <?php endif; ?> <?php if (self::$_var['operable_list']['pay']): ?>
        <input name="pay" type="submit" value="<?php echo self::$_var['lang']['op_pay']; ?>" class="button" />
        <?php endif; ?> <?php if (self::$_var['operable_list']['unpay']): ?>
        <input name="unpay" type="submit" class="button" value="<?php echo self::$_var['lang']['op_unpay']; ?>" />
        <?php endif; ?> <?php if (self::$_var['operable_list']['prepare'] && ( self::$_var['is_pre_sale'] == 0 || self::$_var['pre_sale_success'] == 1 )): ?>
        <input name="prepare" type="submit" value="<?php echo self::$_var['lang']['op_prepare']; ?>" class="button" />
        <?php endif; ?> <?php if (self::$_var['operable_list']['split'] && ( self::$_var['is_pre_sale'] == 0 || self::$_var['pre_sale_success'] == 1 )): ?>
        <input name="ship" type="submit" value="<?php echo self::$_var['lang']['op_split']; ?>" class="button" />
        <?php endif; ?> <?php if (self::$_var['operable_list']['unship']): ?>
        <input name="unship" type="submit" value="<?php echo self::$_var['lang']['op_unship']; ?>" class="button" />
        <?php endif; ?> <?php if (self::$_var['operable_list']['receive']): ?>
        <input name="receive" type="submit" value="<?php echo self::$_var['lang']['op_receive']; ?>" class="button" />
          
          <?php endif; ?> <?php if (self::$_var['operable_list']['cancel'] && self::$_var['shenhe'] != 5): ?>
          <input name="cancel" type="submit" value="<?php echo self::$_var['lang']['op_cancel']; ?>" class="button" />
          
        <?php endif; ?> <?php if (self::$_var['operable_list']['invalid']): ?>
        <input name="invalid" type="submit" value="<?php echo self::$_var['lang']['op_invalid']; ?>" class="button" />
        <?php endif; ?> <?php if (self::$_var['operable_list']['return']): ?>
        
		<!--
        <input name="return" type="submit" value="<?php echo self::$_var['lang']['op_return']; ?>" class="button" />
		-->
		
        <?php endif; ?> <?php if (self::$_var['operable_list']['to_delivery']): ?>
        <input name="to_delivery" type="submit" value="<?php echo self::$_var['lang']['op_to_delivery']; ?>" class="button"/>
        <input name="order_sn" type="hidden" value="<?php echo self::$_var['order']['order_sn']; ?>" />
        <?php endif; ?> <input name="after_service" type="submit" value="<?php echo self::$_var['lang']['op_after_service']; ?>" class="button" /><?php if (self::$_var['operable_list']['remove']): ?>
        <input name="remove" type="submit" value="<?php echo self::$_var['lang']['remove']; ?>" class="button" onClick="return window.confirm('<?php echo self::$_var['lang']['js_languages']['remove_confirm']; ?>');" />
        <?php endif; ?>
        <?php if (self::$_var['order']['extension_code'] == "group_buy"): ?><?php echo self::$_var['lang']['notice_gb_ship']; ?><?php endif; ?>
        <?php if (self::$_var['order']['extension_code'] == "pre_sale"): ?><?php echo self::$_var['lang']['notice_ps_ship']; ?><?php endif; ?>
        <?php if (self::$_var['agency_list']): ?>
        <input name="assign" type="submit" value="<?php echo self::$_var['lang']['op_assign']; ?>" class="button" onclick="return assignTo(document.forms['theForm'].elements['agency_id'].value)" />
        <select name="agency_id"><option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        <?php $_from = self::$_var['agency_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'agency');if (count($_from)):
    foreach ($_from AS self::$_var['agency']):
?>
        <option value="<?php echo self::$_var['agency']['agency_id']; ?>" <?php if (self::$_var['agency']['agency_id'] == self::$_var['order']['agency_id']): ?>selected<?php endif; ?>><?php echo self::$_var['agency']['agency_name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>
        <?php endif; ?>
        <input name="order_id" type="hidden" value="<?php echo $_REQUEST['order_id']; ?>"></td>
    </tr>
  <tr>
    <th><?php echo self::$_var['lang']['action_user']; ?></th>
    <th><?php echo self::$_var['lang']['action_time']; ?></th>
    <th><?php echo self::$_var['lang']['order_status']; ?></th>
    <th><?php echo self::$_var['lang']['pay_status']; ?></th>
    <th><?php echo self::$_var['lang']['shipping_status']; ?></th>
    <th><?php echo self::$_var['lang']['action_note']; ?></th>
  </tr>
  <?php $_from = self::$_var['action_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'action');if (count($_from)):
    foreach ($_from AS self::$_var['action']):
?>
  <tr>
    <td><div align="center"><?php echo self::$_var['action']['action_user']; ?></div></td>
    <td><div align="center"><?php echo self::$_var['action']['action_time']; ?></div></td>
    <td><div align="center"><?php echo self::$_var['action']['order_status']; ?></div></td>
    <td><div align="center"><?php echo self::$_var['action']['pay_status']; ?></div></td>
    <td><div align="center"><?php echo self::$_var['action']['shipping_status']; ?></div></td>
    <td><?php echo nl2br(self::$_var['action']['action_note']); ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<?php if (self::$_var['order']['invoices'] && self::$_var['order']['shipping_name'] != '门店自提'): ?>
<table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
 <tr>
    <th align="center">物流跟踪</th>
  </tr>
  <tr>
    <td bgcolor="#ffffff">
		<ul id='ul_i' class="rec-nav">
		<?php $_from = self::$_var['order']['invoices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'invoice_info');self::$_foreach['name_i'] = array('total' => count($_from), 'iteration' => 0);
if (self::$_foreach['name_i']['total'] > 0):
    foreach ($_from AS self::$_var['invoice_info']):
        self::$_foreach['name_i']['iteration']++;
?>
			<li id="div_i_<?php echo self::$_foreach['name_i']['iteration']; ?>"><a href='javascript:;' onclick="get_invoice_info('<?php echo self::$_var['invoice_info']['shipping_name']; ?>','<?php echo self::$_var['invoice_info']['invoice_no']; ?>','<?php echo self::$_foreach['name_i']['iteration']; ?>')">物流<?php echo self::$_foreach['name_i']['iteration']; ?></a></li>
		<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
		</ul>
		<div id="retData"></div>
	</td>
  </tr>
</table>
<?php endif; ?>
</div>
</form>

<script language="JavaScript">

  var oldAgencyId = <?php echo empty(self::$_var['order']['agency_id']) ? '0' : self::$_var['order']['agency_id']; ?>;

  onload = function()
  {
    // 开始检查订单
    startCheckOrder();
  }


  $("#showphone").click(function(){
    $.ajax({
      type: "post",
      url: "index.php?act=order&op=check",
      data: {},
      dataType: "json",
      success: function(data){
        if(data.status == 1){
          $("#showphone").prev().css("display",'block');
          $("#showphone").prev().prev().css("display",'none');
        }else{
          alert("你没有权限");
        }
      }
    });
  })


  $("#showtel").click(function(){
    $.ajax({
      type: "post",
      url: "index.php?act=order&op=check",
      data: {},
      dataType: "json",
      success: function(data){
        if(data.status == 1){
          $("#showtel").prev().css("display",'block');
          $("#showtel").prev().prev().css("display",'none');
        }else{
          alert("你没有权限");
        }
      }
    });
  })


  /**
   * 把订单指派给某办事处
   * @param int agencyId
   */
  function assignTo(agencyId)
  {
    if (agencyId == 0)
    {
      alert(pls_select_agency);
      return false;
    }
    if (oldAgencyId != 0 && agencyId == oldAgencyId)
    {
      alert(pls_select_other_agency);
      return false;
    }
    return true;
  }
</script>
<script language="javascript">


function get_invoice_info(expressid,expressno,div_id)
{
	$("#ul_i").children("li").removeClass();
	document.getElementById("div_i_"+div_id).className = 'selected';
	
	if (expressid == "同城快递")
	{
		Ajax.call(
			'index.php?act=order&op=get_tc_express', 
			'expressno =' + expressno, 
			function(data){
				document.getElementById("retData").innerHTML='快递公司：'+expressid+' &nbsp; 运单号：'+expressno+'<br>';
				document.getElementById("retData").innerHTML+=data;
			}, 
			'POST', 
			'JSON',
			false
		);
	}
	else
	{
		Ajax.call(
			'index.php?act=order&op=express&com='+ expressid+'&nu=' + expressno, 
			'showtest=showtest', 
			function(data){
				document.getElementById("retData").innerHTML='快递公司：'+expressid+' &nbsp; 运单号：'+expressno+'<br>';
				document.getElementById("retData").innerHTML+=data;
			}, 
			'GET', 
			'TEXT', 
			false
		);
	}
}
listTable.act = 'order';
</script>


<?php echo self::fetch('pagefooter.htm'); ?>