<?php

/**
 * 淘玉php 促销管理数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 促销管理数据模型
 * $Id: activity.model.php 17217 2018-05-4 :29:08Z 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class activityModel extends Model {

    /**
     * @return 获取实体店记录数 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_offlineshop_list_count($where) {
        $param = array();
        $param['table'] = 'offlineshop_list';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取实体店用户数 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_offlineshop_user_count($where) {
        $param = array();
        $param['table'] = 'offlineshop_user';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取线下酒店单条数据
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_offlineshop_list_info($field = '*', $where) {
        return get_one_table_list('offlineshop_list', $field, $where, '', '', 'find');
    }

    /**
     * @return 删除指定实体店信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_offlineshop($where) {
        $deladdre = delete_table_info('offlineshop_list', $where);
        return $deladdre;
    }

    /**
     * @return 三表链表查询实体店绑定用户列表Description
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_offlineshop_user_users_offlineshop_list_list($field, $where, $order = '', $limit = '') {
        return $this->table('offlineshop_user,users,offlineshop_list')
                        ->field($field)
                        ->join('left')//right 或者inner 
                        ->on('offlineshop_user.user_id = users.user_id,offlineshop_user.offline_id = offlineshop_list.offline_id')
                        ->where($where)
                        ->order($order)
                        ->limit($limit)
                        ->select();
    }

}
