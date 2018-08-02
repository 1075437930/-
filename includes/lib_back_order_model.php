<?php

/**
 * 淘玉 还货订单数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 还货订单数据信息增删改查count
 * $Id: lib_order_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class back_orderModel extends Model{

	/**
     * @return 获取单条还货订单信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function select_back_order_info($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('back_order', $field, $where, $order, $limit, 'find');
      return $result;
    }

    /**
     * @return 添加新还货订单
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_back_order($insert) {
       return add_table_info('back_order',$insert);
    }

    /**
     * @return 编辑更新还货订单信息
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_back_order($param,$where) {
        return update_table_info('back_order', $param, $where);
    }

    /**
     * @return 删除还货订单
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_back_order($where) {
        return delete_table_info('back_order', $where);
    }

    /**
     * @return 查询总还货订单个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_back_order_count($where) {
        $param = array();
        $param['table'] = 'back_order';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 还货订单操作记录列表
     * @param  $where  str 搜索条件
     * @param  $limit  str 限制条件
     * @param  $field  str 搜索字段
     * @return array 
     */
    public function get_back_action_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('back_action', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 删除还货订单操作记录列表
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_back_action($where) {
        return delete_table_info('back_action', $where);
    }

    /**
     * @return 新添还货订单操作记录
     * @param  array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_back_action($insert) {
       return add_table_info('back_action',$insert);
    }

    /**
     * @return 新添退换货回复
     * @param  array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_back_replay($insert) {
       return add_table_info('back_replay',$insert);
    }

    /**
     * @return 获取退换货回复图片列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_back_replay_list($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('back_replay', $field, $where, $order, $limit, 'select');
      return $result;
    }

    /**
     * @return 链表查询退货单商品信息find,(back_order,goods)
     * @param  string $field 查询字段
     * @param  string $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_back_order_goods_list($field,$where,$order='',$limit=0){
        return $this->table('back_order,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on("back_order.goods_id = goods.goods_id")
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    } 

    /**
     * @return 链表查询退货单商品信息find,(back_order,order_info)
     * @param  string $field 查询字段
     * @param  string $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_back_order_order_info_list($field,$where,$order='',$limit=0){
        return $this->table('back_order,order_info')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on("back_order.order_id = order_info.order_id")
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }     
}