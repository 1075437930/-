

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="js/calendar.php?lang=<?php echo self::$_var['cfg_lang']; ?>"></script>

<?php echo self::smarty_insert_scripts(array('files'=>'js/validator.js,js/transport.org.js,js/utils.js,js/listtable.js')); ?>

<div class="list-div">
    <div class="important">
        <ul class="import">
            <li class="import_1">
                <div class="module">
                    <i></i>
                    <div class="detail">
                        <strong style="color:#FA841E; text-decoration:none"><?php echo self::$_var['pointnum']['zong_send']; ?>/个</strong>
                        <span style="font-size: 12px">已发放投资卷</span>
                    </div>
                </div>
            </li>
            <li class="import_2">
                <div class="module">
                    <i></i>
                    <div class="detail">
                        <strong style="color:#FA841E; text-decoration:none"><?php echo self::$_var['pointnum']['zong_make']; ?>/个</strong>
                        <span style="font-size: 12px">已使用投资卷</span>
                    </div>
                </div>
            </li>
            <li class="import_3">
                <div class="module">
                    <i></i>
                    <div class="detail">
                        <strong style="color:#FA841E; text-decoration:none"><?php echo self::$_var['pointnum']['zong_sendpic']; ?>/元</strong>
                        <span style="font-size: 12px">总发放卷金额</span>
                    </div>
                </div>
            </li>
            <li class="import_4">
                <div class="module">
                    <i></i>
                    <div class="detail">
                        <strong style="color:#FA841E; text-decoration:none"><?php echo self::$_var['pointnum']['zong_makepic']; ?>/元</strong>
                        <span style="font-size: 12px">总使用卷金额</span>
                    </div>
                </div>
            </li>
            <li class="import_5">
                <div class="module">
                    <i></i>
                    <div class="detail">
                        <strong style="color:#FA841E; text-decoration:none"><?php echo self::$_var['pointnum']['zong_pic']; ?>/元</strong>
                        <span style="font-size: 12px">总带来资金</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<br />




<div class="list-div" id="listDiv">
    <?php endif; ?>
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>描述</th>
            <th>金额</th>
            <th>过期天数</th>
            <th>发放个数</th>
            <th>已用个数</th>
            <th>点卷类型</th>
            <th>使用范围</th>
            <th>发放类型</th>
            <th>是否开启</th>
            <th><?php echo self::$_var['lang']['handler']; ?></th>
        </tr>
        <?php $_from = self::$_var['pointlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'poeitm');if (count($_from)):
    foreach ($_from AS self::$_var['poeitm']):
?>
        <tr width="100%">
            <td width="3%" align="center"><span><?php echo self::$_var['poeitm']['point_id']; ?></span></td>
            <td width="15%" ><span><?php echo self::$_var['poeitm']['point_name']; ?></span></td>
            <td width="25%" ><span><?php echo self::$_var['poeitm']['point_brief']; ?></span></td>
            <td width="5%" align="center"><span><?php echo self::$_var['poeitm']['point_pic']; ?></span></td>
            <td width="5%" align="center"><span><?php echo self::$_var['poeitm']['valid_time']; ?></span></td>
            <td width="5%" align="center"><span><?php echo self::$_var['poeitm']['send']; ?></span></td>
            <td width="5%" align="center"><span><?php echo self::$_var['poeitm']['make']; ?></span></td>
            <td width="5%" align="center"><span><?php echo self::$_var['poeitm']['type_name']; ?></span></td>
            <td width="5%" align="center"><span><?php echo self::$_var['poeitm']['scope_name']; ?></span></td>
            <td width="5%" align="center"><span><?php echo self::$_var['poeitm']['send_name']; ?></span></td>
            <td width="10%" align="center"><img src="templates/default/images/<?php if (self::$_var['poeitm']['start']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_start', <?php echo self::$_var['poeitm']['point_id']; ?>)" /></td>
            <td align="center" width="10%">
                <span>
                    <a href="index.php?act=vouchers&op=edit_point&pointid=<?php echo self::$_var['poeitm']['point_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />编辑</a>
                    <?php if (self::$_var['poeitm']['send_start'] == 1): ?>	
                    <a href="index.php?act=vouchers&op=sent_point&pointid=<?php echo self::$_var['poeitm']['point_id']; ?>" title="发放"><img src="templates/default/images/icon_copy.gif" width="16" height="16" border="0" />发放</a>
                    <?php endif; ?>
                </span>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td class="no-records" colspan="11">暂无内容,请添加投资卷</td></tr>
        <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
        <tr>
            <td align="right" nowrap="true" colspan="11"><?php echo self::fetch('page.htm'); ?></td>
        </tr>
    </table>

    <?php if (self::$_var['full_page']): ?>
</div>

<script language="JavaScript">
    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.query = "query";
    listTable.act = "vouchers";
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>