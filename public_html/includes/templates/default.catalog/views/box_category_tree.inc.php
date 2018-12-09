<style>
    .fa-caret-right::before {/*Expand*/
        content: "";
    }
    .fa-caret-right::after {/*Expand*/
        content: "+";
    }
    .fa-caret-down::before {/*shrink*/
        content: "";
    }
    .fa-caret-down::after {/*shrink*/
        content: "-";
    }
    .nav-pills li a {
        margin: 1px 0;
        background: #ffffff;

    }
    /*hide left categories tree*/
    @media screen and (max-width: 420px){
        #column-left>#box-category-tree,#column-left>.box {
            display: none;
        }
    }

</style>
<?php
    /**
     * 数据来源于 public_html/includes/boxes/box_category_tree.inc.php
     * 分类的页面流程：
     * http://localhost/litecart/public_html/en/nfl-c-303/arizona-cardinals-c-311/
     *  ->public_html/index.php->public_html/pages/category.inc.php
     *  ->public_html/includes/templates/default.catalog/pages/category.inc.php
     *  ->public_html/includes/templates/default.catalog/views/column_left.inc.php
     *  ->public_html/includes/boxes/box_category_tree.inc.php
     *  ->public_html/includes/templates/default.catalog/views/box_category_tree.inc.php
     */

    if (!empty(document::$settings['compact_category_tree'])) { ?>
<style>
  #box-category-tree > ul.compact > li:not(.opened) {
    display: none;
  }
  #box-category-tree > ul.compact > li.toggle {
    display: block !important;
  }
</style>
<?php } ?>

<?php
    // 分类数据
    // 整个pc端的分类需要做成点击一级分类时，异步请求其子分类，并显示，当点击子分类时，流程相同，但是会默认选中当前子分类
  if (!function_exists('custom_draw_category')) {
      function custom_draw_category($category, $category_path) {
          $treeHtml = "";
          echo '<li class="category-'. $category['id'] . (!empty($category['opened']) ? ' opened' : '') . (!empty($category['active']) ? ' active' : '') .'">' . PHP_EOL
              . '  <a href="'. htmlspecialchars($category['link']) .'"><i class="fa fa-fw fa-'. (empty($category['opened']) ? 'caret-right' : 'caret-down') .'"></i> '. $category['name'] .'</a>' . PHP_EOL;
          if (!empty($category['subcategories'])) {
              echo '  <ul class="nav nav-stacked nav-pills">' . PHP_EOL;
              foreach ($category['subcategories'] as $subcategory) {
                  echo PHP_EOL . custom_draw_category($subcategory, $category_path);
              }
              echo '  </ul>' . PHP_EOL;
          }
          echo '</li>' . PHP_EOL;
      }
      // 2018-12-7 注释
//    function custom_draw_category($category, $category_path) {
//      echo '<li class="category-'. $category['id'] . (!empty($category['opened']) ? ' opened' : '') . (!empty($category['active']) ? ' active' : '') .'">' . PHP_EOL
//         . '  <a href="'. htmlspecialchars($category['link']) .'"><i class="fa fa-fw fa-'. (empty($category['opened']) ? 'caret-right' : 'caret-down') .'"></i> '. $category['name'] .'</a>' . PHP_EOL;
//      if (!empty($category['subcategories'])) {
//        echo '  <ul class="nav nav-stacked nav-pills">' . PHP_EOL;
//        foreach ($category['subcategories'] as $subcategory) {
//          echo PHP_EOL . custom_draw_category($subcategory, $category_path);
//        }
//        echo '  </ul>' . PHP_EOL;
//      }
//      echo '</li>' . PHP_EOL;
//    }
  }
?>
<div id="box-category-tree" class="box">
  <h2 class="title"><?php echo $title; ?></h2>
  <ul class="nav nav-stacked nav-pills<?php if (!empty(document::$settings['compact_category_tree']) && !empty($category_path)) echo ' compact'; ?>">
    <?php foreach ($categories as $category) custom_draw_category($category, $category_path); ?>
  </ul>
</div>

<?php if (!empty(document::$settings['compact_category_tree'])) { ?>
<script>
  $('#box-category-tree > ul.compact').prepend(
    '<li class="toggle"><a href="#" data-toggle="showall"><i class="fa fa-caret-left"></i> <?php echo language::translate('title_show_all', 'Show All'); ?></a></li>'
  );

  $('#box-category-tree > ul.compact').on('click', 'a[data-toggle="showall"]', function(e){
    e.preventDefault();
    $(this).slideUp();
    $('#box-category-tree > ul > li:hidden').slideDown();
  });
</script>
<?php } ?>
