



<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>

<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js,js/jquery-1.10.1.js')); ?>





<div class="form-div">

    <form action="javascript:searchActplan();" name="searchForm">

        <input type="text" name="keyword" class="iinput" placeholder="请输入商品名称或订单号">

        <input type="submit" class="isubmit" value="搜索">

    </form>

</div>



<?php endif; ?>

<form method="post" action="index.php" name="listForm" onsubmit="return confirmSubmit(this)">

    <?php echo self::fetch('supp_balance_div.htm'); ?>

</form>

<?php if (self::$_var['full_page']): ?>

<script type="text/javascript">

    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;

    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;

    listTable.act = 'sellsuppbalance';

    listTable.query = "query";

    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';

    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

    function searchActplan() {

        var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);

        listTable.filter['keyword'] = keyword;

        listTable.filter['page'] = 1;

        listTable.loadList();

    }

   


    

    /**
     
     * 浏览器兼容式绑定Onload事件
     
     *
     
     */

    if (Browser.isIE)

    {

        window.attachEvent("onload", bind_order_event);

    } else

    {

        window.addEventListener("load", bind_order_event, false);

    }

    function confirmSubmit(frm, ext)

    {

        if (frm.elements['type'].value == 'balance')

        {

            return confirm("确认要批量结算吗？");

        } else if (frm.elements['type'].value == 'drop')

        {

            var checked_num = chekbox_num();

            if (checked_num > 10)

            {

                alert('批量删除不能超过10个');

                return false;

            }

            return confirm("确认要批量删除吗？");

        } else if (frm.elements['type'].value == '')

        {

            return false;

        } else

        {

            return true;

        }

    }



    function changeAction()

    {

        var frm = document.forms['listForm'];



        if (confirmSubmit(frm, false))

        {

            frm.submit();

        }

    }



    var c = 0, limit = 10;

    function doCheck(obj) {

        obj.checked ? c++ : c--;

        if (c > limit) {

            obj.checked = false;

            alert("批量操作不可超过10个");

            c--;

        }

    }



    function chekbox_num() {

        var checks = document.getElementsByName("checkboxes[]");

        var n = 0;

        for (i = 0; i < checks.length; i++) {

            if (checks[i].checked)
                n++;

        }

        return n;

    }
    function passes(balance_price,sell_id){
        var msg = '结算金额为：'+balance_price+'元，您确定要结款吗？';
        var response = confirm(msg);
        if(response==true){
            $.ajax({
              type: 'POST',
              data:{'id':sell_id},
              url: 'index.php?act=sellsuppbalance&op=passes',
              dataType: "json",
              success: function(msg){
                if(msg.status==1){
                    location.href='index.php?act=sellsuppbalance&op=lists';
                }                
              }              
            });
        }
    }

</script>

<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>