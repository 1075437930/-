<!DOCTYPE html>
<html>
<head>
    <title><?php echo self::$_var['lang']['cp_home']; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="Text/Javascript" language="JavaScript">
        <!--
        
        if (window.top != window)
        {
            window.top.location.href = document.location.href;
        }
        
        //-->
    </script>


    <frameset rows="113,*" framespacing="0" border="0" id="frame-all">

        <frame src="index.php?act=index&op=top" id="header-frame" name="header-frame" frameborder="no" scrolling="no">
        <frameset cols="200, *" framespacing="0" border="0" id="frame-body">
            <frame src="index.php?act=index&op=menu" id="menu-frame" name="menu_frame" frameborder="no" scrolling="yes">
            <frame src="index.php?act=index&op=main" id="main-frame" name="main_frame" frameborder="no" scrolling="yes">
        </frameset>
    </frameset><noframes></noframes>
    <frameset rows="0, 0" framespacing="0" border="0">
        <frame src="" id="hidd-frame" name="hidd-frame" frameborder="no" scrolling="no">
    </frameset>
</head>
<body>

</body>
</html>
