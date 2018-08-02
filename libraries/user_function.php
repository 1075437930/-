<?php

/**
 * 淘玉php 用户信息类公共方法
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 用户信息类公共方法
 * $Id: index.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!index');

/**
  * @return  取得用户信息
  * @param   int     $user_id    用户id
  * @return  array   用户信息
  */
 function user_info($user_id){
     $where = "user_id = '$user_id'";
     $user = get_one_table_list('users', '*', $where, '', '', 'find');
     unset($user['question']);
     unset($user['answer']);
     /* 格式化帐户余额 */
     if ($user){
         $user['formated_user_money'] = price_format($user['user_money'], false);
         $user['formated_frozen_money'] = price_format($user['frozen_money'], false);
     }
     return $user;
 }