<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
    <form method="post" id="form_met" action="index.php?act=tag_manage&op=class_add" name="theForm" enctype="multipart/form-data" >
        <table cellspacing="1"
cellpadding="3" width="100%">

            <input type="hidden" name="goods_type" value="1"/>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['class_name']; ?>:</td>
                <td><input type="text" name="type_name" id="attr_name" maxlength="60" value="" /><?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['class_zs']; ?></td>
                <td><textarea  name="type_group" cols="60" rows="4"  ></textarea></td>

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