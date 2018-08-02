

<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>

<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>



<form method="post" action="index.php" name="listForm">



<div class="list-div" id="listDiv">

<?php endif; ?>



<table cellspacing='1' id="list-table">

  <tr>

    <th><?php echo self::$_var['lang']['rank_name']; ?></th>    

    <th><?php echo self::$_var['lang']['sort_order']; ?></th>

    <th><?php echo self::$_var['lang']['handler']; ?></th>

  </tr>

  <?php $_from = self::$_var['user_ranks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'rank');if (count($_from)):
    foreach ($_from AS self::$_var['rank']):
?>

  <tr>

    <td class="first-cell" ><span onclick="listTable.edit(this,'edit_name', <?php echo self::$_var['rank']['rank_id']; ?>)"><?php echo self::$_var['rank']['rank_name']; ?></span></td>

    <td align="center"><span onclick="listTable.edit(this, 'edit_sort', <?php echo self::$_var['rank']['rank_id']; ?>)" /><?php echo self::$_var['rank']['sort_order']; ?></span></td>

    <td align="center">

    <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['rank']['rank_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a></td>

  </tr>

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  </table>



<?php if (self::$_var['full_page']): ?>

</div>



</form>

<script type="Text/Javascript" language="JavaScript">
listTable.act = 'suprank';
listTable.query = 'query';
<!--



onload = function()

{

    // 开始检查订单

    startCheckOrder();

}



//-->

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>

