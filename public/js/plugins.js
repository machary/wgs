/**
 * Generate menu from json file to element with id  ...

 Creating an instance of XMLHttpRequest
 xul.fr
 */


function buildMenu(jsoncontent)
{
    var data = eval("(" + jsoncontent + ")");
    for(var i in data.mymenu)
    {
        if(data.mymenu[i].level == 1)
        {
            var line = "<li id='" + data.mymenu[i].id + "'><a href='" + data.mymenu[i].href + "'>";
            line += "<img src='" + imgUrl + data.mymenu[i].icon + "'/>";
            line += data.mymenu[i].title + "</a></li>";
            $("#navigation").append(line);
            $("ul#navigation > li#"+data.mymenu[i].id).append("<ul class='subnavigation dropdown ui-accordion ui-widget ui-helper-reset'></ul>");
        }
        else
        {
            var line = "<li class='ui-accordion-li-fix' id='" + data.mymenu[i].id + "'><a href='" + data.mymenu[i].href + "'>";
            line += "<img src='" + imgUrl + data.mymenu[i].icon + "'/>";
            line += data.mymenu[i].title + "</a></li>";
            $("ul#navigation > li#" + data.mymenu[i].parent + " > ul.subnavigation").append(line);
        }
    }
}

//var fname = jsUrl+"json/dynamic_menu.js";
function loadJSON()
{
    $.post(jsUrl+'cms/navigasi/getmenu',
    function(data){
        buildMenu(data);
    });
}

window.onload = loadJSON;