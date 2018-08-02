
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

<?php echo self::smarty_insert_scripts(array('files'=>'js/jquery-1.10.2.min_65682a2.js')); ?>


<script>
    var deleteck = '<?php echo self::$_var['lang']['deleteck']; ?>';
    var deleteid = '<?php echo self::$_var['lang']['delete']; ?>';
    // 这里把JS用到的所有语言都赋值到这里

    <?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

    var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";

    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

    //-->
</script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />




<div class="form-div">
    <form action="javascript:search_brand()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        商品名称<input type="text" name="cat_name" size="15" />
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


<form method="post" action="../control/index.php" name="listForm" onsubmit="return confirm('<?php echo self::$_var['lang']['batch_drop_confirm']; ?>')">

<div class="list-div" id="listDiv">
<?php endif; ?>
  <table cellpadding="3" cellspacing="1">
    <tr>
        <th><input type="checkbox" class="pan" value="0"><?php echo self::$_var['lang']['trash_sn']; ?></th>
        <th><?php echo self::$_var['lang']['goods_sn']; ?></th>
        <th><?php echo self::$_var['lang']['lab_goods_name']; ?></th>
        <th><?php echo self::$_var['lang']['sale_time']; ?></th>
        <th><?php echo self::$_var['lang']['xia_time']; ?></th>
        <th><?php echo self::$_var['lang']['cz']; ?></th>
    </tr>
    <?php $_from = self::$_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
    <tr>

        <td><input type="checkbox" value="<?php echo self::$_var['val']['item_id']; ?>" class="plc"><?php echo self::$_var['val']['item_id']; ?></td>
        <td><?php echo self::$_var['val']['goods_sn']; ?></td>
        <td><?php echo self::$_var['val']['goods_name']; ?></td>
        <td><?php echo self::$_var['val']['starttime']; ?></td>
        <td><?php echo self::$_var['val']['endtime']; ?></td>

        <td>
            <a item_id="<?php echo self::$_var['val']['item_id']; ?>" href="javascript:" class="dele">撤销</a>
        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
    <tr>

        <td>
            <input type="hidden" name="op" value="" />
            <input name="date" type="text" id="date" size="10" value='0000-00-00' readonly="readonly" />
            <input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('date', '%Y-%m-%d', false, false, 'selbtn1');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>
        <input name="selbtn1" type="button" id="selbtn2"  value="<?php echo self::$_var['lang']['pls']; ?>" class="button" onclick="return validate()"/>
            <input name="selbtn1" type="button" id="selbtn3"  value="<?php echo self::$_var['lang']['plx']; ?>" class="button" onclick="return validate1()"/>
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
  listTable.act = "goods_auto";
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
            var cat_id=$(this).attr('item_id');

            if(confirm('确定撤销吗？')){
                location.href="index.php?act=goods_auto&op=auto_del&item_id="+cat_id
            }
            e.stopImmediatePropagation();
        })
    })

    $('.plc').change(function(){
//        alert($(this).val)
          $('.pan').val(1)
    });

    function validate(){
        if(document.listForm.elements["date"].value == "0000-00-00" ){
            alert('请选择日期');
            return false;
        }else{
            if($('.pan').val()==0){
                alert('请选择要上架的商品');
            }else{
                var date=$('#date').val();

                var str='';
                $('.plc:checked').each(function(){
                    str+=$(this).val()+','
                });
                str=str.substring(0,str.length-1);

                location.href="index.php?act=goods_auto&op=pls&item_id="+str+"&date="+date;
            }


        }
    }

    function validate1(){
        if(document.listForm.elements["date"].value == "0000-00-00" ){
            alert('请选择日期');
            return false;
        }else{
            if($('.pan').val()==0){
                alert('请选择要上架的商品');
            }else {
                var date = $('#date').val();

                var str = '';
                $('.plc:checked').each(function () {
                    str += $(this).val() + ','
                });
                str = str.substring(0, str.length - 1);

                location.href = "index.php?act=goods_auto&op=plx&item_id=" + str + "&date=" + date;
            }
        }
    }
</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>