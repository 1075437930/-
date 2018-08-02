<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
    <form method="post" id="form_met" action="index.php?act=update_goods&op=update_goods" name="theForm" enctype="multipart/form-data" >
        <table cellspacing="1"
cellpadding="3" width="100%">

            <tr>
                <td class="label"><?php echo self::$_var['lang']['year']; ?>:</td>
                <td>
                    <select name="years">
                        <?php $_from = self::$_var['years']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
                        <?php if (self::$_var['val'] == 2018): ?>
                           <option value="<?php echo self::$_var['val']; ?>" selected><?php echo self::$_var['val']; ?></option>
                            <?php else: ?>
                           <option value="<?php echo self::$_var['val']; ?>"><?php echo self::$_var['val']; ?></option>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                    </select>
                    <?php echo self::$_var['lang']['require_field']; ?>
                </td>ss
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['mon']; ?>:</td>
                <td>
                    <select name="mons">
                        <?php $_from = self::$_var['mons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
                        <?php if (self::$_var['val'] == 6): ?>
                        <option value="<?php echo self::$_var['val']; ?>" selected><?php echo self::$_var['val']; ?></option>
                            <?php else: ?>
                        <option value="<?php echo self::$_var['val']; ?>"><?php echo self::$_var['val']; ?></option>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                    </select>
                    <?php echo self::$_var['lang']['require_field']; ?>
                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['leixing']; ?>:</td>
                <td>
                    <select name="leixing">

                        <option value="xia" ><?php echo self::$_var['lang']['xia']; ?></option>

                        <option value="zero"><?php echo self::$_var['lang']['zero']; ?></option>

                    </select>
                    <?php echo self::$_var['lang']['require_field']; ?>
                </td>
            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['zm']; ?>:</td>
                <td><input type="text" name="zm" id="attr_name" maxlength="60" value="" /><?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>

            <tr>
                <td colspan="2" align="center"><br />
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
            alert('首字母不能为空');
            return false
        }
    }

</script>