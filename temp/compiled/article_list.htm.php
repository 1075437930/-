

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<div class="form-div">
  <form action="javascript:searchArticle()" name="searchForm" >
    <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    <select name="cat_id" >
      <option value="0"><?php echo self::$_var['lang']['all_cat']; ?></option>
        <?php echo self::$_var['cat_select']; ?>
    </select>
    <?php echo self::$_var['lang']['title']; ?> <input type="text" name="keyword" id="keyword" />
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<form method="POST" action="index.php?act=article&op=batch_remove" name="listForm">

<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
      <a href="javascript:listTable.sort('article_id'); "><?php echo self::$_var['lang']['article_id']; ?></a><?php echo self::$_var['sort_article_id']; ?></th>
    <th><a href="javascript:listTable.sort('title'); "><?php echo self::$_var['lang']['title']; ?></a><?php echo self::$_var['sort_title']; ?></th>
    <th><a href="javascript:listTable.sort('cat_id'); "><?php echo self::$_var['lang']['cat']; ?></a><?php echo self::$_var['sort_cat_id']; ?></th>
    <th><a href="javascript:listTable.sort('article_type'); "><?php echo self::$_var['lang']['article_type']; ?></a><?php echo self::$_var['sort_article_type']; ?></th>
	<th><a href="javascript:listTable.sort('is_open'); "><?php echo self::$_var['lang']['is_open']; ?></a><?php echo self::$_var['sort_is_open']; ?></th>
	<th><a href="javascript:listTable.sort('article_hot'); "><?php echo self::$_var['lang']['article_hot']; ?></a><?php echo self::$_var['sort_article_hot']; ?></th>
	<th><a href="javascript:listTable.sort('article_news'); "><?php echo self::$_var['lang']['article_news']; ?></a><?php echo self::$_var['sort_article_news']; ?></th>
	<th><a href="javascript:listTable.sort('article_jt'); "><?php echo self::$_var['lang']['article_tj']; ?></a><?php echo self::$_var['sort_article_jt']; ?></th>
        <th><a href="javascript:listTable.sort('media_type'); "><?php echo self::$_var['lang']['article_ares']; ?></a><?php echo self::$_var['sort_media_type']; ?></th>
    <th><a href="javascript:listTable.sort('add_time'); "><?php echo self::$_var['lang']['add_time']; ?></a><?php echo self::$_var['sort_add_time']; ?></th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>
  <tr>
    <td><span><input name="checkboxes[]" type="checkbox" value="<?php echo self::$_var['list']['article_id']; ?>" <?php if (self::$_var['list']['cat_id'] <= 0): ?>disabled="true"<?php endif; ?>/><?php echo self::$_var['list']['article_id']; ?></span></td>
    <td class="first-cell">
    <span ><?php echo self::$_var['list']['title']; ?></span></td>
    <td align="left"><span><?php if (self::$_var['list']['cat_id'] > 0): ?><?php echo self::$_var['list']['cat_name']; ?><?php else: ?><?php echo self::$_var['lang']['reserve']; ?><?php endif; ?></span></td>
    <td align="center">
		<span>
		<?php if (self::$_var['list']['article_type'] == 0): ?>
			<?php echo self::$_var['lang']['common']; ?>
		<?php elseif (self::$_var['list']['article_type'] == 1): ?>
			<?php echo self::$_var['lang']['top']; ?>
		<?php else: ?>
			<?php echo self::$_var['lang']['video']; ?>
		<?php endif; ?></span></td>
    <td align="center"><?php if (self::$_var['list']['cat_id'] > 0): ?><span>
    <img src="templates/default/images/<?php if (self::$_var['list']['is_open'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_show', <?php echo self::$_var['list']['article_id']; ?>)" /></span><?php else: ?><img src="templates/default/images/yes.gif" alt="yes" /><?php endif; ?></td>
	<td align="center"><?php if (self::$_var['list']['cat_id'] > 0): ?><span>
    <img src="templates/default/images/<?php if (self::$_var['list']['article_hot'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'article_hot', <?php echo self::$_var['list']['article_id']; ?>)" /></span><?php else: ?><img src="templates/default/images/yes.gif" alt="yes" /><?php endif; ?></td>
	<td align="center"><?php if (self::$_var['list']['cat_id'] > 0): ?><span>
    <img src="templates/default/images/<?php if (self::$_var['list']['article_news'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'article_news', <?php echo self::$_var['list']['article_id']; ?>)" /></span><?php else: ?><img src="templates/default/images/yes.gif" alt="yes" /><?php endif; ?></td>
    <td align="center"><?php if (self::$_var['list']['cat_id'] > 0): ?><span>
    <img src="templates/default/images/<?php if (self::$_var['list']['article_jt'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'article_jt', <?php echo self::$_var['list']['article_id']; ?>)" /></span><?php else: ?><img src="templates/default/images/yes.gif" alt="yes" /><?php endif; ?></td>
    <td align="center"><span><?php echo self::$_var['list']['media_names']; ?></span></td>
	<td align="center"><span><?php echo self::$_var['list']['date']; ?></span></td>
    <td align="center" nowrap="true"><span>
<!--      <a href="article.php?id=<?php echo self::$_var['list']['article_id']; ?>" target="_blank" title="<?php echo self::$_var['lang']['view']; ?>"><img src="templates/default/images/icon_view.gif" border="0" height="16" width="16" /></a>&nbsp;-->
      <a href="index.php?act=article&op=article_edit&id=<?php echo self::$_var['list']['article_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" /></a>&nbsp;

     <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['list']['article_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','article_del')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a></span>
    </td>
   </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="11"><?php echo self::$_var['lang']['no_article']; ?></td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>&nbsp;
    <td align="right" nowrap="true" colspan="11"><?php echo self::fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if (self::$_var['full_page']): ?>
</div>

</form>

<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.act = "article";
  listTable.query = "article_query";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  



 /* 搜索文章 */
 function searchArticle()
 {
    listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter.cat_id = parseInt(document.forms['searchForm'].elements['cat_id'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }

 
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
