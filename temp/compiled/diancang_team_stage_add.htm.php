
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/validator.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/transport.org.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/My97DatePicker/WdatePicker.js"></script>
<script language="JavaScript">
<!--

// 这里把JS用到的所有语言都赋值到这里

<?php $_from = self::$_var['lang']['calendar_lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

var <?php echo self::$_var['key']; ?> = "<?php echo self::$_var['item']; ?>";

<?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

//-->
</script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.js"></script>
<link href="<?php echo self::$_var['urls_dir']; ?>/js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="main-div">
    <form method="post" action="index.php" name="theForm"  onSubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">期号：</td>
                <td>
                    <input name="stage_sn" type="text" id="stage_sn" readonly value="<?php echo self::$_var['stage']['stage_sn']; ?>" maxlength="60" />
                </td>
            </tr>
            <tr>
                <td class="label">团队额外奖励天数：</td>
                <td><input name="team_reward_day" type="text" id="team_reward_day" value="<?php echo self::$_var['stage']['team_reward_day']; ?>" /></td>
            </tr>
            <tr>
                <td class="label">团队总金额上限：</td>
                <td><input name="team_money_limit" type="text" id="team_money_limit" value="<?php echo self::$_var['stage']['team_money_limit']; ?>" /></td>
            </tr>
             <tr>
                <td class="label">团队总人数上限：</td>
                <td><input name="team_person_limit" type="text" id="team_person_limit" value="<?php echo self::$_var['stage']['team_person_limit']; ?>" /></td>
            </tr>
            <tr>
                <td class="label">队长额外奖励百分比：</td>
                <td><input name="captain_radio" type="text" id="captain_radio" value="<?php echo self::$_var['stage']['captain_radio']; ?>" /></td>
            </tr>
            <tr>
                <td class="label">返现天数：</td>
                <td><input name="back_day" type="text" id="back_day" value="<?php echo self::$_var['stage']['back_day']; ?>" /></td>
            </tr>
            <tr>
                <td class="label">开始时间：</td>
                <td>
                    <input name="start_time" type="text" id="start_time" size="25" value='<?php echo self::$_var['stage']['start_time']; ?>' readonly="readonly" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" />
                    -
                    <input name="end_time" type="text" id="end_time" size="25" value='<?php echo self::$_var['stage']['end_time']; ?>' readonly="readonly" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" />
                </td>
            </tr>
            <tr>
                <td class="label">总金额对应年化比：</td>
                <td>
                    <div id="radio_list">
                        <?php $_from = self::$_var['radio_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'radio');if (count($_from)):
    foreach ($_from AS self::$_var['radio']):
?>
                            <input name="extra[]" type="text"  size="12" value='<?php echo self::$_var['radio']; ?>'/><br/>
                        <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
                    </div>
                    <input type="button" value="添加" onclick="addExtra()" class="button" />
                    <span style="color:red;">格式：0|1|0.3 &nbsp;&nbsp;   0到1万(含)，年化率0.3%;;最大值时，120|0|2 ，超过120W（包含），年化率2%</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" class="button" value="<?php echo self::$_var['lang']['button_submit']; ?>" />
                    <input type="reset" class="button" value="<?php echo self::$_var['lang']['button_reset']; ?>" />
                    <input type="hidden" name="act" value="dcgroup" />
                    <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />
                    <input type="hidden" name="stage_id" value="<?php echo self::$_var['stage']['id']; ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>


<script language="JavaScript">
<!--
    var goodsstyle = 0;
    /**
     * 检查表单输入的数据
     */
    function validate()
    {
        validator = new Validator("theForm");
        validator.required('team_reward_day', '团队额外奖励天数不能为空');
        validator.required('team_money_limit', '团队总金额上限不能为空');
        validator.required('back_day', '返现天数不能为空');
        validator.required('start_time', '开始时间不能为空');
        validator.required('end_time', '结束时间不能为空');
        validator.required('back_day', '返现天数不能为空');
        return validator.passed();
    }
    
  

//-->

 

</script>


<script language="JavaScript">
     /**
   * 添加扩展分类
   */
  function addExtra()
  {      
    var sel=  '<input name="extra[]" type="text"  size="25" value=""><br/>';
    document.getElementById('radio_list').innerHTML = document.getElementById('radio_list').innerHTML+sel;    
  }
</script>
<?php echo self::fetch('pagefooter.htm'); ?>
