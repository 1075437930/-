

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar.php?lang=<?php echo self::$_var['cfg_lang']; ?>"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="form-div">
    <table>
        <tr>
            <td>
                <form name="theForm" action="javascript:searchInfo()" >
                    客服名字<input type="text" name="keyword" id="keyword" class="input_te" />
                    <input type="submit" value="搜索" class="button" />
                </form>
            </td>
        </tr>
    </table>
</div>


<div class="list-div" id="listDiv">
    <?php endif; ?>

    <table cellpadding="3" cellspacing="1">
        <tr>
            <th><a href="javascript:listTable.sort('log_id'); ">编号</a><?php echo self::$_var['sort_class_id']; ?></th>
            <th>客服名称</th>
            <th>操作记录</th>
            <th>操作时间</th>
            <th>ip</th>
        </tr>
        <?php $_from = self::$_var['cus_log_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'asks');if (count($_from)):
    foreach ($_from AS self::$_var['asks']):
?>
        <tr width="100%">
            <td width="3%" align="center"><span><?php echo self::$_var['asks']['log_id']; ?></span></td>
            <td width="5%" align="center" class="first-cell"><?php echo self::$_var['asks']['alias']; ?></td>
            <td width="32%" align="left"><span><?php echo self::$_var['asks']['log_info']; ?></span></td>
            <td width="18%" align="center"><span><?php echo self::$_var['asks']['log_time']; ?></span></td>
            <td width="18%" align="center" width="200"><span><?php echo self::$_var['asks']['ip_address']; ?></span></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td class="no-records" colspan="10">暂无内容,请添加问答</td></tr>
        <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
        <tr>
            <td align="right" nowrap="true" colspan="6"><?php echo self::fetch('page.htm'); ?></td>
        </tr>
    </table>

    
</div>

<?php if (self::$_var['full_page']): ?>
<script type="text/javascript" language="JavaScript">
    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.query = "cus_log_query";
    listTable.act = "askset";
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    
    


    function searchInfo()
    {
        listTable.filter['keyword'] = Utils.trim(document.forms['theForm'].elements['keyword'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
