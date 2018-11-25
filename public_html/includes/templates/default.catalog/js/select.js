/**
 * Created by zn on 2018/11/21.
 * this javascript provide product page Style\Color and Other options click
 */

/**
 * 点击常规Option
 * @param obj
 * @param hidden_name
 */
String.prototype.replaceAll = function(findStr,replaceStr){
    return this.replace(new RegExp(findStr,"gm"),replaceStr);
}
var BuilderTemplate = {
    commonTemplate:" <label style='text-transform: uppercase;font-weight: bold;float:left'>{groupName}</label>" +
    "<input class='form-control' type='text' name='{groupName}' value='' style='border:0px;width:auto;height:20px;float:right;color:#b2906a;font-weight:bold;float:left;' required='required'>" +
    "<div class='product_sizes_content' style='display: block;'><span data-info='product_color' class='product_sizes'>",
    nodeColorA:"<a href='javascript:return;' class='product_color' style='border-radius: 25px; width: 50px; height: 50px; background-color: {colorValue}; border: 0px none;'" +
    "pic_link='{link}' name='{groupName}' onclick='clickLinksOption(this,\"color_img\",\"{groupName}\");'></a>",
    nodeOtherA:"<a href='javascript:return;' name='{groupName}' onclick='clickOption(this,\"{groupName}\");" + "'>{optionValue}</a>",
    builderColorTemplate:function(groupName,link,colorValue) {
        var tmpA =  this.nodeColorA.replaceAll("{link}",link);
        tmpA = tmpA.replaceAll("{groupName}",groupName);
        tmpA = tmpA.replaceAll("{colorValue}",colorValue);
        return tmpA;
    },
    builderOtherTemplate:function(groupName,optionValue) {
        var tmpA =  this.nodeOtherA.replaceAll("{groupName}",groupName);
        tmpA = tmpA.replaceAll("{optionValue}",optionValue);
        return tmpA;
    },

    endStr:" </span></div>"
};

function clickOption(obj,hidden_name) {
    obj.style.background="#b2906a";
    obj.style.color = "#ffffff";
    obj.style.border = "1px solid #b2906a";
    obj.style.transition = "all .2s ease-in";
    obj.style.mozTransition = "all .2s ease-in";
    obj.style.webkitTransition = " all .2s ease-in";
    var optionValue = obj.innerHTML;
    var hidden = $('input[name="'+ hidden_name +'"]');
    hidden.val(optionValue);
    // 找到当前元素的父亲
    var parent = $(obj).parent();
    var childer = parent.children('a');
    childer.each(function(index, element){// 之前的要全部消除
        if(element!= obj) {
            element.style.background="#FFFFFF";
            element.style.color = "#000000";
            element.style.border = "1px solid #AFAFAF";
            element.style.transition = "all .2s ease-in";
            element.style.mozTransition = "all .2s ease-in";
            element.style.webkitTransition = "all .2s ease-in";
        }
    });
}
/**
 * 点击color选项
 * @param obj
 * @param viewImgId
 * @param hidden_name
 */
function clickLinksOption(obj,viewImgId,hidden_name) {
    var optionValue = obj.style.backgroundColor;
    obj.style.border="2px solid #000000";

    var hidden = $('input[name="'+ hidden_name +'"]');
    hidden.val(colorRGBtoHex(optionValue));
    var picLink = $(obj).attr("pic_link");
    if(picLink.length > 0) {
        $("#"+viewImgId).attr("src",picLink);
    }
    var parent = $(obj).parent();
    var childer = parent.children('a');
    childer.each(function(index, element){
        if(element!= obj) {
            element.style.border = "0px";
        }
    });
}
/**
 * 点击Style
 * @param obj
 * @param hidden_name
 */
function clickStyle(obj,hidden_name) {
    //改变样式
    var parent = $(obj).parent();
    var child = parent.children('div');
    child.each(function(index, element){
        if(element!= obj) {
            element.style.border="0px";
        }else {
            element.style.border="2px solid #000000";
            element.style.borderRadius="5px";
        }
    });
    //显示选中的值
    obj = $(obj);
    var hidden = $('input[name="'+ hidden_name +'"]');
    var optionValue = obj.attr("title");
    hidden.val(optionValue);
    //获取当前style对应的pid,gid,vid
    var idInfo = obj.attr("id_info");
    var groupName = obj.attr("group_name");
    idInfo = idInfo.split("_");
    var url = window.config.platform.url;
    url = url.replace("en/","");
    url += "pages/ajax/style_switch_ajax.php";
    var data={
        "pid":idInfo[0],
        "gid":idInfo[1],
        "vid":idInfo[2]
    };
    //发送ajax请求
    $.post(url,data,function(data) {
        data = JSON.parse(data);
        var  builderHtml = BuilderTemplate;
        for(var v in data) {
            var groupName = v;
            var options = data[v];
            var innerHtml =  builderHtml.commonTemplate.replaceAll("{groupName}",groupName);
            for(var option in options) {
                var value = option;
                var link = options[value];
                if(v.toLocaleLowerCase() === "color") {
                    innerHtml += builderHtml.builderColorTemplate(groupName,link,value);
                } else {
                    innerHtml += builderHtml.builderOtherTemplate(groupName,value);
                }
            }
            innerHtml += builderHtml.endStr;
            var div = document.getElementById(groupName);
            div.innerHTML = innerHtml;
        }
        // for(var v in data) {// 遍历页面，修改除Style以外的option内容
        //     var innerHtml = data[v];
        //     var id = "#"+v;
        //     var div = document.getElementById(v);
        //     div.innerHTML = innerHtml;
        // }
        // 默认切换每个style时选中第一个颜色框
        var colorObj = $("a.product_color").first();
        clickLinksOption(colorObj[0],'color_img','options[Color]');
    });
}
function colorRGBtoHex(color) {
    var rgb = color.split(',');
    var r = parseInt(rgb[0].split('(')[1]);
    var g = parseInt(rgb[1]);
    var b = parseInt(rgb[2].split(')')[0]);
    var hex = "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    return hex;
}