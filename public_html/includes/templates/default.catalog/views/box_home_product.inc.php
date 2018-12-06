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
    /*--------------------------new ----------------------------*/
             /* font */

            @font-face {
                font-family: "Oswald";
                src: local("Oswald Regular"), local("Oswald-Regular"), url("{snippet:template_path}font/TK3iWkUHHAIjg752GT8G.woff2") format("woff2");
                font-style: normal;
                font-weight: 400;
                unicode-range: U+0-FF, U+131, U+152-153, U+2BB-2BC, U+2C6, U+2DA, U+2DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }
            /*font end*/

            body {
                font: 16px/1.5 Roboto, Arial, sans-serif;
            }

            a {
                text-decoration: none;
                color: #000000;
            }

            ul {
                list-style: none;
                padding: 0px;
                margin-top: 2rem;
            }

    .Band-content>ul>li>a {
                background-color: #000000;
                color: #FFFFFF;
                padding: 1em 1.4em;
                text-transform: uppercase;
                text-align: center;
                font-size: 14px;
                font-weight: 700;
                letter-spacing: 1px;
                /*border:3px solid red;*/
                text-transform: uppercase;
                display: inline-block;
            }

            .adv_row {
                display: flex;
            }

            @media only screen and (max-width: 48em) {
                .adv_row {
                    flex-direction: column;
                }
            }

            .Bands--2up,.Bands--3up,.Bands--4up {
                margin: 0 -.5rem;
            }

            .Band-content {
                text-align: center;
                padding: 1rem;
                max-width: 1300px;
                margin: 0 auto;
                /*border: 1px solid red;*/
            }
        .Band-content a{
            color:#FFFFFF;
        }
        .Band-header {
            color:#000000;
        }
            .Band-content p,.Band-header {
                margin: 0 0 .5rem;
                /*bottom .5rem*/
            }

            .Band-header,.font-heading-1,h1 {
                font: 900 48px/1.2 Oswald;
                letter-spacing: .5px;
            }

            @media only screen and (max-width: 37.4375em) {
                .Band-header,
                .font-heading-1,
                h1 {
                    font: 300 32px/1.2 Oswald;
                    letter-spacing: .5px;
                }
            }

            .Band-image,.Bands-item img,.Bands-item picture {
                display: block;
                margin: auto;
                width: 100%;
            }

            .c-icon {
                display: inline-block;
                width: 14px;
                height: 14px;
                fill: #FFFFFF;
                margin-left: 5px;
            }

            .c-icon svg {
                float: left;
                margin-top: 2px;
            }

            .name .c-icon {
                fill: #000000;
            }
            /*6up*/

            .Bands--6up,
            .Bands--productList {
                flex-wrap: nowrap;
                -ms-flex-wrap: wrap;
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-direction: row;
                /*flex-direction: row;*/
                margin: 1rem 0;
            }

            .ProductBand {
                text-align: left;
                text-decoration: none;
            }

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

            .ProductBand-link:focus .ProductBand-header .brand,
            .ProductBand-link:hover .ProductBand-header .brand,
            .ProductBand-link:hover .ProductBand-header .c-icon svg {
                text-decoration: underline;
                fill: currentColor;
            }
            .ProductBand-link:focus>.ProductBand-header>.name,
            .ProductBand-link:hover>.ProductBand-header>.name,
            .Band-content>ul>li>a:hover {
                text-decoration:none;
                fill: currentColor;
            }

            .ProductBand-link:hover .ProductBand-header .c-icon svg {
                -webkit-transform: translateX(.25rem);
                transform: translateX(.25rem);
            }

            .ProductBand-header .brand {
                font: 12px/1.33333 Roboto, Arial, sans-serif;
                letter-spacing: .5px;
            }

            .ProductBand-header .name {
                font: 16px/1.5 Roboto, Arial, sans-serif;
                padding:0px;
            }
            .ProductBand-header, .ProductBand .c-btn {
                margin: 0 1rem;
            }
        @media screen and (min-width: 900px){
            .ProductBand-header, .ProductBand .c-btn {
                margin: 0 2rem;
                margin-top: 0px;
            }
        }
</style>
<div class="Band-content">
    <h2 class="Band-header">Best Sellers </h2>
    <p></p>
</div>
<div id="ad_product">

</div>

<script id="band" type="text/html">
    <!-- 1 band -->
    <section class="adv_row Bands--1up" data-bi="{&quot;location&quot;:&quot;homepage&quot;,&quot;component&quot;:&quot;1up&quot;}">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;ultraboost_12218&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[0].images[0].link}}">
                    <picture>
                        <source srcset="{{items[0].images[0].mobile_image}}" media="(max-width: 37.4375em)">
                        <img src="{{items[0].images[0].pc_image}}">
                    </picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header"><%=items[0].head%></h2>
                    <p><%=items[0].contents[1]%></p>
                    <ul class="btn-group">
                        <li>
                            <a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;ultraboost_12218&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[0].images[0].link}}">
                                <%=items[0].contents[1]%>
                                <span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--1 band end-->
    <!--2 band-->
    <section class="adv_row Bands--1up" data-bi="{&quot;location&quot;:&quot;homepage&quot;,&quot;component&quot;:&quot;1up&quot;}">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;concordclothing_12318&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[1].images[0].link}}">
                    <picture>
                        <source srcset="{{items[1].images[0].mobile_image}}" media="(max-width: 37.4375em)">
                        <img src="{{items[1].images[0].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header">{{items[1].head}}</h2>
                    <p>{{items[1].contents[0]}}</p>
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;concordclothing_12318&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[1].images[0].link}}">
                                {{items[1].contents[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--2 band end--》
    <!--3 band -->
    <section class="adv_row Bands--1up" data-bi="{&quot;location&quot;:&quot;homepage&quot;,&quot;component&quot;:&quot;1up&quot;}">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;timberlandchampion_113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[2].images[0].link}}">
                    <picture>
                        <source srcset="{{items[2].images[0].mobile_image}}" media="(max-width: 37.4375em)">
                        <img src="{{items[2].images[0].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header">{{items[2].head}}</h2>
                    <p></p>
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;timberlandchampion_113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[2].images[0].link}}">
                                {{items[2].contents[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--3 band end-->
    <!--4 band-->
    <section class="adv_row Bands--2up" data-bi="{&quot;location&quot;:&quot;Homepage&quot;,&quot;component&quot;:&quot;2up&quot;}">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;lebronkyrie_113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[3].images[0].link}}">
                    <picture><img src="{{items[3].images[0].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header">{{items[3].head}}</h2>
                    <p>{{items[3].contents[0]}}</p>
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;lebronkyrie_113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[3].images[0].link}}">{{items[3].contents[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;vapormax_113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[4].images[0].link}}">
                    <picture><img src="{{items[4].images[0].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header">{{items[4].head}}</h2>
                    <p>{{items[4].contents[0]}}</p>
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;vapormax_113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[4].images[0].link}}">{{items[4].contents[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--4 band end-->
    <!--5 band -->
    <section class="adv_row Bands--1up">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;HOH113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[5].images[0].link}}">
                    <picture>
                        <source srcset="{{items[5].images[0].mobile_image}}" media="(max-width: 37.4375em)"><img src="{{items[5].images[0].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header">{{items[5].head}}</h2>
                    <p></p>
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;HOH113018&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[5].images[0].link}}">{{items[5].contents[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--5 band end-->
    <!--6 band-->
    <section class="adv_row Bands--1up">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;RYG112118&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[6].images[0].link}}">
                    <picture>
                        <source srcset="{{items[6].images[0].mobile_image}}" media="(max-width: 37.4375em)"><img src="{{items[6].images[0].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header">{{items[6].head}}</h2>
                    <p></p>
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;RYG112118&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[6].images[0].linki}}">{{items[6].contents[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--6 band end-->
    <section class="adv_row Bands--1up">
        <div class="col Bands-item">
            <div class="Band TitleBand light align-top align-center">
                <div class="Band-content">
                    <h2 class="Band-header">{{items[7].head}}</h2></div>
            </div>
        </div>
    </section>
    <!--7 band-->
    <section class="adv_row Bands--6up" data-bi="{&quot;location&quot;:&quot;Homepage&quot;,&quot;component&quot;:&quot;6up&quot;}">
        <div class="col Bands-item">
            <div class="ProductBand">
                <a class="ProductBand-link" data-context="{&quot;content&quot;:&quot;curry5112818&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[7].images[0].link}}">
                    <picture><img src="{{items[7].images[0].pc_image}}" alt="" class="ProductBand-img"></picture>
                    <h4 class="ProductBand-header"><span class="brand">{{items[7].images[0].descriptions[0]}}</span><span class="name">{{items[7].images[0].descriptions[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4></a>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="ProductBand">
                <a class="ProductBand-link" data-context="{&quot;content&quot;:&quot;retro12_112818&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[7].images[1].link}}">
                    <picture><img src="{{items[7].images[1].pc_image}}" alt="" class="ProductBand-img"></picture>
                    <h4 class="ProductBand-header"><span class="brand">{{items[7].images[1].descriptions[0]}}</span><span class="name">{{items[7].images[1].descriptions[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4></a>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="ProductBand">
                <a class="ProductBand-link" data-context="{&quot;content&quot;:&quot;Kyrie5112818&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[7].images[2].link}}">
                    <picture><img src="{{items[7].images[2].pc_image}}" alt="" class="ProductBand-img"></picture>
                    <h4 class="ProductBand-header"><span class="brand">{{items[7].images[2].descriptions[0]}}</span><span class="name">{{items[7].images[2].descriptions[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4></a>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="ProductBand">
                <a class="ProductBand-link" data-context="{&quot;content&quot;:&quot;pumathunderelectric_112818&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[7].images[3].link}}">
                    <picture><img src="{{items[7].images[3].pc_image}}" alt="" class="ProductBand-img"></picture>
                    <h4 class="ProductBand-header"><span class="brand">{{items[7].images[3].descriptions[0]}}</span><span class="name">{{items[7].images[3].descriptions[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4></a>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="ProductBand">
                <a class="ProductBand-link" data-context="{&quot;content&quot;:&quot;nikeairvapormaxflyknit2_112818&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[7].images[4].link}}">
                    <picture><img src="{{items[7].images[4].pc_image}}" alt="" class="ProductBand-img"></picture>
                    <h4 class="ProductBand-header"><span class="brand">{{items[7].images[4].descriptions[0]}}</span><span class="name">{{items[7].images[4].descriptions[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4></a>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="ProductBand">
                <a class="ProductBand-link" data-context="{&quot;content&quot;:&quot;ultraboostmetal_112819&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[7].images[5].link}}">
                    <picture><img src="{{items[7].images[5].pc_image}}" alt="" class="ProductBand-img"></picture>
                    <h4 class="ProductBand-header"><span class="brand">{{items[7].images[5].descriptions[0]}}</span><span class="name">{{items[7].images[5].descriptions[1]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></span></h4></a>
            </div>
        </div>
    </section>
    <!--7 band end-->

    <section class="adv_row Bands--1up">
        <div class="col Bands-item">
            <div class="Band TitleBand light align-top align-center">
                <div class="Band-content">
                    <h2 class="Band-header">{{items[8].head}}</h2></div>
            </div>
        </div>
    </section>
    <!--8 band-->
    <section class="adv_row Bands--4up" data-bi="{&quot;location&quot;:&quot;Homepage&quot;,&quot;component&quot;:&quot;4up&quot;}">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;Socialchampionslides&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[0].link}}">
                    <picture><img src="{{items[8].images[0].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;Socialchampionslides&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[0].link}}">{{items[8].images[0].descriptions[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;SocialFilaDisruptor2&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[1].link}}">
                    <picture><img src="{{items[8].images[1].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;SocialFilaDisruptor2&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[1].link}}">{{items[8].images[1].descriptions[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;socialtimberland&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[2].link}}">
                    <picture><img src="{{items[8].images[2].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;socialtimberland&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[2].link}}">{{items[8].images[2].descriptions[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a tabindex="-1" class="" data-context="{&quot;content&quot;:&quot;socialnikevapormaxplus&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[3].link}}">
                    <picture><img src="{{items[8].images[3].pc_image}}" alt="" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" data-context="{&quot;content&quot;:&quot;socialnikevapormaxplus&quot;,&quot;type&quot;:&quot;&quot;}" href="{{items[8].images[3].link}}">{{items[8].images[3].descriptions[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--8 band end-->
    <!--9 brand-->
    <section class="adv_row Bands--1up" data-bi="{&quot;location&quot;:&quot;Homepage&quot;,&quot;component&quot;:&quot;1up&quot;}">
        <div class="col Bands-item">
            <div class="Band HeroBand light align-top align-center">
                <a class="" href="{{items[9].images[0].link}}">
                    <picture>
                        <source srcset="{{items[9].images[0].mobile_image}}" media="(max-width: 37.4375em)"><img src="{{items[9].images[0].pc_image}}" alt="Ill-ustrated" class="Band-image"></picture>
                </a>
                <div class="Band-content">
                    <h2 class="Band-header">{{items[9].head}}</h2>
                    <p></p>
                    <ul class="btn-group">
                        <li><a class="c-btn c-btn--primary" href="/category/collection/timberland-x-champion.html">{{items[9].contents[0]}}<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
  <path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>
                        <!--<li><a class="c-btn c-btn--alt" href="https://www.youtube.com/watch?v=7YeaIXrqMGs">Watch the Video<span class="c-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" class="injected-svg" data-src="/built/69/app/icons/base/ic_arrow_chevron.svg" focusable="false" aria-hidden="true">
<path d="M94.35 0l-35.7 35.7L175.95 153 58.65 270.3l35.7 35.7 153-153z"></path>
</svg></span></a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--9 brand end-->
</script>
<script type="text/javascript">
    var url = window.config.platform.url;
    url = url.replace("en/","");
    url += "pages/ajax/home_adv_products.json";
    //发送ajax请求
    $.post(url,function(data) {
//        data = JSON.parse(data);
        var advProductHtml = template('band', data);
        $("#ad_product").html(advProductHtml);
    });
</script>