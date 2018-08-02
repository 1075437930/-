
<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
    <form method="post" id="form_met" action="index.php?act=attribute&op=attr_add" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">
            <input type="hidden" name="attr_id_update" value="<?php echo self::$_var['cat_id']; ?>"/>


            <tr>
                <td class="label"><?php echo self::$_var['lang']['edit_name']; ?>:</td>
                <td><input type="text" name="attr_name" id="attr_name" maxlength="60" value="" /><?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['edit_type']; ?></td>
                <td>

                     <select name="attr_type">
                         <?php $_from = self::$_var['type_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
                              <?php if (self::$_var['val'] [ cat_id ] == self::$_var['cat_id']): ?>
                              <option value="<?php echo self::$_var['val']['cat_id']; ?>" selected><?php echo self::$_var['val']['cat_name']; ?></option>
                                <?php else: ?>
                                   <option value="<?php echo self::$_var['val']['cat_id']; ?>"><?php echo self::$_var['val']['cat_name']; ?></option>
                              <?php endif; ?>
                         <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                     </select>
                    <?php echo self::$_var['lang']['require_field']; ?>
                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['edit_js']; ?></td>
                <td>

                      <input type="radio" name="js" value="0" checked>不需要检索

                    <input type="radio" name="js" value="1" >关键字检索

                    <input type="radio" name="js" value="2">范围检索

                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['edit_gl']; ?></td>
                <td>

                       <input type="radio" name="gl" value="0" checked>否


                         <input type="radio" name="gl" value="1" >是

                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['edit_ischoose']; ?></td>
                <td>

                      <input type="radio" name="ischoose" value="0" checked>唯一属性



                    <input type="radio" name="ischoose" value="1" >单选属性


                    <input type="radio" name="ischoose" value="2">复选属性

                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['edit_fs']; ?></td>
                <td>

                        <input type="radio" name="fs" value="0" checked>手工录入



                    <input type="radio" name="fs" value="1"> 从下面的列表中选择（一行代表一个可选值）

                    <input type="radio" name="fs" value="2">多行文本框

                </td>
            </tr>


            <tr>
                <td class="label"><?php echo self::$_var['lang']['edit_list']; ?></td>
                <td>

                    <textarea  name="list" cols="60" rows="4"  disabled></textarea>
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
            alert('属性名称不能为空');
            return false
        }
    }

</script>


<script>
    $(function(){

        $("input[name='fs']").click(function(){

            if($(this).val()==0){

                $('textarea[name=list]').attr('disabled','true');
            }else{

                $('textarea[name=list]').removeAttr('disabled')
            }


        })
    })
</script>