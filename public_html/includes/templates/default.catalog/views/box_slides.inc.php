
<style type="text/css">
    /* Slide */
    .box {
        margin-bottom: -5px;
    }
    元素 {

    }
    .carousel-indicators .active {
        background-color: #fff;
    }

    .carousel-indicators {
        position: absolute;
        bottom: 15px;
        right: 0px;
        z-index: 15;
        width: 60%;
        padding-left: 0;
        margin-left: -30%;
        text-align: center;
        list-style: none;


    }
    .carousel-indicators li {
        text-indent: -999px;
        cursor: pointer;
        border: 1px solid #b2906a;
        border-radius: 10px;
        display: inline-block;
        background-color: #b2906a;
        float: none;
        display: inline-block;
        width: 25px !important;
        height: 4px;
        padding: 0 1px;
        margin: 3px;
        opacity: 0.5;
    }
    .carousel-indicators .active {
        background-color: #b2906a;
        opacity: 1;
    }
</style>
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
    <?php foreach ($slides as $key => $slide) {
        echo '<li data-target="#box-slides" data-slide-to="'.  $key .'"'. (($key == 0) ? ' class="active"' : '') .'>
</li>';
    } ?>
  </ol>

  <a class="left carousel-control" href="#box-slides" data-slide="prev">
    <span class="icon-prev"><?php echo functions::draw_fonticon('fa-chevron-left'); ?></span>
  </a>
  <a class="right carousel-control" href="#box-slides" data-slide="next">
    <span class="icon-next"><?php echo functions::draw_fonticon('fa-chevron-right'); ?></span>
  </a>
</div>
