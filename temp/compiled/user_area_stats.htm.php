
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/region.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div id="tabbar-div">
    <p>
        <span class="tab-back" onclick="javascript:location.href='index.php?act=user_added_stats&op=lists'">新增会员</span>
        <!-- <span class="tab-back" onclick="javascript:location.href='user_analysis_order_count.php'">会员分析</span>
        <span class="tab-back" onclick="javascript:location.href='user_analysis_scale.php'">会员规模分析</span> -->
        <span class="tab-front" onclick="javascript:location.href='index.php?act=user_area_stats&op=lists'">区域分布</span>
    </p>
</div>
<div class="main-div">
    <p style="margin: 10px">
        1、符合以下任何一种条件的订单即为有效订单：1、采用在线支付方式支付并且已付款；2、采用货到付款方式支付并且交易已完成<br />
        2、点击列表上方的“导出数据”，将列表数据导出为Excel文件<br />
    </p>
</div>
<div class="form-div">
    <form action="javascript:searchAreaStats()" name="searchForm">
        <select name="area_type" id="area_type" onchange="sel_area()">
            <option value="0">按省统计</option>
            <option value="1">按市统计</option>
        </select>
        <select name="province" id="selProvinces">
            <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
            <?php $_from = self::$_var['province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'province');if (count($_from)):
    foreach ($_from AS self::$_var['province']):
?>
            <option value="<?php echo self::$_var['province']['region_id']; ?>"<?php if (self::$_var['province_id'] == self::$_var['province']['region_id']): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['province']['region_name']; ?></option>
            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>
        <select name="city" id="selCities">
            <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
            <?php $_from = self::$_var['city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'city');if (count($_from)):
    foreach ($_from AS self::$_var['city']):
?>
            <option value="<?php echo self::$_var['city']['region_id']; ?>"<?php if (self::$_var['city_id'] == self::$_var['city']['region_id']): ?>selected="selected"<?php endif; ?>><?php echo self::$_var['city']['region_name']; ?></option>
            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
        </select>
        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
        <input type="button" value="导出数据" class="button" onclick="batch_export()" />
    </form>
</div>
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <?php endif; ?>

        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>省份</th>
                <th>市</th>
                <th>区/县</th>
                <th>下单会员数</th>
                <th>下单金额</th>
                <th>下单量</th>
            </tr>

            <?php $_from = self::$_var['result_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['item']):
?>
            <tr>
                <td align="center"><?php echo self::$_var['item']['province_name']; ?></td>
                <td align="center"><?php echo self::$_var['item']['city_name']; ?></td>
                <td align="center"><?php echo self::$_var['item']['district_name']; ?></td>
                <td align="center"><?php echo self::$_var['item']['user_count']; ?></td>
                <td align="center"><?php echo self::$_var['item']['goods_amount']; ?></td>
                <td align="center"><?php echo self::$_var['item']['order_count']; ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td class="no-records" colspan="10"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
            <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
        </table>

        <table cellpadding="4" cellspacing="0">
            <tr>
                <td align="right"><?php echo self::fetch('page.htm'); ?></td>
            </tr>
        </table>

        <?php if (self::$_var['full_page']): ?>
    </div>
</form>

<script type="text/javascript" language="JavaScript">
    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
    listTable.query = "query";
    listTable.act = "user_area_stats";
    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
    

    region.isAdmin = false;

    function searchAreaStats()
    {
        listTable.filter.area_type = Utils.trim(document.forms['searchForm'].elements['area_type'].value);
        listTable.filter.province = Utils.trim(document.forms['searchForm'].elements['province'].value);
        listTable.filter.city = Utils.trim(document.forms['searchForm'].elements['city'].value);
        listTable.filter.page = 1;
        listTable.loadList();
    }
    // 选择区域
    function sel_area()
    {
        if($("#area_type").val() == 0)
        {
            $("#selProvinces").hide();
            $("#selCities").hide();
        }
        else if($("#area_type").val() == 1)
        {
            $("#selProvinces").show();
            $("#selCities").hide();
            document.getElementById('selProvinces').onchange=function(){
               
            }
        }
        else if($("#area_type").val() == 2)
        {
            $("#selProvinces").show();
            $("#selCities").show();
            document.getElementById('selProvinces').onchange=function(){
               region.changed(this, 2, 'selCities');
            }
        }
    }

    sel_area();

    function batch_export()
    {
        var area_type = Utils.trim(document.forms['searchForm'].elements['area_type'].value);
        var province = Utils.trim(document.forms['searchForm'].elements['province'].value);
        var city = Utils.trim(document.forms['searchForm'].elements['city'].value);
        return location.href='index.php?act=user_area_stats&op=export&area_type='+area_type+'&province='+province+'&city='+city;
    }

    
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>