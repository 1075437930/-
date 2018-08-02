

<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>



<div class="form-div">

<form method="post" action="index.php?act=account_log&op=lists&user_id=<?php echo $_GET['user_id']; ?>" name="searchForm">

  <select name="account_type" onchange="document.forms['searchForm'].submit()">

    <option value="" <?php if (self::$_var['account_type'] == ''): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['lang']['all_account']; ?></option>

    <option value="user_money" <?php if (self::$_var['account_type'] == 'user_money'): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['lang']['user_money']; ?></option>

    <option value="frozen_money" <?php if (self::$_var['account_type'] == 'frozen_money'): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['lang']['frozen_money']; ?></option>

    <option value="rank_points" <?php if (self::$_var['account_type'] == 'rank_points'): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['lang']['rank_points']; ?></option>

    <option value="pay_points" <?php if (self::$_var['account_type'] == 'pay_points'): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['lang']['pay_points']; ?></option>

    <option value="taoyu_money" <?php if (self::$_var['account_type'] == 'taoyu_money'): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['lang']['taoyu_money']; ?></option>

  </select>

  <strong><?php echo self::$_var['lang']['label_user_name']; ?></strong>
  
  <?php if (self::$_var['user']['alias']): ?>
    <?php echo self::$_var['user']['alias']; ?>
  <?php else: ?>    
    <?php echo self::$_var['user']['user_name']; ?>
  <?php endif; ?>

  <strong><?php echo self::$_var['lang']['label_user_money']; ?></strong><?php echo self::$_var['user']['formated_user_money']; ?>

  <strong><?php echo self::$_var['lang']['label_frozen_money']; ?></strong><?php echo self::$_var['user']['formated_frozen_money']; ?>

  <strong><?php echo self::$_var['lang']['label_rank_points']; ?></strong><?php echo self::$_var['user']['rank_points']; ?>

  <strong><?php echo self::$_var['lang']['label_pay_points']; ?></strong><?php echo self::$_var['user']['pay_points']; ?>

  <strong><?php echo self::$_var['lang']['label_taoyu_money']; ?></strong><?php echo self::$_var['user']['taoyu_money']; ?>

  </form>

</div>



<form method="post" action="" name="listForm">

<div class="list-div" id="listDiv">

<?php endif; ?>



  <table cellpadding="3" cellspacing="1">

    <tr>

      <th width="20%"><?php echo self::$_var['lang']['change_time']; ?></th>

      <th width="30%"><?php echo self::$_var['lang']['change_desc']; ?></th>

      <th><?php echo self::$_var['lang']['user_money']; ?></th>

      <th><?php echo self::$_var['lang']['frozen_money']; ?></th>

      <th><?php echo self::$_var['lang']['rank_points']; ?></th>

      <th><?php echo self::$_var['lang']['pay_points']; ?></th>

      <th><?php echo self::$_var['lang']['taoyu_money']; ?></th>

    </tr>

    <?php $_from = self::$_var['account_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'account');if (count($_from)):
    foreach ($_from AS self::$_var['account']):
?>

    <tr>

      <td><?php echo self::$_var['account']['change_time']; ?></td>

      <td><?php echo htmlspecialchars(self::$_var['account']['change_desc']); ?></td>

      <td align="right">

        <?php if (self::$_var['account']['user_money'] > 0): ?>

          <span style="color:#0000FF">+<?php echo self::$_var['account']['user_money']; ?></span>

        <?php elseif (self::$_var['account']['user_money'] < 0): ?>

          <span style="color:#FF0000"><?php echo self::$_var['account']['user_money']; ?></span>

        <?php else: ?>

          <?php echo self::$_var['account']['user_money']; ?>

        <?php endif; ?>

      </td>

      <td align="right">

        <?php if (self::$_var['account']['frozen_money'] > 0): ?>

          <span style="color:#0000FF">+<?php echo self::$_var['account']['frozen_money']; ?></span>

        <?php elseif (self::$_var['account']['frozen_money'] < 0): ?>

          <span style="color:#FF0000"><?php echo self::$_var['account']['frozen_money']; ?></span>

        <?php else: ?>

          <?php echo self::$_var['account']['frozen_money']; ?>

        <?php endif; ?>

      </td>

      <td align="right">

        <?php if (self::$_var['account']['rank_points'] > 0): ?>

          <span style="color:#0000FF">+<?php echo self::$_var['account']['rank_points']; ?></span>

        <?php elseif (self::$_var['account']['rank_points'] < 0): ?>

          <span style="color:#FF0000"><?php echo self::$_var['account']['rank_points']; ?></span>

        <?php else: ?>

          <?php echo self::$_var['account']['rank_points']; ?>

        <?php endif; ?>

      </td>

      <td align="right">

        <?php if (self::$_var['account']['pay_points'] > 0): ?>

          <span style="color:#0000FF">+<?php echo self::$_var['account']['pay_points']; ?></span>

        <?php elseif (self::$_var['account']['pay_points'] < 0): ?>

          <span style="color:#FF0000"><?php echo self::$_var['account']['pay_points']; ?></span>

        <?php else: ?>

          <?php echo self::$_var['account']['pay_points']; ?>

        <?php endif; ?>

      </td>

      <td align="right">

        <?php if (self::$_var['account']['taoyu_money'] > 0): ?>

        <span style="color:#0000FF">+<?php echo self::$_var['account']['taoyu_money']; ?></span>

        <?php elseif (self::$_var['account']['taoyu_money'] < 0): ?>

        <span style="color:#FF0000"><?php echo self::$_var['account']['taoyu_money']; ?></span>

        <?php else: ?>

        <?php echo self::$_var['account']['taoyu_money']; ?>

        <?php endif; ?>

      </td>

    </tr>

    <?php endforeach; else: ?>

    <tr><td class="no-records" colspan="6"><?php echo self::$_var['lang']['no_records']; ?></td></tr>

    <?php endif; unset($_from); ?><?php self::pop_vars(); ?>

  </table>

<table id="page-table" cellspacing="0">

  <tr>

    <td align="right" nowrap="true">

    <?php echo self::fetch('page.htm'); ?>

    </td>

  </tr>

</table>



<?php if (self::$_var['full_page']): ?>

</div>

</form>



<script type="text/javascript" language="javascript">

  <!--

  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;

  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.query = "query";
  listTable.act = "account_log";


  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>



  

  onload = function()

  {

      // 开始检查订单

      startCheckOrder();

  }

  

  //-->

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>