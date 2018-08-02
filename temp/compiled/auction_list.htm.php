

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

<div class="form-div">
    <form action="javascript:searchActivity()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        <?php echo self::$_var['lang']['goods_name']; ?> <input type="text" name="keyword" size="30" />
        <input name="is_going" type="checkbox" id="is_going" value="1" />
        <?php echo self::$_var['lang']['act_is_going']; ?>
        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
    </form>
</div>

<form method="post" action="index.php" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
    
    <div class="list-div" id="listDiv">
        <?php endif; ?>

        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>
                    <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" />
                    <a href="javascript:listTable.sort('act_id'); "><?php echo self::$_var['lang']['record_id']; ?></a><?php echo self::$_var['sort_act_id']; ?></th>
                <th width="25%"><a href="javascript:listTable.sort('act_name'); "><?php echo self::$_var['lang']['act_name']; ?></a><?php echo self::$_var['sort_act_name']; ?></th>
                <th width="25%"><a href="javascript:listTable.sort('goods_name'); "><?php echo self::$_var['lang']['goods_name']; ?></a><?php echo self::$_var['sort_goods_name']; ?></th>
                <th><a href="javascript:listTable.sort('start_time'); "><?php echo self::$_var['lang']['start_time']; ?></a><?php echo self::$_var['sort_start_time']; ?></th>
                <th><a href="javascript:listTable.sort('end_time'); "><?php echo self::$_var['lang']['end_time']; ?></a><?php echo self::$_var['sort_end_time']; ?></th>
                <th><?php echo self::$_var['lang']['start_price']; ?></th>
                <th><?php echo self::$_var['lang']['end_price']; ?></th>
                <th>是否明拍</th>
                <th><?php echo self::$_var['lang']['handler']; ?></th>
            </tr>

            <?php $_from = self::$_var['auction_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'auction');if (count($_from)):
    foreach ($_from AS self::$_var['auction']):
?>
            <tr>
                <td><input value="<?php echo self::$_var['auction']['act_id']; ?>" name="checkboxes[]" type="checkbox"><?php echo self::$_var['auction']['act_id']; ?></td>
                <td><?php echo htmlspecialchars(self::$_var['auction']['act_name']); ?></td>
                <td><?php echo htmlspecialchars(self::$_var['auction']['goods_name']); ?></td>
                <td align="center"><?php echo self::$_var['auction']['start_time']; ?></td>
                <td align="center"><?php echo self::$_var['auction']['end_time']; ?></td>
                <td align="right"><?php echo self::$_var['auction']['start_price']; ?></td>
                <td align="right"><?php if (self::$_var['auction']['no_top']): ?><?php echo self::$_var['lang']['label_no_top']; ?><?php else: ?><?php echo self::$_var['auction']['end_price']; ?><?php endif; ?></td>
                <td align="center"><?php echo self::$_var['auction']['is_open_price']; ?></td>
                <td align="center">
                    <a href="index.php?act=auction&op=view_log&id=<?php echo self::$_var['auction']['act_id']; ?>"><img src="templates/default/images/icon_view.gif" title="<?php echo self::$_var['lang']['auction_log']; ?>" border="0" height="16" width="16" /></a>
                    <a href="index.php?act=auction&op=edit&amp;id=<?php echo self::$_var['auction']['act_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" /></a>
                    <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['auction']['act_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" /></a>      </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td class="no-records" colspan="12"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
            <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
        </table>

        <table cellpadding="4" cellspacing="0">
            <tr>
                <td><input type="submit" name="drop" id="btnSubmit" value="<?php echo self::$_var['lang']['drop']; ?>" class="button" disabled="true" />
                    <input type="hidden" name="op" value="batch" /></td>
                <td align="right"><?php echo self::fetch('page.htm'); ?></td>
            </tr>
        </table>

        <?php if (self::$_var['full_page']): ?>
    </div>
    
</form>

<script type="text/javascript" language="JavaScript">

    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.act = 'auction';
    listTable.query = 'query';
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

    
    onload = function()
    {
    document.forms['searchForm'].elements['keyword'].focus();
    startCheckOrder();
    }

    /**
     * 搜索团购活动
     */
    function searchActivity()
    {

    var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['keyword'] = keyword;
    if (document.forms['searchForm'].elements['is_going'].checked)
    {
    listTable.filter['is_going'] = 1;
    }
    else
    {
    listTable.filter['is_going'] = 0;
    }
    listTable.filter['page'] = 1;
    listTable.loadList("auction_list");
    }
    
</script>

<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>