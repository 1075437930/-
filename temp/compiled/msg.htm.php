<?php echo self::fetch('pageheader.htm'); ?>

<div class="list-div">

    <div style="background:#ffffff; padding: 20px 50px; margin: 2px;">

        <table align="center" width="400" border="0" style="background:#FFF;">

            <tr>

                <td width="50" valign="top">

                    <?php if (self::$_var['msg_type'] == 0): ?>

                    <img src="templates/default/images/information.gif" width="32" height="32" border="0" alt="information" />

                    <?php elseif (self::$_var['msg_type'] == 1): ?>

                    <img src="templates/default/images/warning.gif" width="32" height="32" border="0" alt="warning" />

                    <?php else: ?>

                    <img src="templates/default/images/confirm.gif" width="32" height="32" border="0" alt="confirm" />

                    <?php endif; ?>

                </td>

                <td style="font-size: 14px; font-weight: bold"><?php echo self::$_var['msg_detail']; ?></td>

            </tr>

            <tr>

                <td></td>

                <td id="redirectionMsg">

                    <?php if (self::$_var['auto_redirect']): ?><?php echo self::$_var['lang']['auto_redirection']; ?><?php endif; ?>

                </td>

            </tr>

            <tr>

                <td></td>

                <td>

                    <ul style="margin:0; padding:0 10px" class="msg-link">

                        <?php $_from = self::$_var['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'link');if (count($_from)):
    foreach ($_from AS self::$_var['link']):
?>

                        <li><a href="<?php echo self::$_var['link']['href']; ?>" <?php if (self::$_var['link']['target']): ?>target="<?php echo self::$_var['link']['target']; ?>"<?php endif; ?>><?php echo self::$_var['link']['text']; ?></a></li>

                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

                    </ul>



                </td>

            </tr>

        </table>

    </div>

</div>

<?php if (self::$_var['auto_redirect']): ?>

<script language="JavaScript">
    var seconds = 3;
    var defaultUrl = "<?php echo self::$_var['default_url']; ?>";
    

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



<?php endif; ?>

<?php echo self::fetch('pagefooter.htm'); ?>