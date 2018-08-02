<?php

/**
 * 淘玉php 商店设置
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 商店设置
 * $Id: shop_config.php 17217 2018年4月26日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class shopconfigControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('shop_config'); //载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 编辑商店信息页面
     */
    public function edit() {
        /* 检查权限 */
        admin_priv('shop_config');
        $shop_model = Model('systemset');
        /* 可选语言 */
        Tpl::assign('lang_list', array($GLOBALS['setting_config']['lang_type']));
        Tpl::assign('ur_here', L('01_shop_config'));
        Tpl::assign('group_list', $this->get_settings(null, array('5'), array('chat')));
        Tpl::assign('countries', get_regions());
        if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'iis') !== false) {
            $rewrite_confirm = L('rewrite_confirm_iis');
        } else {
            $rewrite_confirm = L('rewrite_confirm_apache');
        }
        Tpl::assign('rewrite_confirm', $rewrite_confirm);
        if (C('shop_country') > 0) {
            Tpl::assign('provinces', get_regions(1, C('shop_country')));
            if (C('shop_province')) {
                Tpl::assign('cities', get_regions(2, C('shop_province')));
            }
        }
        Tpl::assign('cfg', $GLOBALS['setting_config']);
        Tpl::display('shop_config.htm');
    }

    /**
     * @return 编辑商店信息数据提交入库
     */
    public function update() {
        /* 检查权限 */
        admin_priv('shop_config');
        $shop_model = Model('shop_config');
        /*获取配置信息*/
        $arr = array();
        $res = $shop_model->get_shop_config_list('id, value','1');
        foreach ($res as $k => $v) {
            $arr[$v['id']] = $v['value'];
        }
        /*更新配置信息*/
        foreach ($_POST['value'] AS $key => $val) {
            if ($arr[$key] != $val) {
                $data = array();
                $data['value'] = trim($val);
                $res = $shop_model->update_shop_config($data,'id='.$key);
            }
        }

        /*处理图片*/
        /*查询旧图*/
        $file_var_list = array();
        $res = $shop_model->get_shop_config_list('*',"parent_id > 0 AND type = 'file'");
        foreach ($res as $key => $value) {
            $file_var_list[$value['code']] = $value;
        }
        $uploaddir = 'data/sys_config/attribute';
        foreach ($_FILES AS $code => $file) {
            if ((isset($file['error']) && $file['error'] == 0) || (!isset($file['error']) && $file['tmp_name'] != 'none')) {
                /*删除旧图*/
                if(in_array($code, $file_var_list)){
                    $oldpath[] = $file_var_list[$code];
                    ossdeleteObjects($oldpath);
                }
                /*更新图片*/
                $res = upload_oss_img($file,$uploaddir);
                $path = $res['url'];
                if($path){
                    $update = array('value'=>$path);
                    $shop_model->update_shop_config($update,"code = '$code'");
                }
            }
        }

        /* 处理发票类型及税率 */
        if (!empty($_POST['invoice_rate'])) {
            foreach ($_POST['invoice_rate'] as $key => $rate) {
                $rate = round(floatval($rate), 2);
                if ($rate < 0) {
                    $rate = 0;
                }
                $_POST['invoice_rate'][$key] = $rate;
            }

            /*增值税发票_更改_START_www.taoyumall.com*/
            $invoice = array(
                'type' => $_POST['invoice_type'],
                'rate' => $_POST['invoice_rate'],
                'enable' => $_POST['invoice_enable']
            );
            /*增值税发票_更改_END_www.taoyumall.com*/
            $shop_model->update_shop_config(array('value'=>serialize($invoice)),"code='invoice_type'");
        }

        /* 记录日志 */
        admin_log('', 'edit', 'shop_config');

        /* 清除缓存 */
        clear_cache_files();

        /*提示信息*/
        $links[] = array('text' => L('back_shop_config'), 'href' => 'index.php?act=shopconfig&op=edit');
        showMessage(L('save_success'), $links);

    }

    /**
     * @return 删除上传info
     */
    public function del() {
        /* 检查权限 */
        check_authz_json('shop_config');
        /* 取得参数 */
        $code = trim($_GET['code']);
        /*取得上传info信息*/
        $info = Model('shop_config')->select_shop_config_info('value',"code='$code'");
        /*删除图片*/
        if($info['value']){
            ossdeleteObjects($info);
        }
        /*更新数据库*/
        Model('shop_config')->update_shop_config(array('value'=>''),"code='$code'");
        /* 记录日志 */
        admin_log('', 'edit', 'shop_config');
        /* 清除缓存 */
        clear_cache_files();
        /*提示信息*/
        $links[] = array('text' => L('back_shop_config'), 'href' => 'index.php?act=shopconfig&op=edit');
        showMessage(L('save_success'), $links);
    }

    /**
     * @return  获得设置项
     * @param   array   $groups     需要获得的设置组
     * @param   array   $excludes   不需要获得的设置组
     * @param   array   $excludeCodes   不需要获得的设置组的名称-code列
     * @return  array
     */
    private function get_settings($groups = null, $excludes = null, $excludeCodes = null) {
        $config_groups = '';
        $excludes_groups = '';
        
        if (!empty($groups)) {
            foreach ($groups AS $key => $val) {
                $config_groups .= " AND (id='$val' OR parent_id='$val')";
            }
        }

        if (!empty($excludes)) {
            foreach ($excludes AS $key => $val) {
                $excludes_groups .= " AND (parent_id<>'$val' AND id<>'$val')";
            }
        }

        if (!empty($excludeCodes)) {
            foreach ($excludeCodes AS $key => $val) {
                $excludes_groups .= " AND (code<>'$val')";
            }
        }

        /* 取出全部数据：分组和变量 */
        $where = "type<>'hidden' $config_groups $excludes_groups";
        $item_list = Model('shop_config')->get_shop_config_list('*',$where,'parent_id, sort_order, id');

        /* 整理数据 */
        $group_list = array();
        foreach ($item_list AS $key => $item) {
            $pid = $item['parent_id'];
            $item['name'] = isset(L('cfg_name')[$item['code']]) ? L('cfg_name')[$item['code']] : $item['code'];
            $item['desc'] = isset(L('cfg_desc')[$item['code']]) ? L('cfg_desc')[$item['code']] : '';
            if ($item['code'] == 'sms_shop_mobile') {
                $item['url'] = 1;
            }

            if ($pid == 0) {
                /* 分组 */
                if ($item['type'] == 'group') {
                    $group_list[$item['id']] = $item;
                }
            } else {
                /* 变量 */
                if (isset($group_list[$pid])) {
                    if ($item['store_range']) {
                        $item['store_options'] = explode(',', $item['store_range']);
                        foreach ($item['store_options'] AS $k => $v) {
                            $item['display_options'][$k] = isset(L('cfg_range')[$item['code']][$v]) ?
                                    L('cfg_range')[$item['code']][$v] : $v;
                        }
                    }
                    $group_list[$pid]['vars'][] = $item;
                }
            }
        }

        return $group_list;
    }

}
?>

