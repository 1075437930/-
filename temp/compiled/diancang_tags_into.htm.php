<?php echo self::fetch('pageheader.htm'); ?>
<link href="js/calendar/calendar.css" rel="stylesheet" type="text/css"/>
<div class="main-div">
    <form action="index.php" method="post" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table width="100%" id="general-table">
            <tr>
                <td class="label"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="标签名称">标签名称<span
                        class="require-field">*</span></td>
                <td><input type="text" name="tags_name" value="<?php echo self::$_var['tags_into']['tags_name']; ?>" size="35"/></td>
            </tr>
            <tr>
                <td class="label">&nbsp;</td>
                <td>
                <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button"/> 
                <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button"/>
                <input type="hidden" name="act" value="dctags"/> 
                <input type="hidden" name="op" type="hidden" value="<?php echo self::$_var['insert_or_update']; ?>" />
                <input type="hidden" name="dctags_id" value="<?php echo self::$_var['tags_into']['tags_id']; ?>"/>
               </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script language="JavaScript">    

    /*** 检查表单输入的数据*/    
    function validate() {
        validator = new Validator("theForm");
        validator.required("tags_name", '标签名称不能为空');
        return validator.passed();
    }

</script>
<?php echo self::fetch('pagefooter.htm'); ?>
