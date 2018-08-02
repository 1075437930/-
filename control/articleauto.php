<?php

/**
 * 淘玉php 后台文章自动发布程序
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台文章自动发布程序
 * $Id: articleauto.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class articleautoControl extends BaseControl {
    
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('article_auto,calendar');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 文章自动发布列表函数 
     */
    public function lists() {
        /* 权限的判断 */
        admin_priv('article_auto');
        $goodsdb = $this->get_autogoods();
     
        //检查计划任务是否开启 在表crons里面操作
        //$crons_enable = $db->getOne("SELECT enable FROM " . $GLOBALS['ecs']->table('crons') . " WHERE cron_code='ipdel'");
        Tpl::assign('crons_enable', 1);
        Tpl::assign('full_page',    1);
        Tpl::assign('ur_here',      L('article_auto'));
        Tpl::assign('goodsdb',      $goodsdb['goodsdb']);
        Tpl::assign('filter',       $goodsdb['filter']);
        Tpl::assign('record_count', $goodsdb['record_count']);
        Tpl::assign('page_count',   $goodsdb['page_count']);
        Tpl::assign('form_act', 'articleauto');
        Tpl::display('article_auto.htm');
    }
    
    /**
     * @return 文章自动发布列表排序、分页、查询
     */
    public function article_auto_query() {
        $goodsdb = $this->get_autogoods();
        Tpl::assign('crons_enable', 1);
        Tpl::assign('goodsdb',    $goodsdb['goodsdb']);
        Tpl::assign('filter',     $goodsdb['filter']);
        Tpl::assign('record_count',    $goodsdb['record_count']);
        Tpl::assign('page_count',      $goodsdb['page_count']);
        $sort_flag  = sort_flag($goodsdb['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('article_auto.htm'), '',
            array('filter' => $goodsdb['filter'], 'page_count' => $goodsdb['page_count']));
    }
    
    /**
     * @return 自动开始时间发布编辑 Description
     */
    public function edit_starttime(){
        check_authz_json('article_auto');
        if(! preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($_POST['val'])) ){
            make_json_error(L('select_time_error'));
        }
        $id    = intval($_POST['id']);
        $time = local_strtotime(trim($_POST['val']));
        if($id <= 0 || $_POST['val'] == '0000-00-00' || $time <= 0){
            make_json_error(L('select_time'));
        }
        $automodel =  Model('automanage');
        $pram['item_id'] = $id;
        $pram['type'] =  'article';
        $pram['endtime'] =  0;
        $result = $automodel->select_auto_manage_info("manage_id",$pram);
        if(!empty($result)){
            $pram1['starttime'] =  $time;
            $where['manage_id'] = $result['manage_id'];
            $automodel->update_auto_manage($pram1,$where);
        }else{
            $pram['starttime'] =  $time;
            $automodel->insert_auto_manage($pram);
        }
        
        make_json_result(stripslashes($_POST['val']), '', array('act' => 'articleauto', 'id' => $id));
    }
 
    
    /**
     * @return 删除对应的自动发布单个信息 Description
     */
    public function deleteck(){
        check_authz_json('article_auto');
        $goods_id = (int)$_REQUEST['goods_id'];
        $where['item_id'] = $goods_id;
        $where['type'] = 'article';
        Model('automanage')->delete_auto_manage($where);
        $links[] = array('text' => L('article_auto'), 'href' => 'index.php?act=articleauto&op=lists');
        showMessage(L('edit_ok'),$links);
    }
    
    /**
     * @return 批量操作自动发布添加时间 Description
     */
    public function batch_start(){
        admin_priv('article_auto');
        if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes'])){
            showMessage(L('no_select_goods'));
        }
        if($_POST['date'] == '0000-00-00'){
            $_POST['date'] = 0;
        }else{
             $_POST['date'] = local_strtotime(trim($_POST['date']));
        }
        $automodel = Model('automanage');
        foreach($_POST['checkboxes'] as $id){
            $pram['item_id'] = $id;
            $pram['type'] =  'article';
            $pram['endtime'] =  0;
            $result = $automodel->select_auto_manage_info("manage_id",$pram);
            if(!empty($result)){
                $pram1['starttime'] =  $_POST['date'];
                $where['manage_id'] = $result['manage_id'];
                $automodel->update_auto_manage($pram1,$where);
            }else{
                $pram['starttime'] =  $_POST['date'];
                $automodel->insert_auto_manage($pram);
            }
        }
        $lnk[] = array('text' => L('back_list'), 'href' => 'index.php?act=articleauto&op=lists');
        showMessage(L('batch_start_succeed'),$lnk);
    }


    /**
     * @return 获取文章自动发布列表
     */
    private function get_autogoods(){
        $result = get_filter();
        if ($result === false){
            $filter = array();
            $articlemodel = Model('article');
            $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'article.article_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $where = ' WHERE article.is_open = 0 ';
            if (!empty($filter['keyword'])){
                $where .= " AND article.title LIKE '%" . $filter['keyword'] . "%'";
            }
            /* 文章总数 */
            $filter['record_count'] = $articlemodel->get_article_count($where);
            $filter = page_and_size($filter);
            
            /* 获取文章数据 */
             $sql = "SELECT article.*,auto_manage.starttime,auto_manage.endtime,article_cat.cat_name"
                     . " FROM " . Model()->tablename('article') . " AS article "
                    ." LEFT JOIN " . Model()->tablename('article_cat'). " AS article_cat ON article.cat_id = article_cat.cat_id "
                    ." LEFT JOIN " . Model()->tablename('auto_manage'). " AS auto_manage ON article.article_id = auto_manage.item_id  AND auto_manage.type='article' "
                    . $where ." ORDER BY ".$filter['sort_by']." ".$filter['sort_order'];
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($res as $key => $value) {
            if (!empty($value['starttime'])){
                $res[$key]['starttime'] = local_date('Y-m-d',$value['starttime']);
            }
            if (!empty($value['endtime'])){
                $res[$key]['endtime'] = local_date('Y-m-d',$value['endtime']);
            }
            $res[$key]['goods_id'] = $value['article_id'];
            $res[$key]['goods_name'] = $value['title'];
            $res[$key]['goods_sn'] = $value['cat_name'];
            if(!empty($value['img_url'])){
                $img_urls = explode(',',$value['img_url']);
                if(count($img_urls)>0){
                    foreach ($img_urls as $value1) {
                        $res[$key]['img_urls'] = get_imgurl_oss($value1, 50, 50);
                        break;
                    }
                }else{
                    $res[$key]['img_urls'] = get_imgurl_oss($value['img_url'], 50, 50);
                }
            } else {
                $res[$key]['img_urls'] = 'https://www.taoyumall.com/1.jpg';
            }
        }
        
        $arr = array('goodsdb' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }
}
?>

