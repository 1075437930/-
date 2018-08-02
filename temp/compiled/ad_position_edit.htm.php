
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="js/calendar.php?lang=<?php echo self::$_var['cfg_lang']; ?>"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="main-div">
    <form action="index.php" method="post" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table width="100%" id="general-table">
            <tr>
                <td  class="label">
                   <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="广告名称">广告位置名称</td>
                <td>
                    <input type="text" name="site_name1" value="<?php echo self::$_var['dcimgsiteinto']['site_name']; ?>" size="35" <?php if (self::$_var['bubianji'] == 1): ?> disabled="true" <?php endif; ?>/><span class="require-field">*</span>					<input type="hidden" name="site_name" value="<?php echo self::$_var['dcimgsiteinto']['site_name']; ?>" size="35" />
                </td>
            </tr>
            <tr>                <td  class="label">                   <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="广告位宽度">广告位宽度</td>                <td>                    <input type="text" name="width_w" value="<?php echo self::$_var['dcimgsiteinto']['width_w']; ?>" size="35" /><span class="require-field">*</span>                </td>			 </tr>			 <tr>                <td  class="label">                   <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="广告位高度">广告位高度</td>                <td>                    <input type="text" name="high_h" value="<?php echo self::$_var['dcimgsiteinto']['high_h']; ?>" size="35" /><span class="require-field">*</span>                </td>            </tr>			<tr>                <td  class="label">展示端口</td>                <td>                    <input type="radio" name="media_type" value="1" <?php if (self::$_var['dcimgsiteinto']['media_type'] == 1): ?> checked="true" <?php endif; ?> />app端                    <input type="radio" name="media_type" value="2" <?php if (self::$_var['dcimgsiteinto']['media_type'] == 2): ?> checked="true" <?php endif; ?> />电脑端					<input type="radio" name="media_type" value="3" <?php if (self::$_var['dcimgsiteinto']['media_type'] == 3): ?> checked="true" <?php endif; ?> />手机网页端                </td>            </tr>			<tr>                <td class="label">                   默认广告图                <td>                    <input type='file' name='default_img' size='35' />					<?php if (self::$_var['dcimgsiteinto']['default_img']): ?>					<a href="<?php echo self::$_var['dcimgsiteinto']['default_img2']; ?>" target="_blank">						<img src="templates/default/images/yes.gif" border="0" />					</a>					<?php else: ?>					<img src="templates/default/images/no.gif" />					<?php endif; ?>					<input type="hidden" name="default_img2" value="<?php echo self::$_var['dcimgsiteinto']['default_img']; ?>" size="35" /><span class="require-field">*</span>                </td>            </tr>			<tr>				<td class="label">广告描述</td>				<td>				<textarea rows="5" cols="30" name="dic_comt" id="dic_comt" maxlength="200" placeholder="广告描述"><?php echo self::$_var['dcimgsiteinto']['dic_comt']; ?></textarea><span class="require-field">*</span>				</td>			</tr>
            <tr>
                <td class="label">&nbsp;</td>
                <td>
                    <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" />
                    <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
                    <input type="hidden" name="act" value="ad_position" />
                    <input type="hidden" name="op" value="update" />
                    <input type="hidden" name="site_id" value="<?php echo self::$_var['dcimgsiteinto']['site_id']; ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script language="JavaScript">
  
    
   

    /**
     * 检查表单输入的数据
     */
    function validate()
    {
        validator = new Validator("theForm");
        validator.required("site_name1", '广告名称不能为空');		validator.required("width_w", '广告位宽度不能为空');		validator.required("high_h",'广告位高度不能为空');		validator.required("dic_comt",'广告描述不能为空');		

        return validator.passed();
    }

   
    //-->
    
</script>
<?php echo self::fetch('pagefooter.htm'); ?>