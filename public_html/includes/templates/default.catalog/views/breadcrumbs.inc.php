<style type="text/css">
ul.breadcrumb {
    margin: 0 0 15px 0;
    margin-bottom: 15px;
    padding: 7.5px 15px;
    margin-bottom: 15px;
    list-style: none;
    border-radius: 4px;
    font-weight: bold;
    font-size: 12px;
    background: #ffffff;
    font-family: Futura,Arial,Sans-Serif;
}
ul.breadcrumb li a {
    color: #133d8d;

}
ul.breadcrumb li::before {

    content: ">";
    padding: 0 15px;
    color: #ccc;

}
</style>

<ul class="breadcrumb">
<?php
  foreach ($breadcrumbs as $breadcrumb) {
    if (!empty($breadcrumb['link'])) {
      echo '<li><a href="'. htmlspecialchars($breadcrumb['link']) .'">'. $breadcrumb['title'] .'</a></li>';
    } else {
      echo '<li>'. $breadcrumb['title'] .'</li>';
    }
  }
?>
</ul>
