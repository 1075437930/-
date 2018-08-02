<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<div class="form-div">
  <form action="javascript:search_imgs_position()" name="searchForm">
    实体店名称或电话<input type="text" name="words" size="15" />
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
    <input type="hidden" name="act" value="offlineshop" class="button" />
    <input type="hidden" name="op" value="offline_query" class="button" />
  </form>
</div>
<script language="JavaScript">
    function search_imgs_position()
    {
        listTable.filter['words'] = Utils.trim(document.forms['searchForm'].elements['words'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }

</script>

<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellpadding="3" cellspacing="1">
	 <tr>
		<th><?php echo self::$_var['lang']['record_id']; ?></th>
		<th><?php echo self::$_var['lang']['store_name']; ?></th>
		<th><?php echo self::$_var['lang']['store_phone']; ?></th>
		<th><?php echo self::$_var['lang']['store_address']; ?></th>
		<th><?php echo self::$_var['lang']['qr_code']; ?></th>
		<th><?php echo self::$_var['lang']['success_num']; ?></th>
		<th><a href="javascript:listTable.sort('add_time'); "><?php echo self::$_var['lang']['add_time']; ?></a><?php echo self::$_var['sort_add_time']; ?></th>
		<th><?php echo self::$_var['lang']['store_disc']; ?></th>
		<th><?php echo self::$_var['lang']['show_style']; ?></th>
		<th><?php echo self::$_var['lang']['handler']; ?></th>
	<tr>
  <?php $_from = self::$_var['shop_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'shopitem');if (count($_from)):
    foreach ($_from AS self::$_var['shopitem']):
?>
  <tr>
    <td><?php echo self::$_var['shopitem']['offline_id']; ?></td>
    <td class="first-cell"><?php echo self::$_var['shopitem']['offline_name']; ?></td>
    <td align="center"><?php echo self::$_var['shopitem']['offline_tel']; ?></td>
	<td align="center">[<?php echo self::$_var['shopitem']['man_address']; ?>] - <?php echo self::$_var['shopitem']['address']; ?></td>
	<td align="center"><a href="<?php echo self::$_var['shopitem']['daimgurl']; ?>" target="view_window"><img src="<?php echo self::$_var['shopitem']['imgurl']; ?>" width="30" height="30" border="0" /></a></td>
    <td align="center"><a href="index.php?act=offlineshop&op=lists&offline_id=<?php echo self::$_var['shopitem']['offline_id']; ?>"><?php echo self::$_var['shopitem']['counts']; ?></a></td>
	<td align="center"><?php echo self::$_var['shopitem']['add_time']; ?></td>
	<td align="center"><?php echo self::$_var['shopitem']['offline_desc']; ?></td>
	<td align="center"><?php echo self::$_var['shopitem']['yangshi']; ?></td>
    <td align="center">
      <a href="index.php?act=offlineshop&op=offshop_edit&offline_id=<?php echo self::$_var['shopitem']['offline_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" width="16" height="16" border="0" /></a>
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['shopitem']['offline_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','remove_offline')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="14"><?php echo self::$_var['lang']['no_store']; ?></td></tr>
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
  listTable.query = "offline_query";
  listTable.act = "offlineshop";
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
