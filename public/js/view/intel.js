var checkedArr = [];
var ceklis = [];

$(function(){
    var oTable = $('#table-cog').dataTable( {
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
            null
        ],
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "full_numbers",
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : baseUrl + '/peta/intelijen/dataapi',
        "fnServerData" : function(sSource, aoData, fnCallback)
        {
            //filter category content
            var $feed = $('#feed-filter' ).val();
            if($feed != 'unselected' || $feed != '')
            {
                //console.log( $feed );
                aoData.push({
                    "name": "feed-filter",
                    "value":  $feed
                });
            }

            jQuery.getJSON(sSource, aoData, function(json) {
                fnCallback(json)
            });

            //$('.custom-rows').parent().css('vertical-align','middle');
        },

        "fnDrawCallback"  : function(){
//            $(' input:select ').each(function(){
//                if(jQuery.inArray($(this).val(), checkedArr) > 0)
//                {
//                    $(this).attr('selected', 'selected');
//                }
//            });
//            $.uniform.update('input:select');
        }

    })//.makeEditable()

    $('table').delegate(':checkbox', 'click', function()
    {

        if($(this).is(':checked'))
        {
            //if(jQuery.inArray($(this).val(), checkedArr) < 0)
            //{
                checkedArr.push($(this).val());
            //}
            ceklis.push('T');
        }
        else
        {
//            if(jQuery.inArray($(this).val(), checkedArr) > 0)
//            {
//                checkedArr.splice(jQuery.inArray($(this).val(), checkedArr), 1);
//            }
            checkedArr.push($(this).val());
            ceklis.push('F');
        }
        $('#id_intelijen').val(checkedArr.join(','));
        $('#status').val(ceklis.join(','));
    });
});

function selectedValue(selectObj)
{
    var idx = selectObj.selectedIndex;
    var which = selectObj.options[idx].value;
    document.getElementById('filter_value').value = which;
}