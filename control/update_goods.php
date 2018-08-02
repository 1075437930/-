<?php
/**
 * 淘玉php 后台批量下架/库存管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台批量下架/库存管理类
 * $Id: update_goods.php  2018年6月13日10:32:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class update_goodsControl extends BaseControl{
    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('update_goods');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /*
     @return 批量下架/库存
    */
    public function indexset(){
        $years=['2015','2016','2017','2018'];
        $mons=[];
        for($i=1;$i<=12;$i++){
            $mons[]=$i;
        }

        Tpl::assign('years',$years);
        Tpl::assign('mons',$mons);

        Tpl::display('indexset.htm');
    }

    /*
   @return 更新商品数据
  */
    public function update_goods(){
        $years=$_POST['years'];
        $mons=$_POST['mons'];

        $month = $years."-".$mons;//当前年月
        $month_start = strtotime($month);//指定月份月初时间戳
        $month_end = mktime(23, 59, 59, date('m', strtotime($month))+1, 00);//指定月份月末时间戳



        if(time()<$month_start || time()>$month_end){
            /*查找上架时间addtime在选择的月的范围内的商品*/
            $goods=Model('goods')->get_goods_list('*',"add_time between {$month_start} and {$month_end}");


            if($_POST['leixing']=='xia'){
                foreach($goods as $val){
                    $edit=[];
                    $edit['is_on_sale']=0;

                    $frist = substr($_POST['zm'], 0, 1 );
                    if($frist!='F' && $frist!='X' && $frist!='Z' && $frist!='L'  ){
                        /* 记录日志 */
                        admin_log('succ', 'batch_xia', 'xia');

                        Model('goods')->update_goods($edit,"goods_id={$val['goods_id']} AND goods_name like '%{$_POST['zm']}%' ");
                    }else{
                        showMessage(L('ZMJZ'), ['text' => '', "href" => "index.php?act=update_goods&op=indexset"]);
                    }
                }
            }else{
                foreach($goods as $val){
                    $edit=[];
                    $edit['goods_number']=0;
                    $frist = substr($_POST['zm'], 0, 1 );
                    if($frist!='F' && $frist!='X' && $frist!='Z' && $frist!='L'  ){
                        /* 记录日志 */
                        admin_log('succ', 'batch_ku0', 'xia');
                        Model('goods')->update_goods($edit,"goods_id={$val['goods_id']} AND goods_name like '%{$_POST['zm']}%' ");
                    }else{
                        showMessage(L('ZMJZ'), ['text' => '', "href" => "index.php?act=update_goods&op=indexset"]);
                    }
                }
            }
            showMessage(L('succ'), ['text' => '', "href" => "index.php?act=update_goods&op=indexset"]);

        }else{
            showMessage(L('DYJZ'), ['text' => '', "href" => "index.php?act=update_goods&op=indexset"]);
        }

    }
}