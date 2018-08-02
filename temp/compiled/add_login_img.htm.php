
<?php echo self::fetch('pageheader.htm'); ?>
<div class="main-div">
    <form method="post" action="index.php?act=app_seting&op=insert_login_img" name="theForm" enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">上传app登录背景图</td>
                <td>
                    <input name="img" type="file" size="40" />
                    <font color="red">*</font> <span class="notice-span"></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" class="button" value="提交" />
                </td>
            </tr>
        </table>
    </form>
</div>

<?php echo self::fetch('pagefooter.htm'); ?>

