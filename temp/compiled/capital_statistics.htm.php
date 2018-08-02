
<?php echo self::fetch('pageheader_bd.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/echarts-all.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />

<div class="main-div">
    <p style="margin: 10px">
        1、统计图展示了时间段内会员账户余额、淘玉币走势和与前时间段的对比<br />
        2、统计表展示了时间段内会员账户余额、淘玉币和与前一时间段的同比数值<br />
    </p>
</div>
<div class="form-div">
    <form action="index.php?act=capital_statistics&op=lists" method="post" id="searchForm" name="searchForm">
        <select name="stats_type" id="stats_type" onchange="week()">
            <!--
            <option value="0" <?php if (self::$_var['stats_type'] == '0'): ?>selected<?php endif; ?>>按日统计</option>
            -->
            <option value="1" <?php if (self::$_var['stats_type'] == '1'): ?>selected<?php endif; ?>>按周统计</option>
            <option value="2" <?php if (self::$_var['stats_type'] == '2'): ?>selected<?php endif; ?>>按月统计</option>
        </select>
        <input name="date" id="date" value="<?php echo self::$_var['date']; ?>" style="width:100px;height:20px;" onclick="return showCalendar(this, '%Y-%m-%d', false, false, this);" />
        <select name="year" id="year" onchange="week()"></select>
        <select name="month" id="month" onchange="week()"></select>
        <select name="dropweek" id="dropweek" <?php if (self::$_var['stats_type'] != '1'): ?>style="display: none"<?php endif; ?>></select>
        &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="<?php echo self::$_var['lang']['query']; ?>" class="button" />&nbsp;&nbsp;&nbsp;&nbsp;
        <span>当前账户余额：<?php echo self::$_var['total_money']; ?>元</span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span>当前淘玉币：<?php echo self::$_var['total_taoyumoney']; ?>元</span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span>当前资金总数：<?php echo self::$_var['total_capital']; ?>元</span><span style="color:red">（账户余额+淘玉币，1元等于10淘玉币 ）</span>
    </form>
</div>
<div class="list-div" id="orders_amount_div">
    <div class="order_count">
        <p><span class="tab_front">会员账户余额、淘玉币统计</span></p>
        <div style='height:400px;width:90%;margin-left:auto;margin-right:auto;' id='user_added_option_div'></div>
    </div>
</div>
<script type="text/javascript" language="JavaScript">
    var date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth() + 1;

    for (i = 0; i < 10; i++) {
        var oP = document.createElement("option");
        var oText = document.createTextNode(y);
        oP.appendChild(oText);
        oP.setAttribute("value", y);
        if(y == '<?php echo self::$_var['year']; ?>')
        {
            oP.setAttribute("selected", "selected")
        }
        document.getElementById('year').appendChild(oP);
        y = y - 1;
    };
    var j = 1;
    for (i = 1; i < 13; i++) {
        var month = document.createElement("option");
        var monthText = document.createTextNode(j);
        month.appendChild(monthText);
        month.setAttribute("value", j);
        if (j == '<?php echo self::$_var['month']; ?>')
        {
            month.setAttribute("selected", "selected");
        }
        document.getElementById('month').appendChild(month);
        j = j + 1;
    };

    var week_num = '<?php echo self::$_var['week_num']; ?>';
    //绑定周
    function week()
    {
        if($("#stats_type").val()==0)
        {
            $("#date").show();
            $("#year").hide();
            $("#month").hide();
            $("#dropweek").hide();
            return;
        }
        else if($("#stats_type").val()==2)
        {
            $("#date").hide();
            $("#year").show();
            $("#month").show();
            $("#dropweek").hide();
            return;
        }
        $("#date").hide();
        $("#year").show();
        $("#month").show();
        $("#dropweek").show();
        var text = $("#year").val() + '-' + $("#month").val();
        var ymd = text.substring(0, 4) + "-" + text.substring(5, 7) + "-1";
        var week = new Date(Date.parse(ymd.replace(/\-/g, "/")));
        var w = week.toString().substring(0, 3);
        var yymm = new Date(text.substring(0, 4), text.substring(5, 7), 0);
        var day = yymm.getDate();
        var dd = 1;
        switch (w) {
            case "Mon": dd = 0; break;
            case "Tue": dd = 1; break;
            case "Wed": dd = 2; break;
            case "Thu": dd = 3; break;
            case "Fri": dd = 4; break;
            case "Sat": dd = 5; break;
            case "Sun": dd = 6; break;
        }
        var aw = 5;
        if (day == 28 && dd == 0) {
            aw = 4;
        }
        var i = 1;
        $("#dropweek").empty();
        for (var i = 0; i < aw; i++) {
            var start = i * 7 + 1 - dd;
            var end = i * 7 + 7 - dd;
            if (start < 1) {
                start = 1;
            }
            if (end > day) {
                end = day;
            }
            var str = ("第" + (i + 1) + "周 <" + text.substring(5, 7) + "月" + start + "号—" + text.substring(5, 7) + "月" + end + "号>").toString();
            var val = $("#year").val() + '-' + text.substring(5, 7) + '-' + start + ' ' + $("#year").val() + '-' + text.substring(5, 7) + '-' + end + ' ' + i;
            $("#dropweek").append("<option value='" + val + "'>" + str + "</option>");
        }
        $('#dropweek')[0].selectedIndex = week_num;

        var itme = $("#dropweek").find("option:selected").text();
        $("#txtweek:text").val(itme.toString());
    }
    // 执行
    week();

</script>
<script src='js/echarts-all.js'></script>
<script>
    var option1 = {
        title : {
//            text: '未来一周气温变化',
//            subtext: '纯属虚构'
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['淘玉币','账户余额']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: false},
                dataView : {show: false, readOnly: false},
                magicType : {show: true, type: ['line']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : [<?php echo self::$_var['user_reg_time']; ?>]
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value} 元'
                }
            }
        ],
        series : [
            {
                name:'淘玉币',
                type:'line',
                data:[<?php echo self::$_var['taoyu_money_count']; ?>],
                markPoint : {
                    data : [
//                        {type : 'max', name: '最大值'},
//                        {type : 'min', name: '最小值'}
                    ]
                },
                markLine : {
                    data : [
//                        {type : 'average', name: '平均值'}
                    ]
                }
            },
            {
                name:'账户余额',
                type:'line',
                data:[<?php echo self::$_var['user_money_count']; ?>],
                markPoint : {
                    data : [
//                        {name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
                    ]
                },
                markLine : {
                    data : [
//                        {type : 'average', name : '平均值'}
                    ]
                }
            }
        ]
    };
    var order_chart1 = echarts.init(document.getElementById('user_added_option_div'));
    order_chart1.setOption(option1);
</script>
<?php echo self::fetch('pagefooter.htm'); ?>