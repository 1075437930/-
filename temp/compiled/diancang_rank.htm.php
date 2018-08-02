
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?><?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

<form method="post" action="" name="listForm">

<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' id="list-table">
  <tr>
    <th>会员等级名称</th>
    <th>积分下限</th>
    <th>积分上限</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['dc_ranks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'rank');if (count($_from)):
    foreach ($_from AS self::$_var['rank']):
?> 
  <tr>
    <td class="first-cell" ><span onclick="listTable.edit(this,'edit_name', <?php echo self::$_var['rank']['rank_id']; ?>)"><?php echo self::$_var['rank']['rank_name']; ?></span></td>
    <td align="right"><span <?php if (self::$_var['rank']['special_rank'] != 1): ?> onclick="listTable.edit(this, 'edit_min_points', <?php echo self::$_var['rank']['rank_id']; ?>)" <?php endif; ?> ><?php echo self::$_var['rank']['min_points']; ?></span></td>
    <td align="right"><span <?php if (self::$_var['rank']['special_rank'] != 1): ?> onclick="listTable.edit(this, 'edit_max_points', <?php echo self::$_var['rank']['rank_id']; ?>)" <?php endif; ?> ><?php echo self::$_var['rank']['max_points']; ?></span></td>
	 
    <td align="center">
    <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['rank']['rank_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','remove')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a></td>
  </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="4">暂无内容,请添加典藏等级</td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>	<tr>		<td align="right" nowrap="true" colspan="4"><?php echo self::fetch('page.htm'); ?></td>	  </tr>
  </table>	 

<?php if (self::$_var['full_page']): ?>
</div>

</form>
<script type="Text/Javascript" language="JavaScript">  
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;  
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.act = "dcrank";  
listTable.query = "lists_query";    
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>    
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';  
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>  
  


</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
