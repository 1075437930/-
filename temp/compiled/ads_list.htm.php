
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<div class="form-div">
  <form action="javascript:search_ad()" name="searchForm">
    <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
     <select name="select">
     <option value="all">全部</option>
  <option value="1">按广告名称</option>
  <option value="2">按广告ID</option>
  </select>
    关键字<input type="text" name="keyword" size="15" />
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>
<script language="JavaScript">
    function search_ad()
    {
		    listTable.filter['select'] = document.forms['searchForm'].elements['select'].value;
        listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
        listTable.filter['page'] = 1;
        
        listTable.loadList();
    }

</script>

<form method="post" action="" name="listForm">

<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellpadding="3" cellspacing="1">
  <tr>
    <th><a href="javascript:listTable.sort('imgs_id'); "><?php echo self::$_var['lang']['ad_id']; ?></a><?php echo self::$_var['sort_imgs_id']; ?></th>
    <th><a href="javascript:listTable.sort('ad_name'); "><?php echo self::$_var['lang']['ad_name']; ?></a><?php echo self::$_var['sort_ad_name']; ?></th>
    <th>缩略图</th>
    <th><a href="javascript:listTable.sort('siteid'); "><?php echo self::$_var['lang']['position_name']; ?></a><?php echo self::$_var['sort_site_name']; ?></th>
    <th><a href="javascript:listTable.sort('start_time'); "><?php echo self::$_var['lang']['start_date']; ?></a><?php echo self::$_var['sort_start_date']; ?></th>
    <th><a href="javascript:listTable.sort('end_time'); "><?php echo self::$_var['lang']['end_date']; ?></a><?php echo self::$_var['sort_end_date']; ?></th>
    <th>对应连接</th>
    <th>广告参数</th>
    <th><a href="javascript:listTable.sort('click_count'); "><?php echo self::$_var['lang']['click_count']; ?></a><?php echo self::$_var['sort_click_count']; ?></th>
    <th>是否开启</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['ads_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>
  <tr>
   <td class="first-cell">
    <span><?php echo self::$_var['list']['imgs_id']; ?></span>
    </td>
    <td class="first-cell">
    <span ><?php echo htmlspecialchars(self::$_var['list']['ad_name']); ?></span>
    </td>
    <td align="left"><span><img src="http://taoyumall.oss-cn-shanghai.aliyuncs.com/<?php echo self::$_var['list']['imgurl']; ?>" width="50px" style="cursor:pointer"></span>
    </td>
    <td align="left"><span><?php if (self::$_var['list']['siteid'] == 0): ?><?php echo self::$_var['lang']['outside_posit']; ?><?php else: ?><?php echo self::$_var['list']['site_name']; ?><?php endif; ?></span></td>
    <td align="center"><span><?php echo self::$_var['list']['start_date']; ?></span></td>
    <td align="center"><span><?php echo self::$_var['list']['end_date']; ?></span></td>
    <td align="center"><span><?php echo self::$_var['list']['links']; ?></span></td>
    <td align="center"><span><?php echo self::$_var['list']['canshu']; ?></span></td>
    <td align="center"><span><?php echo self::$_var['list']['click_count']; ?></span></td>
    <td width="10%" align="center"><img src="templates/default/images/<?php if (self::$_var['list']['showsd'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_showsd', <?php echo self::$_var['list']['imgs_id']; ?>)" /></td>
    <td align="center"><span>
      <a href="index.php?act=ads&op=edit&id=<?php echo self::$_var['list']['imgs_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" /></a>
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['list']['imgs_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a></span>
    </td>
  </tr>
  <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="11"><?php echo self::$_var['lang']['no_ads']; ?></td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <td align="right" nowrap="true" colspan="11"><?php echo self::fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if (self::$_var['full_page']): ?>
</div>

</form>

<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = "ads_list_query";
  listTable.act = "ads";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
