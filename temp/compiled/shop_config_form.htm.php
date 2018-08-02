      <tr id="<?php echo self::$_var['var']['code']; ?>" <?php if (self::$_var['var']['code'] == 'sms_deposit_balance_reduce_tpl' || self::$_var['var']['code'] == 'sms_recharge_balance_add_tpl' || self::$_var['var']['code'] == 'sms_admin_operation_tpl' || self::$_var['var']['code'] == 'sms_return_goods_tpl'): ?> style="display:none"<?php endif; ?>>

        <td class="label" valign="top">

          <?php if (self::$_var['var']['desc']): ?>

          <a href="javascript:showNotice('notice<?php echo self::$_var['var']['code']; ?>');" title="<?php echo self::$_var['lang']['form_notice']; ?>"><img src="templates/default/images/notice.gif" width="16" height="16" border="0" alt="<?php echo self::$_var['lang']['form_notice']; ?>" /></a>

          <?php endif; ?>

          <?php echo self::$_var['var']['name']; ?>

        </td>

        <td>

          <?php if (self::$_var['var']['type'] == "text"): ?>

          <input name="value[<?php echo self::$_var['var']['id']; ?>]" type="text" value="<?php echo self::$_var['var']['value']; ?>" size="40"/>



          <?php elseif (self::$_var['var']['type'] == "password"): ?>

          <input name="value[<?php echo self::$_var['var']['id']; ?>]" type="password" value="<?php echo self::$_var['var']['value']; ?>" size="40" />



          <?php elseif (self::$_var['var']['type'] == "textarea"): ?>

          <textarea name="value[<?php echo self::$_var['var']['id']; ?>]" cols="40" rows="5"><?php echo self::$_var['var']['value']; ?></textarea>

          <!--<textarea name="value[<?php echo self::$_var['var']['id']; ?>]" cols="40" rows="5"><?php echo self::$_var['var']['value']; ?></textarea>-->



          <?php elseif (self::$_var['var']['type'] == "select"): ?>

          <?php $_from = self::$_var['var']['store_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('k', 'opt');if (count($_from)):
    foreach ($_from AS self::$_var['k'] => self::$_var['opt']):
?>

          <label for="value_<?php echo self::$_var['var']['id']; ?>_<?php echo self::$_var['k']; ?>"><input type="radio" name="value[<?php echo self::$_var['var']['id']; ?>]" id="value_<?php echo self::$_var['var']['id']; ?>_<?php echo self::$_var['k']; ?>" value="<?php echo self::$_var['opt']; ?>"

            <?php if (self::$_var['var']['value'] == self::$_var['opt']): ?>checked="true"<?php endif; ?>

            <?php if (self::$_var['var']['code'] == 'rewrite'): ?>

              onclick="return ReWriterConfirm(this);"

            <?php endif; ?>

            <?php if (self::$_var['var']['code'] == 'smtp_ssl' && self::$_var['opt'] == 1): ?>

              onclick="return confirm('<?php echo self::$_var['lang']['smtp_ssl_confirm']; ?>');"

            <?php endif; ?>

            <?php if (self::$_var['var']['code'] == 'enable_gzip' && self::$_var['opt'] == 1): ?>

              onclick="return confirm('<?php echo self::$_var['lang']['gzip_confirm']; ?>');"

            <?php endif; ?>

            <?php if (self::$_var['var']['code'] == 'retain_original_img' && self::$_var['opt'] == 0): ?>

              onclick="return confirm('<?php echo self::$_var['lang']['retain_original_confirm']; ?>');"

            <?php endif; ?>

          /><?php echo self::$_var['var']['display_options'][self::$_var['k']]; ?></label>

          <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

		  <?php if (self::$_var['var']['code'] == 'sms_user_money_change'): ?>

          	<select onchange="change_tpl(this.value)">

            	<option value="sms_use_balance_reduce_tpl"><?php echo self::$_var['lang']['use_balance_reduce']; ?></option>

                <option value="sms_deposit_balance_reduce_tpl"><?php echo self::$_var['lang']['deposit_balance_reduce']; ?></option>

                <option value="sms_recharge_balance_add_tpl"><?php echo self::$_var['lang']['recharge_balance_add']; ?></option>

                <option value="sms_admin_operation_tpl"><?php echo self::$_var['lang']['admin_operation']; ?></option>

                <option value="sms_return_goods_tpl"><?php echo self::$_var['lang']['return_goods']; ?></option> 

            </select>

          <?php endif; ?>

          <?php elseif (self::$_var['var']['type'] == "options"): ?>

          <select name="value[<?php echo self::$_var['var']['id']; ?>]" id="value_<?php echo self::$_var['var']['id']; ?>_<?php echo self::$_var['key']; ?>">

            <?php echo self::html_options(array('options'=>self::$_var['lang']['cfg_range'][self::$_var['var']['code']],'selected'=>self::$_var['var']['value'])); ?>

          </select>



          <?php elseif (self::$_var['var']['type'] == "file"): ?>

          <input name="<?php echo self::$_var['var']['code']; ?>" type="file" size="40" />

          

           

          <?php if (( self::$_var['var']['code'] == "shop_logo" || self::$_var['var']['code'] == "no_picture" || self::$_var['var']['code'] == "watermark" || self::$_var['var']['code'] == "shop_slagon" || self::$_var['var']['code'] == "wap_logo" || self::$_var['var']['code'] == "erweima_logo" ) && self::$_var['var']['value']): ?>

		  

            <a href="index.php?act=shopconfig&op=del&code=<?php echo self::$_var['var']['code']; ?>"><img src="templates/default/images/no.gif" alt="Delete" border="0" /></a> <img src="templates/default/images/yes.gif" border="0" onmouseover="showImg('<?php echo self::$_var['var']['code']; ?>_layer', 'show')" onmouseout="showImg('<?php echo self::$_var['var']['code']; ?>_layer', 'hide')" />

            <div id="<?php echo self::$_var['var']['code']; ?>_layer" style="position:absolute; width:100px; height:100px; z-index:1; visibility:hidden" border="1">

              <img src="<?php echo self::$_var['var']['value']; ?>" border="0" />

            </div>

          <?php else: ?>

            <?php if (self::$_var['var']['value'] != ""): ?>

            <img src="templates/default/images/yes.gif" alt="yes" />

            <?php else: ?>

            <img src="templates/default/images/no.gif" alt="no" />

            <?php endif; ?>

          <?php endif; ?>

          <?php elseif (self::$_var['var']['type'] == "manual"): ?>



            <?php if (self::$_var['var']['code'] == "shop_country"): ?>

              <select name="value[<?php echo self::$_var['var']['id']; ?>]" id="selCountries" onchange="region.changed(this, 1, 'selProvinces')">

                <option value=''><?php echo self::$_var['lang']['select_please']; ?></option>

                <?php $_from = self::$_var['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'region');if (count($_from)):
    foreach ($_from AS self::$_var['region']):
?>

                  <option value="<?php echo self::$_var['region']['region_id']; ?>" <?php if (self::$_var['region']['region_id'] == self::$_var['cfg']['shop_country']): ?>selected<?php endif; ?>><?php echo self::$_var['region']['region_name']; ?></option>

                <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

              </select>

                  <?php elseif (self::$_var['var']['code'] == "shop_province"): ?>

              <select name="value[<?php echo self::$_var['var']['id']; ?>]" id="selProvinces" onchange="region.changed(this, 2, 'selCities')">

                <option value=''><?php echo self::$_var['lang']['select_please']; ?></option>

                <?php $_from = self::$_var['provinces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'region');if (count($_from)):
    foreach ($_from AS self::$_var['region']):
?>

                  <option value="<?php echo self::$_var['region']['region_id']; ?>" <?php if (self::$_var['region']['region_id'] == self::$_var['cfg']['shop_province']): ?>selected<?php endif; ?>><?php echo self::$_var['region']['region_name']; ?></option>

                <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

              </select>

            <?php elseif (self::$_var['var']['code'] == "shop_city"): ?>

              <select name="value[<?php echo self::$_var['var']['id']; ?>]" id="selCities">

                <option value=''><?php echo self::$_var['lang']['select_please']; ?></option>

                <?php $_from = self::$_var['cities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'region');if (count($_from)):
    foreach ($_from AS self::$_var['region']):
?>

                  <option value="<?php echo self::$_var['region']['region_id']; ?>" <?php if (self::$_var['region']['region_id'] == self::$_var['cfg']['shop_city']): ?>selected<?php endif; ?>><?php echo self::$_var['region']['region_name']; ?></option>

                <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

              </select>

            <?php elseif (self::$_var['var']['code'] == "lang"): ?>

                  <select name="value[<?php echo self::$_var['var']['id']; ?>]">

                  <?php echo self::html_options(array('values'=>self::$_var['lang_list'],'output'=>self::$_var['lang_list'],'selected'=>self::$_var['var']['value'])); ?>

                  </select>

            <?php elseif (self::$_var['var']['code'] == "invoice_type"): ?>

            <table>

              <tr>

                <th scope="col"><?php echo self::$_var['lang']['invoice_type']; ?></th>

                <th scope="col"><?php echo self::$_var['lang']['invoice_rate']; ?></th>

              </tr>

              

              <tr>

                <td>

                  <input type='hidden' name='invoice_enable[0]' value='0'/>

                  <?php if (self::$_var['cfg']['invoice_type']['enable'] [ 0 ] == '1'): ?>

                  <input id='invoice_type0' name="invoice_enable[0]" type="checkbox" value='1' checked=''/>

                  <?php else: ?>

                  <input id='invoice_type0' name="invoice_enable[0]" type="checkbox" value='1' />

                  <?php endif; ?>

                  <label for='invoice_type0'><?php echo self::$_var['lang'][self::$_var['cfg']['invoice_type']['type']['0']]; ?></label>

                  <input name='invoice_type[]' type='hidden' value='<?php echo self::$_var['cfg']['invoice_type']['type']['0']; ?>'/>

                </td>

                <td><input name="invoice_rate[]" type="text" value="<?php echo self::$_var['cfg']['invoice_type']['rate']['0']; ?>" /></td>

              </tr>

              <tr>

                <td>

                  <input type='hidden' name='invoice_enable[1]' value='0'/>

                  <?php if (self::$_var['cfg']['invoice_type']['enable'] [ 1 ] == '1'): ?>

                  <input id='invoice_type1' name="invoice_enable[1]" type="checkbox" value='1' checked=''/>

                  <?php else: ?>

                  <input id='invoice_type1' name="invoice_enable[1]" type="checkbox" value='1' />

                  <?php endif; ?>

                  <label for='invoice_type1'><?php echo self::$_var['lang'][self::$_var['cfg']['invoice_type']['type']['1']]; ?></label>

                  <input name='invoice_type[]' type='hidden' value='<?php echo self::$_var['cfg']['invoice_type']['type']['1']; ?>'/>

                </td>

                <td><input name="invoice_rate[]" type="text" value="<?php echo self::$_var['cfg']['invoice_type']['rate']['1']; ?>" /></td>

              </tr>

			  

            </table>

            <?php endif; ?>

          <?php endif; ?>

          <?php if (self::$_var['var']['desc']): ?>

          <br />

          <span class="notice-span" <?php if (self::$_var['help_open']): ?>style="display:block;<?php if (self::$_var['var']['code'] == 'sms_sign'): ?>color:red;<?php endif; ?>" <?php else: ?> style="display:none" <?php endif; ?> id="notice<?php echo self::$_var['var']['code']; ?>"><?php echo nl2br(self::$_var['var']['desc']); ?></span>

          <?php endif; ?>

        </td>

      </tr>

