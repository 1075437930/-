<?php

/**
 * 淘玉php 典藏订单产品模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 典藏订单产品模型
 * $Id: lib_diancang_ordergoods.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class diancang_ordergoodsModel extends Model {

    /**
     * @return 新增典藏订单产品
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_ordergoods($param) {
        $result = add_table_info('capital_ordergoods', $param);
        return $result;
    }

    /**
     * @return 删除典藏订单产品
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_ordergoods($where) {
        $result = delete_table_info('capital_ordergoods', $where);
        return $result;
    }

    /**
     * @return 更新典藏订单产品信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_capital_ordergoods_info($param,$where) {
        $result = update_table_info('capital_ordergoods', $param, $where);
        return $result;
    }

    /**
     * @return  获取单个典藏订单产品信息find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_ordergoods_info($field = '*', $where) {
        $result = get_one_table_list('capital_ordergoods', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取典藏订单产品信息select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_capital_ordergoods_list($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('capital_ordergoods', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 链表查询典藏订单产品信息，find,(capital_ordergoods，capital_goods)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function select_capital_ordergoods_goods_info($field,$where,$order = '',$limit = ''){
        return $this->table('capital_ordergoods,capital_goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('capital_ordergoods.dcgoods_id = capital_goods.capitalid')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 查询典藏订单产品记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_ordergoods_count($where) {
        $param = array();
        $param['table'] = 'capital_ordergoods';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

}