<?php

/**
 * 短信模板
 * @author Administrator
 */
class Mail_templatesModel extends Model{


    /**
     * @return 查询模板总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_mail_templates_count($where){
        $param = array();
        $param['table'] = 'mail_templates';
        $param['count'] = 'count';
        $param['where'] = $where;
        return DB::select($param);
    }



    /**
     * @return 短信列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_mail_templates_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('mail_templates', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条模板信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param $order  str 排序条件
     * @return	array 数组格式的返回结果
     */
    public function select_mail_templates_info($field = '*', $where='',$order='') {
        return get_one_table_list('mail_templates', $field, $where, $order, '', 'find');
    }

    /**
     * @return  插入模板 Description
     * @param   array  $insert 插入条件
     */
    public function insert_mail_templates($insert) {
        return add_table_info('mail_templates', $insert);
    }

    /**
     * @return 编辑模板信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_mail_templates($param,$where) {
        return update_table_info('mail_templates', $param, $where);
    }

    /**
     * @param string/array $where 删除的条件
     * @return 删除模板信息bool
     */
    public function delete_mail_templates($where){
        $return = delete_table_info('mail_templates', $where);
        return $return;
    }
}
