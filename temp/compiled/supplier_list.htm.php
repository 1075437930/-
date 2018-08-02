
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

<div class="form-div">
    <form action="javascript:searchSupplier()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        <?php echo htmlspecialchars(self::$_var['lang']['supplier_name']); ?>
        <input name="supplier_name" type="text" id="supplier_name" size="15">
        <?php echo self::$_var['lang']['supplier_rank']; ?>
        <select name="rank_name" size=1>
            <option value="0">请选择</option>
            <?php $_from = self::$_var['supplier_rank']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'rank');if (count($_from)):
    foreach ($_from AS self::$_var['rank']):
?>
            <option value="<?php echo self::$_var['rank']['rank_id']; ?>" <?php if (self::$_var['supplier']['rank_id'] == self::$_var['rank']['rank_id']): ?>selected<?php endif; ?>><?php echo self::$_var['rank']['rank_name']; ?></option>
            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>
        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
        <?php if (self::$_var['status'] != 1): ?>
        <a href="index.php?act=supplier&op=lists&status=0&type=a">未审核</a>
        <a href="index.php?act=supplier&op=lists&status=-1&type=a">未通过</a>
        <?php endif; ?>
<!--        <?php if (self::$_var['status'] == 1): ?>
        <input type="button" value="批量导出" class="button" onclick="batch_export()" />
        <?php endif; ?>-->
    </form>
</div>
<form method="post" action="" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
    <div class="list-div" id="listDiv">
        <?php endif; ?>
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th><?php echo self::$_var['lang']['supplier_username']; ?></th>
                <th><?php echo self::$_var['lang']['supplier_name']; ?></th>
                <th><?php echo self::$_var['lang']['supplier_rank']; ?></th>
                <th>入驻商积分</th>
                <!-- <th><?php echo self::$_var['lang']['supplier_tel']; ?></th> -->
                <th>负责人</th>
                <th>联系电话</th>
                <th>商家地址</th>
                <!-- <th><?php echo self::$_var['lang']['system_fee']; ?></th>
                <th><?php echo self::$_var['lang']['supplier_bond']; ?></th> -->
                <th>分成利率</th>
                <th><?php echo self::$_var['lang']['supplier_remark']; ?></th>
                <th>状态</th>
                <th><?php echo self::$_var['lang']['handler']; ?></th>
            </tr>
            <?php $_from = self::$_var['supplier_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'supplier');if (count($_from)):
    foreach ($_from AS self::$_var['supplier']):
?>
            <tr class="
                <?php if (self::$_var['supplier']['status'] == 0): ?>tr_canceled<?php endif; ?> 
                <?php if (self::$_var['supplier']['status'] == - 1): ?> tr_nopay <?php endif; ?>">
                <td ><?php echo self::$_var['supplier']['alias']; ?> </td>
                <td class="first-cell" style="padding-left:10px;" ><?php echo self::$_var['supplier']['supplier_name']; ?></td>
                <td ><?php echo self::$_var['supplier']['rank_name']; ?> </td>
                <td ><?php if (self::$_var['supplier']['status'] == 1): ?><?php echo self::$_var['supplier']['jifen']; ?><?php endif; ?> </td>
                <!--  <td><?php echo self::$_var['supplier']['tel']; ?></td> -->
                <td><?php echo self::$_var['supplier']['contacts_name']; ?></td>
                <td><?php echo self::$_var['supplier']['contacts_phone_mi']; ?></td>
                <td><?php echo self::$_var['supplier']['supplier_address']; ?></td>
                <!-- <td align="center"><?php echo self::$_var['supplier']['system_fee']; ?></td>
                <td align="center"><?php echo self::$_var['supplier']['supplier_bond']; ?></td> -->
                <td align="center"><?php echo self::$_var['supplier']['supplier_rebate']; ?></td>
                <td align="center"><?php echo self::$_var['supplier']['supplier_remark']; ?></td>
                <td align="center"><?php echo self::$_var['supplier']['status_name']; ?></td>
                <td align="center">
                    <a href="index.php?act=supplier&op=edit&id=<?php echo self::$_var['supplier']['supplier_id']; ?>&status=<?php echo self::$_var['status']; ?>" title="<?php echo self::$_var['lang']['view']; ?>"><?php echo self::$_var['lang']['view']; ?></a><?php if (self::$_var['supplier']['status'] > 0 && self::$_var['supplier']['open'] > 0): ?>&nbsp;&nbsp;<a href="supplier.php?suppId=<?php echo self::$_var['supplier']['supplier_id']; ?>" target="_blank">查看店铺</a>&nbsp;&nbsp;
                    <!--<a href="supplier.php?act=view&id=<?php echo self::$_var['supplier']['supplier_id']; ?>" title="查看佣金">查看佣金</a>--><?php else: ?>&nbsp;&nbsp;<?php endif; ?>&nbsp;&nbsp;<a href="javascript:del_supplier(<?php echo self::$_var['supplier']['supplier_id']; ?>)" title="删除店铺">删除店铺</a>&nbsp;&nbsp;
                    <?php if (self::$_var['supplier']['status'] == 1): ?>
                    <a href="index.php?act=supintegral&op=jifen_detail&id=<?php echo self::$_var['supplier']['supplier_id']; ?>"  title="积分详情">积分详情</a>&nbsp;&nbsp;
                    <a href="index.php?act=supintegral&op=reduce_jifen&id=<?php echo self::$_var['supplier']['supplier_id']; ?>"  title="扣除积分">扣除积分</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td class="no-records" colspan="11"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
            <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
        </table>
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

<script type="text/javascript" language="javascript">
    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.act = 'supplier';
    listTable.query = 'query';
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
        listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    onload = function ()
    {
        // 开始检查订单
        startCheckOrder();
    }
    /**
     * 搜索供货商
     */
    function searchSupplier()
    {
        listTable.filter['supplier_name'] = Utils.trim(document.forms['searchForm'].elements['supplier_name'].value);
        listTable.filter['rank_name'] = document.forms['searchForm'].elements['rank_name'].value;
        listTable.filter['page'] = 1;
        listTable.loadList();
    }
    function del_supplier(suppid) {
        var url = "index.php?act=supplier&op=delete&id=" + suppid;
        if (confirm('删除后，相关商品，佣金及其它店铺信息将永久删除，确定删除？')) {
            self.location.href = url;
        }
    }
//    function batch_export()
//    {
//        var supplier_name = Utils.trim(document.forms['searchForm'].elements['supplier_name'].value);
//        var rank_id = Utils.trim(document.forms['searchForm'].elements['rank_name'].value);
//        return location.href = 'index.php?act=supplier&op=export&supplier_name=' + supplier_name + '&rank_id=' + rank_id;
//    }
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>