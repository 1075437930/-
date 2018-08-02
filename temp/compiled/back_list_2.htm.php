<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<style>
.form-search{
	width:50%;
	display:inline-block;
}
.sorts-span-cls{
	position:relative;
	width:40%;
	display:inline-block;
	height:30px;
	margin-top:0px;
}
.sorts-span-cls a{
	display:inline-block;
	line-height:30px;
	width:17%;
	font-weight:bold;
}
</style>

<div class="form-div">
  <form action="javascript:searchOrder()" name="searchForm"  class="form-search">
    <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />    
    <?php echo self::$_var['lang']['order_sn']; ?><input name="order_sn" type="text" id="order_sn" size="15">
    <?php echo htmlspecialchars(self::$_var['lang']['consignee']); ?><input name="consignee" type="text" id="consignee" size="15">
	<?php if (self::$_var['supp_list']): ?>
	<select name="suppid" id="suppid">
      <option value="-1"><?php echo self::$_var['lang']['select_please']; ?></option>
      <?php $_from = self::$_var['supp_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'supp');if (count($_from)):
    foreach ($_from AS self::$_var['supp']):
?>
	  <option value='<?php echo self::$_var['supp']['supplier_id']; ?>' <?php if (self::$_var['supp']['supplier_id'] == $_REQUEST['suppid']): ?> checked <?php endif; ?>><?php echo self::$_var['supp']['supplier_name']; ?></option>
	  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </select>
	<?php endif; ?>
    售后状态<select name="order_type" id="order_type"><option value="0">全部</option><option value="3" <?php if (self::$_var['filter']['order_type'] == '3'): ?>selected<?php endif; ?>>已完成</option><option value="2" <?php if (self::$_var['filter']['order_type'] == '2'): ?>selected<?php endif; ?>>未完成</option><option value="4" <?php if (self::$_var['filter']['order_type'] == '4'): ?>selected<?php endif; ?>>已取消</option></select>
    退单类型<select name="back_type" id="back_type"><option value="0">全部</option><option value="4" <?php if (self::$_var['filter']['back_type'] == '4'): ?>selected<?php endif; ?>>退款</option><option value="1" <?php if (self::$_var['filter']['back_type'] == '1'): ?>selected<?php endif; ?>>退货</option></select>
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
  </form>
  <span class="sorts-span-cls">
	<a href="javascript:sortOrder(5);">待商家审核(<?php echo self::$_var['cntSort']['0']; ?>)</a>
	<a href="javascript:sortOrder(10);">退回待收货(<?php echo self::$_var['cntSort']['1']; ?>)</a>
	<a href="javascript:sortOrder(50);">被取消申请(<?php echo self::$_var['cntSort']['2']; ?>)</a>
	<a href="javascript:sortOrder(40);">待商家退款(<?php echo self::$_var['cntSort']['3']; ?>)</a>
	<a href="javascript:sortOrder(60);">全部(<?php echo self::$_var['cntSort']['4']; ?>)</a>
  </span>
</div>


<form method="post" action="index.php?act=back&op=remove_back" name="listForm" onsubmit="return check()">
  <div class="list-div" id="listDiv">
<?php endif; ?>

<table cellpadding="3" cellspacing="1">
  <tr>
  <th align=left><input onclick='listTable.selectAll(this, "back_id")' type="checkbox"/><?php echo self::$_var['lang']['back_id']; ?></th>
    <th><a href="javascript:listTable.sort('order_sn', 'DESC'); "><?php echo self::$_var['lang']['order_sn']; ?></a><?php echo self::$_var['sort_order_sn']; ?></th>
	<?php if (self::$_var['supp_list']): ?>
	<th><a href="javascript:listTable.sort('supplier_name', 'DESC'); ">供货商家</a><?php echo self::$_var['sort_supplier_name']; ?></th>
	<?php endif; ?>
    <th>商品sn</th>
    <th>商品图片</th>
	<th ><?php echo self::$_var['lang']['back_goods_name']; ?></th>
    <th><a href="javascript:listTable.sort('add_time', 'DESC'); "><?php echo self::$_var['lang']['label_add_time']; ?></a><?php echo self::$_var['sort_add_time']; ?></th>
	<th><?php echo self::$_var['lang']['back_money_1']; ?></th>
	<th><?php echo self::$_var['lang']['back_money_2']; ?></th>
    <th><a href="javascript:listTable.sort('consignee', 'DESC'); "><?php echo self::$_var['lang']['consignee']; ?></a><?php echo self::$_var['sort_consignee']; ?></th>
    <!--<th><a href="javascript:listTable.sort('update_time', 'DESC'); ">签收时间</a><?php echo self::$_var['sort_update_time']; ?></th>-->
    <th>退换状态</th>
    <th><?php echo self::$_var['lang']['back_username']; ?></th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  <tr>
  <?php $_from = self::$_var['back_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('dkey', 'back');if (count($_from)):
    foreach ($_from AS self::$_var['dkey'] => self::$_var['back']):
?>
  <tr>
  <td><input type="checkbox" name="back_id[]" value="<?php echo self::$_var['back']['back_id']; ?>" /><?php echo self::$_var['back']['back_id']; ?></td>
    <td><?php echo self::$_var['back']['order_sn']; ?></td>
	<?php if (self::$_var['supp_list']): ?>
	<td><?php echo htmlspecialchars(self::$_var['back']['supplier_name']); ?></td>
	<?php endif; ?>
    <td>
        <?php $_from = self::$_var['back']['sn_img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('k', 'sn');if (count($_from)):
    foreach ($_from AS self::$_var['k'] => self::$_var['sn']):
?>
        <?php echo self::$_var['sn']['goods_sn']; ?><br>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </td>
    <td>
        <?php $_from = self::$_var['back']['sn_img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('k1', 'img');if (count($_from)):
    foreach ($_from AS self::$_var['k1'] => self::$_var['img']):
?>
        <img src="<?php echo self::$_var['img']['original_img']; ?>"  width="50px" height="50px" alt="">
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </td>
	<td >
	<?php $_from = self::$_var['back']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods_info');if (count($_from)):
    foreach ($_from AS self::$_var['goods_info']):
?>
	[ ID：<?php echo self::$_var['goods_info']['goods_id']; ?> ] &nbsp; &nbsp; 
	<a href="https://www.taoyumall.com/goods.php?id=<?php echo self::$_var['goods_info']['goods_id']; ?>" target="_blank"><?php echo self::$_var['goods_info']['goods_name']; ?></a><br />
	<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
	</td>
    <td align="center"  nowrap="nowrap"><?php echo self::$_var['back']['add_time']; ?></td>
	<td><?php echo self::$_var['back']['refund_money_1']; ?></td>
	<td><?php echo self::$_var['back']['refund_money_2']; ?></td>
    <td align="right" > <?php echo htmlspecialchars(self::$_var['back']['consignee']); ?> <br><?php echo self::$_var['back']['address']; ?></td>
    <!--<td align="center" valign="top" nowrap="nowrap"><?php echo self::$_var['back']['update_time']; ?></td>	-->
    <td align="center"  nowrap="nowrap"><?php echo self::$_var['back']['status_back_val']; ?></td>
    <td align="center"  nowrap="nowrap"><?php echo self::$_var['back']['consignee']; ?></td>
    <td align="center"   nowrap="nowrap">
     <a href="index.php?act=back&op=back_info&back_id=<?php echo self::$_var['back']['back_id']; ?>"><?php echo self::$_var['lang']['detail']; ?></a>
     <a onclick="{if(confirm('<?php echo self::$_var['lang']['confirm_delete']; ?>')){return true;}return false;}" href="index.php?act=back&op=remove_back&back_id=<?php echo self::$_var['back']['back_id']; ?>"><?php echo self::$_var['lang']['remove']; ?></a>
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

<?php if (self::$_var['full_page']): ?>
  </div>
  <div>
    <input name="remove_back" type="submit" id="btnSubmit3" value="<?php echo self::$_var['lang']['remove']; ?>" class="button" disabled="true" onclick="{if(confirm('<?php echo self::$_var['lang']['confirm_delete']; ?>')){return true;}return false;}" />
  </div>
</form>
<script language="JavaScript">
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.act = "back";
listTable.query = "back_query";
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


    onload = function()
    {
        // 开始检查订单
        startCheckOrder();
                
        //
        listTable.act = "back";
        listTable.query = "back_query";;
    }

    /**
     * 搜索订单
     */
    function searchOrder()
    {
		listTable.filter['sort_index'] = -1;
        listTable.filter['order_sn'] = Utils.trim(document.forms['searchForm'].elements['order_sn'].value);
        listTable.filter['consignee'] = Utils.trim(document.forms['searchForm'].elements['consignee'].value);
        //listTable.filter['delivery_sn'] = document.forms['searchForm'].elements['delivery_sn'].value;
		<?php if (self::$_var['supp_list']): ?>
		listTable.filter['suppid'] = document.forms['searchForm'].elements['suppid'].value;
		<?php endif; ?>
		listTable.filter['order_type'] = document.forms['searchForm'].elements['order_type'].value;
		listTable.filter['back_type'] = document.forms['searchForm'].elements['back_type'].value;
		
		
        listTable.filter['page'] = 1;
        listTable.act = "back";
        listTable.query = "back_query";
        listTable.loadList();
    }
	
	 /**
     * 按分类显示订单
     */
    function sortOrder(index)
    {
		listTable.filter['sort_index'] = index;
        listTable.filter['order_sn'] = Utils.trim(document.forms['searchForm'].elements['order_sn'].value);
        listTable.filter['consignee'] = Utils.trim(document.forms['searchForm'].elements['consignee'].value);
        //listTable.filter['delivery_sn'] = document.forms['searchForm'].elements['delivery_sn'].value;
		<?php if (self::$_var['supp_list']): ?>
		listTable.filter['suppid'] = document.forms['searchForm'].elements['suppid'].value;
		<?php endif; ?>
		listTable.filter['order_type'] = document.forms['searchForm'].elements['order_type'].value;
		listTable.filter['back_type'] = document.forms['searchForm'].elements['back_type'].value;
		
		
        listTable.filter['page'] = 1;
        listTable.act = "back";
        listTable.query = "back_query";
        listTable.loadList();
		
    }

    function check()
    {
      var snArray = new Array();
      var eles = document.forms['listForm'].elements;
      for (var i=0; i<eles.length; i++)
      {
        if (eles[i].tagName == 'INPUT' && eles[i].type == 'checkbox' && eles[i].checked && eles[i].value != 'on')
        {
          snArray.push(eles[i].value);
        }
      }
      if (snArray.length == 0)
      {
        return false;
      }
      else
      {
        eles['order_id'].value = snArray.toString();
        return true;
      }
    }
</script>


<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>