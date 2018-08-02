

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>


<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellpadding="3" cellspacing="1">
  <tr>
    <th>编号</th>
	<th>分类名称</th>
	<th>分类注释</th>
	<th>问题个数</th>
    <th>添加人</th>
    <th>添加时间</th>
	<th>设置</th>
  </tr>
  <?php $_from = self::$_var['class_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>
  <tr>
    <td width="3%" align="center"><span><?php echo self::$_var['list']['class_id']; ?></span></td>
	<td width="18%" align="center" class="first-cell"><span><?php echo self::$_var['list']['class_name']; ?></span></td>
	<td width="25%" class="first-cell"><span><?php echo self::$_var['list']['class_cent']; ?></span></td>
	<td width="10%" align="center" class="first-cell"><span><?php echo self::$_var['list']['counts']; ?></span></td>
    <td width="15%" align="center" class="first-cell"><span><?php echo self::$_var['list']['user_name']; ?></span></td>
    <td width="15%" align="left"><span><?php echo self::$_var['list']['add_time']; ?></span></td>
    <td width="15%" align="left">
	 <span>
      <a href="index.php?act=askset&op=ask_class_edit&amp;classid=<?php echo self::$_var['list']['class_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" /><?php echo self::$_var['lang']['edit']; ?></a>
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['list']['class_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','ask_class_remove')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"><?php echo self::$_var['lang']['remove']; ?></a>
	 </span>
	</td>
  </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10">暂无内容,请添加问答分类</td></tr>
   <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <td align="right" nowrap="true" colspan="10"><?php echo self::fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if (self::$_var['full_page']): ?>
</div>


<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = "ask_class_query";
  listTable.act = "askset";
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
