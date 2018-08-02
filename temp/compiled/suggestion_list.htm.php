


<?php if (self::$_var['fullpage']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php endif; ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<form method="POST" action="user_msg.php?act=batch_drop" name="listForm" onsubmit="return confirm_bath()">

    

    <div class="list-div" id="listDiv">

        <table cellpadding="3" cellspacing="1">

            <tr>

                <th><?php echo self::$_var['lang']['yijian_id']; ?></th>

                <th><?php echo self::$_var['lang']['user_name']; ?></th>

                <th><?php echo self::$_var['lang']['yijian_title']; ?></th>

                <th><a href="javascript:listTable.sort('yijian_type'); "><?php echo self::$_var['lang']['yijian_type']; ?></a></th>

                <th><?php echo self::$_var['lang']['yijian_time']; ?></th>

                <th><?php echo self::$_var['lang']['yijian_admin']; ?></th>

                <th><?php echo self::$_var['lang']['user_mags']; ?></th>

                <th><?php echo self::$_var['lang']['caozuo']; ?></th>

            </tr>

            <?php $_from = self::$_var['list_all']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'yijian');if (count($_from)):
    foreach ($_from AS self::$_var['yijian']):
?>

            <tr>

                <td><?php echo self::$_var['yijian']['yijian_id']; ?></td>

                <td align="center">
                    <?php if (self::$_var['yijian']['alias']): ?>
                    <?php echo self::$_var['yijian']['alias']; ?>
                    <?php else: ?>    
                    <?php echo self::$_var['yijian']['user_name']; ?>
                    <?php endif; ?>

                </td>

                <td align="left"><?php echo htmlspecialchars(sub_str(self::$_var['yijian']['yijian_commot'],40)); ?></td>

                <td align="center"><?php echo self::$_var['yijian']['typename']; ?></td>

                <td align="center"  nowrap="nowrap"><?php echo self::$_var['yijian']['add_time']; ?></td>

                <td align="center"><?php echo self::$_var['yijian']['adminname']; ?></td>

                <td align="center"><?php echo self::$_var['yijian']['huifucommot']; ?></td>

                <td align="center">

                    <?php if (self::$_var['yijian']['type'] == 0): ?>

                    <a href="index.php?act=suggestion&op=dispose&id=<?php echo self::$_var['yijian']['yijian_id']; ?>" title="<?php echo self::$_var['lang']['chuli']; ?>">

                        <img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />

                    </a>

                    <a href="index.php?act=suggestion&op=closed&id=<?php echo self::$_var['yijian']['yijian_id']; ?>"   title="<?php echo self::$_var['lang']['guanbi']; ?>">

                        <img src="templates/default/images/book_open.gif" border="0" height="16" width="16">

                    </a>

                    <?php else: ?>

                    <a href="index.php?act=suggestion&op=remove&id=<?php echo self::$_var['yijian']['yijian_id']; ?>"   title="<?php echo self::$_var['lang']['shanchu']; ?>">

                        <img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16">

                    </a>

                    <?php endif; ?>

                </td>

            </tr>

            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

        </table>

        <table id="page-table" cellspacing="0">

            <tr>

                <td><div>



                        <td align="right" nowrap="true">

                            <?php echo self::fetch('page.htm'); ?>

                        </td>

            </tr>

        </table>



    </div>

    

</form>
<script type="text/javascript">
       listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.query = "query";
    listTable.act = "suggestion";
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?> 

</script>


<?php echo self::fetch('pagefooter.htm'); ?>

