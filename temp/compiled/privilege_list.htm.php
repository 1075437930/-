<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php echo self::$_var['lang']['user_name']; ?></th>
    <th><?php echo self::$_var['lang']['join_time']; ?></th>
    <th><?php echo self::$_var['lang']['last_time']; ?></th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['admin_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>
  <tr>
    <td class="first-cell" ><?php echo self::$_var['list']['user_name']; ?></td>
    <td align="center"><?php echo self::$_var['list']['add_time']; ?></td>
    <td align="center"><?php echo self::$_var['list']['last_login']; ?></td>
    <td align="center">
      <a href="index.php?act=privilege&op=allot&id=<?php echo self::$_var['list']['user_id']; ?>&user=<?php echo self::$_var['list']['user_name']; ?>" title="<?php echo self::$_var['lang']['allot_priv']; ?>"><img src="templates/default/images/icon_priv.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp;
      <a href="index.php?act=adminlogs&op=lists&user_id=<?php echo self::$_var['list']['user_id']; ?>" title="<?php echo self::$_var['lang']['view_log']; ?>"><img src="templates/default/images/icon_view.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp;
      <a href="index.php?act=privilege&op=edit&id=<?php echo self::$_var['list']['user_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp;
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['list']['user_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
<?php if (self::$_var['full_page']): ?>
</div>
<script type="text/javascript" language="JavaScript">
   listTable.act = "privilege";
   listTable.query = "admin_query";
   
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>

