<span style='color:#FF0000'>注：拒绝申请和删除申请时，已付款的申请需要先退还申请费用再操作（余额支付的会自行退还到余额中）</span>
<br/>
<table width="100%" cellspacing="1" cellpadding="2" id="list-table">

  <tr>

    <th width="6%">编号</th>

    <th width="6%">店铺ID</th>

    <th width="6%">店铺名称</th>
    <th width="6%">申请功能</th>
    <th width="10%">申请时间</th>

    <th width="6%">申请期限</th>
    <th width="8%">开始时间</th>
    <th width="8%">到期时间</th>

    <th width="6%">所需费用</th>

	<!-- <th width="15%">申请备注</th> -->

	<th width="10%">支付方式</th>
  <th width="6%">交易单号</th>

    <th width="6%">审核状态</th>
    <th>操作</th>

  </tr>

  <?php $_from = self::$_var['apply_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'apply');if (count($_from)):
    foreach ($_from AS self::$_var['apply']):
?>

  <tr align="center" class="<?php echo self::$_var['apply']['id']; ?>" id="<?php echo self::$_var['apply']['id']; ?>">

    <td align="center" class="first-cell" >

      <span>

		<?php echo self::$_var['apply']['id']; ?>

      </span>

    </td>

    <td align="center" class="first-cell" >

      <span>

		<?php echo self::$_var['apply']['supplier_id']; ?>

      </span>

    </td>

    <td align="center" class="first-cell" >

      <?php echo self::$_var['apply']['supplier_name']; ?>

    </td>

    <td align="center" class="first-cell" >

      <?php echo self::$_var['apply']['module']; ?>

    </td>

    <td align="center" class="first-cell" >

      <?php echo self::$_var['apply']['apply_time']; ?>

    </td>

    <td align="center" class="first-cell" >

      <span>

		<?php echo self::$_var['apply']['over_time']; ?>/月

      </span>

    </td>
    <td align="center" class="first-cell" >

      <span>

    <?php echo self::$_var['apply']['open_start_time']; ?>

      </span>

    </td>

    <td align="center" class="first-cell" >

      <span>

		<?php echo self::$_var['apply']['open_end_time']; ?>

      </span>

    </td>

    <td align="center" class="first-cell" >

      <span>

		<?php echo self::$_var['apply']['apply_money']; ?> 元

      </span>

    </td>

	<!-- <td align="center" class="first-cell" >

      <span>

		<?php echo self::$_var['apply']['note']; ?>

      </span>

    </td> -->

	<td align="center" class="first-cell" >

      <span>

		<?php echo self::$_var['apply']['pay_type']; ?>

      </span>
    <?php if (self::$_var['apply']['pay_status'] == 1): ?>
        <span>[<?php echo self::$_var['apply']['pay_status_name']; ?>]</span>
    <?php else: ?>
	   <span style="color:red;">[<?php echo self::$_var['apply']['pay_status_name']; ?>]</span>
    <?php endif; ?>

    </td>
    <td align="center" class="first-cell" >

      <span>
        <?php echo self::$_var['apply']['pay_sn']; ?>
      </span>

    </td>

    <td align="center" class="first-cell" >

      <span>
		    <?php echo self::$_var['apply']['apply_status_name']; ?>
      </span>

    </td>

    <td width="24%" align="center">

      <?php if (self::$_var['apply']['apply_status'] == 1): ?>  
        
        <a href="index.php?act=suppaymodule&op=close&id=<?php echo self::$_var['apply']['id']; ?>" onclick="return confirm('确定要关闭该功能吗？')" title="关闭">关闭</a> 

      <?php elseif (self::$_var['apply']['apply_status'] == 2): ?>  
      
        <a href="index.php?act=suppaymodule&op=pass&id=<?php echo self::$_var['apply']['id']; ?>" onclick="return confirm('确定要通过审核吗？')" title="通过">通过</a>  |
      
         <a href="index.php?act=suppaymodule&op=remove&id=<?php echo self::$_var['apply']['id']; ?>"  onclick="return confirm('已付款的申请,请确保已退还申请费用!\n删除后无法恢复,确定要删除吗？')" title="删除">删除</a>

      <?php else: ?>  
        <a href="index.php?act=suppaymodule&op=refuse&id=<?php echo self::$_var['apply']['id']; ?>" onclick="return confirm('已付款的申请,请确保已退还申请费用!\n确定要拒绝通过吗？')" title="拒绝">拒绝</a> |
        <a href="index.php?act=suppaymodule&op=pass&id=<?php echo self::$_var['apply']['id']; ?>" onclick="return confirm('确定要通过审核吗？')" title="通过">通过</a> |
         <a href="index.php?act=suppaymodule&op=remove&id=<?php echo self::$_var['apply']['id']; ?>" onclick="return confirm('已付款的请确保已退还申请费用!\n删除后无法恢复,确定要删除吗？')" title="删除">删除</a>
      <?php endif; ?>

    </td>

  </tr>

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

</table>



<table id="page-table" cellspacing="0">

  <tr>

    <td align="right" nowrap="true">

      <?php echo self::fetch('page.htm'); ?>

    </td>

  </tr>

</table>

<script>

  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;

  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  
  listTable.act = "suppaymodule";

  listTable.query = "query";

  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item_0_13971400_1528973393');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item_0_13971400_1528973393']):
?>

  <?php if (self::$_var['key'] != "shop_name"): ?>

      listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item_0_13971400_1528973393']; ?>';

  <?php endif; ?>

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  onload = function()

  {

    listTable.query = "query";

  }

</script>

