<?php
/**
 * 淘玉php 后台短信管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台短信管理类
 * $Id: sms.php  2018年6月19日10:32:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class smsControl extends BaseControl
{
    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('sms');//载入语言包
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

        Tpl::assign('mo',1);

        /*页码*/
        $brand_list['num']=[];
        for($i=1;$i<=$brand_list['record_count'];$i++){
            $brand_list['num'][]=$i;
        }

        Tpl::assign('num', $brand_list['num']);


        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);

        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);

        Tpl::assign('full_page',1);

        $action_link = ['text' => L('sms_add'), 'href' => 'index.php?act=sms&op=sms_add'];

        Tpl::assign('ur_here', L('sms'));

        Tpl::assign('action_link', $action_link);

        Tpl::display('sms_list.htm');

    }
    /**
     * @return 查看短信
     */
    public function view_message(){

        $res=Model('sms')->select_sms_info('*',"sms_id={$_GET['sms_id']}");


        /*处理start*/
        $res['from_sms_id']=Model('admin')->select_admin_info('*',"user_id={$res['from_sms_id']}")['user_name'];



        $str=substr($res['to_member_id'],1,strlen($res['to_member_id'])-2); ;

        $arr=Model('users')->get_users_list('*',"user_id in ({$str})");

        $res['to_member_id']='';

        foreach($arr as $val2){
            $res['to_member_id'].= $val2['user_name'].',';
        }

        $res['sms_time']=local_date('Y-m-d H:i:s',  $res['sms_time']);

        if($res['sms_state']==1){
            $res['sms_state']='发送成功';
        }else{
            $res['sms_state']='发送失败';
        }


        /*处理end*/
        Tpl::assign('res', $res);

        Tpl::assign('ur_here', L('view_message'));

        Tpl::display('view_message.htm');
    }

    /**
     * @return 添加短信
     */
    public function sms_add(){

        if(!$_POST['goods_type']){
           /*查询所有模板*/
            $count=Model('mail_templates')->get_mail_templates_count('');

            $res=Model('mail_templates')->get_mail_templates_list('*','','template_id desc',"0,{$count}");

            foreach($res as &$val){
                preg_match('/foreach/',$val['template_content'],$arr);
                if(!empty($arr)){
                    $val=null;
                }
            }

            Tpl::assign('res', $res);
            Tpl::assign('ur_here', L('view_add'));

            Tpl::display('add_message.htm');
        }else{

            $add=[];

            $add['sms_title']=$_POST['message_title'];

            $user = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
            $add['from_sms_id']=$user['user_id'];

            $to_member_name=str_replace(array("\r\n", "\r", "\n"), ",",$_POST['to_member_name']);

            $arr=explode(',',$to_member_name);

            $str='';

            foreach($arr as $val){
                $info=Model('users')->select_users_info('*',"user_name='{$val}'");
                if(empty($info) || !is_array($info)){
                    showMessage(L('cuowu'), ['text' =>'', "href" => "index.php?act=sms&op=sms_add"]);
                }

                $str.= $info['user_id'].',';

            }

            $add['to_member_id']=','.$str;
            $add['sms_time']=gmtime();

            $sg=explode(',',$_POST['tolval']);
            array_pop($sg);
            $to=explode(',',$_POST['tol']);
            array_pop($to);


            $xin=array_combine($to,$sg);

            $chuli_to=[];
            $duo=['{',"$",'}'];
            foreach($to as $key4=>$val5){
                /*去除里面的{ }*/
                $val5=str_replace($duo,'', $val5);
                $chuli_to[]=$val5;

            }
            $param=array_combine($chuli_to,$sg);


            foreach ($xin as $Key=>$Value) {
                $_POST['sms_body'] = str_replace($Key, $Value,  $_POST['sms_body']);
            }

            $add['sms_body']=$_POST['sms_body'];

            $tem=Model('mail_templates')->select_mail_templates_info('*',"template_id={$_POST['template_id']}");


            foreach($arr as $val){
                $info=Model('users')->select_users_info('*',"user_name='{$val}'");

                /*判断短信是否发送成功*/
                if(send_sms_msg($info['mobile_phone'],"{$tem['template_code']}",$param)){
                    $add['sms_state']=1;
                }else{
                    $add['sms_state']=0;
                }
            }

            if(Model('sms')->insert_sms($add)){
                /* 记录日志 */
                admin_log($add['sms_title'], 'add', 'sms');

                showMessage(L('add_succ'), ['text' =>'', "href" => "index.php?act=sms&op=lists"]);
            }

        }

    }

    /**
     * @return 删除短信
     */
    public function del_message(){
        $info=Model('sms')->select_sms_info("sms_id={$_GET['sms_id']}");
        if(Model('sms')->delete_sms("sms_id={$_GET['sms_id']}")){
            /* 记录日志 */
            admin_log($info['sms_title'], 'remove', 'sms');
            showMessage(L('del_succ'), ['text' =>'', "href" => "index.php?act=sms&op=lists"]);
        }
    }



    /**
     * @return 获取短信列表
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
                $where_brand .= " AND sms_title like '%".$_POST['cat_name']."%'";
            }

            /*搜索相关end*/
            $filter['record_count']=Model('sms')->get_sms_count($where_brand);


            $filter = page_and_size($filter);
            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('sms')." WHERE ".$where_brand." ORDER BY  sms_id DESC ";
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
            $val['from_sms_id']=Model('admin')->select_admin_info('*',"user_id={$val['from_sms_id']}")['user_name'];


            $str=substr($val['to_member_id'],1,strlen($val['to_member_id'])-2); ;

            $arr=Model('users')->get_users_list('*',"user_id in ({$str})");

            $val['to_member_id']='';

            foreach($arr as $val2){
                    $val['to_member_id'].= $val2['user_name'].',';
            }

            $val['sms_time']=local_date('Y-m-d H:i:s',  $val['sms_time']);

            if($val['sms_state']==1){
                $val['sms_state']='发送成功';
            }else{
                $val['sms_state']='发送失败';
            }

        }


        return array('brand' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    }

    /**
     * @return 分页、搜索
     */
    public function type_query() {
        $brand_list = $this->GetTypeList();
        Tpl::assign('mo',1);

        /*页码*/
        $brand_list['num']=[];
        for($i=1;$i<=$brand_list['record_count'];$i++){
            $brand_list['num'][]=$i;
        }

        Tpl::assign('num', $brand_list['num']);

        Tpl::assign('brand_list',   $brand_list['brand']);
        Tpl::assign('filter',       $brand_list['filter']);
        Tpl::assign('record_count', $brand_list['record_count']);
        Tpl::assign('page_count',   $brand_list['page_count']);
        $sort_flag  = sort_flag($brand_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('sms_list.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }


    /**
     * @return 获取模板
     */
    public function get_tem(){
         $info=Model("mail_templates")->select_mail_templates_info('*',"template_id={$_GET['template_id']}");

        $arr=[];

        $arr['message_title']=$info['template_subject'];

        $arr['template_content']=$info['template_content'];

        /*匹配出字符串里所有{...}的内容*/
        preg_match_all ("|{(.*)}|U",$info['template_content'],$arr2);

        $arr['gr']=$arr2[0];
        echo json_encode($arr);
    }


    public function shuc(){

        $count=count(Model('article')->get_article_comment_list("*","article_id=8175"));

        echo "<pre>";
        print_r($count);
        echo "</pre>";

    }
}