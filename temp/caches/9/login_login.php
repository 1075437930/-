<?php exit;?>a:3:{s:8:"template";a:1:{i:0;s:68:"/www/wwwroot/taoyumall/taoyuphp/tao_yuec/templates/default/login.htm";}s:7:"expires";i:1529925937;s:8:"maketime";i:1529922337;}<!DOCTYPE html>
<html>
<head>
<title>淘玉商城 管理中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/styles/general.css" rel="stylesheet" type="text/css" />
<link href="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/utils.js"></script><script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/validator.js"></script><script language="JavaScript">
<!--
// 这里把JS用到的所有语言都赋值到这里
var expand_all = "展开";
var collapse_all = "闭合";
var shop_name_not_null = "商店名称不能为空";
var good_name_not_null = "商品名称不能为空";
var good_category_not_null = "商品分类不能为空";
var good_number_not_number = "商品数量不是数值";
var good_price_not_number = "商品价格不是数值";
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
        <td class="dl_1" width="22%">用户名</td>
        <td><input type="text" name="username" class="text_input1"/></td>
      </tr>
      <tr>
        <td class="dl_2">密　码</td>
        <td><input type="password" name="password"  class="text_input1"/></td>
      </tr>
     <tr class="low_height">
        <td>&nbsp;</td>
        <td><input type="checkbox" value="1" name="remember" id="remember" /><label for="remember" >保存登录</label>&nbsp;&nbsp;
		<input type="checkbox" value="1" name="ceshi" id="ceshi"  checked="checked" />
		
		<label for="ceshi" >测试</label></td>
		</tr>
      <tr>
      	<td colspan="2" align="center"><input type="submit" value="登&nbsp;录" class="button2" /></td>
      </tr>
      <tr class="low_height1">
        <td colspan="2" align="right">&raquo; <a href="" >返回首页</a> &raquo; <a href="get_password.php?act=forget_pwd">您忘记了密码吗?</a></td>
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