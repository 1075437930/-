<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>商之翼 管理中心 - 大师分类列表 </title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo self::$_var['urls_dir']; ?>/styles/general.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo self::$_var['urls_dir']; ?>/styles/main.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo self::$_var['urls_dir']; ?>/styles/chosen/chosen.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.js"></script><script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/common.js"></script>
    <script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery-1.10.1.js"></script>
    <script src="<?php echo self::$_var['urls_dir']; ?>/js/layer/layer.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="index.php?act=master&op=master_type_add">添加大师分类</a></span>
    <span class="action-span1"><a href="index.php?act=main">商之翼 管理中心</a> </span><span id="search_id" class="action-span1"> - 大师分类列表 </span>
    <div style="clear:both"></div>
</h1>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>//js/utils.js"></script><script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script><script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/placeholder.js"></script><div class="form-div">
    <form action="index.php?act=master&op=master_type_list" method="post" name="searchForm">
        大师分类名称&nbsp;
        <span style="position:relative"><input type="text" name="keywords" value="<?php echo self::$_var['keywords']; ?>" placeholder="大师分类名称" /></span>
        <input type="submit" class="button" value=" 搜索 " />
    </form>
</div>
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>

                    <a href="index.php?act=master&op=master_type_list&sort=<?php echo self::$_var['sort']; ?>&pagesize=<?php echo self::$_var['pagesize']; ?>&newpage=<?php echo self::$_var['newpage']; ?>&keywords=<?php echo self::$_var['keywords']; ?>">编号</a>
                    <img src="templates/default/images/sort_<?php echo self::$_var['sort']; ?>.gif">				</th>
                <th>大师分类名字</th>
                <th>大师分类图</th>
                <th>
                    <a href="index.php?act=master&op=master_type_list&sort=<?php echo self::$_var['sort']; ?>&pagesize=<?php echo self::$_var['pagesize']; ?>&keywords=<?php echo self::$_var['keywords']; ?>">大师添加时间</a>
                </th>
                <th>分类状态</th>
                <th>操作</th>
            </tr>
            <?php $_from = self::$_var['res']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'v');if (count($_from)):
    foreach ($_from AS self::$_var['v']):
?>
            <tr align="center" id="tb_<?php echo self::$_var['v']['id']; ?>">
                <td>

                    		<?php echo self::$_var['v']['id']; ?></td>
                <td class="first-cell"><?php echo self::$_var['v']['master_type']; ?></td>
                <td><img width="50" height="50" src="<?php echo self::$_var['v']['pic']; ?>" alt=""></td>
                <td><?php echo self::$_var['v']['addtime']; ?></td>
                <td>
                    <input type="hidden" value="<?php echo self::$_var['v']['status']; ?>" id="hid_<?php echo self::$_var['v']['id']; ?>">
                    <?php if (self::$_var['v']['status'] == 1): ?>
                    <img onclick="changestatus(<?php echo self::$_var['v']['id']; ?>)" src="templates/default/images/ture.png" class="img_<?php echo self::$_var['v']['id']; ?>" style="width: 20px;height: 20px" alt="">
                    <?php else: ?>
                    <img onclick="changestatus(<?php echo self::$_var['v']['id']; ?>)" src="templates/default/images/false.png" class="img_<?php echo self::$_var['v']['id']; ?>" style="width: 20px;height: 20px" alt="">
                    <?php endif; ?>

                </td>
                <td align="center">
                    <a href="javascript:update_master_type(<?php echo self::$_var['v']['id']; ?>)" title="编辑">
                        <img src="templates/default/images/icon_edit.gif" border="0" height="16" width="16" />
                    </a>
                    <a href="javascript:del_master_type(<?php echo self::$_var['v']['id']; ?>)" title="移除">
                        <img src="templates/default/images/icon_drop.gif" border="0" height="16" width="16" />
                    </a>
                </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </table>
    </div>
<form method="post" action="index.php?act=master&op=master_type_list&keywords=<?php echo self::$_var['keywords']; ?>">
    <div style="height: 50px;float: right; line-height: 50px">
        总计<?php echo self::$_var['len']; ?>个记录
        分为<?php echo self::$_var['totpage']; ?>页
        当前第<?php echo self::$_var['newpage']; ?>页，
        每页 <input type="text" value="<?php echo self::$_var['pagesize']; ?>" id="pagesize" name='pagesize' style="width: 30px;height: 25px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
        <a href="index.php?act=master&op=master_type_list&pagesize=<?php echo self::$_var['pagesize']; ?>&keywords=<?php echo self::$_var['keywords']; ?>">第一页</a>&nbsp;&nbsp;&nbsp;
        <a href="index.php?act=master&op=master_type_list&newpage=<?php echo self::$_var['lowpage']; ?>&pagesize=<?php echo self::$_var['pagesize']; ?>&keywords=<?php echo self::$_var['keywords']; ?>">上一页</a>&nbsp;&nbsp;&nbsp;
        <a href="index.php?act=master&op=master_type_list&newpage=<?php echo self::$_var['addpage']; ?>&pagesize=<?php echo self::$_var['pagesize']; ?>&keywords=<?php echo self::$_var['keywords']; ?>">下一页</a>&nbsp;&nbsp;&nbsp;
        <a href="index.php?act=master&op=master_type_list&pagesize=<?php echo self::$_var['pagesize']; ?>&newpage=<?php echo self::$_var['totpage']; ?>&keywords=<?php echo self::$_var['keywords']; ?>">最末页</a>&nbsp;&nbsp;&nbsp;
        跳转到
        <input type="text" value="" name="newpage" id="go" style="width: 30px;height: 25px">&nbsp;&nbsp;&nbsp;
        <input type="submit" value="GO">
    </div>
</form>
</body>
</html>
<script>
    function add_type(){
        layer.open({
            type:2,
            title:"添加大师分类",
            shadeClose: true,
            shade: false,
            area: ['400px', '300px'],
            content: ["index.php?act=master&op=master_type_add", 'no']
        })
    }
    function del_master_type(id){
        $.post("index.php?act=master&op=master_type_remove",{id:id},function(data){
            if(data.status > 0){
                $("#tb_"+id).remove();
                layer.alert("删除成功",{icon:6});
            }else if(data.status == -1){
                layer.alert("删除失败",{icon:5});
            }else{
                layer.alert("该分类下有大师不能删除",{icon:5});
            }
        },'json')
    }
    function update_master_type(id){
        layer.open({
            type:2,
            title:"编辑大师分类",
            shadeClose: true,
            shade: false,
            area: ['600px', '600px'],
            content: ["index.php?act=master&op=master_type_edit&id="+id, 'no']
        })
    }
    $("#pagesize").keyup(function(){
        var pagesize = $("#pagesize").val();
        if(isNaN(pagesize)){
            $("#pagesize").val("1");
        }
    })
    $("#go").keyup(function(){
        var go = $("#go").val();
        if(isNaN(go)){
            $("#go").val("1");
        }
    })



    function changestatus(id){
        var status =  $("#hid_"+id).val();
        if(status == 1 ){
            $("#hid_"+id).val(0);
        }else{
            $("#hid_"+id).val(1);
        }
        var newstatus =  $("#hid_"+id).val();
        $.post("index.php?act=master&op=update_type_status",{id:id,status:newstatus},function(data){
            if(data.status > 0){
                if( $("#hid_"+id).val() == 0 ){
                    $(".img_"+id).attr("src","<?php echo self::$_var['urls_dir']; ?>/images/false.png");
                }else{
                    $(".img_"+id).attr("src","<?php echo self::$_var['urls_dir']; ?>/images/ture.png");
                }
            }else{
                if($("#hid_"+id).val() == 0){
                    $("#hid_"+id).val(1);
                }else{
                    $("#hid_"+id).val(0);
                }
            }
        },"json")
    }
</script>

