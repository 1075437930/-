
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<form method="post" action="" name="listForm">
    
    <div class="list-div" id="listDiv">
        <?php endif; ?>
        <table cellspacing='1' id="list-table">
            <tr>
                <th>活动期号</th>
                <th>团队额外奖励天数</th>
                <th>团队总金额上限(万)</th>
                <th>团队人数上限</th>
                <th>返现时间（天）</th>
                <th>奖励区间比例</th>
                <th>队长额外奖励比例</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>是否开放</th>
                <th>操作</th>
            </tr>
            <?php $_from = self::$_var['team_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'team');if (count($_from)):
    foreach ($_from AS self::$_var['team']):
?>
            <tr>
                <td class="first-cell" ><?php echo self::$_var['team']['stage_sn']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['team_reward_day']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['team_money_limit']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['team_person_limit']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['back_day']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['extra_ratio']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['captain_radio']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['start_time']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['end_time']; ?></span></td>
                <td class="first-cell" ><span><?php echo self::$_var['team']['is_open']; ?></span></td>
                <td align="center">	 
                    <a href="index.php?act=dcgroup&op=edit&amp;stage_id=<?php echo self::$_var['team']['id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>">
                        <img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />编辑
                    </a>
                    <a href="index.php?act=dcgroup&op=close_stage&amp;stage_id=<?php echo self::$_var['team']['id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>">
                        <img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />关闭
                    </a>
                    <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['team']['id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>', 'remove')" title="<?php echo self::$_var['lang']['remove']; ?>">
                        <img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16">删除
                    </a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td class="no-records" colspan="15">暂无内容</td></tr>
            <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
            <tr>
                <td align="right" nowrap="true" colspan="15"><?php echo self::fetch('page.htm'); ?></td>
            </tr>
        </table>
        <?php if (self::$_var['full_page']): ?>
    </div>
    
</form>
<script type="Text/Javascript" language="JavaScript">
    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.act = "dcgroup";
    listTable.query = "lists_query";
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    
    onload = function()
    {
    startCheckOrder();
    }
    
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
