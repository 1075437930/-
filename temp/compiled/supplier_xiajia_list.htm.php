
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js,js/jquery-1.6.2.min.js,js/chosen.jquery.min.js')); ?>
<script language="JavaScript">

// 这里把JS用到的所有语言都赋值到这里

    <?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

    var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

</script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<link href='<?php echo self::$_var['urls_dir']; ?>/styles/store.css' rel='stylesheet' type='text/css' />
<link href='<?php echo self::$_var['urls_dir']; ?>/styles/chosen/chosen.css' rel='stylesheet' type='text/css' />
<div class="list-div">
    <div class="rebate-detaile">
        <div class="rebate_item rebate_shop-item">
            <div class="item-hd">
                <p>下架产品总额</p>
                <p><?php echo self::$_var['tot']['tot_fenxiao_price']; ?>元</p>
            </div>
        </div>
        <div class="rebate_item rebate_goods-item">
            <div class="item-hd">
                <p>平台收入总额</p>
                <p>+<?php echo self::$_var['tot']['tot_pay_money']; ?>元</p>
            </div>
        </div>
        <div class="rebate_item rebate_order-item">
            <div class="item-hd">
                <p>平台支出总额</p>
                <p>0元</p>
            </div>
        </div>
    </div>
</div>
<div class="form-div">
    <form action="index.php?act=supplier_rebate" method="post" name="searchForm">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <!-- <td width="7%" align="right">商家名称：</td> -->
                <td width="100%" align="left">
                    <!-- <input type="text" name="" /> -->
                    <select class="chzn-select" data-placeholder="商家名称" style="width:350px;" name="suppid"> 
                        <option value=""></option>
                        
                        <option value="">全部</option>
                        

                        <?php $_from = self::$_var['supplier_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'supp');if (count($_from)):
    foreach ($_from AS self::$_var['supp']):
?>
                        <option value="<?php echo self::$_var['supp']['supplier_id']; ?>"><?php echo self::$_var['supp']['supplier_name']; ?></option>  
                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                    </select>
                    <a href='javascript:search_submit()' class="button_round" title="搜索" >搜索</a>
                    <a class="button_round" title="批量导出搜索结果" onclick="exportSupps()">下载</a>
                </td>
            </tr>
        </table>
    </form>
    <div style="float: right ;margin-top: -37px "><a class="button_round" href="index.php?act=suprebate&op=lists"><?php echo self::$_var['msg']; ?></a></div>
</div>

<form method="post" action="" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
    <div class="list-div" id="listDiv">
        <?php endif; ?>

        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>商家名称</th>
                <th><a href="javascript:listTable.sort('rebate_money'); ">下架商品总额（元）</a></th>
                <th><a href="javascript:listTable.sort('result_money'); ">下架佣金抽成总额（元）</a></th>
                <th>操作</th>
            </tr>
            <?php $_from = self::$_var['money']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'v');if (count($_from)):
    foreach ($_from AS self::$_var['v']):
?>
            <tr>
                <td align="center"><?php echo self::$_var['v']['supplier_name']; ?></td>
                <td align="center"><?php echo self::$_var['v']['fenxiao_price']; ?></td>
                <td align="center">+<?php echo self::$_var['v']['pay_money']; ?></td>
                <td align="center"><a href="index.php?act=suprebate&op=xiajia_view&suppid=<?php echo self::$_var['v']['supplier_id']; ?>" title="查看"><img src="templates/default/images/icon_view.gif" border="0" height="16" width="16" /></a></td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td class="no-records" colspan="5"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
            <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
            
        </table>
        <table id="page-table" cellspacing="0">
            <tr>
                <td>&nbsp;</td>
                <td align="right" nowrap="true">
                    <?php echo self::fetch('page.htm'); ?>
                </td>
            </tr>
        </table>

        <?php if (self::$_var['full_page']): ?>
    </div>
</form>

<script type="text/javascript" language="javascript">

    listTable.recordCount = '<?php echo self::$_var['record_count']; ?>';
    listTable.pageCount = '<?php echo self::$_var['page_count']; ?>';
    listTable.act = 'suprebate';
    listTable.query = 'xiajia_query';
   <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
  listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

    

    /**
     * 搜索订单
     */
    function searchRebate()
    {
    listTable.filter['suppid'] = Utils.trim(document.forms['searchForm'].elements['suppid'].value);
    listTable.filter['page'] = 1;
    listTable.loadList();
    }
    function search_submit(){
    //listTable.query = "search_query";
    searchRebate();
    }

    function exportSupps()
    {
    var frm = document.forms['searchForm'];
    frm.action = "index.php?act=suprebate&op=export_supps_xiajia&is_export=1";
    frm.submit();
    }
    
            //-->
</script>
<script type="text/javascript">
            $().ready(function(){
    $(".chzn-select").chosen();
    });
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>