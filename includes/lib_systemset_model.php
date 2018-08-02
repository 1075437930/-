<?php

/**
 * 淘玉 系统设置数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 系统设置相关
 * $Id: lib_systemset_model.php 17217 2018-04-07  萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class systemsetModel {

    
    
    /**
     * 获取友情链接数据
     *
     * @access      public
     * @param       $field
     * @param       $where
     * @return      array
     */
    function select_friend_link_info($field, $where) {
        return get_one_table_list('friend_link', $field, $where, '', '', 'find');
    }

    /**
     * @return 更新友情链接数据
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_friend_link($param, $where) {
        $result = Db::update('friend_link', $param, $where);
        return $result;
    }

    /**
     * @return 删除指定友情链接信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_friend_link($where) {
        $deladdre = delete_table_info('friend_link', $where);
        return $deladdre;
    }

}
