<?php echo self::fetch('pageheader.htm'); ?>
<!--<div class="main-div">-->
<div class="list-div" id="listDiv">
    <form method="post" id="form_met" action="index.php?act=pricecut&op=pricecut_edit" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">

            <input type="hidden" name="pricecut_id" value="<?php echo self::$_var['res']['pricecut_id']; ?>"/>
            <tr>
                <th colspan="10">降价通知</th>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['phone']; ?>:</td>
                <td><?php echo self::$_var['res']['mobile']; ?></td>
                <td class="label"><?php echo self::$_var['lang']['goods_name']; ?>:</td>
                <td><?php echo self::$_var['goods_name']; ?></td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['email']; ?>:</td>
                <td><?php echo self::$_var['res']['email']; ?></td>
                <td class="label"><?php echo self::$_var['lang']['goods_price']; ?>:</td>
                <td><?php echo self::$_var['goods_price']; ?></td>
            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['requ_time']; ?>:</td>
                <td><?php echo self::$_var['res']['add_time']; ?></td>
                <td class="label"><?php echo self::$_var['lang']['tongzhi_price']; ?>:</td>
                <td><?php echo self::$_var['res']['price']; ?></td>
            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['beizhu']; ?>:</td>
                <td colspan="7">
                    <textarea rows="5" cols="80" name="remark" id="attr_name"><?php echo self::$_var['res']['remark']; ?></textarea>
                    <?php echo self::$_var['lang']['require_field']; ?></td>

            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['tongzhi_state']; ?>:</td>
                <td colspan="7">

                    <select name="status">
                        <?php if (self::$_var['res']['status'] == 0): ?>
                          <option value="0" selected>未通知</option>
                            <?php else: ?>
                        <option value="0" >未通知</option>
                        <?php endif; ?>
                        <?php if (self::$_var['res']['status'] == 1): ?>
                        <option value="1" selected>系统通知（失败）</option>
                        <?php else: ?>
                        <option value="1" >系统通知（失败）</option>
                        <?php endif; ?>
                        <?php if (self::$_var['res']['status'] == 2): ?>
                        <option value="2" selected>系统通知（成功）</option>
                        <?php else: ?>
                        <option value="2" >系统通知（成功）</option>
                        <?php endif; ?>
                        <?php if (self::$_var['res']['status'] == 3): ?>
                        <option value="3" selected>人工通知</option>
                        <?php else: ?>
                        <option value="3" >人工通知</option>
                        <?php endif; ?>
                    </select>
                    <?php echo self::$_var['lang']['require_field']; ?></td>

            </tr>

            <tr>
                <td colspan="10" align="center"><br />
                    <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />

                </td>
            </tr>

        </table>
    </form>
</div>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>

<?php echo self::fetch('pagefooter.htm'); ?>

<script>
    var inp_name=document.getElementById('attr_name');

    var form= document.getElementById('form_met');

    form.onsubmit=function(){
        if(!inp_name.value){
            alert('备注不能为空');
            return false
        }
    }


</script>

