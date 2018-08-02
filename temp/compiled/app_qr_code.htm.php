

<?php echo self::fetch('pageheader.htm'); ?>


<div class="main-div">
    <form method="post" action="index.php?act=app_seting&op=add_qr_code" name="theForm" enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">二维码图片</td>
                <td>
                    <img src="<?php echo self::$_var['app_config']['app_qr_code']; ?>" width="200px" height="200px" border="0" />
                </td>
            </tr>
            <tr>
                <td class="label">上传新二维码</td>
                <td>
                    <input name="app_qr_code" type="file" size="40" />
                    <font color="red">*</font> <span class="notice-span"></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" class="button" value="提交" />
                    <input type="hidden" name="app_qr_code_old" value="<?php echo self::$_var['app_config']['app_qr_code']; ?>" />
                    <input type="hidden" name="id" value="<?php echo self::$_var['app_config']['id']; ?>" />    </td>
            </tr>
        </table>
    </form>
</div>

<?php echo self::fetch('pagefooter.htm'); ?>

