<?php echo self::fetch('pageheader.htm'); ?>
<form method="POST" action="index.php" name="theForm"  onsubmit="return validate()">
<div class="list-div">
<table width="100%">
  <tr>
    <td class="label"><?php echo self::$_var['lang']['user_name']; ?></td>
    <td>
      <input type="text" name="user_name" maxlength="20" value="<?php echo htmlspecialchars(self::$_var['user']['role_name']); ?>" size="34"/><?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label"><?php echo self::$_var['lang']['role_describe']; ?></td>
    <td>
    <textarea name="role_describe" cols="31" rows="6"><?php echo self::$_var['user']['role_describe']; ?></textarea>
<?php echo self::$_var['lang']['require_field']; ?></td>
  </tr>
  </table>
<table cellspacing='1' id="list-table">
<?php $_from = self::$_var['priv_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'priv');if (count($_from)):
    foreach ($_from AS self::$_var['priv']):
?>
 <tr>
  <td width="18%" valign="top" class="first-cell">
    <input name="chkGroup" type="checkbox" value="checkbox" onclick="check('<?php echo self::$_var['priv']['priv_list']; ?>',this);" class="checkbox"><?php echo self::$_var['lang'][self::$_var['priv']['action_code']]; ?>
  </td>
  <td>
    <?php $_from = self::$_var['priv']['priv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('priv_list', 'list');if (count($_from)):
    foreach ($_from AS self::$_var['priv_list'] => self::$_var['list']):
?>
    <div style="width:200px;float:left;">
    <label for="<?php echo self::$_var['priv_list']; ?>"><input type="checkbox" name="action_code[]" value="<?php echo self::$_var['priv_list']; ?>" id="<?php echo self::$_var['priv_list']; ?>" class="checkbox" <?php if (self::$_var['list']['cando'] == 1): ?> checked="true" <?php endif; ?> onclick="checkrelevance('<?php echo self::$_var['list']['relevance']; ?>', '<?php echo self::$_var['priv_list']; ?>')" title="<?php echo self::$_var['list']['relevance']; ?>"/>
    <?php echo self::$_var['lang'][self::$_var['list']['action_code']]; ?></label>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</td></tr>
<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
  <tr>
    <td align="center" colspan="2" >
      <input type="checkbox" name="checkall" value="checkbox" onclick="checkAll(this.form, this);" class="checkbox" /><?php echo self::$_var['lang']['check_all']; ?>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit"   name="Submit"   value="<?php echo self::$_var['lang']['button_save']; ?>" class="button" />&nbsp;&nbsp;&nbsp;
      <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
      <input type="hidden"   name="id"    value="<?php echo self::$_var['user_id']; ?>" />
      <input type="hidden"   name="act"   value="<?php echo self::$_var['form_act']; ?>" />
      <input type="hidden"   name="op"   value="<?php echo self::$_var['form_op']; ?>" />
    </td>
  </tr>
</table>
</div>
</form>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/validator.js')); ?>

<script language="javascript">
//表单效验
function validate(){
    validator = new Validator("theForm");
	validator.required("user_name",  "角色名称为空!");
	return validator.passed();
}
function checkAll(frm, checkbox)
{
  for (i = 0; i < frm.elements.length; i++)
  {
    if (frm.elements[i].name == 'action_code[]' || frm.elements[i].name == 'chkGroup')
    {
      frm.elements[i].checked = checkbox.checked;
    }
  }
}

function check(list, obj)
{
  var frm = obj.form;

    for (i = 0; i < frm.elements.length; i++)
    {
      if (frm.elements[i].name == "action_code[]")
      {
          var regx = new RegExp(frm.elements[i].value + "(?!_)", "i");

          if (list.search(regx) > -1) {frm.elements[i].checked = obj.checked;}
      }
    }
}

function checkrelevance(relevance, priv_list)
{
  if(document.getElementById(priv_list).checked && relevance != '')
  {
    document.getElementById(relevance).checked=true;
  }
  else
  {
    var ts=document.getElementsByTagName("input");
    
    for (var i=0; i<ts.length;i++)
    {
      var text=ts[i].getAttribute("title");

      if( text == priv_list) 
      {
        document.getElementById(ts[i].value).checked = false;
      }
    }
  }
}
</script>

<?php echo self::fetch('pagefooter.htm'); ?>
