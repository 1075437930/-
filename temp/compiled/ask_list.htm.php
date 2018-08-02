

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="form-div">
<table>
  <tr>
      <td>
      <form name="theForm" action="javascript:searchInfo()" >
		问题或回答中包含关键词<input type="text" name="keyword" id="keyword" class="input_te" />
		<input type="submit" value="搜索" class="button" />
      </form>
      </td>   
  </tr>
</table>
</div>


<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellpadding="3" cellspacing="1">
  <tr>
    <th>编号</th>
    <th><a href="javascript:listTable.sort('class_id'); ">分类</a><?php echo self::$_var['sort_class_id']; ?></th>
    <th>问题</th>
    <th>关键词</th>
    <th>答案</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['ask_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'asks');if (count($_from)):
    foreach ($_from AS self::$_var['asks']):
?>
  <tr width="100%">
    <td width="3%" align="center"><span><?php echo self::$_var['asks']['ask_id']; ?></span></td>
    <td width="5%" align="center" class="first-cell"><a href="index.php?act=askset&op=ask_list&amp;class_id=<?php echo self::$_var['asks']['class_id']; ?>" title="<?php echo self::$_var['asks']['class_cent']; ?>"><span><?php echo self::$_var['asks']['class_name']; ?></span></a></td>
    <td width="18%" align="left"><span><?php echo self::$_var['asks']['questions']; ?></span></td>
    <td width="18%" align="left"><span><?php echo self::$_var['asks']['keyword']; ?></span></td>
	<td width="32%" align="left" width="200"><span><?php echo self::$_var['asks']['answers']; ?></span></td>
    <td align="center" width="20%">
      <span>
      <a href="index.php?act=askset&op=ask_edit&amp;askid=<?php echo self::$_var['asks']['ask_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="<?php echo self::$_var['urls_dir']; ?>/images/icon_edit.gif" border="0" height="16" width="16" />编辑问答</a>
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['asks']['ask_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','ask_remove')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="<?php echo self::$_var['urls_dir']; ?>/images/icon_drop.gif" border="0" height="16" width="16">删除问答</a>
	  </span>
    </td>
  </tr>
  <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10">暂无内容,请添加问答</td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <td align="right" nowrap="true" colspan="6"><?php echo self::fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if (self::$_var['full_page']): ?>
</div>


<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = "ask_list_query";
  listTable.act = "askset";

  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  



function searchInfo()
{
    listTable.filter['keyword'] = Utils.trim(document.forms['theForm'].elements['keyword'].value);
	  listTable.filter['page'] = 1;
    listTable.loadList();
}
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
