<?php

/**
 * 淘玉php 典藏管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 典藏管理类
 * $Id: diancang.php 17217 2018年4月28日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class diancangControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('diancang'); //载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 典藏首页
     */
    public function dcindex() {
        admin_priv('dcindex');
        $tongji = $this->dc_index_nums();
        $pending = $this->dc_index_dai();
        $goods_nums = $this->dc_goods_nums();
        $goods_price = $this->dc_price_tong();
        Tpl::assign('full_page', 1);
        Tpl::assign('tongji', $tongji);
        Tpl::assign('pending', $pending);
        Tpl::assign('goods_nums', $goods_nums);
        Tpl::assign('goods_price', $goods_price);
        Tpl::assign('ur_here', '典藏管理系统');
        Tpl::display('diancang_index.htm');
    }
    
    /*------------------------------------------------------ */
    //-- 典藏产品
    /*------------------------------------------------------ */
    
    /**
     * @return 典藏产品列表
     */
    public function dclist() {
        admin_priv('dclist');
        $dcgood_list = $this->get_dcgood_list();
        Tpl::assign('full_page', 1);
        Tpl::assign('diancang_list', $dcgood_list['dclist']);
        Tpl::assign('filter', $dcgood_list['filter']);
        Tpl::assign('record_count', $dcgood_list['record_count']);
        Tpl::assign('page_count', $dcgood_list['page_count']);
        $sort_flag = sort_flag($dcgood_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::assign('action_link', array('href' => 'index.php?act=diancang&op=dcadd', 'text' => '添加典藏产品'));
        Tpl::display('diancang_list.htm');
    }

    /**
     * @return 典藏产品列表排序、分页、查询
     */
    public function dcquery() {
        admin_priv('dclist');
        $dcgood_list = $this->get_dcgood_list();
        Tpl::assign('diancang_list', $dcgood_list['dclist']);
        Tpl::assign('filter', $dcgood_list['filter']);
        Tpl::assign('record_count', $dcgood_list['record_count']);
        Tpl::assign('page_count', $dcgood_list['page_count']);
        $sort_flag = sort_flag($dcgood_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('diancang_list.htm'), '', array('filter' => $dcgood_list['filter'], 
        'page_count' => $dcgood_list['page_count']));
    }

    /**
     * @return 添加典藏产品页面
     */
    public function dcadd() {
        admin_priv('dcadds');
        /*数据初始化*/
        $dcgood_into = array(
            'goods_name' => '选搜索后在选择',
            'goods_id' => '0',
            'dc_price' => '0',
            'typesd' => ''
        );
        $capitalid = 0;
        /* 模板赋值 */        
        Tpl::assign('insert_or_update', 'dcinsert');
        Tpl::assign('ur_here', '添加典藏产品');
        Tpl::assign('class_id', '');
        $class_list = $this->get_dcclass_style();
        Tpl::assign('stylelitst', $class_list['stylelitst']);
        Tpl::assign('action_link', array('href' => 'index.php?act=diancang&op=dclist', 'text' => '返回典藏产品列表'));
        Tpl::assign('ur_here', '典藏产品管理');
        Tpl::assign('dcgoods', $dcgood_into);
        /*2表示是余额 1表示淘金币 暂时做死的只用余额*/
        $yuebili_html = $this->build_yuebili_html(2, $capitalid);
        Tpl::assign('dc_yuebili_html', $yuebili_html);
        Tpl::display('diancang_info.htm');
    }

    /**
     * @return 添加典藏产品数据入库
     */
    public function dcinsert() {
        /*权限验证*/
        admin_priv('dcadds');
        $diancang_model = Model('diancang');
        $yuebili_model = Model('yuebili');
        /*插入数据*/
        $new_times = gmtime();
        $dc_data['dc_names'] = empty($_REQUEST['dc_names']) ? trim($_REQUEST['goods_names']) : trim($_REQUEST['dc_names']);
        $dc_data['dc_brief'] = empty($_REQUEST['dc_brief']) ? '' : trim($_REQUEST['dc_brief']);
        $dc_data['goods_id'] = empty($_REQUEST['goods_lists']) ? '' : trim($_REQUEST['goods_lists']);
        $dc_data['dc_price'] = empty($_REQUEST['dc_price']) ? '' : trim($_REQUEST['dc_price']);
        $dc_data['dc_classid'] = empty($_REQUEST['dc_class']) ? '' : trim($_REQUEST['dc_class']);
        $dc_data['juan_type'] = empty($_REQUEST['juan_type']) ? '' : trim($_REQUEST['juan_type']);
        $dc_data['add_time'] = $new_times;
        $dc_data['dc_update'] = $new_times;
        $biliid = $_REQUEST['biliid'];
        $bili_into = $_REQUEST['bili_into'];        
        /*数据插入典藏商品表*/
        $diancangid = $diancang_model->insert_capital_goods($dc_data);
        /*典藏商品相关其他数据表数据操作*/
        if(!empty($diancangid)){            
            $nums = count($biliid);
            $dcusernum = $nums - 1;
            $max_bilis = 0;
            for ($x = 0; $x <= $dcusernum; $x++) {
                $instinto = array();
                $biliids = $biliid[$x];
                $yuefen = $yuebili_model->select_capital_bili_info('yuefen',"set_id = '$biliids'")['yuefen'];
                if ($x == $dcusernum) {
                    $instinto['capitalid'] = $diancangid;
                    $instinto['set_id']    = $biliid[$x];
                    $instinto['yuefen']    = $yuefen;
                    $instinto['stypes']    = "2";
                    $instinto['bili']      = $bili_into[$biliid[$x]];
                    $resu = $diancang_model->insert_capital_goodsyue($instinto); 
                } else {
                    $instinto['capitalid'] = $diancangid;
                    $instinto['set_id']    = $biliid[$x];
                    $instinto['yuefen']    = $yuefen;
                    $instinto['stypes']    = "2";
                    $instinto['bili']      = $bili_into[$biliid[$x]];
                    $resu = $diancang_model->insert_capital_goodsyue($instinto); 
                }

                if ($bili_into[$biliid[$x]] > $max_bilis) {
                    $max_bilis = $bili_into[$biliid[$x]];
                }
            }
            /*插入最大比例*/
            $whe = "capitalid = '$diancangid'";
            $diancang_model->update_capital_goods(array('max_bili'=>$max_bilis),$whe);
            if (!empty($resu)) {
                admin_log($dc_data['dc_names'], 'add', 'capital_goods');
                /* 清楚缓存文件 */
                clear_cache_files();
                $link = array('text' => L('go_back',''), 'href' => 'index.php?act=diancang&op=dclist');
                showMessage('典藏产品添加成功', $link);
            } else {
                $diancang_model->delete_capital_goods("capitalid = '$diancangid'");
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage('添加失败，重新添加', $link);
            }
        } else {
            $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage('添加失败，重新添加', $link);
        }
    }

    /**
     * @return 编辑典藏产品页面
     */
    public function dcedit() {
        admin_priv('dcadds');
        /* 模板赋值 */        
        Tpl::assign('insert_or_update', 'dcupdate');
        $capitalid = $_REQUEST['capitalid'];
        $dcgood_into = $this->get_dcgood_info($capitalid);
        Tpl::assign('ur_here', '修改典藏产品');
        Tpl::assign('class_id', $dcgood_into['dc_classid']);
        $class_list = $this->get_dcclass_style();
        Tpl::assign('stylelitst', $class_list['stylelitst']);
        Tpl::assign('action_link', array('href' => 'index.php?act=diancang&op=dclist', 'text' => '返回典藏产品列表'));
        Tpl::assign('dcgoods', $dcgood_into);
        /*2表示是余额 1表示淘金币 暂时做死的只用余额*/
        $yuebili_html = $this->build_yuebili_html(2, $capitalid);
        Tpl::assign('dc_yuebili_html', $yuebili_html);
        Tpl::display('diancang_info.htm');
    }

     /**
     * @return 编辑典藏产品页面
     */
    public function dcupdate() {
        /*权限验证*/
        admin_priv('dcadds');
        $diancang_model = Model('diancang');
        $yuebili_model = Model('yuebili');
        $capitalid = trim($_REQUEST['capitalid']);
        /*更新数据*/
        $dc_data['dc_names'] = empty($_REQUEST['dc_names']) ? trim($_REQUEST['goods_names']) : trim($_REQUEST['dc_names']);
        $dc_data['dc_brief'] = empty($_REQUEST['dc_brief']) ? '' : trim($_REQUEST['dc_brief']);
        $dc_data['goods_id'] = empty($_REQUEST['goods_lists']) ? '' : trim($_REQUEST['goods_lists']);
        $dc_data['dc_price'] = empty($_REQUEST['dc_price']) ? '' : trim($_REQUEST['dc_price']);
        $dc_data['dc_classid'] = empty($_REQUEST['dc_class']) ? '' : trim($_REQUEST['dc_class']);
        $dc_data['juan_type'] = empty($_REQUEST['juan_type']) ? '' : trim($_REQUEST['juan_type']);
        $dc_data['dc_update'] = gmtime();
        $biliid = $_REQUEST['biliid'];
        $bili_into = $_REQUEST['bili_into'];
        /*更新典藏商品表*/
        $res = $diancang_model->update_capital_goods($dc_data,"capitalid = '$capitalid'");
        /*典藏商品相关其他数据表数据操作*/
        if (!empty($res) && $res!=0) {
            /*删除旧的月份信息*/
            $diancang_model->delete_capital_goodsyue("capitalid = '$capitalid'");
            $nums = count($biliid);
            $dcusernum = $nums - 1;
            $max_bilis = 0;
            for ($x = 0; $x <= $dcusernum; $x++) {
                $instinto = array();
                $biliids = $biliid[$x];
                $yuefen = $yuebili_model->select_capital_bili_info('yuefen',"set_id = '$biliids'")['yuefen'];
                if ($x == $dcusernum) {
                    $instinto['capitalid'] = $capitalid;
                    $instinto['set_id']    = $biliid[$x];
                    $instinto['yuefen']    = $yuefen;
                    $instinto['stypes']    = "2";
                    $instinto['bili']      = $bili_into[$biliid[$x]];
                    $resu = $diancang_model->insert_capital_goodsyue($instinto);
                } else {
                    $instinto['capitalid'] = $capitalid;
                    $instinto['set_id']    = $biliid[$x];
                    $instinto['yuefen']    = $yuefen;
                    $instinto['stypes']    = "2";
                    $instinto['bili']      = $bili_into[$biliid[$x]];
                    $resu = $diancang_model->insert_capital_goodsyue($instinto);
                }

                if ($bili_into[$biliid[$x]] > $max_bilis) {
                    $max_bilis = $bili_into[$biliid[$x]];
                }
            }
            /*插入最大比例*/
            $whe = "capitalid = '$capitalid'";
            $diancang_model->update_capital_goods(array('max_bili'=>$max_bilis),$whe);
            if (!empty($resu) && $resu!=0) {
                admin_log($dc_data['dc_names'], 'edit', 'capital_goods');
                /* 清楚缓存文件 */
                clear_cache_files();
                $link = array('text' => L('go_back',''), 'href' => 'index.php?act=diancang&op=dclist');
                showMessage('典藏产品修改成功', $link);
            } else {
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage('修改失败，重新修改', $link);
            }
        } else {
            $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
            showMessage('修改失败，重新修改', $link);
        }
    }

    /**
     * @return 删除典藏产品
     */
    public function dcremove() {
        admin_priv('dcadds');
        $diancang_model = Model('diancang');
        $dc_id = intval($_GET['id']);
        $where = "capitalid = '$dc_id'";
        $result = $diancang_model->delete_capital_goods($where);
        $result = $diancang_model->delete_capital_goodsyue($where);
        $result = $diancang_model->delete_capital_goodsuser("dcgoods_id = '$dc_id'");
        $result = Model('diancang_tagods')->delete_capital_tagods($where);
        if ($result) {
            admin_log($dc_id, 'remove', 'capital_goods');
            $url = 'index.php?act=diancang&op=dcquery';
            ecs_header("Location: $url\n");
            exit;
        }
    }

    /**
     * @return 添加典藏产品商品搜索
     */
    public function search_goods() {
        admin_priv('dcadds');
        $filter = $_GET['keyword'];
        $arr['goodsinto'] = $this->get_goods_list($filter);
        make_json_result($arr);
    }

    /**
     * @return 添加典藏产品商品搜索后选中商品（显示商品价格）
     */
    public function xuan_goods() {
        admin_priv('dcadds');
        $filter = $_GET['goods_id'];
        $arr = $this->get_goods_info($filter);
        make_json_result($arr);
    }
   
    /*------------------------------------------------------ */
    //-- 典藏产品状态切换
    /*------------------------------------------------------ */
    
    /**
     * @return 勾选典藏产品是否能用卷
     */
    public function toggle_juan_type() {
        admin_priv('dcadds');
        $dc_id = intval($_REQUEST['id']);
        $is_value = intval($_REQUEST['val']);
        $where = " capitalid = '$dc_id'";
        $res = Model('diancang')->update_capital_goods(array('juan_type'=>$is_value),$where);
        if($res){
            admin_log($dc_id . '是否用卷修改', 'edit', 'capital_goods');
            clear_cache_files();
            make_json_result($is_value);
        }      
    }

    /**
     * @return 修改典藏产品显示不显示情况
     */
    public function toggle_dc_show() {
        admin_priv('dcadds');
        $dc_id = intval($_REQUEST['id']);
        $is_value = intval($_REQUEST['val']);
        $where = " capitalid = '$dc_id'";
        $res = Model('diancang')->update_capital_goods(array('dc_show'=>$is_value),$where);
        if($res){
            admin_log($dc_id . '显示修改', 'edit', 'capital_goods');
            clear_cache_files();
            make_json_result($is_value);
        }       
    }

    /**
     * @return 勾选典藏产品是否是精品按钮
     */
    public function toggle_dc_best() {
        admin_priv('dcadds');
        $dc_id = intval($_REQUEST['id']);
        $is_value = intval($_REQUEST['val']);
        $where = " capitalid = '$dc_id'";
        $res = Model('diancang')->update_capital_goods(array('dc_best'=>$is_value),$where);
        if($res){
            admin_log($dc_id . '精品修改', 'edit', 'capital_goods');
            clear_cache_files();
            make_json_result($is_value);
        }        
    }

    /**
     * @return 勾选典藏产品是否是新品按钮
     */
    public function toggle_dc_new() {
        admin_priv('dcadds');
        $dc_id = intval($_REQUEST['id']);
        $is_value = intval($_REQUEST['val']);
        $where = " capitalid = '$dc_id'";
        $res = Model('diancang')->update_capital_goods(array('dc_new'=>$is_value),$where);
        if($res){
            admin_log($dc_id . '新品修改', 'edit', 'capital_goods');
            clear_cache_files();
            make_json_result($is_value);
        }        
    }

    /**
     * @return 勾选典藏产品是否是新品按钮
     */
    public function toggle_dc_hot() {
        admin_priv('dcadds');
        $dc_id = intval($_REQUEST['id']);
        $is_value = intval($_REQUEST['val']);
        $where = " capitalid = '$dc_id'";
        $res = Model('diancang')->update_capital_goods(array('dc_hot'=>$is_value),$where);
        if($res){
            admin_log($dc_id . '热门修改', 'edit', 'capital_goods');
            clear_cache_files();
            make_json_result($is_value);
        }        
    }

    /*------------------------------------------------------ */
    //-- 典藏产品标签
    /*------------------------------------------------------ */
    
    /**
     * @return 典藏产品标签设置
     */
    public function set_dctags() {
        admin_priv('dctags');
        $capitalid = trim($_REQUEST['capitalid']);
        $goodtag_list = $this->get_goodtag_list();
        Tpl::assign('full_page', 1);
        Tpl::assign('dcgtags_list', $goodtag_list['dcgtags_list']);
        Tpl::assign('filter', $goodtag_list['filter']);
        Tpl::assign('record_count', $goodtag_list['record_count']);
        Tpl::assign('page_count', $goodtag_list['page_count']);
        $sort_flag = sort_flag($goodtag_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::assign('action_link', array('href' => 'index.php?act=diancang&op=add_goodtags&capitalid=' . $capitalid, 'text' => '添加对应标签设置'));
        Tpl::display('diancang_dcgstags_list.htm');
    }

    /**
     * @return 典藏产品标签设置排序、分页、查询
     */
    public function query_dcgtags() {
        admin_priv('dctags');
        $capitalid = trim($_REQUEST['capitalid']);
        $goodtag_lists = $this->get_goodtag_list();
        Tpl::assign('dcgtags_list', $goodtag_lists['dcgtags_list']);
        Tpl::assign('filter', $goodtag_lists['filter']);
        Tpl::assign('record_count', $goodtag_lists['record_count']);
        Tpl::assign('page_count', $goodtag_lists['page_count']);
        $sort_flag = sort_flag($goodtag_lists['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('diancang_dcgstags_list.htm'), '', 
            array('filter' => $goodtag_lists['filter'], 'page_count' => $goodtag_lists['page_count']));
    }

    /**
     * @return 典藏产品标签添加页面
     */
    public function add_goodtags() {
        admin_priv('dctags');
        $capitalid = trim($_REQUEST['capitalid']);
        $htmltags = $this->build_goodtag_html($capitalid);
        Tpl::assign('action_link', array('href' => 'index.php?act=diancang&op=set_dctags&capitalid=' . $capitalid, 'text' => '返回产品标签列表'));
        Tpl::assign('ur_here', '标签管理');
        Tpl::assign('build_goodtag_html', $htmltags);
        Tpl::assign('dc_ids', $capitalid);
        Tpl::display('diancang_dcgstags_into.htm');
    }

    /**
     * @return 典藏产品标签添加数据入库
     */
    public function insert_goodtags() {
        admin_priv('dctags');
        $model = Model('diancang_tagods');
        $newstags_name = empty($_REQUEST['newstags_name']) ? '' : trim($_REQUEST['newstags_name']);
        $goodstag = empty($_REQUEST['goodstag']) ? '' : $_REQUEST['goodstag'];
        $capitalid = empty($_REQUEST['dc_id']) ? '' : $_REQUEST['dc_id'];
        if (!empty($newstags_name)) {
            $tags_id = $model->insert_capital_tags(array('tags_name'=>$newstags_name));
            admin_log($newstags_name, 'add', 'capital_tags');
        }

        if (!empty($goodstag)) {
            $result = $model->delete_capital_tagods("capitalid = '$capitalid'");
            foreach ($goodstag as $value) {
                $instinto = array();
                $instinto['tags_id'] = $value;
                $instinto['capitalid'] = $capitalid;
                $model->insert_capital_tagods($instinto);
            }
        }
        if (!empty($tags_id)) {
            $data['tags_id'] = $tags_id;
            $data['capitalid'] = $capitalid;
            $model->insert_capital_tagods($data);
        }
        if (!empty($goodstag) || !empty($newstags_name)) {
            admin_log($newstags_name, 'add', 'capital_tagods');
            /* 清楚缓存文件 */
            clear_cache_files();
            $link = array('text' => '返回对应标签列表', 'href' => 'index.php?act=diancang&op=set_dctags&capitalid=' . $capitalid);
            showMessage('典藏对应标签添加成功', $link);
        }
    }

    /**
     * @return 典藏产品标签删除
     */
    public function remove_goodtags() {
        admin_priv('dctags');
        $model = Model('diancang_tagods');
        $taggood_id = intval($_GET['id']);
        $capitalid = $model->select_capital_tagods_info('capitalid'," taggood_id = '$taggood_id'");
        $result = $model->delete_capital_tagods(" taggood_id = '$taggood_id'");;
        if ($result) {
            admin_log($taggood_id, 'remove', 'capital_tagods');
            $url = 'index.php?act=diancang&op=query_dcgtags&capitalid=' . $capitalid['capitalid'];
            ecs_header("Location: $url\n");
            exit;
        }
    }
    
    /*------------------------------------------------------ */
    //-- 典藏管理私有方法
    /*------------------------------------------------------ */

    /**
     * @return 获取典藏产品典藏列表
     * @return array
     */
    private function get_dcgood_list(){
        /*过滤条件*/
        $result = get_filter();
        if ($result === false){   
            $filter = array();
            $diancang_model = Model('diancang');
            $getimts = gmtime();
            $last = strtotime("-1 month", $getimts);
            /*上个月最后一天*/
            $last_lastday = date("Y-m-t", $last);
            /*上个月第一天*/
            $last_firstday = date('Y-m-01', $last);
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'capitalid' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['benyue_add'] = empty($_REQUEST['benyue_add']) ? '' : trim($_REQUEST['benyue_add']);
            $filter['shangyue_add'] = empty($_REQUEST['shangyue_add']) ? '' : trim($_REQUEST['shangyue_add']);
            $wheresd = '';
            /*关键字搜索支持典藏名称、典藏描述、和商品表商品货号*/
            if (!empty($filter['keyword'])) {
                $wheresd .= "  AND  (capital_goods.dc_names like '%" . $filter['keyword'] . "%' OR capital_goods.dc_brief like '%" . $filter['keyword'] . "%' OR goods.goods_sn like '%" . $filter['keyword'] . "%') ";
            }
      
            if (!empty($filter['benyue_add'])) {
                $wheresd .= " AND capital_goods.add_time >" . get_menyone($getimts) . " AND capital_goods.add_time < " . get_menylast($getimts);
            }
            if (!empty($filter['shangyue_add'])) {
                $wheresd .= " AND capital_goods.add_time >" . strtotime($last_firstday) . " AND capital_goods.add_time < " . strtotime($last_lastday);
            }
            $filter['record_count'] = $diancang_model->get_capital_goods_goods_count('1'.$wheresd);
            $filter = page_and_size($filter);
            $sql = "SELECT capital_goods.*,goods.goods_sn,goods.goods_name,goods.shop_price,goods.promote_price,goods.goods_fenxiao_price,goods.original_img,cc.class_name FROM " . 
                Model()->tablename('capital_goods') . " as capital_goods " .
                " LEFT JOIN " . Model()->tablename('goods') . " AS goods ON capital_goods.goods_id = goods.goods_id " .
                " LEFT JOIN " . Model()->tablename('capital_class') . " AS cc ON capital_goods.dc_classid = cc.dcclass_id WHERE 1 " . $wheresd .
                " ORDER by $filter[sort_by] $filter[sort_order] " ;
        } else {
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);        
        foreach ($row as $key => $value) {
            $dc_ids = $value['capitalid'];
            $goodyue = $diancang_model->get_capital_goodsyue_list('*',"capitalid = '$dc_ids'");
            $xiangqing = '总共设置' . count($goodyue) . '种,收益模式 有：';
            foreach ($goodyue as $key2 => $value2) {
                $xiangqing .= $value2['yuefen'] . '月-比例：' . $value2['bili'] . '% ，';
            }
            $row[$key]['imgurls'] = get_imgurl_oss($value['original_img'], 30, 30, false, true);
            $row[$key]['add_time'] = local_date('Y-m-d', $value['add_time']);
            $row[$key]['dc_update'] = local_date('Y-m-d', $value['dc_update']);

            $row[$key]['xiang'] = $xiangqing;
            $goodtagnum = Model('diancang_tagods')->get_capital_tagods_count("capitalid = '$dc_ids'");
            $row[$key]['goodtagnum'] = $goodtagnum;
        }
        $arr = array('dclist' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * @return 获取典藏首页统计数据
     * @return array
     */
    private function dc_index_nums() {
        $getimts = gmtime();
        $times['jin'] = time_adsu($getimts, 2, 0);
        $times['zou'] = time_adsu($getimts, 2, 7);
        $times['yue'] = get_menyone($getimts);
        $times['zong'] = 0;
        $zonglist = '';
        /*今天 本周 本月 数据统计*/
        foreach ($times as $keys => $value) {
            $wheresd = " WHERE pay_status = 1 AND order_status IN (0,1,5) AND pay_time >= '$value' ";
            $where = "  pay_status = 1 AND order_status IN (0,1,5) AND pay_time >= '$value' ";
            $field = 'COUNT(*) AS nums ,SUM(goods_amount) AS good_pics,SUM(goods_youpic) AS youpic,SUM(order_amount) AS ying_pay';    
            $row = Model('diancang_order')->select_capital_order_info($field,$where);
            if (empty($row['good_pics'])) {
                $row['good_pics'] = 0;
            }
            if (empty($row['youpic'])) {
                $row['youpic'] = 0;
            }
            if (empty($row['ying_pay'])) {
                $row['ying_pay'] = 0;
            }
            $zonglist[$keys] = $row;
        }

        /*计算结算*/
        $where = "  pay_status = 1 AND order_status = 4 AND order_status = 4 AND shipping_status = 1 ";
        $field = 'COUNT(*) AS nums ,SUM(goods_amount) AS good_pics,SUM(goods_youpic) AS youpic,SUM(order_amount) AS ying_pay';    
        $jie = Model('diancang_order')->select_capital_order_info($field,$where);     
        if (empty($jie['good_pics'])) {
            $jie['good_pics'] = 0;
        }
        if (empty($jie['youpic'])) {
            $jie['youpic'] = 0;
        }
        if (empty($jie['ying_pay'])) {
            $jie['ying_pay'] = 0;
        }
        $zonglist['jiesuan'] = $jie;

        return ($zonglist) ;
    }

    /**
     * @return 获取典藏首页待处理数据
     * @return array
     */
    private function dc_index_dai(){
        $diancang_order_model = Model('diancang_order');
        $diancang_back_model = Model('diancang_back');
        $getimts = gmtime();
        /*待确认订单数*/
        $where = 'pay_status = 1 AND order_status = 0 AND shipping_status = 0 ';
        $queren = $diancang_order_model->get_capital_order_count($where);
        /*待处理发货数*/
        $where = 'pay_status = 1 AND order_status IN(5,1) AND shipping_status = 0 ';
        $fahuo = $diancang_order_model->get_capital_order_count($where);
        /*待确认退货数*/
        $where = 'refund_type = 1 AND status_back = 5 AND status_refund = 0 ';
        $tuihuo = $diancang_back_model->get_capital_back_count($where);
        /*待处理收货数*/
        $where = 'refund_type = 1 AND status_back = 2 AND status_refund = 0 ';
        $shouhuo = $diancang_back_model->get_capital_back_count($where);
        /*七天内到期的订单数*/
        $where = "pay_status = 1 AND order_status IN(5,1) AND end_time <= " . time_adsu($getimts, 1, 7) . " AND end_time >= '$getimts'";
        $seven_days = $diancang_order_model->get_capital_order_count($where);
        /*本月到期的订单数*/
        $where = "pay_status = 1 AND order_status IN(5,1) AND end_time <= " . get_menylast($getimts) . " AND end_time >= " . get_menyone($getimts);
        $one_moon = $diancang_order_model->get_capital_order_count($where);
        /*到期未退的订单数*/
        $where = "pay_status = 1 AND order_status IN(5,1) AND back_goods =0 AND end_time <= " . $getimts . " AND end_time >= " . time_adsu($getimts, 2, 7);
        $deposit = $diancang_order_model->get_capital_order_count($where);
        /*到期不退的订单数*/
        $where = "pay_status = 1 AND order_status IN(5,1) AND back_goods =0  AND end_time < " . time_adsu($getimts, 2, 7);
        $withdraw = $diancang_order_model->get_capital_order_count($where);
        $zonglist['queren'] = $queren;
        $zonglist['fahuo'] = $fahuo;
        $zonglist['tuihuo'] = $tuihuo;
        $zonglist['shouhuo'] = $shouhuo;
        $zonglist['seven_days'] = $seven_days;
        $zonglist['one_moon'] = $one_moon;
        $zonglist['deposit'] = $deposit;
        $zonglist['withdraw'] = $withdraw;
        $total = 0;
        foreach ($zonglist as $keys => $value) {
            $total += $value;
        }
        $zonglist['total'] = $total;
        return $zonglist;
    }

    /**
     * @return 获取典藏首页产品统计数据
     * @return array
     */
    private function dc_goods_nums() {
        $diancang_order_model = Model('diancang_order');
        $diancang_back_model = Model('diancang_back');
        $diancang_model = Model('diancang');
        $getimts = gmtime();
        $last = strtotime("-1 month", $getimts);
        $last_lastday = date("Y-m-t", $last);/*上个月最后一天*/
        $last_firstday = date('Y-m-01', $last);/*上个月第一天*/
        /*本月新添加典藏产品数*/
        $where = "add_time <= " . get_menylast($getimts) . " AND add_time >" . get_menyone($getimts);
        $benyue_num = $diancang_model->get_capital_goods_count($where);
        /*本月退回商品数*/
        $where = "capital_order.order_status = 4 AND capital_order.back_goods = 2 AND capital_back.add_time < " . get_menylast($getimts) . " AND capital_back.add_time > " . get_menyone($getimts) . " AND capital_back.status_back = 3";
        $res = $diancang_back_model->get_capital_back_order_list('*',$where);
        $benyue_refund_num = count($res);
        /*上月新加典藏产品*/
        $where = "add_time <= " . strtotime($last_lastday) . " AND add_time>" . strtotime($last_firstday);
        $shangyue_num = $diancang_model->get_capital_goods_count($where);
        /*上月退回商品数*/
        $where = "capital_order.order_status = 4 AND capital_order.back_goods = 2 AND capital_back.add_time < " . strtotime($last_lastday) . " AND capital_back.add_time > " . strtotime($last_firstday) . " AND capital_back.status_back = 3";
        $res = $diancang_back_model->get_capital_back_order_list('*',$where);
        $shangyue_refund_num = count($res);
        /*总退回典藏产品数*/
        $where = "capital_order.order_status = 4 AND capital_order.back_goods = 2 AND status_back = 3";
        $res = $diancang_back_model->get_capital_back_order_list('*',$where);
        $total_refund_num = count($res);
        /*总卖出典藏产品数*/
        $where = "capital_order.order_status IN(5,1)  AND capital_order.pay_status = 1";
        $res = $diancang_order_model->get_capital_order_ordergoods_list('*',$where);
        $total_sole_num = count($res);
        /*总投资（在外）典藏商品数*/
        $where = "capital_order.back_goods = 0 AND  capital_order.order_status IN (5,1) AND capital_order.pay_status = 1";
        $res = $diancang_order_model->get_capital_order_ordergoods_list('*',$where);
        $total_out_num = count($res);
        /*现有库存*/
        $where = "stats_buy =1 " ;
        $total_kucun_num = $diancang_model->get_capital_goods_count($where);
        $zonglist['benyue_num'] = $benyue_num;
        $zonglist['benyue_refund_num'] = $benyue_refund_num;
        $zonglist['shangyue_num'] = $shangyue_num;
        $zonglist['shangyue_refund_num'] = $shangyue_refund_num;
        $zonglist['total_refund_num'] = $total_refund_num;
        $zonglist['total_sole_num'] = $total_sole_num;
        $zonglist['total_out_num'] = $total_out_num;
        $zonglist['total_kucun_num'] = $total_kucun_num;
        $total = 0;
        foreach ($zonglist as $keys => $value) {
            $total += $value;
        }
        $zonglist['total'] = $total;
        return $zonglist;
    }

    /**
     * @return 获取典藏首页产品价格统计数据
     * @return  array
     */
    private function dc_price_tong() {
        $diancang_order_model = Model('diancang_order');
        $diancang_back_model = Model('diancang_back');
        $diancang_model = Model('diancang');
        $getimts = gmtime();
        $last = strtotime("-1 month", $getimts);
        $last_lastday = date("Y-m-t", $last);//上个月最后一天
        $last_firstday = date('Y-m-01', $last);//上个月第一天
        /*本月产品均价*/
        $field = 'avg(dc_price) AS benyue_goods_price_avg';
        $where = "add_time <= " . get_menylast($getimts) . " AND add_time >" . get_menyone($getimts);
        $benyue_goods_price_avg = $diancang_model->select_capital_goods_info($field,$where)['benyue_goods_price_avg'];
        
        /*本月购买均价*/
        $field = 'avg(goods_amount) AS benyue_buy_price_avg';
        $where = "order_status IN(5,1) AND pay_status = 1 AND add_time <= " . get_menylast($getimts) . " AND add_time >" . get_menyone($getimts);
        $benyue_buy_price_avg = $diancang_order_model->select_capital_order_info($field,$where)['benyue_buy_price_avg'];
        /*上月产品均价*/
        $field = 'avg(dc_price) AS shangyue_goods_price_avg';
        $where = "add_time <= " . strtotime($last_lastday) . " AND add_time>" . strtotime($last_firstday);
        $shangyue_goods_price_avg = $diancang_model->select_capital_goods_info($field,$where)['shangyue_goods_price_avg'];
        /*上月购买均价*/
        $field = 'avg(goods_amount) AS shangyue_buy_price_avg';
        $where = "order_status IN(5,1) AND pay_status = 1 AND add_time < " . strtotime($last_lastday) . " AND add_time > " . strtotime($last_firstday);
        $shangyue_buy_price_avg = $diancang_order_model->select_capital_order_info($field,$where)['shangyue_buy_price_avg'];
        /*总退款价格（未退款）*/
        $field = 'avg(refund_money) AS total_refund_money';
        $where = "status_refund = 0";
        $total_refund_money = $diancang_back_model->select_capital_back_info($field,$where)['total_refund_money'];
        /*总购买价格*/
        $field = 'avg(goods_amount) AS total_buy_num';
        $where = "order_status IN (5,1) AND pay_status = 1";
        $total_buy_num = $diancang_order_model->select_capital_order_info($field,$where)['total_buy_num'];
        /*本月优惠券*/
        $where = "youpic_id = 2 AND order_status IN (5,1) AND pay_status = 1 AND add_time <= " . get_menylast($getimts) . " AND add_time > " . get_menyone($getimts);
        $benyue_youhui_num = $diancang_order_model->get_capital_order_count($where);
        /*总优惠券*/
        $where = "youpic_id = 2 AND order_status IN(5,1) AND pay_status = 1";
        $total_youhui_num = $diancang_order_model->get_capital_order_count($where);
        $zonglist['benyue_goods_price_avg'] = $benyue_goods_price_avg;
        $zonglist['benyue_buy_price_avg'] = $benyue_buy_price_avg;
        $zonglist['shangyue_goods_price_avg'] = $shangyue_goods_price_avg;
        $zonglist['shangyue_buy_price_avg'] = $shangyue_buy_price_avg;
        $zonglist['total_refund_money'] = $total_refund_money;
        $zonglist['total_buy_num'] = $total_buy_num;
        $zonglist['benyue_youhui_num'] = $benyue_youhui_num;
        $zonglist['total_youhui_num'] = $total_youhui_num;
        foreach ($zonglist as $keys => $value) {
            $zonglist[$keys] = round($value, 2);
        }
        return $zonglist;
    }

    /**
     * @return  根据典藏收益模式查找对应的月份比例
     * @param   int $stypes 收益类型
     * @param   int $capitalid 典藏id
     * @return  string
     * @Author 张晓龙 2017/9/2 星期六
     */
    private function build_yuebili_html($stypes, $capitalid = 0) {
        $bili_model = Model('yuebili');
        $diancang_model = Model('diancang');
        $where = "stypes = '$stypes'";
        $order_by = 'yuefen DESC';
        $field = '*';
        $yuenili = $bili_model->get_capital_bili_list($field,$where,$order_by);
        if (!empty($capitalid)) {
            foreach ($yuenili AS $key1 => $val1) {
                $seit_ids = $val1['set_id'];
                $wheres = " set_id = '$seit_ids' AND capitalid = '$capitalid'";
                $fields = '*';    
                $goodyue = $diancang_model->select_capital_goodsyue_info($fields,$wheres);
                if (!empty($goodyue)) {
                    $yuenili[$key1]['capitalid'] = $capitalid;
                    $yuenili[$key1]['bili'] = $goodyue['bili'];
                } else {
                    $yuenili[$key1]['capitalid'] = '';

                }
            }
        }
        $html = '<table width="100%" id="attrTable">';
        $spec = 0;
        foreach ($yuenili AS $key => $val) {
            if (!empty($val['goods_centag'])) {
                $goods_centag = $val['goods_centag'];
            } else {
                $goods_centag = '';
            }
            $html .= '<tr><td class="label" >多选</td>';
            $html .= '<td>';
            if (!empty($val['capitalid'])) {
                $html .= '<input type="checkbox" id="biliid" name="biliid[]" value="' . $val['set_id'] . '" checked="checked"  />';
            } else {
                $html .= '<input type="checkbox" id="biliid" name="biliid[]" value="' . $val['set_id'] . '"  />';
            }
            $html .= $val['yuefen'] . '月';
            $html .= '<input type="text" id="bili_into" name="bili_into[' . $val['set_id'] . ']" placeholder="勾选以后需要添加具体描述" value="' . $val['bili'] . '" size="20" />';
            $html .= '比例添加</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * @return  根据关键词获取产品列表选择产品
     * @return  array
     */
    private function get_goods_list($filter) {
        $keyword = isset($filter) && trim($filter) != '' ? trim($filter) : '';
        if (!empty($keyword)) {
            $where = "  (goods_sn like '%" . $keyword . "%' OR goods_name like '%" . $keyword . "%') AND goods_commonid = 0 AND goods_verify = 1 AND is_real = 1 AND is_alone_sale = 1 AND goods_number >=1 ";
        } else {
            $where = " goods_commonid = 0 AND goods_verify = 1 AND is_real = 1 AND is_alone_sale = 1 AND goods_number >=1 ";
        }
        $order = ' last_update desc,add_time desc,sort_order asc ';
        $field = 'goods_id,goods_sn,goods_name';
        $row = Model('goods')->get_goods_list($field,$where,$order);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * @return  添加典藏产品，选中商品时根据产品id获取产品价格信息
     * @return  array
     */
    private function get_goods_info($filter) {
        $goods_id = isset($filter) && trim($filter) != '' ? trim($filter) : '';
        if (!empty($goods_id)) {
            $where = " goods_id = '$goods_id' AND goods_commonid = 0 AND goods_verify = 1 AND is_real = 1 AND is_alone_sale = 1 AND goods_number >=1 ";
        } else {
            $where = "  goods_commonid = 0 AND goods_verify = 1 AND is_real = 1 AND is_alone_sale = 1 AND goods_number >=1 ";
        }
        $order = ' last_update desc,add_time desc,sort_order asc ';
        $field = 'shop_price,promote_price,goods_fenxiao_price,goods_name';
        $row = Model('goods')->select_goods_info($field,$where,$order);
        $goodsinto = '';
        if (!empty($row)) {
            if ($row['goods_fenxiao_price'] > 0 && $row['goods_fenxiao_price'] < $row['shop_price'] && $row['goods_fenxiao_price'] < $row['promote_price']) {
                $goodsinto['goodpic'] = $row['goods_fenxiao_price'];
            } else {
                $goodsinto['goodpic'] = $row['shop_price'];
            }
        } else {
            $goodsinto['goodpic'] = '';
        }
        $goodsinto['goods_name'] = $row['goods_name'];
        return $goodsinto;
    }

    /**
     * @return  获取典藏分类列表
     * @return  array
     */
    private function get_dcclass_style() {
        $stylelist = array();
        $row = Model('diancang_class')->get_capital_class_list('*','1');
        foreach ($row as $k => $v) {
            $stylelist[$v['dcclass_id']] = htmlspecialchars($v['class_name']);
        }
        return array('stylelitst' => $stylelist);
    }

    /**
     * @return 获取对应id的典藏产品信息
     * @return array
     */
    private function get_dcgood_info($capitalid) {
        $model = Model('diancang');
        $field = 'capital_goods.*,goods.goods_sn,goods.goods_name,goods.shop_price,goods.promote_price,goods.goods_fenxiao_price,capital_class.class_name';
        $where = "capital_goods.capitalid = '$capitalid'";
        $row = $model->get_capital_goods_goods_class_info($field,$where);
        if (!empty($row)) {
            $dc_ids = $row['capitalid'];
            if ($row['goods_fenxiao_price'] > 0 && $row['goods_fenxiao_price'] < $row['shop_price'] && $row['goods_fenxiao_price'] < $row['promote_price']) {
                $row['pic_pic'] = $row['goods_fenxiao_price'];
            } else {
                $row['pic_pic'] = $row['shop_price'];
            }
            $row1 = $model->select_capital_goodsyue_info("*","capitalid = '$dc_ids'");
            $row['yuebilis'] = $row1;
        } else {
            $row['pic_pic'] = '';
        }
        $row['typesd'] = 1;
        return $row;
    }

    /**
     * @return 获取典藏产品和标签对应列表
     * @return array
     */
    private function get_goodtag_list() {
        $capitalid = trim($_REQUEST['capitalid']);
        $result = get_filter();
        $model = Model('diancang_tagods');
        if ($result === false){
            $filter = array();
            $filter['capitalid'] = $capitalid;
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'taggood_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);            
            $filter['record_count'] = Model('diancang_tagods')->get_capital_tagods_count("capitalid = ".$filter['capitalid']);
            $filter = page_and_size($filter);
            $sql =  "SELECT ctg.*,cg.dc_names,ct.tags_name FROM " . Model()->tablename('capital_tagods') . " AS ctg " .
            " LEFT JOIN " . Model()->tablename('capital_tags') . " AS ct ON ctg.tags_id = ct.tags_id " .
            " LEFT JOIN " . Model()->tablename('capital_goods') . " AS cg ON ctg.capitalid = cg.capitalid " .
            " WHERE ctg.capitalid =  " .$filter['capitalid'].
            " ORDER by $filter[sort_by] $filter[sort_order]";
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
            $filter['capitalid'] = $capitalid;
        }
        $row = get_all_page($sql, $filter['page_size'], $filter['start']);
        $arr = array('dcgtags_list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * @return 根据获取全部标签可让用户多选
     * @param  int $capitalid 典藏产品id
     * @return string
     * @Author 张晓龙 2017/9/2 星期六
     */
    private function build_goodtag_html($capitalid = 0) {
        $model = Model('diancang_tagods');
        $tag_list = $model->get_capital_tags_list('*','1','tags_id DESC');
        if ($capitalid) {
            foreach ($tag_list AS $key => $val) {
                $tags_id = $val['tags_id'];
                $goodtags = $model->select_capital_tagods_info('*',"tags_id = '$tags_id' AND capitalid = '$capitalid'");
                if (!empty($goodtags)) {
                    $tag_list[$key]['capitalid'] = $capitalid;
                } else {
                    $tag_list[$key]['capitalid'] = '';

                }
            }
        }
        $html = '<table width="100%" id="attrTable">';
        $html .= '<tr><td class="label" >可多选</td>';
        $html .= '<td>';
        foreach ($tag_list AS $key1 => $val2) {
            if (!empty($val2['capitalid'])) {
                $html .= '<input type="checkbox" id="goodstag" name="goodstag[]" value="' . $val2['tags_id'] . '" checked="checked"  />';
            } else {
                $html .= '<input type="checkbox" id="goodstag" name="goodstag[]" value="' . $val2['tags_id'] . '"  />';
            }
            $html .= $val2['tags_name'];
        }
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }
}
?>

