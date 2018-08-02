<?php

/**
 * 淘玉php 拍卖活动数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 拍卖活动数据模型
 * $Id: lib_auction_model.php 17217 2018-05-4 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class auctionModel extends Model {
    
    /**
     * @return 获取对应活动总个数count
     * @param string/Array $where 查询条件 
     * @return int
     */
    function get_auction_count($where) {
       $param = array();
       $param['table'] = 'goods_activity';
       $param['count'] = 'count';
       $param['where'] = $where;
       return DB::select($param);
    }
    
    /**
     * @return 获取对应活动列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_goods_activity_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods_activity', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条活动信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_activity_info($field = '*', $where) {
       return get_one_table_list('goods_activity', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑活动信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods_activity($param,$where) {
        return update_table_info('goods_activity', $param, $where);
    }
    
    
}
