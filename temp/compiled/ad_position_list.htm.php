

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="js/calendar.php?lang=<?php echo self::$_var['cfg_lang']; ?>"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<div class="form-div">
  <form action="javascript:search_imgs_position()" name="searchForm">
    <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    <select name="select" >
    <option value="all">全部</option>
	<option value="1">app端口</option>
	<option value="2">电脑端口</option>
	<option value="3">手机网页端口</option>
	</select>
    关键字<input type="text" name="keyword" size="15" />
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellpadding="3" cellspacing="1">
	  <tr>
		<th>编号</th>
		<th>名称</th>
		<th>默认图像</th>
		<th>位置大小</th>
		<th>对应端口</th>
		<th>是否开启</th>
		<th><?php echo self::$_var['lang']['handler']; ?></th>
	  </tr>
	  <?php $_from = self::$_var['dcimgsite']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'imgsite');if (count($_from)):
    foreach ($_from AS self::$_var['imgsite']):
?>
	  <tr width="100%">
		<td width="3%" align="center"><span><?php echo self::$_var['imgsite']['site_id']; ?></span></td>
		<td width="15%" ><span><?php echo self::$_var['imgsite']['site_name']; ?></span></td>
		<td width="15%" ><span><img src="http://taoyumall.oss-cn-shanghai.aliyuncs.com/<?php echo self::$_var['imgsite']['default_img']; ?>" border="0" width="50px" style="cursor:pointer"/></span></td>
		<td width="15%" align="center"><span>宽:<?php echo self::$_var['imgsite']['width_w']; ?>,高:<?php echo self::$_var['imgsite']['high_h']; ?></span></td>
		<td width="15%" align="center"><span><?php echo self::$_var['imgsite']['media_type']; ?></span></td>
		<td width="10%" align="center"><img src="templates/default/images/<?php if (self::$_var['imgsite']['showsd']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_siteshowsd', <?php echo self::$_var['imgsite']['site_id']; ?>)" /></td>
		<td align="center" width="10%">
		  <span>
		  <a href="index.php?act=ad_position&op=edit&id=<?php echo self::$_var['imgsite']['site_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />编辑</a>
		  <a href="index.php?act=ads&op=lists&site_id=<?php echo self::$_var['imgsite']['site_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />广告列表</a>
		  <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['imgsite']['site_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"><?php echo self::$_var['lang']['remove']; ?></a>
		  </span>
		</td>
	  </tr>
	  <?php endforeach; else: ?>
		<tr><td class="no-records" colspan="11">暂无内容,请添加广告位置</td></tr>
	  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
	   <tr>
		<td align="right" nowrap="true" colspan="11"><?php echo self::fetch('page.htm'); ?></td>
	  </tr>
	</table>

<?php if (self::$_var['full_page']): ?>
</div>

<script language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = "lists_query";
  listTable.act = "ad_position";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
   function search_imgs_position()
    {
		listTable.filter['select'] = document.forms['searchForm'].elements['select'].value;
        listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }

  
</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>