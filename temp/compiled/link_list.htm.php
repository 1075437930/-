

<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<form method="post" action="" name="listForm">



<div class="list-div" id="listDiv">

<?php endif; ?>

<table cellpadding="3" cellspacing="1">

  <tr>

    <th><a href="javascript:listTable.sort('link_name'); "><?php echo self::$_var['lang']['link_name']; ?></a><?php echo self::$_var['sort_link_name']; ?></th>

    <th><a href="javascript:listTable.sort('link_url'); "><?php echo self::$_var['lang']['link_url']; ?></a><?php echo self::$_var['sort_link_url']; ?></th>

    <!--<th><a href="javascript:listTable.sort('link_logo'); "><?php echo self::$_var['lang']['link_logo']; ?></a><?php echo self::$_var['sort_link_logo']; ?></th>-->

    <th><a href="javascript:listTable.sort('show_order'); "><?php echo self::$_var['lang']['show_order']; ?></a><?php echo self::$_var['sort_show_order']; ?></th>

    <th><?php echo self::$_var['lang']['handler']; ?></th>

  </tr>

  <tr>

  <?php $_from = self::$_var['links_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'link');if (count($_from)):
    foreach ($_from AS self::$_var['link']):
?>

  <tr>

    <td class="first-cell"><span onclick="listTable.edit(this, 'edit_link_name', <?php echo self::$_var['link']['link_id']; ?>)"><?php echo htmlspecialchars(self::$_var['link']['link_name']); ?></span></td>

    <td align="left"><span><a href="<?php echo self::$_var['link']['link_url']; ?>" target="_blank"><?php echo htmlspecialchars(self::$_var['link']['link_url']); ?></a></span></td>

    <!--<td align="center"><span><?php echo self::$_var['link']['link_logo']; ?></span></td>-->

    <td align="right"><span onclick="listTable.edit(this, 'edit_show_order', <?php echo self::$_var['link']['link_id']; ?>)"><?php echo self::$_var['link']['show_order']; ?></span></td>

    <td align="center"><span>

    <a href="index.php?act=friendlink&op=edit_link&id=<?php echo self::$_var['link']['link_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" /></a>&nbsp;

    <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['link']['link_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a></span></td>

  </tr>

  <?php endforeach; else: ?>

    <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_links']; ?></td></tr>

  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>

  <tr>

    <td align="right" nowrap="true" colspan="10"><?php echo self::fetch('page.htm'); ?></td>

  </tr>

</table>



<?php if (self::$_var['full_page']): ?>

</div>



</form>

<script type="text/javascript" language="JavaScript">

  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;

  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  
  listTable.act = 'friendlink';
  
  listTable.query = 'link_query';

  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  

  onload = function()

  {

    // 开始检查订单

    startCheckOrder();

  }

  

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>

