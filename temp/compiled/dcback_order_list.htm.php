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
    订单编号<input name="order_sn" type="text" id="order_sn" size="15">
    用户名称<input name="consignee" type="text" id="consignee" size="15">
    完成状态<select name="status_refund" id="order_type"><option value="-1">全部</option><option value="1" <?php if (self::$_var['filter']['status_refund'] == '1'): ?>selected<?php endif; ?>>已完成</option><option value="0" <?php if (self::$_var['filter']['status_refund'] == '0'): ?>selected<?php endif; ?>>未完成</option></select>
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

<form method="post" action="index.php?act=dcback&op=remove" name="listForm" onsubmit="return check()">
  <div class="list-div" id="listDiv">
<?php endif; ?>

<table cellpadding="3" cellspacing="1">
  <tr>
	<th>序号</th>
    <th>订单号</th>
	<th>产品名称</th>
	<th>产品图片</th>
    <th><a href="javascript:listTable.sort('add_time', 'DESC'); ">申请时间</a><?php echo self::$_var['sort_add_time']; ?></th>
	<th>退款金额</th>
    <th>订单信息</th>
    <th>退换状态</th>
    <th>订单申请人</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  <tr>
  <?php $_from = self::$_var['back_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('dkey', 'back');if (count($_from)):
    foreach ($_from AS self::$_var['dkey'] => self::$_var['back']):
?>
  <tr>
	<td><?php echo self::$_var['back']['back_id']; ?></td>
    <td><?php echo self::$_var['back']['order_sn']; ?></td>
	<td>[ 编号：<?php echo self::$_var['back']['goods_sn']; ?> ] &nbsp; &nbsp; <?php echo self::$_var['back']['goods_name']; ?></td>
	<td><img src="<?php echo self::$_var['back']['goods_url']; ?>" width="40" height="40" border="0"  /></td>
    <td align="center"  nowrap="nowrap"><?php echo self::$_var['back']['add_time']; ?></td>
	<td>支付金额:<?php echo self::$_var['back']['order_amount']; ?><br>代金卷:<?php echo self::$_var['back']['goods_youpic']; ?><br>应退款金额:<?php echo self::$_var['back']['refund_money']; ?></td>
    <td align="right" > <?php echo self::$_var['back']['consignee']; ?> <br><?php echo self::$_var['back']['address']; ?></td>
    <td align="center"  nowrap="nowrap"><?php echo self::$_var['back']['status_back_val']; ?></td>
    <td align="center"  nowrap="nowrap"><?php echo self::$_var['back']['user_name']; ?></td>
    <td align="center"   nowrap="nowrap">
     <a href="index.php?act=dcback&op=info&dcback_id=<?php echo self::$_var['back']['back_id']; ?>">查看</a>
     <br />
        <?php if (self::$_var['back']['look_goods']): ?>
            <span style="color:#F00">看货退货</span>
        <?php else: ?>
            <span>典藏退货</span>
        <?php endif; ?>
	<?php if (self::$_var['back']['status_back'] >= 6): ?>
      <a onclick="{if(confirm('<?php echo self::$_var['lang']['confirm_delete']; ?>')){return true;}return false;}" href="index.php?act=dcback&op=remove&back_id=<?php echo self::$_var['back']['back_id']; ?>"><?php echo self::$_var['lang']['remove']; ?></a>
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

<?php if (self::$_var['full_page']): ?>
  </div>
 
</form>
<script language="JavaScript">
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.act = "dcback";
listTable.query = "lists_query";
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


    /**
     * 搜索订单
     */
    function searchOrder()
    {
		listTable.filter['sort_index'] = -1;
        listTable.filter['order_sn'] = Utils.trim(document.forms['searchForm'].elements['order_sn'].value);
        listTable.filter['consignee'] = Utils.trim(document.forms['searchForm'].elements['consignee'].value);
		listTable.filter['status_refund'] = document.forms['searchForm'].elements['status_refund'].value;
        listTable.filter['page'] = 1;
        listTable.query = "lists_query";
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
		listTable.filter['status_refund'] = document.forms['searchForm'].elements['status_refund'].value;
        listTable.filter['page'] = 1;
        listTable.query = "lists_query";
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