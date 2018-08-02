

<div class="form-div">

    <form action="javascript:fenxiao_audit_search()" name="searchForm">

        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />

        <?php echo self::$_var['lang']['fenxiao_search']; ?> <input type="text" name="shop_name" placeholder="请输入店铺名称" value="" size="20" />

        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />

        
         <a href="index.php?act=suppaymodule&op=lists&pay_status=1">未支付</a>
    <a href="index.php?act=suppaymodule&op=lists&pay_status=2">已支付</a>
    <a href="index.php?act=suppaymodule&op=lists&apply_status=1">未审核</a>
    <a href="index.php?act=suppaymodule&op=lists&apply_status=2">已审核</a>
    

    </form>
    
   

</div>



<script language="JavaScript">

    function fenxiao_audit_search()

    {

        listTable.filter['shop_name'] = Utils.trim(document.forms['searchForm'].elements['shop_name'].value);

        listTable.filter['page'] = 1;

        listTable.loadList();

    }

</script>

