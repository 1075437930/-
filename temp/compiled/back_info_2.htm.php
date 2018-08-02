

<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/topbar.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/selectzone.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/common.js"></script>

<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="4"><?php echo self::$_var['lang']['base_info']; ?></th>
  </tr>

  <tr>
    <td width="18%"><div align="right"><strong><?php echo self::$_var['lang']['label_order_sn']; ?></strong></div></td>
   <td width="34%"><?php echo self::$_var['back_order']['order_sn']; ?><?php if (self::$_var['back_order']['extension_code'] == "group_buy"): ?><a href="group_buy.php?act=edit&id=<?php echo self::$_var['back_order']['extension_id']; ?>"><?php echo self::$_var['lang']['group_buy']; ?></a><?php elseif (self::$_var['back_order']['extension_code'] == "exchange_goods"): ?><a href="exchange_goods.php?act=edit&id=<?php echo self::$_var['back_order']['extension_id']; ?>"><?php echo self::$_var['lang']['exchange_goods']; ?></a><?php endif; ?>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_order_time']; ?></strong></div></td>
    <td><?php echo self::$_var['old_order']['add_time']; ?></td>
  </tr>
  <tr>
    <td width="18%"><div align="right"><strong>服务类型：</strong></div></td>
   <td width="34%">
    <?php if (self::$_var['back_order']['back_type'] == 1): ?>退货<?php endif; ?>
    <?php if (self::$_var['back_order']['back_type'] == 2): ?>换货<?php endif; ?>
    <?php if (self::$_var['back_order']['back_type'] == 3): ?>维修<?php endif; ?>
    <?php if (self::$_var['back_order']['back_type'] == 4): ?>退款（无需退货）<?php endif; ?>
   </td>
    <td><div align="right"><strong>退款方式：</strong></div></td>
    <td>
    	<?php if (self::$_var['back_order']['back_pay'] == 1): ?>退款至账户余额<?php endif; ?>
        <?php if (self::$_var['back_order']['back_pay'] == 2): ?>线下退钱<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_user_name']; ?></strong></div></td>
    <td><?php echo empty(self::$_var['back_order']['user_name']) ? self::$_var['lang']['anonymous'] : self::$_var['back_order']['user_name']; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_how_oos']; ?></strong></div></td>
    <td><?php echo self::$_var['old_order']['how_oos']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_shipping']; ?></strong></div></td>
    <td><?php if (self::$_var['old_order']['shipping_id'] > 0): ?><?php echo self::$_var['old_order']['shipping_name']; ?><?php endif; ?> </td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_shipping_fee']; ?></strong></div></td>
    <td><?php echo self::$_var['old_order']['shipping_fee']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_insure_yn']; ?></strong></div></td>
    <td><?php if (self::$_var['old_order']['insure_yn']): ?><?php echo self::$_var['lang']['yes']; ?><?php else: ?><?php echo self::$_var['lang']['no']; ?><?php endif; ?></td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_insure_fee']; ?></strong></div></td>
    <td><?php echo empty(self::$_var['old_order']['insure_fee']) ? '0.00' : self::$_var['old_order']['insure_fee']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_invoice_no']; ?></strong></div></td>
    <td ><?php echo self::$_var['old_order']['invoice_no']; ?></td>
	<td><div align="right"><strong><?php echo self::$_var['lang']['label_shipping_time']; ?></strong></div></td>
    <td><?php echo self::$_var['old_order']['shipping_time']; ?></td>
  </tr>
  </table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="4"><?php echo self::$_var['lang']['back_info']; ?></th>
    </tr>
  <tr>
    <td><div align="right"><strong>申请退货/维修时间：</strong></div></td>
    <td><?php echo self::$_var['back_order']['formated_add_time']; ?></td>
    <td><div align="right"><strong>申请人用户名：</strong></div></td>
    <td><?php echo self::$_var['back_order']['user_name']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>换回商品收件人：</strong></div></td>
    <td><?php echo htmlspecialchars(self::$_var['back_order']['consignee']); ?></td>
    <td><div align="right"><strong>联系电话：</strong></div></td>
    <td><span style="display: block"><?php echo self::$_var['old_order']['tel']; ?></span><span style="display: none"><?php echo self::$_var['old_order']['tel1']; ?></span><a href="javascript:(0)" id="showtel">点击查看</a></td>
  </tr>
  <tr>
    <td><div align="right"><strong>换回商品收货人地址：</strong></div></td>
    <td ><?php echo htmlspecialchars(self::$_var['back_order']['address']); ?></td>
	 <td><div align="right"><strong>邮编：</strong></div></td>
    <td><?php echo htmlspecialchars(self::$_var['back_order']['zipcode']); ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>退货/维修原因：</strong></div></td>
    <td colspan=3><?php echo self::$_var['back_order']['back_reason']; ?></td>
	</tr>
  <tr>
    <td><div align="right"><strong>用户退回商品所用快递：</strong></div></td>
    <td><?php echo htmlspecialchars(self::$_var['back_order']['shipping_name']); ?></td>
    <td><div align="right"><strong>运单号：</strong></div></td>
    <td><?php echo self::$_var['back_order']['invoice_no']; ?></td>
  </tr>
	<tr>
    <td><div align="right"><strong>图片：</strong></div></td>
    <td colspan=3>
    <?php if (( self::$_var['imgs'] )): ?>
    <?php $_from = self::$_var['imgs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'src');if (count($_from)):
    foreach ($_from AS self::$_var['src']):
?>
    <div style="float:left; padding:4px; border:solid #ddd 1px; margin:0 10px 10px 0"><a href="<?php echo self::$_var['src']; ?>" target="_blank"><img height="100" src="<?php echo self::$_var['src']; ?>" /></a></div>
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    <?php endif; ?>
    </td>
  </tr>
</table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="2">客户留言</th>
  </tr>
  <?php if (self::$_var['back_order']['postscript']): ?>
  <tr>
    <td width="18%"><div align="right">（<?php echo self::$_var['back_order']['formated_add_time']; ?>）<strong>客户：</strong></div></td>
    <td><?php echo self::$_var['back_order']['postscript']; ?></td>
  </tr>
  <?php endif; ?>
  <?php $_from = self::$_var['back_replay']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'value');if (count($_from)):
    foreach ($_from AS self::$_var['value']):
?>
  <tr>
    <td><div align="right">（<?php echo self::$_var['value']['add_time']; ?>）<?php if (self::$_var['value']['type'] == 0): ?><strong style="color:#F00">客服：</strong><?php else: ?><strong>客户：</strong><?php endif; ?></div></td>
    <td><?php echo self::$_var['value']['message']; ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  
  <tr>
    <td width="18%"></td>
    <td>
    	<script>
		function check_replay()
		{
			if (document.getElementById("message").value == '')
			{
				alert("请输入回复内容！");
				document.getElementById("message").focus();
				return false;	
			}
		}
		</script>
    	<form action="index.php?act=back&op=replay" method="post" onsubmit="return check_replay()">
        <input name="back_id" type="hidden" value="<?php echo $_REQUEST['back_id']; ?>">
    	<div><textarea id="message" name="message" style="width:500px; height:60px;"></textarea></div>
        <div><input type="submit" value="回复" /></div>
        </form>
    </td>
  </tr>
</table>
</div>



<div class="list-div" style="margin-bottom: 5px">

<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="7" scope="col"><?php echo self::$_var['lang']['goods_info']; ?></th>
    </tr>
  <tr>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_name_brand']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_sn']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['product_sn']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_attr']; ?></strong></div></td>
    <td colspan=3><div align="center"><strong><?php echo self::$_var['lang']['label_send_number']; ?></strong></div></td>
  </tr>
 <?php $_from = self::$_var['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods_info');if (count($_from)):
    foreach ($_from AS self::$_var['goods_info']):
?>
 <tr>
 <td><?php echo self::$_var['goods_info']['goods_name']; ?></td>
 <td><?php echo self::$_var['goods_info']['goods_id']; ?></td>
 <td><?php echo self::$_var['goods_info']['product_id']; ?></td>
 <td><?php echo self::$_var['goods_info']['goods_attr']; ?></td>
 <td colspan=3><?php echo self::$_var['goods_info']['send_number']; ?></td>
 </tr>
 <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <th colspan="7" scope="col"><?php echo self::$_var['lang']['back_goods_info']; ?></th>
    </tr>
  <tr>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_name_brand']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_sn']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['product_sn']; ?></strong></div></td>
    <td scope="col"><div align="center"><strong><?php echo self::$_var['lang']['goods_attr']; ?></strong></div></td>
	<td scope="col"><div align="center"><strong>业务</strong></div></td>
	<td scope="col"><div align="center"><strong>应退金额</strong></div></td>
    <td scope="col" ><div align="center"><strong><?php echo self::$_var['lang']['label_send_number']; ?></strong></div></td>
  </tr>
  <?php $_from = self::$_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS self::$_var['goods']):
?>
  <tr>
    <td>
    <?php if (self::$_var['goods']['goods_id'] > 0 && self::$_var['goods']['extension_code'] != 'package_buy'): ?>
    <a href="<?php echo self::$_var['goods']['goods_url']; ?>" target="_blank"><?php echo self::$_var['goods']['goods_name']; ?> <?php if (self::$_var['goods']['brand_name']): ?>[ <?php echo self::$_var['goods']['brand_name']; ?> ]<?php endif; ?>
    <?php endif; ?>
    </td>
    <td><div align="left"><?php echo self::$_var['goods']['goods_id']; ?></div></td>
    <td><div align="left"><?php echo self::$_var['goods']['product_id']; ?></div></td>
    <td><div align="left"><?php echo nl2br(self::$_var['goods']['goods_attr']); ?></div></td>
	<td>
	    <?php if (self::$_var['back_order']['back_type'] == 1): ?>退货<?php endif; ?>
	    <?php if (self::$_var['back_order']['back_type'] == 2): ?>换货<?php endif; ?>
	    <?php if (self::$_var['back_order']['back_type'] == 3): ?>维修<?php endif; ?>
	    <?php if (self::$_var['back_order']['back_type'] == 4): ?>退款（无需退货）<?php endif; ?>	
	</td>
	<td><?php if (self::$_var['back_order']['back_type'] == 1): ?><?php echo self::$_var['goods']['back_goods_money']; ?><?php endif; ?></td>
    <td><div align="left"><?php echo self::$_var['goods']['back_goods_number']; ?></div></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <?php if (self::$_var['back_order']['back_type'] == 4): ?>
  <tr>
  <td colspan="7"><strong>此单为整单退款，订单已付金额（应退金额）：<?php echo self::$_var['back_order']['money_paid']; ?></strong></td>
  </tr>
  <?php endif; ?>
</table>
</div>

<div class="list-div" style="margin-bottom: 5px">
<form  action="index.php?act=back&op=operate" method="post">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="6"><?php echo self::$_var['lang']['action_info']; ?></th>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_action_note']; ?></strong></div></td>
  <td colspan="5"><textarea name="action_note" cols="80" rows="3"></textarea></td>
    </tr>
  <tr>
    <td><div align="right"></div>
      <div align="right"><strong><?php echo self::$_var['lang']['label_operable_act']; ?></strong> </div></td>
    <td colspan="5">
	<?php if (self::$_var['back_order']['status_back'] < 6): ?>
		<?php if (self::$_var['back_order']['status_back'] == 5): ?>
			<?php if (self::$_var['operable_list']['ok']): ?>
			<input name="ok" type="submit" value="通过申请" class="button" />
			<?php endif; ?>
			<?php if (self::$_var['operable_list']['no']): ?>
			<input name="no" type="submit" value="拒绝申请" class="button" />
			<?php endif; ?>
		<?php else: ?>
			<?php if (self::$_var['operable_list']['confirm'] && self::$_var['back_order']['back_type'] != 4 && ( self::$_var['back_order']['status_back'] == 2 || self::$_var['back_order']['status_back'] == 0 ) && ( self::$_var['back_order']['status_refund'] == 0 || self::$_var['back_order']['status_refund'] == 9 )): ?>
			<input name="confirm" type="submit" value="收到用户寄回商品" class="button" />
			<input name="cancel_apply" type="submit" value="取消退货" class="button" /> 
			<?php endif; ?> 
			<?php if (self::$_var['operable_list']['backshipping'] && self::$_var['back_order']['back_type'] != 4 && self::$_var['back_order']['status_back'] == 1 && ( self::$_var['back_order']['status_refund'] == 0 || self::$_var['back_order']['status_refund'] == 9 )): ?>
			<!--<input name="backshipping" type="submit" class="button" value="换出商品寄出" />-->
			<?php endif; ?>
			<?php if (( self::$_var['back_order']['back_type'] == 4 || ( self::$_var['back_order']['back_type'] == 1 && self::$_var['back_order']['status_back'] == 1 ) ) && ( self::$_var['back_order']['status_refund'] == 0 || self::$_var['back_order']['status_refund'] == 9 )): ?>
			<input name="refund" type="submit" value="去退款" class="button" />
			<?php endif; ?>
			<?php if (self::$_var['operable_list']['backshipping'] && self::$_var['back_order']['back_type'] == 3 && self::$_var['back_order']['status_back'] == 1): ?>
	        <input name="backshipping" type="submit" class="button" value="换出商品寄出" />
	        <?php endif; ?>

			<?php if (self::$_var['operable_list']['backfinish'] && ( self::$_var['back_order']['status_refund'] == 1 || self::$_var['back_order']['status_back'] == 2 )): ?>
			<input name="backfinish" type="submit" value="完成" class="button" />
			<?php endif; ?> 
        <?php endif; ?>
		<input name="after_service" type="submit" value="<?php echo self::$_var['lang']['op_after_service']; ?>" class="button" />
	<?php else: ?>
		<?php if (self::$_var['back_order']['status_back'] == 6): ?>
		此单已被管理员拒绝
		<?php endif; ?>
		<?php if (self::$_var['back_order']['status_back'] == 7): ?>
		此单已被系统取消
		<input name="recover_apply" type="submit" value="恢复待收货状态" class="button" />  
		<?php endif; ?>
		<?php if (self::$_var['back_order']['status_back'] == 8): ?>
		此单已被用户自行取消
		<?php endif; ?>
	<?php endif; ?>
	<input name="back_id" type="hidden" value="<?php echo $_REQUEST['back_id']; ?>">
	</td>
  </tr>
  <tr>
    <th><?php echo self::$_var['lang']['action_user']; ?></th>
    <th><?php echo self::$_var['lang']['action_time']; ?></th>
    <th>退换货状态</th>
    <th>退款状态</th>
    <th><?php echo self::$_var['lang']['action_note']; ?></th>
  </tr>
  <?php $_from = self::$_var['action_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'action');if (count($_from)):
    foreach ($_from AS self::$_var['action']):
?>
  <tr>
    <td><div align="center"><?php echo self::$_var['action']['action_user']; ?></div></td>
    <td><div align="center"><?php echo self::$_var['action']['action_time']; ?></div></td>
    <td><div align="center">
    <?php if (self::$_var['back_order']['back_type'] == 4): ?>
    退款（无需退货）
    <?php else: ?>
    <?php echo self::$_var['action']['status_back']; ?>
    <?php endif; ?>
    </div></td>
    <td><div align="center"><?php echo self::$_var['action']['status_refund']; ?></div></td>
    <td><?php echo nl2br(self::$_var['action']['action_note']); ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
</form>
</div>



<script language="JavaScript">

  var oldAgencyId = <?php echo empty(self::$_var['back_order']['agency_id']) ? '0' : self::$_var['back_order']['agency_id']; ?>;

  onload = function()
  {
    // 开始检查订单
    startCheckOrder();
  }
$("#showtel").click(function(){
    $.ajax({
        type: "post",
        url: "index.php?act=back&op=check",
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
</script>


<?php echo self::fetch('pagefooter.htm'); ?>