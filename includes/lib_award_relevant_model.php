<?php

/**
 * 淘玉php 摇奖用户信息数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 摇奖用户信息增删改查
 * $Id: lib_article_model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class award_relevantModel extends Model {

	/**
     * @return 新添加摇奖用户信息keyid
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_award_relevant($insert) {
        return add_table_info('award_relevant',$insert);
    }

    /**
     * @return  获取单个摇奖用户信息find
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function select_award_relevant_info($field = '*', $where) {
        $result = get_one_table_list('award_relevant', $field, $where, '', '', 'find');
        return $result;
    }
    
    /**
     * @return 摇奖次数加1操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认加1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段加5  $param = '表字段'  为本字段加1 
     */
    function update_award_relevant_setInc($where, $param) {
        return update_table_original_field('award_relevant', $where, $param);
    }       
    
    /**
     * @return 更新表名的某个字段直-n等类似的操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认减1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段减5  $param = '表字段'  为本字段减1 
     */
    function update_award_relevant_setDec($where, $param) {
        return update_table_setDec('award_relevant', $where, $param);
    }

}