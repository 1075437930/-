<?php if (self::$_var['full_page']): ?>



<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<div class="form-div">

  <form action="javascript:searchUser()" name="searchForm">

    <img src="templates/default/images/icon_search.gif" width="25" height="22" border="0" alt="SEARCH" />

    <?php echo self::$_var['lang']['user_id']; ?> <input type="text" name="keyword" size="10" />

      <select name="process_type">

        <option value="-1"><?php echo self::$_var['lang']['process_type']; ?></option>

        <option value="0" <?php echo self::$_var['process_type_0']; ?>><?php echo self::$_var['lang']['surplus_type_0']; ?></option>

        <option value="1" <?php echo self::$_var['process_type_1']; ?>><?php echo self::$_var['lang']['surplus_type_1']; ?></option>

      </select>

      <select name="payment">

      <option value=""><?php echo self::$_var['lang']['pay_mothed']; ?></option>

      <?php echo self::html_options(array('options'=>self::$_var['payment_list'])); ?>

      </select>

      <select name="is_paid">

        <option value="-1"><?php echo self::$_var['lang']['status']; ?></option>

        <option value="0" <?php echo self::$_var['is_paid_0']; ?>><?php echo self::$_var['lang']['unconfirm']; ?></option>

        <option value="1" <?php echo self::$_var['is_paid_1']; ?>><?php echo self::$_var['lang']['confirm']; ?></option>

        <option value="2"><?php echo self::$_var['lang']['cancel']; ?></option>

      </select>

      <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />

  </form>

</div>



<form method="POST" action="" name="listForm">



<div class="list-div" id="listDiv">

<?php endif; ?>

<table cellpadding="3" cellspacing="1">

  <tr>

    <th><a href="javascript:listTable.sort('user_name', 'DESC'); "><?php echo self::$_var['lang']['user_id']; ?></a><?php echo self::$_var['sort_user_name']; ?></th>

    <th><a href="javascript:listTable.sort('add_time', 'DESC'); "><?php echo self::$_var['lang']['add_date']; ?></a><?php echo self::$_var['sort_add_time']; ?></th>

    <th><a href="javascript:listTable.sort('process_type', 'DESC'); "><?php echo self::$_var['lang']['process_type']; ?></a><?php echo self::$_var['sort_process_type']; ?></th>

    <th><a href="javascript:listTable.sort('amount', 'DESC'); "><?php echo self::$_var['lang']['surplus_amount']; ?></a><?php echo self::$_var['sort_amount']; ?></th>

    <th><a href="javascript:listTable.sort('payment', 'DESC'); "><?php echo self::$_var['lang']['pay_mothed']; ?></a><?php echo self::$_var['sort_payment']; ?></th>

    <th><a href="javascript:listTable.sort('is_paid', 'DESC'); "><?php echo self::$_var['lang']['status']; ?></a><?php echo self::$_var['sort_is_paid']; ?></th>

    <th><?php echo self::$_var['lang']['admin_user']; ?></th>

	<th><?php echo self::$_var['lang']['surplus_username']; ?></th>

    <th><?php echo self::$_var['lang']['handler']; ?></th>

  </tr>

  <?php $_from = self::$_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['item']):
?>

  <tr>

    <td>
		<a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['item']['user_id']; ?>" target="_blank">
      <?php if (self::$_var['item']['alias']): ?>
        <?php echo self::$_var['item']['alias']; ?>
      <?php elseif (self::$_var['item']['user_name']): ?>
        <?php echo self::$_var['item']['user_name']; ?>
      <?php else: ?>
        <?php echo self::$_var['lang']['no_user']; ?>
      <?php endif; ?>
	  </a>
      </td>

    <td align="center"><?php echo self::$_var['item']['add_date']; ?></td>

    <td align="center"><?php echo self::$_var['item']['process_type_name']; ?></td>

    <td align="right"><?php echo self::$_var['item']['surplus_amount']; ?></td>

    <td><?php if (self::$_var['item']['payment']): ?><?php echo self::$_var['item']['payment']; ?><?php else: ?>N/A<?php endif; ?></td>

	<?php if (self::$_var['item']['process_type'] == 1): ?>

		<td align="center">

		<?php if (self::$_var['item']['is_tx'] == 1): ?><?php echo self::$_var['lang']['confirm']; ?><?php endif; ?>

		<?php if (self::$_var['item']['is_tx'] == 2): ?><?php echo self::$_var['lang']['cancel']; ?><?php endif; ?>

		<?php if (self::$_var['item']['is_tx'] == 0): ?><?php echo self::$_var['lang']['unconfirm']; ?><?php endif; ?>

		</td>

	
  <?php else: ?>

		<td align="center"><?php if (self::$_var['item']['is_paid']): ?><?php echo self::$_var['lang']['confirm']; ?><?php else: ?><?php echo self::$_var['lang']['wei_pay']; ?><?php endif; ?></td>

	<?php endif; ?>

    <td align="center"><?php echo self::$_var['item']['admin_user']; ?></td>

	<td align="center"><?php echo self::$_var['item']['kaihu_user']; ?></td>

    <td align="center">

	<?php if (self::$_var['item']['process_type'] == 1): ?>

		<?php if (self::$_var['item']['is_tx'] == 1): ?>

		<a href="index.php?act=useraccount&op=info&id=<?php echo self::$_var['item']['id']; ?>" title="查看"><img src="templates/default/images/icon_view.gif" border="0" height="16" width="16" /></a>

		<?php endif; ?>

		<?php if (self::$_var['item']['is_tx'] == 2): ?>

		<a href="index.php?act=useraccount&op=info&id=<?php echo self::$_var['item']['id']; ?>" title="查看"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" /></a>

		<a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['item']['id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['drop']; ?>" ><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a>

		<?php endif; ?>

		<?php if (self::$_var['item']['is_tx'] == 0): ?>

			<a href="index.php?act=useraccount&op=check&id=<?php echo self::$_var['item']['id']; ?>" title="<?php echo self::$_var['lang']['check']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />

			<a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['item']['id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['drop']; ?>" ><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a>

		<?php endif; ?>

	<?php else: ?>

		<?php if (self::$_var['item']['is_paid']): ?>

		<a href="index.php?act=useraccount&op=info&id=<?php echo self::$_var['item']['id']; ?>" title="查看"><img src="templates/default/images/icon_view.gif" border="0" height="16" width="16" /></a>

		<?php else: ?>

		<a href="index.php?act=useraccount&op=check&id=<?php echo self::$_var['item']['id']; ?>" title="<?php echo self::$_var['lang']['check']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />

		<a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['item']['id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['drop']; ?>" ><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a>

		<?php endif; ?>

	<?php endif; ?>

    </td>

  </tr>

  <?php endforeach; else: ?>

  <tr>

    <td class="no-records" colspan="8"><?php echo self::$_var['lang']['no_records']; ?></td>

  </tr>

  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>



<table id="page-table" cellspacing="0">

<tr>

  <td>&nbsp;</td>

  <td align="right" nowrap="true">

  <?php echo self::fetch('page.htm'); ?>

  </td>

</tr>

</table>

<?php if (self::$_var['full_page']): ?>

</div>



</form>



<script type="text/javascript" language="JavaScript">

listTable.recordCount = <?php echo self::$_var['record_count']; ?>;

listTable.pageCount = <?php echo self::$_var['page_count']; ?>;

listTable.act = 'useraccount';

listTable.query = 'query';

<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';

<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>



<!--



onload = function()

{

    // 开始检查订单

    startCheckOrder();

}

/**

 * 搜索用户

 */

function searchUser()

{

    listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);

    listTable.filter['process_type'] = document.forms['searchForm'].elements['process_type'].value;

    listTable.filter['payment'] = Utils.trim(document.forms['searchForm'].elements['payment'].value);

    listTable.filter['is_paid'] = document.forms['searchForm'].elements['is_paid'].value;

    listTable.filter['page'] = 1;

    listTable.loadList();

}

//-->

</script>



<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>