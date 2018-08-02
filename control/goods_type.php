<?php
/**
 * 淘玉php 后台商品类型管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台商品类型管理类
 * $Id: goods_type.php  2018年6月13日10:32:42 陈洋 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class goods_typeControl extends BaseControl
{

    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('goods_type');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /*
        @return 商品类型管理
    */
    public function manage()
    {
        /* 权限判断 */
        admin_priv('goods_manage');


        $brand_list = $this->GetTypeList();

    //        $brand_list['brand']就是结果数组
        Tpl::assign('brand_list',   $brand_list['brand']);
        //filter是搜索相关的东西
        Tpl::assign('filter',       $brand_list['filter']);

        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);

        Tpl::assign('full_page',    1);


        $action_link = ['text' => L('add'), 'href' => 'index.php?act=goods_type&op=add_type'];


        Tpl::assign('ur_here', L('goods_type'));
        Tpl::assign('action_link', $action_link);

        Tpl::display('goods_type.htm');
    }

    /*
       @return 添加商品类型
    */
    public function add_type()
    {
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['goods_type']) {
            Tpl::assign('ur_here', L('add'));
            Tpl::display('goods_type_add.htm');
        }else{
            $add = [];
            $add['cat_name'] = $_POST['type_name'];
            $add['attr_group'] = $_POST['type_group'];

            if (Model('goods_type')->insert_goods_type($add)) {
                /* 记录日志 */
                admin_log($add['cat_name'], 'add', 'goods_type');

                showMessage(L('add_succ'), ['text' =>L('add_succ'), "href" => "index.php?act=goods_type&op=manage"]);
            }
        }

    }

    /*
       @return 商品类型修改
   */
    public function goods_type_edit()
    {
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['goods_type']) {
            /*查询当前要修改的类型*/
            $res = Model('goods_type')->select_goods_type_info($field = '*', "cat_id={$_GET['goods_type']}");

            Tpl::assign('ur_here', L('edit_goods_type'));
            Tpl::assign('res', $res);
            Tpl::display('goods_type_edit.htm');
        } else {
            $edit = [];
            $edit['cat_name'] = $_POST['type_name'];
            $edit['attr_group'] = $_POST['type_group'];


            if (Model('goods_type')->update_goods_type($edit, "cat_id={$_POST['goods_type']}")) {
                /* 记录日志 */
                admin_log($edit['cat_name'], 'edit', 'goods_type');
                showMessage(L('succ'), ['text' => '', "href" => "index.php?act=goods_type&op=manage"]);
            }

        }

    }

    /*
     @return 商品类型删除
 */
    public function goods_type_del()
    {
        /* 权限判断 */
        admin_priv('goods_manage');

        $info=Model("goods_type")->select_goods_type_info("*","cat_id={$_GET['goods_type']}");
        if (Model('goods_type')->delete_goods_type("cat_id={$_GET['goods_type']}")) {

            /* 记录日志 */
            admin_log($info['cat_name'], 'remove', 'goods_type');

            showMessage(L('del_succ'), ['text' => L('del_succ'), "href" => "index.php?act=goods_type&op=manage"]);
        }

    }

    /**
     * @return 获取类型列表
     * @access  public
     * @return  array
     */

    private function GetTypeList(){
        $result = get_filter();
        if ($result === false){
            /* 分页大小 */
            $filter = array();

            $goods_type = Model('goods_type');
            /*搜索相关start*/
            $filter['cat_name'] = !empty($_POST['cat_name']) ? $_POST['cat_name']:'';
            $where_brand = " 1 ";
            if($filter['cat_name']){
                $where_brand .= " AND cat_name like '%".$_POST['cat_name']."%'";
            }
            /*搜索相关end*/

            $filter['record_count'] =count($goods_type->get_goods_type_list('*',''));

            $filter = page_and_size($filter);
            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('goods_type')." WHERE ".$where_brand." ORDER BY  cat_id DESC ";
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }

        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach($res as &$val){
            $count = count(Model('goods_attr')->get_attribute_list('*',"cat_id={$val['cat_id']}"));
            $val['attr_count'] = $count;
        }

        //这一步是防止没有查出结果$res不是数组php报出waring警告
        if(!is_array($res) || empty($res)){
            $res=array();
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
        make_json_result(Tpl::fetch('goods_type.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }
}