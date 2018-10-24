<div id="box-slides" class="box carousel slide" data-ride="carousel">

  <div class="carousel-inner">
<?php
  foreach ($slides as $key => $slide) {
    echo '<div class="item'. (($key == 0) ? ' active' : '') .'">' . PHP_EOL;

    if ($slide['link']) {
      echo '<a href="'. htmlspecialchars($slide['link']) .'">' . PHP_EOL;
    }

    echo '<img src="'. $slide['image'] .'" alt="" style="width: 100%;" /></a>' . PHP_EOL;

    if (!empty($slide['caption'])) {
      echo '<div class="carousel-caption">'. $slide['caption'] .'</div>' . PHP_EOL;
    }

    if ($slide['link']) {
      echo '</a>' . PHP_EOL;
    }

    echo '</div>' . PHP_EOL;
  }
?>
  </div>

  <ol class="carousel-indicators">
    <?php foreach ($slides as $key => $slide) echo '<li data-target="#box-slides" data-slide-to="'.  $key .'"'. (($key == 0) ? ' class="active"' : '') .'></li>'; ?>
  </ol>

  <a class="left carousel-control" href="#box-slides" data-slide="prev">
    <span class="icon-prev"><?php echo functions::draw_fonticon('fa-chevron-left'); ?></span>
  </a>
  <a class="right carousel-control" href="#box-slides" data-slide="next">
    <span class="icon-next"><?php echo functions::draw_fonticon('fa-chevron-right'); ?></span>
  </a>
</div>
<!-- slider -->
<!--<div class="pedestal">-->
<!--    <div id="spotlight" class="spotlight">-->
<!--        <div class="slide_content" style="display: block;">-->
<!--            <ul class="slideAnim" style="transition: all 1s ease 0s; width: 4749px; left: -1583px;"><li class="dark-bkg slideitem0" style="background-image: url(&quot;/ns/common/champssports/images/pedestal/CH_P092818_Spotlight_GoingNumb.jpg&quot;); width: 1583px;"><a href="/product/model:78300/sku:V7940700/nike-air-max-plus-mens/yellow/" class="slide-link left" title="Watch Now" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target=""></a><a href="javascript:openVideoOverlay($('#overlay_video_2'))" class="slide-link right" title="Watch Now" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target=""></a><div class="copy-area"><div class="slide-title">Going Numb Season 2, Episode 9</div><a href="/product/model:78300/sku:V7940700/nike-air-max-plus-mens/yellow/" class="button" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target="">Shop Now</a><a href="javascript:openVideoOverlay($('#overlay_video_2'))" class="button" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target="">Watch Now</a></div></li><li class="dark-bkg slideitem1 selected" style="background-image: url(&quot;/ns/common/champssports/images/pedestal/CH_P092818_Spotlight_GoingNumb.jpg&quot;); width: 1583px;"><a href="/product/model:78300/sku:V7940700/nike-air-max-plus-mens/yellow/" class="slide-link left" title="Watch Now" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target=""></a><a href="javascript:openVideoOverlay($('#overlay_video_2'))" class="slide-link right" title="Watch Now" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target=""></a><div class="copy-area"><div class="slide-title">Going Numb Season 2, Episode 9</div><a href="/product/model:78300/sku:V7940700/nike-air-max-plus-mens/yellow/" class="button" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target="">Shop Now</a><a href="javascript:openVideoOverlay($('#overlay_video_2'))" class="button" manual_cm_re="Slider-_-cs_ls_st_sc-_-goingnumbmelvingordon_episode_09282018" target="">Watch Now</a></div></li><li class="dark-bkg slideitem2" style="background-image: url(&quot;/ns/common/champssports/images/pedestal/Spotlight-SneakerIcon-81418.jpg&quot;); width: 1583px;"><a href="/_-_/keyword-reebok+altered" class="slide-link left" title="" manual_cm_re="Slider-_-cs_ls_st_sc-_-sneakericon_shopnow_08142018" target=""></a><a href="javascript:openVideoOverlay($('#overlay_video_4'))" class="slide-link right" title="" manual_cm_re="Slider-_-cs_ls_st_sc-_-sneakericon_08142018" target=""></a><div class="copy-area"><div class="slide-title">Sneaker Icon Ep. 2</div><a href="/_-_/keyword-reebok+altered" class="button" manual_cm_re="Slider-_-cs_ls_st_sc-_-sneakericon_shopnow_08142018" target="">Shop Now</a><a href="javascript:openVideoOverlay($('#overlay_video_4'))" class="button" manual_cm_re="Slider-_-cs_ls_st_sc-_-sneakericon_08142018" target="">Watch Now</a></div></li></ul>-->
<!--        </div>-->
<!--        <div class="slide_controls">-->
<!--            <ul><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide0 slide_control"></a></li><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide1 slide_control"></a></li><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide2 slide_control"></a></li><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide3 slide_control"></a></li><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide4 slide_control selected"></a></li><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide5 slide_control"></a></li><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide6 slide_control"></a></li><li style="width: 12.5%;"><a href="javascript:void(0);" title=" " class="slide7 slide_control"></a></li></ul>-->
<!--        </div>-->
<!--        <div class="slide_buttons" style="display: block;">-->
<!--            <!-- previous arrows -->
<!--            <div class="sl_previous"><a href="javascript:csPedestal.spotlight.previousSlide();"></a></div>-->
<!--            <!-- next arrows -->
<!--            <div class="sl_next"><a href="javascript:csPedestal.spotlight.nextSlide();"></a></div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div> <!-- slider end-->
<!--<style type="text/css">-->
<!--    .pedestal {-->
<!--        background: #fff;-->
<!--        text-align: center;-->
<!--    }-->
<!--    .pedestal .spotlight {-->
<!--        position: relative;-->
<!--        display: block;-->
<!--        max-width: 1920px;-->
<!--        margin: 0 auto;-->
<!--    }-->
<!--    .spotlight {-->
<!--        position: relative;-->
<!--        display: inline-block;-->
<!--        width: 100%;-->
<!--        height: auto;-->
<!--        margin: 0;-->
<!--        font-size: 12px;-->
<!--        overflow: hidden;-->
<!--    }-->
<!--    .slide_content {-->
<!--        display: none;-->
<!--        width: 100%;-->
<!--        height: auto;-->
<!--        overflow: hidden;-->
<!--    }-->
<!--    .pedestal .slide_controls {-->
<!--        position: absolute;-->
<!--        bottom: 72px;-->
<!--        right: 75px;-->
<!--        width: auto;-->
<!--    }-->
<!--    .slide_controls {-->
<!--        width: 100%;-->
<!--    }-->
<!---->
<!--    .pedestal .slide_controls ul {-->
<!--        text-align: center;-->
<!--    }-->
<!--    .slide_controls ul {-->
<!--        width: 100%;-->
<!--        list-style: none;-->
<!--        height: auto;-->
<!--        margin: 0;-->
<!--        padding: 0;-->
<!--        overflow: hidden;-->
<!--    }-->
<!--    .pedestal .slide_controls ul li {-->
<!--        float: none;-->
<!--        display: inline-block;-->
<!--        width: 22px !important;-->
<!--        height: 3px;-->
<!--        padding: 0 1px;-->
<!--        margin: 0;-->
<!--    }-->
<!--    /*.slide_controls ul li {*/-->
<!--        /*float: left;*/-->
<!--        /*display: block;*/-->
<!--        /*padding: 0;*/-->
<!--        /*margin: 0;*/-->
<!--    /*}*/-->
<!---->
<!--    .pedestal .slide_buttons {-->
<!--        width: 100%;-->
<!--        height: 1px;-->
<!--        position: absolute;-->
<!--        bottom: 0px;-->
<!--        z-index: 80;-->
<!--    }-->
<!--    .slide_buttons {-->
<!--        display: none;-->
<!--        position: absolute;-->
<!--        left: 0;-->
<!--        bottom: 0;-->
<!--        z-index: 10;-->
<!--    }-->
<!--    .pedestal .slide_buttons .sl_previous, .pedestal .slide_buttons .sl_next {-->
<!--        bottom: 200px;-->
<!--    }-->
<!--    .pedestal .slide_buttons .sl_previous {-->
<!--        position: absolute;-->
<!--        bottom: 250px;-->
<!--        left: 0px;-->
<!--    }-->
<!--    .sl_previous {-->
<!--        display: inline-block;-->
<!--        float: left;-->
<!--    }-->
<!--    .pedestal .slide_buttons .sl_next a {-->
<!--        width: 74px;-->
<!--        height: 55px;-->
<!--        background: url('{snippet:template_path}images/right-turn-arrow.png') center no-repeat;-->
<!--        background-size: auto auto;-->
<!--        background-size: contain;-->
<!--    }-->
<!--    .pedestal .slide_buttons .sl_previous a {-->
<!--        width: 74px;-->
<!--        height: 55px;-->
<!--        background: url('{snippet:template_path}images/left-turn-arrow.png') center no-repeat;-->
<!--        background-size: auto auto;-->
<!--        background-size: contain;-->
<!--    }-->
<!--    .pedestal .slide_buttons a {-->
<!--        display: block;-->
<!--        opacity: 0;-->
<!--        filter: alpha(opacity=0);-->
<!--        -moz-transition: all 0.5s;-->
<!--        -webkit-transition: all 0.5s;-->
<!--        -o-transition: all 0.5s;-->
<!--        transition: all 0.5s;-->
<!--    }-->
<!--    a {-->
<!--        text-decoration: underline;-->
<!--        color: #000;-->
<!--    }-->
<!--</style> <!-- slider style end -->
