<?php
    /**
     * //TODO: 列表底部分页的视图
     */
?>
<style type="text/css">
    .pagination_active {
        font-size: 14px !important;
        text-decoration: none !important;
        background: #b2906a;
        color: #fff !important;
        border: 1px solid #9c7d60;
        font-weight: bold;
        padding: 10px 10px;
        text-transform:Uppercase;
        height:40px;
    }
    .pagination_active>a {
        color:#FFFFFF;
    }
    .pagination_default {
        font-size: 14px !important;
        text-decoration: none !important;
        background: #e0e0e0;
        border: 1px solid #e0e0e0;
        font-weight: bold;
        padding: 10px 10px;
        margin-left: 1px;
        cursor: pointer;
        text-transform:Uppercase;
        height:40px;
    }
    .disabled {
        height:40px;
        opacity: .5;
        cursor: not-allowed;
        font-size: 14px !important;
        text-decoration: none !important;
        background: #e0e0e0;
        border: 1px solid #e0e0e0;
        font-weight: bold;
        padding: 10px 10px;
        margin-left: 1px;
        text-transform:Uppercase;
        height:40px;
   }
    .pagination_main {
        display: inline-flex;
        padding-left: 0;
        margin: 15px 0;
        border-radius: 4px;
        list-style: none;
    }
    /*a:link{color: #000000;}*/
    </style>

<ul class="pagination_main">
    <?php foreach ($items as $item) { ?>
        <?php if ($item['disabled']) { ?>
            <li class="disabled"><span><?php echo $item['title']; ?></span></li>
        <?php } else { ?>
            <li<?php if ($item['active']) {
                echo ' class="pagination_active"';
            } else {
                echo ' class="pagination_default"';
            }
            ?>>
                <a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
        <?php } ?>
    <?php } ?>
</ul>

<!--<ul class="pagination">-->
<!--  --><?php //foreach ($items as $item) { ?>
<!--    --><?php //if ($item['disabled']) { ?>
<!--    <li class="disabled"><span>--><?php //echo $item['title']; ?><!--</span></li>-->
<!--    --><?php //} else { ?>
<!--    <li--><?php //if ($item['active']) echo ' class="active"'; ?>
<!--    >-->
<!--        <a href="--><?php //echo htmlspecialchars($item['link']); ?><!--">--><?php //echo $item['title']; ?><!--</a></li>-->
<!--    --><?php //} ?>
<!--  --><?php //} ?>
<!--</ul>-->
