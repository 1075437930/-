<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/jquery-1.10.2.min_65682a2.js')); ?>
<div class="main-div">
    <form method="post" id="form_met" action="index.php?act=sms&op=sms_add" name="theForm" enctype="multipart/form-data" >
        <table cellspacing="1"
cellpadding="3" width="100%">

            <input type="hidden" name="goods_type" value="1"/>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['message_title']; ?>:</td>
               <td><input type="text" name="message_title" id="attr_name" maxlength="60" value="" /><?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>

            <tr>
                <td class="label"><?php echo self::$_var['lang']['to_member_name']; ?>:</td>
                <td>
                    <textarea name="to_member_name" rows="5" cols="20" id="inp2"></textarea>
                    <?php echo self::$_var['lang']['require_field']; ?>一行代表一个用户</td>
            </tr>



            <tr>
                <td class="label"><?php echo self::$_var['lang']['templates']; ?>:</td>
                <td>
                    <select name="template_id" id="inp4">
                        <option value="">请选择</option>
                        <?php $_from = self::$_var['res']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>
                        <?php if (! empty ( self::$_var['val'] )): ?>
                        <option value="<?php echo self::$_var['val']['template_id']; ?>"><?php echo self::$_var['val']['template_subject']; ?></option>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                    </select>
                    <?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>

            <tbody class="kz">
                <!--<tr >-->
                    <!--<td class="label">键:</td>-->
                    <!--<td><input type="text"  value="值"></td>-->
                <!--</tr>-->

                <!--<tr >-->
                    <!--<td class="label">键:</td>-->
                    <!--<td><input type="text"  value="值"></td>-->
                </tr>
            </tbody>
            <input type="hidden" name="tol" value="1"/>
            <input type="hidden" name="tolval" />

            <tr>
                <td class="label"><?php echo self::$_var['lang']['message_body']; ?></td>
                <td><textarea   cols="120" rows="10"  id="xs" disabled></textarea></td>
                <textarea name="sms_body" id="inp5"  style="opacity: 0"></textarea>
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
    $(function(){
        $('select[name=template_id]').change(function(){
            tm_id=$(this).val();
            $.ajax({
                url:"index.php?act=sms&op=get_tem&template_id="+tm_id,
                method:"get",
                success:function(rev){

                    json_obj=JSON.parse(rev);


                    $('textarea[name=sms_body]').html(json_obj.template_content);
                    $('#xs').html(json_obj.template_content);

                    $('input[name=message_title]').val(json_obj.message_title);

                    var str='';
                    for(i=0;i<json_obj.gr.length;i++){
                        if(i==0){
                            str+="<tr><td class='label'>"+json_obj.gr[i]+"</td><td><input type='text' name='"+json_obj.gr[i]+"'  value='淘玉商城'></td></tr>";
                        }else{
                            str+="<tr><td class='label'>"+json_obj.gr[i]+"</td><td><input type='text' name='"+json_obj.gr[i]+"'  value=''></td></tr>";
                        }

                    }
                    $('.kz').html(str);

                }
            })
        });


        var inp_name=document.getElementById('attr_name');
        var inp_name2=document.getElementById('inp2');
        var inp_name3=document.getElementById('inp3');
        var inp_name4=document.getElementById('inp4');
        var inp_name5=document.getElementById('inp5');

        var form= document.getElementById('form_met');

        form.onsubmit=function(){
            if(!inp_name.value){
                alert('标题不能为空');
                return false
            }
            if(!inp_name2.value){
                alert('用户名称不能为空');
                return false
            }
            if(!inp_name3.value){
                alert('请选择短信类型');
                return false
            }
            if(!inp_name4.value){
                alert('请选择短信模板');
                return false
            }


            var str1='';
            var str2='';
            $('.kz tr').each(function(){
                str1+=$(this).find('input[type=text]').val()+',';
                str2+=$(this).find('input[type=text]').attr('name')+','
            });

            $('input[name=tol]').val(str2);
            $('input[name=tolval]').val(str1);
        };

        $(document).on('input propertychange blur',".kz tr input",function(){
            var str1='';
            var str2='';
            $('.kz tr').each(function(){
                str1+=$(this).find('input[type=text]').val()+',';
                str2+=$(this).find('input[type=text]').attr('name')+','
            });

            $('input[name=tol]').val(str2);
            $('input[name=tolval]').val(str1);
        });


//
//    $('input[type=reset]').click(function(){
//        var str2=''
//        $('.kz tr').each(function(){
////            str1+=$(this).find('input[type=text]').val()+',';
//            str2+=$(this).find('input[type=text]').attr('name')+','
//        });
//        $('input[name=tol]').val(str2);
//        alert($('input[name=tol]').val());
//        return false;
//    })

    })



</script>