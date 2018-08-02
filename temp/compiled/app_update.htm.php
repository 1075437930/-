
<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
    <form method="post" id="form_met" action="index.php?act=app_seting&op=app_update" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">
            <input type="hidden" name="goods_type" value="1"/>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['app_version']; ?>:</td>
                <td><input type="text" id="attr_name" name="app_version" maxlength="60" value="<?php echo self::$_var['res']['version']; ?>" /><?php echo self::$_var['lang']['require_field']; ?></td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['version_description']; ?></td>
                <td>
                    <textarea rows="5" cols="80" name="version_description" id="version_description"><?php echo self::$_var['res']['version_description']; ?></textarea>
                    <?php echo self::$_var['lang']['require_field']; ?>
                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['ios_url']; ?></td>
                <td>
                    <input type="text" name="ios_url"  maxlength="100" value="<?php echo self::$_var['res']['ios_url']; ?>" id="ios_url"/>
                </td>
            </tr>
            <tr>
                <td class="label"><?php echo self::$_var['lang']['is_update']; ?></td>
                <td>
                    <?php if (self::$_var['res']['is_update'] == 0): ?>
                    <input type="radio" name="is_update"  maxlength="60" value="0" checked />否
                        <?php else: ?>
                    <input type="radio" name="is_update"  maxlength="60" value="0"  />否
                    <?php endif; ?>

                    <?php if (self::$_var['res']['is_update'] == 1): ?>
                    <input type="radio" name="is_update"  maxlength="60" value="1" checked />是
                    <?php else: ?>
                    <input type="radio" name="is_update"  maxlength="60" value="1"  />是
                    <?php endif; ?>
                    <?php echo self::$_var['lang']['require_field']; ?><br>如果不更新 用户登录app无提示，如果有每次登录都会提示
                </td>


            </tr>

            <tr>
                <td colspan="2" align="center"><br />
                    <input type="submit" class="button" value="<?php echo self::$_var['lang']['quding']; ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>

<?php echo self::fetch('pagefooter.htm'); ?>

<script>
    var inp_name=document.getElementById('attr_name');
    var inp_name1=document.getElementById('version_description');
    var inp_name2=document.getElementById('ios_url');

    var form= document.getElementById('form_met');

    form.onsubmit=function(){
        if(!inp_name.value){
            alert('版本号不能为空');
            return false
        }

        if(!inp_name1.value){
            alert('版本说明不能为空');
            return false
        }

        if(!inp_name2.value){
            alert('ios更新地址不能为空');
            return false
        }

    }


</script>

