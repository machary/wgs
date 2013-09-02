$(function(){
    var oTable = $('#table_penyalur').dataTable( {
        "bJQueryUI": true,
        "sScrollX": "",
        "bSortClasses": false,
        "aaSorting": [[0,'asc']],
        "bAutoWidth": true,
        "bInfo": true,
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "full_numbers",
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : baseUrl + '/cms/guncategory/dataapi',
        "fnServerData" : function(sSource, aoData, fnCallback)
        {
            //filter category content
            if(jQuery('#region-filter').length)
            {

                var filter = jQuery('#region-filter').val()
                aoData.push({
                    "name": "filter",
                    "value":  filter
                });


                var filterName = '';
                var filterValue = '';

                if($('#kabupaten-filter').val() != 0)
                {
                    var filterName = 'kabupaten';
                    var filterValue = $('#kabupaten-filter').val();
                }
                else if($('#propinsi-filter').val() != 0)
                {
                    var filterName = 'provinsi';
                    var filterValue = $('#propinsi-filter').val();
                }

                else if($('#region-filter').val() != '' )
                {
                    var filterName = 'region';
                    var filterValue = $('#region-filter').val();
                }
                aoData.push({
                        "name": 'arealevel',
                        "value":  filterName},
                    {
                        "name": 'areaid',
                        "value":  filterValue});

            }

            jQuery.getJSON(sSource, aoData, function(json) {
                fnCallback(json);
            });
        }
    });
});