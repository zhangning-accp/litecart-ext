<?php
    /**
     * 首页Slide下面的商品部分
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/11/6
     * Time: 11:32
     */
?>
<style type="text/css">
    .heading-spacer {
        position: relative;
        background-color: #e0e0e0;
        border-bottom: 1px solid #afafaf;
        padding: 30px 0;
        text-align: center;
    }

    .scroll-down-circle {
        position: absolute;
        top: -23px;
        left: 50%;
        width: 46px;
        height: 46px;
        margin-left: -23px;
        border-radius: 23px;
        cursor: pointer;
        background: url('{snippet:home_path}images/footer/title-arrows-grey-40x26.png') center no-repeat;
        background-size: 50% auto;
        background-color: #e0e0e0;
    }

    .heading-spacer h1 {
        margin: 0;
        color: #000;
        font-family: Futura, arial, sans-serif;
        font-size: 24px;
        text-transform: uppercase;
        font-family: Futura, arial, sans-serif;
    }

    .grid-header-container {
        width: 100%;
        font-family: Futura, arial, sans-serif;
        text-align: center;
        background: #e0e0e0;
        border-top: 1px solid #fff;
        border-bottom: 1px solid #b0b0b0;
    }

    @media only screen and (min-width: 950px)
        #grid-tabs-ver {
            display: block;
        }

        #grid-tabs-ver {
            width: 100%;
        }

        .grid-header {
            position: relative;
            width: 50%;
            height: 93px;
            max-width: 960px;
            margin: -15px auto;
            margin-top: -15px;
            margin-right: auto;
            margin-bottom: -15px;
            margin-left: auto;
        }

        ul {
            display: block;
            list-style-type: disc;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            padding-inline-start: 40px;
        }

        .tab-selected span {
            background-color: #FFF !important;
            color: #b88f63 !important;
            display: block;
            border-left: 1px solid #B0B0B0;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 78px;
            text-align: left;
            cursor: pointer;
            transition: all 0.4s ease 0s;
            height: 100%;
            text-align: center;
        }

        .grid-tab span {
            display: block;
            border-left: 1px solid #B0B0B0;
            color: #000;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 78px;
            text-align: left;
            cursor: pointer;
            transition: all 0.4s ease 0s;
            height: 100%;
            text-align: center;
        }

        .grid-tab, .grid-tab-selected {
            position: relative;
            float: left;
            height: 78px;
            overflow: hidden;
            width: 183px;
            text-align: center;
            border-left: 1px solid #FFF;
        }

        .grid-tab:last-child {
            border-right: 1px solid #FFF;
        }

        li {
            display: list-item;
            text-align: -webkit-match-parent;
        }

    /*adv product css*/
        .grid_container {
            max-width: 1920px;
            min-width: 320px;
            width: 100%;
            margin: 0 auto;
            overflow: hidden;
            -webkit-transform-origin-x: 50%;
            -moz-transform-origin-x: 50%;
            -webkit-transform-origin-y: 50%;
            -moz-transform-origin-y: 50%;
            -webkit-transform-style: preserve-3d;
            -moz-transform-style: preserve-3d;
        }
        .grid_container ul.column {
            display: inline-block;
            width: 25%;
            padding: 0;
            margin: 0;
            overflow: hidden;
            vertical-align: bottom;
            list-style: none;
        }

</style>
<div id="grid-title" class="heading-spacer">
    <div class="scroll-down-circle"></div>
    <h1 style="font-weight: 800">Best Sellers</h1>
</div>
<!-- tab -->
<div class="grid-header-container">
    <div class="grid-header" id="grid-tabs-ver">
        <ul>
            <li class="grid-tab tab-selected" data-collection-index="0"><span>Best Sellers</span></li>
            <li class="grid-tab" data-collection-index="2"><span>Running</span></li>
            <li class="grid-tab" data-collection-index="3"><span>Basketball</span></li>
            <li class="grid-tab" data-collection-index="5"><span>Womens</span></li>
            <li class="grid-tab" data-collection-index="12"><span>Kids</span></li>
        </ul>
    </div>
</div><!--tab end-->
<!--adv product-->
<div class="grid_container" style="border: 1px solid red;">
    <ul class="column"  style="border: 1px solid red;">
        <li class="full-width border-hover" style="width:300px;heigth:300px;">
<!--            <a href="javascript:return;" title=""-->
<!--                                               target=""-->
<!--                                               onclick="javascript:return;"-->
<!--                                               class="vendor-grid-cell">-->
<!--                <div class="cell-img"-->
<!--                     style="background-image:url('https://www.champssports.com/ns/common/champssports/images/grid/LargeGrid-AirMaxPlus-91818.jpg');border: 1px solid red;width:100px;height: 100px;"></div>-->
                <div style="background-image:url('https://www.champssports.com/ns/common/champssports/images/grid/LargeGrid-AirMaxPlus-91818.jpg');"><p style="margin-top: -9.5px;">Shop Nike Air Max Plus</p></div>
<!--            </a>-->
        </li>
    </ul>
</div>

<!--adv product end -->