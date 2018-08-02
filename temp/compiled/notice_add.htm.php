<link rel="stylesheet" href="<?php echo self::$_var['urls_dir']; ?>/styles/skin_0.css">

<script src="<?php echo self::$_var['urls_dir']; ?>/js/jquery.min.js"></script>

<div class="page">

    <div class="fixed-bar">

        <div class="item-title">

            <h3>会员通知</h3>

            <ul class="tab-base">

                <li><a href="JavaScript:void(0);" class="current"><span>发送通知</span></a></li>

            </ul>

        </div>

    </div>

    <div class="fixed-empty"></div>

    <form id="notice_form" method="POST" action="index.php?act=userremind&op=submit" onsubmit="return check()">

        <input type="hidden" name="form_submit" value="ok" />

        <table class="table tb-type2">

            <tbody>

                <tr class="noborder">

                    <td colspan="2" class="required"><label>发送类型: </label></td>

                </tr>

                <tr class="noborder">

                    <td class="vatop rowform"><ul class="nofloat">

                            <li>

                                <label><input type="radio" checked="" value="1" name="send_type" id="zd_user">指定会员</label>

                            </li>

                            <li>

                                <label><input type="radio" value="2" name="send_type" id="all_user"/>全部会员</label>

                            </li>

                        </ul>

                    </td>

                    <td class="vatop tips"></td>

                </tr>

            </tbody>

            <tbody id="user_list">

                <tr>

                    <td colspan="2" class="required"><label class="validation" for="user_name">会员列表: </label></td>

                </tr>

                <tr class="noborder">

                    <td class="vatop rowform"><textarea id="user_name" name="user_name" rows="6" class="tarea" value=""></textarea></td>

                    <td class="vatop tips">每行填写一个会员名</td>

                </tr>

            </tbody>

            <tbody id="msg">

                <tr>

                    <td colspan="2" class="required"><label class="validation">通知内容: </label></td>

                </tr>

                <tr class="noborder">

                    <td colspan="2" class="vatop rowform"><textarea name="content1" rows="6" class="tarea" id="tarea" value=""></textarea></td>

                </tr>

            </tbody>

            <tfoot>

                <tr class="tfoot">

                    <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><button type="submit">提交</button></span></a></td>

                </tr>

            </tfoot>

        </table>

    </form>

</div>

<script>

    function check() {

        var zduser = document.getElementById('zd_user');

        var alluser = document.getElementById('all_user');

        var tarea = document.getElementById('tarea').value.length;

        var username = document.getElementById('user_name').value.length;

        if (zduser.checked) {

            if (tarea < 1 || username < 1) {

                return false;

            }

        }

        if (alluser.checked) {

            if (tarea < 1) {

                return false;

            }

        }





    }

</script>

