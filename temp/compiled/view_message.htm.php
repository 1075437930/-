<?php echo self::fetch('pageheader.htm'); ?>
<!--<div class="main-div">-->
<div class="list-div" id="listDiv">
    <form method="post" id="form_met" action="" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">

            <input type="hidden" name="pricecut_id" value="<?php echo self::$_var['res']['pricecut_id']; ?>"/>
            <tr>
                <th colspan="10">短信详情</th>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['message_id']; ?>:</td>
                <td><?php echo self::$_var['res']['sms_id']; ?></td>
                <td class="label"><?php echo self::$_var['lang']['from_message_id']; ?>:</td>
                <td><?php echo self::$_var['res']['from_sms_id']; ?></td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['to_message_id']; ?>:</td>
                <td><?php echo self::$_var['res']['to_member_id']; ?></td>
                <td class="label"><?php echo self::$_var['lang']['message_title']; ?>:</td>
                <td><?php echo self::$_var['res']['sms_title']; ?></td>
            </tr>


            <tr>
                <td class="label"><?php echo self::$_var['lang']['message_time']; ?>:</td>
                <td><?php echo self::$_var['res']['sms_time']; ?></td>
                <td class="label"><?php echo self::$_var['lang']['message_state']; ?>:</td>
                <td><?php echo self::$_var['res']['sms_state']; ?></td>
            </tr>



            <tr>
                <td class="label"><?php echo self::$_var['lang']['message_body']; ?>:</td>
                <td colspan="7">
                    <textarea rows="5" cols="80" name="remark" id="attr_name"><?php echo self::$_var['res']['sms_body']; ?></textarea>
                    <?php echo self::$_var['lang']['require_field']; ?></td>

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

