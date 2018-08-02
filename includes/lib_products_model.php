<?php

/**
 * 淘玉 货品数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 货品信息增删改查
 * $Id: lib_goods_model.php 17217 2018-04-07  萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class productsModel extends Model{

	/**
     * @return 获取单条货品信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_products_info($field = '*', $where) {
       return get_one_table_list('products', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑库品信息boolean
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_products($param,$where) {
        return update_table_info('products', $param, $where);
    }

     /**
     * @return 更新products表的某个字段直+n等类似的操作
     * @param  $where  array   查询条件
     * @param  $param   sting/array  需要增加或减少N的字段  默认加1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段加5  $param = '表字段'  为本字段加1 
     */
    public function update_products_setInc($where, $param) {
        return update_table_original_field('products', $where, $param);
    }

    /**
     * @return 更新products的某个字段直-n等类似的操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认减1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段减5  $param = '表字段'  为本字段减1 
     */
    public function update_products_setDec($where, $param) {
        return update_table_setDec('products', $where, $param);
    }
}