

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

<div class="form-div">
    <form action="javascript:search_punish()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        搜索商家处罚分类 <input type="text" name="punish_reason" size="15" />
        <input type="submit" value="搜索" class="button" />
    </form>
</div>

<script language="JavaScript">
    function search_punish()
    {
        listTable.filter['punish_reason'] = Utils.trim(document.forms['searchForm'].elements['punish_reason'].value);
    listTable.query = 'punish_query';
        listTable.loadList();
    }

</script>
<?php endif; ?>

<div class="list-div" id="listDiv">


  <table cellpadding="3" cellspacing="1">
    <tr>
      <th>编号</th>
      <th>处罚原因</th>
      <th>处罚积分</th>
	  <th>违规说明</th>
      <th>备注</th>
      <th>操作</th>
    </tr>
    <?php $_from = self::$_var['punish_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'punish');if (count($_from)):
    foreach ($_from AS self::$_var['punish']):
?>
    <tr>
      <td align="center"><?php echo self::$_var['punish']['id']; ?></td>
      <td align="center" class="first-cell">
        <?php echo self::$_var['punish']['reason']; ?>
      </td>
	  <td align="center"><?php echo self::$_var['punish']['count']; ?></td>
	  <td align="center"><?php echo self::$_var['punish']['instructions']; ?></td>
      <td align="center"><?php echo self::$_var['punish']['beizhu']; ?></td>
     
      <td align="center">
        <a href="index.php?act=supintegral&op=punish_edit&id=<?php echo self::$_var['punish']['id']; ?>" title="编辑">编辑</a> |
        <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['punish']['id']; ?>, '你确认要删除选定的处罚分类吗？')" title="删除">删除</a> 
      </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10">没有更多记录</td></tr>
    <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  </table>

<?php if (self::$_var['full_page']): ?>
</div>

<script type="text/javascript" language="javascript">
 listTable.act = 'supintegral';
 listTable.query = 'punish_query';
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>