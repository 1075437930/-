

<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>


<div class="list-div" id="listDiv">

<table cellspacing='1' cellpadding='3'>

  <tr>

    <th><?php echo self::$_var['lang']['payment_name']; ?></th>

    <th width="40%"><?php echo self::$_var['lang']['payment_desc']; ?></th>

    <th><?php echo self::$_var['lang']['short_pay_fee']; ?></th>

    <th><?php echo self::$_var['lang']['sort_order']; ?></th>

    <th><?php echo self::$_var['lang']['handler']; ?></th>

  </tr>

  <?php $_from = self::$_var['pay_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'pay');if (count($_from)):
    foreach ($_from AS self::$_var['pay']):
?>
  <tr>

    <td class="first-cell" valign="top"><?php echo self::$_var['pay']['pay_name']; ?></td>
    <td><?php echo nl2br(self::$_var['pay']['pay_desc']); ?></td>
    <td valign="top" align="right">
      <span><?php echo self::$_var['pay']['pay_fee']; ?></span>
    </td>
    <td align="right" valign="top"> <span ><?php echo self::$_var['pay']['pay_order']; ?></span> </td>
    <td align="center" valign="top">      
      <a href="index.php?act=payment&op=edit&code=<?php echo self::$_var['pay']['pay_code']; ?>"><?php echo self::$_var['lang']['edit']; ?></a>
      <a href="javascript:confirm_redirect(lang_removeconfirm, 'index.php?act=payment&op=remove&code=<?php echo self::$_var['pay']['pay_code']; ?>')"><?php echo self::$_var['lang']['remove']; ?></a>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

</table>

</div>



<script type="Text/Javascript" language="JavaScript">

<!--



onload = function()

{

  // 开始检查订单

  startCheckOrder();

}



//-->

</script>

<?php echo self::fetch('pagefooter.htm'); ?>