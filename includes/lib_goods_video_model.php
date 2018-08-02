<?php
/**
 * 淘玉 商品视频模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 商品视频增删改查
 * $Id: lib_goods_video_model.php 17217 2018-06-14  陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class goods_videoModel extends Model{
    /**
     * @return 查询商品视频总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_goods_video_count($where){
        $param = array();
        $param['table'] = 'goods_video';
        $param['count'] = 'count';
        $param['where'] = $where;
        return DB::select($param);
    }
    /**
     * @return 商品视频列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */

    public function get_goods_video_list($field='*',$where,$order='',$limit=0){
        return get_one_table_list('goods_video',$field, $where, $order, $limit, 'select');
    }

    /**
     * @return 获取单条視頻信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param $order  str 排序条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_video_info($field = '*', $where='',$order='') {
        return get_one_table_list('goods_video', $field, $where, $order, '', 'find');
    }

    /**
     * @param string/array $where 删除的条件
     * @return 删除视频信息bool
     */
    public function delete_goods_video($where){
        $return = delete_table_info('goods_video', $where);
        return $return;
    }

    /**
     * @return  插入视频 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_video($insert) {
        return add_table_info('goods_video', $insert);
    }


}