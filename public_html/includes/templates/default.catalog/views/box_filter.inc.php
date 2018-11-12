<style>
    body{
        font-family: Roboto, Arial, sans-serif;
    }
    input[type="checkbox"] + label::before {
        width: 17px;
        height: 17px;
        border: 2px solid #000;
        margin-right: 10px;
        -webkit-box-flex: 0;
        -ms-flex: 0 0 21px;
        flex: 0 0 21px;
        max-width: 21px;
        content: "\a0";  /*不换行空格*/
        display: inline-block;
        vertical-align: .2em;
        border-radius: .2em;
        text-indent: 5px;
        line-height: 21px;  /*行高不加单位，子元素将继承数字乘以自身字体尺寸而非父元素行高*/
    }
    input[type="checkbox"]:checked + label::before {
        content: "√";
    }
    input[type="checkbox"]{
        position: absolute;
        clip: rect(0,0,0,0);
        z-index: 1000;
        width: 100px;
        height: 100px;
    }
    .checkbox-div{
        display: inline-block;
        height: 12px;
        line-height: 0px;
        vertical-align: middle;
        font-size: 14px;
        color: #333;
    }
    .form-control{
        border: 0;
    }
    ul.list-unstyled > li {
        padding: 8px 0;
    }
    .list-unstyled > li:hover{
        background-color: #f5f5f5;
        cursor: pointer;
    }
    #box-filter {
        padding-left: 25px;
    }
    .filter_btn {/*Filter button style*/
        display: none;
    }
    @media screen and (max-width: 420px){
        .filter_pop {
            opacity: 1.0;
            left: 0;
            position: fixed;
            bottom: 50px;
            width: 100%;
            z-index: 10;
            background-color: #FFFFFF;
            overflow:scroll;
            height: 100%;
            width: 100%;
            display: none;
        }
        .filter_btn {
            display: block;
            position:fixed;
            bottom:0;
            height: 50px;
            width: 100%;
            background-color: #b2906a;
            color: #FFFFFF;
            font-family: Roboto, Arial, sans-serif;
            text-align: center;
            cursor: pointer;
            z-index: 10;
            line-height: 50px;
        }
    }


</style>
<script>
    function filterButtonClick(obj) {
        if(obj.innerHTML == "Fliter") {
            obj.innerHTML = "Done";
            $("#box-filter").css("display","block");
        } else {
            obj.innerHTML = "Fliter";
            $("#box-filter").css("display","none");
        }
    }
</script>
<div class="filter_btn" onclick="filterButtonClick(this)">Fliter</div>
<div id="box-filter" class="filter_pop">
  <?php echo functions::form_draw_form_begin('filter_form', 'get'); ?>
<!--  --><?php //if ($manufacturers) { ?>
<!--  <div class="box manufacturers">-->
<!--    <h2 class="title">--><?php //echo language::translate('title_manufacturers', 'Manufacturers'); ?><!--</h2>-->
<!--    <div class="form-control">-->
<!--      <ul class="list-unstyled">-->
<!--        --><?php //foreach ($manufacturers as $manufacturer) echo '<li><label>'. functions::form_draw_checkbox('manufacturers[]', $manufacturer['id'], true) .' '. $manufacturer['name'] .'</label></li>' . PHP_EOL; ?>
<!--      </ul>-->
<!--    </div>-->
<!--  </div>-->
<!--  --><?php //} ?>
  <?php if ($product_groups) { ?>
  <?php foreach ($product_groups as $group) { ?>
  <div class="box product-group" data-id="<?php echo $group['id']; ?>">
    <h2 class="title"><?php echo $group['name']; ?></h2>
    <div class="form-control">
      <ul class="list-unstyled">
        <?php foreach ($group['values'] as $value) echo '<li>' . functions::form_draw_checkbox('product_groups[]', $group['id'].'-'.$value['id'],true,"id = " .$value['id']."_input") .' <label for="'.$value['id']."_input" .'"></label><div class="checkbox-div">'. $value['name'].'</div></li>' . PHP_EOL; ?>
      </ul>
    </div>
  </div>
  <?php } ?>
  <?php } ?>

  <?php echo functions::form_draw_form_end(); ?>
</div>
<script>
  $('form[name="filter_form"] input[name="manufacturers[]"]').click(function(){
    $(this).closest('form').submit();
  });

  $('form[name="filter_form"] input[name="product_groups[]"]').click(function(){
    $(this).closest('form').submit();
  });
//  $(window).resize(function () {          //当浏览器大小变化时
//      var windowHeight = $(window).height();//浏览器时下窗口可视区域高度
//      var documentHeight = $(document).height();//浏览器时下窗口文档的高度
//      var bodyHeight = $(document.body).height(); //浏览器时下窗口文档body的高度
//      var bodyOuteHeight = $(document.body).outerHeight(true);//浏览器时下窗口文档body的总高度 包括border padding margin
//      alert("浏览器下窗口可视区域高度:" + windowHeight);
//      alert("浏览器时下窗口文档的高度:" + documentHeight);
//      alert("浏览器时下窗口文档body的高度:" + bodyHeight);
//      alert("浏览器时下窗口文档body的总高度:" + bodyOuteHeight);
//  });
</script>