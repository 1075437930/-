

<?php echo self::fetch('pageheader_bd.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/selectzone_bd.js,js/validator.js')); ?>


<div class="tab-div">
  <div id="tabbar-div">
    <p>
      <span class="tab-front" id="general-tab"><?php echo self::$_var['lang']['tab_general']; ?></span><span
      class="tab-back" id="detail-tab"><?php echo self::$_var['lang']['tab_content']; ?></span><span
      class="tab-back" id="goods-tab"><?php echo self::$_var['lang']['tab_goods']; ?></span>
    </p>
  </div>

  <div id="tabbody-div">
    <form  action="index.php" method="post" enctype="multipart/form-data" name="theForm" onsubmit="return validate();">
    <table width="90%" id="general-table">
      <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['title']; ?></td>
        <td><input type="text" name="title" size ="40" maxlength="60" value="<?php echo htmlspecialchars(self::$_var['article']['title']); ?>" /><?php echo self::$_var['lang']['require_field']; ?></td>
      </tr>          
      <?php if (self::$_var['article']['cat_id'] >= 0): ?>
      <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['cat']; ?> </td>
        <td>
          <select name="article_cat" onchange="catChanged()">
            <option value="0"><?php echo self::$_var['lang']['select_plz']; ?></option>
            <?php echo self::$_var['cat_select']; ?>
          </select>
         <?php echo self::$_var['lang']['require_field']; ?></td>
      </tr>
      <?php else: ?>
      <input type="hidden" name="article_cat" value="-1" />
      <?php endif; ?>
      <?php if (self::$_var['article']['cat_id'] >= 0): ?>
      <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['article_type']; ?></td>
        <td>
            <input type="radio" name="article_type" value="0" <?php if (self::$_var['article']['article_type'] == 0): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['common']; ?>
	    <input type="radio" name="article_type" value="1" <?php if (self::$_var['article']['article_type'] == 1): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['top']; ?>
            <input type="radio" name="article_type" value="2" <?php if (self::$_var['article']['article_type'] == 2): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['video']; ?>
        <?php echo self::$_var['lang']['require_field']; ?>        </td>
      </tr>
	  <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['article_hot']; ?></td>
        <td><input type="radio" name="article_hot" value="1" <?php if (self::$_var['article']['article_hot'] == 1): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['shi']; ?>
		<input type="radio" name="article_hot" value="0" <?php if (self::$_var['article']['article_hot'] == 0): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['fou']; ?>
        <?php echo self::$_var['lang']['require_field']; ?>        </td>
      </tr>
	   <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['article_tj']; ?></td>
        <td><input type="radio" name="article_jt" value="1" <?php if (self::$_var['article']['article_jt'] == 1): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['shi']; ?>
		<input type="radio" name="article_jt" value="0" <?php if (self::$_var['article']['article_jt'] == 0): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['fou']; ?>
        <?php echo self::$_var['lang']['require_field']; ?>        </td>
      </tr>
	   <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['article_news']; ?></td>
        <td><input type="radio" name="article_news" value="1" <?php if (self::$_var['article']['article_news'] == 1): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['shi']; ?>
		<input type="radio" name="article_news" value="0" <?php if (self::$_var['article']['article_news'] == 0): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['fou']; ?>
        <?php echo self::$_var['lang']['require_field']; ?>        </td>
      </tr>
      <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['is_open']; ?></td>
        <td>
        <input type="radio" name="is_open" value="1" <?php if (self::$_var['article']['is_open'] == 1): ?>checked<?php endif; ?>> <?php echo self::$_var['lang']['isopen']; ?>
      <input type="radio" name="is_open" value="0" <?php if (self::$_var['article']['is_open'] == 0): ?>checked<?php endif; ?>> <?php echo self::$_var['lang']['isclose']; ?><?php echo self::$_var['lang']['require_field']; ?>        </td>
      </tr>
      <?php else: ?>
      <tr style="display:none">
      <td colspan="2"><input type="hidden" name="article_type" value="0" /><input type="hidden" name="is_open" value="1" /></td>
      </tr>
      <?php endif; ?>
      
      <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['article_ares']; ?></td>
        <td><input type="radio" name="media_type" value="0" <?php if (self::$_var['article']['media_type'] == 0): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['ares_all']; ?>
		<input type="radio" name="media_type" value="1" <?php if (self::$_var['article']['media_type'] == 1): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['ares_app']; ?>
                <input type="radio" name="media_type" value="2" <?php if (self::$_var['article']['media_type'] == 2): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['ares_pc']; ?>
                <input type="radio" name="media_type" value="3" <?php if (self::$_var['article']['media_type'] == 3): ?>checked<?php endif; ?>><?php echo self::$_var['lang']['ares_qita']; ?>
        <?php echo self::$_var['lang']['require_field']; ?>        </td>
      </tr>
      <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['keywords']; ?></td>
        <td><input type="text" name="keywords" maxlength="60" value="<?php echo htmlspecialchars(self::$_var['article']['keywords']); ?>" /></td>
      </tr>
      <tr>
        <td class="narrow-label"><?php echo self::$_var['lang']['lable_description']; ?></td>
        <td><textarea name="description" id="description" cols="40" rows="5"><?php echo htmlspecialchars(self::$_var['article']['description']); ?></textarea></td>
      </tr>
      <tr>
        <td class="narrow-label">视频上传</td>
        <td><input type="file" name="file">
          <span class="narrow-label"><?php echo self::$_var['lang']['file_url']; ?>
          <input name="file_url" type="text" value="<?php echo htmlspecialchars(self::$_var['article']['file_url']); ?>" size="30" maxlength="255" />
          </span></td>
      </tr>
      <tr>
        <td class="narrow-label">视频时长</td>
        <td><input type="text" name="vidoe_times" maxlength="60" value="<?php echo self::$_var['article']['vidoe_times']; ?>" /> 样式 02:23 为2分钟23秒</td>
      </tr>
	  <tr>
        <td class="narrow-label">图片上传</td>
        <td><input type="file" name="img">
          <span class="narrow-label"> 或输入图片地址
          <input name="img_url" type="text" value="<?php echo htmlspecialchars(self::$_var['article']['img_url']); ?>" size="30" maxlength="255" />
          </span></td>
      </tr>
    </table>

    <table width="90%" id="detail-table" style="display:none">
     <tr><td><?php echo self::$_var['FCKeditor']; ?></td></tr>
    </table>

    <table width="90%" id="goods-table" style="display:none">
      
      <tr>
      <td colspan="5">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        
        <input type="text" name="keyword" size="30" />
        <input type="button" value="<?php echo self::$_var['lang']['button_search']; ?>" onclick="searchGoods()" class="button" />
      <td>
      </tr>
      
      <tr>
        <th><?php echo self::$_var['lang']['all_goods']; ?></th>
        <th><?php echo self::$_var['lang']['handler']; ?></th>
        <th><?php echo self::$_var['lang']['send_bouns_goods']; ?></th>
      </tr>
      <tr>
        <td width="45%" align="center">
          <select name="source_select" size="20" style="width:90%" ondblclick="sz.addItem(false,'article', 'add_link_goods', articleId)" multiple="true">
          </select>
        </td>
        <td align="center">
          <p><input type="button" value="&gt;" onclick="sz.addItem(false,'article', 'add_link_goods', <?php echo self::$_var['article']['description']; ?>)" class="button" /></p>
          <p><input type="button" value="&lt;" onclick="sz.dropItem(false,'article', 'drop_link_goods', <?php echo self::$_var['article']['description']; ?>)" class="button" /></p>
        </td>
        <td width="45%" align="center">
          <select name="target_select" multiple="true" size="20" style="width:90%" ondblclick="sz.dropItem(false,'article','drop_link_goods', <?php echo self::$_var['article']['description']; ?>)">
            <?php $_from = self::$_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS self::$_var['goods']):
?>
            <option value="<?php echo self::$_var['goods']['goods_id']; ?>"><?php echo self::$_var['goods']['goods_name']; ?></option>
            <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
          </select>
        </td>
      </tr>
    </table>
    <div class="button-div">
      <input type="hidden" name="act" value="<?php echo self::$_var['form_act']; ?>" />
      <input type="hidden" name="op" value="<?php echo self::$_var['form_op']; ?>" />
      <input type="hidden" name="old_title" value="<?php echo self::$_var['article']['title']; ?>"/>
      <input type="hidden" name="id" value="<?php echo self::$_var['article']['article_id']; ?>" />
      <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button"  />
      <input type="reset" value="<?php echo self::$_var['lang']['button_reset']; ?>" class="button" />
    </div>
    </form>
  </div>

</div>

<script language="JavaScript">

var articleId = <?php echo empty(self::$_var['article']['article_id']) ? '0' : self::$_var['article']['article_id']; ?>;
var elements  = document.forms['theForm'].elements;
var sz        = new SelectZone(1, elements['source_select'], elements['target_select'], '');



function validate()
{
  var validator = new Validator('theForm');
  validator.required('title', no_title);

// <?php if (self::$_var['article']['cat_id'] >= 0): ?>
//   validator.isNullOption('article_cat',no_cat);
// <?php endif; ?>


  return validator.passed();
}

document.getElementById("tabbar-div").onmouseover = function(e)
{
    var obj = Utils.srcElement(e);

    if (obj.className == "tab-back")
    {
        obj.className = "tab-hover";
    }
}

document.getElementById("tabbar-div").onmouseout = function(e)
{
    var obj = Utils.srcElement(e);

    if (obj.className == "tab-hover")
    {
        obj.className = "tab-back";
    }
}

document.getElementById("tabbar-div").onclick = function(e)
{
    var obj = Utils.srcElement(e);

    if (obj.className == "tab-front")
    {
        return;
    }
    else
    {
        objTable = obj.id.substring(0, obj.id.lastIndexOf("-")) + "-table";

        var tables = document.getElementsByTagName("table");
        var spans  = document.getElementsByTagName("span");

        for (i = 0; i < tables.length; i++)
        {
            if (tables[i].id == objTable)
            {
                tables[i].style.display = (Browser.isIE) ? "block" : "table";
            }
            else
            {
                tables[i].style.display = "none";
            }
        }
        for (i = 0; spans.length; i++)
        {
            if (spans[i].className == "tab-front")
            {
                spans[i].className = "tab-back";
                obj.className = "tab-front";
                break;
            }
        }
    }
}

function showNotice(objId)
{
    var obj = document.getElementById(objId);

    if (obj)
    {
        if (obj.style.display != "block")
        {
            obj.style.display = "block";
        }
        else
        {
            obj.style.display = "none";
        }
    }
}

function searchGoods()
{
    var elements  = document.forms['theForm'].elements;
    var filters   = new Object;
    var keyword = elements['keyword'].value;
    sz.loadOptions('article','get_artOrgoods_list', '&keyword='+keyword,filters);
}


/**
 * 选取上级分类时判断选定的分类是不是底层分类
 */
function catChanged()
{
  var obj = document.forms['theForm'].elements['article_cat'];

  cat_type = obj.options[obj.selectedIndex].getAttribute('cat_type');
  if (cat_type == undefined)
  {
    cat_type = 1;
  }

  if ((obj.selectedIndex > 0) && (cat_type == 2 || cat_type == 4))
  {
    alert(not_allow_add);
    obj.selectedIndex = 0;
    return false;
  }

  return true;
}
</script>
<?php echo self::fetch('pagefooter.htm'); ?>