<?php if (self::$_var['full_page']): ?>



<?php echo self::fetch('pageheader.htm'); ?> 
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/placeholder.js"></script>
<div class="form-div">

    <form action="javascript:searchUser()" name="searchForm">

        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />

        &nbsp;<?php echo self::$_var['lang']['label_level_name']; ?>

        <select name="user_rank">

            <option value="0"><?php echo self::$_var['lang']['all_option']; ?></option>

            <?php echo self::html_options(array('options'=>self::$_var['user_ranks'])); ?>

        </select>

        &nbsp;<?php echo self::$_var['lang']['label_pay_points_gt']; ?>&nbsp;

        <input type="text" name="pay_points_gt" size="8" style="min-width: 150px;"/>

        &nbsp;<?php echo self::$_var['lang']['label_pay_points_lt']; ?>&nbsp;

        <input type="text" name="pay_points_lt" size="10" style="min-width: 150px;" />

        <?php echo self::$_var['lang']['label_user_name']; ?>&nbsp;

        <span style="position:relative"><input type="text" name="keyword" placeholder="手机号/用户名/邮箱" /></span>

        <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_search']; ?>" />

    </form>

</div>

<form method="POST" action="index.php" name="listForm" onsubmit="return confirm_bath()">

    

    <div class="list-div" id="listDiv">

        <?php endif; ?>

        

        <table cellpadding="3" cellspacing="1">

            <tr>

                <th>

                    <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">

                    <a href="javascript:listTable.sort('user_id'); "><?php echo self::$_var['lang']['record_id']; ?></a>

                    <?php echo self::$_var['sort_user_id']; ?>

                </th>

                <th>

                    <a href="javascript:listTable.sort('user_name'); "><?php echo self::$_var['lang']['username']; ?></a>

                    <?php echo self::$_var['sort_user_name']; ?>

                </th>


                <th>

                    <a href="javascript:listTable.sort('mobile_phone'); "><?php echo self::$_var['lang']['is_validated']; ?>&nbsp;|&nbsp;<?php echo self::$_var['lang']['mobile_phone']; ?></a>

                    <?php echo self::$_var['sort_mobile_phone']; ?>

                </th>

                

                <th><?php echo self::$_var['lang']['user_money']; ?></th>

                <th><?php echo self::$_var['lang']['frozen_money']; ?></th>

                <th><?php echo self::$_var['lang']['rank_points']; ?></th>

                <th><?php echo self::$_var['lang']['pay_points']; ?></th>

                <th><?php echo self::$_var['lang']['taoyu_money']; ?></th>

                <th>

                    <a href="javascript:listTable.sort('reg_time'); "><?php echo self::$_var['lang']['reg_date']; ?></a>

                    <?php echo self::$_var['sort_reg_time']; ?>

                </th>


                <th><?php echo self::$_var['lang']['handler']; ?></th>

            <tr><?php $_from = self::$_var['user_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'user');if (count($_from)):
    foreach ($_from AS self::$_var['user']):
?>

            <tr>

                <td>

                    <input type="checkbox" name="checkboxes[]" value="<?php echo self::$_var['user']['user_id']; ?>" notice="<?php if (self::$_var['user']['user_money'] != 0): ?>1<?php else: ?>0<?php endif; ?>" />

                    <?php echo self::$_var['user']['user_id']; ?>

                </td>

                <td class="first-cell">

                    <span style="margin-bottom: 2px; line-height: 14px; display: block;">
                        <?php if (self::$_var['user']['alias'] == ''): ?>
                        <?php echo htmlspecialchars(self::$_var['user']['user_name']); ?>
                        <?php else: ?>
                        <?php echo htmlspecialchars(self::$_var['user']['alias']); ?>
                        <?php endif; ?>
                    </span>

                    <span style="border: 1px #6DD26A solid; background-color: #6DD26A; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;">

                        <?php if (self::$_var['user']['froms'] == 'pc'): ?>

                        PC会员

                        <?php elseif (self::$_var['user']['froms'] == 'mobile'): ?>

                        微商城会员

                        <?php elseif (self::$_var['user']['froms'] == 'app'): ?>

                        APP会员

                        <?php endif; ?>

                    </span>

                    <?php if (self::$_var['user']['level_name'] != null): ?>

                    <span style="margin-left: 5px; border: 1px #FBB24E solid; background-color: #FBB24E; padding: 1px 2px 0px 2px; color: white; display: inline; border-radius: 2px;"> <?php echo self::$_var['user']['level_name']; ?> </span><br/>

                    <?php endif; ?>

                </td>

                <td align="center">

                    <?php if (self::$_var['user']['mobile_phone'] != null): ?><?php if (self::$_var['user']['user_mobile_bind']): ?>

                    <img src="templates/default/images/yes.gif">

                    <?php else: ?>

                    <img src="templates/default/images/no.gif">

                    <?php endif; ?><?php endif; ?>

                    <!-- <span onclick="listTable.edit(this, 'edit_mobile_phone', <?php echo self::$_var['user']['user_id']; ?>)"><?php echo self::$_var['user']['mobile_phone']; ?></span> -->

                </td>

                <td><?php echo self::$_var['user']['user_money']; ?></td>

                <td><?php echo self::$_var['user']['frozen_money']; ?></td>

                <td><?php echo self::$_var['user']['rank_points']; ?></td>

                <td><?php echo self::$_var['user']['pay_points']; ?></td>

                <td><?php echo self::$_var['user']['taoyu_money']; ?></td>

                <td align="center"><?php echo self::$_var['user']['reg_time']; ?></td>

                <td align="center">

                    <a href="index.php?act=users&op=edit&id=<?php echo self::$_var['user']['user_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>">

                        <img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />

                    </a>

                    <a href="index.php?act=users&op=address_list&id=<?php echo self::$_var['user']['user_id']; ?>" title="<?php echo self::$_var['lang']['address_list']; ?>">

                        <img src="templates/default/images/book_open.gif" border="0" height="16" width="16" />

                    </a>
                    <a href="index.php?act=order&op=lists&user_id=<?php echo self::$_var['user']['user_id']; ?>" title="<?php echo self::$_var['lang']['view_order']; ?>">

                        <img src="templates/default/images/icon_view.gif" border="0" height="16" width="16" />

                    </a>
                    <a href="index.php?act=account_log&op=lists&user_id=<?php echo self::$_var['user']['user_id']; ?>" title="<?php echo self::$_var['lang']['view_deposit']; ?>">

                        <img src="templates/default/images/icon_account.gif" border="0" height="16" width="16" />

                    </a>

                    <a href="javascript:confirm_redirect('<?php if (self::$_var['user']['user_money'] != 0): ?><?php echo self::$_var['lang']['still_accounts']; ?><?php endif; ?><?php echo self::$_var['lang']['remove_confirm']; ?>', 'index.php?act=users&op=remove&id=<?php echo self::$_var['user']['user_id']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>">

                        <img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" />

                    </a>

                </td>

            </tr>

            <?php endforeach; else: ?>

            <tr>

                <td class="no-records" colspan="11"><?php echo self::$_var['lang']['no_records']; ?></td>

            </tr>

            <?php endif; unset($_from); ?><?php self::pop_vars(); ?>

            <tr>

                <td colspan="2">

                    <input type="hidden" name="act" value="users" />

                    <input type="hidden" name="op" value="batch_remove" />

                    <input type="submit" id="btnSubmit" value="<?php echo self::$_var['lang']['button_remove']; ?>" disabled="true" class="button" />

                </td>

                <td align="right" nowrap="true" colspan="11"><?php echo self::fetch('page.htm'); ?></td>

            </tr>

        </table>

        <?php if (self::$_var['full_page']): ?>

    </div>

    

</form>

<script type="text/javascript" language="JavaScript">


    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.act = 'users';
    listTable.query = 'query';
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>




            onload = function()

            {

            document.forms['searchForm'].elements['keyword'].focus();
            // 开始检查订单

            startCheckOrder();
            }



    /**
     
     * 搜索用户
     
     */

    function searchUser()

    {

    listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['rank'] = document.forms['searchForm'].elements['user_rank'].value;
    listTable.filter['pay_points_gt'] = Utils.trim(document.forms['searchForm'].elements['pay_points_gt'].value);
    listTable.filter['pay_points_lt'] = Utils.trim(document.forms['searchForm'].elements['pay_points_lt'].value);
    listTable.filter['page'] = 1;
    listTable.loadList();
    }



    function confirm_bath()

    {

    userItems = document.getElementsByName('checkboxes[]');
    cfm = '<?php echo self::$_var['lang']['list_remove_confirm']; ?>';
    for (i = 0; userItems[i]; i++)

    {

    if (userItems[i].checked && userItems[i].notice == 1)

    {

    cfm = '<?php echo self::$_var['lang']['list_still_accounts']; ?>' + '<?php echo self::$_var['lang']['list_remove_confirm']; ?>';
    break;
    }

    }



    return confirm(cfm);
    }


</script>
<?php echo self::fetch('pagefooter.htm'); ?> <?php endif; ?>

