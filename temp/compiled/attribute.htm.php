
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/jquery-1.10.2.min_65682a2.js')); ?>


<div class="form-div">
    <form action="javascript:search_brand()" name="searchForm" method="post">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        按商品类型显示:
        <select name="cat_name" onchange="search_brand()">
            
            <option value="-10" >全部</option>
            <?php $_from = self::$_var['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val2');if (count($_from)):
    foreach ($_from AS self::$_var['val2']):
?>
                <?php if (self::$_var['val2'] [ cat_id ] == self::$_var['goods_type']): ?>
                <option value="<?php echo self::$_var['val2']['cat_id']; ?>" selected><?php echo self::$_var['val2']['cat_name']; ?></option>
                    <?php else: ?>
                   <option value="<?php echo self::$_var['val2']['cat_id']; ?>"><?php echo self::$_var['val2']['cat_name']; ?></option>
                <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>

    </form>
</div>
<script language="JavaScript">
    function search_brand(){

        //搜索字段名cat_name  document.forms['searchForm'].elements['cat_name'].value是上面搜索表单里的name为cat_name的input的value

        listTable.filter['goods_type']=Utils.trim(document.forms['searchForm'].elements['cat_name'].value);

        listTable.filter['page'] = 1;

        listTable.loadList();
    }
</script>


<form method="post" action="index.php" name="listForm" onsubmit="return confirm('<?php echo self::$_var['lang']['batch_drop_confirm']; ?>')">

<div class="list-div" id="listDiv">
<?php endif; ?>
  <table cellpadding="3" cellspacing="1">
    <tr>
        <th><input type="checkbox"><?php echo self::$_var['lang']['goods_attr_bh']; ?></th>
        <th><?php echo self::$_var['lang']['goods_attr_name']; ?></th>
        <th><?php echo self::$_var['lang']['goods_attr_type']; ?></th>
        <th><?php echo self::$_var['lang']['goods_attr_fs']; ?></th>
        <th><?php echo self::$_var['lang']['goods_attr_list']; ?></th>
        <th><?php echo self::$_var['lang']['goods_attr_px']; ?></th>
        <th><?php echo self::$_var['lang']['goods_attr_cz']; ?></th>
    </tr>
    <?php $_from = self::$_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
    <tr>

        <td><input type="checkbox" name="del_bh" value="<?php echo self::$_var['val']['attr_id']; ?>" type_id="<?php echo self::$_var['val']['type_id']; ?>"><?php echo self::$_var['val']['attr_id']; ?></td>
        <td><?php echo self::$_var['val']['attr_name']; ?></td>
        <td><?php echo self::$_var['val']['type_name']; ?></td>
        <td><?php echo self::$_var['val']['fs']; ?></td>
        <td><?php echo self::$_var['val']['attr_values']; ?></td>
        <td><?php echo self::$_var['val']['sort_order']; ?></td>

        <td>
            <a href="index.php?act=attribute&op=attr_edit&attr_id=<?php echo self::$_var['val']['attr_id']; ?>" title="编辑"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16"></a>
            <a href="javascript:" class="dele" attr_id="<?php echo self::$_var['val']['attr_id']; ?>" type_id="<?php echo self::$_var['val']['type_id']; ?>"   title="移除"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a>
        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
    <tr>
        <td><input  id="btnSubmit" value="删除" class="button" style="width: 50px;height: 30px;
        text-align: center">
        </td>
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


  listTable.act = "attribute";
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
            var attr_id=$(this).attr('attr_id');
            var type_id=$(this).attr('type_id');
            if(confirm('确定删除吗？')){
                location.href="index.php?act=attribute&op=attr_del&attr_id="+attr_id+"&type_id="+type_id;
            }
            e.stopImmediatePropagation();
        })

        //批量删除
        $('#btnSubmit').click(function(){
            var str='';
//            alert(input[name=del_bh])
            $('input[name=del_bh]:checked').each(function(){
                str+=$(this).val()+','
            });
            str=str.substring(0,str.length-1);
            var type_id=$('input[name=del_bh]').eq(0).attr('type_id');
            if(confirm('确定删除吗？')){
                location.href="index.php?act=attribute&op=attr_del&attr_id="+str+"&type_id="+type_id;
            }
        })
    })
</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>