<!DOCTYPE html>

<html>

<head>

<title><?php echo self::$_var['lang']['cp_home']; ?><?php if (self::$_var['ur_here']): ?> - <?php echo self::$_var['ur_here']; ?> <?php endif; ?></title>

<meta name="robots" content="noindex, nofollow">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="<?php echo self::$_var['urls_dir']; ?>/styles/general.css" rel="stylesheet" type="text/css" />

<link href="<?php echo self::$_var['urls_dir']; ?>/styles/main.css" rel="stylesheet" type="text/css" />

<link href="<?php echo self::$_var['urls_dir']; ?>/styles/chosen/chosen.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery.json.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/common.js"></script>
<script language="JavaScript">

<!--

// 这里把JS用到的所有语言都赋值到这里

<?php $_from = self::$_var['lang']['js_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";

<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

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

<?php if (self::$_var['action_link']): ?>

<span class="action-span"><a href="<?php echo self::$_var['action_link']['href']; ?>"><?php echo self::$_var['action_link']['text']; ?></a></span>

<?php endif; ?>

<?php if (self::$_var['action_link2']): ?>

<span class="action-span"><a href="<?php echo self::$_var['action_link2']['href']; ?>"><?php echo self::$_var['action_link2']['text']; ?></a>&nbsp;&nbsp;</span>

<?php endif; ?>

<span class="action-span1"><a href="index.php?act=index&op=main"><?php echo self::$_var['lang']['cp_home']; ?></a> </span><span id="search_id" class="action-span1"><?php if (self::$_var['ur_here']): ?> - <?php echo self::$_var['ur_here']; ?> <?php endif; ?></span>

<div style="clear:both"></div>

</h1>

