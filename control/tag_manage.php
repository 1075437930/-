<?php
/**
 * 淘玉php 后台标签管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台标签管理类
 * $Id: tag_manage.php  2018年6月13日10:32:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class tag_manageControl extends BaseControl
{
    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('tag_manage');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 标签管理列表
     */
    public function class_list(){

        /* 权限判断 */
        admin_priv('goods_manage');


        $brand_list = $this->GetTypeList();

        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);

        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);

        Tpl::assign('full_page',1);

        $action_link = ['text' => L('class_add'), 'href' => 'index.php?act=tag_manage&op=class_add'];

        Tpl::assign('ur_here', L('tag'));

        Tpl::assign('action_link', $action_link);

        $action_link2 = ['text' => L('tag_add'), 'href' => 'index.php?act=tag_manage&op=tag_add'];

        Tpl::assign('action_link2', $action_link2);

        Tpl::display('class_list.htm');

    }

    /*
      @return 分类修改
  */
    public function tag_class_edit()
    {
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['tag_class_id']) {
            /*查询当前要修改的分类*/
            $res = Model('admintag')->select_admintag_class_info('*', "tag_class_id={$_GET['tag_class_id']}");

            $action_link = ['text' => L('class_add'), 'href' => 'index.php?act=tag_manage&op=class_add'];
            Tpl::assign('action_link', $action_link);

            Tpl::assign('ur_here', L('edit_tag_class'));
            Tpl::assign('res', $res);
            Tpl::display('tag_class_edit.htm');
        } else {
            $edit = [];

            $edit['class_name'] = $_POST['class_name'];
            $edit['class_cent'] = $_POST['class_cent'];


            if (Model('admintag')->update_admintag_class($edit, "tag_class_id={$_POST['tag_class_id']}")) {

                showMessage(L('edit_succ'), ['text' => '', "href" => "index.php?act=tag_manage&op=class_list"]);
            }

        }

    }

    /*
     * @return 删除标签分类
     * */
    public function trash_del(){
        if(Model('admintag')->delete_admintag_class("trash_id={$_GET['id']}")){
            showMessage(L('del_succ'), ['text' =>L('del_succ'), "href" => "index.php?act=goods&op=trash"]);
        }
    }

    /**
     * @return 添加标签分类
     */
    public function class_add(){
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['goods_type']) {
            Tpl::assign('ur_here', L('class_add'));
            Tpl::display('tag_class_add.htm');
        }else{
            $add = [];
            $add['class_name'] = $_POST['type_name'];
            $add['class_cent'] = $_POST['type_group'];

            if (Model('admintag')->insert_admintag_class($add)) {
                /* 记录日志 */
                admin_log($add['class_name'], 'add', 'tag_class');

                showMessage(L('add_succ'), ['text' =>L('add_succ'), "href" => "index.php?act=tag_manage&op=class_list"]);
            }
        }
    }

    /**

     * @return 添加标签：内容中不能出现空格 主要内容+返回值属性

     * @param
     */

    public function tag_add(){
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['goods_type']) {
            /*查询分类列表*/
            $class_list=Model('admintag')->get_admintag_class_list('*','');


            Tpl::assign('class_list', $class_list);

            Tpl::assign('ur_here', L('tag_add'));
            Tpl::display('tag_add.htm');
        }else{
            $add = [];
            $add['tag_words'] = $_POST['tag_name'];
            $add['tag_cent'] = $_POST['tag_zs'];

            $add['tag_key'] = $_POST['tag_keyword'];

            $add['tag_class_id'] = $_POST['tag_class'];

            if (Model('admintag')->insert_admintag($add)) {
                /* 记录日志 */
                admin_log($add['tag_words'], 'add', 'tag');
                showMessage(L('add_tag_succ'), ['text' =>L('add_tag_succ'), "href" => "index.php?act=tag_manage&op=class_list"]);

            }
        }

    }

    /**
     * @return 编辑分类
     */
    public function class_edit(){
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['goods_type']) {
            /*查询当前要修改的分类*/
            $info=Model('admintag')->select_admintag_class_info('*',"tag_class_id={$_GET['tag_class_id']}");

            Tpl::assign('info', $info);
            Tpl::assign('ur_here', L('class_edit'));
            Tpl::display('tag_class_edit.htm');

        }else{
            $edit = [];
            $edit['class_name'] = $_POST['class_name'];
            $edit['class_cent'] = $_POST['class_cent'];

            if (Model('admintag')->update_admintag_class($edit,"tag_class_id={$_POST['tag_class_id']}")) {
                /* 记录日志 */
                admin_log($edit['class_name'], 'edit', 'tag_class');
                showMessage(L('edit_succ'), ['text' =>'', "href" => "index.php?act=tag_manage&op=class_list"]);

            }
        }
    }


    /**
     * @return 删除分类
     */
    public function class_del()
    {
        /* 权限判断 */
        admin_priv('goods_manage');
        $info=Model('admintag')->select_admintag_class("tag_class_id={$_GET['tag_class_id']}");
        if (Model('admintag')->delete_admintag_class("tag_class_id={$_GET['tag_class_id']}")) {
            /* 记录日志 */
            admin_log($info['class_name'], 'remove', 'goods_type');

            showMessage(L('del_succ'), ['text' => L('del_succ'), "href" => "index.php?act=tag_manage&op=class_list"]);
        }

    }


    /**
     * @return 分类下标签列表
     */
    public function tag_lists(){
        /* 权限判断 */
        admin_priv('goods_manage');

        /*查询所有分类*/
        $class_all=Model('admintag')->get_admintag_class_list('*','');

        Tpl::assign('class_all',$class_all);

        Tpl::assign('tag_class_id',$_GET['tag_class_id']);


        $brand_list = $this->GetTagList();

        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);

        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);


        Tpl::assign('full_page', 1);


        $action_link = ['text' => L('class_add'), 'href' => 'index.php?act=tag_manage&op=class_add'];


        Tpl::assign('action_link', $action_link);

        $action_link2 = ['text' => L('tag_add'), 'href' => 'index.php?act=tag_manage&op=tag_add'];

        Tpl::assign('action_link2', $action_link2);


        Tpl::assign('ur_here', L('tag_list'));


        Tpl::display('tag_lists.htm');

    }

    /**
     * @return 编辑标签
     */
    public function tag_edit(){
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['tag_class_id']) {
            /*查询当前要修改的标签*/
            $info=Model('admintag')->select_admintag_info('*',"tag_id={$_GET['tag_id']}");

            /*查询分类列表*/
            $class_list=Model('admintag')->get_admintag_class_list('*','');


            Tpl::assign('class_list', $class_list);
            Tpl::assign('tag_class_id', $_GET['tag_class_id']);

            Tpl::assign('info', $info);
            Tpl::assign('ur_here', L('tag_edit'));

            Tpl::display('tag_edit.htm');

        }else{
            $edit = [];
            $edit['tag_words'] = $_POST['tag_name'];
            $edit['tag_cent'] = $_POST['tag_zs'];

            $edit['tag_key'] = $_POST['tag_keyword'];

            $edit['tag_class_id'] = $_POST['tag_class'];

            if (Model('admintag')->update_admintag($edit,"tag_id={$_POST['tag_id']}")) {
                /* 记录日志 */
                admin_log($edit['tag_words'], 'edit', 'tag');
                showMessage(L('tag_edit_succ'), ['text' =>'', "href" => "index.php?act=tag_manage&op=tag_lists&tag_class_id={$_POST['tag_class_id']}"]);

            }
        }
    }


    /**
     * @return 删除标签
     */
    public function tag_del(){
        $info=Model('admintag')->select_admintag_info("*","tag_id={$_GET['tag_id']}");
        if(Model('admintag')->delete_admintag("tag_id={$_GET['tag_id']}")){
            /* 记录日志 */
            admin_log($info['tag_words'], 'remove', 'tag');
            showMessage(L('tag_del_succ'), ['text' =>'', "href" => "index.php?act=tag_manage&op=tag_lists&tag_class_id={$_GET['tag_class_id']}"]);
        }
    }

    /**
     * @return 获取分类列表
     * @access  public
     * @return  array
     */

    private function GetTypeList(){
        $result = get_filter();
        if ($result === false){
            /* 分页大小 */
            $filter = array();

            $goods_type = Model('admintag');
            /*搜索相关start*/
            $filter['cat_name'] = !empty($_POST['cat_name']) ? $_POST['cat_name']:'';
            $where_brand = " 1 ";
            if($filter['cat_name']){
                $where_brand .= " AND class_name like '%".$_POST['cat_name']."%'";
            }

            /*搜索相关end*/
            $filter['record_count']=count(Model('admintag')->get_admintag_class_list('*',''));

            $filter = page_and_size($filter);
            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('admintag_class')." WHERE ".$where_brand." ORDER BY  tag_class_id DESC ";
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }

        $res = get_all_page($sql, $filter['page_size'], $filter['start']);

        foreach ($res as $key => &$val) {
            /*查询分类下的标签*/
            $arrs=Model('admintag')->get_admintag_list('*',"tag_class_id={$val['tag_class_id']}");
            $str='';
            foreach($arrs as $val2){
                $str.=$val2['tag_words'].',';
            }
            $val['baotags']=$str;
        }

        /*这一步是防止没有查出结果$res不是数组php报出waring警告*/
        if(!is_array($res) || empty($res)){
            $res=array();
        }

        return array('brand' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    }

    /**
     * @return 获取分类下标签列表
     * @access  public
     * @return  array
     */

    private function GetTagList(){
        $result = get_filter();
        if ($result === false){
            /* 分页大小 */
            $filter = array();

            $goods_type = Model('admintag');
            /*搜索相关start*/

            $where_brand = " 1 ";

            /*获取参数一定要放在$filter数组里，否则会接受不到*/
            $filter['tag_class_id']=!empty($_REQUEST['tag_class_id']) ? trim($_REQUEST['tag_class_id']):'';

            if(intval($filter['tag_class_id'])==-10){
                $where_brand .='';
            }else{
                $where_brand .="AND tag_class_id={$filter['tag_class_id']}";
            }



            /*搜索相关end*/
            $filter['record_count']=count(Model('admintag')->get_admintag_list('*',"tag_class_id={$filter['tag_class_id']}"));

            if(intval($filter['tag_class_id'])==-10){
                $filter['record_count'] =count(Model('admintag')->get_admintag_list('*',""));
            }

            $filter = page_and_size($filter);

           /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('admintag')." WHERE ".$where_brand." ORDER BY  tag_id DESC ";
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }

        $res = get_all_page($sql, $filter['page_size'], $filter['start']);

        /*这一步是防止没有查出结果$res不是数组php报出waring警告*/
        if(!is_array($res) || empty($res)){
            $res=array();
        }

        foreach ($res as $key => &$val) {
            /*查询标签的分类*/
            $info=Model('admintag')->select_admintag_class_info('*',"tag_class_id={$filter['tag_class_id']}");
            $val['class_name']=$info['class_name'];
        }


        return array('brand' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    }

    /**
     * @return 分页、搜索
     */
    public function tag_query() {
        $brand_list = $this->GetTagList();
        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);
        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);
        $sort_flag  = sort_flag($brand_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('tag_lists.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
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
        make_json_result(Tpl::fetch('class_list.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }
}