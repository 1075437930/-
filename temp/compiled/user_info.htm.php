

<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/region.js"></script>

<div class="main-div">



<form method="post" action="index.php" name="theForm" onsubmit="return validate()" enctype="multipart/form-data">



<table width="100%" >

  <tr>

    <td class="label"><?php echo self::$_var['lang']['username']; ?>:</td>

    <td>
      <?php if (self::$_var['form_op'] == "update"): ?>
        <?php echo self::$_var['user']['alias']; ?>
        <input type="hidden" name="username" value="<?php echo self::$_var['user']['user_name']; ?>" />
      <?php else: ?>
        <input type="text" name="username" maxlength="60" value="<?php echo self::$_var['user']['user_name']; ?>" />
        <?php echo self::$_var['lang']['require_field']; ?>
      <?php endif; ?>
    </td>

  </tr>

  <?php if (self::$_var['form_action'] == "update"): ?>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['user_money']; ?>:</td>

    <td><?php echo self::$_var['user']['formated_user_money']; ?> <a href="account_log.php?act=list&user_id=<?php echo self::$_var['user']['user_id']; ?>&account_type=user_money">[ <?php echo self::$_var['lang']['view_detail_account']; ?> ]</a> </td>

  </tr>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['frozen_money']; ?>:</td>

    <td><?php echo self::$_var['user']['formated_frozen_money']; ?> <a href="account_log.php?act=list&user_id=<?php echo self::$_var['user']['user_id']; ?>&account_type=frozen_money">[ <?php echo self::$_var['lang']['view_detail_account']; ?> ]</a> </td>

  </tr>

  <tr>

    <td class="label"><a href="javascript:showNotice('noticeRankPoints');" title="<?php echo self::$_var['lang']['form_notice']; ?>"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>"></a> <?php echo self::$_var['lang']['rank_points']; ?>:</td>

    <td><?php echo self::$_var['user']['rank_points']; ?> <a href="account_log.php?act=list&user_id=<?php echo self::$_var['user']['user_id']; ?>&account_type=rank_points">[ <?php echo self::$_var['lang']['view_detail_account']; ?> ]</a> <br /><span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeRankPoints"><?php echo self::$_var['lang']['notice_rank_points']; ?></span></td>

  </tr>

  <tr>

    <td class="label"><a href="javascript:showNotice('noticePayPoints');" title="<?php echo self::$_var['lang']['form_notice']; ?>"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>" /></a> <?php echo self::$_var['lang']['pay_points']; ?>:</td>

    <td><?php echo self::$_var['user']['pay_points']; ?> <a href="account_log.php?act=list&user_id=<?php echo self::$_var['user']['user_id']; ?>&account_type=pay_points">[ <?php echo self::$_var['lang']['view_detail_account']; ?> ]</a> <br />

        <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticePayPoints"><?php echo self::$_var['lang']['notice_pay_points']; ?></span></td>

  </tr>

  <tr>
    <td class="label"><?php echo self::$_var['lang']['mobile_phone']; ?>:</td>
    <td>
      <span id="mobile_phone_mi"><?php echo self::$_var['user']['mobile_phone_mi']; ?><a href="javascript:" onclick="checked_look_phone()">点击查看</a></span>
      <input type="text" id="mobile_phone" style="display: none;"  maxlength="60" size="40" value="<?php echo self::$_var['user']['mobile_phone']; ?>" />
      <?php echo self::$_var['lang']['require_field']; ?>
    </td>
  </tr>

  <?php endif; ?>
<!--
  <tr>

    <td class="label"><?php echo self::$_var['lang']['email']; ?>:</td>

    <td><input type="text" id="email" name="email" maxlength="60" size="40" value="<?php echo self::$_var['user']['email']; ?>" /><?php echo self::$_var['lang']['require_field']; ?></td>

  </tr>-->

  

  <?php if (self::$_var['form_op'] == "insert"): ?>
  <tr>
    <td class="label"><?php echo self::$_var['lang']['mobile_phone']; ?>:</td>
    <td>
      <input type="text" id="mobile_phone"  name="mobile_phone" maxlength="60" size="40" /><?php echo self::$_var['lang']['require_field']; ?>
    </td>
  </tr>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['password']; ?>:</td>

    <td><input type="password" name="password" maxlength="20" size="20" /><?php echo self::$_var['lang']['require_field']; ?></td>

  </tr>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['confirm_password']; ?>:</td>

    <td><input type="password" name="confirm_password" maxlength="20" size="20" /><?php echo self::$_var['lang']['require_field']; ?></td>

  </tr>

  <?php elseif (self::$_var['form_action'] == "update"): ?>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['newpass']; ?>:</td>

    <td><input type="password" name="password" maxlength="20" size="20" /></td>

  </tr>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['confirm_password']; ?>:</td>

    <td><input type="password" name="confirm_password" maxlength="20" size="20" /></td>

  </tr>

  <?php endif; ?>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['user_rank']; ?>:</td>

    <td><select name="user_rank">

      <option value="0"><?php echo self::$_var['lang']['not_special_rank']; ?></option>

      <?php echo self::html_options(array('options'=>self::$_var['special_ranks'],'selected'=>self::$_var['user']['user_rank'])); ?>

    </select></td>

  </tr>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['gender']; ?>:</td>

    <td><?php echo self::html_radios(array('name'=>'sex','options'=>self::$_var['lang']['sex'],'checked'=>self::$_var['user']['sex'])); ?></td>

  </tr>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['birthday']; ?>:</td>

    <td><?php echo self::html_select_date(array('field_order'=>'YMD','prefix'=>'birthday','time'=>self::$_var['user']['birthday'],'start_year'=>'-60','end_year'=>'+1','display_days'=>'true','month_format'=>'%m')); ?></td>

  </tr>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['credit_line']; ?>:</td>

    <td><input name="credit_line" type="text" id="credit_line" value="<?php echo self::$_var['user']['credit_line']; ?>" size="10" /></td>

  </tr>

  <?php $_from = self::$_var['extend_info_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'field');if (count($_from)):
    foreach ($_from AS self::$_var['field']):
?>

  <?php if (self::$_var['field']['reg_field_name'] != '验证手机'): ?>

  <tr>

    <td class="label"><?php echo self::$_var['field']['reg_field_name']; ?>:</td>

    <td>

    <input name="extend_field<?php echo self::$_var['field']['id']; ?>" type="text" size="40" class="inputBg" value="<?php echo self::$_var['field']['content']; ?>"/>

    </td>

  </tr>

  <?php endif; ?>

  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  

  <tr>

  	<td class="label">真实姓名:</td>

    <td><input type="text" name="real_name" size="40" class="inputBg" value="<?php echo self::$_var['user']['real_name']; ?>"/></td>

  </tr>

  <tr>

  	<td class="label">身份证号:</td>

    <td><input type="text" name="card" size="40" class="inputBg" value="<?php echo self::$_var['user']['card']; ?>"/></td>

  </tr>

  <tr>

  	<td class="label">身份证正面:</td>

    <td>

    <input type="file" name="face_card"/><br />

    <div style="padding:10px 0px">

    <?php if (self::$_var['user']['face_card'] != ''): ?><img src="<?php echo self::$_var['user']['face_card']; ?>" width="100" height="100" /><?php else: ?>暂无<?php endif; ?>

    </div>

    </td>

  </tr>

  <tr>

  	<td class="label">身份证反面:</td>

    <td>

    <input type="file" name="back_card" /><br />

    <div style="padding:10px 0px">

    <?php if (self::$_var['user']['back_card'] != ''): ?><img src="<?php echo self::$_var['user']['back_card']; ?>" width="100" height="100" /><?php else: ?>暂无<?php endif; ?>

    </div>

    </td>

  </tr>

  <tr>

  	<td class="label">现居地:</td>

    <td colspan="3"><select name="country" id="selCountries" onchange="region.changed(this, 1, 'selProvinces')">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
          <?php $_from = self::$_var['country_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'country');if (count($_from)):
    foreach ($_from AS self::$_var['country']):
?>
          <?php if (self::$_var['country']['parent_id'] == 0): ?><option value="<?php echo self::$_var['country']['region_id']; ?>"><?php echo self::$_var['country']['region_name']; ?></option><?php endif; ?>
          <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
      </select>
        <select name="province" id="selProvinces" onchange="region.changed(this, 2, 'selCities')">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        </select>
        <select name="city" id="selCities" onchange="region.changed(this, 3, 'selDistricts')">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        </select>
        <select name="district" id="selDistricts">
          <option value="0"><?php echo self::$_var['lang']['select_please']; ?></option>
        </select></td>

  </tr>

  <tr>

  	<td class="label">详细地址:</td>

    <td><input type="text" name="address" value="<?php echo self::$_var['user']['address']; ?>" /></td>

  </tr>

  

  <tr>

  	<td class="label">审核状态:</td>

    <td>

    	<select name="status">

        	<option value="0" <?php if (self::$_var['user']['status'] == 0): ?> selected="selected"<?php endif; ?>>请选择审核状态</option>

            <option value="1" <?php if (self::$_var['user']['status'] == 1): ?> selected="selected"<?php endif; ?>>审核通过</option>

            <option value="2" <?php if (self::$_var['user']['status'] == 2): ?> selected="selected"<?php endif; ?>>审核中</option>

            <option value="3" <?php if (self::$_var['user']['status'] == 3): ?> selected="selected"<?php endif; ?>>审核不通过</option>      

        </select>

    </td>

  </tr>

  

  <?php if (self::$_var['user']['parent_id']): ?>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['parent_user']; ?>:</td>

    <td><a href="index.php?act=users&op=edit&id=<?php echo self::$_var['user']['parent_id']; ?>"><?php echo self::$_var['user']['parent_username']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?act=users&op=remove_parent&id=<?php echo self::$_var['user']['user_id']; ?>"><?php echo self::$_var['lang']['parent_remove']; ?></a></td>

  </tr>

  <?php endif; ?>

  <?php if (self::$_var['affiliate']['on'] == 1 && self::$_var['affdb']): ?>

  <tr>

    <td class="label"><?php echo self::$_var['lang']['affiliate_user']; ?>:</td>

    <td>[<a href="index.php?act=users&op=aff_list&auid=<?php echo self::$_var['user']['user_id']; ?>"><?php echo self::$_var['lang']['show_affiliate_users']; ?></a>][<a href="affiliate_ck.php?act=list&auid=<?php echo self::$_var['user']['user_id']; ?>"><?php echo self::$_var['lang']['show_affiliate_orders']; ?></a>]</td>

  </tr>

  <tr>

    <td></td>

    <td>   

    <table border="0" cellspacing="1" style="background: #dddddd; width:30%;">

    <tr>

    <td bgcolor="#ffffff"><?php echo self::$_var['lang']['affiliate_lever']; ?></td>

    <?php $_from = self::$_var['affdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('level', 'val0');if (count($_from)):
    foreach ($_from AS self::$_var['level'] => self::$_var['val0']):
?>

    <td bgcolor="#ffffff"><?php echo self::$_var['level']; ?></td>

    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

    </tr>

    <tr>

    <td bgcolor="#ffffff"><?php echo self::$_var['lang']['affiliate_num']; ?></td>

    <?php $_from = self::$_var['affdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['val']):
?>

    <td bgcolor="#ffffff"><?php echo self::$_var['val']['num']; ?></td>

    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

    </tr>

    </table>

    </td>

  </tr>

  <?php endif; ?>

  <tr>

    <td colspan="2" align="center">

      <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" />

      <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />

      <input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
      
      <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />

      <input type="hidden" name="id" value="<?php echo self::$_var['user']['user_id']; ?>" />    </td>

  </tr>

</table>



</form>

</div>

<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>



<script language="JavaScript">

region.isAdmin = false;

if (document.forms['theForm'].elements['op'].value == "insert")

{

  document.forms['theForm'].elements['username'].focus();

}



onload = function()

{

    // 开始检查订单

    startCheckOrder();

}



/**

 * 检查表单输入的数据

 */

function validate()

{

    validator = new Validator("theForm");

	if ($.trim($("#mobile_phone").val()).length > 0) {

		validator.isMobile("mobile_phone", invalid_mobile_phone, true);

	}

	if ($.trim($("#mobile_phone").val()).length == 0) {

		alert("手机号码不能为空！");

		return false;

	}

	

    if (document.forms['theForm'].elements['op'].value == "insert")

    {

        validator.required("username",  no_username);

        validator.required("password", no_password);

        validator.required("confirm_password", no_confirm_password);

        validator.eqaul("password", "confirm_password", password_not_same);



        var password_value = document.forms['theForm'].elements['password'].value;

        if (password_value.length < 6)

        {

          validator.addErrorMsg(less_password);

        }

        if (/ /.test(password_value) == true)

        {

          validator.addErrorMsg(passwd_balnk);

        }

    }

    else if (document.forms['theForm'].elements['op'].value == "update")

    {

        var newpass = document.forms['theForm'].elements['password'];

        var confirm_password = document.forms['theForm'].elements['confirm_password'];

        if(newpass.value.length > 0 || confirm_password.value.length)

        {

          if(newpass.value.length >= 6 || confirm_password.value.length >= 6)

          {

            validator.eqaul("password", "confirm_password", password_not_same);

          }

          else

          {

            validator.addErrorMsg(password_len_err);

          }

        }

    }



    return validator.passed();

}



//点击查看手机号
function checked_look_phone(){
   $.ajax({
      type: "post",
      url: "custom_common.php?act=check",
      data: {},
      dataType: "json",
      success: function(data){
        if(data.status == 1){
          $("#mobile_phone").css("display",'block');
          $("#mobile_phone").attr("name",'mobile_phone');
          $("#mobile_phone_mi").css("display",'none');
        }else{
          alert("你没有权限");
        }
      }
    });
}


</script>



<?php echo self::fetch('pagefooter.htm'); ?>

