
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/jquery-1.10.2.min_65682a2.js')); ?>

<div class="form-div">
    <form action="javascript:search_brand()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        关键字 <input type="text" name="cat_name" size="15" />
        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
    </form>
</div>
<script language="JavaScript">
    function search_brand(){
        //搜索字段名cat_name  document.forms['searchForm'].elements['cat_name'].value是上面搜索表单里的name为cat_name的input的value
        listTable.filter['cat_name'] = Utils.trim(document.forms['searchForm'].elements['cat_name'].value);

        listTable.filter['page'] = 1;
        listTable.loadList();
    }
</script>


<form method="post" action="index.php" name="listForm" onsubmit="return confirm('<?php echo self::$_var['lang']['batch_drop_confirm']; ?>')">

<div class="list-div" id="listDiv">
<?php endif; ?>
  <table cellpadding="3" cellspacing="1">
    <tr>
        <th><?php echo self::$_var['lang']['class_bh']; ?></th>
        <th><?php echo self::$_var['lang']['class_name']; ?></th>
        <th><?php echo self::$_var['lang']['class_zs']; ?></th>
        <th><?php echo self::$_var['lang']['baotags']; ?></th>
        <th><?php echo self::$_var['lang']['cz']; ?></th>
    </tr>
    <?php $_from = self::$_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
    <tr>

        <td><?php echo self::$_var['val']['tag_class_id']; ?></td>
        <td><?php echo self::$_var['val']['class_name']; ?></td>
        <td><?php echo self::$_var['val']['class_cent']; ?></td>
        <td><?php echo self::$_var['val']['baotags']; ?></td>

        <td>
            <a href="index.php?act=tag_manage&op=class_edit&tag_class_id=<?php echo self::$_var['val']['tag_class_id']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16">编辑分类</a>
            <a href="javascript:" tag_class_id="<?php echo self::$_var['val']['tag_class_id']; ?>" class="dele"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16">删除分类</a>
            <a  href="index.php?act=tag_manage&op=tag_lists&tag_class_id=<?php echo self::$_var['val']['tag_class_id']; ?>" ><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16">编辑分类下标签</a>
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
  listTable.act = "tag_manage";
  listTable.query = "type_query";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</script>

<script>


    $(function(){
        $('body').on('click','.dele',function(e){
            var cat_id=$(this).attr('tag_class_id');

            if(confirm('确定删除吗？')){
                location.href="index.php?act=tag_manage&op=class_del&tag_class_id="+cat_id
            }
            e.stopImmediatePropagation();
        })
    })

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>