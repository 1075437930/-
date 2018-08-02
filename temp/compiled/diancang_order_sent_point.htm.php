
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'validator.js,../js/transport.org.js')); ?>
<div class="main-div">
    <form method="post" action="index.php" name="theForm" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">用户名：</td>
                <td><input type="text" value="<?php echo self::$_var['alias']; ?>" disabled="disabled" ></td>
            </tr>
            <tr>
                <td class="label">点券列表</td>
                <td>
                    <select name="point" id="point" onchange="select_point()">
                        <option value="0" selected>请选择点券</option>
                        <?php $_from = self::$_var['point_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'point');if (count($_from)):
    foreach ($_from AS self::$_var['point']):
?>
                             <option value="<?php echo self::$_var['point']['point_id']; ?>" data="<?php echo self::$_var['point']['point_pic']; ?>" ><?php echo self::$_var['point']['point_name']; ?></option>
                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">金额：</td>
                <td><input type="text" value="0" disabled="disabled" id="money" ></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />
                    <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />
                    <input type="hidden" name="capital_userid" value="<?php echo self::$_var['capital_userid']; ?>" />
                    <input type="hidden" name="dc_order_id" value="<?php echo self::$_var['dc_order_id']; ?>" />
                     <input type="hidden" name="user_id" value="<?php echo self::$_var['user_id']; ?>" />
                     <input type="hidden" name="act" value="dcorder" />
                    <input type="hidden" name="op" value="<?php echo self::$_var['insert_or_update']; ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>


<script language="JavaScript">
<!--
    onload = function ()
    {
        // 开始检查订单
        startCheckOrder();
    }
    function select_point(){
        var obj = document.getElementById('point');
        var money = obj.options[obj.selectedIndex].getAttribute('data');
        document.getElementById('money').value = money;
    }
//-->
</script>

<?php echo self::fetch('pagefooter.htm'); ?>
