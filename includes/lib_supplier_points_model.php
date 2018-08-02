<?php

/**
 * 淘玉php 处罚分类模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 处罚分类模型
 * $Id: lib_supplier_model.php 17217 2018-05-5 :29:08Z 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class supplier_pointsModel extends Model {

	/**
     * @return 新添加处罚分类信息
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_supplier_points($insert) {
        return add_table_info('supplier_points', $insert);
    }

    /**
     * @return 删除处罚分类 bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_supplier_points($where) {
        return delete_table_info('supplier_points', $where);
    }

    /**
     * @return 编辑处罚分类信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_supplier_points($param, $where) {
        return update_table_info('supplier_points', $param, $where);
    }

    /**
     * @return 获取单条处罚分类信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return  array 数组格式的返回结果
     */
    public function select_supplier_points_info($field = '*', $where) {
        return $this->table('supplier_points')->field($field)->where($where)->find();
    }

    /**
     * @return 获取多条处罚分类记录
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return array
     */
    public function get_supplier_points_list($field, $where, $order = '', $limit = 0) {
        return $this->table('supplier_points')->field($field)->where($where)->order($order)->limit($limit)->select();
    }

    /**
     * @return 查询入驻商积分分类记录个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_supplier_points_count($where) {
        $param = array();
        $param['table'] = 'supplier_points';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

}
