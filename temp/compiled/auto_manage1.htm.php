<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

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
<?php if (! self::$_var['crons_enable']): ?>
<ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
  <li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;" ><?php echo self::$_var['lang']['enable_notice']; ?></li>
</ul>
<?php endif; ?>
<form action="javascript:searchArticle()" name="searchForm" method="post">
  <?php echo self::$_var['lang']['goods_name']; ?>
  <input type="hidden" name="act" value="list" />
  <input name="keyword" type="text" size="25" /> 
  <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
</form>
</div>
<form method="post" action="index.php" name="listForm" >
<div class="list-div" id="listDiv">
  <?php endif; ?>

<table cellspacing='1' cellpadding='3'>
<tr>
  <th width="5%"><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"><?php echo self::$_var['lang']['id']; ?></th>
  <th><?php echo self::$_var['lang']['article_numbe']; ?></th>
  <th><?php echo self::$_var['lang']['goods_name']; ?></th>
  <th><?php echo self::$_var['lang']['img_url']; ?></th>
  <th width="25%"><?php echo self::$_var['lang']['starttime']; ?></th>
  <th width="10%"><?php echo self::$_var['lang']['handler']; ?></th>
</tr>
<?php $_from = self::$_var['goodsdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
<tr>
  <td><input name="checkboxes[]" type="checkbox" value="<?php echo self::$_var['val']['goods_id']; ?>" /><?php echo self::$_var['val']['goods_id']; ?></td>
  <td><?php echo self::$_var['val']['goods_sn']; ?></td>
  <td><?php echo self::$_var['val']['goods_name']; ?></td>
  <td><img src="<?php echo self::$_var['val']['img_urls']; ?>" width="40" height="40" border="0" /></td>
  <td align="center">
  <span onclick="listTable.edit(this, 'edit_starttime', '<?php echo self::$_var['val']['goods_id']; ?>');showCalendar(this.firstChild, '%Y-%m-%d', false, false, this.firstChild)"><?php if (self::$_var['val']['starttime']): ?><?php echo self::$_var['val']['starttime']; ?><?php else: ?>0000-00-00<?php endif; ?></span>
</td>                                                                      
  <td align="center">
      <span id="del<?php echo self::$_var['val']['goods_id']; ?>">
        <?php if (self::$_var['val']['endtime'] || self::$_var['val']['starttime']): ?>
          <a href="index.php?act=articleauto&op=deleteck&goods_id=<?php echo self::$_var['val']['goods_id']; ?>" onclick="return confirm('<?php echo self::$_var['lang']['deleteck']; ?>');"><?php echo self::$_var['lang']['delete']; ?></a>
        <?php else: ?>
          -
        <?php endif; ?>
      </span>
  </td>
</tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
<?php endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
<?php if (self::$_var['full_page']): ?>
<?php endif; ?>
<table id="page-table" cellspacing="0">
  <tr>
    <td>
      <input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
      <input type="hidden" name="op" value="" />
      <input name="date" type="text" id="date" size="10" value='0000-00-00' readonly="readonly" />
      <input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('date', '%Y-%m-%d', false, false, 'selbtn1');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>
      <input type="button" id="btnSubmit1" value="<?php echo self::$_var['lang']['button_start']; ?>" class="button" onClick="return validate('batch_start')" />
    </td>
    <td align="right" nowrap="true">
    <?php echo self::fetch('page.htm'); ?>
    </td>
  </tr>
</table>
<?php if (self::$_var['full_page']): ?>
</form>
</div>
<script type="Text/Javascript" language="JavaScript">
listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
listTable.query = "article_auto_query";
listTable.act = "articleauto";
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

function validate(name){
  if(document.listForm.elements["date"].value == "0000-00-00"){
    alert('其选择日期');
    return false;	
  }else{
    document.listForm.op.value=name;
    document.listForm.submit();
  }
}

/* 搜索文章 */
 function searchArticle()
 {
    listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }

</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
