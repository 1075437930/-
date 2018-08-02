<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
    <form method="post" id="form_met" action="index.php?act=mail_templates&op=templates_edit" name="theForm" enctype="multipart/form-data" >
        <table cellspacing="1"
cellpadding="3" width="100%">

            <input type="hidden" name="template_id" value="<?php echo self::$_var['res']['template_id']; ?>"/>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['tem_key']; ?>:</td>
               <td><input type="text" name="tem_key"  maxlength="60" value="<?php echo self::$_var['res']['template_code']; ?>"  id="inp1"/><?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>


            <tr>
                <td class="label"><?php echo self::$_var['lang']['tem_name']; ?>:</td>
                <td><input type="text" name="tem_name"  maxlength="60" value="<?php echo self::$_var['res']['template_subject']; ?>" id="inp2"/><?php echo self::$_var['lang']['require_field']; ?> </td>

            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['tem_body']; ?>:</td>
                <td><textarea  name="template_content" cols="60" rows="4"  id="inp3"><?php echo self::$_var['res']['template_content']; ?></textarea><?php echo self::$_var['lang']['dui']; ?>
                </td>

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
    var inp_name=document.getElementById('inp1');
    var inp_name2=document.getElementById('inp2');
    var inp_name3=document.getElementById('inp3');

    var form= document.getElementById('form_met');

    form.onsubmit=function(){
        if(!inp_name.value){
            alert('key不能为空');
            return false
        }
        if(!inp_name2.value){
            alert('名称不能为空');
            return false
        }

        if(!inp_name3.value){
            alert('模板内容不能为空');
            return false
        }

    }

</script>