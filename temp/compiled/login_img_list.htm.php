<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<h1>
    <span class="action-span"><a href="index.php?act=app_seting&op=add_login_img"><?php echo self::$_var['lang']['add_img']; ?></a></span>

</h1>
<div class="list-div" id="listDiv">
    <?php endif; ?>
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th><span><a href="javascript:listTable.sort('id', 'DESC'); " title=""><?php echo self::$_var['lang']['bianhao']; ?></a></span></th>
            <th><?php echo self::$_var['lang']['background_img']; ?></th>
            <th><?php echo self::$_var['lang']['action']; ?></th>
        </tr>
        <?php $_from = self::$_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['list']):
?>
        <tr width="100%" id="tr_<?php echo self::$_var['list']['id']; ?>">
            <td width="30%" align="center"><span><?php echo self::$_var['list']['id']; ?></span></td>
            <td width="50%" align="center" class="first-cell"><img src="<?php echo self::$_var['list']['imgurl']; ?>" alt=""></td>
            <td width="20%" align="center">
                <a href="index.php?act=app_seting&op=remove_login_img&id=<?php echo self::$_var['list']['id']; ?>" title="移除">
                    <img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" />
                </a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_img']; ?></td></tr>
        <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
        <tr>
            <td align="right" nowrap="true" colspan="6"><?php echo self::fetch('page.htm'); ?></td>
        </tr>
    </table>

    <?php if (self::$_var['full_page']): ?>
</div>


<script type="text/javascript" language="JavaScript">
    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.act = "app_seting";
    listTable.query = "login_img_query";
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    

</script>

<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>
