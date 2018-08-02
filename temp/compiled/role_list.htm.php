

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php echo self::$_var['lang']['user_name']; ?></th>
    <th><?php echo self::$_var['lang']['role_describe']; ?></th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['admin_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>
  <tr>
    <td class="first-cell" ><?php echo self::$_var['list']['role_name']; ?></td>
    <td class="first-cell" ><?php echo self::$_var['list']['role_describe']; ?></td>
    <td align="center">
      <a href="index.php?act=role&op=edit&id=<?php echo self::$_var['list']['role_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp;
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['list']['role_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>

<?php if (self::$_var['full_page']): ?>
</div>
<script type="text/javascript" language="JavaScript">
   listTable.act = "role";
   listTable.query = "role_query";
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
