/// <reference path="jquery-1.5.1-vsdoc.js"/>
/// <reference path="jquery.validate.js"/>

String.empty = "";

String.isNullOrEmpty = function (text) {
    if (text == null || text == String.empty) {
        return true;
    }

    return false;
};

String.isNullOrWhiteSpace = function (text) {
    if (text == null || $.trim(text) == String.empty) {
        return true;
    }

    return false;
};

// TIMERS
$.fn.extend({
    everyTime: function (interval, label, fn, times) {
        return this.each(function () {
            $.timer.add(this, interval, label, fn, times);
        });
    },
    oneTime: function (interval, label, fn) {
        return this.each(function () {
            $.timer.add(this, interval, label, fn, 1);
        });
    },
    stopTime: function (label, fn) {
        return this.each(function () {
            $.timer.remove(this, label, fn);
        });
    }
});

$.extend({
    timer: {
        global: [],
        guid: 1,
        dataKey: "jQuery.timer",
        regex: /^([0-9]+(?:\.[0-9]*)?)\s*(.*s)?$/,
        powers: {
            // Yeah this is major overkill... 
            "ms": 1,
            "cs": 10,
            "ds": 100,
            "s": 1000,
            "das": 10000,
            "hs": 100000,
            "ks": 1000000
        },
        timeParse: function (value) {
            if (value == undefined || value == null)
                return null;

            var result = this.regex.exec($.trim(value.toString()));

            if (result[2]) {
                var num = parseFloat(result[1]);
                var mult = this.powers[result[2]] || 1;
                return num * mult;
            } else {
                return value;
            }
        },
        add: function (element, interval, label, fn, times) {
            var counter = 0;

            if ($.isFunction(label)) {
                if (!times)
                    times = fn;
                fn = label;
                label = interval;
            }

            interval = $.timer.timeParse(interval);

            if (typeof interval != "number" || isNaN(interval) || interval < 0)
                return;

            if (typeof times != "number" || isNaN(times) || times < 0)
                times = 0;

            times = times || 0;

            var timers = $.data(element, this.dataKey) || $.data(element, this.dataKey, {});

            if (!timers[label])
                timers[label] = {};

            fn.timerID = fn.timerID || this.guid++;

            var handler = function () {
                if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
                    $.timer.remove(element, label, fn);
            };

            handler.timerID = fn.timerID;

            if (!timers[label][fn.timerID])
                timers[label][fn.timerID] = window.setInterval(handler, interval);

            this.global.push(element);

        },
        remove: function (element, label, fn) {
            var timers = $.data(element, this.dataKey), ret;

            if (timers) {

                if (!label) {
                    for (label in timers)
                        this.remove(element, label, fn);
                } else if (timers[label]) {
                    if (fn) {
                        if (fn.timerID) {
                            window.clearInterval(timers[label][fn.timerID]);
                            delete timers[label][fn.timerID];
                        }
                    } else {
                        for (var fn in timers[label]) {
                            window.clearInterval(timers[label][fn]);
                            delete timers[label][fn];
                        }
                    }

                    for (ret in timers[label]) break;

                    if (!ret) {
                        ret = null;
                        delete timers[label];
                    }
                }

                for (ret in timers) break;

                if (!ret)
                    $.removeData(element, this.dataKey);
            }
        }
    }
});

$(window).bind("unload", function () {
    $.each($.timer.global, function (index, item) {
        $.timer.remove(item);
    });
});

// COOKIE
$.cookie = function (name, value, options) {
    if (typeof value != "undefined") { // name and value given, set cookie
        options = options || {};

        if (value === null) {
            value = String.empty;
            options.expires = -1;
        }

        var expires = String.empty;

        if (options.expires && (typeof options.expires == "number" || options.expires.toUTCString)) {
            var date;

            if (typeof options.expires == "number") {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }

            expires = "; expires=" + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }

        var path = options.path ? "; path=" + options.path : String.empty;
        var domain = options.domain ? "; domain=" + options.domain : String.empty;
        var secure = options.secure ? "; secure" : String.empty;

        document.cookie = [name, "=", encodeURIComponent(value), expires, path, domain, secure].join(String.empty);
    } else { // only name given, get cookie
        var cookieValue = null;

        if (document.cookie && document.cookie != String.empty) {
            var cookies = document.cookie.split(";");

            for (var i = 0; i < cookies.length; i++) {
                var cookie = $.trim(cookies[i]);

                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + "=")) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }

        return cookieValue;
    }
};

$(document).ready(function () {
    $("#language").find("select").change(function () {
        var lang = $(this).val();

        if (!String.isNullOrWhiteSpace(lang)) {
            alert(lang);
        }
    });

    // fix maxlength
    $("form").find("input[data-val-length-max]").each(function () {
        $(this).attr("maxlength", $(this).attr("data-val-length-max"));
    });

    // BODY.PAYMENT
    $("body.payment").each(function () {
        var timeZone;
        var regex = new RegExp("UTC([\+\-]{1}[01]{1}[0-9]{1}[0134]{1}[05]{1})", "i");

        var results = regex.exec(new Date().toString());

        if (results != null && results.length > 1) {
            timeZone = results[1];
        } else {
            timeZone = "00000";
        }

        $.cookie(".VTTZ", timeZone, null);
        $.cookie(".VTCP", false, null);

        $("#CreditCardNumber").bind("paste", function () {
            $.cookie(".VTCP", true, null);
        });

        // copy address
        $("#copyaddress").change(function () {
            if ($(this).attr("checked")) {
                $("#BillingFirstName").val($("#ShippingFirstName").val());
                $("#BillingLastName").val($("#ShippingLastName").val());
                $("#BillingAddress1").val($("#ShippingAddress1").val());
                $("#BillingAddress2").val($("#ShippingAddress2").val());
                $("#BillingCity").val($("#ShippingCity").val());
                $("#BillingState").val($("#ShippingState").val());
                $("#BillingZipcode").val($("#ShippingZipcode").val());
                $("#BillingCountry").val($("#ShippingCountry").val());
                $("#BillingTelephone").val($("#ShippingTelephone").val());
            }
        });

        // credit card type
        Payment.ChangeCreditCard();

        $("#CreditCardNumber").keyup(function () {
            Payment.ChangeCreditCard();
        });

        $("#CreditCardNumber").change(function () {
            Payment.ChangeCreditCard();
        });

        Payment.ChangeCountry("Shipping");
        Payment.ChangeCountry("Billing");

        $("#ShippingCountry").change(function () {
            Payment.ChangeCountry("Shipping");
        });

        $("#BillingCountry").change(function () {
            Payment.ChangeCountry("Billing");
        });

        $("#ShippingState-US,#ShippingState-CA,#BillingState-US,#BillingState-CA").change(function () {
            var objId = $(this).attr("id").toString();

            $("#" + objId.substring(0, objId.length - 3)).val($(this).val());
        });

        $("#shippingaddress h3,#billingaddress h3").click(function () {
            $(this).parent().toggleClass("collapse");
        });

        $("input[name='CreditCardType']").change(function () {
        });
    });

    // BODY.REDIRECT
    $("body.redirect").each(function () {
        var backButton = $(this).find("#back-to-merchant");
        var returnUrl = backButton.attr("data-return-url");

        backButton.click(function () {
            backButton.stopTime();

            document.location = returnUrl;
        });

        backButton.oneTime("8s", "back-to-merchant", function () {
            document.location = returnUrl;
        });
    });
});

var Payment = {};

Payment.ChangeCountry = function (addressType) {
    var country = $("#" + addressType + "Country").val();

    if (country == "US") {
        $("#" + addressType + "State").hide();
        $("#" + addressType + "State-US").show();
        $("#" + addressType + "State-CA").hide();

        $("#" + addressType + "State-US").val($("#" + addressType + "State").val());
    }
    else if (country == "CA") {
        $("#" + addressType + "State").hide();
        $("#" + addressType + "State-US").hide();
        $("#" + addressType + "State-CA").show();

        $("#" + addressType + "State-CA").val($("#" + addressType + "State").val());
    }
    else {
        $("#" + addressType + "State").show();
        $("#" + addressType + "State-US").hide();
        $("#" + addressType + "State-CA").hide();
    }
};

Payment.ChangeCreditCard = function () {
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
var R_R = {
		'en-US': {
			required:" This field is required ",
			label_cardnum:"The credit card information you entered is invalid",
			cardnumerror:"The format you entered is incorrect",
			lblExpire:"",
			lblCVVNOTE_LBL:""
	    },
	    'de-de': {
	    	required:"Pflichtfeld",
			label_cardnum:"Ungültige Kreditkarteninformationen: Die Kartennummer ist nicht korrekt  ",
			cardnumerror:"Das Format der Buchstaben ist nicht korrekt"
	    },
	    'it-IT': {
	    	required:" Queste frasi sono necessarie",
			label_cardnum:"Le informazioni inserite da Lei non è valido ",
			cardnumerror:"Il formato inserito da Lei non corretto"
	    },
	    'ja-ja': {
	    	required:"これは必要なフィールドです  ",
			label_cardnum:"ご記入のクレジットカード情報は無効です",
			cardnumerror:"ご記入のフォーマットは正しくありません"
	    },
	    'fr-fr': {
	    	required:"Ce champ est obligatoire ",
			label_cardnum:"Les informations sur la carte de crédit que vous avez saisies sont invalides",
			cardnumerror:"Le format de votre saisie est incorrect"
	    },
	    'es-es': {
	    	required:"Este campo es necesario ",
			label_cardnum:"Inválida introducción de su información de tarjeta de crédito ",
			cardnumerror:"El formato que usted entra es incorrecto"
	    },
	    'ru-md': {
	    	required:"Это поле обязательно для заполнения ",
			label_cardnum:"Недействительная информация о кредитной карты",
			cardnumerror:"Неверный формат"
	    },
	    'da-da': {
	    	required:"Dette felt er kræves",
			label_cardnum:"De indtastede kreditkort oplysninger er ugyldigt ",
			cardnumerror:"Det indtastede format er ugyldigt"
	    },
	    'no-no': {
	    	required:"Dette feltet er påkrevd ",
			label_cardnum:"Kredittkortet informasjonen du skrev inn er ugyldig",
			cardnumerror:"Formatet du har angitt er feil"
	    },
	    'nl-nl': {
	    	required:"Dit veld is verplicht ",
			label_cardnum:"De gegevens van de kredietkaart die je hebt ingevoerd is ongeldig ",
			cardnumerror:"De gegevens van de kredietkaart die je hebt ingevoerd is ongeldig"
	    },
	    'sv-sv': {
	    	required:"Detta fält är tvingande",
			label_cardnum:"Den kreditkortsinformation du angav är ogiltigt ",
			cardnumerror:"Formatet är felaktigt"
	    },
	    'tr-tr': {
	    	required:" This field is required ",
			label_cardnum:"The credit card information you entered is invalid",
			cardnumerror:"The format you entered is incorrect"
	    },
	    'ms-ms': {
	    	required:" Medan ini dikehendaki ",
			label_cardnum:"Maklumat kad kredit anda masukkan tidak sah",
			cardnumerror:"Format yang anda masukkan tidak betul"
	    },
	    'id-id': {
	    	required:" Bidang ini diperlukan ",
			label_cardnum:"Informasi kartu kredit Anda masukkan tidak valid",
			cardnumerror:"Format yang Anda masukkan salah"
	    }
	};
function checkLanguage(){

	var language = document.getElementById("Select2").options[document.getElementById("Select2").options.selectedIndex].value;//获取选择的语言
    language = language.substring(0,2).toLowerCase();
    switch(language){
        case 'zh':            checkForm('en-US');          break;
        case 'en':            checkForm('en-US');          break;
        case 'de':            checkForm('de-de');          break;
        case 'it':            checkForm('it-IT');          break;
        case 'ja':            checkForm('ja-ja');          break;
        case 'fr':            checkForm('fr-fr');          break;
        case 'es':            checkForm('es-es');          break;
        case 'ru':            checkForm('ru-md');          break;
        case 'da':            checkForm('da-da');          break;
        case 'no':            checkForm('no-no');          break;
        case 'sv':            checkForm('sv-sv');          break;
        case 'nl':            checkForm('nl-nl');          break;
        case 'pl':            checkForm('en-US');          break;
        case 'tr':            checkForm('tr-tr');          break;
        case 'he':            checkForm('en-US');          break;
        case 'ms':            checkForm('ms-ms');          break;
        case 'id':			  checkForm('id-id');          break;
        default:              checkForm('en-US');          break;
    }

}
function checkForm(lang){
	lang = lang || '';
	var label_cardnum = document.getElementById("label_cardnum");
	var lblExpire = document.getElementById("lblExpire");
	var lblCVVNOTE_LBL = document.getElementById("lblCVVNOTE_LBL");
	
	var ddlYear=document.getElementById("ddlYear");
	var ddlMonth=document.getElementById("ddlMonth");
  	var cardtype=document.getElementById("CardType");//card org
  	var cardnum=document.getElementById("CreditCardNumber").value;//card num
  	var cvv=document.getElementById("SecurityCode").value;//cvv code

	if(cardnum==""){
		label_cardnum.innerHTML = R_R[lang]["required"];
		return false;
		}
	if(cardtype.value==""){
		label_cardnum.innerHTML = R_R[lang]["cardnumerror"];
		return false;
	}
	if(cardnum.length<16){
		label_cardnum.innerHTML = R_R[lang][label_cardnum.id];
  		return false;
  	}
	label_cardnum.innerHTML="";
	if(ddlYear.value!="" && ddlMonth.value!=""){
		  lblExpire.innerHTMLL="";
		  document.getElementById("ExpDate").value=ddlYear.value+ddlMonth.value;
	  }else{
		  lblExpire.innerHTML=R_R[lang]["required"];
		  return false;
	  }
	if(cvv==""||cvv.length<3) 
	  { 
		lblCVVNOTE_LBL.innerHTML=R_R[lang]["required"];
	  	return false;
	  }
		lblCVVNOTE_LBL.innerHTML="";
		 if (self != top) { 
             document.getElementById("IFrame").value = "1";
         }
         else { 
             document.getElementById("IFrame").value = "2";
         }
    document.getElementById('submit').style.background = "#CCC";
    document.getElementById('submit').style.color = "#FFF";

    document.getElementById('tranDiv').style.display = "";



		document.forms['formId'].submit(); 
		document.getElementById("submit").disabled=true ;
}
