<?php

/**
 * 淘玉 后台app管理功能
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 吴博 $
 * 后台app管理
 * $Id: app_seting.php  2018-04-07   萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class app_setingControl extends BaseControl{
    public function __construct() {
        parent::__construct();
        Language::read('app_seting');
        $lang = Language::getLangContent();
        Tpl::assign('lang', $lang);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 登录背景图列表
     */
    public function login_img_list(){
        $img_list = $this->get_login_img_list();
        Tpl::assign('full_page', 1);
        Tpl::assign('ur_here', L('login_img_list'));
        Tpl::assign('img_list',      $img_list['list']);
        Tpl::assign('filter',          $img_list['filter']);
        Tpl::assign('record_count',    $img_list['record_count']);
        Tpl::assign('page_count',      $img_list['page_count']);
        $sort_flag  = sort_flag($img_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::display('login_img_list.htm');
    }
    
    /**
     * @return 登录背景图列表 刷新排序
     */
    public function login_img_query(){
        $img_list = $this->get_login_img_list();
        Tpl::assign('img_list',      $img_list['list']);
        Tpl::assign('filter',          $img_list['filter']);
        Tpl::assign('record_count',    $img_list['record_count']);
        Tpl::assign('page_count',      $img_list['page_count']);
        $sort_flag  = sort_flag($img_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('login_img_list.htm'), '',
            array('filter' => $img_list['filter'], 'page_count' => $img_list['page_count']));
    }

    /**
     * @return 打开添加背景图的页面
     */
    public function add_login_img(){
        Tpl::display('add_login_img.htm');
    }

    /**
     * @return 添加登录背景图
     */
    public function insert_login_img(){
        $link = array('href'=>'index.php?act=app_seting&op=login_img_list', 'text' => L('back_login_img_list'));
        $img = $_FILES['img'];
        if($img['error'] == 0){
            $files = upload_oss_img($img,'data/login_background');
            $fie['imgurl'] = $files['url'];
            $res = Model('app_seting')->insert_background_img($fie);
            if($res){
               showMessage(L('add_success'),$link);
            }else{
                $bool = ossDeleteFileObject($files['url']);
                showMessage(L('add_fail'),$link);
            }
        }else{
            showMessage(L('img_is_false'),$link);
        }
    }

    /**
     * @return 删除登录背景图
     */
    public function remove_login_img(){
        $obj = Model('app_seting');
        $link = array('href'=>'index.php?act=app_seting&op=login_img_list', 'text' => L('back_login_img_list'));
        $id = $_REQUEST['id'];
        $where = "id = $id";
        $result = $obj->select_background_img_info('imgurl',$where)['imgurl'];
        ossDeleteFileObject($res);
        $res1 = $obj->delete_background_img($where);
        if($res1){
            showMessage(L('del_success'),$link);
        }else{
            showMessage(L('del_fail'),$link);
        }
    }

    /**
     * @return 打开app二维码页面
     */
    public function qr_code(){
        $obj = Model('app_seting');
        $config = $obj->select_app_config_info();
        $config['app_qr_code'] = get_imgurl_oss($config['app_qr_code'],200,200);
        Tpl::assign('ur_here', L('qr_code'));
        Tpl::assign('app_config', $config);
        Tpl::display('app_qr_code.htm');
    }
    
    /**
     * @return 上传app二维码
     */
    public function add_qr_code(){
        $link = array('href'=>'index.php?act=app_seting&op=qr_code', 'text' => L('back_qr_code'));
        $app_qr_code_old = $_POST['app_qr_code_old'];
        $config_id = $_POST['id'];
        $qr_code = $_FILES['app_qr_code'];
        //上传图片
        if($qr_code['error']==0){
            $files = upload_oss_img($qr_code,'appimg/qr_code');
            $code_url = $files['url'];
        }else{
            showMessage(L('add_fail'),$link);
        }
        if($app_qr_code_old){
            $update['app_qr_code'] = $code_url;
            $where['id'] = $config_id;
            $res = Model('app_seting')->update_app_config($update,$where);
        }else{
            $data['app_qr_code']= $code_url;
            $res = Model('app_seting')->insert_app_config($data);
        }
        showMessage(L('add_success'),$link);
    }

    /**
     * @return 获取登录背景图
     */
    private function get_login_img_list(){
        $obj = Model('app_seting');
        $result = get_filter();
        if ($result === false){
            $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['record_count'] = $obj->get_background_img_count('1');
            $filter = page_and_size($filter);
            $sql = 'SELECT *'.' FROM ' .Model()->tablename('login_background_img'). ' AS login_background_img ' .                
                'WHERE 1 '.' ORDER by login_background_img.'.$filter['sort_by'].' '.$filter['sort_order'];
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $res = get_all_page($sql, $filter['page_size'], $filter['start']); 
        foreach($res as $k => $v){
            $res[$k]['imgurl'] = get_imgurl_oss($v['imgurl'],100,150);
        }
        $arr = array('list' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * @return app更新设置
     */
    public function app_update(){

        if(!$_POST['goods_type']){

            $res=Model('app_seting')->select_app_config_info('*','id=1');

            Tpl::assign('res',$res);

            Tpl::assign('ur_here', L('app_update'));
            Tpl::display('app_update.htm');
        }else{
            $edit=[];
            $edit['version']=$_POST['app_version'];
            $edit['version_description']=$_POST['version_description'];
            $edit['ios_url']=$_POST['ios_url'];
            $edit['is_update']=$_POST['is_update'];

            if(Model('app_seting')->update_app_config($edit,"id=1")){
                showMessage(L('update_succ'), ['text' =>'', "href" => "index.php?act=app_seting&op=app_update"]);
            }
        }
    }


}
