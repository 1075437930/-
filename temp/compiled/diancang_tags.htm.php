
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<form method="post" action="" name="listForm">

<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' id="list-table">
  <tr>
    <th>典藏产品标签</th>
	<th>对应产品个数</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['dctags_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'dctags');if (count($_from)):
    foreach ($_from AS self::$_var['dctags']):
?> 
  <tr>
    <td class="first-cell" ><span onclick="listTable.edit(this,'edit_tags_name', <?php echo self::$_var['dctags']['tags_id']; ?>)"><?php echo self::$_var['dctags']['tags_name']; ?></span></td>
     <td class="first-cell" ><span><?php echo self::$_var['dctags']['goodnums']; ?></span></td>
	 
    <td align="center">	 <a href="index.php?act=dctags&op=edit&dctags_id=<?php echo self::$_var['dctags']['tags_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />编辑</a>
    <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['dctags']['tags_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','remove')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16">删除</a></td>
  </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="4">暂无内容,请添加典藏标签</td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
	<tr>
	<td align="right" nowrap="true" colspan="4"><?php echo self::fetch('page.htm'); ?></td>
	</tr>
  </table>
<?php if (self::$_var['full_page']): ?>
</div>

</form>
<script type="Text/Javascript" language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = "lists_query";
  listTable.act = "dctags";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  
  onload = function()
  {
    startCheckOrder();
  }
  
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
