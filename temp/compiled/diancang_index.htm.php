

<?php echo self::fetch('pageheader.htm'); ?>



<div class="list-div">
	<div class="important">
    	<ul class="import">
        	<li class="import_1">
            	<div class="module">
            		<i></i>
                	<div class="detail">
						<strong><a href="diancang_order.php?act=dcorder_list&today_tou=1" title="今日投资" style="color:#FA841E; text-decoration:none"><?php echo self::$_var['tongji']['jin']['good_pics']; ?>元</a>&nbsp;/&nbsp;<?php echo self::$_var['tongji']['jin']['nums']; ?>个</strong>
                		<span>今日投资总额/个数</span>
                	</div>
                </div>
            </li>
            <li class="import_2">
            	<div class="module">
            		<i></i>
                	<div class="detail">
                		<strong><a href="diancang_order.php?act=dcorder_list&benzhou_tou=1" title="本周投资" style="color:#FA841E; text-decoration:none"><?php echo self::$_var['tongji']['zou']['good_pics']; ?>元</a>&nbsp;/&nbsp;<?php echo self::$_var['tongji']['zou']['nums']; ?>个</strong>
                		<span>7天内投资总额/个数</span>
                	</div>
                </div>
            </li>
            <li class="import_3">
            	<div class="module">
            		<i></i>
                	<div class="detail">
                		<strong><a href="diancang_order.php?act=dcorder_list&benyue_tou=1" title="本月投资" style="color:#FA841E; text-decoration:none"><?php echo self::$_var['tongji']['yue']['good_pics']; ?>元</a>&nbsp;/&nbsp;<?php echo self::$_var['tongji']['yue']['nums']; ?>个</strong>
                		<span>本月投资总额/个数</span>
                	</div>
                </div>
            </li>
            <li class="import_4">
            	<div class="module">
            		<i></i>
                	<div class="detail">
                		<strong><a href="diancang_order.php?act=dcorder_list&total_sell=1" title="总投资" style="color:#FA841E; text-decoration:none"><?php echo self::$_var['tongji']['zong']['good_pics']; ?>-<?php echo self::$_var['tongji']['zong']['youpic']; ?>元</a>&nbsp;/&nbsp;<?php echo self::$_var['tongji']['zong']['nums']; ?>个</strong>
                		<span>总投资-代金卷/个数</span>
                	</div>
                </div>
            </li>
            <li class="import_5">
            	<div class="module">
            		<i></i>
                	<div class="detail">
                		<strong><a href="diancang_order.php?act=dcorder_back" title="总结算钱数" style="color:#FA841E; text-decoration:none"><?php echo self::$_var['tongji']['jiesuan']['good_pics']; ?>元</a>&nbsp;/&nbsp;<?php echo self::$_var['tongji']['jiesuan']['nums']; ?>个</strong>
                		<span>已结算额/个数</span>
                	</div>
                </div>
            </li>
        </ul>
    </div>
</div>
<br />
<div class="list-div">
	<div class="console-detaile">
        <div class="item shop-item">
            <div class="item-hd"><span class="bg1">待处理<em><?php echo self::$_var['pending']['total']; ?> 个</em></span></div>
            <div class="item-bd item-bd1">
                <ul class="clearfix">
                    <li>
                        <strong><a href="diancang_order.php?act=dcorder_list&order_status=0">订单确认</a></strong>
                        <span><?php echo self::$_var['pending']['queren']; ?> 个</span>
                    </li>
                    <li class="li_even">
                        <strong><a href="diancang_order.php?act=dcorder_list&order_status=1&shipping_status=0&shipping_status=0&pay_status=1">待处理发货</a></strong>
                        <span><?php echo self::$_var['pending']['fahuo']; ?> 个</span>
                    </li>
                    <li>
                        <strong><a href="diancang_order.php?act=dcorder_back&status_back=5">退货确认</a></strong>
                        <span><?php echo self::$_var['pending']['tuihuo']; ?> 个</span>
                    </li>
                    <li class="li_even">
                        <strong><a href="diancang_order.php?act=dcorder_back&sort_index=10">待处理收货</a></strong>
                        <span><?php echo self::$_var['pending']['shouhuo']; ?> 个</span>
                    </li>
					<li>
                        <strong><a href="diancang_order.php?act=dcorder_list&tian7=1">7天内到期</a></strong>
                        <span><?php echo self::$_var['pending']['seven_days']; ?> 个</span>
                    </li>
                    <li class="li_even">
                        <strong><a href="diancang_order.php?act=dcorder_list&benyue_daoqi=1">本月到期</a></strong>
                        <span><?php echo self::$_var['pending']['one_moon']; ?> 个</span>
                    </li>
					<li>
                        <strong><a href="diancang_order.php?act=dcorder_list&daoqi=1">到期未退</a></strong>
                        <span><?php echo self::$_var['pending']['deposit']; ?> 个</span>
                    </li>
                    <li class="li_even">
                        <strong><a href="diancang_order.php?act=dcorder_list&nots_tui=1">到期不退</a></strong>
                        <span><?php echo self::$_var['pending']['withdraw']; ?> 个</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="item goods-item">
            <div class="item-hd"><span class="bg2">总商品<em><?php echo self::$_var['goods_nums']['total']; ?> 件</em></span></div>
            <div class="item-bd item-bd2">
                <ul class="clearfix">
                    <li>
                        <strong><a href="diancang.php?act=dclist&benyue_add=1">本月新加</a></strong>
                        <span><?php echo self::$_var['goods_nums']['benyue_num']; ?> 件</span>
                    </li>                   
                    <li class="li_even">
                        <strong><a href="diancang_order.php?act=dcorder_back&benyue_back=1">本月退回</a></strong>
                        <span><?php echo self::$_var['goods_nums']['benyue_refund_num']; ?> 件</span>
                    </li>
                    <li>
                        <strong><a href="diancang.php?act=dclist&shangyue_add=1">上月新加</a>
                        </strong><span><?php echo self::$_var['goods_nums']['shangyue_num']; ?> 件</span>
                    </li>
                    <li class="li_even">
                        <strong><a href="diancang_order.php?act=dcorder_back&shangyue_back=1">上月退回</a></strong>
                        <span><?php echo self::$_var['goods_nums']['shangyue_refund_num']; ?> 件</span>
                    </li>
                    <li>
                        <strong><a href="diancang_order.php?act=dcorder_back">总退回</a></strong>
                        <span><?php echo self::$_var['goods_nums']['total_refund_num']; ?> 件</span>
                    </li>
                    <li class="li_even">
                        <strong><a href="diancang_order.php?act=dcorder_list&total_sell=1">总卖出</a></strong>
                        <span><?php echo self::$_var['goods_nums']['total_sole_num']; ?> 件</span>
                    </li>
                    <li>
                        <strong><a href="diancang_order.php?act=dcorder_list&out_sell=1">总投资(在外)</a></strong>
                        <span><?php echo self::$_var['goods_nums']['total_out_num']; ?> 件</span>
                    </li>
                     <li class="li_even">
                        <strong><a href="diancang.php?act=dclist">现有库存</a></strong>
                         <span><?php echo self::$_var['goods_nums']['total_kucun_num']; ?> 件</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="item order-item">
            <div class="item-hd"><span class="bg3">比率数据<em>平均值</em></span></div>
            <div class="item-bd item-bd3">
                <ul class="clearfix">
                	<li>
                        <strong><a>本月产品价格</a></strong>
                        <span><?php echo self::$_var['goods_price']['benyue_goods_price_avg']; ?>元</span>
                    </li>
                	<li class="li_even">
                        <strong><a>本月购买价格</a></strong>
                        <span><?php echo self::$_var['goods_price']['benyue_buy_price_avg']; ?>元</span>
                    </li>
                    <li>
                        <strong><a>上月产品价格</a></strong>
                        <span><?php echo self::$_var['goods_price']['shangyue_goods_price_avg']; ?>元</span>
                    </li>
                    <li class="li_even">
                        <strong><a>上月购买价格</a></strong>
                        <span><?php echo self::$_var['goods_price']['shangyue_buy_price_avg']; ?>元</span>
                    </li>
                    <li>
                        <strong><a>总退款价格(未退款)</a></strong>
                        <span><?php echo self::$_var['goods_price']['total_refund_money']; ?>元</span>
                    </li>
                    <li class="li_even">
                        <strong><a>总购买价格</a></strong>
                        <span><?php echo self::$_var['goods_price']['total_buy_num']; ?>元</span>
                    </li>
                    <li>
                        <strong><a>本月优惠劵</a></strong>
                        <span><?php echo self::$_var['goods_price']['benyue_youhui_num']; ?> 笔</span>
                    </li>
                    <li class="li_even">
                        <strong><a>总优惠劵</a></strong>
                        <span><?php echo self::$_var['goods_price']['total_youhui_num']; ?> 笔</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div style="height:0px;line-height:0px;clear:both"></div>

<script src='js/echarts-all.js'></script>
<script>
    var froms_chart = echarts.init(document.getElementById('froms_chart_div'));
    froms_chart.setOption(<?php echo self::$_var['froms_option']; ?>);
    var order_chart = echarts.init(document.getElementById('order_chart_div'));
    order_chart.setOption(<?php echo self::$_var['orders_option']; ?>);
    var order_chart1 = echarts.init(document.getElementById('order_chart_div1'));
    order_chart1.setOption(<?php echo self::$_var['orders_option1']; ?>);
    var sales_chart = echarts.init(document.getElementById('sales_chart_div'));
    sales_chart.setOption(<?php echo self::$_var['sales_option']; ?>);
    var sales_chart1 = echarts.init(document.getElementById('sales_chart_div1'));
    sales_chart1.setOption(<?php echo self::$_var['sales_option1']; ?>);
</script>


<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js')); ?>
<script type="Text/Javascript" language="JavaScript">
<!--
onload = function()
{
  /* 检查订单 */
  startCheckOrder();
}
  Ajax.call('index.php?is_ajax=1&act=main_api','', start_api, 'GET', 'JSON');

  //Ajax.call('cloud.php?is_ajax=1&act=cloud_remind','', cloud_api, 'GET', 'JSON');

   function start_api(result)

    {

		document.getElementById("php_ver").innerHTML = result.php_ver;

		document.getElementById("mysql_ver").innerHTML = result.mysql_ver;

		document.getElementById("ver").innerHTML = result.ver;

		document.getElementById("pro_ver").innerHTML = result['version']['ver'];

		document.getElementById("charset").innerHTML = result.charset;

      apilist = document.getElementById("lilist").innerHTML;

      document.getElementById("lilist").innerHTML =apilist;

      if(document.getElementById("Marquee") != null)

      {

        var Mar = document.getElementById("Marquee");

        lis = Mar.getElementsByTagName('div');

        //alert(lis.length); //显示li元素的个数

        if(lis.length>1)

        {

          api_styel();

        }      

      }

    }

 

      function api_styel()

      {

        if(document.getElementById("Marquee") != null)

        {

            var Mar = document.getElementById("Marquee");

            if (Browser.isIE)

            {

              Mar.style.height = "52px";

            }

            else

            {

              Mar.style.height = "36px";

            }

            

            var child_div=Mar.getElementsByTagName("div");



        var picH = 16;//移动高度

        var scrollstep=2;//移动步幅,越大越快

        var scrolltime=30;//移动频度(毫秒)越大越慢

        var stoptime=4000;//间断时间(毫秒)

        var tmpH = 0;

        

        function start()

        {

          if(tmpH < picH)

          {

            tmpH += scrollstep;

            if(tmpH > picH )tmpH = picH ;

            Mar.scrollTop = tmpH;

            setTimeout(start,scrolltime);

          }

          else

          {

            tmpH = 0;

            Mar.appendChild(child_div[0]);

            Mar.scrollTop = 0;

            setTimeout(start,stoptime);

          }

        }

        setTimeout(start,stoptime);

        }

      }

//-->

</script>



<?php echo self::fetch('pagefooter.htm'); ?>

