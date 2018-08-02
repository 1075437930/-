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

class regionModel extends Model {

    /**
     * @return 获取区域数据（单条）
     * @param  $field
     * @param  $where
     * @return array
     */
    function select_region_info($field, $where) {
        return get_one_table_list('region', $field, $where, '', '', 'find');
    }
    
    /**
     * @return 获取区域数据(列表)
     * @param  $field  str 搜索字段
     * @param  $where  str 搜索条件
     * @param  $order  str 排序
     * @param  $limit  str 限制条件
     * @return array
     */
    function get_regoin_list($field='*', $where='',$order='',$limit='') {
        return get_one_table_list('region', $field, $where, '', '', 'select');
    }
    
    /**
     * @return 新添加区域信息keyid
     * @param  array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_region($insert) {
       return add_table_info('region',$insert);
    }
    
    /**
     * @return 编辑区域信息boolean
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_region($param,$where) {
       return update_table_info('region', $param, $where);
    }
     /**
     * @return 删除区域信息bool
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    function delete_region($where) {
        return delete_table_info('region', $where);
    }
}
