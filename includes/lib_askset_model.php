<?php

/**
 * 淘玉php 问答数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 问答信息增删改查
 * $Id: master.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class asksetModel extends Model {

    /**
     * @return 新增问答
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_que_ans($param) {
        $result = add_table_info('que_ans', $param);
        return $result;
    }

    /**
     * @return 删除问答
     * @param  string/array $where 删除条件
     * @return bool
     */
    public function delete_que_ans($where) {
        return delete_table_info('que_ans',$where);

    }

    /**
     * @return 更新问答信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_que_ans_info($param,$where) {
        $result = update_table_info('que_ans', $param, $where);
        return $result;
    }

    /**
     * @return 获取问答一条信息find
     * @param  str $field 查询字段
     * @param  array/string $where 条件
     * @return array
     */
    public function select_que_ans_info($field='*',$where='') {
        $result = get_one_table_list('que_ans', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 查询问答数量
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_que_ans_count($where) {
        $param = array();
        $param['table'] = 'que_ans';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 新增问答分类
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_que_class($param) {
        $result = add_table_info('que_class', $param);
        return $result;
    }

    /**
     * @return 删除问答分类
     * @param  string/array $where 删除条件
     * @return bool
     */
    public function delete_que_class($where) {
        return delete_table_info('que_class',$where);

    }

    /**
     * @return 更新问答分类信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_que_class_info($param,$where) {
        $result = update_table_info('que_class', $param, $where);
        return $result;
    }

    /**
     * @return 获取问答分类一条信息find
     * @param  str $field 查询字段
     * @param  array/string $where 条件
     * @return array
     */
    public function select_que_class_info($field='*',$where='') {
        $result = get_one_table_list('que_class', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 查询问答分类数量
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_que_class_count($where) {
        $param = array();
        $param['table'] = 'que_class';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取问答分类列表
     * @param  str $field 搜索字段
     * @param  array/string $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function get_que_class_list($field='*', $where='', $order='' , $limit = 0) {
        $result = get_one_table_list('que_class', $field, $where, $order, $limit, 'select');
        return $result;
    }
   
    /**
     * @return 查询客服日志记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_cus_log_users_count($where) {
        $res = $this->table('cus_ser_log,users')
        ->field('*')
        ->join('left')
        ->on('cus_ser_log.user_id=users.user_id')
        ->where($where)
        ->select();
        return count($res);
    }

}