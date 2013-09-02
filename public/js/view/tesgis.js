var map, ov, panel, nav, movemap, opx;
var tiled, kodam, lantamal_area, lanal_area, lanudal, pelabuhan, bandara, rs, planes;
var latitude, longitude;
var lon = 111.18476;
var lat = -4.79675;
var geoUrl = "http://10.1.1.114:8080/geoserver/wms";
var format = 'image/png';
var wgs84 = new OpenLayers.Projection("EPSG:4326");
$(function(){
    var bounds = new OpenLayers.Bounds(
        92.637, -20.372,
        140.724, 3.684
    );
    var options = {
        controls: [],
        maxExtent: bounds,
        maxResolution: 0.16440234375,
        projection: wgs84,
        units: 'degrees'
    };

    var planeStyleMap = new OpenLayers.StyleMap({
        externalGraphic: jsUrl + 'js/img/kapal.png',
        graphicWidth: 30,
        graphicHeight: 21,
        fillOpacity: 0
        //rotation: "${angle}"
    });


    map = new OpenLayers.Map('map', options);
    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.ZoomPanel());
    map.addControl(new OpenLayers.Control.LayerSwitcher());

    OpenLayers.ProxyHost= proxyUrl;

    // setup single tiled layer
    tiled = new OpenLayers.Layer.WMS(
        "Provinsi", geoUrl,
        {
            layers: 'BaseSeskoal',
            STYLES: '',
            format: format
        },
        {isBaseLayer: true, displayInLayerSwitcher:false}
    );

    kodam = new OpenLayers.Layer.WMS(
        "KODAM", geoUrl,
        {
            layers: 'seskoal:yonif',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false}
    );

    lantamal_area = new OpenLayers.Layer.WMS(
        "Lantamal Area", geoUrl,
        {
            layers: 'seskoal:lantapol,seskoal:lantamal',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false}
    );

    lanal_area = new OpenLayers.Layer.WMS(
        "Lanal Area", geoUrl,
        {
            layers: 'seskoal:lanalpol,seskoal:lanal',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false}
    );

    lanudal = new OpenLayers.Layer.WMS(
        "Lanudal", geoUrl,
        {
            layers: 'seskoal:bandara',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false}
    );

    pelabuhan = new OpenLayers.Layer.WMS(
        "Pelabuhan", geoUrl,
        {
            layers: 'seskoal:pelabuhan',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false}
    );

    bandara = new OpenLayers.Layer.WMS(
        "Bandar Udara", geoUrl,
        {
            layers: 'seskoal:bandara',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false}
    );

    rs = new OpenLayers.Layer.WMS(
        "Rumah Sakit", geoUrl,
        {
            layers: 'seskoal:rumahsakit',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false}
    );

    kodam.setVisibility(false);
    lantamal_area.setVisibility(false);
    lanal_area.setVisibility(false);
    lanudal.setVisibility(false);
    pelabuhan.setVisibility(false);
    bandara.setVisibility(false);
    rs.setVisibility(false);

    map.addLayers([tiled, kodam, lantamal_area, lanal_area, lanudal, pelabuhan, bandara, rs]);

    //setMarkers(111.18476, -4.79675);

    map.addControl(new OpenLayers.Control.Scale($('#scale').get(0)));
    map.addControl(new OpenLayers.Control.MousePosition({element: $('location').get(0)}));
    //map.zoomToExtent(bounds);

    map.setCenter(new OpenLayers.LonLat(119, -3), 2);

    //vaigasi drag zoom
    panel = new OpenLayers.Control.NavToolbar();
    map.addControl(panel);

    //navigator
    ov = new OpenLayers.Control.OverviewMap({maximized:true});
    map.addControl(ov);
    ov.ovmap.getResolution()/map.getResolutionForZoom(map.numZoomLevels);
    ov.minRatio = ov.ovmap.getResolution()/map.getResolutionForZoom(0);

    map.zoomToMaxExtent(bounds);

    queryLayers();
});

function queryLayers()
{
    map.events.register('click', map, function (e) {

        if(tiled.visibility != false){
            var indexLayer = 0;
            var ws_layers = 'BaseSeskoal';
        }else{
            if(kodam.visibility != false){
                var indexLayer = 1;
                var ws_layers = 'seskoal:yonif';
            }else if(lantamal_area.visibility != false){
                var indexLayer = 2;
                var ws_layers = 'seskoal:lantamal';
            }else if(lanal_area.visibility != false){
                var indexLayer = 3;
                var ws_layers = 'seskoal:lanal';
            }
        }

        if(indexLayer != undefined){


            //set zoom level
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
                QUERY_LAYERS: map.layers[indexLayer].params.LAYERS,
                FEATURE_COUNT: 50,
                Layers: ws_layers,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                format: format,
                styles: map.layers[indexLayer].params.STYLES,
                srs: map.layers[indexLayer].params.SRS
            };

            OpenLayers.loadURL(serverUrl, params, this, getResponse, getResponse);
            OpenLayers.Event.stop(e);

//            opx = map.getLayerPxFromViewPortPx(e.xy) ;
//            marker.map = map ;
//            marker.moveTo(opx);

        }
    });
}

//untuk menampilkan data provinsi pada saat di klik
function getResponse(response)
{
    //console.log(response.responseText);
    $('#tampung-data').html(response.responseText);
}

//membuat marker
var markers, marker;
function setMarkers(lon, lat)
{
    markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);

    var size = new OpenLayers.Size(30,21);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon(jsUrl + 'js/img/kapal.png',size,offset);
    markers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(lon,lat),icon));

    var halfIcon = icon.clone();
    markers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(0,45),halfIcon));

    marker = new OpenLayers.Marker(new OpenLayers.LonLat(90,10),icon.clone());
    //marker.setOpacity(0.2);
    marker.events.register('mousedown', marker, function(evt) { alert(this.icon.url); OpenLayers.Event.stop(evt); });
    markers.addMarker(marker);

}