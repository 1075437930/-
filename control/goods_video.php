<?php
/**
 * 淘玉php 后台视频管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 后台视频管理类
 * $Id: goods_video.php  2018年6月13日10:32:42 陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class goods_videoControl extends BaseControl
{
    /**
     * @return 构造函数方法 Description
     */
    public function __construct()
    {
        Language::read('goods_video');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 视频管理列表
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

        $action_link = ['text' => L('video_add'), 'href' => 'index.php?act=goods_video&op=video_add'];

        Tpl::assign('ur_here', L('video'));

        Tpl::assign('action_link', $action_link);

        Tpl::display('video_list.htm');

    }


    /**
     * @return 获取视频列表
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
                $where_brand .= " AND goods_sn like '%".$_POST['cat_name']."%'";
            }

            /*搜索相关end*/
            $filter['record_count']=Model('goods_video')->get_goods_video_count($where_brand);

            $filter = page_and_size($filter);
            /* 查询记录 */
            $sql = "SELECT * FROM ".Model()->tablename('goods_video')." WHERE ".$where_brand." ORDER BY  video_id DESC ";
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

        $arr=Model('goods')->get_goods_list('*','');


        foreach($res as &$val){
             /*获取oss图片地址*/

            $url=Model('goods')->select_goods_info('*',"goods_sn ='{$val['goods_sn']}'");

            $val['video_imges'] = get_imgurl_oss($url['original_img'],40,40,false,true);

            preg_match("/http/",$val['video_url'],$arr2);

            if(!empty($arr2)){
                $val['video_url']=$val['video_url'];
            }else{
                $val['video_url']=get_videourl_oss($val['video_url'],true);
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
        make_json_result(Tpl::fetch('video_list.htm'), '',array('filter' => $brand_list['filter'], 'page_count' => $brand_list['page_count']));
    }


    /*
     * @return 删除视频
     * */
    public function video_del(){

        $info=Model('goods_video')->select_goods_video_info("*","video_id={$_GET['video_id']}");

        if(Model('goods_video')->delete_goods_video("video_id={$_GET['video_id']}")){

            preg_match("/http/",$info['video_url'],$arr2);

            if(!empty($arr2)){
                $chai=parse_url($info['video_url']);
                $path=$chai['path'];
                $path=substr($path,1);
                $info['video_url']=$path;
            }

            /*删除对应oss资源*/
            $dele=[];

            $dele['video_url']=$info['video_url'];

            if(ossdeleteObjects($dele)){

                /* 记录日志 */
                admin_log($info['video_url'], 'remove', 'video');

                showMessage(L('del_succ'), ['text' =>'', "href" => "index.php?act=goods_video&op=lists"]);
            }

        }
    }

    /**
     * @return 添加视频
     */
    public function video_add(){
        /* 权限判断 */
        admin_priv('goods_manage');

        if (!$_POST['goods_type']) {
            Tpl::assign('ur_here', L('class_add'));
            Tpl::display('video_add.htm');
        }else{
            $add = [];
            $add['goods_sn'] = $_POST['goods_sn'];
            $add['video_url'] = $_POST['video_url'];
            $add['add_time'] = gmtime();
            $user = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
            $add['user_id']=$user['user_id'];
            $add['user_name']=$user['user_name'];
            if (Model('goods_video')->insert_goods_video($add)) {

                /* 记录日志 */
                admin_log($add['video_url'], 'add', 'video');

                showMessage(L('add_succ'), ['text' =>'', "href" => "index.php?act=goods_video&op=lists"]);
            }
        }
    }


}