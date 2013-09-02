/*
* Berisi fungsi2 untuk menampilkan data intelijen di peta operasional
* @author : tajhul.faijin
* */

//render point intelijen
function intelPoints( $layer, $url ) {
    $.getJSON( $url,
    {
        "matra": null //sementara ga pake filter per matra
    },
    function(data) {

        if( data.length > 0 ){
            var ll, popupContentHTML;
           	var AutoSizeFramedCloud = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
           		'autoSize': true
           	});
            var size = new OpenLayers.Size(23,23);
            var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);

            for(i in data) {
                if(data[i].point !== undefined){
                    var iconIMG = null;
                    switch(data[i].matra){
                        case 'darat' :
                            iconIMG = 'red-intel-AD.png';
                            break;
                        case 'laut' :
                            iconIMG = 'red-intel-AL.png';
                            break;
                        case 'udara' :
                            iconIMG = 'red-intel-AU.png';
                            break;
                    }

                    //koordinate (multiple koordinate)
                    var points = data[i].point.split('|'); //<-- | = separator dari inputan erlan
                    if( points.length > 0 ){
                        var n = 1;
                        for(j in points){
                            var koor = points[j].split(',');
                            ll = new OpenLayers.LonLat( koor[0], koor[1]);
                            popupContentHTML = '<p><b>'+ data[i].negara +'</b></p>';
                            var icon = new OpenLayers.Icon(baseUrl + '/images/icons/' + iconIMG, size, offset);
                            addMarker($layer, ll, AutoSizeFramedCloud, popupContentHTML, true, true, icon);
                        }
                    }
                }
            }
        }
    });
}