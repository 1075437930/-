<?php

/**
 * 淘玉php 云片短信
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 云片短信
 * $Id: payment.php 17217 2015-05-19 06:29:08Z 淘玉 $
*/
defined('TaoyuShop') or exit('Access Invalid!tpl');

class Yunpian1 {
	public $apikey ='b837e2df35fa1f1d2270447b4df54bd3';

	/**
	 * @return 指定模板单发
	 * @param  int    $tpl_id  模板id，需要使用已审核通过的模板id
	 * @param  array  $param   短信模板参数，例如：$param['number'] = 1234 ;
	 * @param  str    $mobile  接收的手机号,仅支持单号码发送
	 * @return array  $result  数组形式返回值
	 */
	public function tpl_single_send($tpl_id,$param,$mobile){
		$tpl_value = '';
		foreach ($param as $key => $value) {
			$tpl_value.=urlencode("#$key#").'='.urlencode($value).'&';
		}
		$data['tpl_id'] = $tpl_id;
		$data['tpl_value'] = rtrim($tpl_value,'&');
		$data['apikey'] = $this->apikey;
		$data['mobile'] = $mobile;
		$url = 'v2/sms/tpl_single_send.json';
		$result = json_decode($this->send($url,$data),true);
		return $result;		 
	}

	/**
	 * @return 单条短信发送,智能匹配短信模板
	 * @param  str    $param   需要使用已审核通过的模板或者默认模板；
	 * @param  str    $mobile  接收的手机号,仅支持单号码发送
	 * @return array  $result  数组形式返回值
	 */
	public function single_send($param,$mobile){
		$data['text'] = $param;
		$data['apikey'] = $this->apikey;
		$data['mobile'] = $mobile;
		$url = 'v2/sms/single_send.json';
		$result = json_decode($this->send($url,$data),true);
		return $result;
	}

	/**
	 * @return 指定模板群发
	 * @param  int    $tpl_id  模板id，需要使用已审核通过的模板id
	 * @param  array  $param   短信模板参数，例如：$param['number'] = 1234 ;
	 * @param  str    $mobile  接收的手机号,多个手机号用英文逗号隔开
	 * @return array  $result  数组形式返回值
	 */
	public function tpl_batch_send($tpl_id,$param,$mobile){
		$tpl_value = '';
		foreach ($param as $key => $value) {
			$tpl_value.=urlencode("#$key#").'='.urlencode($value).'&';
		}
		$data['tpl_id'] = $tpl_id;
		$data['tpl_value'] = rtrim($tpl_value,'&');
		$data['apikey'] = $this->apikey;
		$data['mobile'] = $mobile;
		$url = 'v2/sms/tpl_batch_send.json';
		$result = json_decode($this->send($url,$data),true);
		return $result;		 
	}

	/**
	 * @return 批量发送相同内容,相同内容多个号码
	 * @param  str    $param   需要使用已审核通过的模板或者默认模板；
	 * @param  str    $mobile  接收的手机号,多个手机号用英文逗号隔开
	 * @return array  $result  数组形式返回值
	 */
	public function batch_send($param,$mobile){
		$data['text'] = $param;
		$data['apikey'] = $this->apikey;
		$data['mobile'] = $mobile;
		$url = 'v2/sms/batch_send.json';
		$result = json_decode($this->send($url,$data),true);
		return $result;
	}

	/**
	 * @return 添加模版
	 * @param  str    $content 模板内容，必须以带符号【】的签名开头,如：【淘玉】您的验证码是#code#
	 * @return array  $result  数组形式返回值
	 */
	public function tpl_add($content,$tplType){
		$data['apikey'] = $this->apikey;
		$data['tpl_content'] = $content;
		$data['tpl_type'] = $tplType;

		$url = 'v2/tpl/add.json';
		$result = json_decode($this->send($url,$data),true);
		return $result;
	}

	/**
	 * @return 修改模版
	 * @param  int    $tpl_id  模板id，需要使用已审核通过的模板id
	 * @param  str    $content 模板内容，必须以带符号【】的签名开头,如：【淘玉】您的验证码是#code#
	 * @return array  $result  数组形式返回值
	 */
	public function tpl_update($tpl_id,$content){
		$data['apikey'] = $this->apikey;
		$data['tpl_id'] = $tpl_id;
		$data['tpl_content'] = $content;
		$url = 'v2/tpl/update.json';
		$result = json_decode($this->send($url,$data),true);
		return $result;
	}

	/**
	 * @return 删除模版
	 * @param  int    $tpl_id  模板id，需要使用已审核通过的模板id
	 * @return array  $result  数组形式返回值
	 */
	public function tpl_del($tpl_id){
		$data['apikey'] = $this->apikey;
		$data['tpl_id'] = $tpl_id;
		$url = 'v2/tpl/del.json';
		$result = json_decode($this->send($url,$data),true);
		return $result;
	}



	/**
	 * @return 向云片国内短信API发送请求，请求类型为普通post请求
	 * @param  str    $url  请求地址，不带域名
	 * @param  $data  array 请求参数
	 * @return array  数组形式返回值
	 */
	public function send($url,$data) {
		$ch = curl_init();
		/* 设置验证方式 */
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8',
		    'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'));
		/* 设置返回结果为流 */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		/* 设置超时时间*/
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		/* 设置通信方式 */
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL,'https://sms.yunpian.com/'.$url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	    $result = curl_exec($ch);
        $error = curl_error($ch);
	    if($result === false){
	        return 'Curl error: ' . $error;
	    } else {
	        return $result;
	    }
	}


}