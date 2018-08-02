<!DOCTYPE html>
<html>
<head>
<title><?php echo self::$_var['lang']['cp_home']; ?><?php if (self::$_var['ur_here']): ?> - <?php echo self::$_var['ur_here']; ?><?php endif; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo self::$_var['urls_dir']; ?>/styles/general.css" rel="stylesheet" type="text/css" />
<link href="<?php echo self::$_var['urls_dir']; ?>/styles/main.css" rel="stylesheet" type="text/css" />


<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>
<script language="JavaScript">
<!--
// 这里把JS用到的所有语言都赋值到这里
<?php $_from = self::$_var['lang']['js_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

if (window.parent != window)
{
  window.top.location.href = location.href;
}

//-->
</script>
</head>
<body style="background:url(templates/default/images/login_bg.png) repeat-x;padding:0px;">
<div style="width:1210px;height:768px;margin:0 auto;background:url(templates/default/images/login_dl.jpg) no-repeat; " >
<form method="post" action="index.php?act=login&op=signin" name='theForm' onsubmit="return validate()">
  <table cellspacing="0" cellpadding="0" style=" padding-top:295px; " align="center" class="login_dl">
  <tr>
<td class="dl">
      <table cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td class="dl_1" width="22%"><?php echo self::$_var['lang']['label_username']; ?></td>
        <td><input type="text" name="username" class="text_input1"/></td>
      </tr>
      <tr>
        <td class="dl_2"><?php echo self::$_var['lang']['label_password']; ?></td>
        <td><input type="password" name="password"  class="text_input1"/></td>
      </tr>
     <tr class="low_height">
        <td>&nbsp;</td>
        <td><input type="checkbox" value="1" name="remember" id="remember" /><label for="remember" >保存登录</label>&nbsp;&nbsp;
		<input type="checkbox" value="1" name="ceshi" id="ceshi" <?php if (self::$_var['ceshisql'] == 1): ?> checked="checked" <?php endif; ?>/>
		
		<label for="ceshi" >测试</label></td>
		</tr>
      <tr>
      	<td colspan="2" align="center"><input type="submit" value="登&nbsp;录" class="button2" /></td>
      </tr>
      <tr class="low_height1">
        <td colspan="2" align="right">&raquo; <a href="" ><?php echo self::$_var['lang']['back_home']; ?></a> &raquo; <a href="get_password.php?act=forget_pwd"><?php echo self::$_var['lang']['forget_pwd']; ?></a></td>
      </tr>
      </table>
    </td>
  </tr>
  </table>
  <input type="hidden" name="act" value="signin" />
</form>
</div>
<script language="JavaScript">

  document.forms['theForm'].elements['username'].focus();
  
  /**
   * 检查表单输入的内容
   */
  function validate()
  {
    var validator = new Validator('theForm');
    validator.required('username', user_name_empty);
    //validator.required('password', password_empty);
    if (document.forms['theForm'].elements['captcha'])
    {
      validator.required('captcha', captcha_empty);
    }
    return validator.passed();
  }
  

</script>
</body>