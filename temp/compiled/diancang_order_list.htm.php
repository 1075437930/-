
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<div class="form-div">
  <form action="javascript:searchOrderzhang()" name="searchForm">
    <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    订单号：<input name="order_sn" type="text" id="order_sn" size="15">
    收货人：<input name="consignee" type="text" id="consignee" size="15">
	订单操作状态：
    <select name="order_status" id="order_status">
      <option value="-1">请选择</option>
      <option value="0">未确认</option>
	  <option value="1">已确认</option>
	  <option value="2">已取消</option>
	  <option value="3">无效</option>
	  <option value="4">退货</option>
	  <option value="5">完成</option>
    </select>
	订单发货状态：
	<select name="shipping_status" id="shipping_status">
      <option value="-1">请选择</option>
      <option value="0">未发货</option>
	  <option value="1">已发货</option>
	  <option value="2">已收货</option>
    </select>
	订单支付状态：
	<select name="pay_status" id="pay_status">
      <option value="-1">请选择</option>
      <option value="0">未付款</option>
	  <option value="1">已付款</option>
	  <option value="2">付款中</option>
    </select>
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
	<a href="index.php?act=dcorder&op=lists&tian7=1&daoqi=0&nots_tui=0">7天内到期(<?php echo self::$_var['timesd']['tian_nums']; ?>)</a>
    <a href="index.php?act=dcorder&op=lists&daoqi=2&tian7=0&nots_tui=0">到期未退(<?php echo self::$_var['timesd']['tui_nums']; ?>)</a>
    <a href="index.php?act=dcorder&op=lists&nots_tui=3&tian7=0&daoqi=0">过期不退(<?php echo self::$_var['timesd']['not_nums']; ?>)</a>
  </form>
</div>
<script language="JavaScript">

</script>


<form method="post" action="index.php?act=dcorder&op=operate" name="listForm" >
  <div class="list-div" id="listDiv">
<?php endif; ?>

<table cellpadding="3" cellspacing="1">
  <tr>
    <th>订单号</th>
	<th>商品图片</th>
    <th><a href="javascript:listTable.sort('add_time', 'DESC'); ">下单时间</a><?php echo self::$_var['sort_add_time']; ?></th>
    <th>收货人</th>
    <th>商品总金额</th>
	<th>应付金额</th>
	<th>投资时间</th>
	<th>收益比例</th>
	<th>收益金额</th>
	<th>优惠金额</th>
	<th>优惠途径</th>
    <th>订单状态</th>
	<th>付款渠道</th>
	<th><a href="javascript:listTable.sort('end_time', 'DESC'); ">结束时间</a><?php echo self::$_var['sort_end_time']; ?></th>
	<th>发放代金卷</th>
    <th>操作</th>
  <tr>
  <?php if (self::$_var['dcorder_list']): ?>
  <?php $_from = self::$_var['dcorder_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'order');if (count($_from)):
    foreach ($_from AS self::$_var['order']):
?>
  <tr class="
  	<?php if (self::$_var['order']['can_remove']): ?>tr_canceled<?php endif; ?> 
    <?php if (self::$_var['order']['pay_status'] == 0 && self::$_var['order']['order_status'] != 2): ?>tr_nopay<?php endif; ?> 
    <?php if (self::$_var['order']['shipping_status'] == 2): ?>tr_receipt<?php endif; ?>
    <?php if (self::$_var['order']['order_status'] != 2 && self::$_var['order']['pay_status'] == 2 && self::$_var['order']['shipping_status'] == 0): ?>tr_nosend<?php endif; ?>
    <?php if (self::$_var['order']['tuihuan']): ?>tr_back<?php endif; ?>">
    <td valign="top" nowrap="nowrap"><?php echo self::$_var['order']['order_sn']; ?></td>
    <td valign="top" nowrap="nowrap" align="center">
	<img src="<?php echo self::$_var['order']['imgurls']; ?>" width="30" height="30" border="0"  />
	<br /><?php echo self::$_var['order']['goods_sn']; ?></td>
	<td align="left" valign="top"><?php echo self::$_var['order']['tian_time']; ?></td>
    <td align="left" valign="top"><?php echo self::$_var['order']['consignee']; ?><?php if (self::$_var['order']['tel']): ?> [TEL: <?php echo self::$_var['order']['tel']; ?>]<?php else: ?>无<?php endif; ?> <br /><?php echo self::$_var['order']['address']; ?></td>
    <td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['goods_amount']; ?></td>
	<td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['order_amount']; ?></td>
	<td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['yue_fen']; ?>/月</td>
    <td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['goods_bili']; ?>%</td>
	<td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['gyongjing_pic']; ?></td>
	<td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['goods_youpic']; ?></td>
	<td align="center" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['youpic_name']; ?></td>
	<td align="center" valign="top" nowrap="nowrap"><?php echo self::$_var['lang']['os'][self::$_var['order']['order_status']]; ?>,<?php echo self::$_var['lang']['ps'][self::$_var['order']['pay_status']]; ?>,<?php echo self::$_var['lang']['ss'][self::$_var['order']['shipping_status']]; ?></td>
	 
	<td align="center" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['pay_name']; ?></td>
	<td align="center" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['jie_time']; ?></td>
	 <?php if (self::$_var['order']['send_point'] == 1): ?>
		<td align="center" valign="top" nowrap="nowrap">已发放</td>
	 <?php else: ?>
		<td align="center" valign="top" nowrap="nowrap">
                        <a href="index.php?act=dcorder&op=dcorder_send_point&dcorder_id=<?php echo self::$_var['order']['capital_orderid']; ?>&user_id=<?php echo self::$_var['order']['user_id']; ?>" title="点击发放代金卷">点击发放</a>
                </td>
	 <?php endif; ?>
    <td align="center" valign="top"  nowrap="nowrap">
     <a href="index.php?act=dcorder&op=info&dcorder_id=<?php echo self::$_var['order']['capital_orderid']; ?>"><?php echo self::$_var['lang']['detail']; ?></a>
     <?php if (self::$_var['order']['can_remove']): ?>
     <br /><a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['order']['capital_orderid']; ?>, remove_confirm, 'remove')"><?php echo self::$_var['lang']['remove']; ?></a>
     <?php endif; ?>
     <?php if (self::$_var['order']['look_goods']): ?>
     <br /><span style="color:#F00">看货订单</span>
     <?php else: ?>
      <br /><span style="">典藏订单</span>
     <?php endif; ?>
     <?php if (self::$_var['order']['tuihuan']): ?>
     <br /><span style="color:#F00"><?php echo self::$_var['order']['tuihuan']['back_type']; ?>,<?php echo self::$_var['order']['tuihuan']['status_back']; ?></span>
     <?php endif; ?>
    </td>
  </tr>
 <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <?php else: ?>
	<tr><td class="no-records" colspan="14">暂无订单内容</td></tr>
  <?php endif; ?>
</table>


<table id="page-table" cellspacing="0">
  <tr>
    <td align="right" nowrap="true">
    <?php echo self::fetch('page.htm'); ?>
    </td>
  </tr>
</table>

<?php if (self::$_var['full_page']): ?>
  </div>
</form>
<script language="JavaScript">
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.act = "dcorder";
listTable.query = "lists_query";
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

	
	/**
	 * 搜索订单
	 */
	function searchOrderzhang()
	{
		listTable.filter['order_sn'] = Utils.trim(document.forms['searchForm'].elements['order_sn'].value);
		listTable.filter['consignee'] = Utils.trim(document.forms['searchForm'].elements['consignee'].value);
		listTable.filter['order_status'] = document.forms['searchForm'].elements['order_status'].value;
		listTable.filter['shipping_status'] = document.forms['searchForm'].elements['shipping_status'].value;
		listTable.filter['pay_status'] = document.forms['searchForm'].elements['pay_status'].value;
		listTable.loadList();
	}
 
</script>


<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>

