
<?php if (self::$_var['full_page']): ?><?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<div class="form-div">
    <form action="javascript:searchCustomer()" name="searchForm"><img src="templates/default/images/icon_search.gif" width="26"
                                                                      height="22" border="0" alt="SEARCH"/>
        <?php echo self::$_var['lang']['cus_name']; ?> <input type="text" name="keyword" size="30"/> <input type="submit"
                                                                              value="<?php echo self::$_var['lang']['button_search']; ?>"
                                                                              class="button"/></form>
</div>
<form method="POST" action="index.php?act=third_customer&op=batch_drop" name="listForm"
      onsubmit="return confirm('<?php echo self::$_var['lang']['batch_drop_confirm']; ?>')">
    <div class="list-div" id="listDiv"><?php endif; ?>
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/> <a
                        href="javascript:listTable.sort('cus_id'); "><?php echo self::$_var['lang']['record_id']; ?></a><?php echo self::$_var['sort_cus_id']; ?>
                </th>
                <th><a href="javascript:listTable.sort('cus_name'); "><?php echo self::$_var['lang']['cus_name']; ?></a><?php echo self::$_var['sort_cus_name']; ?></th>
                <th>客服组名称</th>
                <th><a href="javascript:listTable.sort('cus_no'); "><?php echo self::$_var['lang']['cus_no']; ?></a><?php echo self::$_var['sort_cus_no']; ?></th>
                <th><a href="javascript:listTable.sort('cus_type'); "><?php echo self::$_var['lang']['cus_type']; ?></a><?php echo self::$_var['sort_cus_type']; ?></th>
                <th><a href="javascript:listTable.sort('is_master'); "><?php echo self::$_var['lang']['is_master']; ?></a><?php echo self::$_var['sort_is_master']; ?></th>
                <th><a href="javascript:listTable.sort('add_time'); "><?php echo self::$_var['lang']['add_time']; ?></a><?php echo self::$_var['sort_add_time']; ?></th>
                <th><?php echo self::$_var['lang']['handler']; ?></th>
            </tr>
            <?php $_from = self::$_var['third_customer_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'cus');if (count($_from)):
    foreach ($_from AS self::$_var['cus']):
?>
            <tr>
                <td><input value="<?php echo self::$_var['cus']['cus_id']; ?>" name="checkboxes[]" type="checkbox"><?php echo self::$_var['cus']['cus_id']; ?></td>
                <td class="first-cell"><span><?php echo self::$_var['cus']['cus_name']; ?></span>
                </td>
                <td><?php echo self::$_var['cus']['zuname']; ?></td>
                <td class="first-cell"><span><?php echo self::$_var['cus']['cus_no']; ?></span>
                </td>
                <?php if (self::$_var['cus']['cus_type'] == 0): ?>
                <td align="center"><?php echo self::$_var['lang']['qq']; ?></td>
                <?php elseif (self::$_var['cus']['cus_type'] == 1): ?>
                <td align="center"><?php echo self::$_var['lang']['ww']; ?></td>
                <?php elseif (self::$_var['cus']['cus_type'] == 2): ?>
                <td align="center"><?php echo self::$_var['lang']['meiqia']; ?></td>
                <?php else: ?>
                <td align="center"><?php echo self::$_var['lang']['taoyumall']; ?></td>
                <?php endif; ?>
                <td align="center"><img src="templates/default/images/<?php if (self::$_var['cus']['is_master']): ?>yes<?php else: ?>no<?php endif; ?>.gif"
                                        onclick="listTable.toggle(this, 'toggle_master', <?php echo self::$_var['cus']['cus_id']; ?>)"/></td>
                <td align="center"><?php echo self::$_var['cus']['formated_add_time']; ?></td>
                <td align="center">
                <a href="index.php?act=third_customer&op=edit&cus_id=<?php echo self::$_var['cus']['cus_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>">
                <img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16"/></a>
                <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['cus']['cus_id']; ?>,'<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>">
                <img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16"/></a></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td>
            </tr>
            <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
        </table>
        <table cellpadding="4" cellspacing="0">
            <tr>
                <td><input type="submit" name="drop" id="btnSubmit" value="<?php echo self::$_var['lang']['drop']; ?>" class="button"
                           disabled="true"/></td>
                <td align="right"><?php echo self::fetch('page.htm'); ?></td>
            </tr>
        </table>
<?php if (self::$_var['full_page']): ?>
    </div>
</form>
<script type="text/javascript" language="JavaScript">listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
 listTable.act = 'third_customer';
listTable.query = 'query';
<?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
 function searchCustomer(){   
 	listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);   listTable.filter.page = 1;   
 	listTable.loadList();
 }
</script>
<?php echo self::fetch('pagefooter.htm'); ?><?php endif; ?>