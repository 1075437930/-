<?php
/**
 * 淘玉php 降价通知管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 降价通知管理类
 * $Id: pricecut.php  2018年6月13日10:32:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class pricecutControl extends BaseControl
{
    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('pricecut');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 降价通知列表
     */
    public function lists(){

        /* 权限判断 */
        admin_priv('goods_manage');

        $brand_list = $this->GetTypeList();


        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);

        Tpl::assign('record_count', $brand_list['record_count']);

        Tpl::assign('page_count',   $brand_list['page_count']);

        Tpl::assign('full_page',1);


        Tpl::assign('ur_here', L('tongzhi'));

        Tpl::display('pricecut.htm');

    }


    /**
     * @return 获取降价通知列表
     * @access  public
     * @return  array
     */

    private function GetTypeList(){
        $result = get_filter();
        if ($result === false){
            /* 分页大小 */
            $filter = array();

            /*搜索相关start*/
            $filter['cat_name'] = !empty($_POST['cat_name']) ? $_POST['cat_name']:'';
            $filter['status'] = !empty($_POST['status']) ? $_POST['status']:'';
            $where_brand = " 1 ";
            if($filter['cat_name'] && isset($filter['status'])){
                if($filter['status']==-1){
                    $where_brand .= " AND (mobile='{$filter['cat_name']}' or status=0)";
                }elseif($filter['status']==-10){
                    $where_brand .= " AND mobile='{$filter['cat_name']}'";
                }else{
                    $where_brand .= " AND (mobile='{$filter['cat_name']}' or status={$filter['status']})";
                }

            }


            /*搜索相关end*/
            $filter['record_count']=Model('pricecut')->get_pricecut_count($where_brand);

            $filter = page_and_size($filter);
            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('pricecut')." WHERE ".$where_brand." ORDER BY  pricecut_id DESC ";
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }

        $res = get_all_page($sql, $filter['page_size'], $filter['start']);


        //这一步是防止没有查出结果$res不是数组php报出waring警告
        if(!is_array($res) || empty($res)){
            $res=array();
        }

        foreach($res as &$val){
            $val['goods_name']=Model('goods')->select_goods_info('*',"goods_id={$val['goods_id']}")['goods_name'];

            if(Model('goods')->select_goods_info('*',"goods_id={$val['goods_id']}")['shop_price']){
                $val['shop_price']=Model('goods')->select_goods_info('*',"goods_id={$val['goods_id']}")['shop_price'];
            }else{
                $val['shop_price']=0.00;
            }

            if($val['status']==0){
                $val['status']='未通知';
            }elseif($val['status']==1){
                $val['status']='系统通知（失败）';
            }elseif($val['status']==2){
                $val['status']='系统通知（成功）';
            }elseif($val['status']==3){
                $val['status']='人工通知';
            }

            $val['add_time']=local_date('Y-m-d H:i:s', $val['add_time']);
        }


        return array('brand' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    }

    /**
     * @return 分页、搜索
     */
    public function type_query() {
        $brand_list = $this->GetTypeList();
        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);
        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);
        $sort_flag  = sort_flag($brand_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('pricecut.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }

    /*
      @return 降价通知修改
  */
    public function pricecut_edit()
    {
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['pricecut_id']) {
            /*查询当前要修改的通知*/
            $res = Model('pricecut')->select_pricecut_info('*', "pricecut_id={$_GET['pricecut_id']}");

            $goods_name=Model('goods')->select_goods_info('*',"goods_id={$res['goods_id']}")['goods_name'];

            $goods_price=Model('goods')->select_goods_info('*',"goods_id={$res['goods_id']}")['shop_price'];

            $action_link = ['text' => L('tongzhi'), 'href' => 'index.php?act=pricecut&op=lists'];
            $res['add_time']=local_date('Y-m-d h:i:s', $res['add_time']);
            Tpl::assign('action_link', $action_link);
            Tpl::assign('goods_name', $goods_name);
            Tpl::assign('goods_price', $goods_price);
            Tpl::assign('ur_here', L('tongzhi_edit'));

            Tpl::assign('res', $res);
            Tpl::display('pricecut_edit.htm');
        } else {
            $edit = [];

            $edit['remark'] = $_POST['remark'];
            $edit['status'] = $_POST['status'];

            $info=Model('pricecut')->select_pricecut_info('*',"pricecut_id={$_POST['pricecut_id']}");
            $goods_name=Model('goods')->select_goods_info('*',"goods_id={$info['goods_id']}")['goods_name'];
            if (Model('pricecut')->update_pricecut($edit, "pricecut_id={$_POST['pricecut_id']}")) {
                /* 记录日志 */
                admin_log($goods_name, 'edit', 'pricecut');

                showMessage(L('edit_succ'), ['text' => '', "href" => "index.php?act=pricecut&op=lists"]);
            }

        }

    }


    /*
     @return 降价通知删除
    */
    public function pricecut_del(){
        $info=Model('pricecut')->select_pricecut_info('*',"pricecut_id={$_GET['pricecut_id']}");
        $goods_name=Model('goods')->select_goods_info('*',"goods_id={$info['goods_id']}")['goods_name'];
        if(Model('pricecut')->delete_pricecut("pricecut_id={$_GET['pricecut_id']}")){
            /* 记录日志 */
            admin_log($goods_name, 'remove', 'pricecut');
            showMessage(L('del_succ'), ['text' => '', "href" => "index.php?act=pricecut&op=lists"]);
        }
    }


}