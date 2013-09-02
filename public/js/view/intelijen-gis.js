var wgs84 = new OpenLayers.Projection("EPSG:4326");
geoUrl = "http://192.168.1.28:8080/geoserver/wms";
format = 'image/png';
var map, panel, ov, longitude, latitude, info, popup, px;
var tiled, udara, laut, darat, all, zee, alki, teritorial;
var intel_darat_poly, intel_laut_poly, intel_udara_poly;
var intel_darat_point, intel_laut_point, intel_udara_point;
var pergerakan,titik_referensi;

$(function(){

    OpenLayers.ProxyHost= proxyUrl;
    var options = {
        controls: [],
        //maxExtent: bounds,
        maxResolution: 0.0780,
        projection: wgs84,
        units: 'degrees',
        restrictedExtent: new OpenLayers.Bounds(95.115729755272, -12.012413207701, 140.70672975527, 7.4095867922987)
    };

    map = standardMap('map');

    intel_darat_poly = new OpenLayers.Layer.Vector('Area Darat');
    intel_laut_poly = new OpenLayers.Layer.Vector('Area Laut');
    intel_udara_poly = new OpenLayers.Layer.Vector('Area Udara');

    pergerakan = new OpenLayers.Layer.Vector('Pergerakan Musuh');

    intel_udara_point = new OpenLayers.Layer.Markers("Point Udara");
    intel_laut_point = new OpenLayers.Layer.Markers("Point Laut");
    intel_darat_point = new OpenLayers.Layer.Markers("Point Darat");

    titik_referensi = new OpenLayers.Layer.Markers("Titik Referensi");

    intel_darat_poly.setVisibility(false);
    intel_laut_poly.setVisibility(false);
    intel_udara_poly.setVisibility(false);
    intel_darat_point.setVisibility(false);
    intel_laut_point.setVisibility(false);
    intel_udara_point.setVisibility(false);
    pergerakan.setVisibility(false);
    titik_referensi.setVisibility(false);

    map.addLayers([
        pergerakan,intel_darat_poly, intel_laut_poly, intel_udara_poly,
        intel_darat_point, intel_laut_point, intel_udara_point,
        titik_referensi
    ]);
    map.addControl(new OpenLayers.Control.Scale($('#scale').get(0)));
    map.redrawSwitcherTree();

    //queryLayers();
    changeMarkers();
});

function addMarkers(places, number)
{
    AutoSizeFramedCloud = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
        'autoSize': true
    });
    popupClass = AutoSizeFramedCloud;

//    var root1 = document.getElementById('info-cc');
//    var root2 = document.getElementById('info-cr');
//    var root3 = document.getElementById('info-cv');
    for(var i in places)
    {
        popupContentHTML = '<table style="background: #fff;" class="popup musuh">'
            +'<tr>'
            +'<th colspan="3">'
            + places[i]['negara']
            +'</th>'
            +'</tr>';

	        var splitJmlKekuatan = places[i]['jumlah_kekuatan'].split('|');
	        var splitFilePath = places[i]['filepath'].split('|');
            var splitKeterangan = places[i]['keterangan'].split('|');
	        var hitung = splitJmlKekuatan.length - 1;
	        for(var h=0;h<=hitung;h++)
	        {
		        popupContentHTML += '<tr>'
			        +'<td>' + splitJmlKekuatan[h] + '</td>'
			        +'<td>'
			        +'<img style="height: 25px; width: 21px;" src="' + baseUrl + '/' + splitFilePath[h] + '">'
			        +'</td>'
                    +'<td>' + splitKeterangan[h] + '</td>'
		            +'</tr>';
	        }
	    popupContentHTML += '</table>';

//        var ccE = document.createElement('cc');
//        var crE = document.createElement('cr');
//        var cvE = document.createElement('cv');
//
//        var ccT = document.createTextNode(places[i]['critical_capabilities']);
//        var crT = document.createTextNode(places[i]['critical_requirement']);
//        var cvT = document.createTextNode(places[i]['critical_vinerability']);

//        ccE.appendChild(ccT);
//        crE.appendChild(crT);
//        cvE.appendChild(cvT);
//
//        root1.appendChild(ccE);
//        root2.appendChild(crE);
//        root3.appendChild(cvE);

	    switch(number)
	    {
		    case 1:
			    var iconUrl = baseUrl + '/images/icons/red-ops-AU.png';
			    break;
		    case 2:
			    var iconUrl = baseUrl + '/images/icons/red-ops-AL.png';
			    break;
		    case 3:
			    var iconUrl = baseUrl + '/images/icons/red-ops-AD.png';
			    break;
	    }

        if(places[i]['point'] != undefined)
        {
            var lonlankoor = places[i]['point'].split(',');
            var realKoordinate = new OpenLayers.LonLat(lonlankoor[0], lonlankoor[1]);

            var size = new OpenLayers.Size(21,25);
            var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
            var icon = new OpenLayers.Icon(iconUrl, size, offset);

            switch(number)
            {
                case 1:
                    addMarker(intel_udara_point, realKoordinate, popupClass, popupContentHTML, true, false, icon);
                    break;
                case 2:
                    addMarker(intel_laut_point, realKoordinate, popupClass, popupContentHTML, true, false, icon);
                    break;
                case 3:
                    addMarker(intel_darat_point, realKoordinate, popupClass, popupContentHTML, true, false, icon);
                    break;
            }
        }
    }
}

function changeMarkers()
{
    map.events.register('changelayer', null, function(evt){
            switch( true ){
                case (evt.layer.name == 'Point Udara' && intel_udara_point.visibility == true) :
                        parseData(1, 'point', '/peta/intelijen/markers', 1);
                    break;

                case (evt.layer.name == 'Point Laut' && intel_laut_point.visibility == true) :
                        parseData(2, 'point', '/peta/intelijen/markers', 2);
                    break;

                case (evt.layer.name == 'Point Darat' && intel_darat_point.visibility == true) :
                        parseData(3, 'point', '/peta/intelijen/markers', 3);
                    break;

                case (evt.layer.name == 'Area Udara' && intel_udara_poly.visibility == true) :
                        parseData(1, 'polygon', '/peta/intelijen/polygon', 1);
                    break;

                case (evt.layer.name == 'Area Laut' && intel_laut_poly.visibility == true) :
                        parseData(2, 'polygon', '/peta/intelijen/polygon', 2);
                    break;

                case (evt.layer.name == 'Area Darat' && intel_darat_poly.visibility == true) :
                        parseData(3, 'polygon', '/peta/intelijen/polygon', 3);
                    break;

                case (evt.layer.name == 'Pergerakan Musuh' && pergerakan.visibility == true) :
                        parseData(0, 'pergerakan', '/peta/intelijen/movement', 0);
                    break;
                case (evt.layer.name == 'Titik Referensi' && titik_referensi.visibility == true) :
                    parseData(1, 'referensi', '/peta/api/referensi', 0);
                    break;
            }
    });
}

function parseData(id_divisi, jenis, url, matra)
{
    $.post(baseUrl + url,
        {
            "layer_id":id_divisi,
            "id_divisi": id_divisi
        },
        function(data){
            switch(jenis)
            {
                case 'point':
                    addMarkers( data, matra );
                    break;
                case 'polygon':
                    addPolygons( data, matra );
                    break;
                case 'pergerakan':
                    addPergerakan(data);
                    break;
                case 'referensi':
                    addReferensi(data);
                    break
            }
    }, "json");
}

function addReferensi(data)
{
    AutoSizeFramedCloud = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
        'autoSize': true
    });
    popupClass = AutoSizeFramedCloud;

    for(var i in data)
    {
        popupContentHTML = '<table style="width: 250px;">'
            +'<tr>'
            +'<th>'
            + data[i]['keterangan']
            +'</th>'
            +'</tr>'
            +'</table>';

        var iconUrl = baseUrl + '/images/info.png';

        if(data[i]['longitude'] != undefined && data[i]['latitude'] != undefined)
        {
            var realKoordinate = new OpenLayers.LonLat(data[i]['longitude'], data[i]['latitude']);

            var size = new OpenLayers.Size(21,25);
            var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
            var icon = new OpenLayers.Icon(iconUrl, size, offset);

            addMarker(titik_referensi, realKoordinate, popupClass, popupContentHTML, true, false, icon);
        }
    }
}

function addPergerakan(data)
{
    for(var i in data)
    {
        if(data[i]['id_simbol_pergerakan'] != undefined && data[i].point != null)
        {
            var splitPoint = data[i]['point'].split(', ')
            var point = new OpenLayers.Geometry.Point(splitPoint[0], splitPoint[1]);
            var feature = new OpenLayers.Feature.Vector(point);

            var splitSize = data[i]['size'].split(' : ');

            var size = new OpenLayers.Size(splitSize[0],splitSize[1]);

            feature.style ={
                externalGraphic:baseUrl + '/' + data[i]['filepath'],
                graphicXOffset:-size.w/2,
                graphicYOffset:-size.h,
                graphicWidth:size.w,
                graphicHeight:size.h,
                pointRadius:0,
                rotation:data[i]['rotation']
            }//default size icon

            pergerakan.addFeatures(feature);
        }
    }
}

function addPolygons(data, number)
{
    var format = new OpenLayers.Format.WKT();
    for(var i in data)
    {
        switch(number)
        {
            case 1:
                //udara
                intel_udara_poly.addFeatures([format.read(data[i]['geom'])]);
                break;
            case 2:
                //laut
                intel_laut_poly.addFeatures([format.read(data[i]['geom'])]);
                break;
            case 3:
                //darat
                intel_darat_poly.addFeatures([format.read(data[i]['geom'])]);
                break;
        }
    }
}

function queryLayers()
{
    map.events.register('click', map, function (e) {
        var lonlat = map.getLonLatFromViewPortPx(e.xy);
        latitude = lonlat.lat; //set latitude value
        longitude = lonlat.lon; //set longitude value

        var params = {
            REQUEST: "GetFeatureInfo",
            EXCEPTIONS: "application/vnd.ogc.se_xml",
            BBOX: map.getExtent().toBBOX(),
            SERVICE: "WMS",
            VERSION: "1.1.1",
            X: Math.round (e.xy.x),
            Y: Math.round (e.xy.y),
            INFO_FORMAT: 'text/html',
            QUERY_LAYERS: map.layers[0].params.LAYERS,
            FEATURE_COUNT: 50,
            Layers: 'bathymetry,provinsi',
            WIDTH: map.size.w,
            HEIGHT: map.size.h,
            format: format,
            styles: map.layers[0].params.STYLES,
            srs: map.layers[0].params.SRS
        };

        OpenLayers.loadURL(geoUrl, params, this, getResponse, getResponse);
        OpenLayers.Event.stop(e);
    });
}

function getResponse(response)
{
    $('#tampung-data').html(response.responseText);
}