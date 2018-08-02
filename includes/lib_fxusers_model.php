<?php

/**
 * 淘玉php 分销设置模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 分销设置增删改查
 * $Id: admin.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class fxusersModel{
   
    /**
     * @param int  $user_id 用户id
     * @return 通过用户id查询用户的等级信息
     */
    public function select_user_level_infomation($user_id){
        $sqluser = " SELECT us.user_name,us.alias,us.real_name,us.fxalias,les.level_name,les.level_bili,les.remark ".
            " FROM " . Model()->tablename('users') ." AS us, ".
            Model()->tablename('user_level')." AS les  " .
            " WHERE us.level_id = les.level_id AND user_id = $user_id " ;
        $user_into = Db::getRow($sqluser);
        return $user_into;
    }
   
    /**
     * 查询订单数目
     * @param string $where 需要查询的条件
     * @return 查询订单数目int
     */
    public function select_order_info_count($where){
        $sqlconut = "SELECT COUNT(a.counts) FROM (select count(*) counts from ".Model()->tablename('order_info').' where '.$where." group by user_id ) a ";
        $res = Db::getOne($sqlconut);
        return $res;
    }
     
    /**
     * @param string $where 需要查询的条件
     * @return 根据条件查询mail_templates的信息array
     */
    public function select_mail_templates_info($where){
        $sql2  = 'SELECT *'.
            ' FROM ' .$GLOBALS['ecs']->table('mail_templates').
            " WHERE $where ";
        $tpl_info = Db::getRow($sql2);
        return $tpl_info;
    }

    /**
     * @param int  $userid 用户id
     * @param string $starts    开始时间
     * @param string $ends      结束时间
     * @return 根据月份计算用户单月总销售金额int
     */
    public function get_meny_pic($userid,$starts = '',$ends = ''){
        $where = " WHERE user_id = '$userid' AND add_time >= $starts AND add_time <= $ends AND order_status IN ('0','1','5') AND pay_status = 2 ";
        $sql = "SELECT sum(money_paid) as zongpic " .
            " FROM ".Model()->tablename('order_info').$where;
        $fenxiao_data = Db::getAll($sql);

        return $fenxiao_data[0]['zongpic'];
    }
}