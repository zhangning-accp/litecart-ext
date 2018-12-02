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
        background: url('{snippet:template_path}images/footer/title-arrows-grey-40x26.png') center no-repeat;
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

    /*@media only screen and (min-width: 950px)*/
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

        /*adv style @media的顺序不能变*/
        @media (min-width: 48em) {
            .Bands-item.col {
                margin: 0 0 1rem;
            }
            .row {
                margin: 0px;
            }
            .col {
                flex: 1;
            }
        }
        @media (min-width: 600px) {
            .Bands--6up, .Bands--productList {
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-direction: row;
                flex-direction: row;
                margin: 1rem 0;
            }

            .Bands--6up .Bands-item, .Bands--productList .Bands-item {
                margin: 1rem 0;
                -webkit-box-flex: 1;
                -ms-flex: 1 1 33.33333%;
                flex: 1 1 33.33333%;
            }
        }

        @media (min-width: 1200px) {
            .Bands--6up, .Bands--productList {
                -ms-flex-wrap: nowrap;
                flex-wrap: nowrap;
            }
        }

        .ProductBand-link {
            height: 100%;
            padding-bottom: 1rem;
            text-decoration: none;
        }



        .ProductBand {
            text-align: center;
        }

        /*img*/
        .Band-image, .Bands-item img, .Bands-item picture {
            width: 80%;
        }

        /*band header*/
        .ProductBand-header {
            margin-top: .5rem;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            color: #000;
        }

        .ProductBand-header .brand {
            font: 12px/1.33333 Roboto, Arial, sans-serif;
        }

        .ProductBand-header .name {
            font: 16px/1.5 Roboto, Arial, sans-serif;

        }

        @media (min-width: 900px)
            .ProductBand-header, .ProductBand .c-btn {
                margin: 0 2rem;
            }

            .ProductBand-header .brand, .ProductBand-header .name {
                -webkit-text-decoration: underline transparent;
                text-decoration: underline transparent;
                -webkit-transition: -webkit-text-decoration .25s;
                transition: -webkit-text-decoration .25s;
                transition: text-decoration .25s;
                transition: text-decoration .25s, -webkit-text-decoration .25s;
            }

            .ProductBand-header .brand, .ProductBand-header .name {
                -webkit-text-decoration: underline transparent;
                text-decoration: underline transparent;
                -webkit-transition: -webkit-text-decoration .25s;
                transition: -webkit-text-decoration .25s;
                transition: text-decoration .25s;
                transition: text-decoration .25s, -webkit-text-decoration .25s;
            }

            .ProductBand-header .c-icon {
                position: relative;
                top: -1px;
            }

            .c-icon {
                display: inline-block;
                width: 14px;
                height: 14px;
                fill: #383838;
                -webkit-transform-origin: 50% 50%;
                transform-origin: 50% 50%;
                -webkit-transition: -webkit-transform .2s;
                transition: -webkit-transform .2s;
                transition: transform .2s;
                transition: transform .2s, -webkit-transform .2s;
            }

            .c-icon svg {
                fill: inherit;
                float: left;
                margin-top: 3px;
                padding-left: 3px;
            }

            /*items*/
            .Band-content {
                width: 100%;
                position: relative;
                text-align: center;
                /*font: 16px/1.85 Roboto,Arial,sans-serif;*/
                font: 40px/1.5 Oswald;
                font-size: 1.875rem;
            }

            .Band-content > ul {
                list-style: none;
            }

            .Band-content > ul > li a {
                color: #000000;
                font: 14px/1.2 Roboto, Arial, sans-serif;
            }

            .Band-content > ul > li a:hover {
                color: #866c4e;
                /*font:14px/1.2 Roboto,Arial,sans-serif;*/
                text-decoration: none;

            }

            .ProductBand a:hover, .ProductBand-header:hover {
                text-decoration: none;
                color: #866c4e;
                font-weight: bold;
            }
</style>
<div id="grid-title" class="heading-spacer">
<!--    <div class="scroll-down-circle"></div>-->
    <h1 style="font-weight: 800">Best Sellers</h1>
</div>
<!-- tab -->
<!--<div class="grid-header-container">-->
<!--    <div class="grid-header" id="grid-tabs-ver">-->
<!--        <ul>-->
<!--            <li class="grid-tab tab-selected" data-collection-index="0"><span>Best Sellers</span></li>-->
<!--            <li class="grid-tab" data-collection-index="2"><span>Running</span></li>-->
<!--            <li class="grid-tab" data-collection-index="3"><span>Basketball</span></li>-->
<!--            <li class="grid-tab" data-collection-index="5"><span>Womens</span></li>-->
<!--            <li class="grid-tab" data-collection-index="12"><span>Kids</span></li>-->
<!--        </ul>-->
<!--    </div>-->
<!--</div><!--tab end-->
<!--adv product-->
<section class="row Bands--6up">
    <div class="col Bands-item">
        <div class="ProductBand"><a class="ProductBand-link" href="/product/Jordan-Retro-5---Men-s/36027006.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/6up/CH_T110318_6Up_JordanRetro5.jpg"
                            alt="" class="ProductBand-img"></picture>
                <h4 class="ProductBand-header"><span class="brand">Jordan</span><span class="name">Retro 5<span
                                class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"
                                                    class="injected-svg"
                                                    data-src="/built/63/app/icons/base/ic_arrow_chevron.svg"
                                                    focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4>
            </a></div>
    </div>
    <div class="col Bands-item">
        <div class="ProductBand"><a class="ProductBand-link"
                                    href="/product/Under-Armour-Hovr-Phantom---Men-s/20972602.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/6up/CH_T110218_6Up_20972602.jpg"
                            alt="" class="ProductBand-img"></picture>
                <h4 class="ProductBand-header"><span class="brand">Under Armour</span><span
                            class="name">Hovr Phantom<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                               viewBox="0 0 306 306"
                                                                               class="injected-svg"
                                                                               data-src="/built/63/app/icons/base/ic_arrow_chevron.svg"
                                                                               focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4>
            </a></div>
    </div>
    <div class="col Bands-item">
        <div class="ProductBand"><a class="ProductBand-link" href="/product/under-armour-curry-5---men-s/20657108.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/6up/CH_T110218_6Up_20657108.jpg"
                            alt="" class="ProductBand-img"></picture>
                <h4 class="ProductBand-header"><span class="brand">Under Armour</span><span class="name">Curry 5<span
                                class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"
                                                    class="injected-svg"
                                                    data-src="/built/63/app/icons/base/ic_arrow_chevron.svg"
                                                    focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4>
            </a></div>
    </div>
    <div class="col Bands-item">
        <div class="ProductBand"><a class="ProductBand-link" href="/product/PUMA-Clyde-Court---Men-s/19189501.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/6up/CH_T103118_6Up_19189501.jpg"
                            alt="" class="ProductBand-img"></picture>
                <h4 class="ProductBand-header"><span class="brand">PUMA</span><span class="name">Clyde Court<span
                                class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"
                                                    class="injected-svg"
                                                    data-src="/built/63/app/icons/base/ic_arrow_chevron.svg"
                                                    focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4>
            </a></div>
    </div>
    <div class="col Bands-item">
        <div class="ProductBand"><a class="ProductBand-link"
                                    href="/product/Nike-Air-Vapormax-Run-Utility---Men-s/Q8810200.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/6up/CH_T110118_6Up_Q8810200.jpg"
                            alt="" class="ProductBand-img"></picture>
                <h4 class="ProductBand-header"><span class="brand">Nike</span><span
                            class="name">VaporMax Run Utility<span class="c-icon"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg"
                                    data-src="/built/63/app/icons/base/ic_arrow_chevron.svg" focusable="false"
                                    aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4>
            </a></div>
    </div>
    <div class="col Bands-item">
        <div class="ProductBand"><a class="ProductBand-link"
                                    href="/product/timberland-cityforce-reveal--mens/A1Z5S754.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/6up/CH_101518_6Up_A1Z5S754.jpg"
                            alt="" class="ProductBand-img"></picture>
                <h4 class="ProductBand-header"><span class="brand">Timberland</span><span
                            class="name">Cityforce Reveal<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                                   viewBox="0 0 306 306"
                                                                                   class="injected-svg"
                                                                                   data-src="/built/63/app/icons/base/ic_arrow_chevron.svg"
                                                                                   focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4>
            </a></div>
    </div>
</section>
<!--big img-->
<div style="padding: 20px">
    <a tabindex="-1" class=""
       href="/category/shoes/nike/air-max-270.html?query=Nike+Air+Max+270+Shoes%3Arelevance%3Agender%3A200001">
        <picture>
            <source srcset="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/hero/CH_T110118_Hero_NikeWAM270.jpg"
                    media="(max-width: 37.4375em)">
            <img src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/hero/CH_T110118_Hero_NikeWAM270.jpg"
                 alt=""
                 class="Band-image"></picture>
    </a>
</div>
<!-- items -->
<section class="row Bands--1up">
    <div class="col Bands-item">
        <div class="Band TitleBand light align-top align-center">
            <div class="Band-content"><h2 class="Band-header">Trending on the Gram</h2></div>
        </div>
    </div>
</section>
<section class="row Bands--4up">
    <div class="col Bands-item">
        <div class="Band HeroBand light align-top align-center"><a tabindex="-1" class=""
                                                                   href="/product/Jordan-Retro-11---Men-s/78037016.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/4up/CH_T110218_4up_JordanRetro11.jpg"
                            alt="" class="Band-image"></picture>
            </a>
            <div class="Band-content">
                <ul class="btn-group">
                    <li><a class="c-btn c-btn--primary" href="/product/Jordan-Retro-11---Men-s/78037016.html">Shop Retro
                            11<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"
                                                        class="injected-svg"
                                                        data-src="/built/64/app/icons/base/ic_arrow_chevron.svg"
                                                        focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col Bands-item">
        <div class="Band HeroBand light align-top align-center"><a tabindex="-1" class=""
                                                                   href="/product/Jordan-Retro-5---Men-s/36027006.html">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/4up/CH_T110218_4up_JordanRetro5.jpg"
                            alt="" class="Band-image"></picture>
            </a>
            <div class="Band-content">
                <ul class="btn-group">
                    <li><a class="c-btn c-btn--primary" href="/product/Jordan-Retro-5---Men-s/36027006.html">Shop Retro
                            5<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"
                                                       class="injected-svg"
                                                       data-src="/built/64/app/icons/base/ic_arrow_chevron.svg"
                                                       focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col Bands-item">
        <div class="Band HeroBand light align-top align-center"><a tabindex="-1" class=""
                                                                   href="/search?query=knit hats">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/4up/CH_T110218_4up_Knits.jpg"
                            alt="" class="Band-image"></picture>
            </a>
            <div class="Band-content">
                <ul class="btn-group">
                    <li><a class="c-btn c-btn--primary" href="/search?query=knit hats">Shop Knit Hats<span
                                    class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"
                                                        class="injected-svg"
                                                        data-src="/built/64/app/icons/base/ic_arrow_chevron.svg"
                                                        focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col Bands-item">
        <div class="Band HeroBand light align-top align-center"><a tabindex="-1" class=""
                                                                   href="/search?query=nike crimson">
                <picture><img
                            src="https://www.champssports.com/content/dam/flincfoundation/champssports/homepage-images/4up/CH_T110218_4up_CrimsonPack.jpg"
                            alt="" class="Band-image"></picture>
            </a>
            <div class="Band-content">
                <ul class="btn-group">
                    <li><a class="c-btn c-btn--primary" href="/search?query=nike crimson">Shop Nike Crimson<span
                                    class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"
                                                        class="injected-svg"
                                                        data-src="/built/64/app/icons/base/ic_arrow_chevron.svg"
                                                        focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--adv product end -->