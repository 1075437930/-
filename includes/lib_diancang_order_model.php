<?php

/**
 * 淘玉php 典藏订单数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 典藏订单数据模型
 * $Id: lib_diancang_order_model.php 17217 2018-05-5 :29:08Z 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class diancang_orderModel extends Model {
    
    /*********典藏订单*********/

    /**
     * @return 删除典藏订单信息
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_order($where) {
        $result = delete_table_info('capital_order', $where);
        return $result;
    }

    /**
     * @return 编辑更新典藏订单信息
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_capital_order($param,$where) {
        return update_table_info('capital_order', $param, $where);
    }

    /**
     * @return  获取单条典藏订单信息find
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @return  array  数组格式的返回结果
     */
    public function select_capital_order_info($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('capital_order', $field, $where, $order, $limit, 'find');
      return $result;
    }

    /**
     * @return 获取典藏订单信息select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序
     * @param  string $limit 分页
     * @return string/array $where 查询条件
     */
    public function get_capital_order_list($field, $where='', $order='' , $limit = 0) {
        $result = get_one_table_list('capital_order', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 链表查询典藏订单信息，find,(capital_order，region)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_capital_order_region_info($field,$where,$order = '',$limit = ''){
        return $this->table('capital_order,region')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('capital_order.province = region.region_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 链表查询典藏订单信息，select,(capital_order，capital_ordergoods)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_capital_order_ordergoods_list($field,$where,$order = '',$limit = ''){
        return $this->table('capital_order,capital_ordergoods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('capital_order.capital_orderid = capital_ordergoods.capital_orderid')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }

    /**
     * @return 链表查询典藏订单信息，select,(capital_order，capital_ordergoods，capital_goods)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function select_capital_order_ordergoods_goods_info($field,$where,$order = '',$limit = ''){
        return $this->table('capital_order,capital_ordergoods,capital_goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('capital_order.capital_orderid = capital_ordergoods.capital_orderid,capital_ordergoods.dcgoods_id = capital_goods.capitalid')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 查询总典藏订单个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_capital_order_count($where) {
        $param = array();
        $param['table'] = 'capital_order';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /*********典藏订单操作记录*********/

    /**
     * @return 新增典藏订单操作记录
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_orderaction($param) {
        $result = add_table_info('capital_orderaction', $param);
        return $result;
    }

    /**
     * @return  获取单条典藏订单操作记录信息find
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @return  array  数组格式的返回结果
     */
    public function select_capital_orderaction_info($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('capital_orderaction', $field, $where, $order, $limit, 'find');
      return $result;
    }

    /**
     * @return  获取多条典藏订单操作记录信息select
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @return  array  数组格式的返回结果
     */
    public function get_capital_orderaction_list($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('capital_orderaction', $field, $where, $order, $limit, 'select');
      return $result;
    }
 
}
