
      <script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
      <script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/ajaxPage.js"></script>

      <div id="turn-page">

        <?php echo self::$_var['lang']['total_records']; ?> <span id="totalRecords"><?php echo self::$_var['record_count']; ?></span>

        <?php echo self::$_var['lang']['total_pages']; ?> <span id="totalPages"><?php echo self::$_var['page_count']; ?></span>

        <?php echo self::$_var['lang']['page_current']; ?> <span id="pageCurrent"><?php echo self::$_var['pagenum']; ?></span>

        <?php echo self::$_var['lang']['page_size']; ?> <input type='text' size='3' id='pageSize' value="<?php echo self::$_var['length']; ?>" onkeypress="return listTable.changePageSize(event)" />
        
        <span id="page-link">
          <a href="javascript:" class="first"><?php echo self::$_var['lang']['page_first']; ?></a>
          <a href="javascript:" class="prev"><?php echo self::$_var['lang']['page_prev']; ?></a>
          <a href="javascript:" class="next"><?php echo self::$_var['lang']['page_next']; ?></a>
          <a href="javascript:" class="last"><?php echo self::$_var['lang']['page_last']; ?></a>
         跳转到 <input id="pageInput" title="按回车键跳转到指定页数" value="<?php echo self::$_var['pagenum']; ?>" style="width:25px;"  onkeypress="if(event.keyCode==13) {listTable.gotoPage(this.value);}"   type="text">
          <input class="button" id="pagerBtn" value="GO"  type="button">
        </span>
      </div>
      <script>
          $(function(){
              /*上一页*/
              var page_prev=parseInt($('#pageCurrent').html())-1;
              $('.prev').click(function(){

                  if(page_prev<1){
                      page_prev=1;
                  }
                  chuan(page_prev);
              });

              /*下一页*/

              $('.next').click(function(){
                  var page_next=parseInt($('#pageCurrent').html())+1;

                  var page_count=parseInt($('#totalPages').html());

                  if(page_next>page_count){
                      page_next=page_count;
                  }
                  chuan(page_next);
              });

              /*第一页*/
              $('.first').click(function(){
                  chuan(1);
              });

              /*最后一页*/
              $('.last').click(function(){

                  var page_count=parseInt($('#totalPages').html());
                  chuan(page_count);
              });

              /*去指定的页*/
              $('#pagerBtn').click(function(){
                    var val =$('#pageInput').val();
                  var page_count=parseInt($('#totalPages').html());
                  if(val>page_count){
                      val=page_count;
                  }
                      chuan(val);
              });



              function chuan(page){
                  var length=$('#pageSize').val();

                  $('#totalPages').html(Math.ceil(parseInt("<?php echo self::$_var['record_count']; ?>")/length));
                  $('#pageCurrent').html(page);
                  $.ajax({
                      url:"index.php?act=goods&op=trash",
                      data:{"pagenum":page,"length":length,'request':1},
                      method:"get",
                      success:function(rev){
                          console.log(rev);
                          $('.item').remove();
                          /*将json字符串转换为json对象*/
                          var json_obj=JSON.parse(rev);

                          for(var i = 0;i<json_obj.length;i++){

                              $('#trr').before("<tr class='item'><td>"+json_obj[i].trash_id+"</td><td>"+json_obj[i].goods_name+"</td><td>"+json_obj[i].goods_sn+"</td><td>"+json_obj[i].goods_price+"</td><td><a href='javascript:' class='hy' data-id='"+json_obj[i].trash_id+"'>还原</a><a href='index.php?act=goods&op=trash_del&id="+json_obj[i].trash_id+"'>删除</a></td></tr>");
                              console.log('index.php?act=goods_type&op=goods_type_edit&goods_type='+json_obj[i].cat_id)
                          }
                      }
                  });
              }
          })








      </script>