<?php

/**
 * 淘玉 还货商品数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 还货商品信息增删改查
 * $Id: lib_order_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class back_goodsModel extends Model{

	/**
     * @return 添加新还货商品
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_back_goods($insert) {
       return add_table_info('back_goods',$insert);
    }

    /**
     * @return 编辑更新还货商品信息
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_back_goods($param,$where) {
        return update_table_info('back_goods', $param, $where);
    }

    /**
     * @return 删除还货订单商品
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_back_goods($where) {
        return delete_table_info('back_goods', $where);
    }

    /**
     * @return 链表查询还货商品find，（back_goods，back_order） 
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_back_goods_back_order_list($field,$where,$order = '',$limit = ''){
        return $this->table('back_goods,back_order')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('back_goods.back_id=back_order.back_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 还货商品列表
     * @param  $field  str 搜索字段
     * @param  $where  str 搜索条件
     * @param  $order  str 排序
     * @param  $limit  str 限制条件
     * @return array 
     */
    public function get_back_goods_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('back_goods', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条还货商品信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function select_back_goods_info($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('back_goods', $field, $where, $order, $limit, 'find');
      return $result;
    }


}