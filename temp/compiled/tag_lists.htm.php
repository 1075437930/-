
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/jquery-1.10.2.min_65682a2.js')); ?>


<div class="form-div">
    <form action="javascript:search_brand()" name="searchForm" method="post">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        按标签分类显示:
        <select name="cat_name" onchange="search_brand()">
            
            <option value="-10" >全部</option>
            <?php $_from = self::$_var['class_all']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val2');if (count($_from)):
    foreach ($_from AS self::$_var['val2']):
?>
                <?php if (self::$_var['val2'] [ tag_class_id ] == self::$_var['tag_class_id']): ?>
                <option value="<?php echo self::$_var['val2']['tag_class_id']; ?>" selected><?php echo self::$_var['val2']['class_name']; ?></option>
                    <?php else: ?>
                   <option value="<?php echo self::$_var['val2']['tag_class_id']; ?>"><?php echo self::$_var['val2']['class_name']; ?></option>
                <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>

    </form>
</div>
<script language="JavaScript">
    function search_brand(){

        //搜索字段名cat_name  document.forms['searchForm'].elements['cat_name'].value是上面搜索表单里的name为cat_name的input的value

        listTable.filter['tag_class_id']=Utils.trim(document.forms['searchForm'].elements['cat_name'].value);


        listTable.filter['page'] = 1;

        listTable.loadList();
    }
</script>


<form method="post" action="index.php" name="listForm" onsubmit="return confirm('<?php echo self::$_var['lang']['batch_drop_confirm']; ?>')">

<div class="list-div" id="listDiv">
<?php endif; ?>
  <table cellpadding="3" cellspacing="1">
    <tr>
        <th><?php echo self::$_var['lang']['tag_bh']; ?></th>
        <th><?php echo self::$_var['lang']['tag_class']; ?></th>
        <th><?php echo self::$_var['lang']['tag_name']; ?></th>
        <th><?php echo self::$_var['lang']['tag_zs']; ?></th>
        <th><?php echo self::$_var['lang']['tag_keywords']; ?></th>
        <th><?php echo self::$_var['lang']['cz']; ?></th>
    </tr>
    <?php $_from = self::$_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
    <tr>
        <td><?php echo self::$_var['val']['tag_id']; ?></td>
        <td><?php echo self::$_var['val']['class_name']; ?></td>
        <td><?php echo self::$_var['val']['tag_words']; ?></td>
        <td><?php echo self::$_var['val']['tag_cent']; ?></td>
        <td><?php echo self::$_var['val']['tag_key']; ?></td>

        <td>
            <a href="index.php?act=tag_manage&op=tag_edit&tag_id=<?php echo self::$_var['val']['tag_id']; ?>&tag_class_id=<?php echo self::$_var['val']['tag_class_id']; ?>" title="编辑"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16"></a>

            <a href="javascript:" class="dele" tag_id="<?php echo self::$_var['val']['tag_id']; ?>" tag_class_id="<?php echo self::$_var['val']['tag_class_id']; ?>"   title="移除"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a>
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
  listTable.query = "tag_query";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>



</script>


<script>
    $(function(){
        $('body').on('click','.dele',function(e){
            var tag_id=$(this).attr('tag_id');
            var tag_class_id=$(this).attr('tag_class_id');
            if(confirm('确定删除吗？')){
                location.href="index.php?act=tag_manage&op=tag_del&tag_id="+tag_id+"&tag_class_id="+tag_class_id;
            }
            e.stopImmediatePropagation();
        });

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