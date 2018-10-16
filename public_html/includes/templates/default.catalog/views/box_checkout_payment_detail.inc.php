<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/10/7
     * Time: 21:51
     * 订单支付卡号页面
     */
    $pc_url = WS_DIR_IMAGES.'payment/pc/';
    ?>
<head>
    <style type="text/css">
        body{
            border:1px solid #FFCC99;
            font-size: 9pt;
            font-family:"Segoe UI", Verdana, Helvetica, Sans-Serif;
        }
    </style>
<link href="<?php echo  $pc_url.'global3.css'?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo $pc_url.'en.css'?>" rel="stylesheet" type="text/css"/>
<?php //document::$snippets['foot_tags'][] = '<script src="/litecart/public_html/images/payment/pc/jquery-1.js" type="text/javascript">'; ?>
</head>
<div class="lanclick">
</div>
<div style="border: 1px solid #FFCC99;padding-left: 5px;padding-right: 5px;">
<!--    <div class="forms">-->
            <div id="creditcardinfo">
                <h2 id="lblCredit">Credit Card Information</h2>
                <dl class="creditcardnumber">
                    <dt>
                        <label for="CreditCardNumber" id="lblCardNo_LBL">Credit card number </label>
                    </dt>
                    <dd>
                        <input class="" maxlength="16"
                               data-val="true"
                               data-val-length="The length you entered is over the limit"
                               data-val-length-max="16"
                               data-val-length-min="16"
                               data-val-regex="The format you entered is incorrect"
                               data-val-regex-pattern="[\d]{12,19}"
                               data-val-required="This field is required" id="CreditCardNumber"
                               name="CardPAN" type="text"
                               onkeyup="changeCreditCard(this);" />
                        <div id="supportedcreditcards">
                            <img style="opacity: 0.5; display: inline;" src="<?php echo  $pc_url.'Visa.png'?>" alt="Visa" />
                            <img style="opacity: 0.5; display: inline;" src="<?php echo  $pc_url.'MasterCard.png'?>" alt="MasterCard" />
                            <img style="opacity: 0.5; display: inline;" src="<?php echo  $pc_url.'jcb.png'?>" alt="Jcb" />

                        </div>
                        <span class="required">*</span>
                        <span class="field-validation-error"
                              data-valmsg-for="CreditCardNumber" data-valmsg-replace="true">

							<span generated="true" htmlfor="CreditCardNumber" id="label_cardnum"></span>
							</span>
                        <span class="tips" id="order_erorr_note"><strong>Note:</strong> Do not enter dashes or spaces</span>
                    </dd>
                </dl>
                <dl class="expirationdate">
                    <dt>
                        <label for="ExpirationDate" id="lblExpDate_LBL">Expiration date </label>
                    </dt>
                    <dd>
                        <select name="ExpirationMonth" id="ddlMonth">
                            <option value="01" selected="selected">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select> / <select name="ExpirationYear" id="ddlYear">
                            <?php
                                for ($i = date('Y'); $i < date('Y') + 15; $i++) {
                                    echo '<option value=' . strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) .($i==0?'selected="selected"':""). '>' . strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) . '</option>';
                                }
                            ?>

                        </select> <span class="required">*</span> <span
                                class="field-validation-error" data-valmsg-for="ExpirationDate"
                                data-valmsg-replace="true" id="lblExpire"></span>
                    </dd>
                </dl>
                <dl class="securitycode">
                    <dt>
                        <label for="SecurityCode" id="lblCVV_LBL">CVV2/CVC2/CAV2</label>
                    </dt>
                    <dd>
                        <input maxlength="4" data-val="true"
                               data-val-length="The length you entered is over the limit"
                               data-val-length-max="4"
                               data-val-regex="The format you entered is incorrect"
                               data-val-regex-pattern="[\d]{3,4}"
                               data-val-required="This field is required" id="SecurityCode"
                               name="CVV2" type="password" onkeyup="this.value=this.value.replace(/\D/g,'')" />
                        <span class="required">*</span>
                        <span class="help">
                            (<a href="javascript:" id="cvvhelp">What's this?</a>)
                            <img
                                    src="<?php echo $pc_url.'securitycode.gif'?>" alt="" />
                        </span><span
                                class="field-validation-error" data-valmsg-for="SecurityCode"
                                data-valmsg-replace="true" id="lblCVVNOTE_LBL"></span> <span class="tips" id="cvvnote"><strong>Note:</strong> Usually last 3 digits in the back of the card</span>
                    </dd>
                </dl>
            </div>
<!--    </div>-->
</div>
<script type="text/javascript">
    /**
     * 当输入不同的信用卡号时，出现不同的图
     * @param obj
     */
    function changeCreditCard(obj) {
        obj.value=obj.value.replace(/\D/g,'');
        var supportedcreditcards = $("#supportedcreditcards");
        var creditcardnumber = $("#CreditCardNumber");
        var securitycode = $("#SecurityCode");
        var cardtype = $("#CardType");

        if ($.trim(creditcardnumber.val()).length < 2) {
            supportedcreditcards.find("img").css("opacity", "0.5").show();
        }
        else {
            supportedcreditcards.find("img").css("opacity", "1").hide();
            switch ($.trim(creditcardnumber.val()).substr(0, 2)) {
                case "40":
                case "41":
                case "42":
                case "43":
                case "44":
                case "45":
                case "46":
                case "47":
                case "48":
                case "49":
                    supportedcreditcards.find("img[alt='Visa']").show();
                    creditcardnumber.attr("maxlength", "16");
                    securitycode.attr("maxlength", "3");
                    cardtype.val("V");//赋值
                    break;
                case "51":
                case "52":
                case "53":
                case "54":
                case "55":
                    supportedcreditcards.find("img[alt='MasterCard']").show();
                    creditcardnumber.attr("maxlength", "16");
                    securitycode.attr("maxlength", "3");
                    cardtype.val("M");//赋值
                    break;
                case "35":
                    supportedcreditcards.find("img[alt='Jcb']").show();
                    creditcardnumber.attr("maxlength", "16");
                    securitycode.attr("maxlength", "3");
                    cardtype.val("J");//赋值
                    break;
                case "34":
                case "37":
                    supportedcreditcards.find("img[alt='AmericanExpress']").show();
                    creditcardnumber.attr("maxlength", "15");
                    securitycode.attr("maxlength", "4");
                    cardtype.val("A");//赋值
                    break;
                case "30":
                case "36":
                case "38":
                case "39":
                case "60":
                case "64":
                case "65":
                    supportedcreditcards.find("img[alt='Discover']").show();
                    creditcardnumber.attr("maxlength", "16");
                    securitycode.attr("maxlength", "3");
                    cardtype.val("D");//赋值
                    break;
                default:cardtype.val("");//赋值
            }
        }
    };

    /**
     * 显示
     */
    function showCVVImage() {

    }
</script>

