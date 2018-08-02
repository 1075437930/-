<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery-1.10.1.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/ajaxfileupload.js"></script>

<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab" onclick ="set1()" style="font-weight: normal"><?php echo self::$_var['lang']['item_push']; ?></span>
            <span class="tab-back" id="detail-tab" onclick ="set2()"><?php echo self::$_var['lang']['people_push']; ?></span>
            <span class="tab-back" id="mix-tab" onclick ="set3()"><?php echo self::$_var['lang']['attribute_to_push']; ?></span>
            <!--            <span class="tab-back" id="properties-tab" onclick ="set4()"><?php echo self::$_var['lang']['all_push']; ?></span>-->
        </p>
    </div>
</div>
<style type="text/css">
    #tabbody-div {
        position:absolute;
        left:50%;
        top:25%;
        border-top: 0px;
        margin-left:-350px;
        margin-top:-32px
    }
    td.label {
        width: 136px;
    }
</style>
<div id="tabbody-div">
    
    <table width="100%" id="general-table"  style="display:block;" align="right">
        <tr>
            <td class="label"><?php echo self::$_var['lang']['goods_name']; ?></td>
            <td>
                <input type="text"  name="goods_name" id="search_goods_name" value="">
                <input type="hidden"  name="one_goods_id1" id="one_goods_id1" value="">
                <a  onclick="javascript:searchGoodsname($('#search_goods_name').val());" href="javascript:void(0);" id="btn_search_goods" class="isubmit2Btn"><i class="icon-search"></i>搜索</a>
                <p class="hint" style="color:#cccccc;">请输入商品id或商品名称或商品货号进行搜索，并点击选中</p>
                <div id="div_goods_search_result" class="search-result" style="cursor:pointer;">
                    <style type="text/css">
                        li:hover{
                            color:red;
                        }
                    </style>
                    <div id="brandDiv" >
                        <ul id="ulBrand"></ul>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="label"><?php echo self::$_var['lang']['push_content']; ?></td>
            <td>
                <textarea name="push_content"  id="goods_brief"  cols="40" rows="3" placeholder="限制100字"></textarea>
            </td>
        </tr>
        <tr>
            <td class="label"><?php echo self::$_var['lang']['the_user_type']; ?></td>
            <td>
                <input type="checkbox" name="is_android1" id="is_android1" value="1" />
                <?php echo self::$_var['lang']['android']; ?>
                <input type="checkbox" name="is_ios1" id="is_ios1" value="1" />
                <?php echo self::$_var['lang']['ios']; ?>
            </td>
        </tr>

        <tr>
            <td class="label"><?php echo self::$_var['lang']['push_people']; ?></td>
            <td>
                <input type="radio" name="vip_a" value="1" />
                <?php echo self::$_var['lang']['vip1']; ?>
                <input type="radio" name="vip_a" value="2" />
                <?php echo self::$_var['lang']['vip2']; ?>
                <input type="radio" name="vip_a" value="3" />
                <?php echo self::$_var['lang']['vip3']; ?>
                <input type="radio" name="vip_a" value="4" />
                <?php echo self::$_var['lang']['vip4']; ?>
                <input type="radio" name="vip_a" value="5" />
                <?php echo self::$_var['lang']['vip5']; ?>
                <input type="radio" name="vip_a" value="6" />
                普通用户
                <input type="radio" name="vip_a" value="7" />
                VIP用户
                <input type="radio" name="vip_a" value="8" />
                全部用户
            </td>
        </tr>
    </table>

    
    <table width="100%" id="people_push" style="display:none" align="center">
        <tr>
            <td class="label">推送类型</td>
            <td>
                <select name="push_types1" id="push_types1" style="margin-left:0px;">
                    <option value="0">请选择</option>
                    <option value="1">商品推送</option>
                    <option value="3">系统推送</option>					
                </select>
            </td>
        </tr>
        <tr style="display:none;" id="show_search_goods2">
            <td class="label"><?php echo self::$_var['lang']['goods_name']; ?></td>
            <td>
                <input type="text"  name="goods_name2" id="search_goods_name2" value="">
                <input type="hidden"  name="one_goods_id2" id="one_goods_id2" value="">
                <a  onclick="javascript:searchGoodsname2($('#search_goods_name2').val());" href="javascript:void(0);" id="btn_search_goods2" class="isubmit2Btn"><i class="icon-search"></i>搜索</a>
                <p class="hint" style="color:#cccccc;">请输入商品id或商品名称或商品货号进行搜索，并点击选中</p>
                <div id="div_goods_search_result2" class="search-result" style="cursor:pointer;">
                    <style type="text/css">
                        li:hover{
                            color:red;
                        }
                    </style>
                    <div id="brandDiv2" >
                        <ul id="ulBrand2"></ul>
                    </div>
                </div>
            </td>
        </tr>
        <tr  id="tuisong_the_user2">
            <td class="label"><?php echo self::$_var['lang']['the_user']; ?></td>
            <td>
                <input type="text"  name="user_name" id="search_user_name" value="" >
                <input type="hidden"  name="one_user_id" id="one_user_id" value="" >
                <a  onclick="javascript:searchUsername($('#search_user_name').val());" href="javascript:void(0);" id="btn_user_goods" class="isubmit2Btn"><i class="icon-search"></i>搜索</a>
                <p class="hint1" style="color:#cccccc;">请输入用户id或用户名称或用户昵称进行搜索，并点击选中</p>
                <div id="div_user_search_result" class="search-result" style="cursor:pointer;">
                    <style type="text/css">
                        li:hover{
                            color:red;
                        }
                    </style>
                    <div id="brandDiv1" >
                        <ul id="ulBrand1"></ul>
                    </div>
                </div>
            </td>
        </tr>
        <tr style="display:none;" id="tuisong_tr_title2">
            <td class="label">推送标题</td>
            <td>
                <input type="text"  name="tuisong_title2" id="tuisong_title2" value="">
            </td>
        <tr>
            <td class="label"><?php echo self::$_var['lang']['push_content']; ?></td>
            <td>
                <textarea name="push_content" id="goods_brief1" cols="40" rows="3" placeholder="限制100字"></textarea>
            </td>
        </tr>
    </table>
    
    <table width="100%" id="attribute_to_push" style="display:none" align="center">
        <tr>
            <td class="label">推送类型</td>
            <td>
                <select name="push_types2" id="push_types2" style="margin-left:0px;">
                    <option value="0">请选择</option>
                    <option value="2">公告推送</option>
                </select>
            </td>
        </tr>
        <tr style="display:none;" id="show_search_goods3">
            <td class="label"><?php echo self::$_var['lang']['goods_name']; ?></td>
            <td>
                <input type="text"  name="goods_name3" id="search_goods_name3" value="">
                <input type="hidden"  name="one_goods_id3" id="one_goods_id3" value="">
                <a  onclick="javascript:searchGoodsname3($('#search_goods_name3').val());" href="javascript:void(0);" id="btn_search_goods3" class="isubmit2Btn"><i class="icon-search"></i>搜索</a>
                <p class="hint" style="color:#cccccc;">请输入商品id或商品名称或商品货号进行搜索，并点击选中</p>
                <div id="div_goods_search_result3" class="search-result" style="cursor:pointer;">
                    <style type="text/css">
                        li:hover{
                            color:red;
                        }
                    </style>
                    <div id="brandDiv3" >
                        <ul id="ulBrand3"></ul>
                    </div>
                </div>
            </td>
        </tr>
        <tr style="display:none;" id="tuisong_tr_title3">
            <td class="label">推送标题</td>
            <td>
                <input type="text"  name="tuisong_title3" id="tuisong_title3" value="">
            </td>
        </tr>
        <tr>
            <td class="label">上传图片：</td>
            <td>
                <input type="file"  name="fu_UploadFile" id="fu_UploadFile" >
            </td>
        </tr>
        <tr>
            <td class="label"><?php echo self::$_var['lang']['push_content']; ?></td>
            <td>
                <textarea name="push_content" id="goods_brief2" cols="40" rows="3" placeholder="限制100字"></textarea>
            </td>
        </tr>
        <tr>
            <td class="label"><?php echo self::$_var['lang']['push_people']; ?></td>
            <td>
                <input type="radio" name="vip_b" value="1" />
                <?php echo self::$_var['lang']['vip1']; ?>
                <input type="radio" name="vip_b" value="2" />
                <?php echo self::$_var['lang']['vip2']; ?>
                <input type="radio" name="vip_b" value="3" />
                <?php echo self::$_var['lang']['vip3']; ?>
                <input type="radio" name="vip_b" value="4" />
                <?php echo self::$_var['lang']['vip4']; ?>
                <input type="radio" name="vip_b" value="5" />
                <?php echo self::$_var['lang']['vip5']; ?>
                <input type="radio" name="vip_b" value="6" />
                普通用户
                <input type="radio" name="vip_b" value="7" />
                VIP用户
                <input type="radio" name="vip_b" value="8" />
                全部用户
            </td>
        </tr>
    </table>
    
    <table width="100%" id="all_push" style="display:none" align="center">
        <tr>
            <td class="label">推送类型</td>
            <td>
                <select name="push_types3" id="push_types3" style="margin-left:0px;">
                    <option value="0">请选择</option>
                    <option value="1">商品推送</option>
                    <option value="2">公告推送</option>
                    <option value="3">系统推送</option>
                    <option value="4">工单推送</option>						
                </select>
            </td>
        </tr>
        <tr style="display:none;" id="show_search_goods4">
            <td class="label"><?php echo self::$_var['lang']['goods_name']; ?></td>
            <td>
                <input type="text"  name="goods_name4" id="search_goods_name4" value="">
                <input type="hidden"  name="one_goods_id4" id="one_goods_id4" value="">
                <a  onclick="javascript:searchGoodsname4($('#search_goods_name4').val());" href="javascript:void(0);" id="btn_search_goods4" class="isubmit2Btn"><i class="icon-search"></i>搜索</a>
                <p class="hint" style="color:#cccccc;">请输入商品id或商品名称或商品货号进行搜索，并点击选中</p>
                <div id="div_goods_search_result4" class="search-result" style="cursor:pointer;">
                    <style type="text/css">
                        li:hover{
                            color:red;
                        }
                    </style>
                    <div id="brandDiv4" >
                        <ul id="ulBrand4"></ul>
                    </div>
                </div>
            </td>
        </tr>
        <tr style="display:none;" id="tuisong_tr_title4">
            <td class="label">推送标题</td>
            <td>
                <input type="text"  name="tuisong_title4" id="tuisong_title4" value="">
            </td>
        <tr>
            <td class="label"><?php echo self::$_var['lang']['push_content']; ?></td>
            <td>
                <textarea name="push_content" id="goods_brief3" cols="40" rows="3" placeholder="限制100字"></textarea>
            </td>
        </tr>
    </table>
    <div class="button-div">
        <input id="goods_info_submit" type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button"/>
        <input id="goods_info_reset" type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
    </div>
</div>
<script language="JavaScript">
    function set1() {
        document.getElementById("general-table").style.display = "block";
        document.getElementById("people_push").style.display = "none";
        document.getElementById("attribute_to_push").style.display = "none";
        document.getElementById("all_push").style.display = "none";
        document.getElementById("general-tab").style.backgroundColor = "#E6E6E6";
        document.getElementById("detail-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("mix-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("properties-tab").style.backgroundColor = "#F7F7F7";
    }
    function set2() {
        document.getElementById("general-table").style.display = "none";
        document.getElementById("people_push").style.display = "block";
        document.getElementById("attribute_to_push").style.display = "none";
        document.getElementById("all_push").style.display = "none";
        document.getElementById("general-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("detail-tab").style.backgroundColor = "#E6E6E6";
        document.getElementById("mix-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("properties-tab").style.backgroundColor = "#F7F7F7";
    }
    function set3() {
        document.getElementById("general-table").style.display = "none";
        document.getElementById("people_push").style.display = "none";
        document.getElementById("attribute_to_push").style.display = "block";
        document.getElementById("all_push").style.display = "none";
        document.getElementById("general-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("detail-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("mix-tab").style.backgroundColor = "#E6E6E6";
        document.getElementById("properties-tab").style.backgroundColor = "#F7F7F7";
    }
    function set4() {
        document.getElementById("general-table").style.display = "none";
        document.getElementById("people_push").style.display = "none";
        document.getElementById("attribute_to_push").style.display = "none";
        document.getElementById("all_push").style.display = "block";
        document.getElementById("general-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("detail-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("mix-tab").style.backgroundColor = "#F7F7F7";
        document.getElementById("properties-tab").style.backgroundColor = "#E6E6E6";
    }


    $("#goods_info_submit").click(
            function () {
                var send_content = '';
                var tab1 = $('#general-table').css('display');
                var tab2 = $('#people_push').css('display');
                var tab3 = $('#attribute_to_push').css('display');
                var tab4 = $('#all_push').css('display');
                if (tab1 != 'none') {
                    var is_android = document.getElementById('is_android1').checked;
//                    $("[name='is_android1']").attr("checked");
                    var is_ios = document.getElementById('is_ios1').checked;
//                            $("[name='is_ios1']").attr("checked");
                    
                    alert('安卓：'+is_android+'//'+is_ios);
                    //选择VIP
                    var vip_check= 0;  
                    var checkboxStr=document.getElementsByName("vip_a");    
                    for(var i=0; i<checkboxStr.length; i++){  
                        if(checkboxStr[i].checked){  
                           vip_check = checkboxStr[i].value;
                        }  
                    }
                    
                    send_content = $("#goods_brief").val();
                    var one_goods_id = $("#one_goods_id1").val();
                    var goods_name = $("#search_goods_name").val();

                    if (send_content.length < 1) {
                        alert('推送内容不能为空');
                        return;
                    } else if (send_content.length > 200) {
                        alert('推送内容不能超过100字');
                        return;
                    } else if (is_android == false && is_ios == false) {
                        alert('安卓和ios至少选一个');
                        return;
                    }
//                                else if(is_vip1 == false && is_vip2 == false && is_vip3 == false && is_vip4 == false && is_vip5 == false){
//					alert('vip等级至少选一个');
//					return;
//				}
                    else if (one_goods_id < 1) {
                        alert('选择商品异常');
                        return;
                    }
                    
                    $.post("index.php?act=app_tuisong&op=tuisong_goods",
                            {send_content: send_content, is_android: is_android, is_ios: is_ios, 
                                vip_check: vip_check, one_goods_id: one_goods_id, goods_name: goods_name},
                            function (data) {
                                alert(data.msgs);
                            }, "json");

                } else if (tab2 != 'none') {
                    var one_user_id = $("#one_user_id").val();
                    send_content = $("#goods_brief1").val();
                    var one_goods_id = $("#one_goods_id2").val();
                    var tuisong_title = '';
                    tuisong_title = $("#search_goods_name2").val();
                    var push_types1 = $("#push_types1").val();
                    if (push_types1 < 1) {
                        alert('请选择推送类型');
                        return;
                    }

                    if ($("#push_types1").val() == 3) {
                        tuisong_title = $("#tuisong_title2").val();
                        if (tuisong_title.length < 1) {
                            alert('标题不能为空');
                            return;
                        } else if (tuisong_title.length > 16) {
                            alert('标题不能太长');
                            return;
                        }
                    }

                    if (one_user_id.length < 1) {
                        alert('必须选择要发送的用户名');
                        return;
                    } else if (send_content.length < 1) {
                        alert('推送内容不能为空');
                        return;
                    } else if (send_content.length > 200) {
                        alert('推送内容不能超过100字');
                        return;
                    } else if ($("#push_types1").val() == 1 && one_goods_id < 1) {
                        alert('选择商品异常');
                        return;
                    }
                    $.post("index.php?act=app_tuisong&op=tuisong_person",
                            {send_content: send_content, user_id: one_user_id, push_types: push_types1,
                                one_goods_id: one_goods_id, tuisong_title: tuisong_title},
                            function (data) {
                                alert(data.msgs);
                            }, "json");

                } else if (tab3 != 'none') {
                    var vip_check= 0;  
                    var checkboxStr=document.getElementsByName("vip_b");    
                    for(var i=0; i<checkboxStr.length; i++){  
                        if(checkboxStr[i].checked){  
                           vip_check = checkboxStr[i].value; 
                        }
                    } 
                    
                    send_content = $("#goods_brief2").val();
                    var one_goods_id = $("#one_goods_id3").val();

                    var push_types2 = $("#push_types2").val();
                    if (push_types2 < 1) {
                        alert('请选择推送类型');
                        return;
                    }

                    var tuisong_title = '';
                    if ($("#push_types2").val() == 2) {
                        tuisong_title = $("#tuisong_title3").val();
                        if (tuisong_title.length < 1) {
                            alert('标题不能为空');
                            return;
                        } else if (tuisong_title.length > 16) {
                            alert('标题不能太长');
                            return;
                        }
                    }

                    if (send_content.length < 1) {
                        alert('推送内容不能为空');
                        return;
                    } else if (send_content.length > 200) {
                        alert('推送内容不能超过100字');
                        return;
                    } else if (is_android == false && is_ios == false) {
                        alert('安卓和ios至少选一个');
                        return;
                    } 
//                    else if (is_vip1 == false && is_vip2 == false && is_vip3 == false && is_vip4 == false && is_vip5 == false) {
//                        alert('vip等级至少选一个');
//                        return;
//                    } 
                    else if ($("#push_types1").val() == 1 && one_goods_id < 1) {
                        alert('选择商品异常');
                        return;
                    }
                    
                    $.ajaxFileUpload({
                        url: "index.php?act=app_tuisong&op=tuisong_attr",
                        type: 'post',
                        secureuri: false, //一般设置为false
                        fileElementId: 'fu_UploadFile', // 上传文件的id、name属性名
                        dataType: 'json', //返回值类型，一般设置为json、application/json
                        data: {send_content: send_content, vip_check:vip_check,tuisong_title: tuisong_title},
                        success: function (data) {
                                if (data.Result) {
                                    alert("文件成功处理完成!" + data.FileName);
                                } else {
                                    alert("文件成功处理出错！原因：" + data.ErrorMessage);
                                }
                        },
                        error: function (data, status, e) {
                            alert("错误：上传组件错误，请检察网络!");
                        }
                    });

                } else if (tab4 != 'none') {
                    send_content = $("#goods_brief3").val();
                    var one_goods_id = $("#one_goods_id4").val();

                    var push_types3 = $("#push_types3").val();
                    if (push_types3 < 1) {
                        alert('请选择推送类型');
                        return;
                    }
                    var tuisong_title = '';
                    if ($("#push_types3").val() == 2 || $("#push_types3").val() == 3) {
                        tuisong_title = $("#tuisong_title4").val();
                        if (tuisong_title.length < 1) {
                            alert('标题不能为空');
                            return;
                        } else if (tuisong_title.length > 16) {
                            alert('标题不能太长');
                            return;
                        }
                    }

                    if (send_content.length < 1) {
                        alert('推送内容不能为空');
                        return;
                    } else if (send_content.length > 200) {
                        alert('推送内容不能超过100字');
                        return;
                    } else if ($("#push_types1").val() == 1 && one_goods_id < 1) {
                        alert('选择商品异常');
                        return;
                    }

                    $.post("index.php?act=app_tuisong&op=tuisong4",
                            {send_content: send_content, push_types: push_types3, one_goods_id: one_goods_id, tuisong_title: tuisong_title},
                            function (data) {
                                alert(data.msgs);
                            }, "json");

                }
            }

    );

    var searchGoodsname = function (goodInfo) {
        $("#ulBrand").children('li').remove();
        $(".hint").hide();
        if (goodInfo) {
            $.post("index.php?act=app_tuisong&op=search_goods", {goods_name: goodInfo}, function (data) {
                $.each(data,
                        function (n, value) {
                            var usernameLi = "<li goods_name='" + value.goods_name + "' goods_brief='" + value.goods_brief + "' style='list-style:none;margin-left:-105px;'><label>" + value.goods_id + "</label> " + value.goods_name + "(" + value.goods_sn + ")" + " </li>"
                            $("#ulBrand").append(usernameLi);
                        }
                );

                $("#ulBrand>li").bind("click", function () {
                    var GoodId = $(this).children("label").html();
                    var isGoodId = /^\d+$/;

                    if (!isGoodId.test(GoodId)) {
                        alert("商品id不存在!");
                        return false;
                    }
                    $("#search_goods_name").val($(this).attr("goods_name"));
                    $("#one_goods_id1").val($(this).find('label').html());
                    $("#goods_brief").val($(this).attr("goods_brief"));
                    $("#ulBrand").children('li').remove();
                });

            }, "json");
        }
    };

    var searchUsername = function (userInfo) {
        $("#ulBrand1").children('li').remove();
        $(".hint1").hide();
        if (userInfo) {
            $.post("index.php?act=app_tuisong&op=search_user", {user_name: userInfo}, function (data) {

                $.each(data,
                        function (n, value) {
                            var usernameLi = "<li user_name='" + value.user_name + "' style='list-style:none;margin-left:-105px;'><label>" + value.user_id + "</label> " + value.user_name + "(" + value.alias + ")" + " </li>"
                            $("#ulBrand1").append(usernameLi);
                        }

                );
                $("#ulBrand1>li").bind("click", function () {
                    var UserId = $(this).children("label").html();
                    var isUserId = /^\d+$/;
                    if (!isUserId.test(UserId)) {
                        alert("用户id不存在!");
                        return false;
                    } else {
                        $("#one_user_id").val(UserId);
                    }
                    $("#search_user_name").val($(this).attr("user_name"));
                    $("#ulBrand1").children('li').remove();
                });

            }, "json");
        }
    };

    var searchGoodsname2 = function (goodInfo) {
        $("#ulBrand2").children('li').remove();
        $(".hint").hide();
        if (goodInfo) {
            $.post("index.php?act=app_tuisong&op=search_goods", {goods_name: goodInfo}, function (data) {
                $.each(data,
                        function (n, value) {
                            var usernameLi = "<li goods_name='" + value.goods_name + "' goods_brief='" + value.goods_brief + "' style='list-style:none;margin-left:-105px;'><label>" + value.goods_id + "</label> " + value.goods_name + "(" + value.goods_sn + ")" + " </li>"
                            $("#ulBrand2").append(usernameLi);
                        }
                );

                $("#ulBrand2>li").bind("click", function () {
                    var GoodId = $(this).children("label").html();
                    var isGoodId = /^\d+$/;

                    if (!isGoodId.test(GoodId)) {
                        alert("商品id不存在!");
                        return false;
                    }
                    $("#search_goods_name2").val($(this).attr("goods_name"));
                    $("#one_goods_id2").val($(this).find('label').html());
                    $("#goods_brief1").val($(this).attr("goods_brief"));
                    $("#ulBrand2").children('li').remove();
                });

            }, "json");
        }
    };

    var searchGoodsname3 = function (goodInfo) {
        $("#ulBrand3").children('li').remove();
        $(".hint").hide();
        if (goodInfo) {
            $.post("index.php?act=app_tuisong&op=search_goods", {goods_name: goodInfo}, function (data) {
                $.each(data,
                        function (n, value) {
                            var usernameLi = "<li goods_name='" + value.goods_name + "' goods_brief='" + value.goods_brief + "' style='list-style:none;margin-left:-105px;'><label>" + value.goods_id + "</label> " + value.goods_name + "(" + value.goods_sn + ")" + " </li>"
                            $("#ulBrand3").append(usernameLi);
                        }
                );

                $("#ulBrand3>li").bind("click", function () {
                    var GoodId = $(this).children("label").html();
                    var isGoodId = /^\d+$/;

                    if (!isGoodId.test(GoodId)) {
                        alert("商品id不存在!");
                        return false;
                    }
                    $("#search_goods_name3").val($(this).attr("goods_name"));
                    $("#one_goods_id3").val($(this).find('label').html());
                    $("#goods_brief2").val($(this).attr("goods_brief"));
                    $("#ulBrand3").children('li').remove();
                });

            }, "json");
        }
    };

    var searchGoodsname4 = function (goodInfo) {
        $("#ulBrand4").children('li').remove();
        $(".hint").hide();
        if (goodInfo) {
            $.post("index.php?act=app_tuisong&op=search_goods", {goods_name: goodInfo}, function (data) {
                $.each(data,
                        function (n, value) {
                            var usernameLi = "<li goods_name='" + value.goods_name + "' goods_brief='" + value.goods_brief + "' style='list-style:none;margin-left:-105px;'><label>" + value.goods_id + "</label> " + value.goods_name + "(" + value.goods_sn + ")" + " </li>"
                            $("#ulBrand4").append(usernameLi);
                        }
                );

                $("#ulBrand4>li").bind("click", function () {
                    var GoodId = $(this).children("label").html();
                    var isGoodId = /^\d+$/;

                    if (!isGoodId.test(GoodId)) {
                        alert("商品id不存在!");
                        return false;
                    }
                    $("#search_goods_name4").val($(this).attr("goods_name"));
                    $("#one_goods_id4").val($(this).find('label').html());
                    $("#goods_brief3").val($(this).attr("goods_brief"));
                    $("#ulBrand4").children('li').remove();
                });

            }, "json");
        }
    };

    $("#push_types1").change(
            function () {
                if ($(this).val() == 1) {
                    $("#show_search_goods2").css('display', '');
                } else {
                    $("#show_search_goods2").css('display', 'none');
                }
                if ($(this).val() == 2 || $(this).val() == 3) {
                    $("#tuisong_tr_title2").css('display', '');
                    if ($(this).val() == 2) {
                        $("#tuisong_the_user2").css('display', 'none');
                    } else {
                        $("#tuisong_the_user2").css('display', '');
                    }
                } else {
                    $("#tuisong_tr_title2").css('display', 'none');
                }

            }
    );

    $("#push_types2").change(
            function () {
                if ($(this).val() == 1) {
                    $("#show_search_goods3").css('display', '');
                } else {
                    $("#show_search_goods3").css('display', 'none');
                }
                if ($(this).val() == 2 || $(this).val() == 3) {
                    $("#tuisong_tr_title3").css('display', '');
                } else {
                    $("#tuisong_tr_title3").css('display', 'none');
                }
            }
    );

    $("#push_types3").change(
            function () {
                if ($(this).val() == 1) {
                    $("#show_search_goods4").css('display', '');
                    $("#tuisong_tr_title4").css('display', '');
                } else {
                    $("#show_search_goods4").css('display', 'none');
                    $("#tuisong_tr_title4").css('display', 'none');
                }
                if ($(this).val() == 2 || $(this).val() == 3) {
                    $("#tuisong_tr_title4").css('display', '');
                } else {
                    $("#tuisong_tr_title4").css('display', 'none');
                }
            }
    );
</script>
