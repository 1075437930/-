<?php echo self::fetch('pageheader.htm'); ?>
<script language="JavaScript">
<!--

// 这里把JS用到的所有语言都赋值到这里

<?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";

<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

//-->
</script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="main-div">
  <form action="index.php" method="post" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
    <table width="100%" id="general-table">
      <tr>
        <td class="label">
          <img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="广告名称">广告名称
          <span class="require-field">*</span></td>
        <td>
          <input type="text" name="ad_name" value="<?php echo self::$_var['dcimginto']['ad_name']; ?>" size="35" /></td>
      </tr>
      <tr>
        <td class="label">开始时间
          <span class="require-field">*</span></td>
        <td>
          <input name="start_time" type="text" id="start_time" size="22" value='<?php echo self::$_var['dcimginto']['start_time']; ?>' />
          <input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('start_time', '%Y-%m-%d', false, false, 'selbtn1');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button" /></td>
      </tr>
      <tr>
        <td class="label">结束时间
          <span class="require-field">*</span></td>
        <td>
          <input name="end_time" type="text" id="end_time" size="22" value='<?php echo self::$_var['dcimginto']['end_time']; ?>' />
          <input name="selbtn2" type="button" id="selbtn2" onclick="return showCalendar('end_time', '%Y-%m-%d', false, false, 'selbtn2');" value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button" /></td>
      </tr>
      <tr>
        <td class="label">广告连接
          <td>
            <input type="text" name="links" value="<?php echo self::$_var['dcimginto']['links']; ?>" size="35" />
            <span class="require-field">如果不填写 则app点击不做页面跳转</span></td>
      </tr>
      <tr>
        <td class="label">广告连接名称
          <td>
            <input type="text" name="links_name" value="<?php echo self::$_var['dcimginto']['links_name']; ?>" size="35" /></td></tr>
      <tr>
        <td class="label">对应连接参数
          <td>
            <input type="text" name="canshu" value="<?php echo self::$_var['dcimginto']['canshu']; ?>" size="35" />
            <span class="require-field">如果不填写 则无参数页面传递</span></td>
      </tr>
      <tr>
        <td class="label">广告位置
          <span class="require-field">*</span></td>
        <td>
          <select class="chzn-select" name="siteid">
            <option value='0'>请选择</option><?php $_from = self::$_var['site_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'site');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['site']):
?> <?php $_from = self::$_var['site']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 's');if (count($_from)):
    foreach ($_from AS self::$_var['s']):
?>
            <option value="<?php echo self::$_var['key']; ?>"><?php echo self::$_var['s']; ?></option><?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?> <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?></select></td>
      </tr>
      <tr>
        <td class="label">广告图
          <span class="require-field">*</span>
          <td>
            <input type='file' name='imgurl' size='35' /><?php if (self::$_var['dcimginto']['imgurl']): ?>
            <a href="<?php echo self::$_var['dcimginto']['imgurl2']; ?>" target="_blank">
              <img src="templates/default/images/yes.gif" border="0" /></a><?php else: ?>
            <img src="templates/default/images/no.gif" /><?php endif; ?>
            <input type="hidden" name="imgurl2" value="<?php echo self::$_var['dcimginto']['imgurl']; ?>" size="35" /></td></tr>
      <tr>
        <td class="label"><?php echo self::$_var['lang']['showsd']; ?></td>
        <td>
          <input type="radio" name="showsd" value="1" <?php if (self::$_var['ads']['showsd'] == 1): ?> checked="true" <?php endif; ?> /><?php echo self::$_var['lang']['is_enabled']; ?>
          <input type="radio" name="showsd" value="0" <?php if (self::$_var['ads']['showsd'] == 0): ?> checked="true" <?php endif; ?> /><?php echo self::$_var['lang']['no_enabled']; ?></td></tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td>
          <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" />
          <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
          <input type="hidden" name="imgs_id" value="<?php echo self::$_var['dcimginto']['imgs_id']; ?>" />
          <input type="hidden" name="act" value="ads" />
          <input type="hidden" name="op" value="insert" /></td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script language="JavaScript">{
    literal
  }
  /**     * 检查表单输入的数据     */
  function validate() {
    validator = new Validator("theForm");
    validator.required("ad_name", '广告名称不能为空');
    validator.required("start_time", '开始时间不能为空');
    validator.required("end_time", '结束时间不能为空');
    /* 代码增加 By  www.taoyumall.com Start */
    validator.islt('start_time', 'end_time', '结束日期不能小于开始日期');
    return validator.passed();
  } //-->    
  </script><?php echo self::fetch('pagefooter.htm'); ?>