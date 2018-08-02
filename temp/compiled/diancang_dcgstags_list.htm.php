
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' id="list-table">
  <tr>
    <th>典藏产品</th>
	<th>对应标签</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['dcgtags_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'dcgtags');if (count($_from)):
    foreach ($_from AS self::$_var['dcgtags']):
?> 
  <tr>
    <td class="first-cell" ><?php echo self::$_var['dcgtags']['dc_names']; ?></td>
     <td class="first-cell" ><?php echo self::$_var['dcgtags']['tags_name']; ?></td>
	 
    <td align="center">	 
		<a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['dcgtags']['taggood_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','remove_goodtags')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a>
	</td>
  </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="4">暂无内容,请添加对应典藏标签</td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
	<tr>
	<td align="right" nowrap="true" colspan="4"><?php echo self::fetch('page.htm'); ?></td>
	</tr>
  </table>
  <?php if (self::$_var['full_page']): ?>
</div>

<script type="text/javascript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.act="diancang";
  listTable.query="query_dcgtags";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?>='<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  


</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
