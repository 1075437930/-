<!--<link href="styles/zTree/zTreeStyle.css" rel="stylesheet" type="text/css" />--><!--<link href="styles/chosen/chosen.css" rel="stylesheet" type="text/css">--><script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery-1.6.2.min.js"></script><script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/chosen.jquery.min.js"></script><script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/jquery.ztree.all-3.5.min.js"></script><script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/category_selecter.js"></script><div class="form-div">    <form action="javascript:searchGoods()" name="searchForm">        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />                <select id="cat_id" name="cat_id">            <option value="0"><?php echo self::$_var['lang']['all_cat']; ?></option>            <?php echo self::$_var['cat_list']; ?>        </select>                <select  class="chzn-select" id="brand_id" name="brand_id"><option value="0"><?php echo self::$_var['lang']['goods_brand']; ?></option><?php echo self::html_options(array('options'=>self::$_var['brand_list'])); ?></select>                关键字 <input type="text" name="keyword" size="15" />        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />    </form></div><script language="JavaScript">     function searchGoods(){listTable.filter['cat_id'] = document.forms['searchForm'].elements['cat_id'].value;        listTable.filter['brand_id'] = document.forms['searchForm'].elements['brand_id'].value;        listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);        listTable.filter['page'] = 1;        listTable.loadList();}</script>