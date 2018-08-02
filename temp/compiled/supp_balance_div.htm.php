<div class="list-div" id="listDiv">
<table cellpadding="3" cellspacing="1">
  <tr>
    <th width="12%">
      <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" />
      订单号
    </th>
    <th width="5%">店铺id</th>
    <th width="8%">店铺名称</th>
	<th width="20%">商品信息</th>
    <th width="8%">分销价格</th>
    <th width="8%">成本价格</th>
    <th width="8%">成交价格</th>
	<th width="9%">申请时间</th>
	<th width="9%">结款时间</th>
	<th width="8%">结款状态(可操作)</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'unit');if (count($_from)):
    foreach ($_from AS self::$_var['unit']):
?>
  <tr>
    <td id="" data_id="<?php echo self::$_var['unit']['order_id']; ?>"><input type="checkbox" name="checkboxes[]" value="<?php echo self::$_var['unit']['order_id']; ?>"/><?php echo self::$_var['unit']['order_sn']; ?></td>
    <td align="center"><span><?php echo self::$_var['unit']['supplier_id']; ?></span></td>
    <td align="center"><?php echo self::$_var['unit']['supplier_name']; ?></td>
    <td align="center">
      <?php $_from = self::$_var['unit']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS self::$_var['goods']):
?>
      <a href="<?php echo self::$_var['goods']['goods_url']; ?>" target="_blank"><img src="<?php echo self::$_var['goods']['goods_thumb']; ?>" /><br /><?php echo self::$_var['goods']['goods_name']; ?> <?php if (self::$_var['goods']['brand_name']): ?>[ <?php echo self::$_var['goods']['brand_name']; ?> ]<?php endif; ?></a><br/>
      货号：<?php echo self::$_var['goods']['goods_sn']; ?>&nbsp;&nbsp;&nbsp;
      库存：<?php echo self::$_var['goods']['storage']; ?>
      <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </td>
    <td align="center"><?php echo self::$_var['unit']['fenxiao_price']; ?></td>
    <td align="center"><?php echo self::$_var['unit']['cost_price']; ?></td>
    <td align="center"><?php echo self::$_var['unit']['goods_pay_price']; ?><br/> [结算金额：<?php echo self::$_var['unit']['balance_price']; ?>元]</td>
	<td align="center"><?php echo self::$_var['unit']['add_time']; ?></td>
	<td align="center"><?php echo self::$_var['unit']['pass_time']; ?></td>
	<?php if (self::$_var['unit']['balance_status'] == 1): ?>
    <td align="center"  onclick="passes('<?php echo self::$_var['unit']['balance_price']; ?>','<?php echo self::$_var['unit']['sell_id']; ?>')"><?php echo self::$_var['unit']['o_status']; ?></td>
	<?php else: ?>
    <td align="center"><?php echo self::$_var['unit']['o_status']; ?></td>	
	<?php endif; ?>
	<td align="center">
		<a href="javascript:void;" onclick="listTable.remove(<?php echo self::$_var['unit']['sell_id']; ?>, '确定要删除吗？')" title="<?php echo self::$_var['lang']['trash']; ?>"><img src="templates/default/images/icon_trash.gif" width="16" height="16" border="0" /></a>
	</td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="15"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>



<table id="page-table" cellspacing="0">
  <tr>
    <td>
      <input type="hidden" name="act" value="sellsuppbalance" />
      <input type="hidden" name="op" value="batch" />
      <select name="type" id="selAction" onchange="changeAction()">
        <option value="">请选择</option>
        <option value="balance">批量结款</option>
        <option value="drop">批量删除</option>
      </select>
      <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" id="btnSubmit" name="btnSubmit" class="button" disabled="true" />
    </td>
    <td align="right" nowrap="true">
    <?php echo self::fetch('page.htm'); ?>
    </td>
  </tr>
</table>
</div>