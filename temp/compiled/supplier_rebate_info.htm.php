

<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>

<?php echo self::smarty_insert_scripts(array('files'=>'js/topbar.js,js/utils.js,js/listtable.js,js/selectzone.js,js/common.js,js/jquery-1.6.2.min.js,js/chosen.jquery.min.js')); ?>

<script language="JavaScript">

    // 这里把JS用到的所有语言都赋值到这里

    <?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
        var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

</script>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css"/>

<link href='<?php echo self::$_var['urls_dir']; ?>/styles/store.css' rel='stylesheet' type='text/css'/>

<link href='<?php echo self::$_var['urls_dir']; ?>/styles/chosen/chosen.css' rel='stylesheet' type='text/css'/>



<div class="form-div">

    <form action="index.php" name="searchForm" method="post">

        <input type="hidden" name="suppid" value="<?php echo $_REQUEST['suppid']; ?>">

        <table width="100%" cellpadding="0" cellspacing="0" border="0">

            <tr>
                <td width="9%" align="right">账户变动时间：</td>

                <td colspan="3">
                    <input name="start_time" type="text" id="start_time" value="<?php echo self::$_var['favourable']['start_time']; ?>"
                           readonly="readonly"/>
                    <input name="selbtn1" type="button" id="selbtn1"
                           onclick="return showCalendar('start_time', '%Y-%m-%d %H:%M', '24', false, 'selbtn1');"
                           value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>
                    &nbsp;-&nbsp;
                    <input name="end_time" type="text" id="end_time" value="<?php echo self::$_var['favourable']['end_time']; ?>"
                           readonly="readonly"/>
                    <input name="selbtn2" type="button" id="selbtn2"
                           onclick="return showCalendar('end_time', '%Y-%m-%d %H:%M', '24', false, 'selbtn2');"
                           value="<?php echo self::$_var['lang']['btn_select']; ?>" class="button"/>

                    <a href="javascript:;" class="rebate_time"
                       onclick="set_time(this, '<?php echo self::$_var['today']['start']; ?>', '<?php echo self::$_var['today']['ends']; ?>')">今日</a>

                    <a href="javascript:;" class="rebate_time"
                       onclick="set_time(this, '<?php echo self::$_var['yestoday']['start']; ?>', '<?php echo self::$_var['yestoday']['ends']; ?>')">昨日</a>

                    <a href="javascript:;" class="rebate_time"
                       onclick="set_time(this, '<?php echo self::$_var['week']['start']; ?>', '<?php echo self::$_var['week']['ends']; ?>')">最近7天</a>

                    <a href="javascript:;" class="rebate_time"
                       onclick="set_time(this, '<?php echo self::$_var['month']['start']; ?>', '<?php echo self::$_var['month']['ends']; ?>')">最近30天</a>

                </td>

            </tr>

            <tr>

                <td width="9%" align="right">支付方式：</td>

                <td width="15%">

                    <select class="chzn-select" name='payid' id="payid" style='width:123px;'>

                        <option value='-1' selected='selected'>全部</option>

                        <?php $_from = self::$_var['payinfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'pay');if (count($_from)):
    foreach ($_from AS self::$_var['pay']):
?>

                        <option value='<?php echo self::$_var['pay']['pay_id']; ?>'><?php echo self::$_var['pay']['pay_name']; ?></option>

                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

                    </select></td>

                <td width="67%">

                    <select class="chzn-select" data-placeholder="商家订单号" style="width:350px;" name="orderid">

                        <option value=""></option>

                        

                        <option value="">全部</option>

                        

                        <?php $_from = self::$_var['supplier_order']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'supp');if (count($_from)):
    foreach ($_from AS self::$_var['supp']):
?>

                        <option value="<?php echo self::$_var['supp']['order_id']; ?>"><?php echo self::$_var['supp']['order_sn']; ?></option>

                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

                    </select>


                    <a class="button_round" onclick="searchSupp()">搜索</a>

                    <a class="button_round" onclick="exportGoods()">批量导出</a>

                </td>

            </tr>

        </table>

    </form>

</div>

<div class="list-div" id="listDiv">

    <?php endif; ?>

    <table cellpadding="3" cellspacing="1">

        <tr>
            <th>商家名称</th>
            <th><a href="javascript:listTable.sort('sr.add_time'); ">账户变动时间</a></th>

            <th>订单号</th>

            <th>订单金额（元）</th>

            <th>平台扣除佣金（元）</th>

            <th>商家实际收入金额（元）</th>

            <th>支付方式</th>

            <th>备注</th>

        </tr>

        <?php $_from = self::$_var['supplier_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'supplier');if (count($_from)):
    foreach ($_from AS self::$_var['supplier']):
?>

        <tr>
            <td align="center"><?php echo self::$_var['supplier_name']; ?></td>
            <td align="center"><?php echo self::$_var['supplier']['add_time']; ?></td>

            <td align="center"><?php echo self::$_var['supplier']['order_sn']; ?></td>

            <td align="center"><?php echo self::$_var['supplier']['all_money']; ?></td>

            <td align="center">-<?php echo self::$_var['supplier']['rebate_money']; ?></td>

            <td align="center" class="add">+<?php echo self::$_var['supplier']['result_money']; ?></td>

            <td align="center"><?php echo self::$_var['supplier']['pay_name']; ?></td>

            <td align="center"><?php echo self::$_var['supplier']['texts']; ?></td>

        </tr>

        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

        

        <!--<tr><td class="no-records" colspan="7"><?php echo self::$_var['lang']['no_records']; ?></td></tr>-->


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



<script language="JavaScript">

    listTable.query = "search_supp_query";
    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.act = 'suprebate';
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
        listTable.filter.<?php echo self::$_var['key']; ?>= '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


    function check(status) {

        if (status <= 0) {//冻结状态下结算佣金验证

            var snArray = new Array();
            var eles = document.forms['theForm'].elements;
            for (var i = 0; i < eles.length; i++) {

                if (eles[i].tagName == 'INPUT' && eles[i].type == 'checkbox' && eles[i].checked && eles[i].value != 'on') {

                    snArray.push(eles[i].value);
                }

            }

            if (snArray.length == 0) {

                alert('请选择要结算的订单!');
                return false;
            } else {

                eles['order_id'].value = snArray.toString();
                return true;
            }

        } else if (status == 1) {//可结算状态下撤销全部佣金

            if (confirm('撤销后，佣金状态由可结算将回归到冻结状态')) {

                return true;
            } else {

                return false;
            }

        }

    }


    function set_time(obj, starttime, endtime) {


        if ($(obj).hasClass("cur")) {

            $('.rebate_time').removeClass("cur");
            $('#start_time').val('');
            $('#end_time').val('');
        } else {

            $('.rebate_time').removeClass("cur");
            $(obj).addClass("cur");
            $('#start_time').val(starttime);
            $('#end_time').val(endtime);
        }

    }


    function searchSupp() {

        listTable.filter['start_time'] = Utils.trim(document.forms['searchForm'].elements['start_time'].value);
        listTable.filter['end_time'] = Utils.trim(document.forms['searchForm'].elements['end_time'].value);
        listTable.filter['payid'] = Utils.trim(document.forms['searchForm'].elements['payid'].value);
        listTable.filter['orderid'] = Utils.trim(document.forms['searchForm'].elements['orderid'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }


    function exportGoods() {

        var frm = document.forms['searchForm'];
        frm.action = "index.php?act=suprebate&op=export_goods&supplier_name=<?php echo self::$_var['supplier_name']; ?>";
        frm.submit();
    }


</script>


<script type="text/javascript">

    $().ready(function () {

        $(".chzn-select").chosen();
    });

</script>




<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>