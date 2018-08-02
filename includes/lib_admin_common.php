<?php
/**
 * 淘玉php 管理员数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 管理员信息增删改查
 * $Id: admin.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class adminCommon{
	/**
	 * 管理员列表
	 *
	 * @param array $condition 检索条件
	 * @param obj $obj_page 分页对象
	 * @return array 数组类型的返回结果
	 */
	public function getAdminList($condition,$obj_page){
		$condition_str = $this->_condition($condition);
		$param = array(
                        'table'=>'admin_user',
                        'field'=>'*',
                        'where'=>$condition_str
                );
		$result = Db::select($param);
		return $result;
	}
	
	/**
	 * 构造检索条件
	 *
	 * @param array $condition 检索条件
	 * @return string 字符串类型的返回结果
	 */
	public function _condition($condition){
		$condition_str = '';
		
		if ($condition['user_id'] != ''){
			$condition_str .= " and user_id = '". $condition['user_id'] ."'";
		}
		if ($condition['user_name'] != ''){
			$condition_str .= " and user_name = '". $condition['user_name'] ."'";
		}
		if ($condition['password'] != ''){
			$condition_str .= " and password = '". $condition['password'] ."'";
		}
		
		return $condition_str;
	}
	
	/**
	 * 取单个管理员的内容
	 *
	 * @param int $user_id 管理员ID
	 * @return array 数组类型的返回结果
	 */
	public function getOneAdmin($user_id){
		if (intval($user_id) > 0){
			$param = array();
			$param['table'] = 'admin_user';
			$param['field'] = 'user_id';
			$param['value'] = intval($user_id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	/**
	 * 获取管理员信息
	 *
	 * @param	array $param 管理员条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoAdmin($param, $field = '*') {
		if(empty($param)) {
			return false;
		}
		//得到条件语句
		$condition_str	= $this->_condition($param);
		$param	= array();
		$param['table']	= 'admin_user';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$admin_info	= Db::select($param);
		return $admin_info[0];
	}
	
	/**
	 * 新增
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function addAdmin($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('admin_user',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 更新信息
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function updateAdmin($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " user_id = '". $param['user_id'] ."'";
			$result = Db::update('admin_user',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function delAdmin($id){
		if (intval($id) > 0){
			$where = " user_id = '". intval($id) ."'";
			$result = Db::delete('admin_user',$where);
			return $result;
		}else {
			return false;
		}
	}
}