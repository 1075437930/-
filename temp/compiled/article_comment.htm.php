

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<div class="form-div">
  <form action="javascript:searchArticle()" name="searchForm" >
    <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    <?php echo self::$_var['lang']['comment']; ?> <input type="text" name="keyword" id="keyword" />
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<form method="POST" action="index.php?act=article&op=comment_beth" name="listForm">

<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
        <th><?php echo self::$_var['lang']['article_id']; ?></th>
        <th><?php echo self::$_var['lang']['comment']; ?></th>
        <th><?php echo self::$_var['lang']['title']; ?></th>
        <th><?php echo self::$_var['lang']['comment_type']; ?></th>
	<th><?php echo self::$_var['lang']['comment_zannum']; ?></th>
	<th><?php echo self::$_var['lang']['comment_huifu']; ?></th>
	<th><?php echo self::$_var['lang']['comment_user']; ?></th>
        <th><?php echo self::$_var['lang']['add_time']; ?></th>
        <th><?php echo self::$_var['lang']['is_open']; ?></th>
        <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['comment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>
  <tr>
    <td><span><?php echo self::$_var['list']['id']; ?></span></td>
    <td align="left"><span><?php echo self::$_var['list']['content']; ?></span></td>
    <td align="left"><span><?php echo self::$_var['list']['title']; ?></span></td>
    <td align="center"><span><?php echo self::$_var['list']['type_name']; ?></span></td>
    <td align="center"><?php echo self::$_var['list']['zannum']; ?></td>
    <td align="center"><?php echo self::$_var['list']['to_commnet']; ?></td>
    <td align="center"><span><?php echo self::$_var['list']['alias']; ?></span></td>
    <td align="center"><span><?php echo self::$_var['list']['date']; ?></span></td>
     <td align="center">
         <span>
             <img src="templates/default/images/<?php if (self::$_var['list']['show_type'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'comment_show_type', <?php echo self::$_var['list']['id']; ?>)" />
         </span>
    </td>
    <td align="center" nowrap="true"><span>
      <a href="index.php?act=article&op=comment_lock&comment_id=<?php echo self::$_var['list']['id']; ?>" title="<?php echo self::$_var['lang']['view']; ?>"><img src="templates/default/images/icon_view.gif" border="0" height="16" width="16" /></a>&nbsp;
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['list']['id']; ?>, '<?php echo self::$_var['lang']['drop_confirm_comment']; ?>','comment_del')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a></span>
    </td>
   </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_article']; ?></td></tr>
    <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>&nbsp;
    <td align="right" nowrap="true" colspan="10"><?php echo self::fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if (self::$_var['full_page']): ?>
</div>

</form>

<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.act = "article";
  listTable.query = "comment_query";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  



 /* 搜索文章评论 */
 function searchArticle()
 {
    listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }

 
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
