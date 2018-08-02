
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
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

<table>

  <tr>

      <td>

      <form name="theForm" action="javascript:searchInfo()" >

      <?php echo self::$_var['lang']['view_admin']; ?>

      <select name="admin">

      <option value='0'><?php echo self::$_var['lang']['select_admin']; ?></option>

      <?php echo self::html_options(array('options'=>self::$_var['admin_list'],'selected'=>self::$_var['admin'])); ?>

      </select>   

     

     日期<input type="text" name="add_time1" id="add_time1" class="input_te" readonly="readonly"   /><input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('add_time1', '%Y-%m-%d %H:%M', '24', false, 'selbtn1');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>&nbsp;&nbsp;至&nbsp;&nbsp;<input type="text" name="add_time2" id="add_time2" class="input_te" readonly="readonly" /><input name="selbtn2" type="button" id="selbtn2" onclick="return showCalendar('add_time2', '%Y-%m-%d %H:%M', '24', false, 'selbtn2');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>

      <?php echo self::$_var['lang']['view_ip']; ?>

      <select name="ip">

      <option value='0'><?php echo self::$_var['lang']['select_ip']; ?></option>

      <?php echo self::html_options(array('options'=>self::$_var['ip_list'],'selected'=>self::$_var['ip'])); ?>

      </select>

      <input type="submit" value="<?php echo self::$_var['lang']['comfrom']; ?>" class="button" />

      </form>

      </td>   

  </tr>

  <tr>

      <td colspan="3">

      <form name="Form2" action="index.php?act=adminlogs&op=batch_drop" method="POST" >

      <?php echo self::$_var['lang']['drop_logs']; ?>

      <select name="log_date">

        <option value='0'><?php echo self::$_var['lang']['select_date']; ?></option>

        <option value='1'><?php echo self::$_var['lang']['week_date']; ?></option>

        <option value='2'><?php echo self::$_var['lang']['month_date']; ?></option>

        <option value='3'><?php echo self::$_var['lang']['three_month']; ?></option>

        <option value='4'><?php echo self::$_var['lang']['six_month']; ?></option>

        <option value='5'><?php echo self::$_var['lang']['a_yaer']; ?></option>

      </select>
     
      <input name="drop_type_date" type="submit" value="<?php echo self::$_var['lang']['comfrom']; ?>" class="button" />

      </form>

      </td>

    </tr>

</table>

</div>



<form method="POST" action="index.php?act=adminlogs&op=batch_drop" name="listForm">



<div class="list-div" id="listDiv">

<?php endif; ?>



<table cellpadding="3" cellspacing="1">

  <tr>

    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">

    <a href="javascript:listTable.sort('log_id'); "><?php echo self::$_var['lang']['log_id']; ?></a><?php echo self::$_var['sort_log_id']; ?></th>

    <th><a href="javascript:listTable.sort('user_id'); "><?php echo self::$_var['lang']['user_id']; ?></a><?php echo self::$_var['sort_user_id']; ?></th>

    <th><a href="javascript:listTable.sort('log_time'); "><?php echo self::$_var['lang']['log_time']; ?></a><?php echo self::$_var['sort_log_time']; ?></th>

    <th><a href="javascript:listTable.sort('ip_address'); "><?php echo self::$_var['lang']['ip_address']; ?></a><?php echo self::$_var['sort_ip_address']; ?></th>

    <th><?php echo self::$_var['lang']['log_info']; ?></th>

  </tr>

  <?php $_from = self::$_var['log_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>

  <tr>
    <td width="10%"><span><input name="checkboxes[]" type="checkbox" value="<?php echo self::$_var['list']['log_id']; ?>" /><?php echo self::$_var['list']['log_id']; ?></span></td>
    <?php if (self::$_var['list']['user_name'] == ''): ?>
      <td width="15%" class="first-cell"><span>系统</span></td>
    <?php else: ?>
      <td width="15%" class="first-cell"><span><?php echo htmlspecialchars(self::$_var['list']['user_name']); ?></span></td>
    <?php endif; ?>

    <td width="20%" align="center"><span><?php echo self::$_var['list']['log_time']; ?></span></td>

    <td width="15%" align="left"><span><?php echo self::$_var['list']['ip_address']; ?></span></td>

    <td width="40%" align="left"><span><?php echo self::$_var['list']['log_info']; ?></span></td>

  </tr>

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  <tr>

    <td colspan="2"><input name="drop_type_id" type="submit" id="btnSubmit" value="<?php echo self::$_var['lang']['drop_logs']; ?>" disabled="true" class="button" /></td>

    <td align="right" nowrap="true" colspan="10"><?php echo self::fetch('page.htm'); ?></td>

  </tr>

</table>



<?php if (self::$_var['full_page']): ?>

</div>





<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = 'logs_query';
  listTable.act = "adminlogs";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

function searchInfo(){

    listTable.filter['user_id'] = Utils.trim(document.forms['theForm'].elements['admin'].value);
    listTable.filter.add_time1 = Utils.trim(document.forms['theForm'].elements['add_time1'].value);
    listTable.filter.add_time2 = Utils.trim(document.forms['theForm'].elements['add_time2'].value);
    listTable.filter['ip'] = Utils.trim(document.forms['theForm'].elements['ip'].value);
    listTable.filter['page'] = 1;
    listTable.loadList();

}

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>

