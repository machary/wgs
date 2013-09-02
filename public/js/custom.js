/**
 * Created by JetBrains PhpStorm.
 * User: MacHary
 * Date: 4/24/12
 * Time: 1:40 PM
 * To change this template use File | Settings | File Templates.
 */

$(function(){
    $("div.message").click(function(){
        $(this).fadeOut('slow');
    });
    $(".act-delete").live('click', function(){
        if(confirm('Anda yakin ?')){
            return true;
        } else {
            return false;
        }
    });

    // O2k7 skin (silver)
    tinyMCE.init({
        // General options
        mode : "textareas",
//        theme : "advanced",
        skin : "o2k7",
        skin_variant : "silver",

        editor_selector : "mceEditor",
        editor_deselector : "mceNoEditor",
        width:"100%",	
        plugins : "save,insertdatetime,paste,autosave,nonbreaking",		
        nonbreaking_force_tab : true,
	 entity_encoding: 'named',
        entities: '160,nbsp',

        height : "350",
	 width: "486",


        // Theme options
	 theme_advanced_buttons1 : "newdocument,|,italic,underline,|,justifyleft,justifycenter,justifyfull,|,pastetext,|,search,replace,|,outdent,indent,|,undo,redo",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "none",
        theme_advanced_resizing : true

        // Example content CSS (should be your site CSS)
//        content_css : "css/content.css",

        // Drop lists for link/image/media/template dialogs
//        template_external_list_url : "lists/template_list.js",
//        external_link_list_url : "lists/link_list.js",
//        external_image_list_url : "lists/image_list.js",
//        media_external_list_url : "lists/media_list.js"

        // Replace values for the template plugin
//        template_replace_values : {
//            username : "Some User",
//            staffid : "991234"
//        }
    });

});

/*
 * makePopupContent <untuk membuat content popup yg berisi simbol taktis?
 * @author : tajhul.faijin@sangkuriang.co.id
 * why ? dibuat untuk mengatasi masalah popup kekuatan sendiri/musuh yg kepotong kareana terbatasnya tinggi base layer
 * @param (int) first => index pertama dari jumlah array (biasanya 1)
 * @param (int) now => nilai dari counter looping
 * @param (int) index => nilai dari index looping
 * @param (int) end => offset, batas maximum
 * @param (array/obj) data => data untuk content popup
 * @param (string) tableClass => nama class table (musuh / sendiri)
 * tableClass ini berhubungan dgn styling table di custom-style.css
 * */
function makePopupContent(first, now, index, end, data, tableClass){
    index++;
    var result = '';
    if(first == now){
        result = '<td valign="top">';
        result += '<table class="child-'+ tableClass +'">';
        result += '<tr>';
        result += '<td>' + data.jumlah + '</td>';
        result += '<td>';
        result += '<img style="height: 25px; width: 21px;" src="' + baseUrl + '/' + data.filepath + '">';
        result += '</td>';
        result += '<td>' + data.singkatan + '</td>';
        result += '<td>' + data.keterangan + '</td>';
        result += '</tr>';
    }
    else if(now > first && now < end) {
        result = '<tr>';
        result += '<td>' + data.jumlah + '</td>';
        result += '<td>';
        result += '<img style="height: 25px; width: 21px;" src="' + baseUrl + '/' + data.filepath + '">';
        result += '</td>';
        result += '<td>' + data.singkatan + '</td>';
        result += '<td>' + data.keterangan + '</td>';
        result += '</tr>';
    }
    else if(end == now){
        result += '<tr>';
        result += '<td>' + data.jumlah + '</td>';
        result += '<td>';
        result += '<img style="height: 25px; width: 21px;" src="' + baseUrl + '/' + data.filepath + '">';
        result += '</td>';
        result += '<td>' + data.singkatan + '</td>';
        result += '<td>' + data.keterangan + '</td>';
        result += '</tr>';

        result += '</table>';
        result += '</td>';
    }
    return result;
}