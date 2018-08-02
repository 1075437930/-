<?php
/**
 * 淘玉php 后台商品属性管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台品牌管理类
 * $Id: attribute.php  2018年6月13日14:46:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');
class attributeControl extends BaseControl {
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('attribute');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /*
       @return 商品属性列表
   */
    public function attr_list(){

        /* 权限判断 */
        admin_priv('goods_manage');

        $brand_list = $this->GetTypeList();

        Tpl::assign('brand_list',   $brand_list['brand']);
        //$brand_list['filter']是搜索相关的东西
        Tpl::assign('filter',       $brand_list['filter']);

        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);

        /*查询商品类型*/
        $types=Model('goods_type')->get_goods_type_list('*','');

        Tpl::assign('types',$types);

        Tpl::assign('full_page', 1);

//        Tpl::assign('goods_type', $_REQUEST['goods_type']);

        $action_link = ['text' => L('attr_add'), 'href' => "index.php?act=attribute&op=attr_add&cat_id={$_GET['goods_type']}"];


        Tpl::assign('ur_here', L('goods_attr'));
        Tpl::assign('action_link', $action_link);

        Tpl::display('attribute.htm');



    }

    /*
     @return 添加商品属性
    */
    public function attr_add(){
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!isset($_POST['attr_id_update'])) {

            /*类型列表*/
            $type_list=Model('goods_type')->get_goods_type_list('*','');
            Tpl::assign('type_list',$type_list);

            Tpl::assign('ur_here', L('attr_add'));
            Tpl::assign('cat_id',$_GET['cat_id']);
            Tpl::display('attribute_add.htm');
        }else{

            $add=[];
            $add['attr_name']=$_POST['attr_name'];
            $add['cat_id']=$_POST['attr_type'];
            $add['attr_index']=$_POST['js'];
            $add['is_linked']=$_POST['gl'];
            $add['attr_input_type']=$_POST['fs'];
            $add['attr_values']=$_POST['list'];
            if($_POST['fs']==1){
                $add['attr_values']=str_replace(array("\r\n", "\r", "\n"), ",",$_POST['list']);
            }


            if (Model('attr')->insert_attr($add)) {
                /* 记录日志 */
                admin_log($add['attr_name'], 'add', 'attribute');

                showMessage(L('add_succ'), ['text' =>L('add_succ'), "href" => "index.php?act=attribute&op=attr_list&goods_type={$_POST['attr_id_update']}"]);
            }
        }

    }


    /*
      @return 修改商品属性
    */
    public function attr_edit(){

        /* 权限判断 */
        admin_priv('goods_manage');

        if(!isset($_POST['attr_id_update'])){

            /*当前要修改的属性*/
            $res=Model('goods_attr')->select_attribute_info('*',"attr_id={$_GET['attr_id']}");

            /*类型列表*/
            $type_list=Model('goods_type')->get_goods_type_list('*','');

            Tpl::assign('ur_here', L('edit_attr'));
            Tpl::assign('type_list',$type_list);

            Tpl::assign('res',$res);

            Tpl::display('attribute_edit.htm');

        }else{
            $edit=[];
            $edit['attr_name']=$_POST['attr_name'];
            $edit['cat_id']=$_POST['attr_type'];
            $edit['attr_index']=$_POST['js'];

            $edit['is_linked']=$_POST['gl'];
            $edit['attr_input_type']=$_POST['fs'];
            $add['attr_values']=$_POST['list'];
            if($_POST['fs']==1){
                $add['attr_values']=str_replace(array("\r\n", "\r", "\n"), ",",$_POST['list']);
            }

            $edit_id=$_POST['attr_id_update'];

            if(Model('attr')->update_attr($edit,"attr_id={$edit_id}")){
                /* 记录日志 */
                admin_log($edit['attr_name'], 'edit', 'attribute');
                showMessage(L('succ'), ['text'=>'',"href"=>"index.php?act=attribute&op=attr_list&goods_type={$_POST['goods_type']}"]);
            }
        }

    }

    /*
     @return 删除商品属性
    */
    public function attr_del(){
        /* 权限判断 */
        admin_priv('goods_manage');
        $info=Model('goods_attr')->get_attribute_list("*","attr_id in ({$_GET['attr_id']})");
        $str='';
        foreach($info as $val){
            $str.=$val['attr_name'].',';
        }
        if(Model('attr')->delete_attr("attr_id in ({$_GET['attr_id']})")){
            /* 记录日志 */
            admin_log($str, 'edit', 'attribute');
            showMessage(L('del_succ'), ['text'=>'',"href"=>"index.php?act=attribute&op=attr_list&goods_type={$_GET['type_id']}"]);
        }

    }


    /**
     * @return 获取类型列表
     * @access  public
     * @return  array
     */

    public function GetTypeList(){
        $result = get_filter();

        if ($result === false){
            /* 分页大小 */
            $filter = array();

            /*获取参数一定要放在$filter数组里，否则会接受不到*/
            $filter['goods_type'] = !empty($_REQUEST['goods_type']) ? trim($_REQUEST['goods_type']):'';

            $where_brand ="AND cat_id={$filter['goods_type']}";

            if(intval($filter['goods_type'])==-10){
                $where_brand='';
            }

            $filter['record_count'] =count(Model('goods_attr')->get_attribute_list('*',"cat_id={$filter['goods_type']}"));

            if(intval($filter['goods_type'])==-10){
                $filter['record_count'] =count(Model('goods_attr')->get_attribute_list('*',""));
            }

            $filter = page_and_size($filter);

            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('attribute')."WHERE 1 ".$where_brand." ORDER BY  attr_id DESC ";


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
            $info=Model('goods_type')->select_goods_type_info('*',"cat_id={$filter['goods_type']}");
            $val['type_name']=$info['cat_name'];
            $val['type_id']=$info['cat_id'];

            if($val['attr_input_type']==0){
                $val['fs']='手动输入';
            }elseif($val['attr_input_type']==1){
                $val['fs']='选择输入';
            }else{
                $val['fs']='多行文本输入';
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
        make_json_result(Tpl::fetch('attribute.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }

}
