 

<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/topbar.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/common.js"></script>
<?php if (self::$_var['user']): ?>
<div id="topbar">
  <div align="right"><a href="" onclick="closebar(); return false"><img src="templates/default/images/close.gif" border="0" /></a></div>
  <table width="100%" border="0">
    <caption><strong> <?php echo self::$_var['lang']['buyer_info']; ?> </strong></caption>
    <tr>
      <td> 用户账号 </td>
      <td> <?php echo self::$_var['user']['alias']; ?></td>
    </tr>
    <tr>
      <td> 账户余额</td>
      <td> <?php echo self::$_var['user']['formated_user_money']; ?> </td>
    </tr>
    <tr>
      <td> 在投金额 </td>
      <td> <?php echo self::$_var['user']['allpic']; ?> </td>
    </tr>
	<tr>
      <td> 优惠金额 </td>
      <td> <?php echo self::$_var['user']['youpic']; ?></td>
    </tr>
    <tr>
      <td> 预计收益 </td>
      <td> <?php echo self::$_var['user']['shouyi_pic']; ?> </td>
    </tr>
  </table>
  <?php if (self::$_var['dcorder']['goods_list']): ?>
  <table width="100%" border="0">
    <caption><strong> 在投产品 </strong></caption>
    <tr>
      <td> 产品编号 </td>
      <td> <?php echo self::$_var['goodsinto']['goods_sn']; ?> </td>
    </tr>
    <tr>
      <td> 产品图片 </td>
      <td> <img src="<?php echo self::$_var['goodsinto']['imgurls']; ?>" width="30" height="30" border="0"  /> </td>
    </tr>
    <tr>
      <td> 产品价格 </td>
      <td> <?php echo self::$_var['goodsinto']['goods_amount']; ?> </td>
    </tr>
    <tr>
      <td> 收益金额 </td>
      <td> <?php echo self::$_var['goodsinto']['gyongjing_pic']; ?> </td>
    </tr>
    <tr>
      <td> 年化率 </td>
      <td> <?php echo self::$_var['goodsinto']['goods_bili']; ?>% </td>
    </tr>
	<tr>
      <td> 投资时长 </td>
      <td> <?php echo self::$_var['goodsinto']['yue_fen']; ?>/月</td>
    </tr>
	
  </table>
  <?php else: ?>
  <table width="100%" border="0">
    <caption><strong> 在投产品 </strong></caption>
    <tr>
      <td> 产品编号 </td>
      <td>  </td>
    </tr>
    <tr>
      <td> 产品图片 </td>
      <td>  </td>
    </tr>
    <tr>
      <td> 产品价格 </td>
      <td>  </td>
    </tr>
    <tr>
      <td> 收益金额 </td>
      <td>  </td>
    </tr>
    <tr>
      <td> 年化率 </td>
      <td> % </td>
    </tr>
  <tr>
      <td> 投资时长 </td>
      <td> /月</td>
    </tr>
  
  </table>
  <?php endif; ?>
</div>
<?php endif; ?>
<form action="index.php?act=dcorder&op=operate" method="post" name="theForm">
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <td colspan="4">
      <div align="center">
        <input name="prev" type="button" class="button" onClick="location.href='index.php?act=dcorder&op=info&dcorder_id=<?php echo self::$_var['prev_id']; ?>';" value="<?php echo self::$_var['lang']['prev']; ?>" <?php if (! self::$_var['prev_id']): ?>disabled<?php endif; ?> />
        <input name="next" type="button" class="button" onClick="location.href='index.php?act=dcorder&op=info&dcorder_id=<?php echo self::$_var['next_id']; ?>';" value="<?php echo self::$_var['lang']['next']; ?>" <?php if (! self::$_var['next_id']): ?>disabled<?php endif; ?> />
	</div></td>
  </tr>
  <tr>
    <th colspan="4">基本信息</th>
  </tr>
  <tr>
    <td width="18%"><div align="right"><strong>订单号:</strong></div></td>
    <td width="34%"><?php echo self::$_var['dcorder']['order_sn']; ?></td>
    <td width="15%"><div align="right"><strong>订单状态:</strong></div></td>
    <td><?php echo self::$_var['dcorder']['status']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>购货人：</strong></div></td>
    <td><?php echo self::$_var['dcorder']['user_name']['alias']; ?> <?php if (self::$_var['dcorder']['user_id'] > 0): ?>[ <a href="" onclick="staticbar();return false;"><?php echo self::$_var['lang']['display_buyer']; ?></a> ] <?php endif; ?></td>
    <td><div align="right"><strong>下单时间：</strong></div></td>
    <td><?php echo self::$_var['dcorder']['formated_add_time']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>支付方式：</strong></div></td>
    <td><?php if (self::$_var['dcorder']['pay_id'] > 0): ?><?php echo self::$_var['dcorder']['pay_name']; ?><?php else: ?>未获取<?php endif; ?></td>
    <td><div align="right"><strong>付款时间：</strong></div></td>
    <td><?php echo self::$_var['dcorder']['pay_time']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>配送方式：</strong></div></td>
    <td><?php if (self::$_var['dcorder']['shipping_id'] > 0): ?><span id="shipping_name"><?php echo self::$_var['dcorder']['shipping_name']; ?></span><?php else: ?>未获得<?php endif; ?></td>
    <td><div align="right"><strong>发货时间：</strong></div></td>
    <td><?php echo self::$_var['dcorder']['shipping_time']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>发货单号：</strong></div></td>
    <td><?php if (self::$_var['dcorder']['shipping_id'] > 0 && self::$_var['dcorder']['shipping_status'] > 0): ?><span><?php if (self::$_var['dcorder']['invoice_no']): ?><span id="invoice_no"><?php echo self::$_var['dcorder']['invoice_no']; ?></span><?php endif; ?></span><a href="index.php?act=dcorder&op=edit&dcorder_id=<?php echo self::$_var['dcorder']['capital_orderid']; ?>&step=invoice_no" class="special"><?php echo self::$_var['lang']['edit']; ?></a><?php endif; ?></td>
    <td><div align="right"><strong>订单来源：</strong></div></td>
    <td>典藏</td>
  </tr>

  <tr>
    <th colspan="4">收货人信息<a href="index.php?act=dcorder&op=edit&dcorder_id=<?php echo self::$_var['dcorder']['capital_orderid']; ?>&step=consignee" class="special"><?php echo self::$_var['lang']['edit']; ?></a></th>
    </tr>
  <tr>
    <td><div align="right"><strong>收货人:</strong></div></td>
    <td><?php echo self::$_var['dcorder']['consignee']; ?></td>
    <td><div align="right"><strong>收货电话：</strong></div></td>
	<td><span style="display: block"><?php echo self::$_var['dcorder']['tel']; ?></span><span style="display: none"><?php echo self::$_var['dcorder']['tel1']; ?></span><a href="javascript:" id="showtel">点击查看</a></td>
  </tr>
  <tr>
    <td><div align="right"><strong>地址：</strong></div></td>
    <td colspan="3">[<?php echo self::$_var['dcorder']['region']; ?>] <?php echo htmlspecialchars(self::$_var['dcorder']['address']); ?></td>  
  </tr>
</table>
</div>

<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="10" scope="col">商品信息</th>
    </tr>
  <tr>
    <td scope="col" width="30%"><div align="center"><strong>商品名称</strong></div></td>
    <td scope="col"><div align="center"><strong>售后</strong></div></td>
	<td scope="col"><div align="center"><strong>产品图片</strong></div></td>
    <td scope="col"><div align="center"><strong>货号</strong></div></td>
    <td scope="col"><div align="center"><strong>价格</strong></div></td>
	<td scope="col"><div align="center"><strong>代金卷优惠</strong></div></td>
    <td scope="col"><div align="center"><strong>数量</strong></div></td>
    <td scope="col"><div align="center"><strong>小计</strong></div></td>
  </tr>

  <tr>
    <td><?php echo self::$_var['goods_intos']['goods_name']; ?></td>
    <td><?php echo self::$_var['goods_intos']['shouhou']; ?></td>
    <td><img src="<?php echo self::$_var['goods_intos']['imgurls']; ?>" width="30" height="30" border="0"  /></td>
    <td><?php echo self::$_var['goods_intos']['goods_sn']; ?></td>
    <td><div align="right"><?php echo self::$_var['goods_intos']['goods_amount']; ?></div></td>
	<td><div align="right"><?php echo self::$_var['goods_intos']['goods_youpic']; ?></div></td>
    <td><div align="right">1</div></td>
    <td><?php echo self::$_var['goods_intos']['order_amount']; ?></td>
  </tr>

  <tr>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
    <td><div align="right"><strong><?php echo self::$_var['lang']['label_total']; ?></strong></div></td>
    <td><div align="right"><?php echo self::$_var['dcorder']['order_amount']; ?></div></td>
  </tr>
</table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th>费用信息：</th>
  </tr>
  <tr>
    <td><div align="right">商品总金额：<strong><?php echo self::$_var['dcorder']['formated_goods_amount']; ?></strong>- 优惠劵抵扣<strong><?php echo self::$_var['dcorder']['formated_goods_youpic']; ?></strong> +  配送费用：<strong><?php echo self::$_var['dcorder']['formated_shipping_fee']; ?></strong>
      </div></td>
  <tr>
    <td><div align="right"> =订单总金额：<strong><?php echo self::$_var['dcorder']['formated_order_amount']; ?></strong></div></td>
  </tr>
  <tr>
    <td><div align="right">
       应付款金额：<strong><?php echo self::$_var['dcorder']['formated_order_amount']; ?></strong> - 使用余额： <strong><?php echo self::$_var['dcorder']['formated_surplus']; ?></strong>
    </div></td>
  <tr>
    <td><div align="right"> = 已付款金额<strong><?php echo self::$_var['dcorder']['formated_money_paid']; ?></strong></div></td>
  </tr>
</table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="6">操作信息</th>
  </tr>
  <tr>
    <td><div align="right"><strong>操作备注：</strong></div></td>
  <td colspan="5"><textarea name="action_note" cols="80" rows="3"></textarea></td>
    </tr>
  <tr>
    <td><div align="right"></div>
      <div align="right"><strong><?php echo self::$_var['lang']['label_operable_act']; ?></strong> </div></td>
	  
		<?php if (self::$_var['dcorder']['pay_status'] == 1 && self::$_var['dcorder']['shipping_status'] == 0 && self::$_var['dcorder']['order_status'] == 1): ?>
			<td colspan="2">
				<input type="text" value="请输入快递单号" name="invoice_no"  style="color:#00F" />    
				<input name="to_shipping" type="submit" style="color:#F00" value="一键发货" class="button" />
			</td>
			<td><div align="right"><strong style="color:#c00">若一键发货请忽略此操作</strong></div></td>
			<td colspan="2">
		<?php else: ?>
			<td colspan="5">
		<?php endif; ?>
		
		<?php if (self::$_var['operable_list']['confirm']): ?>
			<input name="confirm" type="submit" value="<?php echo self::$_var['lang']['op_confirm']; ?>" class="button" />
		<?php endif; ?>
		<?php if (self::$_var['operable_list']['pay']): ?>
			<input name="pay" type="submit" value="<?php echo self::$_var['lang']['op_pay']; ?>" class="button" />
		<?php endif; ?>
		<?php if (self::$_var['operable_list']['unpay']): ?>
			<input name="unpay" type="submit" class="button" value="<?php echo self::$_var['lang']['op_unpay']; ?>" />
		<?php endif; ?>
		<?php if (self::$_var['operable_list']['butui']): ?>
			<input name="butui" type="submit" value="设置不退" class="button" />
		<?php endif; ?>
		<?php if (self::$_var['operable_list']['unship']): ?>
			<input name="unship" type="submit" value="<?php echo self::$_var['lang']['op_unship']; ?>" class="button" />
		<?php endif; ?> 
		<?php if (self::$_var['operable_list']['cancel'] && self::$_var['shenhe'] != 5): ?>
		  <input name="cancel" type="submit" value="<?php echo self::$_var['lang']['op_cancel']; ?>" class="button" />
		<?php endif; ?> 
		<?php if (self::$_var['operable_list']['invalid']): ?>
			<input name="invalid" type="submit" value="<?php echo self::$_var['lang']['op_invalid']; ?>" class="button" />
		<?php endif; ?> 
		
		<?php if (self::$_var['operable_list']['remove']): ?>
			<input name="remove" type="submit" value="<?php echo self::$_var['lang']['remove']; ?>" class="button" onClick="return window.confirm('<?php echo self::$_var['lang']['js_languages']['remove_confirm']; ?>');" />
		<?php endif; ?>
			<input name="dcorder_id" type="hidden" value="<?php echo self::$_var['dcorder']['capital_orderid']; ?>" />
			</td>
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
    <td><?php echo self::$_var['action']['action_note']; ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
</div>

</form>

<script language="JavaScript">

  var oldAgencyId = <?php echo empty(self::$_var['dcorder']['agency_id']) ? '0' : self::$_var['dcorder']['agency_id']; ?>;


  $("#showtel").click(function(){
    $.ajax({
      type: "post",
      url: "index.php?act=dcorder&op=check",
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