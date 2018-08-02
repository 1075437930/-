
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

<div class="form-div">
    <form action="javascript:search_brand()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        搜索商品品牌 <input type="text" name="brand_name" size="15" />
        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
    </form>
</div>
<script language="JavaScript">
    function search_brand(){
        listTable.filter['brand_name'] = Utils.trim(document.forms['searchForm'].elements['brand_name'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }
</script>
<form method="post" action="index.php" name="listForm" onsubmit="return confirm('<?php echo self::$_var['lang']['batch_drop_confirm']; ?>')">

<div class="list-div" id="listDiv">
<?php endif; ?>
  <table cellpadding="3" cellspacing="1">
    <tr>
      <th><?php echo self::$_var['lang']['record_id']; ?></th>
      <th><?php echo self::$_var['lang']['brand_name']; ?></th>
      <th><?php echo self::$_var['lang']['brand_logo']; ?></th>
      <th><?php echo self::$_var['lang']['brand_desc']; ?></th>
      <th><?php echo self::$_var['lang']['sort_order']; ?></th>
      <th><?php echo self::$_var['lang']['is_show']; ?></th>
      <th><?php echo self::$_var['lang']['handler']; ?></th>
    </tr>
    <?php $_from = self::$_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'brand');if (count($_from)):
    foreach ($_from AS self::$_var['brand']):
?>
    <tr>
      <td><?php echo self::$_var['brand']['brand_id']; ?></td>
      <td align="left">
        <span onclick="javascript:listTable.edit(this, 'edit_brand_name', <?php echo self::$_var['brand']['brand_id']; ?>)"><?php echo htmlspecialchars(self::$_var['brand']['brand_name']); ?></span>
      </td>
      <td align="center"><img src="<?php echo self::$_var['brand']['brand_logo']; ?>" style="width: 50px;height: 50px" /></td>
      <td align="left"><?php echo self::$_var['brand']['brand_desc']; ?></td>
      <td align="right"><span onclick="javascript:listTable.edit(this, 'edit_sort_order', <?php echo self::$_var['brand']['brand_id']; ?>)"><?php echo self::$_var['brand']['sort_order']; ?></span></td>
      <td align="center"><img src="templates/default/images/<?php if (self::$_var['brand']['is_show']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_show', <?php echo self::$_var['brand']['brand_id']; ?>)" /></td>
      <td align="center">
        <a href="index.php?act=brand&op=brand_edit&brand_id=<?php echo self::$_var['brand']['brand_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><?php echo self::$_var['lang']['edit']; ?></a> |
        <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['brand']['brand_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','brand_remove')" title="<?php echo self::$_var['lang']['edit']; ?>"><?php echo self::$_var['lang']['remove']; ?></a> 
      </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
    <tr>
      <td align="right" nowrap="true" colspan="7">
      <?php echo self::fetch('page.htm'); ?>
      </td>
    </tr>
  </table>
<?php if (self::$_var['full_page']): ?>

</div>
</form>
<script type="text/javascript" language="javascript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.act = "brand";
  listTable.query = "brand_query()";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</script>
<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>