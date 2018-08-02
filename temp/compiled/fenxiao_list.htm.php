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
          <input name="start_time" type="text" id="start_time" size="22" value='<?php echo self::$_var['time_rand']['start_date']; ?>'>
          <input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('start_time', '%Y-%m-%d', false, false, 'selbtn1');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button" /></td>
      </tr>
      <tr>
        <td class="label">结束日期
        <td>
          <input name="end_time" type="text" id="end_time" size="22" value='<?php echo self::$_var['time_rand']['end_date']; ?>' />
          <input name="selbtn2" type="button" id="selbtn2" onclick="return showCalendar('end_time', '%Y-%m-%d', false, false, 'selbtn2');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button" /></td>
      </tr>
	<?php echo self::$_var['lang']['usernames']; ?>&nbsp;
	<input type="text" name="userming" size="10" style="min-width: 150px;" />
    <input type="submit" name="submit" value="<?php echo self::$_var['lang']['query']; ?>" class="button" />
  </form>
</div>

<form method="POST" action="" name="listForm">
<div class="list-div" id="listDiv">
<?php endif; ?>
<?php if (self::$_var['goods_order_count']): ?>
  <table width="100%" cellspacing="1" cellpadding="3">
	<th><?php echo self::$_var['lang']['overall_sum']; ?> : <?php echo self::$_var['goods_order_count']['zongpicss']; ?></th>
    <th><?php echo self::$_var['lang']['fandian_buy_all']; ?> : <?php echo self::$_var['goods_order_count']['sumprice']; ?></th>
    <th><?php echo self::$_var['lang']['fandian_buy_count']; ?> : <?php echo self::$_var['goods_order_count']['yifanmai']; ?></th>
    <th><?php echo self::$_var['lang']['fandian_invite_all']; ?> : <?php echo self::$_var['goods_order_count']['inviteprice']; ?></th>
    <th><?php echo self::$_var['lang']['fandian_invite_count']; ?> : <?php echo self::$_var['goods_order_count']['yifanyao']; ?></th>
  </table>
<?php endif; ?>
  <table width="100%" cellspacing="1" cellpadding="3">
     <tr>
      <th><?php echo self::$_var['lang']['payuser']; ?></th>
	  <th><?php echo self::$_var['lang']['overall_sum']; ?></th>
	  <th><?php echo self::$_var['lang']['order_sum']; ?></th>
      <th><?php echo self::$_var['lang']['zong_paysum']; ?></th>
      <th><?php echo self::$_var['lang']['zong_spaysum']; ?></th>
      <th><?php echo self::$_var['lang']['posername']; ?></th>
      <th><?php echo self::$_var['lang']['zong_yqrsum']; ?></th>
	  <th><?php echo self::$_var['lang']['zong_syqrsum']; ?></th>
    <th>时间</th>
      <th><?php echo self::$_var['lang']['caozuo']; ?></th>
    </tr>
  <?php $_from = self::$_var['goods_order_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');self::$_foreach['val'] = array('total' => count($_from), 'iteration' => 0);
if (self::$_foreach['val']['total'] > 0):
    foreach ($_from AS self::$_var['list']):
        self::$_foreach['val']['iteration']++;
?>
    <tr align="center">
      <td>
	  <span style="margin-bottom: 2px; line-height: 14px; display: block;"><a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['list']['user_id']; ?>" target="_blank"><?php echo self::$_var['list']['username']; ?></a></span>
	  <span style="border: 1px #6DD26A solid; background-color: #6DD26A; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"><?php echo self::$_var['list']['alias']; ?></span>
	  <span style="border: 1px #f40d0d solid; background-color: #f40d0d; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"><?php echo self::$_var['list']['level_name']; ?></span>
	  </td>
	  <td><?php echo self::$_var['list']['zongpic']; ?>/元</td>
	  <td align="center"><?php echo self::$_var['list']['nums']; ?>/个</td>
      <td align="center"><?php echo self::$_var['list']['fanzong']; ?>/元</td>
      <td><?php echo self::$_var['list']['yjfan_dian']; ?>/元</td>
      <td align="center">
	  <span style="margin-bottom: 2px; line-height: 14px; display: block;"><a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['list']['parent_id']; ?>" target="_blank"><?php echo self::$_var['list']['parentname']; ?></a></span>
	  <span style="border: 1px #6DD26A solid; background-color: #6DD26A; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"><?php echo self::$_var['list']['parentname2']; ?></span>
	  </td>
      <td align="center"><?php echo self::$_var['list']['poserfan']; ?>/元</td>
	  <td align="center"><?php echo self::$_var['list']['yjyqfan_dian']; ?>/元</td>
	  <td align="center"><?php echo self::$_var['list']['add_time']; ?></td>
      <td align="center">
	  <?php if (self::$_var['list']['yjtypewan'] == 0): ?>
	    <a href="javascript:fandiancz(<?php echo self::$_var['list']['user_id']; ?>,'<?php echo self::$_var['list']['alias']; ?>')" title="<?php echo self::$_var['lang']['fandiancz']; ?>" >
			<span style="border: 1px #f40d0d solid; background-color: #f40d0d; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;"><?php echo self::$_var['lang']['fandiangcz']; ?></span>
		</a>
	  <?php elseif (self::$_var['list']['yjtypewan'] == 2): ?>
	  <?php else: ?>
		<span style="border: 1px #5fb45c solid; background-color: #5fb45c; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">已完成购买返点</span>	
	  <?php endif; ?>
	  <?php if (self::$_var['list']['yqtypewan'] == 0): ?>
	    <a href="javascript:yqfandiancz(<?php echo self::$_var['list']['user_id']; ?>,'<?php echo self::$_var['list']['parentname2']; ?>')" title="<?php echo self::$_var['lang']['fandianycz']; ?>" >
			<span style="border: 1px #0d49f4 solid; background-color: #0d49f4; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;"><?php echo self::$_var['lang']['fandianycz']; ?></span>
		</a>
	  <?php elseif (self::$_var['list']['yqtypewan'] == 2): ?>
	  <?php else: ?>
		<span style="border: 1px #45c140 solid; background-color: #45c140; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;">已完成邀请买返点</span>	
	  <?php endif; ?>
		<a href="javascript:info(<?php echo self::$_var['list']['user_id']; ?>)" title="<?php echo self::$_var['lang']['xiangqing']; ?>" >
			<span style="border: 1px #e77c2d solid; background-color: #e77c2d; padding: 4px 2px 4px 2px; color: #f1f1f1; display: inline; border-radius: 2px;"><?php echo self::$_var['lang']['xiangqing']; ?></span>
		</a>
		</td>
    </tr>
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
  <script type="Text/Javascript" language="JavaScript">
    

  </script>
<?php if (self::$_var['full_page']): ?>
</div>
</form>

<script type="Text/Javascript" language="JavaScript">
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.query = 'query';
listTable.act = 'fenxiao';
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>



function info(userid){
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
    
    
  location.href = "index.php?act=fenxiao&op=info&user_id="+userid+"&start_date="+strat+"&end_date="+end_date;

}

function fandiancz(userid,username){
	 var frm =  document.forms['TimeInterval'];
   if(frm.elements['start_time'].value){
     var stt = frm.elements['start_time'].value +' 00:00:00';
     var strat = gettimewss(stt)-(8*3600);
  }else{
     var strat = '';
  } 
  if(frm.elements['end_time'].value){
     var end = frm.elements['end_time'].value + ' 23:59:59';
     var end_date = gettimewss(end)-(8*3600);
  }else{
     var end_date = '';
  }    
	var mgas = '你确定要操作用户为：'+username+':'+stt+'--'+end+'期间购买返点和邀请人返点吗，操作后无法在恢复，请谨慎操作。'
	if(window.confirm(mgas)){
		 location.href ="index.php?act=fenxiao&op=fandiancz&user_id="+userid+"&start_date="+strat+"&end_date="+end_date;
	}
}

function yqfandiancz(userid,username){
	 var frm =  document.forms['TimeInterval'];
   if(frm.elements['start_time'].value){
     var stt = frm.elements['start_time'].value +' 00:00:00';
     var strat = gettimewss(stt)-(8*3600);
  }else{
     var strat = '';
  } 
  if(frm.elements['end_time'].value){
     var end = frm.elements['end_time'].value + ' 23:59:59';
     var end_date = gettimewss(end)-(8*3600);
  }else{
     var end_date = '';
  }
	 var mgas = '你确定要操作用户为：'+username+':'+stt+'--'+end+'期间邀请人返点吗，操作后无法在恢复，请谨慎操作。'
	if(window.confirm(mgas)){
		 window.open("index.php?act=fenxiao&op=yqfandiancz&user_id="+userid+"&start_date="+strat+"&end_date="+end_date);
	}
}

function getList()
{
    var frm =  document.forms['TimeInterval'];
    listTable.filter['start_date'] = gettimewss(frm.elements['start_time'].value +' 00:00:00')-(8*3600);
    listTable.filter['end_date'] = gettimewss(frm.elements['end_time'].value + ' 23:59:59')-(8*3600);
	  listTable.filter['page'] = 1;
    listTable.filter['userming'] = frm.elements['userming'].value;
	
    listTable.loadList();
    getDownUrl();
}

function gettimewss(stringTime)
{

	var timestamp2 = Date.parse(new Date(stringTime));
	 rtimestamp2 = timestamp2 / 1000;
	return rtimestamp2;
}

function getDownUrl()
{
  var aTags = document.getElementsByTagName('A');
   
  for (var i = 0; i < aTags.length; i++)
  { 

    if (aTags[i].href.indexOf('download') >= 0)
    {
      if (listTable.filter['start_date'] == "")
      {
        var frm =  document.forms['TimeInterval'];
        listTable.filter['start_date'] = gettimewss(frm.elements['start_time'].value +' 00:00:00')-(8*3600);
    listTable.filter['end_date'] = gettimewss(frm.elements['end_time'].value + ' 23:59:59')-(8*3600);
		listTable.filter['userming'] = frm.elements['userming'].value;
		
      }
      aTags[i].href = "index.php?act=fenxiao&op=download&start_date=" + listTable.filter['start_date'] + "&end_date=" + listTable.filter['end_date']+ "&userming=" + listTable.filter['userming'];
    }
  }
}

</script>

<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>