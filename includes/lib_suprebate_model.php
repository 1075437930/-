<?php

/**
 * 淘玉php 入驻商佣金支付记录模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 入驻商佣金支付记录数据模型
 * $Id: lib_suprebate_model.php 17217 2018-05-5 :29:08Z 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class suprebateModel extends Model {

	/**
     * @return 获取单条supplier_rebate_log信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_supplier_rebate_log_info($field = '*', $where) {
        return get_one_table_list('supplier_rebate_log', $field, $where, '', '', 'find');
    }

    /**
     * @return 新添加supplier_rebate_log信息keyid
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_supplier_rebate_log($insert) {
        return add_table_info('supplier_rebate_log', $insert);
    }

    /**
     * @return 获取所有的商家名称Description
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_supplier_rebate_log_supplier_list($field, $where, $group, $order = '', $limit = '') {
        return $this->table('supplier_rebate_log,supplier')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('supplier_rebate_log.supplier_id=supplier.supplier_id ')
        ->where($where)
        ->group($group)
        ->order($order)
        ->limit($limit)
        ->select();
    }

    /**
     * @return 查询总佣金日志记录个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_supplier_rebate_log_count($where) {
        $param = array();
        $param['table'] = 'supplier_rebate_log';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 查询总佣金记录个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_supplier_rebate_log_count2($where) {
        $sql = "SELECT COUNT(sr.supplier_id) FROM " . Model()->tablename('supplier_rebate_log') . " AS sr  " . $where . " group by sr.supplier_id ";
        return Db::getAll($sql);
    }

    /**
     * @return 获取所有商家的订单
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_supplier_rebate_log_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('supplier_rebate_log', $field, $where, $order, $limit, 'select');
    }

}