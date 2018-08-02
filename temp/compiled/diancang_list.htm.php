

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>


<div class="form-div">
  <form action="javascript:search_imgs_position()" name="searchForm">
    关键字<input type="text" name="keyword" size="30" placeholder="典藏名称 典藏描述 商品货号"/>
    <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>
<script language="JavaScript">
    function search_imgs_position()
    {
        listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }

</script>


  
  <div class="list-div" id="listDiv">
<?php endif; ?>
<table cellpadding="3" cellspacing="1">
  <tr>
    <th>
      <a href="javascript:listTable.sort('capitalid'); "><?php echo self::$_var['lang']['record_id']; ?></a><?php echo self::$_var['sort_capitalid']; ?>
    </th>
    <th>典藏名称</th>
	<th>图片</th>
	<th>编号</th>
	<th>分类</th>
	<th><a href="javascript:listTable.sort('add_time'); ">添加时间</a><?php echo self::$_var['sort_add_time']; ?></th>
	<th><a href="javascript:listTable.sort('dc_update'); ">修改时间</a><?php echo self::$_var['sort_dc_update']; ?></th>
	<th>典藏价格</th>
	<th>典藏详情</th>
	<th>是否用卷</th>
	<th>是否显示</th>
	<th>精品</th>
	<th>新品</th>
    <th>热销推荐</th>
    <th>推荐排序</th>
	<th>标签设置</th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  <tr>
  <?php $_from = self::$_var['diancang_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'diancang');if (count($_from)):
    foreach ($_from AS self::$_var['diancang']):
?>
  <tr>
    <td><?php echo self::$_var['diancang']['capitalid']; ?></td>
    <td class="first-cell"><?php echo htmlspecialchars(self::$_var['diancang']['dc_names']); ?></td>
	<td><img src="<?php echo self::$_var['diancang']['imgurls']; ?>" width="30" height="30" border="0" /></td>
    <td align="center"><?php echo self::$_var['diancang']['goods_sn']; ?></td>
	<td align="center"><?php echo self::$_var['diancang']['class_name']; ?></td>
    <td align="center"><?php echo self::$_var['diancang']['add_time']; ?></td>
	<td align="center"><?php echo self::$_var['diancang']['dc_update']; ?></td>
	<td align="center"><?php echo self::$_var['diancang']['dc_price']; ?></td>
	<td align="center"><?php echo self::$_var['diancang']['xiang']; ?></td>
	<td align="center"><img src="templates/default/images/<?php if (self::$_var['diancang']['juan_type']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_juan_type', <?php echo self::$_var['diancang']['capitalid']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['diancang']['dc_show']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_dc_show', <?php echo self::$_var['diancang']['capitalid']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['diancang']['dc_best']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_dc_best', <?php echo self::$_var['diancang']['capitalid']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['diancang']['dc_new']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_dc_new', <?php echo self::$_var['diancang']['capitalid']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['diancang']['dc_hot']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_dc_hot', <?php echo self::$_var['diancang']['capitalid']; ?>)" /></td>
    <td align="center"><span onclick="listTable.edit(this, 'edit_sort_goods', <?php echo self::$_var['diancang']['capitalid']; ?>)"><?php echo self::$_var['diancang']['sort_goods']; ?></span></td>
	<td align="center"><a href="index.php?act=diancang&op=set_dctags&capitalid=<?php echo self::$_var['diancang']['capitalid']; ?>" title="标签设置">标签（<?php echo self::$_var['diancang']['goodtagnum']; ?>）</a></td>
    <td align="center">
      <a href="index.php?act=diancang&op=dcedit&capitalid=<?php echo self::$_var['diancang']['capitalid']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" width="16" height="16" border="0" /></a>
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['diancang']['capitalid']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>','dcremove')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="14">暂无典藏产品快去添加吧</td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>



<table id="page-table" cellspacing="0">
  <tr>
    <td align="right" nowrap="true">
    <?php echo self::fetch('page.htm'); ?>
    </td>
  </tr>
</table>

<?php if (self::$_var['full_page']): ?>
</div>

<script type="text/javascript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.act = "diancang";
  listTable.query = "dcquery";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  


</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>