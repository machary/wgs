var wgs84 = new OpenLayers.Projection("EPSG:4326");
var geoUrl = PETA_DASAR.geoUrl;
var format = 'image/png';
var tiled,zee, alki, teritorial; // base Layer
var kodam, radar,willan,lanal,wilkod // layer kekuatan sendiri
var bandara, pelabuhan; //layer asset sipil
var map, panel, ov, longitude, latitude, info, popup, px;
var udara, laut, darat, cog, cc, cv, cr;

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

    map = new OpenLayers.Map('map', options);
    map.addControl(new OpenLayers.Control.ZoomPanel());
    map.addControl(new OpenLayers.Control.LayerSwitcher());
    map.addControl(new OpenLayers.Control.MousePosition({element: $('#location').get(0),formatOutput: formatLonlats}));

    // -------------- Base Layer ---------------------------

    tiled = new OpenLayers.Layer.WMS(
        "Provinsi", geoUrl,
        {
            layers: 'Bathymetri,wil_provinsi',
            STYLES: '',
            format: format
        },
        {isBaseLayer: true, displayInLayerSwitcher:false}
    );

    zee = new OpenLayers.Layer.WMS(
        "ZEE", geoUrl,
        {
            layers: 'zee',
            STYLES: '',
            transparent: "true",
            format: format
        },
        {isBaseLayer: false, displayInLayerSwitcher:true,visibility: false}
    );

    alki = new OpenLayers.Layer.WMS(
        "Alki", geoUrl,
        {
            layers: 'alki',
            STYLES: '',
            transparent: "true",
            format: format
        },
        {isBaseLayer: false, displayInLayerSwitcher:true,visibility: false}
    );
    teritorial = new OpenLayers.Layer.WMS(
        "Teritorial", geoUrl,
        {
            layers: 'laut_teritori',
            STYLES: '',
            transparent: "true",
            format: format
        },
        {isBaseLayer: false, displayInLayerSwitcher:true}
    );

    // ------------------- End of Base Layer ---------------------------


    // ------------------- Layer Kekuatan Sendiri ----------------------

    kodam = new OpenLayers.Layer.WMS(
        "KODAM", geoUrl,
        {
            layers: 'seskoal:yonif',
            STYLES: '',
            format: format,
            transparent:true
        },
        {isBaseLayer:false,visibility: false}
    );

    radar = new OpenLayers.Layer.WMS( "Radar",
        geoUrl, {
            transparent : true,
            layers: 'radar',
            format: 'image/png'},{isBaseLayer: false, visibility: false}
    );

    willan = new OpenLayers.Layer.WMS( "Wilayah LANTAMAL",
        geoUrl, {
            transparent : true,
            layers: 'wil_lantamal',
            format: 'image/png'},{isBaseLayer: false, visibility: false}
    );

    lanal = new OpenLayers.Layer.WMS( "Pangkalan AL",
        geoUrl, {
            transparent : true,
            layers: 'pst_lanal',
            format: 'image/png'},{isBaseLayer: false, visibility: false}
    );

    wilkod = new OpenLayers.Layer.WMS( "Wilayah KODAM",
        geoUrl, {
            transparent : true,
            layers: 'wil_kodam',
            format: 'image/png'},{isBaseLayer: false, visibility: false}
    );

    // ------------------- End Of Layer Kekuatan Sendiri ----------------------

    // ------------------- Aset sipil ----------------------

    bandara = new OpenLayers.Layer.WMS( "Bandara",
        geoUrl, {
            transparent : true,
            layers: 'bandara',
            format: 'image/png'},{isBaseLayer: false, visibility: false}
    );
    pelabuhan = new OpenLayers.Layer.WMS( "Pelabuhan",
        geoUrl, {
            transparent : true,
            layers: 'pelabuhan',
            format: 'image/png'},{isBaseLayer: false, visibility: false}
    );

    // ------------------- End Of Aset sipil ----------------------

    tiled.transitionEffect = "resize";
    // map.addLayers([tiled,zee, alki, teritorial]);
    // map.addLayers([kodam, radar,willan,lanal,wilkod]);
    // map.addLayers([bandara,pelabuhan]);
	// pakai layer standard
	map.addLayers(PETA_DASAR.getLayers());

    map.addControl(new OpenLayers.Control.Scale($('#scale').get(0)));

    /* == Fitur (Control)Zoom Area + Drag Pan */
    // override NavToolbar untuk mendisable scroll
    var CustomNavToolbar = OpenLayers.Class(OpenLayers.Control.NavToolbar, {
        initialize: function(options) {
            OpenLayers.Control.Panel.prototype.initialize.apply(this, [options]);
            this.addControls([
                new OpenLayers.Control.Navigation({'zoomWheelEnabled': false}), // disable scroll di sini
                new OpenLayers.Control.ZoomBox()
            ]);
        }
    });

    /* == Fitur Klik Dapatkan Koordinat */
    map.events.register('click', map, function(e) {
        var lonlat = map.getLonLatFromViewPortPx(e.xy);
        var text = lonlat.lon + ', ' + lonlat.lat;
        $('#coordclick').find('input').val(text).select();
    });
    /* == End of Fitur Klik Dapatkan Koordinat */

    var ctc = new CustomNavToolbar();
    map.addControl(ctc);
    ctc.moveTo(new OpenLayers.Pixel(5, 70));
    /* == End of Fitur Zoom Area + Drag Pan */


    map.setCenter(new OpenLayers.LonLat(118.0001, -2.4), 1);

    function formatLonlats(lonLat) {
        var lat = lonLat.lat;
        var long = lonLat.lon;
        var ns = OpenLayers.Util.getFormattedLonLat(lat);
        var ew = OpenLayers.Util.getFormattedLonLat(long,'lon');
        return ns + ', ' + ew + ' (' + (Math.round(lat * 10000) / 10000) + ', ' + (Math.round(long * 10000) / 10000) + ')';
    }

    //navigator
    ov = new OpenLayers.Control.OverviewMap({maximized:false});
    map.addControl(ov);
    ov.ovmap.getResolution()/map.getResolutionForZoom(map.numZoomLevels);
    ov.minRatio = ov.ovmap.getResolution()/map.getResolutionForZoom(0);

    queryLayers();
	
	// edited by: Kanwil
	PETA_DASAR.makeLayerSwitcherTree(map);
});

function queryLayers()
{
    map.events.register('click', map, function (e) {

        if(tiled.visibility != false){
            var indexLayer = 0;
            var ws_layers = 'BaseSeskoal';
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
                QUERY_LAYERS: map.layers[0].params.LAYERS,
                FEATURE_COUNT: 50,
                Layers: 'BaseSeskoal',
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                format: format,
                styles: map.layers[0].params.STYLES,
                srs: map.layers[0].params.SRS
            };

            OpenLayers.loadURL(serverUrl, params, this, getResponse, getResponse);
            OpenLayers.Event.stop(e);
        }
    });
}

function getResponse(response)
{
    //console.log(response.responseText);
    $('#tampung-data').html(response.responseText);
}