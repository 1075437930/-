
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/jquery-1.10.2.min_65682a2.js')); ?>

<div class="form-div">
    <form action="javascript:search_brand()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        通知状态
        <select name="status" size="1">
            <option value="-10">不限</option>
            
            <option value="-1">未通知</option>
            <option value="1">系统通知（失败）</option>
            <option value="2">系统通知（成功）</option>
            <option value="3">人工通知</option>
        </select>
        客户手机号码
        <input type="text" name="cat_name" size="15" />

        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
    </form>
</div>
<script language="JavaScript">
    function search_brand(){

        listTable.filter['status'] = Utils.trim(document.forms['searchForm'].elements['status'].value);

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
        <th><?php echo self::$_var['lang']['bh']; ?></th>
        <th><?php echo self::$_var['lang']['phone']; ?></th>
        <th><?php echo self::$_var['lang']['email']; ?></th>
        <th><?php echo self::$_var['lang']['goods_name']; ?></th>
        <th><?php echo self::$_var['lang']['goods_price']; ?></th>
        <th><?php echo self::$_var['lang']['tongzhi_price']; ?></th>
        <th><?php echo self::$_var['lang']['tongzhi_state']; ?></th>
        <th><?php echo self::$_var['lang']['requ_time']; ?></th>
        <th><?php echo self::$_var['lang']['beizhu']; ?></th>
        <th><?php echo self::$_var['lang']['cz']; ?></th>
    </tr>
    <?php $_from = self::$_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
    <tr>

        <td><?php echo self::$_var['val']['pricecut_id']; ?></td>
        <td><?php echo self::$_var['val']['mobile']; ?></td>
        <td><?php echo self::$_var['val']['email']; ?></td>
        <td><?php echo self::$_var['val']['goods_name']; ?></td>
        <td>￥<?php echo self::$_var['val']['shop_price']; ?></td>
        <td>￥<?php echo self::$_var['val']['price']; ?></td>
        <td><?php echo self::$_var['val']['status']; ?></td>
        <td><?php echo self::$_var['val']['add_time']; ?></td>
        <td><?php echo self::$_var['val']['remark']; ?></td>

        <td>
            <a href="index.php?act=pricecut&op=pricecut_edit&pricecut_id=<?php echo self::$_var['val']['pricecut_id']; ?>" ><img title="编辑" src="templates/default/images/icon_edit.gif" border="0" height="16" width="16"></a>

            <a href="javascript:" pricecut_id="<?php echo self::$_var['val']['pricecut_id']; ?>" class="dele"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a>

        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
    <tr>
      <td align="right" nowrap="true" colspan="10">
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
  listTable.act = "pricecut";
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
            var cat_id=$(this).attr('pricecut_id');

            if(confirm('确定删除吗？')){
                location.href="index.php?act=pricecut&op=pricecut_del&pricecut_id="+cat_id
            }
            e.stopImmediatePropagation();
        })
    })

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>