

<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<form method="post" action="" name="listForm">



<div class="list-div" id="listDiv">

<?php endif; ?>



<table cellspacing='1' id="list-table">

  <tr>

    <th><?php echo self::$_var['lang']['rank_name']; ?></th>

    <th><?php echo self::$_var['lang']['integral_min']; ?></th>

    <th><?php echo self::$_var['lang']['integral_max']; ?></th>

    <th><?php echo self::$_var['lang']['discount']; ?>(%)</th>

    <th><?php echo self::$_var['lang']['special_rank']; ?></th>

    <th><?php echo self::$_var['lang']['show_price_short']; ?></th>

    

    <th>分成会员</th>



    <th><?php echo self::$_var['lang']['handler']; ?></th>

  </tr>

  <?php $_from = self::$_var['user_ranks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'rank');if (count($_from)):
    foreach ($_from AS self::$_var['rank']):
?>

  <tr>

    <td class="first-cell" ><span onclick="listTable.edit(this,'edit_name', <?php echo self::$_var['rank']['rank_id']; ?>)"><?php echo self::$_var['rank']['rank_name']; ?></span></td>

    <td align="right"><span <?php if (self::$_var['rank']['special_rank'] != 1): ?> onclick="listTable.edit(this, 'edit_min_points', <?php echo self::$_var['rank']['rank_id']; ?>)" <?php endif; ?> ><?php echo self::$_var['rank']['min_points']; ?></span></td>

    <td align="right"><span <?php if (self::$_var['rank']['special_rank'] != 1): ?> onclick="listTable.edit(this, 'edit_max_points', <?php echo self::$_var['rank']['rank_id']; ?>)" <?php endif; ?> ><?php echo self::$_var['rank']['max_points']; ?></span></td>

    <td align="right"><span onclick="listTable.edit(this, 'edit_discount', <?php echo self::$_var['rank']['rank_id']; ?>)"><?php echo self::$_var['rank']['discount']; ?></span></td>

    <td align="center"><img src="templates/default/images/<?php if (self::$_var['rank']['special_rank']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_special', <?php echo self::$_var['rank']['rank_id']; ?>)" /></td>

    <td align="center"><img src="templates/default/images/<?php if (self::$_var['rank']['show_price']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_showprice', <?php echo self::$_var['rank']['rank_id']; ?>)" /></td>

    

    <td align="center"><img src="templates/default/images/<?php if (self::$_var['rank']['is_recomm']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_is_recomm', <?php echo self::$_var['rank']['rank_id']; ?>)" /></td>

	 

    <td align="center">

    <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['rank']['rank_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"></a></td>

  </tr>

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  </table>



<?php if (self::$_var['full_page']): ?>

</div>



</form>

<script type="Text/Javascript" language="JavaScript">
listTable.act = 'userrank';
  
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

