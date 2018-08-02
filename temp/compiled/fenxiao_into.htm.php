<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>
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
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<style>
.colors {
	color:#FF0000;
}
.colors a:link {
    color: #FF0000;
}
</style>
<div class="form-div">
  <form name="TimeInterval"  action="javascript:getList()" style="margin:0px">
    <tr>
        <td class="label">开始日期
        <td>
          <input name="start_time" type="text" id="start_time" size="22" value='<?php echo self::$_var['start_date']; ?>'>
          <input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('start_time', '%Y-%m-%d', false, false, 'selbtn1');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button" /></td>
      </tr>
      <tr>
        <td class="label">结束日期
        <td>
          <input name="end_time" type="text" id="end_time" size="22" value='<?php echo self::$_var['end_date']; ?>' />
          <input name="selbtn2" type="button" id="selbtn2" onclick="return showCalendar('end_time', '%Y-%m-%d', false, false, 'selbtn2');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button" /></td>
      </tr>
    <input type="hidden" name="userid" value="<?php echo self::$_var['userid']; ?>"/>
	<input type="submit" name="submit" value="<?php echo self::$_var['lang']['query']; ?>" class="button" />

  </form>
	 <span style="color: #f33e3e;">备注 : <?php echo self::$_var['userinto']['youhuio']; ?></span><br><span style="color: #f33e3e;">管理员备注 : 修改返点点击确定以后会有对话框提示确定返点或者不返点。点击取消以后这个订单就不能在返点操作！！！！</span>
</div>

<form method="POST" action="" name="listForm">
<div class="list-div" id="listDiv">
<?php endif; ?>
<?php if (self::$_var['userinto']): ?>
  <table width="100%" cellspacing="1" cellpadding="3">
    <th><?php echo self::$_var['lang']['payuser']; ?> : <?php echo self::$_var['userinto']['username']; ?></th>
    <th><?php echo self::$_var['lang']['overall_sum']; ?> : <?php echo self::$_var['userinto']['zongpic']; ?></th>
    <th><?php echo self::$_var['lang']['zong_paysum']; ?> : <?php echo self::$_var['userinto']['zongfanpic']; ?></th>
    <th><?php echo self::$_var['lang']['zong_yqrsum']; ?> : <?php echo self::$_var['userinto']['zongyqfanpic']; ?></th>
  </table>
<?php endif; ?>
  <table width="100%" cellspacing="1" cellpadding="3">
     <tr>
      <th><?php echo self::$_var['lang']['payuser']; ?></th>
	  <th><?php echo self::$_var['lang']['goods_sn']; ?></th>
	  <th><?php echo self::$_var['lang']['order_sn']; ?></th>
	  <th><?php echo self::$_var['lang']['goods_imgs']; ?></th>
      <th><?php echo self::$_var['lang']['goodspic']; ?></th>
      <th><?php echo self::$_var['lang']['paynum']; ?></th>
	  <th><?php echo self::$_var['lang']['paypic']; ?></th>
      <th><?php echo self::$_var['lang']['posername']; ?></th>
      <th><?php echo self::$_var['lang']['payfan']; ?></th>
	  <th><?php echo self::$_var['lang']['poserfan']; ?></th>
	  <th><?php echo self::$_var['lang']['paytime']; ?></th>
	  <th><?php echo self::$_var['lang']['addtime']; ?></th>
	  <th><?php echo self::$_var['lang']['order_circs']; ?></th>
      <th><?php echo self::$_var['lang']['caozuo']; ?></th>
    </tr>
  <?php $_from = self::$_var['goods_order_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');self::$_foreach['val'] = array('total' => count($_from), 'iteration' => 0);
if (self::$_foreach['val']['total'] > 0):
    foreach ($_from AS self::$_var['list']):
        self::$_foreach['val']['iteration']++;
?>
<?php if (self::$_var['list']['goods_sn']): ?>
    <tr align="center">
      <td>
		 <span style="margin-bottom: 2px; line-height: 14px; display: block;"><a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['list']['user_id']; ?>" target="_blank"><?php echo self::$_var['userinto']['username']; ?></a></span>
		 <span style="border: 1px #6DD26A solid; background-color: #6DD26A; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"><?php echo self::$_var['userinto']['alias']; ?></span>
	     <span style="border: 1px #f40d0d solid; background-color: #f40d0d; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"><?php echo self::$_var['userinto']['level_name']; ?></span>
	  </td>
	  <td align="center"><a href="<?php echo self::$_var['list']['goods_url']; ?>" target="_blank"><?php echo self::$_var['list']['goods_sn']; ?></a></td>
	  <td align="center" ><a href="index.php?act=order&op=info&order_id=<?php echo self::$_var['list']['order_id']; ?>" target="_blank"><?php echo self::$_var['list']['order_sn']; ?></a></td>
	  
	  <td align="center"><a href="<?php echo self::$_var['list']['goods_url']; ?>" target="_blank"><img src="<?php echo self::$_var['list']['goods_image']; ?>" border="0" height="50" width="50" /></a></td>
      <td align="center"><?php echo self::$_var['list']['goods_pay_price']; ?></td>
      <td><?php echo self::$_var['list']['goods_number']; ?>/个</td>
	  
      <td align="center" ><?php echo self::$_var['list']['money_paid']; ?></td>
	  
      <td align="center">
	  <span style="margin-bottom: 2px; line-height: 14px; display: block;"><a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['list']['parent_id']; ?>" target="_blank"><?php echo self::$_var['list']['parentname']; ?></a></span>
	  <span style="border: 1px #6DD26A solid; background-color: #6DD26A; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"><?php echo self::$_var['list']['parentname2']; ?></span>
		</td>
	  
      <td align="center" ><?php echo self::$_var['list']['fanli_pic']; ?>/元</td>
	  <td align="center" ><?php echo self::$_var['list']['fandian_yq_pic']; ?>/元</td>
	  
	  <td align="center"><?php echo self::$_var['list']['pay_time']; ?></td>
	  <td align="center"><?php echo self::$_var['list']['add_time']; ?></td>
	  <td align="center"><?php echo self::$_var['list']['statustpye']; ?></td>

      <td align="center" >
	  <?php if (self::$_var['list']['xiug'] == 1): ?>
		<?php if (self::$_var['list']['fandian_pay'] == 1): ?>
			<span style="border: 1px #6DD26A solid; background-color: #6DD26A; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">已返点(购买)</span>
		<?php elseif (self::$_var['list']['fandian_pay'] == 0): ?>
			<a href="javascript:editpay(<?php echo self::$_var['list']['order_id']; ?>,<?php echo self::$_var['list']['user_id']; ?>)" title="<?php echo self::$_var['lang']['fandiancz']; ?>">
				<span style="border: 1px #f40d0d solid; background-color: #f40d0d; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">(购买)返点操作</span>
			 </a>
		<?php else: ?>
			<span style="border: 1px #36ba4a solid; background-color: #36ba4a; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">不参于(购买)返点</span>
		<?php endif; ?>
		<br>
		<br>
		<?php if (self::$_var['list']['fandian_yq'] == 1): ?>
			<span style="border: 1px #6DD26A solid; background-color: #6DD26A; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">已返点(邀请)</span>
		<?php elseif (self::$_var['list']['fandian_yq'] == 0): ?>
			<a href="javascript:edityq(<?php echo self::$_var['list']['order_id']; ?>,<?php echo self::$_var['list']['user_id']; ?>)" title="<?php echo self::$_var['lang']['fandiancz']; ?>">
				<span style="border: 1px #fe6c00 solid; background-color: #fe6c00; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">(邀请)返点操作</span>
			 </a>
		<?php else: ?>
			<span style="border: 1px #36ba4a solid; background-color: #36ba4a; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">不参于(邀请)返点</span>
		<?php endif; ?>
	  <?php else: ?>
		<span style="border: 1px #573dfa solid; background-color: #573dfa; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;"><?php echo self::$_var['list']['statustpye']; ?></span>
	  <?php endif; ?>
	</td>
	
    </tr>
	<?php endif; ?>	
  <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="13"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  </table>
  <table id="page-table" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td align="right" nowrap="true">
    <?php echo self::fetch('page.htm'); ?>
    </td>
  </tr>
  </table>
<?php if (self::$_var['full_page']): ?>
</div>
</form>

<script type="Text/Javascript" language="JavaScript">
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.query = 'info_query';
listTable.act = 'fenxiao';
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


function editpay(orderids,userid)
{	
	var frm =  document.forms['TimeInterval'];
	if(frm.elements['start_time'].value){
       var strat = gettimewss(frm.elements['start_time'].value +' 00:00:00')-(8*3600);
    }else{
       var strat = '';
    } 
    if(frm.elements['end_time'].value){
       var end_date = gettimewss(frm.elements['end_time'].value + ' 23:59:59')-(8*3600);
    }else{
       var end_date = '';
    }      	  
	var mgas = '确定购买返点,如果取消，表示这个订单不用在购买返点，看清楚在操作！！！！！'
    if(window.confirm(mgas)){
		 window.location.href="index.php?act=fenxiao&op=editpay&order_id="+orderids+"&add=1&userid="+userid+"&start_date="+strat+"&end_date="+end_date;
	}else{
		 window.location.href="index.php?act=fenxiao&op=editpay&order_id="+orderids+"&add=0&userid="+userid+"&start_date="+strat+"&end_date="+end_date;
	}
}

function edityq(orderids,userid)
{
	var frm =  document.forms['TimeInterval'];
	if(frm.elements['start_time'].value){
       var strat = gettimewss(frm.elements['start_time'].value +' 00:00:00')-(8*3600);
    }else{
       var strat = '';
    } 
    if(frm.elements['end_time'].value){
       var end_date = gettimewss(frm.elements['end_time'].value + ' 23:59:59')-(8*3600);
    }else{
       var end_date = '';
    }      	  
	var mgas = '确定邀请人返点,如果取消，表示这个订单不用在邀请人返点，看清楚在操作！！！！！'
    if(window.confirm(mgas)){
		 window.location.href="index.php?act=fenxiao&op=edityq&order_id="+orderids+"&add=1&userid="+userid+"&start_date="+strat+"&end_date="+end_date;
	}else{
		 window.location.href="index.php?act=fenxiao&op=edityq&order_id="+orderids+"&add=0&userid="+userid+"&start_date="+strat+"&end_date="+end_date;
	}
}

function getList()
{
    var frm =  document.forms['TimeInterval'];
    listTable.filter['start_date'] = gettimewss(frm.elements['start_time'].value +' 00:00:00');
    listTable.filter['end_date'] = gettimewss(frm.elements['end_time'].value + ' 23:59:59');
	listTable.filter['page'] = 1;
	listTable.query = 'info_query';
    listTable.loadList();
}

function gettimewss(stringTime)
{
	var timestamp2 = Date.parse(new Date(stringTime));
	 rtimestamp2 = timestamp2 / 1000;
	
	return rtimestamp2;
}
</script>

<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
