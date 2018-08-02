<?php exit;?>a:3:{s:8:"template";a:3:{i:0;s:66:"/www/wwwroot/taoyumall/taoyuphp/tao_yuec/templates/default/msg.htm";i:1;s:73:"/www/wwwroot/taoyumall/taoyuphp/tao_yuec/templates/default/pageheader.htm";i:2;s:73:"/www/wwwroot/taoyumall/taoyuphp/tao_yuec/templates/default/pagefooter.htm";}s:7:"expires";i:1529748410;s:8:"maketime";i:1529744810;}<!DOCTYPE html>
<html>
<head>
<title>淘玉商城 管理中心</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/styles/general.css" rel="stylesheet" type="text/css" />
<link href="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/styles/main.css" rel="stylesheet" type="text/css" />
<link href="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/styles/chosen/chosen.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/jquery.json.js"></script>
<script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/transport.js"></script>
<script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/common.js"></script>
<script language="JavaScript">
<!--
// 这里把JS用到的所有语言都赋值到这里
var expand_all = "展开";
var collapse_all = "闭合";
var shop_name_not_null = "商店名称不能为空";
var good_name_not_null = "商品名称不能为空";
var good_category_not_null = "商品分类不能为空";
var good_number_not_number = "商品数量不是数值";
var good_price_not_number = "商品价格不是数值";
//-->
</script>
</head>
<body>
<div id="menu_list" onmouseover="show_popup()" onmouseout="hide_popup()">
<ul>
<li><a href="goods.php?act=add" target="main_frame">添加新商品</a></li>
<li><a href="category.php?act=add" target="main_frame">添加商品分类</a></li>
<li><a href="order.php?act=add" target="main_frame">添加订单</a></li>
<li><a href="article.php?act=add" target="main_frame">添加新文章</a></li>
<li><a href="users.php?act=add" target="main_frame">添加会员</a></li>
</ul>
</div>
<script>
function show_popup(){
frmBody = parent.document.getElementById('frame-body');
if (frmBody.cols == "37, 12, *")
{
parent.main_frame.document.getElementById('menu_list').style.left = '195px';
}
else
{
parent.main_frame.document.getElementById('menu_list').style.left = '40px';
}
parent.main_frame.document.getElementById('menu_list').style.display = 'block';
}
function hide_popup(){
parent.main_frame.document.getElementById('menu_list').style.display = 'none';
}
</script>
<h1>
<span class="action-span1"><a href="index.php?act=index&op=main">淘玉商城 管理中心</a> </span><span id="search_id" class="action-span1"></span>
<div style="clear:both"></div>
</h1>
<div class="list-div">
    <div style="background:#ffffff; padding: 20px 50px; margin: 2px;">
        <table align="center" width="400" border="0" style="background:#FFF;">
            <tr>
                <td width="50" valign="top">
                    
                    <img src="templates/default/images/information.gif" width="32" height="32" border="0" alt="information" />
                    
                </td>
                <td style="font-size: 14px; font-weight: bold">短信添加成功</td>
            </tr>
            <tr>
                <td></td>
                <td id="redirectionMsg">
                    如果您不做出选择，将在 <span id="spanSeconds">3</span> 秒后跳转到第一个链接地址。
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <ul style="margin:0; padding:0 10px" class="msg-link">
                        
                        <li><a href="index.php?act=sms&op=lists" >返回</a></li>
                        
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</div>
<script language="JavaScript">
    var seconds = 3;
    var defaultUrl = "index.php?act=sms&op=lists";
    
    onload = function ()
    {
        if (defaultUrl == 'javascript:history.go(-1)' && window.history.length == 0)
        {
            document.getElementById('redirectionMsg').innerHTML = '';
            return;
        }
        window.setInterval(redirection, 1000);
    }
    function redirection()
    {
        if (seconds <= 0)
        {
            window.clearInterval();
            return;
        }
        seconds--;
        document.getElementById('spanSeconds').innerHTML = seconds;
        
        if (seconds == 0)
        {
            location.href = defaultUrl;
            window.clearInterval();
        }
    }
</script>
<div id="footer">
<br />
版权所有 &copy; 2015-2025 淘玉商城，并保留所有权利。
</div>
<script type="text/javascript" src="https://www.taoyumall.com/taoyuphp/tao_yuec/templates/default/js/utils.js"></script>
<div id="popMsg">
  <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#cfdef4" border="0">
  <tr>
    <td style="color: #0f2c8c" width="30" height="24"></td>
    <td style="font-weight: normal; color: #1f336b; padding-top: 4px;padding-left: 4px" valign="center" width="100%"> 新订单通知</td>
    <td style="padding-top: 2px;padding-right:2px" valign="center" align="right" width="19"><span title="关闭" style="cursor: hand;cursor:pointer;color:red;font-size:12px;font-weight:bold;margin-right:4px;" onclick="Message.close()" >×</span><!-- <img title=关闭 style="cursor: hand" onclick=closediv() hspace=3 src="msgclose.jpg"> --></td>
  </tr>
  <tr>
    <td style="padding-right: 1px; padding-bottom: 1px" colspan="3" height="70">
    <div id="popMsgContent">
      <p>您有 <strong style="color:#ff0000" id="spanNewOrder">1</strong> 个新订单以及 
      <strong style="color:#ff0000" id="spanNewPaid">0</strong> 个新付款的订单</p>
      <p align="center" style="word-break:break-all"><a href="order.php?act=list"><span style="color:#ff0000">点击查看新订单</span></a></p>
    </div>
    </td>
  </tr>
  </table>
</div>
<!--
<embed src="templates/default/images/online.wav" width="0" height="0" autostart="false" name="msgBeep" id="msgBeep" enablejavascript="true"/>
-->
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0" id="msgBeep" width="1" height="1">
  <param name="movie" value="images/online.swf">
  <param name="quality" value="high">
  <embed src="templates/default/images/online.swf" name="msgBeep" id="msgBeep" quality="high" width="0" height="0" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash">
  </embed>
</object>
<script language="JavaScript">
document.onmousemove=function(e)
{
  var obj = Utils.srcElement(e);
  if (typeof(obj.onclick) == 'function' && obj.onclick.toString().indexOf('listTable.edit') != -1)
  {
    obj.title = '';
    obj.style.cssText = 'background: #278296;';
    obj.onmouseout = function(e)
    {
      this.style.cssText = '';
    }
  }
  else if (typeof(obj.href) != 'undefined' && obj.href.indexOf('listTable.sort') != -1)
  {
    obj.title = '';
  }
}
<!--
var MyTodolist;
function showTodoList(adminid)
{
  if(!MyTodolist)
  {
    var global = $import("js/global.js","js");
    global.onload = global.onreadystatechange= function()
    {
      if(this.readyState && this.readyState=="loading")return;
      var md5 = $import("js/md5.js","js");
      md5.onload = md5.onreadystatechange= function()
      {
        if(this.readyState && this.readyState=="loading")return;
        var todolist = $import("js/todolist.js","js");
        todolist.onload = todolist.onreadystatechange = function()
        {
          if(this.readyState && this.readyState=="loading")return;
          MyTodolist = new Todolist();
          MyTodolist.show();
        }
      }
    }
  }
  else
  {
    if(MyTodolist.visibility)
    {
      MyTodolist.hide();
    }
    else
    {
      MyTodolist.show();
    }
  }
}
if (Browser.isIE)
{
  onscroll = function()
  {
    //document.getElementById('calculator').style.top = document.body.scrollTop;
    document.getElementById('popMsg').style.top = (document.body.scrollTop + document.body.clientHeight - document.getElementById('popMsg').offsetHeight) + "px";
  }
}
if (document.getElementById("listDiv"))
{
  document.getElementById("listDiv").onmouseover = function(e)
  {
    obj = Utils.srcElement(e);
    if (obj)
    {
      if (obj.parentNode.tagName.toLowerCase() == "tr") row = obj.parentNode;
      else if (obj.parentNode.parentNode.tagName.toLowerCase() == "tr") row = obj.parentNode.parentNode;
      else return;
      for (i = 0; i < row.cells.length; i++)
      {
        if (row.cells[i].tagName != "TH") row.cells[i].style.backgroundColor = '#F4FAFB';
      }
    }
  }
  document.getElementById("listDiv").onmouseout = function(e)
  {
    obj = Utils.srcElement(e);
    if (obj)
    {
      if (obj.parentNode.tagName.toLowerCase() == "tr") row = obj.parentNode;
      else if (obj.parentNode.parentNode.tagName.toLowerCase() == "tr") row = obj.parentNode.parentNode;
      else return;
      for (i = 0; i < row.cells.length; i++)
      {
          if (row.cells[i].tagName != "TH") row.cells[i].style.backgroundColor = '';
      }
    }
  }
  document.getElementById("listDiv").onclick = function(e)
  {
    var obj = Utils.srcElement(e);
    if (obj.tagName == "INPUT" && obj.type == "checkbox")
    {
      if (!document.forms['listForm'])
      {
        return;
      }
      var nodes = document.forms['listForm'].elements;
      var checked = false;
      for (i = 0; i < nodes.length; i++)
      {
        if (nodes[i].checked)
        {
           checked = true;
           break;
         }
      }
      if(document.getElementById("btnSubmit"))
      {
        document.getElementById("btnSubmit").disabled = !checked;
      }
      for (i = 1; i <= 10; i++)
      {
        if (document.getElementById("btnSubmit" + i))
        {
          document.getElementById("btnSubmit" + i).disabled = !checked;
        }
      }
    }
  }
}
//-->
</script>
</body>
</html>
