<?php
/********************************** 前台control父类 **********************************************/
/**
 * 淘玉php 前台control父类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * $Id: control.php 17217 2015-05-19 06:29:08Z 淘玉 $
*/
defined('TaoyuShop') or exit('Access Invalid!control');
class BaseControl{
        /**
	 * 管理信息参数 
	 */
	protected $admin_info;
	/**
	 * 权限内容
	 */
	protected $permission;
        /**
	 * 模版类
	 */
	protected $smarty;
	protected function __construct(){
		Language::read('log_action,menu,priv_action');
                $lang = Language::getLangContent();
                Tpl::assign('lang',$lang);
		$this->admin_info = $this->systemLogin();
	}
        
	/**
	 * @return 取得当前管理员信息
	 * @return 数组类型的返回结果
	 */
	protected final function getAdminInfo(){
		return $this->admin_info;
	}
	/**
	 * @return 系统后台登录验证
	 * @return array 返回用户数组
	 */
	protected final function systemLogin(){
		//取得cookie内容，解密，和系统匹配
		$user = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
		if (empty($user['user_name']) && empty($user['user_id'])){
			@header('Location: index.php?act=login&op=login');exit;
		}else {
			$this->systemSetKey($user);
		}
		return $user;
	}

	/**
	 * @return 系统后台会员登录后将会员验证内容写入对应cookie中
	 * @param string $name 用户名
	 * @param int $id 用户ID
	 * @return bool 布尔类型的返回结果
	 */
	protected final function systemSetKey($user){
		setNcCookie('sys_key',encrypt(serialize($user),MD5_KEY),3600,'',null);
	}

}



