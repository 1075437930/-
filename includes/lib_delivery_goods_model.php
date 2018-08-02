<?php

/**
 * 淘玉 发货商品数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 发货商品信息增删改查
 * $Id: lib_order_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class delivery_goodsModel extends Model{

	
	/**
     * @return 获取订单发货商品列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return array
     */
    public function get_delivery_goods_list($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('delivery_goods', $field, $where, $order, $limit, 'select');
    }

	/**
     * @return 获取一条订单发货商品信息find
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function select_delivery_goods_info($field = '*', $where='',$order='',$limit='') {
        return get_one_table_list('delivery_goods', $field, $where, $order, $limit, 'find');
    }

    /**
     * @return 新增订单发货商品
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_delivery_goods($param) {
        $result = add_table_info('delivery_goods', $param);
        return $result;
    }

    /**
     * @return 删除订单发货商品
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_delivery_goods($where) {
        return delete_table_info('delivery_goods', $where);
    }

    /**
     * @return 链表查询订单发货商品信息(order_goods,goods,products) 
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_delivery_goods_goods_products_list($field,$where,$order = '',$limit = '',$group=''){
        return $this->table('delivery_goods,goods,products')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('delivery_goods.goods_id = goods.goods_id,delivery_goods.product_id = products.product_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->group($group)
        ->select();
    }

    /**
     * @return 链表查询订单发货商品信息(order_goods,goods,products) 
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_delivery_goods_goods_list($field,$where,$order = '',$limit = '',$group=''){
        return $this->table('delivery_goods,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('delivery_goods.goods_id = goods.goods_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->group($group)
        ->select();
    }

    /**
     * @return 链表查询单条订单发货商品信息(delivery_goods,delivery_order) 
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function select_delivery_goods_order_info($field,$where,$order = '',$limit = '',$group=''){
        return $this->table('delivery_goods,delivery_order')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('delivery_order.delivery_id = delivery_goods.delivery_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->group($group)
        ->find();
    }
}