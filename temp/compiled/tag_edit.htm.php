<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
    <form method="post" id="form_met" action="index.php?act=tag_manage&op=tag_edit" name="theForm" enctype="multipart/form-data" >
        <table cellspacing="1"
cellpadding="3" width="100%">

            <input type="hidden" name="tag_class_id" value="<?php echo self::$_var['tag_class_id']; ?>"/>

            <input type="hidden" name="tag_id" value="<?php echo self::$_var['info']['tag_id']; ?>"/>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['tag_name']; ?>:</td>
               <td><input type="text" name="tag_name" id="attr_name" maxlength="60" value="<?php echo self::$_var['info']['tag_words']; ?>" /><?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['tag_class']; ?>:</td>
                <td>
                    <select name="tag_class">
                        <?php $_from = self::$_var['class_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
                            <?php if (self::$_var['val'] [ tag_class_id ] == self::$_var['info'] [ tag_class_id ]): ?>
                                <option value="<?php echo self::$_var['val']['tag_class_id']; ?>" selected><?php echo self::$_var['val']['class_name']; ?></option>
                                <?php else: ?>
                                 <option value="<?php echo self::$_var['val']['tag_class_id']; ?>"><?php echo self::$_var['val']['class_name']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                    </select>
                    <?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['tag_zs']; ?></td>
                <td><textarea  name="tag_zs" cols="60" rows="4"  ><?php echo self::$_var['info']['tag_cent']; ?></textarea></td>

            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['tag_keyword']; ?></td>
                <td><textarea  name="tag_keyword" cols="60" rows="4"  ><?php echo self::$_var['info']['tag_key']; ?></textarea>
                    <?php echo self::$_var['lang']['tag_keyword1']; ?>
                </td>

            </tr>

            <tr>
                <td colspan="2" align="center"><br />
                    <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />
                    <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />

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
            alert('类型名称不能为空');
            return false
        }
    }

</script>