<?php

/**
 * 分销管理功能
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 萤火虫 $
 * $Id: fenxiao.php  2018-04-07   萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class fenxiaoControl extends BaseControl{
    public function __construct() {
        parent::__construct();
        Language::read('fxusers,calendar');
        $lang = Language::getLangContent();
        Tpl::assign('lang', $lang);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 分销返利列表
     */
    public function lists(){
        admin_priv('fx_fandian');
        if (!isset($_REQUEST['start_date'])) {
            $_REQUEST['start_date'] = get_menyone();
        }
        if (!isset($_REQUEST['end_date'])) {
            $_REQUEST['end_date'] = get_menylast();
        }
        /*列表信息*/
        $goods_order_data = $this->get_fenxiao_list();
        /*统计信息*/
        $counts = $this->get_fenxiao_list(false);
        /* 赋值到模板 */
        Tpl::assign('ur_here', L('fx_fan'));
        Tpl::assign('goods_order_data', $goods_order_data['fenxiao_data']);
        Tpl::assign('goods_order_count', $counts['set']);
        Tpl::assign('time_rand', $counts['time_rand']);
        Tpl::assign('filter',           $goods_order_data['filter']);
        Tpl::assign('record_count',     $goods_order_data['record_count']);
        Tpl::assign('page_count',       $goods_order_data['page_count']);
        Tpl::assign('filter',           $goods_order_data['filter']);
        Tpl::assign('full_page',        1);
        Tpl::assign('sort_goods_num',   '<img src="images/sort_desc.gif">');
        Tpl::assign('start_date',       $_REQUEST['start_date']);
        Tpl::assign('end_date',         $_REQUEST['end_date']);
        Tpl::assign('action_link',      array('text' => L('download_sale_sort'), 'href' => 'index.php?act=fenxiao&op=download' ));
        Tpl::display('fenxiao_list.htm');
    }

    /**
     * @return 分销返利列表排序、分页、查询
     */
    public function query(){
        admin_priv('fx_fandian');
        if (!isset($_REQUEST['start_date'])) {
            $_REQUEST['start_date'] = get_menyone();
        }
        if (!isset($_REQUEST['end_date'])) {
            $_REQUEST['end_date'] = get_menylast();
        }
        /*列表信息*/
        $goods_order_data = $this->get_fenxiao_list();
        /*统计信息*/
        $counts = $this->get_fenxiao_list(false);
        Tpl::assign('action_link', array('text' => L('download_sale_sort'), 'href' => 'index.php?act=fenxiao&op=download' ));
        Tpl::assign('goods_order_data', $goods_order_data['fenxiao_data']);
        Tpl::assign('goods_order_count', $counts['set']);
        Tpl::assign('filter',       $goods_order_data['filter']);
        Tpl::assign('record_count', $goods_order_data['record_count']);
        Tpl::assign('page_count',   $goods_order_data['page_count']);
        $sort_flag  = sort_flag($goods_order_data['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('fenxiao_list.htm'), '', array('filter' => $goods_order_data['filter'], 'page_count' => $goods_order_data['page_count']));
    }

    /**
     * @return 邀请人分销返点列表
     */
    public function info(){
        admin_priv('fx_fandian');
        if (empty($_REQUEST['start_date'])) {
            $_REQUEST['start_date'] = get_menyone();
        }
        if (empty($_REQUEST['end_date'])) {
            $_REQUEST['end_date'] = get_menylast();
        }
        $fan_bill = C('yqfanli');
        $goods_order_data = $this->get_fenxiao_info_list($fan_bill);
        /* 赋值到模板 */
        Tpl::assign('ur_here',          L('fx_fan'));
        Tpl::assign('goods_order_data', $goods_order_data['fenxiao_data']);
        Tpl::assign('userinto', $goods_order_data['set']);
        Tpl::assign('filter',           $goods_order_data['filter']);
        Tpl::assign('record_count',     $goods_order_data['record_count']);
        Tpl::assign('page_count',       $goods_order_data['page_count']);
        Tpl::assign('filter',           $goods_order_data['filter']);
        Tpl::assign('full_page',        1);
        Tpl::assign('sort_goods_num',   '<img src="templates/default/images/sort_desc.gif">');
        Tpl::assign('start_date',       local_date('Y-m-d', $_REQUEST['start_date']));
        Tpl::assign('end_date',         local_date('Y-m-d', $_REQUEST['end_date']));
        Tpl::assign('action_link',      array('text' => L('download_sale_sort'), 'href' => '#download' ));
        /* 显示页面 */
        Tpl::display('fenxiao_into.htm');
    }

    /**
     * @return 邀请人分销返利列表排序、分页、查询
     */
    public function info_query(){
        admin_priv('fx_fandian');
        $fan_bill = C('yqfanli');        
        $goods_order_data = $this->get_fenxiao_info_list($fan_bill);
        Tpl::assign('goods_order_data', $goods_order_data['fenxiao_data']);
        Tpl::assign('userinto', $goods_order_data['set']);
        Tpl::assign('filter',       $goods_order_data['filter']);
        Tpl::assign('record_count', $goods_order_data['record_count']);
        Tpl::assign('page_count',   $goods_order_data['page_count']);
        $sort_flag  = sort_flag($goods_order_data['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('fenxiao_into.htm'), '', array('filter' => $goods_order_data['filter'], 'page_count' => $goods_order_data['page_count']));
    }

    /**
     * @return app邀请返利设置页面
     */
    public function yqfanli_edit(){
        admin_priv('fx_logo');
        $where['id'] = 1070;
        $obj =  Model('shop_config');
        $res = $obj->select_shop_config_info('*',$where);
        Tpl::assign('ur_here', L('yq_fanli'));
        Tpl::assign('result',$res);
        Tpl::display('yqfanli.htm');
    }

    /**
     * @return app邀请返利设置数据入库
     */
    public function yqfanli_update(){
        admin_priv('fx_logo');
        $id = $_REQUEST['id'];
        $yqfanli = $_REQUEST['ypfanli'];
        $link = array('href'=>'index.php?act=fenxiao&op=yqfanli_edit', 'text' => L('back_fenxiao'));
        if(empty($id)){
            showMessage(L('cannot_found_param'),$link);
        }
        if(empty($yqfanli)){
            showMessage(L('yqfanli_cannot_empty'),$link);
        }
        $update['value'] = $yqfanli;
        $where['id'] = $id;
        $res = Model('shop_config')->update_shop_config($update,$where);
        if($res){
            showMessage(L('edit_success'),$link);
        }else{
            showMessage(L('edit_fail'),$link);
        }
    }

    /**
     * @return 批量操作购买返点
     */
    public function fandiancz(){
        admin_priv('fx_fandian_into');
        if (!isset($_REQUEST['start_date'])) {
            $_REQUEST['start_date'] = get_menyone();
        }
        if (!isset($_REQUEST['end_date'])) {
            $_REQUEST['end_date'] = get_menylast();
        }
        $link = array('href'=>'index.php?act=fenxiao&op=lists', 'text' => L('back_fenxiao'));
        $userid = $_REQUEST['user_id'];
        $goods_order_data = $this->batch_updata_fenxiao($userid);
        Tpl::assign('filter',$goods_order_data['filter']);
        if($goods_order_data['types']){
            showMessage($goods_order_data['msgs'],  $link);
        }else{
            showMessage($goods_order_data['msgs'],  $link);
        }
    }

    /**
     * @return 批量操作邀请返点
     */
    public function yqfandiancz(){
        admin_priv('fx_fandian_into');
        $fan_bill = C('yqfanli');
        if (!isset($_REQUEST['start_date'])) {
            $_REQUEST['start_date'] = get_menyone();
        }
        if (!isset($_REQUEST['end_date'])) {
            $_REQUEST['end_date'] = get_menylast();
        }
        $link = array('href'=>'index.php?act=fenxiao&op=lists', 'text' => L('back_fenxiao'));
        $userid = $_REQUEST['user_id'];
        $goods_order_data = $this->batch_updata_yqfenxiao($userid);
        Tpl::assign('filter',$goods_order_data['filter']);
        if($goods_order_data['types']){
            showMessage($goods_order_data['msgs'],$link);
        }else{
            showMessage($goods_order_data['msgs'], $link);
        }
    }

    /**
     * @return 单个操作购买返点
     */
    public function editpay(){
        /* 检查权限 */
        admin_priv('fx_fandian_into');
        if (!isset($_REQUEST['start_date'])){
            $_REQUEST['start_date'] = get_menyone();
        }
        if (!isset($_REQUEST['end_date'])){
            $_REQUEST['end_date'] = get_menylast();
        }
        $userid = $_REQUEST['userid'];
        $sts = $_REQUEST['start_date'];
        $ede = $_REQUEST['end_date'];
        $links = array('text' => '返回列表', 'href' => "index.php?act=fenxiao&op=info&user_id=$userid&start_date=$sts&end_date=$ede");
        if (!empty($_REQUEST['order_id']) && isset($_REQUEST['add'])){
            $onefenxiao = $this->updata_fenxiao($_REQUEST['order_id'],$_REQUEST['add']);            
            if($onefenxiao['types']){
                showMessage($onefenxiao['msgs'],$links);
            }else{
                showMessage($onefenxiao['msgs'],$links);
            }
        }else{
            showMessage('参数错误无法返点',$links);
        }
        
    }

    /**
     * @return 单个操作邀请返点
     */
    public function edityq(){
        /* 检查权限 */
        admin_priv('fx_fandian_into');
        $fan_bill = C('yqfanli');
        if (!isset($_REQUEST['start_date'])) {
            $_REQUEST['start_date'] = get_menyone();
        }
        if (!isset($_REQUEST['end_date'])) {
            $_REQUEST['end_date'] = get_menylast();
        }
        $sts = $_REQUEST['start_date'];
        $userid = $_REQUEST['userid'];
        $ede = $_REQUEST['end_date'];
        $links = array('text' => '返回列表', 'href' => "index.php?act=fenxiao&op=info&user_id=$userid&start_date=$sts&end_date=$ede");
        if (!empty($_REQUEST['order_id']) && isset($_REQUEST['add'])) {
            $onefenxiao = $this->updata_yqfenxiao($_REQUEST['order_id'], $_REQUEST['add']);
            if ($onefenxiao['types']) {
                showMessage($onefenxiao['msgs'],$links);
            } else {
                showMessage($onefenxiao['msgs'],$links);
            }
        } else {
            showMessage('参数错误无法返点', $links);
        }
    }

    /**
     * @return 分销返点报表下载
     */
    public function download(){
        admin_priv('fx_fandian');
        if (!isset($_REQUEST['start_date'])) {
            $_REQUEST['start_date'] = get_menyone();
        }
        if (!isset($_REQUEST['end_date'])) {
            $_REQUEST['end_date'] = get_menylast();
        }
        /*列表信息*/
        $goods_order_data = $this->get_fenxiao_list();
        /*导出到表格*/
        $title = array(
            '购买用户',
            '有效订单总金额',
            '总订单数量',
            '总购买返点',
            '邀请人',
            '总邀请人返点',
            '已经返点（购买）',
            '已经返点（邀请）',
        );
        /*格式化数据*/
        $tmp = array();
        foreach ($goods_order_data['fenxiao_data'] as $key => $value) {
            $tmp[$key]['alias'] = $value['alias'];
            $tmp[$key]['zongpic'] = $value['zongpic'];
            $tmp[$key]['nums'] = $value['nums'];
            $tmp[$key]['fanzong'] = $value['fanzong'];
            $tmp[$key]['parentname'] = $value['parentname2'] ? $value['parentname2'] : $value['parentname'];
            $tmp[$key]['poserfan'] = $value['poserfan'];
            $tmp[$key]['yjfan_dian'] = $value['yjfan_dian'];
            $tmp[$key]['yjyqfan_dian'] = $value['yjyqfan_dian'];
        }
        /*导出数据*/
        exportExcel($tmp,$title,'区域分布');    
    }

      
    /**
     * @return  取得分销返点列表及数据统计
     * @param   bool  $flag  是否分页,默认分页，false不分页
     * @return  array 分销返点数据arr
     */
    private function get_fenxiao_list($flag = true){
        $result = get_filter();
        if ($result === false){
            if(!empty($_REQUEST['start_date'])){
                $filter['start_date'] = $_REQUEST['start_date'];
            }else{
                $filter['start_date'] = get_menyone();
            }
            if(isset($_REQUEST['userming'])){
                $filter['userming'] = $_REQUEST['userming'];
            }else{
                $filter['userming'] = '';
            }
            if(!empty($_REQUEST['end_date'])){
                $filter['end_date'] = $_REQUEST['end_date'];
            }else{
                $filter['end_date'] = get_menylast();
            }
            $where = "order_status IN ('0','1','5') AND pay_status = 2 ";
            if ($filter['start_date']) {   
                $t = $filter['start_date'];
                $where .= " AND add_time >= '" . $t . "'";
            }
            if ($filter['end_date']){
                $ti = $filter['end_date'];
                $where .= " AND add_time <= '" . $ti . "'";
            }
            if($filter['userming']){
                $where .= " AND buyer_name = '" . $filter['userming'] . "'";
            }
            $total = Model('order')->get_order_info_list('user_id',$where,'','','user_id');                      
            $filter['record_count'] = count($total);
            /* 分页大小 */
            $filter = page_and_size($filter);            
            $biaozidun = " COUNT(*) as nums,sum(goods_amount) as zongpic,user_id,sum(fanli_pic) as fanzong,sum(fandian_yq_pic) as yqzong,parent_id,add_time";
            $sql = "SELECT " .$biaozidun.
            " FROM ".Model()->tablename('order_info').' WHERE '.$where ." group by user_id ";
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }               
        if(!$flag){
            $biaozidun = " COUNT(*) as nums,sum(goods_amount) as zongpic,user_id,sum(fanli_pic) as fanzong,sum(fandian_yq_pic) as yqzong,parent_id,add_time ";
            $fenxiao_data = Model('order')->get_order_info_list($biaozidun,$where,'',0,'user_id');
        }else{
            $fenxiao_data = get_all_page($sql, $filter['page_size'], $filter['start']);
        }
        /*初始化各统计值*/           
        $yifanyao = 0;
        $yifanmai = 0;
        $inviteprice = 0;
        $invitenum = 0;
        $zongpics = 0;
        if(!empty($fenxiao_data)){
            foreach ($fenxiao_data as $key => $item) {
                $parentid = $item['parent_id'];
                $userids = $item['user_id'];
                $user_into = Model('users')->get_users_user_level_info('users.user_name,users.alias,users.real_name,users.fxalias,user_level.level_name,user_level.level_bili,user_level.remark',"user_id = $userids");
                $zongpic = intval($item['zongpic']);
                $zongpics += $zongpic;
                $fenxiao_data[$key]['username'] = $user_into['user_name'];
                $fenxiao_data[$key]['add_time'] = local_date(C('time_format'), $item['add_time']);
                if(!empty($user_into['real_name'])){
                    $fenxiao_data[$key]['alias'] = $user_into['real_name'];
                }else{
                    if(!empty($user_into['fxalias'])){
                        $fenxiao_data[$key]['alias'] = $user_into['fxalias'];
                    }else{
                        if(!empty($user_into['alias'])){
                            $fenxiao_data[$key]['alias'] = $user_into['alias'];
                        }else{
                            $fenxiao_data[$key]['alias'] = "无昵称";
                        }
                    }
                }
                /*购买返点*/
                $fenxiao_data[$key]['level_name'] = $user_into['level_name'];         
                $sqlyj = "SELECT yf.nums,yf.zongpic,yf.user_id,yf.fanzong FROM ( SELECT COUNT(*) as nums,sum(goods_amount) as zongpic,user_id,sum(fanli_pic) as fanzong,parent_id FROM ".Model()->tablename('order_info').' where '.$where ." AND fandian_pay = 1 group by user_id) yf WHERE yf.user_id = '$userids' ";
                $fenxiao_yj = Model('order')->get_return_point($sqlyj);
                if($zongpic >= $user_into['remark']){
                    $fandianzsd = intval($item['fanzong']);
                    if($fandianzsd != 0){
                        $sumprice += $fandianzsd;
                    }
                }else{
                    $fandianzsd = 0;
                    $fenxiao_data[$key]['fanzong'] = 0;
                }
                $yijfdians = intval($fenxiao_yj['fanzong']);
                if(!empty($yijfdians)){
                    $fenxiao_data[$key]['yjfan_dian'] = $yijfdians;
                }else{
                    $fenxiao_data[$key]['yjfan_dian'] = 0;
                }
                if(!empty($parentid)){
                    /*邀请人返点*/               
                    $sqlyj2 = "SELECT yf.nums,yf.zongpic,yf.user_id,yf.fanyqzong FROM ( SELECT COUNT(*) as nums,sum(goods_amount) as zongpic,user_id,sum(fandian_yq_pic) as fanyqzong,parent_id FROM ".Model()->tablename('order_info').' where '.$where ." AND fandian_yq = 1 group by user_id) yf WHERE yf.user_id = '$userids' ";
                    $fenxiao_yqrj = Model('order')->get_return_point($sqlyj2);
                    $parent_into = Model('users')->select_users_info('*','user_id='.$parentid);                
                    $fenxiao_data[$key]['parentname']   = $parent_into['user_name'];
                    if(!empty($parent_into['real_name'])){
                        $fenxiao_data[$key]['parentname2'] = $parent_into['real_name'];
                    }else{
                        if(!empty($parent_into['fxalias'])){
                            $fenxiao_data[$key]['parentname2'] = $parent_into['fxalias'];
                        }else{
                            if(!empty($parent_into['alias'])){
                                $fenxiao_data[$key]['parentname2'] = $parent_into['alias'];
                            }else{
                                $fenxiao_data[$key]['parentname2'] = "无昵称";
                            }
                        }
                    }
                    $fenxiao_data[$key]['poserfan'] = intval($item['yqzong']);
                    $yqryfds = intval($fenxiao_yqrj['fanyqzong']);
                    if(!empty($yqryfds)){
                        $fenxiao_data[$key]['yjyqfan_dian'] = $yqryfds;
                    }else{
                        $fenxiao_data[$key]['yjyqfan_dian'] = 0;
                    }
                    $inviteprice += $fenxiao_data[$key]['poserfan'];
                    $yifanyao += $fenxiao_data[$key]['yjyqfan_dian'];
                }else{
                    $fenxiao_data[$key]['parentname']   = '无邀请人';
                    $fenxiao_data[$key]['poserfan']     = '0';
                    $fenxiao_data[$key]['yjyqfan_dian'] = '0';
                    $fenxiao_data[$key]['parentname2']  = "无";
                }
                $fenxiao_data[$key]['yjyqfan_nums'] = $fenxiao_yqrj['nums'];
                $yifanmai += $yijfdians;
                $zongfans = intval($fenxiao_data[$key]['fanzong']);
                $yifendians = intval($fenxiao_data[$key]['yjfan_dian']);
                $yqfndiansd = intval($fenxiao_data[$key]['yjyqfan_dian']);
                $yqfdsd = intval($fenxiao_data[$key]['poserfan']);
                if(!empty($zongfans)){
                    if($yifendians == $zongfans){
                        $fenxiao_data[$key]['yjtypewan'] = 1;
                    }else{
                        $fenxiao_data[$key]['yjtypewan'] = 0;
                    }
                }else{
                    $fenxiao_data[$key]['yjtypewan'] = 2;
                }
                if(!empty($yqfdsd)){
                    if($yqfndiansd == $yqfdsd){
                        $fenxiao_data[$key]['yqtypewan'] = 1;
                    }else{
                        $fenxiao_data[$key]['yqtypewan']     = 0;
                    }
                }else{
                    $fenxiao_data[$key]['yqtypewan']     = 2;
                }
            }
            $set['sumprice'] = $sumprice;
            $set['inviteprice'] = $inviteprice;
            $set['yifanmai'] = $yifanmai;
            $set['yifanyao'] = $yifanyao;
            $set['zongpicss'] = $zongpics;
            $time_rand['start_date'] = local_date('Y-m-d', get_menyone());
            $time_rand['end_date'] = local_date('Y-m-d', get_menylast());
            $arr = array('fenxiao_data' => $fenxiao_data, 'set' => $set,'time_rand'=>$time_rand ,'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
            return $arr;    
        }        
    }

    /**
     * @return  取得邀请人分销返点列表及数据统计
     * @param   bool  $is_pagination  是否分页
     * @return  array 分销返点数据arr
     */
    private function get_fenxiao_info_list($fan_bill){
        $result = get_filter();
        if ($result === false){
            if(!empty($_REQUEST['start_date'])){
                $filter['start_date'] = $_REQUEST['start_date'];
            }else{
                $filter['start_date'] = get_menyone();
            }
            if(!empty($_REQUEST['end_date'])){
                $filter['end_date'] = $_REQUEST['end_date'];
            }else{
                $filter['end_date'] = get_menylast();
            }
            $orderbys = " ORDER BY fandian_pay asc ";
            $userid = $_REQUEST['user_id'];
            $filter['user_id'] = $userid;
            $where = "  order_info.order_status IN ('0','1','5') AND order_info.pay_status = 2  AND order_info.user_id = '$userid' ";
            if ($filter['start_date']) {
                $where .= " AND order_info.add_time >= '" . $filter['start_date'] . "'";
            }
            if ($filter['end_date']) {
                $where .= " AND order_info.add_time <= '" . $filter['end_date'] . "'";
            }
            $resu = Model('order')->get_order_goods_order_info_list('distinct(order_goods.goods_id)',$where);
            $filter['record_count'] = count($resu);
            /* 分页大小 */
            $filter = page_and_size($filter);
            $sql = "SELECT order_goods.goods_id, order_goods.goods_sn, order_goods.goods_number, 
            order_goods.goods_fenxiao_price,order_goods.goods_image,order_goods.goods_name,
            order_goods.goods_pay_price,order_info.order_status,order_info.order_sn, 
            order_info.shipping_status,order_info.order_sn, order_info.order_id, order_info.user_id, 
            order_info.buyer_name, order_info.pay_status, order_info.parent_id, order_info.money_paid, 
            order_info.fandian_pay, order_info.goods_amount, order_info.add_time, order_info.pay_time, 
            order_info.confirm_time,order_info.fandian_yq, order_info.fanli_pic, order_info.fandian_yq_pic " .
            " FROM ".Model()->tablename('order_goods')." AS order_goods left join" .
            Model()->tablename('order_info')." AS order_info  on order_info.order_id=order_goods.order_id WHERE " .
            $where .$orderbys;
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        
        $fenxiao_data = get_all_page($sql, $filter['page_size'], $filter['start']);
        $user_into = Model('users')->get_users_user_level_info('users.user_name,users.alias,users.real_name,users.fxalias,user_level.level_name,user_level.level_bili,user_level.remark',"user_id = $userid");
        $user['username'] = $user_into['user_name'];
        $user['level_name'] = $user_into['level_name'];
        if(!empty($user_into['real_name'])){
            $user['alias'] = $user_into['real_name'];
        }else{
            if(!empty($user_into['fxalias'])){
                $user['alias'] = $user_into['fxalias'];
            }else{
                if(!empty($user_into['alias'])){
                    $user['alias'] = $user_into['alias'];
                }else{
                    $user['alias'] = "无昵称";
                }
            }
        }
        $baifen = $user_into['level_bili']*100;
        $user['youhuio'] = '此用户'.$user_into['user_name'].'享受每月购买返点：'.$baifen.'%.';
        $starts = $filter['start_date'];
        $ends = $filter['end_date'];
        $where = "user_id = '$userid' AND add_time >= $starts AND add_time <= $ends AND order_status IN ('0','1','5') AND pay_status = 2 ";
        $fields = 'sum(money_paid) as zongpic';
        $zongpic = Model('order')->get_order_info_list($fields,$where)[0]['zongpic'];
        $user['zongpic'] = $zongpic;
        $zongfandian = 0;
        $zongyqdian = 0;
        $geshus = 1;
        foreach ($fenxiao_data as $key => $item){
            $parentid = $item['parent_id'];
            $fenxiao_data[$key]['add_time'] = local_date('Y-m-d H:i:s', $item['add_time']);
            $fenxiao_data[$key]['pay_time']  = local_date('Y-m-d H:i:s', $item['pay_time']);
            $fenxiao_data[$key]['confirm_time']    = local_date('Y-m-d H:i:s', $item['confirm_time']);
            $fenxiao_data[$key]['goods_image']    = get_imgurl_oss($item['goods_image'],100,100);
            $fenxiao_data[$key]['goods_url'] = WEB_PATH.'goods.php?id='.$item['goods_id'];
            $zongfandian += $fenxiao_data[$key]['fanli_pic'];
            $zongyqdian += $fenxiao_data[$key]['fandian_yq_pic'];
            if(!empty($parentid)){
                $parent_into = Model('users')->select_users_info('*','user_id='.$parentid);
                $fenxiao_data[$key]['parentname']   = $parent_into['user_name'];
                if(!empty($parent_into['real_name'])){
                    $fenxiao_data[$key]['parentname2'] = $parent_into['real_name'];
                }else{
                    if(!empty($parent_into['fxalias'])){
                        $fenxiao_data[$key]['parentname2'] = $parent_into['fxalias'];
                    }else{
                        if(!empty($parent_into['alias'])){
                            $fenxiao_data[$key]['parentname2'] = $parent_into['alias'];
                        }else{
                            $fenxiao_data[$key]['parentname2'] = "无昵称";
                        }
                    }
                }
                
            }else{
                $fenxiao_data[$key]['parentname']   = '无邀请人';
                $fenxiao_data[$key]['poserfan']     = '0';
            }
            if($item['shipping_status'] == 0){
                $fenxiao_data[$key]['statustpye']    = '未发货';
                $fenxiao_data[$key]['xiug']    = 0;
            }else if($item['shipping_status'] == 1){
                $fenxiao_data[$key]['statustpye']    = '未签收';
                $fenxiao_data[$key]['xiug']    = 0;
            }else if($item['shipping_status'] == 3){
                $fenxiao_data[$key]['statustpye']    = '未发货';
                $fenxiao_data[$key]['xiug']    = 0;
            }else{
                if($fenxiao_data[$key]['poserfan'] == 0 && $fenxiao_data[$key]['fanli_pic'] == 0){
                    $fenxiao_data[$key]['statustpye']    = '无需返点';
                    $fenxiao_data[$key]['xiug']    = 0;
                }else{
                    $fenxiao_data[$key]['statustpye']    = '可以返利';
                    $fenxiao_data[$key]['xiug']    = 1;
                }
            }
        }
        $user['zongfanpic'] = $zongfandian;
        $user['zongyqfanpic'] = $zongyqdian;
        $arr = array('fenxiao_data' => $fenxiao_data,'set' => $user, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

    /**
     * @return  批量操作购买返点
     * @param   bool  $is_pagination  是否分页
     * @return  array 分销返点数据arr
     */
    private function batch_updata_fenxiao($userids){
        $order_model = Model('order');
        if(!empty($_REQUEST['start_date'])){
            $filter['start_date'] = $_REQUEST['start_date'];
        }else{
            $filter['start_date'] = get_menyone();
        }
        if(!empty($_REQUEST['end_date'])){
            $filter['end_date'] = $_REQUEST['end_date'];
        }else{
            $filter['end_date'] = get_menyone();
        }
        $yues = get_timeyue($filter['start_date']);
        $where = " order_status = 5 AND pay_status = 2 AND shipping_status = 2 AND user_id = '$userids' AND fandian_pay = 0 ";
        if ($filter['start_date'])
        {
            $where .= " AND add_time >= '" . $filter['start_date'] . "'";
        }
        if ($filter['end_date'])
        {
            $where .= " AND add_time <= '" . $filter['end_date'] . "'";
        }        
        $param = 'order_id,buyer_name,parent_id, fandian_pay,money_paid,add_time,pay_time,confirm_time,fanli_pic';
        $fandian_data = $order_model->get_order_info_list($param,$where);
        $yqtype = '';
        $paytype = '';
        if(!empty($fandian_data)){
            $nums = 0;
            $zongnums = count($fandian_data);
            $zongfanli = 0;
            $zongmoney = 0;
            $parent_ids = $fandian_data[0]['parent_id'];
            foreach($fandian_data as $key => $item){
                $orderids = $item['order_id'];
                $update['fandian_pay'] =1;
                $wheres['order_id'] = "$orderids";
                $zahngss = $order_model->update_order_info($update,$wheres);
                if(!empty($zahngss)){
                    $zongfanli = $zongfanli+$item['fanli_pic'];
                    $nums = $nums+1;
                }
            }
            if(!empty($zongfanli)){
                $where1['user_id'] = $userids;
                $field['user_money'] = $zongfanli;
                $useruptpay = Model('users')->update_users_setInc($field,$where1);
                if(!empty($useruptpay)){
                    $usertelpay = Model('users')->select_users_info('*','user_id='.$userids);
                    $mobilespay = $usertelpay['mobile_phone'];
                    $payuser_name = $usertelpay['alias'];
                    $user_moneypay = $usertelpay['user_money'];
                    if(!empty($mobilespay)){
                        $param = array();
                        $param['username'] = $payuser_name;
                        $param['yue'] = $yues;
                        $param['yuan'] = $zongfanli;
                        $param['types'] = 'app购买';
                        $param['zongyuan'] = $user_moneypay;
                        $param['site_name'] = '淘玉商城';                       
                        $result = send_sms_msg($mobilespay,'fandian_mags',$param);
                    }
                    $action_note = $yues.'月，总app购买返点'.$zongfanli.'元，返到用户余额 总余额'.$user_moneypay;
                    /* 插入帐户变动记录 */
                    $account_log = array(
                        'user_id'       => $userids,
                        'user_money'    => $zongfanli,
                        'frozen_money'  => 0,
                        'rank_points'   => 0,
                        'pay_points'    => 0,
                        'change_time'   => gmtime(),
                        'change_desc'   => $action_note,
                        'change_type'   => 9
                    );
                    Model('accountlog')->insert_account_log($account_log);
                    $paytype = true;
                }else{
                    $paytype = false;
                }
            }
            if(!empty($paytype)){
                $arr = array('msgs' => $payuser_name.'购买人返点总'.$zongnums.'个，成功'.$nums.'个', 'types' => true, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
                return $arr;
            }else{
                $arr = array('msgs' => $payuser_name.'购买返点全部添加失败,总'.$zongnums.'个，成功'.$nums.'个', 'types' => false, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
                return $arr;
            }
        }else{
            $arr = array('msgs' => '暂时没有满足返点的订单，可以查看返点详情了解', 'types' => false, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
            return $arr;
        }
    }

    /**
     * @return  批量操作邀请返点
     * @param   bool  $is_pagination  是否分页
     * @return  array   分销返点数据arr
     */
    private function batch_updata_yqfenxiao($userids){
        $order_model = Model('order');
        if(!empty($_REQUEST['start_date'])){
            $filter['start_date'] = $_REQUEST['start_date'];
        }else{
            $filter['start_date'] = get_menyone();
        }
        if(!empty($_REQUEST['end_date'])){
            $filter['end_date'] = $_REQUEST['end_date'];
        }else{
            $filter['end_date'] = get_menyone();
        }
        $yues = get_timeyue($filter['start_date']);

        $where = "  order_status = 5 AND pay_status = 2 AND shipping_status = 2 AND user_id = '$userids' AND fandian_yq = 0 ";

        if ($filter['start_date'])
        {
            $where .= " AND add_time >= '" . $filter['start_date'] . "'";
        }
        if ($filter['end_date'])
        {
            $where .= " AND add_time <= '" . $filter['end_date'] . "'";
        }        
        $param = 'order_id,buyer_name,parent_id, fandian_pay,money_paid,add_time,pay_time,confirm_time,fandian_yq_pic';
        $fandian_data = $order_model->get_order_info_list($param,$where,'order_id asc');
        $yqtype = '';
        $paytype = '';
        if(!empty($fandian_data)){
            $nums = 0;
            $zongnums = count($fandian_data);
            $zongmoney = 0;
            $jiancepic = 0;
            $parent_ids = $fandian_data[0]['parent_id'];
            foreach($fandian_data as $key => $item){
                $jiancepic = $jiancepic +$item['fandian_yq_pic'];
                $orderids = $item['order_id'];
                $update['fandian_yq'] =1;
                $where = array();
                $where['order_id'] = "$orderids";
                $zahngss = $order_model->update_order_info($update,$where);
                if(!empty($zahngss)){
                    $zongmoney = $zongmoney+$item['fandian_yq_pic'];
                    $parent_ids2 = $item['parent_id'];
                    $nums = $nums+1;
                }
            }
            if($parent_ids == $parent_ids2){
                $zongyqfanli = intval($zongmoney);
                if(!empty($zongyqfanli) && !empty($parent_ids)){
                    if($zongyqfanli <= $jiancepic){
                        $where1['user_id'] = $parent_ids;
                        $field['user_money'] = $zongyqfanli;
                        $userupt = Model('users')->update_users_setInc($field,$where1);
                        if(!empty($userupt)){
                            $usertel = Model('users')->select_users_info('*','user_id='.$parent_ids);
                            $mobiles = $usertel['mobile_phone'];
                            $yquser_name = $usertel['alias'];
                            $user_money = $usertel['user_money'];
                            if(!empty($mobiles)){
                                $param = array();
                                $param['username'] = $yquser_name;
                                $param['yue'] = $yues;
                                $param['yuan'] = $zongyqfanli;
                                $param['types'] = '邀请人';
                                $param['zongyuan'] = $user_money;
                                $param['site_name'] = '淘玉商城';                                
                                $result = send_sms_msg($mobiles,'fandian_mags',$param);
                            }
                            $action_note = $yues.'月，总邀请人返点'.$zongyqfanli.'元，返到用户余额 总余额'.$user_money;
                            /* 插入帐户变动记录 */
                            $account_log = array(
                                'user_id'       => $parent_ids,
                                'user_money'    => $zongyqfanli,
                                'frozen_money'  => 0,
                                'rank_points'   => 0,
                                'pay_points'    => 0,
                                'change_time'   => gmtime(),
                                'change_desc'   => $action_note,
                                'change_type'   => 9
                            );
                            Model('accountlog')->insert_account_log($account_log);
                            $yqtype = true;
                        }else{
                            $yqtype = false;
                        }
                    }else{
                        $arr = array('msgs' => '此用户有非法操作情况，返点暂不发放，请联系技术和财务一起查证以后再发', 'types' => false, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
                        return $arr;
                    }
                }
                if(!empty($yqtype)){
                    $arr = array('msgs' => $yquser_name.'邀请人返点总'.$zongnums.'个，成功'.$nums.'个', 'types' => true, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
                    return $arr;
                }else{
                    $arr = array('msgs' => $yquser_name.'邀请人返点全部添加失败，总'.$zongnums.'个，成功'.$nums.'个', 'types' => false, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
                    return $arr;
                }
            }else{
                $arr = array('msgs' => '购买人有多个邀请人，无法批量邀请人返点，请点击详情单个返点', 'types' => false, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
                return $arr;
            }
        }else{
            $arr = array('msgs' => '暂时没有满足返点的订单，可以查看返点详情了解', 'types' => false, 'filter' => $filter, 'start_date' => $filter['start_date'], 'end_date' => $filter['end_date']);
            return $arr;
        }
    }

    /**
     * @return  单个操作购买返点
     * @param   bool  $is_pagination  是否分页
     * @return  array 分销返点数据arr
     */
    private function updata_fenxiao($orderid,$typesd){
        $order_model = Model('order');
        if($typesd == 1){
            /*确定（购买）返点*/
            $where = " order_id = $orderid AND fandian_pay = 0 ";
            $param = 'order_id, user_id,buyer_name, parent_id, fandian_pay,money_paid, fanli_pic, fandian_pay,goods_amount, order_sn';
            $fenxiao_data = $order_model->select_order_info_info($param,$where);
            $user_id = $fenxiao_data['user_id'];
            $fanli_pic = intval($fenxiao_data['fanli_pic']);
            $fandian_pay = $fenxiao_data['fandian_pay'];
            $order_sn = $fenxiao_data['order_sn'];
            $buyer_name = $fenxiao_data['buyer_name'];
            if(!empty($user_id) && $fanli_pic != 0 ){               
                $where = "order_id = $orderid";
                $data = array('fandian_pay'=>1);
                $upadeorder = $order_model->update_order_info($data,$where);
                if(!empty($upadeorder)){
                    $wheres = array('user_id'=>$user_id);
                    $inc_num['user_money'] = $fanli_pic;
                    $pareupde = Model('users')->update_users_setInc($inc_num,$wheres);
                    if(!empty($pareupde)){
                        $action_note = '订单号：'.$order_sn.'app购买返点'.$fanli_pic.'元，返到用户余额';
                        /* 插入帐户变动记录 */
                        $account_log = array(
                            'user_id'       => $user_id,
                            'user_money'    => $fanli_pic,
                            'frozen_money'  => 0,
                            'rank_points'   => 0,
                            'pay_points'    => 0,
                            'change_time'   => gmtime(),
                            'change_desc'   => $action_note,
                            'change_type'   => 9
                        );
                        Model('accountlog')->insert_account_log($account_log);
                        $arr = array('msgs' => '用户'.$buyer_name.'购买返点已放入用户余额', 'types' => true);
                        return $arr;
                    }else{
                        $arr = array('msgs' => '添加用户返点失败，但已修改返点属性不能在返点，需要返点请联系技术人员', 'types' => false);
                        return $arr;
                    }
                }else{
                    $arr = array('msgs' => '添加用户返点失败', 'types' => false);
                    return $arr;
                }
            }
        }elseif($typesd == 0){ 
            /*取消（购买）返点*/
            $where = "order_id = $orderid";
            $data = array('fandian_pay'=>2);
            $upadeorder = $order_model->update_order_info($data,$where);            
            if(!empty($upadeorder)){
                $arr = array('msgs' => '取消用户返点成功', 'types' => true);
                return $arr;
            }else{
                $arr = array('msgs' => '取消用户返点失败。', 'types' => false);
                return $arr;
            }
        }
    }

    /**
     * @return  单个操作购买返点
     * @param   bool  $is_pagination  是否分页
     * @return  array 分销返点数据arr
     */
    private function updata_yqfenxiao($orderid,$typesd){
        $order_model = Model('order');
        if($typesd == 1){
            /*确定（邀请）返点*/
            $where = " order_id = $orderid AND fandian_yq = 0 ";
            $param = 'order_id, user_id, buyer_name, parent_id, fandian_pay, money_paid, fanli_pic, fandian_yq_pic, goods_amount, order_sn';
            $fenxiao_data = $order_model->select_order_info_info($param,$where);
            if(!empty($fenxiao_data)){
                $parent_id = $fenxiao_data['parent_id'];
                $money_paid = $fenxiao_data['money_paid'];
                $fandian_yq_pic = $fenxiao_data['fandian_yq_pic'];
                $order_sn = $fenxiao_data['order_sn'];
                if(!empty($parent_id) && $money_paid != 0 && $fandian_yq_pic != 0){                    
                    $where = "order_id = $orderid";
                    $data = array('fandian_yq'=>1);
                    $upadeorder = $order_model->update_order_info($data,$where);
                    if(!empty($upadeorder)){
                        $duobi = intval($fandian_yq_pic);                        
                        $wheres = array('user_id'=>$parent_id);
                        $inc_num['user_money'] = $fandian_yq_pic;
                        $pareupde = Model('users')->update_users_setInc($inc_num,$wheres);
                        if(!empty($pareupde)){
                            $action_note = '订单号：'.$order_sn.'app邀请人返点'.$fandian_yq_pic.'元，返到用户余额';
                            /* 插入帐户变动记录 */
                            $account_log = array(
                                'user_id'       => $parent_id,
                                'user_money'    => $duobi,
                                'frozen_money'  => 0,
                                'rank_points'   => 0,
                                'pay_points'    => 0,
                                'change_time'   => gmtime(),
                                'change_desc'   => $action_note,
                                'change_type'   => 9
                            );
                            Model('accountlog')->insert_account_log($account_log);
                            $arr = array('msgs' => '邀请人返点已放入用户余额', 'types' => true);
                            return $arr;
                        }else{                           
                            $where = "order_id = $orderid";
                            $data = array('fandian_yq'=>0);    
                            $upadeorder = $order_model->update_order_info($data,$where);
                            $arr = array('msgs' => '添加邀请人返点失败2', 'types' => false);
                            return $arr;
                        }
                        
                    }else{
                        $arr = array('msgs' => '添加邀请人返点失败1', 'types' => false);
                        return $arr;
                    }
                }
            }else{
                $arr = array('msgs' => '已经返点过了，不能多次返点', 'types' => false);
                return $arr;
            }
        }else if($typesd == 0){
            /*取消（邀请）返点*/            
            $where = "order_id = $orderid";
            $data = array('fandian_yq'=>2);
            $upadeorder = $order_model->update_order_info($data,$where); 
            if(!empty($upadeorder)){
                $arr = array('msgs' => '取消邀请人返点成功', 'types' => true);
                return $arr;
            }else{
                $arr = array('msgs' => '取消邀请人返点返点失败。', 'types' => false);
                return $arr;
            }
        }
    }

    




















}
