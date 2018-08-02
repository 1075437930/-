<?php
/**
 * 淘玉php 后台商品自动上下架管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台商品自动上下架管理类
 * $Id: goods_auto.php  2018年6月13日10:32:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class goods_autoControl extends BaseControl
{

    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('goods,calendar');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }


    /**
     * @return 商品自动上下架管理 Description
     */
    public function lists()
    {

        /* 权限判断 */
        admin_priv('goods_manage');


        $brand_list = $this->GetTypeList();

        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);

        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);

        Tpl::assign('full_page',    1);


        Tpl::assign('ur_here', L('auto_manage'));


        Tpl::display('auto_manage.htm');
    }




    /**
     * @return 商品自动上下架撤销 Description
     */
    public function auto_del()
    {
        /* 权限判断 */
        admin_priv('goods_manage');

        $info=Model('automanage')->select_auto_manage_info('*',"item_id={$_GET['item_id']}");
        if(Model('automanage')->delete_auto_manage("item_id={$_GET['item_id']}")){
            /* 记录日志 */
            admin_log($info['goods_name'], 'chex', 'auto_goods');

            showMessage(L('che_succ'), ['text' => '', "href" => "index.php?act=goods_auto&op=lists"]);
        }
    }

    /**
     * @return 批量上架操作 Description
     */
    public function pls(){
        $edit=[];
        $edit['starttime']=strtotime($_GET['date']);

        if(Model('automanage')->update_auto_manage($edit,"item_id in ({$_GET['item_id']})")){
            $goods_ed=[];
            $goods_ed['is_on_sale']=1;
            $goods_ed['add_time']=strtotime($_GET['date']);
            if(Model('goods')->update_goods($goods_ed,"goods_id in ({$_GET['item_id']})")){
                /* 记录日志 */
                admin_log('', 'pl', 'shang');

                showMessage(L('pls_succ'), ['text' => '', "href" => "index.php?act=goods_auto&op=lists"]);
            }
        }
    }

    /**
     * @return 批量下架操作 Description
     */
    public function plx(){
        $edit=[];
        $edit['endtime']=strtotime($_GET['date']);

        if(Model('automanage')->update_auto_manage($edit,"item_id in ({$_GET['item_id']})")){
            $goods_ed=[];
            $goods_ed['is_on_sale']=0;

            if(Model('goods')->update_goods($goods_ed,"goods_id in ({$_GET['item_id']})")){
                /* 记录日志 */
                admin_log('', 'batch_xia', '');
                showMessage(L('plx_succ'), ['text' => '', "href" => "index.php?act=goods_auto&op=lists"]);
            }
        }
    }

    /**
     * @return 获取自动列表
     * @access  public
     * @return  array
     */

    private function GetTypeList(){
        $result = get_filter();
        if ($result === false){
            /* 分页大小 */
            $filter = array();

            $goods_type = Model('automanage');
            /*搜索相关start*/
            $filter['cat_name'] = !empty($_POST['cat_name']) ? $_POST['cat_name']:'';
            $where_brand = " 1 ";
            if($filter['cat_name']){
                $where_brand .= " AND goods_name like '%".$_POST['cat_name']."%'";
            }
            /*搜索相关end*/

            $filter['record_count'] =count($goods_type->get_auto_manage_list('*',''));
            if($filter['cat_name']){
                $filter['record_count']=count($goods_type->get_auto_manage_list('*',"goods_name like '%".$filter['cat_name']."%'"));
            }

            $filter = page_and_size($filter);

            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('auto_manage')." WHERE ".$where_brand." ORDER BY  item_id DESC ";
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

            $val['starttime']= local_date('Y-m-d',$val['starttime']);
            $val['endtime']= local_date('Y-m-d',$val['endtime']);
            if($val['starttime']==0){
                $val['starttime']='000-00-00';

            }
            if($val['endtime']==0){
                $val['endtime']='000-00-00';
            }
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
        make_json_result(Tpl::fetch('auto_manage.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }
}