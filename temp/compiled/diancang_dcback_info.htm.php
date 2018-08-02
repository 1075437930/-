

<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/selectzone.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/topbar.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="4">原订单基本信息</th>
  </tr>
  <tr>
    <td width="18%"><div align="right"><strong>订单号：</strong></div></td>
    <td width="34%"><?php echo self::$_var['dcback_order']['order_sn']; ?></td>
    <td><div align="right"><strong>下单时间：</strong></div></td>
    <td><?php echo self::$_var['dcback_order']['order_time']; ?></td>
  </tr>
  <tr>
    <td width="18%"><div align="right"><strong>服务类型：</strong></div></td>
    <td width="34%">退货退款</td>
    <td><div align="right"><strong>退款方式：</strong></div></td>
    <td>退款至账户余额 </td>
  </tr>
  <tr>
    <td><div align="right"><strong>收货人：</strong></div></td>
    <td><?php echo empty(self::$_var['dcback_order']['consignee']) ? self::$_var['lang']['anonymous'] : self::$_var['dcback_order']['consignee']; ?></td>
    <td><div align="right"><strong>地址：</strong></div></td>
    <td><?php echo self::$_var['dcback_order']['region']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>配送方式：</strong></div></td>
    <td><?php echo self::$_var['dcback_order']['shipping_name']; ?> </td>
    <td><div align="right"><strong>配送费用：</strong></div></td>
    <td><?php echo self::$_var['dcback_order']['shipping_fee']; ?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>发货单号：</strong></div></td>
    <td ><?php echo self::$_var['dcback_order']['order_nos']; ?></td>
	<td><div align="right"><strong>发货时间：</strong></div></td>
    <td><?php echo self::$_var['dcback_order']['shipping_time']; ?></td>
  </tr>
  </table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="4">退款/退货</th>
    </tr>
  <tr>
    <td><div align="right" width="18%"><strong>退货时间：</strong></div></td>
    <td width="34%"><?php echo self::$_var['dcback_order']['add_time']; ?></td>
    <td><div align="right"><strong>申请人用户名：</strong></div></td>
    <td><?php echo self::$_var['dcback_order']['user_name']; ?><br /><span style="display: block"><?php echo self::$_var['dcback_order']['user_tel1']; ?></span><span style="display: none"><?php echo self::$_var['dcback_order']['user_tel']; ?></span><a href="javascript:" id="showtel">点击查看</a></td>
  </tr>
  <tr>
    <?php if (self::$_var['dcback_order']['look_goods']): ?>
      <td><div align="right" width="18%"><strong>看货开始时间：</strong></div></td>
      <td width="34%"><?php echo self::$_var['dcback_order']['shipping_time']; ?></td>
      <td><div align="right"><strong>看货到期时间</strong></div></td>
      <td><?php echo self::$_var['dcback_order']['look_end_time']; ?></td>
    <?php else: ?>
      <td><div align="right" width="18%"><strong>典藏到期时间：</strong></div></td>
      <td width="34%"><?php echo self::$_var['dcback_order']['new_end_time']; ?></td>
      <td><div align="right"><strong>典藏过期时间</strong></div></td>
      <td><?php echo self::$_var['dcback_order']['new_end_time2']; ?></td>
    <?php endif; ?>

  </tr>
  <tr>
    <td><div align="right"><strong>退货/维修原因：</strong></div></td>
    <td colspan=3><?php echo self::$_var['dcback_order']['back_reason']; ?></td>
	</tr>
  <tr>
    <td><div align="right"><strong>用户退回商品所用快递：</strong></div></td>
    <td><?php echo htmlspecialchars(self::$_var['dcback_order']['shipping_name']); ?></td>
    <td><div align="right"><strong>运单号：</strong></div></td>
    <td><?php echo self::$_var['dcback_order']['invoice_no']; ?></td>
  </tr>
</table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="2">客户留言</th>
  </tr>
  <tr>
    <td align="right" width="18%"><div >（<?php echo self::$_var['dcback_order']['add_time']; ?>）<strong>客户：</strong></div></td>
    <td align="left"><?php echo self::$_var['dcback_order']['postscript']; ?></td>
  </tr>
</table>
</div>
<div class="list-div" style="margin-bottom: 5px">

<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="4" scope="col">原订单-商品信息</th>
    </tr>
  <tr>
    <td scope="col"><div align="center"><strong>商品名称 </strong></div></td>
    <td scope="col"><div align="center"><strong>产品编号</strong></div></td>
    <td scope="col"><div align="center"><strong>产品图片</strong></div></td>
    <td scope="col"><div align="center"><strong>产品价格</strong></div></td>
  </tr>
 <?php $_from = self::$_var['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods_info');if (count($_from)):
    foreach ($_from AS self::$_var['goods_info']):
?>
 <tr>
 <td><?php echo self::$_var['goods_info']['goods_name']; ?></td>
 <td><?php echo self::$_var['goods_info']['goods_sn']; ?></td>
 <td><img src="<?php echo self::$_var['goods_info']['goods_url']; ?>" width="30" height="30" border="0"></td>
 <td><?php echo self::$_var['goods_info']['dc_goods_price']; ?></td>
 </tr>
 <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <th colspan="4" scope="col">退货/返修-商品信息</th>
    </tr>
  <tr>
    <td scope="col"><div align="center"><strong>商品名称 </strong></div></td>
    <td scope="col"><div align="center"><strong>产品图片</strong></div></td>
	<td scope="col"><div align="center"><strong>应退金额</strong></div></td>
	<td scope="col"><div align="center"><strong>退货状态</strong></div></td>
  </tr>
  <tr>
    <td><?php echo self::$_var['dcback_order']['goods_name']; ?></td>

    <td><div align="left"><img src="<?php echo self::$_var['dcback_order']['imgsurl']; ?>" width="30" height="30" border="0"></div></td>
    <td><div align="left"><?php echo self::$_var['dcback_order']['refund_money']; ?></div></td>
	<td><?php echo self::$_var['dcback_order']['status_back_val']; ?></td>
  </tr>
  <tr>
  <td colspan="4"><strong>此单为整单退款，订单已付金额（应退金额）：<?php echo self::$_var['dcback_order']['refund_money']; ?></strong></td>
  </tr>
</table>
</div>
<div class="list-div" style="margin-bottom: 5px">
<form  action="index.php?act=dcback&op=operate" method="post">
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
      <div align="right"><strong>当前可执行操作：</strong> </div></td>
    <td colspan="5">
	<?php if (self::$_var['dcback_order']['status_back'] < 6): ?>
		<?php if (self::$_var['dcback_order']['status_back'] == 5): ?>
			<?php if (self::$_var['operable_list']['ok']): ?>
			<input name="ok" type="submit" value="通过申请" class="button" />
			<?php endif; ?>
			<?php if (self::$_var['operable_list']['no']): ?>
			<input name="no" type="submit" value="拒绝申请" class="button" />
			<?php endif; ?>
		<?php else: ?>
			<?php if (self::$_var['operable_list']['confirm'] && ( self::$_var['dcback_order']['status_back'] == 2 || self::$_var['dcback_order']['status_back'] == 0 ) && self::$_var['dcback_order']['status_refund'] == 0): ?>
			<input name="confirm" type="submit" value="收到用户寄回商品" class="button" />
			<input name="cancel_apply" type="submit" value="取消退货" class="button" /> 
			<?php endif; ?> 
			<?php if (self::$_var['dcback_order']['status_back'] == 1 && self::$_var['dcback_order']['status_refund'] == 0): ?>
			<input name="refund" type="submit" value="去退款" class="button" onClick="return window.confirm('点击后直接退款到用户的余额上面，是否确定');" />
			<?php endif; ?>
			<?php if (self::$_var['operable_list']['backshipping'] && self::$_var['dcback_order']['status_back'] == 1): ?>
	        <input name="backshipping" type="submit" class="button" value="换出商品寄出" />
	        <?php endif; ?>
			<?php if (self::$_var['operable_list']['backfinish'] && ( self::$_var['dcback_order']['status_refund'] == 1 || self::$_var['dcback_order']['status_back'] == 2 )): ?>
			<input name="backfinish" type="submit" value="完成" class="button" onClick="return window.confirm('点击完成相当于不退款只完成退款订单是否确定');" />
			<?php endif; ?> 
        <?php endif; ?>
	<?php else: ?>
		<?php if (self::$_var['dcback_order']['status_back'] == 6): ?>
		此单已被管理员拒绝
		<?php endif; ?>
		<?php if (self::$_var['dcback_order']['status_back'] == 7): ?>
		此单已被系统取消
		<input name="recover_apply" type="submit" value="恢复待收货状态" class="button" />  
		<?php endif; ?>
		<?php if (self::$_var['dcback_order']['status_back'] == 8): ?>
		此单已被用户自行取消
		<?php endif; ?>
	<?php endif; ?>
	<input name="dcback_id" type="hidden" value="<?php echo self::$_var['dcback_order']['back_id']; ?>">
	</td>
  </tr>
  <tr>
    <th>操作者</th>
    <th>操作时间</th>
    <th>退换货状态</th>
    <th>退款状态</th>
    <th>备注</th>
  </tr>
  <?php $_from = self::$_var['action_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'action');if (count($_from)):
    foreach ($_from AS self::$_var['action']):
?>
  <tr>
    <td><div align="center"><?php echo self::$_var['action']['action_user']; ?></div></td>
    <td><div align="center"><?php echo self::$_var['action']['action_time']; ?></div></td>
    <td><div align="center">
		<?php echo self::$_var['action']['status_back']; ?>
    </div></td>
    <td><div align="center"><?php echo self::$_var['action']['status_refund']; ?></div></td>
    <td><?php echo nl2br(self::$_var['action']['action_note']); ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
</form>
</div>

<script language="JavaScript">



 
$("#showtel").click(function(){
    $.ajax({
      type: "post",
      url: "index.php?act=dcback&op=check",
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