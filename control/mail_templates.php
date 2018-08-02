<?php
/**
 * 淘玉php 管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台短信设置管理类
 * $Id: mail_templates.php  2018年6月19日10:32:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class mail_templatesControl extends BaseControl
{
    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('mail_templates');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 短信列表
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

        $action_link = ['text' => L('tem_add'), 'href' => 'index.php?act=mail_templates&op=templates_add'];

        Tpl::assign('ur_here', L('sms_conf'));

        Tpl::assign('action_link', $action_link);

        Tpl::display('templates_list.htm');

    }




    /**
     * @return 添加短信模板
     */
    public function templates_add(){

        if(!$_POST['goods_type']){

            Tpl::assign('ur_here', L('templates_add'));

            Tpl::display('templates_add.htm');
        }else{

            $add=[];
            $add['template_code']=$_POST['tem_key'];
            $add['is_html']=$_POST['is_html'];
            $add['template_subject	']=$_POST['tem_name'];
            $add['template_content']=$_POST['template_content'];
            $add['last_modify']=gmtime();

            if(Model('mail_templates')->insert_mail_templates($add)){

                showMessage(L('add_succ'), ['text' =>'', "href" => "index.php?act=mail_templates&op=lists"]);
            }

        }

    }


    /**
     * @return 修改模板
     */
    public function templates_edit(){
        if(!$_POST['template_id']){
            $res=Model('mail_templates')->select_mail_templates_info('*',"template_id={$_GET['template_id']}",'');


            Tpl::assign('ur_here', L('templates_edit'));
            Tpl::assign('res', $res);

            Tpl::display('templates_edit.htm');
        }else{

            $edit=[];
            $edit['template_code']=$_POST['tem_key'];
            $edit['is_html']=$_POST['is_html'];
            $edit['template_subject	']=$_POST['tem_name'];
            $edit['template_content']=$_POST['template_content'];
            $edit['last_modify']=time();

            if(Model('mail_templates')->update_mail_templates($edit,"template_id={$_POST['template_id']}")){

                showMessage(L('edit_succ'), ['text' =>'', "href" => "index.php?act=mail_templates&op=lists"]);
            }

        }

    }

    /**
     * @return 删除模板
     */
    public function templates_del(){
         if(Model('mail_templates')->delete_mail_templates("template_id={$_GET['template_id']}")){
             showMessage(L('del_succ'), ['text' =>'', "href" => "index.php?act=mail_templates&op=lists"]);
         }
    }

    /**
     * @return 获取模板列表
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
            $where_brand = " 1 ";
            if($filter['cat_name']){
                $where_brand .= " AND template_subject	 like '%".$_POST['cat_name']."%'";
            }

            /*搜索相关end*/
            $filter['record_count']=Model('mail_templates')->get_mail_templates_count($where_brand);

            $filter = page_and_size($filter);

            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('mail_templates')." WHERE ".$where_brand." ORDER BY  template_id DESC ";
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
            if($val['is_html']==0){
                $val['is_html']='否';
            }else{
                $val['is_html']='是';
            }

            $val['template_content']=htmlspecialchars($val['template_content']);

            $val['last_modify']=local_date('Y-m-d H:i:s',$val['last_modify']);
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
        make_json_result(Tpl::fetch('templates_list.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }


}