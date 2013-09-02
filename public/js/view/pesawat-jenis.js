$(function(){
    var oTable = $('#table_pesawat_jenis').dataTable( {
        "bJQueryUI": true,
        "sScrollX": "",
        "bSortClasses": false,
        "aaSorting": [[0,'asc']],
        "aoColumns": [ null, null, {bSortable:false} ],
        "bAutoWidth": true,
        "bFilter": true,
        "bInfo": true,
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "full_numbers",
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : baseUrl + '/cms/pesawat-jenis/dataapi',
        "fnServerData" : function(sSource, aoData, fnCallback)
        {
            jQuery.getJSON(sSource, aoData, function(json) {
                fnCallback(json);
            });
        }
    })
});