$(function(){
    var oTable = $('#table_penyalur').dataTable( {
        "bJQueryUI": true,
        "sScrollX": "",
        "bSortClasses": false,
        "aaSorting": [[0,'asc']],
        "bAutoWidth": true,
        "bInfo": true,
        "aoColumns":[
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            {bSortable:false}
        ],
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "full_numbers",
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : baseUrl + '/cms/torpedo/dataapi',
        "fnServerData" : function(sSource, aoData, fnCallback)
        {
            //filter category content
            if(jQuery('#feed-filter').length)
            {
                filter = jQuery('#feed-filter').val()

                aoData.push({
                    "name": "filter",
                    "value":  filter
                });
            }

            jQuery.getJSON(sSource, aoData, function(json) {
                fnCallback(json);
            });
        }
    })//.makeEditable()
});