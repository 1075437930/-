<?php

/**
 * app管理中设置银行卡功能
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 吴博 $
 * $Id: authority.php  2018-04-07   萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class app_banksControl extends BaseControl{
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
     * @return 银行卡列表
     */
    public function lists(){
        $obj = Model('app_seting');
        $app_list = $this->get_appbankslist();
        Tpl::assign('ur_here', L('banks_list'));
        Tpl::assign('action_link', array('href' => 'index.php?act=app_banks&op=add', 'text' => "添加账号"));
        Tpl::assign('app_list',    $app_list);
        Tpl::display('app_banks_list.htm');
    }

    /**
     * @return 打开银行账号编辑页面
     */
    public function edit(){
        $bankid = $_REQUEST['id'];
        $link = array('href'=>'index.php?act=app_banks&op=lists', 'text' => L('back_banks_list'));
        if(!empty($bankid)){
            $app_list = $this->get_appbankslist($bankid);
            Tpl::assign('ur_here', L('banks_edit'));
            Tpl::assign('app_list',$app_list);
            Tpl::assign('form_op','update');
            Tpl::display('app_banks_info.htm');
        }else{
            showMessage(L('param_is_fail'), $link);
        }
    }

    /**
     * @return 修改银行卡信息
     */
    public function update(){
        $kahao = $_POST['kahao'];
        $bank_name = $_POST['bank_name'];
        $user_name = $_POST['user_name'];
        $imgsd = $_POST['imgsd'];
        $bank_imags = $_FILES['bank_imags'];
        $bankid = $_POST['id'];
        $link = array('href'=>'index.php?act=app_banks&op=lists', 'text' => L('back_banks_list'));
        if(!empty($bankid)){
            //代表图片上传
            $admin_info = $this->admin_info;
            $add_name = $admin_info['user_name'];
            if($bank_imags['size']){
                $files = upload_oss_img($bank_imags,'data/appimg/banks');
                $userimgurl= $files['url'];
                if(empty($userimgurl)){
                    showMessage(L('upload_img_fail'), $link);
                }else{
                    $imgurl = $userimgurl;
                }
            }else{
                $imgurl = $imgsd;
            }
            $nowtime = gmtime();
            $update['zhanghao'] = $kahao;
            $update['yh_name'] = $bank_name;
            $update['user_name'] = $user_name;
            $update['tubiao_style'] = $imgurl;
            $update['add_time'] = $nowtime;
            $update['add_name'] = $add_name;
            $where['yh_id'] = $bankid;
            $ruesd = Model('app_seting')->update_app_yinghang($update,$where);
            if(!empty($ruesd)){
                showMessage(L('update_success'),$link);
            }else{
                showMessage(L('update_fail'),$link);
            }
        }else{
            showMessage(L('param_is_fail'),$link);
        }
    }

    /**
     * @return 打开添加账户页面
     */
    public function add(){
        Tpl::assign('app_list','');
        Tpl::assign('form_op','insert');
        Tpl::display('app_banks_info.htm');
    }

    /**
     * @return 添加账户
     */
    public function insert(){
        $kahao = $_POST['kahao'];
        $bank_name = $_POST['bank_name'];
        $user_name = $_POST['user_name'];
        $imgsd = $_POST['imgsd'];
        $bank_imags = $_FILES['bank_imags'];
        //代表图片上传
        $admin_info = $this->admin_info;
        $add_name = $admin_info['user_name'];
        $link = array('href'=>'index.php?act=app_banks&op=lists', 'text' => L('back_banks_list'));
        if($bank_imags['size']){
            $files = upload_oss_img($bank_imags,'data/appimg/banks');
            $userimgurl= $files['url'];
            if(empty($userimgurl)){
                showMessage(L('upload_img_fail'), $link);
            }else{
                $imgurl = $userimgurl;
                $nowtime = gmtime();
                $field['zhanghao'] = $kahao;
                $field['yh_name'] = $bank_name;
                $field['user_name'] = $user_name;
                $field['tubiao_style'] = $imgurl;
                $field['add_time'] = $nowtime;
                $field['add_name'] = $add_name;
                $insert_id = Model('app_seting')->insert_app_yinghang($field);
                if(!empty($insert_id)){
                    showMessage(L('add_success'),$link);
                }else{
                    showMessage(L('add_fail'),$link);
                }
            }
        }else{
            showMessage(L('img_cannot_empty'),$link);
        }
    }

    /**
     * @return 删除账户
     */
    public function remove(){
        $banksid = $_REQUEST['id'];
        $link = array('href'=>'index.php?act=app_banks&op=lists', 'text' => L('back_banks_list'));
        if(!empty($banksid)){
            $where['yh_id'] = $banksid;
            $tureds = Model('app_seting')->delete_app_yinghang($where);
            if(!empty($tureds)){
                showMessage(L('delete_success'), $link);
            }else{
                showMessage(L('delete_fail'), $link);
            }
        }else{
            showMessage(L('param_is_fail'),$link);
        }
    }

    /**
     * @param int $bankid 银行卡id默认为空
     * @return 获取银行卡列表array
     */
    private function get_appbankslist($bankid=''){
        if(!empty($bankid)){          
            $where = "yh_id = $bankid";
            $arr = Model('app_seting')->select_yinghang_info('*',$where);
            $arr['tubiao_style'] = get_imgurl_oss($arr['tubiao_style']);
            return $arr;
        }else{
            $arr = Model('app_seting')->get_yinghang_list();
            foreach($arr as $k=>$v ){
                $arr[$k]['tubiao_style'] = get_imgurl_oss($v['tubiao_style']);
            }
            return $arr;
        }
    }

}