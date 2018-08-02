
<?php if (self::$_var['full_page']): ?> <?php echo self::fetch('pageheader.htm'); ?> 
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<div class="form-div">
	<form action="javascript:searchGroupBuy()" name="searchForm">
		<img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
		<?php echo self::$_var['lang']['cus_name']; ?>或会员名称
		<input type="text" name="keyword" size="30" />
		<input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
	</form>
</div>
<form method="post" action="index.php?act=customer&op=batch_drop" name="listForm" onsubmit="return confirm('<?php echo self::$_var['lang']['batch_drop_confirm']; ?>');">
	
	<div class="list-div" id="listDiv">
		<?php endif; ?>
		<table cellpadding="3" cellspacing="1">
			<tr>
				<th>
					<input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" />
					<a href="javascript:listTable.sort('cus_id'); "><?php echo self::$_var['lang']['cus_id']; ?></a><?php echo self::$_var['sort_cus_id']; ?>
				</th>
				<th>
					<a href="javascript:listTable.sort('user_id'); "><?php echo self::$_var['lang']['user_id']; ?></a><?php echo self::$_var['sort_user_id']; ?>
				</th>
				<th>
					<a href="javascript:listTable.sort('cus_name'); "><?php echo self::$_var['lang']['cus_name']; ?></a><?php echo self::$_var['sort_cus_name']; ?>
				</th>
				<th>
					<a href="javascript:; "><?php echo self::$_var['lang']['cus_degree']; ?></a>
				</th>
				<th>
					<a href="javascript:listTable.sort('cus_type'); "><?php echo self::$_var['lang']['cus_type']; ?></a><?php echo self::$_var['sort_cus_type']; ?>
				</th>
				<th>
					<a href="javascript:listTable.sort('cus_enable'); "><?php echo self::$_var['lang']['cus_enable']; ?></a><?php echo self::$_var['sort_cus_enable']; ?>
				</th>
				<th>
					<a href="javascript:listTable.sort('add_time'); "><?php echo self::$_var['lang']['add_time']; ?></a><?php echo self::$_var['sort_add_time']; ?>
				</th>
				
				<th><?php echo self::$_var['lang']['handler']; ?></th>
			</tr>
			<?php $_from = self::$_var['customer_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');self::$_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if (self::$_foreach['name']['total'] > 0):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
        self::$_foreach['name']['iteration']++;
?>
			<tr>
				<td>
					<input value="<?php echo self::$_var['item']['cus_id']; ?>" name="checkboxes[]" type="checkbox" value="<?php echo self::$_var['item']['cus_id']; ?>">
					<?php echo self::$_var['item']['cus_id']; ?>
				</td>
				<td align="center"><?php echo self::$_var['item']['of_username']; ?></td>
				<td align="center"><?php echo self::$_var['item']['cus_name']; ?></td>
				<td align="center"><?php echo self::$_var['item']['cus_degree']; ?></td>
				<td align="center"><?php echo self::$_var['lang']['CUS_TYPE'][self::$_var['item']['cus_type']]; ?></td>
				<td align="center"><?php echo self::$_var['lang']['CUS_ENABLE'][self::$_var['item']['cus_enable']]; ?></td>
				<td align="center"><?php echo self::$_var['item']['formated_add_time']; ?></td>				
				<td align="center">
					
					<a href="index.php?act=customer&op=edit&id=<?php echo self::$_var['item']['cus_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>">
						<img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />
					</a>
					<a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['item']['cus_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>">
						<img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" />
					</a>
				</td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td>
			</tr>
			<?php endif; unset($_from); ?><?php self::pop_vars(); ?>
		</table>
		<table cellpadding="4" cellspacing="0">
			<tr>
				<td>
					<input type="submit" name="drop" id="btnSubmit" value="<?php echo self::$_var['lang']['drop']; ?>" class="button" disabled="true" />
				</td>
				<td align="right"><?php echo self::fetch('page.htm'); ?></td>
			</tr>
		</table>
		<?php if (self::$_var['full_page']): ?>
	</div>
	
</form>
<script type="text/javascript" >

  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = 'query';
  listTable.act = 'customer';
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  
  
  /**
   * 搜索
   */
  function searchGroupBuy()
  {

    var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['keyword'] = keyword;
    listTable.filter['page'] = 1;
    listTable.loadList("customer_list");
  }
  
 
</script>
<?php echo self::fetch('pagefooter.htm'); ?> <?php endif; ?>
