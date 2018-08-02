
<?php if (self::$_var['act'] == 'invoice_list'): ?>
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script language="JavaScript">
<!--

// 这里把JS用到的所有语言都赋值到这里

<?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";

<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

//-->
</script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />


<div class="form-div">
<form action="javascript:search_invoice()" name="search_form">

</form>
</div>

<div class="list-div" id="listDiv">
<?php endif; ?>
<form method="post" action="index.php?act=order&op=invoice_op" name="listForm" onsubmit="return check()">
<input name="order_id" type="hidden" value="" />
<table cellpadding="3" cellspacing="1">
  <tr>
    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" />编号</th>
    <th><a href="javascript:listTable.sort('inv_type', 'DESC'); "><?php echo self::$_var['lang']['inv_type']; ?></a></th>
    <th><a href="javascript:listTable.sort('order_sn', 'DESC'); "><?php echo self::$_var['lang']['order_sn']; ?></a></th>
    <th><a href="javascript:listTable.sort('add_time', 'DESC'); "><?php echo self::$_var['lang']['order_time']; ?></a></th>
    <th><a href="javascript:listTable.sort('user_name', 'DESC'); ">会员名称</a></th>
    <th><a href="javascript:listTable.sort('inv_status', 'DESC'); "><?php echo self::$_var['lang']['inv_status']; ?></a></th>
    <th><a href="javascript:listTable.sort('inv_content', 'DESC'); "><?php echo self::$_var['lang']['inv_content']; ?></a></th>
    <th><a href="javascript:listTable.sort('inv_money', 'DESC'); "><?php echo self::$_var['lang']['inv_money']; ?></a></th>
  <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['order_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('okey', 'order');if (count($_from)):
    foreach ($_from AS self::$_var['okey'] => self::$_var['order']):
?>
  <tr>
    <td align='center'><input type="checkbox" name="checkboxes" value="<?php echo self::$_var['order']['order_sn']; ?>" /><?php echo self::$_var['order']['order_id']; ?></td>
    <td align='center'><?php echo self::$_var['lang'][self::$_var['order']['inv_type']]; ?></td>
    <td align='center' valign="top" nowrap="nowrap"><a href="index.php?act=order&op=info&order_id=<?php echo self::$_var['order']['order_id']; ?>" id="order_<?php echo self::$_var['okey']; ?>"><?php echo self::$_var['order']['order_sn']; ?><?php if (self::$_var['order']['extension_code'] == "group_buy"): ?><br /><div align="center"><?php echo self::$_var['lang']['group_buy']; ?></div><?php elseif (self::$_var['order']['extension_code'] == "exchange_goods"): ?><br /><div align="center"><?php echo self::$_var['lang']['exchange_goods']; ?></div><?php endif; ?></a></td>
    <td align='center'><?php echo self::$_var['order']['formatted_add_time']; ?></td>
    <td align='center'><?php echo htmlspecialchars(self::$_var['order']['buyer']); ?></td>
    <td align='center'><?php echo self::$_var['lang'][self::$_var['order']['inv_status']]; ?></td>
    <td align='center'><?php echo self::$_var['order']['inv_content']; ?><?php echo self::$_var['lang']['invoice_type']; ?></td>
    <td align='center'><?php echo self::$_var['order']['formatted_inv_money']; ?></td>
    <td align='center'>
      <a href="?act=edit&order_id=<?php echo self::$_var['order']['order_id']; ?>&step=invoice&step_detail=info" ><?php echo self::$_var['lang']['detail']; ?></a>
      <a href="javascript:listTable.remove(<?php echo self::$_var['order']['order_sn']; ?>, remove_invoice_confirm, 'remove_invoice');" ><?php echo self::$_var['lang']['op_remove']; ?></a>
	 <?php if (self::$_var['order']['rebate_ispay'] == 1 && self::$_var['is_rebate']): ?>
	 <a href="index.php?act=order&op=rebate&order_id=<?php echo self::$_var['order']['order_id']; ?>&supp=<?php echo empty($_REQUEST['supp']) ? '0' : $_REQUEST['supp']; ?>">计算佣金</a>
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
<table>
  <tr>
    <td>
        <input id='btnSubmit' class='button' type='button' disabled="true" value='<?php echo self::$_var['lang']['provide_invoice']; ?>'  onclick="provide_multi_invoice()"  />
        <input id='btnSubmit1' class='button' type='button'disabled="true" value='<?php echo self::$_var['lang']['op_remove']; ?>' onclick="remove_multi_invoice()" />
        <input id='btnSubmit2' class='button' name='export' type='submit' disabled="true" value='<?php echo self::$_var['lang']['export_to_excel']; ?>' onclick="this.form.target = '_blank'" />
      </td>
  </tr>
</table>
</div>
</form>
<?php if (self::$_var['full_page']): ?>
<script language="JavaScript">
  listTable.url += '&act_detail=invoice_query';
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = "lists_query";
  listTable.act = "order";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  function provide_multi_invoice()
  {
    if(check())
    {
      listTable.args = 'act=provide_invoice&order_sns='+document.forms['listForm']['order_id'].value+listTable.compileFilter();
      Ajax.call(listTable.url,listTable.args,listTable.listCallback,'GET','JSON');
    }
  }
  function remove_multi_invoice()
  {
    if(check())
    {
      listTable.remove(document.forms['listForm']['order_id'].value, remove_invoice_confirm, 'remove_invoice');
    }
  }
  function export_all_invoice()
  {
    window.open('index.php?act=order&op=export_all_invoice');
  }
  function search_invoice()
  {
      listTable.filter['add_time'] = Utils.trim(document.forms['search_form'].elements['add_time'].value);
      listTable.filter['start_time'] = "";
      listTable.filter['end_time'] = "";
      listTable.filter['inv_status'] = Utils.trim(document.forms['search_form'].elements['inv_status'].value);
      listTable.filter['user_name'] = Utils.trim(document.forms['search_form'].elements['user_name'].value);
      listTable.filter['order_sn'] = Utils.trim(document.forms['search_form'].elements['order_sn'].value);
      listTable.filter['vat_inv_consignee_name'] = Utils.trim(document.forms['search_form'].elements['vat_inv_consignee_name'].value);
      listTable.filter['vat_inv_consignee_phone'] = Utils.trim(document.forms['search_form'].elements['vat_inv_consignee_phone'].value);
	  listTable.filter['page'] = 1;
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

  listTable.listCallback = function(result, txt)
  {
      if (result.error > 0)
      {
          alert(result.message);
      }
      else
      {
          try
          {
              document.getElementById('listDiv').innerHTML = result.content;
              if (typeof result.filter == "object")
              {
                  listTable.filter = result.filter;
              }
              listTable.pageCount = result.page_count;
          }
          catch(e)
          {
              alert(e.message);
          }
      }
  }
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
<?php else: ?>

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>

<div class="form-div">
  <form action="javascript:searchOrder()" name="searchForm">
    <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
	<img  style="display:none;" id="auto_cron_exec" src="" />
	<img  style="display:none;" id="auto_cancel_exec" src="" />
    <?php echo self::$_var['lang']['order_sn']; ?><input name="order_sn" type="text" id="order_sn" size="15">
    <?php echo htmlspecialchars(self::$_var['lang']['consignee']); ?><input name="consignee" type="text" id="consignee" size="15">
    <?php echo self::$_var['lang']['all_status']; ?>
    <select name="status" id="status">
      <option value="-1" selected="selected" id='please'>请选择...</option>
      <?php echo self::html_options(array('options'=>self::$_var['status_list'])); ?>

    </select>
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
    	
    支付渠道
    <select name="payment">
        <option value="0" selected="selected">请选择...</option>
        <?php $_from = self::$_var['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'payment');if (count($_from)):
    foreach ($_from AS self::$_var['payment']):
?>
        <option value="<?php echo self::$_var['payment']['pay_id']; ?>"><?php echo self::$_var['payment']['pay_name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </select>
	
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
   
  </form>
</div>


<form method="post" action="index.php?act=order&op=operate" name="listForm" onsubmit="return check()">
  <div class="list-div" id="listDiv">
<?php endif; ?>

<table cellpadding="3" cellspacing="1">
  <tr>
    <th>
      <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" /><a href="javascript:listTable.sort('order_sn', 'DESC'); "><?php echo self::$_var['lang']['order_sn']; ?></a><?php echo self::$_var['sort_order_sn']; ?>
    </th>
	<?php if (self::$_var['supp_list']): ?>
	<th><a href="javascript:listTable.sort('supplier_name', 'DESC'); ">供货商家</a><?php echo self::$_var['sort_supplier_name']; ?></th>
	<?php endif; ?>
      <th>商品sn</th>
      <th>商品图片</th>
    
    <th><?php echo self::$_var['lang']['order_type']; ?></th>
	
    <th><a href="javascript:listTable.sort('add_time', 'DESC'); "><?php echo self::$_var['lang']['order_time']; ?></a><?php echo self::$_var['sort_order_time']; ?></th>
    
    <th><?php echo self::$_var['lang']['order_superior']; ?></th>
    
    <th><a href="javascript:listTable.sort('consignee', 'DESC'); "><?php echo self::$_var['lang']['consignee']; ?></a><?php echo self::$_var['sort_consignee']; ?></th>
    <th><a href="javascript:listTable.sort('total_fee', 'DESC'); "><?php echo self::$_var['lang']['total_fee']; ?></a><?php echo self::$_var['sort_total_fee']; ?></th>
    <th><a href="javascript:listTable.sort('order_amount', 'DESC'); "><?php echo self::$_var['lang']['order_amount']; ?></a><?php echo self::$_var['sort_order_amount']; ?></th>
	<th>订单来源</th>
    <th><?php echo self::$_var['lang']['all_status']; ?></th>
	<th>付款渠道</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>

  </tr>
  <?php $_from = self::$_var['order_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('okey', 'order');if (count($_from)):
    foreach ($_from AS self::$_var['okey'] => self::$_var['order']):
?>
  <tr class="
  	<?php if (self::$_var['order']['can_remove']): ?>tr_canceled<?php endif; ?> 
    <?php if (self::$_var['order']['pay_status'] == 0 && self::$_var['order']['order_status'] != 2): ?>tr_nopay<?php endif; ?> 
    <?php if (self::$_var['order']['shipping_status'] == 2): ?>tr_receipt<?php endif; ?>
    <?php if (self::$_var['order']['order_status'] != 2 && self::$_var['order']['pay_status'] == 2 && self::$_var['order']['shipping_status'] == 0): ?>tr_nosend<?php endif; ?>
    <?php if (self::$_var['order']['tuihuan']): ?>tr_back<?php endif; ?>">
    <td valign="top" nowrap="nowrap"><input type="checkbox" name="checkboxes" value="<?php echo self::$_var['order']['order_sn']; ?>" /><a href="index.php?act=order&op=info&order_id=<?php echo self::$_var['order']['order_id']; ?>" id="order_<?php echo self::$_var['okey']; ?>"><?php echo self::$_var['order']['order_sn']; ?><?php if (self::$_var['order']['extension_code'] == "group_buy"): ?><br /><div align="center"><?php echo self::$_var['lang']['group_buy']; ?></div><?php elseif (self::$_var['order']['extension_code'] == "exchange_goods"): ?><br /><div align="center"><?php echo self::$_var['lang']['exchange_goods']; ?></div><?php endif; ?></a></td>
	<?php if (self::$_var['supp_list']): ?>
	<td><?php echo htmlspecialchars(self::$_var['order']['supplier_name']); ?></td>
	<?php endif; ?>
    <td>
        <?php $_from = self::$_var['order']['sn_img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('k', 'sn');if (count($_from)):
    foreach ($_from AS self::$_var['k'] => self::$_var['sn']):
?>
        <?php echo self::$_var['sn']['goods_sn']; ?><br>
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </td>
    <td>
        <?php $_from = self::$_var['order']['sn_img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('k1', 'img');if (count($_from)):
    foreach ($_from AS self::$_var['k1'] => self::$_var['img']):
?>
        <img src="<?php echo self::$_var['img']['original_img']; ?>" width="50px" height="50px" alt="">
        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    </td>
    
	<td><?php if (self::$_var['order']['is_pickup'] == 1): ?><?php echo self::$_var['lang']['pickup_order']; ?><?php else: ?><?php echo self::$_var['lang']['normal_order']; ?><?php endif; ?></td>
	
    <td>
	<?php if (self::$_var['order']['real_name']): ?>
		昵称:<?php echo htmlspecialchars(self::$_var['order']['real_name']); ?>
	<?php else: ?>
		<?php if (self::$_var['order']['alias']): ?>
			昵称:<?php echo htmlspecialchars(self::$_var['order']['alias']); ?>
		<?php else: ?>
			无昵称
		<?php endif; ?>
	<?php endif; ?>
	<br /><?php echo self::$_var['order']['short_order_time']; ?></td>
	
    <td><?php echo self::$_var['order']['parent_id']; ?></td>
    

    
    <td align="left" valign="top"><a href="mailto:<?php echo self::$_var['order']['email']; ?>"> <?php echo htmlspecialchars(self::$_var['order']['consignee']); ?></a><?php if (self::$_var['order']['tel']): ?> [TEL: <?php echo htmlspecialchars(self::$_var['order']['tel']); ?>]<?php endif; ?> <br /><?php echo htmlspecialchars(self::$_var['order']['address']); ?></td>
    <td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['formated_total_fee']; ?></td>
    <td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['formated_order_amount']; ?></td>
	<td align="right" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['froms']; ?></td>
    <td align="center" valign="top" nowrap="nowrap"><?php echo self::$_var['lang']['os'][self::$_var['order']['order_status']]; ?>,<?php echo self::$_var['lang']['ps'][self::$_var['order']['pay_status']]; ?>,<?php echo self::$_var['lang']['ss'][self::$_var['order']['shipping_status']]; ?></td>
	<td align="center" valign="top" nowrap="nowrap"><?php echo self::$_var['order']['pay_name']; ?></td>
    <td align="center" valign="top"  nowrap="nowrap">
     <a href="index.php?act=order&op=info&order_id=<?php echo self::$_var['order']['order_id']; ?>"><?php echo self::$_var['lang']['detail']; ?></a>
     <?php if (self::$_var['order']['can_remove']): ?>
     <br /><a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['order']['order_id']; ?>, remove_confirm, 'remove')"><?php echo self::$_var['lang']['remove']; ?></a>
     <?php endif; ?>
     <?php if (self::$_var['order']['tuihuan']): ?>
     <br /><span style="color:#F00"><?php echo self::$_var['order']['tuihuan']['back_type']; ?>,<?php echo self::$_var['order']['tuihuan']['status_back']; ?></span>
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
  <div>
    <input name="confirm" type="submit" id="btnSubmit" value="<?php echo self::$_var['lang']['op_confirm']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="invalid" type="submit" id="btnSubmit1" value="<?php echo self::$_var['lang']['op_invalid']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="cancel" type="submit" id="btnSubmit2" value="<?php echo self::$_var['lang']['op_cancel']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="remove" type="submit" id="btnSubmit3" value="<?php echo self::$_var['lang']['remove']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="print" type="submit" id="btnSubmit4" value="<?php echo self::$_var['lang']['print_order']; ?>" class="button" disabled="true" onclick="this.form.target = '_blank'" />
    <input name="batch" type="hidden" value="1" />
    <input name="order_id" type="hidden" value="" />
  </div>
</form>
<script language="JavaScript">
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.query = "lists_query";
listTable.act = "order";
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


    window.onload = function()
    { 
        document.getElementById('please').selected='selected';
    }

    /**
     * 搜索订单
     */
    function searchOrder()
    {
        listTable.filter['order_sn'] = Utils.trim(document.forms['searchForm'].elements['order_sn'].value);
        listTable.filter['consignee'] = Utils.trim(document.forms['searchForm'].elements['consignee'].value);
        listTable.filter['composite_status'] = document.forms['searchForm'].elements['status'].value;
        //alert(listTable.filter['composite_status']);
		<?php if (self::$_var['supp_list']): ?>
		listTable.filter['suppid'] = document.forms['searchForm'].elements['suppid'].value;
		<?php endif; ?>
		
		listTable.filter['payment'] = document.forms['searchForm'].elements['payment'].value;
		
        listTable.filter['page'] = 1;
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
    /**
     * 显示订单商品及缩图
     */
    /**
     * 建立订单商品显示层
     *
     * @return void
     */
    function create_goods_layer(id)
    {
        if (!Utils.$(id))
        {
            var n_div = document.createElement('DIV');
            n_div.id = id;
            n_div.className = 'order-goods';
            document.body.appendChild(n_div);
            Utils.$(id).onmouseover = function()
            {
                window.clearTimeout(window.timer);
            }
            Utils.$(id).onmouseout = function()
            {
                hide_order_goods(id);
            }
        }
        else
        {
            Utils.$(id).style.display = '';
        }
    }


    /**
     * 处理订单商品的Callback
     *
     * @return void
     */
    function response_goods_info(result)
    {
        if (result.error > 0)
        {
            alert(result.message);
            hide_order_goods(show_goods_layer);
            return;
        }
        if (typeof(goods_hash_table[result.content[0].order_id]) == 'undefined')
        {
            goods_hash_table[result.content[0].order_id] = result;
        }
        Utils.$(show_goods_layer).innerHTML = result.content[0].str;
    }
</script>


<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>

<?php endif; ?>
