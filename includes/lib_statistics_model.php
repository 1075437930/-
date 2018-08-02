<?php
/**
 * 淘玉 数据统计数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 数据统计相关
 * $Id: lib_statistics_model.php 17217 2018-04-07  萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class statisticsModel{
    /**
     * 查询现有在库成品/半成品
     * @param int  $type   1是成品 2半成品
     * @return 查询现有在库成品/半成品array
     */
    public function get_finish_goods_now($type){
        $result = get_filter();
        if ($result === false) {
            /* 分页大小 */
            $filter = array();
            /* 记录总数以及页数 */
            $sql = "select * from ecs_stock_goods where st_goods_number > 0 and st_goods_type = $type";
            $res = Db::getAll($sql);
            $filter['record_count'] = count($res);
            $filter = page_and_size($filter);

            $tot_num = Db::getOne("select sum(st_goods_number) from ecs_stock_goods where st_goods_number > 0 and st_goods_type = $type");
            if(!$tot_num){
                $tot_num = 0;
            }
            if($type == 1){
                $tot_num1 = '在库的成品总数为'.$tot_num;
            }else{
                $tot_num1 = '在库的半成品总数为'.$tot_num;
            }
            /* 查询记录 */

            $sql = "select a.stock_id,a.st_goods_name,a.st_goods_sn,st_goods_number,a.st_cost_price,a.st_goods_remarks,b.group_name from ecs_stock_goods as a left join ecs_goods_group as b on a.st_goods_group = b.group_id where a.st_goods_number > 0 and a.st_goods_type = $type  " .'LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $all = Db::getAll($sql);
        return array('now_goods_list' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count'],'tot_num'=>$tot_num1);
    }

    /**
     * 查询在外成品/半成品
     * @param int $type  1是成品 2半成品
     * @return 查询在外成品/半成品array
     */
	public function get_semi_finish_goods_out($type){
        $result = get_filter();
        if ($result === false) {
            /* 分页大小 */
            $filter = array();
            /* 记录总数以及页数 */
            $sql = "select * from ecs_out_goods as a left join ecs_stock_goods as b on a.out_stock_id = b.stock_id where b.st_goods_type = $type ";
            $res = Db::getAll($sql);
            $filter['record_count'] = count($res);
            $filter = page_and_size($filter);

            $tot_num = Db::getOne("select sum(out_goods_number) from ecs_out_goods as a left join ecs_stock_goods as b on a.out_stock_id = b.stock_id where b.st_goods_type = $type ");
            if(!$tot_num){
                $tot_num = 0;
            }
            if($type == 1){
                $tot_num1 = '在外的成品总数为'.$tot_num;
            }else{
                $tot_num1 = '在外的半成品总数为'.$tot_num;
            }
            /* 查询记录 */
            $sql = "select a.out_id,a.out_goods_number,a.out_remarks,b.stock_id,b.st_goods_name,b.st_goods_sn,b.st_cost_price,c.group_name from ecs_out_goods as a left join ecs_stock_goods as b on a.out_stock_id = b.stock_id left join ecs_goods_group as c on a.out_goods_group = c.group_id where b.st_goods_type = $type  " .'LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $all = Db::getAll($sql);
        return array('out_goods_list' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count'],'tot_num'=>$tot_num1);
    }

    /**
     * 根据条件查询商品列表
     * @param string $is_on       是否在库 1 在 2 出库 默认1
     * @param string $type         商品的状态 1成品 2半成品 默认1
     * @param string $start_time  时间范围开始时间
     * @param string $end_time    时间范围结束时间
     * @return  根据条件查询商品列表array
     */
    public function get_ku_goods_list($is_on=1,$type='',$start_time='',$end_time=''){
        if($is_on == 1){
            //在库
        $sql = "select a.stock_id,a.st_goods_name,a.st_goods_sn,st_goods_number,a.st_cost_price,a.st_goods_remarks,a.st_goods_type,b.group_name from ecs_stock_goods as a left join ecs_goods_group as b on a.st_goods_group = b.group_id where a.st_goods_number > 0   " ;
         $sq11 =   "select sum(st_goods_number) from ecs_stock_goods  where st_goods_number > 0   " ;
            if($type){
                $sql = $sql." and a.st_goods_type = $type ";
                $sq11 = $sq11." and st_goods_type = $type ";
            }
            if($start_time){
                $sql = $sql." and st_add_time between '$start_time' and  '$end_time'";
                $sq11 = $sq11." and st_add_time between '$start_time' and  '$end_time'";
            }
        }else if($is_on == 2){
            //出库
            $sql = "select a.out_id,a.out_goods_number,a.out_remarks,b.stock_id,b.st_goods_name,b.st_goods_sn,b.st_cost_price,b.st_goods_type,c.group_name from ecs_out_goods as a left join ecs_stock_goods as b on a.out_stock_id = b.stock_id left join ecs_goods_group as c on a.out_goods_group = c.group_id  ";
            $sq11= "select sum(out_goods_number) from ecs_out_goods as a left join ecs_stock_goods as b on a.out_stock_id = b.stock_id  ";
            if($type){
                $sql = $sql."  where b.st_goods_type = $type ";
                $sq11 = $sq11." where b.st_goods_type = $type ";
                if($start_time){
                    $sql = $sql." and out_add_time between '$start_time' and  '$end_time'";
                    $sq11 = $sq11." and out_add_time between '$start_time' and  '$end_time'";
                }
            }else{
                if($start_time){
                    $sql = $sql." where out_add_time between '$start_time' and  '$end_time'";
                    $sq11 = $sq11." where out_add_time between '$start_time' and  '$end_time'";
                }
            }
        }
        print_r($sql);
        die;
        $result = get_filter();
        if ($result === false) {
            /* 分页大小 */
            $filter = array();
            /* 记录总数以及页数 */
            $res = Db::getAll($sql);
            $filter['record_count'] = count($res);
            $filter = page_and_size($filter);
            $tot_num = Db::getOne($sq11);
            if(!$tot_num){
                $tot_num = 0;
            }else{
               if($is_on == 1){
                   if($type == 1){
                       $tot_num = "在库成品的总数量为：".$tot_num;
                   }elseif($type==2){
                       $tot_num = "在库半成品的总数量为：".$tot_num;
                   }else{
                       $tot_num = "在库的总数量为：".$tot_num;
                   }
               }else{
                   if($type == 1){
                       $tot_num = "出库成品的总数量为：".$tot_num;
                   }elseif($type==2){
                       $tot_num = "出库半成品的总数量为：".$tot_num;
                   }else{
                       $tot_num = "出库的总数量为：".$tot_num;
                   }
               }
            }
            /* 查询记录 */
            $sql = $sql .' LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $all = Db::getAll($sql);
        if($is_on == 1){
            return array('now_goods_list' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count'],'tot_num'=>$tot_num);
        }else{
            return array('out_goods_list' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count'],'tot_num'=>$tot_num);
        }
    }


}