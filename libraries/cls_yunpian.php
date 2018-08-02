<?php
/**
 * 淘玉php 云片短信
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: wang $
 * 云片短信
 * $Id: cls_yunpian.php 2018-06-22 08:40:32 wang $
 */
defined('TaoyuShop') or exit('Access Invalid!tpl');
include_once(BASE_PATH .'/api/yunpian_sdk/vendor/autoload.php');
use \Yunpian\Sdk\YunpianClient;
class Yunpian {
	/*初始化短信发送客户端*/
	protected static $clnt;

	public function __construct(){
		self::$clnt = YunpianClient::create($apikey);
	}

	/*短信方法*/

	/**
	 * @return 指定模板单发
	 * @param  int    $tpl_id  模板id，需要使用已审核通过的模板id
	 * @param  array  $param   短信模板参数，例如：$param['number'] = 1234 ;
	 * @param  str    $mobile  接收的手机号,仅支持单号码发送
	 * @return array           一维数组形式返回值
	 * 返回值说明：
	 * 请求成功，返回码code=0，msg提示信息（所有发送短信方法，请求成功只代表短信发送成功，用户是否接收成功需要查看接收状态）
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg提示信息
	 */
	public function tpl_single_send($tpl_id,$param,$mobile){
		if(!empty($param)){
			$tpl_value = '';
			foreach ($param as $key => $value) {
				$tpl_value.=urlencode("#$key#").'='.urlencode($value).'&';
			}
			$param = [YunpianClient::MOBILE => $mobile,YunpianClient::TPL_ID => $tpl_id,
				YunpianClient::TPL_VALUE => rtrim($tpl_value,'&')];
			$result = self::$clnt->sms()->tpl_single_send($param);
			$code = $result->code();
			$msg = $result->msg();
			return array('code'=>$code,'msg'=>$msg);
        } else {
			return array('code'=>2,'msg'=>'短信模板参数不能空');
		}		 
	}

	/**
	 * @return 指定模板群发
	 * @param  int    $tpl_id  模板id，需要使用已审核通过的模板id
	 * @param  array  $param   短信模板参数，例如：$param['number'] = 1234 ;
	 * @param  str    $mobile  接收的手机号,多个手机号用英文逗号隔开
	 * @return array           二维数组形式返回值，一个手机号一个数组
	 * 返回值说明：
	 * 请求成功，返回码code=0，msg提示信息
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg提示信息
	 */
	public function tpl_batch_send($tpl_id,$param,$mobile){
		if(!empty($param)){
			$tpl_value = '';
			foreach ($param as $key => $value) {
				$tpl_value.=urlencode("#$key#").'='.urlencode($value).'&';
			}
			$param = [YunpianClient::MOBILE => $mobile,YunpianClient::TPL_ID => $tpl_id,
				YunpianClient::TPL_VALUE => rtrim($tpl_value,'&')];
			$result = self::$clnt->sms()->tpl_batch_send($param);		
			$code = $result->code();
			$msg = $result->msg();
			return array('code'=>$code,'msg'=>$msg);
		} else {
			return array('code'=>2,'msg'=>'短信模板参数不能空');
		}				 
	}

	/**
	 * @return 单条短信发送,智能匹配短信模板
	 * @param  str    $param   需要使用已审核通过的模板或者默认模板,例如：'【淘玉】您的验证码是4567'；
	 * @param  str    $mobile  接收的手机号,仅支持单号码发送
	 * @return array           一维数组形式返回值
	 * 返回值说明：
	 * 请求成功，返回码code=0，msg提示信息
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg提示信息
	 */
	public function single_send($param,$mobile){		
        if(!empty($param)){
			$param = [YunpianClient::MOBILE => $mobile,YunpianClient::TEXT => $param];
			$result = self::$clnt->sms()->single_send($param);		
			$code = $result->code();
			$msg = $result->msg();
			return array('code'=>$code,'msg'=>$msg);	
		} else {
			return array('code'=>2,'msg'=>'短信模板参数不能空');
		}
	}

	/**
	 * @return 批量发送相同内容,相同内容多个号码
	 * @param  str    $param   需要使用已审核通过的模板或者默认模板；
	 * @param  str    $mobile  接收的手机号,多个手机号用英文逗号隔开
	 * @return array           二维数组形式返回值，一个手机号一个数组
	 * 返回值说明：
	 * 请求成功，返回码code=0，msg提示信息
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg提示信息
	 */
	public function batch_send($param,$mobile){		
		if(!empty($param)){
			$param = [YunpianClient::MOBILE => $mobile,YunpianClient::TEXT => $param];
			$result = self::$clnt->sms()->batch_send($param);		
			$code = $result->code();
			$msg = $result->msg();
			return array('code'=>$code,'msg'=>$msg);	
		} else {
			return array('code'=>2,'msg'=>'短信模板参数不能空');
		}
	}

	/**
	 * @return 获取短信接收状态
	 * @param  int $page_size  每页个数，最大100个，默认20个
	 * @return array           数组形式返回值
	 * 注意，已成功获取的数据将会删除，请妥善处理接口返回的数据
	 * 返回值说明：
	 * error_detail 	string 	运营商反馈代码的中文解释，仅供参考
	 * sid 	long（64位）  短信id，64位整型， 对应Java和C#的long，不可用int解析
	 * uid 	string  用户自定义id
	 * user_receive_time  string  用户接收时间
	 * error_msg  string  运营商返回的代码，如："DB:0103"
	 * mobile  string  接收手机号
	 * report_status  string 	接收状态有:SUCCESS/FAIL，状态值无需引号
	 */
	public function pull_status($page_size){
		$param = [YunpianClient::PAGE_SIZE => $page_size];
		$result = self::$clnt->sms()->pull_status($param);
		return $result->data();
	}

	/*模板方法*/

	/**
	 * @return 添加模版
	 * @param  str    $content     模板内容，必须以带符号【】的签名开头,如：【淘玉】您的验证码是#code#
	 * @param  int    $tpltype     模板类型，0通知类，1或者模板内容中有'验证码'三个字为验证码类，2营销类
	 * @param  int    $notify_type 审核结果短信通知的方式: 3表示不需要通知,默认; 0表示需要通知; 1表示仅审核不通过时通知; 2表示仅审核通过时通知;
	 * @param  str    $website     验证码类模板对应的官网注册页面（验证码类模板必须有才能通过审核）
	 * @return array               数组形式返回值
	 * 返回值说明：
	 * 请求成功，返回码code=0
	 * tpl_id添加成功后生成的模板id
	 * check_status，新添加模板的审核状态CHECKING/SUCCESS/FAIL
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg错误提示信息
	 */
	public function tpl_add($tpl_content,$tpltype = 0,$notify_type = 3,$website = ''){
		$param = [YunpianClient::TPL_CONTENT => $tpl_content,YunpianClient::TPL_TYPE => $tpltype,
			YunpianClient::NOTIFY_TYPE => $notify_type,YunpianClient::WEBSITE => $website];
		$result = self::$clnt->tpl()->add($param);
		$code = $result->code();
		if($code == 0){
			$data = $result->data();
			return array('code'=>0,'tpl_id'=>$data['tpl_id'],'check_status'=>$data['check_status']);
		} else {
			return array('code'=>$code,'msg'=>$result->detail());
		}
	}

	/**
	 * @return 修改模版
	 * @param  int    $tpl_id      模板id，需要使用已审核通过的模板id
	 * @param  str    $content     模板内容，必须以带符号【】的签名开头,如：【淘玉】您的验证码是#code#
	 * @param  int    $tpltype     模板类型，0通知类，1或者模板内容中有'验证码'三个字为验证码类，2营销类
	 * @param  int    $notify_type 审核结果短信通知的方式: 0表示需要通知,默认; 1表示仅审核不通过时通知; 2表示仅审核通过时通知; 3表示不需要通知
	 * @param  str    $website     验证码类模板对应的官网注册页面（验证码类模板必须有才能通过审核）
	 * @return array               数组形式返回值
	 * 返回值说明：
	 * 请求成功，返回码code=0
	 * tpl_id修改的模板id
	 * check_status，修改后模板重新审核的状态CHECKING/SUCCESS/FAIL
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg错误提示信息
	 */
	public function tpl_update($tpl_id,$tpl_content,$tpltype = 0,$notify_type = 3,$website = ''){
		$param = [YunpianClient::TPL_ID => $tpl_id,YunpianClient::TPL_CONTENT => $tpl_content,
		YunpianClient::TPLTYPE => $tpltype,YunpianClient::NOTIFY_TYPE => $notify_type,YunpianClient::WEBSITE => $website];
		$result = self::$clnt->tpl()->update($param);
		return $result;
		$code = $result->code();
		if($code == 0){
			$data = $result->data();
			return array('code'=>0,'tpl_id'=>$data['tpl_id'],'check_status'=>$data['check_status']);
		} else {
			return array('code'=>$code,'msg'=>$result->detail());
		}
	}

	/**
	 * @return 删除模版
	 * @param  int $tpl_id  模板id，需要使用已审核通过的模板id
	 * @return array        数组形式返回值
	 * 返回值说明：
	 * 请求成功，返回码code=0
	 * tpl_id已经删除的模板id
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg错误提示信息
	 */
	public function tpl_del($tpl_id){
		$param = [YunpianClient::TPL_ID => $tpl_id];
		$result = self::$clnt->tpl()->del($param);
		$code = $result->code();
		if($code == 0){
			$data = $result->data();
			return array('code'=>0,'tpl_id'=>$data['tpl_id']);
		} else {
			return array('code'=>$code,'msg'=>$result->detail());
		}
	}

	/**
	 * @return 获取模版
	 * @param  int $tpl_id  模板id，需要使用已审核通过的模板id。指定id时返回id对应的模板。未指定时返回所有模板
	 * @return array        数组形式返回值
	 */
	public function tpl_get($tpl_id){
		$param = [YunpianClient::TPL_ID => $tpl_id];
		$result = self::$clnt->tpl()->get($param);
		return $result->data();
	}

	/*签名方法*/

	/**
	 * @return 添加签名
	 * @param  str $sign  签名内容，不能包含【】。3-8个字且不能是纯英文和数字（开通“不限签名格式”和“仅发国际短信”的用户不受此限）
	 * @param  bool $notify   	是否短信通知结果，默认false
	 * @param  bool $apply_vip  是否申请专用通道，默认false
	 * @param  bool $is_only_global  是否仅发国际短信，默认false
	 * @param  str $industry_type  所属行业，默认“其它”
	 * @param  str $license_url  签名对应的营业执照或其他企业资质的图片文件URL，默认为空
	 * @return array  数组形式返回值
	 * 返回值说明：
	 * 请求成功，返回码code=0
	 * sign添加的签名的完整内容
	 * apply_state 添加的签名当前审核状态
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg错误提示信息
	 */
	public function sign_add($sign,$notify = false,$apply_vip = false,$is_only_global= false,$industry_type = '其他',$license_url = ''){
		$param = [YunpianClient::SIGN => $sign,YunpianClient::NOTIFY => $notify,YunpianClient::APPLYVIP => $apply_vip,
			YunpianClient::ISONLYGLOBAL => 'false',YunpianClient::INDUSTRYTYPE => '其他',YunpianClient::LICENSE_URL => $license_url];
		$result = self::$clnt->sign()->add($param);
		$code = $result->code();
		if($code == 0){
			$data = $result->data();
			return array('code'=>0,'sign'=>$data['sign'],'apply_state'=>$data['apply_state']);
		} else {
			return array('code'=>$code,'msg'=>$result->detail());
		}
	}

	/**
	 * @return 修改签名
	 * @param  str $old_sign  旧签名内容，指定修改哪个签名，可以加【】也可不加
	 * @param  str $sign  签名内容，不能包含【】。3-8个字且不能是纯英文和数字（开通“不限签名格式”和“仅发国际短信”的用户不受此限）
	 * @param  bool $notify   	是否短信通知结果，默认false
	 * @param  bool $apply_vip  是否申请专用通道，默认false
	 * @param  bool $is_only_global  是否仅发国际短信，默认false
	 * @param  str $industry_type  所属行业，默认“其它”
	 * @param  str $license_url  签名对应的营业执照或其他企业资质的图片文件URL，默认为空
	 * @return array  数组形式返回值
	 * 返回值说明：
	 * 请求成功，返回码code=0
	 * sign修改后签名的完整内容
	 * apply_state 修改后签名当前审核状态
	 * 请求失败，返回码code为0以外的值，具体参考文档返回码说明，msg错误提示信息
	 */
	public function sign_update($old_sign,$sign,$notify = false,$apply_vip = false,$is_only_global= false,$industry_type = '其他',$license_url = ''){
		$param = [YunpianClient::OLD_SIGN => $old_sign,YunpianClient::SIGN => $sign,YunpianClient::NOTIFY => $notify,YunpianClient::APPLYVIP => $apply_vip,
			YunpianClient::ISONLYGLOBAL => 'false',YunpianClient::INDUSTRYTYPE => '其他',YunpianClient::LICENSE_URL => $license_url];
		$result = self::$clnt->sign()->update($param);
		$code = $result->code();
		if($code == 0){
			$data = $result->data();
			return array('code'=>0,'sign'=>$data['sign'],'apply_state'=>$data['apply_state']);
		} else {
			return array('code'=>$code,'msg'=>$result->detail());
		}
	}

	/**
	 * @return 获取签名
	 * @param  str  $keyword    搜索关键字（模糊匹配），若要获取指定签名可加上符号，如【淘玉商城】
	 * @param  bool $page_size  返回条数，必须大于0，默认10条，不带或者格式错误返回全部
	 * 返回值说明：
	 * sign 签名
     * enabled 当前签名是否启用，1是，空不是
     * vip 是否专用通道 ，1是，空不是
     * only_global 是否用于国际短信 ，1是，空不是
     * industry_type 行业 默认'其它'
     * check_status 当前状态,'CHECKING'审核中，'FAIL'审核失败，'SUCCESS'审核成功，'APLLYING_VIP'普通通道审核成功申请升级专用通道中
     * chan  通道类型，'NONE'暂未分配，'GLOBAL'国际短信通道，'MARKET'营销通道，'VIP'专用通道，'NORMAL'普通通道
     * extend 扩展号，为空表示暂未分配 
     * remark 客服给的审核结果解释，一般见于审核失败
	 */
	public function sign_get($keyword,$page_size = 10){
		$param = [YunpianClient::SIGN => $keyword,YunpianClient::PAGE_SIZE => $page_size];
		$result = self::$clnt->sign()->get($param);
		return $result->data();
	}




}