
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<div class="list-div" id="listDiv">
<table cellspacing='0' cellpadding='3' class="shipping_list_th">
  <tr>
    <th width="15%"><?php echo self::$_var['lang']['shipping_name']; ?></th>
    <th width="35%"><?php echo self::$_var['lang']['shipping_desc']; ?></th>
    <th width="10%"><?php echo self::$_var['lang']['support_cod']; ?></th>
    <th width="5%"><?php echo self::$_var['lang']['sort_order']; ?></th>
    <th width="25%"><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
</table>
<table cellspacing='0' cellpadding='3' class="module">
  <?php $_from = self::$_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'module');if (count($_from)):
    foreach ($_from AS self::$_var['module']):
?>
  <tr>
    <td class="first-cell"  width="15%" align="center">
      <span onclick="listTable.edit(this, 'edit_name', '<?php echo self::$_var['module']['shipping_code']; ?>'); return false;"><?php echo self::$_var['module']['shipping_name']; ?></span>
      <?php echo self::$_var['module']['name']; ?>
    </td>
    <td width="35%">
      <span onclick="listTable.edit(this, 'edit_desc', '<?php echo self::$_var['module']['shipping_code']; ?>'); return false;"><?php echo self::$_var['module']['shipping_desc']; ?></span>
      <?php echo self::$_var['module']['desc']; ?>
    </td>

    <td align='center' width="10%"><?php if (self::$_var['module']['support_cod'] == 1): ?><?php echo self::$_var['lang']['yes']; ?><?php else: ?><?php echo self::$_var['lang']['no']; ?><?php endif; ?></td>
    <td align="center" width="5%">  <span onclick="listTable.edit(this, 'edit_order', '<?php echo self::$_var['module']['shipping_code']; ?>'); return false;"><?php echo self::$_var['module']['shipping_order']; ?></span></td>
    <td align="center" width="25%">

	  <a href="javascript:confirm_redirect(lang_removeconfirm,'index.php?act=shipping&op=remove&code=<?php echo self::$_var['module']['shipping_code']; ?>')"><?php echo self::$_var['lang']['remove']; ?></a>
	  <?php if (self::$_var['module']['is_default_show']): ?>
      <span style="color:red"><?php echo self::$_var['lang']['defaultshow']; ?></span>
      <a href="index.php?act=shipping&op=edit_print_template&shipping=<?php echo self::$_var['module']['shipping_id']; ?>"><?php echo self::$_var['lang']['shipping_print_edit']; ?></a>
	  <?php else: ?>	  
	  <a href="javascript:confirm_redirect(lang_isdefaultshowconfirm,'index.php?act=shipping&op=is_default_show&code=<?php echo self::$_var['module']['shipping_code']; ?>');"><?php echo self::$_var['lang']['isdefaultshow']; ?></a>
	  
	  <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>
</div>
</div>

<?php echo self::fetch('pagefooter.htm'); ?>




