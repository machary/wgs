$(function(){
    var oTable = $('#table_pesawat_al').dataTable( {
        "bJQueryUI": true,
        "sScrollX": "",
        "bSortClasses": false,
        "aaSorting": [[0,'asc']],
        "aoColumns": [ null, null, {bSortable:false} ],
        "bAutoWidth": true,
        "bInfo": true,
        "bFilter": true,
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "full_numbers",
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : baseUrl + '/cms/pesawat-al/dataapi',
        "fnServerData" : function(sSource, aoData, fnCallback)
        {
            jQuery.getJSON(sSource, aoData, function(json) {
                fnCallback(json);
            });
        }
    })
});