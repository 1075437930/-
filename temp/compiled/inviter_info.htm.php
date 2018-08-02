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
    <form action="javascript:search_inviter()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        搜索邀请人 <input type="text" name="user_name" size="15" />
        <input type="submit" value="搜索" class="button" />
    </form>
</div>
<script language="JavaScript">
    function search_inviter()
    {
        listTable.filter['user_name'] = Utils.trim(document.forms['searchForm'].elements['user_name'].value);
    listTable.query = 'info_query';
        listTable.loadList();
    }

</script>
<form method="POST" action="" name="listForm">
<div class="list-div" id="listDiv">
<?php endif; ?>
  <table width="100%" cellspacing="1" cellpadding="3">
     <tr>
      <th>
        <a href="javascript:listTable.sort('user_id'); "><?php echo self::$_var['lang']['record_id']; ?></a><?php echo self::$_var['sort_user_id']; ?>
      </th>
      <th>会员名称</th>
      <th>手机号</th>
      <th>账户余额</th>
      <th>淘玉币</th>
      <th>注册日期</th>
      <th>到期时间</th>
	   </tr>
    <?php $_from = self::$_var['inviter_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');self::$_foreach['val'] = array('total' => count($_from), 'iteration' => 0);
if (self::$_foreach['val']['total'] > 0):
    foreach ($_from AS self::$_var['list']):
        self::$_foreach['val']['iteration']++;
?>
      <tr align="center">
        <td><?php echo self::$_var['list']['user_id']; ?></td>
        <td>
          <?php if (self::$_var['list']['alias']): ?>
      	  <span style="margin-bottom: 2px; line-height: 14px; display: block;"><a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['list']['user_id']; ?>" target="_blank"><?php echo self::$_var['list']['alias']; ?></a></span>
          <?php else: ?>
          <span style="margin-bottom: 2px; line-height: 14px; display: block;"><a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['list']['user_id']; ?>" target="_blank"><?php echo self::$_var['list']['user_name']; ?></a></span>
          <?php endif; ?>
      	  <?php if (self::$_var['list']['level_name']): ?>
          <span style="border: 1px #f40d0d solid; background-color: #f40d0d; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"><?php echo self::$_var['list']['level_name']; ?></span>
          <?php else: ?>
          <span style="border: 1px #e77c2d solid; background-color: #e77c2d; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;">普通会员</span>
          <?php endif; ?>
  	   </td>
       <td><?php echo self::$_var['list']['phone']; ?></td>
       <td><?php echo self::$_var['list']['user_money']; ?>元</td>
       <td><?php echo self::$_var['list']['taoyu_money']; ?></td>
       <td><?php echo self::$_var['list']['add_time']; ?></td>
       <td><?php echo self::$_var['list']['validity_period']; ?></td>
      </tr>  
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
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
listTable.query = 'info_query';
listTable.act = 'inviter';
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


</script>

<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>