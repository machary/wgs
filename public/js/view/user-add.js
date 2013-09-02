var jab = [];
var subjab = [];
var skenario = [];

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
            null
        ],
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "full_numbers",
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : baseUrl + '/management/job/adddataapi',
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
                //if(aaData != 'undefined')
                //{
                fnCallback(json)
                //}
            });

            $('.custom-rows').parent().css('vertical-align','middle');
        },

        "fnDrawCallback"  : function(){
            $(' #jabatan ').each(function(){
                if(jQuery.inArray($(this).val(), jab) > 0)
                {
                    $(this).attr('selected', 'selected');
                }
            });
            $.uniform.update('#jabatan');

            $(' #subjabatan ').each(function(){
                if(jQuery.inArray($(this).val(), subjab) > 0)
                {
                    $(this).attr('selected', 'selected');
                }
            });
            $.uniform.update('#subjabatan');

            $(' #skenario ').each(function(){
                if(jQuery.inArray($(this).val(), skenario) > 0)
                {
                    $(this).attr('selected', 'selected');
                }
            });
            $.uniform.update('#skenario');
        }

    })//.makeEditable()

    $('table').delegate('#jabatan', 'change', function()
    {
        if($(this).is('#jabatan'))
        {
            jab.push($(this).val());
        }
        $('#jabatan_value').val(jab.join(','));
    });

    $('table').delegate('#subjabatan', 'change', function()
    {
        if($(this).is('#subjabatan'))
        {
            subjab.push($(this).val());
        }
        $('#subjabatan_value').val(subjab.join(','));
    });

    $('table').delegate('#skenario', 'change', function()
    {
        if($(this).is('#skenario'))
        {
            skenario.push($(this).val());
        }
        $('#skenario_value').val(skenario.join(','));
    });
});