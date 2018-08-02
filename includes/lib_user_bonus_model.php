<?php
/**
 * 淘玉 用户红包数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 订单信息增删改查count
 * $Id: lib_order_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class user_bonusModel extends Model{

	/**
     * @return 编辑订单信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_user_bonus($param,$where) {
        return update_table_info('user_bonus', $param, $where);
    }





}