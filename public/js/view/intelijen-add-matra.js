var polygonLayer, markers, size, iconMatra, offset, icon;
$(function(){
    var wgs84 = new OpenLayers.Projection("EPSG:4326");
    var format = 'image/png';
    var map, drawControls;
    $('#map2').css('height', '400px');
    var options = {
        controls: [],
        //maxExtent: bounds,
        maxResolution: 0.0780,
        projection: wgs84,
        units: 'degrees',
        restrictedExtent: new OpenLayers.Bounds(95.115729755272, -12.012413207701, 140.70672975527, 7.4095867922987)
    };

    map = standardMap('map2');

    polygonLayer = new OpenLayers.Layer.Vector("Polygon Layer");
    map.addLayer(polygonLayer);

	//add new marker
	markers = new OpenLayers.Layer.Markers( "Markers");
	map.addLayer(markers);

	iconMatra = baseUrl + '/images/icons/red_circle.png';

	size = new OpenLayers.Size(21,25);
	offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
	icon = new OpenLayers.Icon(iconMatra, size, offset);

    //map.addControl(new OpenLayers.Control.LayerSwitcher());
    map.addControl(new OpenLayers.Control.MousePosition());

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

    var panelControls = [
        new OpenLayers.Control.Navigation(),
        new OpenLayers.Control.DrawFeature(polygonLayer,
            OpenLayers.Handler.Polygon,
            {'displayClass': 'olControlDrawFeaturePolygon'})
    ];

    var toolbar = new OpenLayers.Control.Panel({
        displayClass: 'olControlEditingToolbar',
        defaultControl: panelControls[0]
    });

    toolbar.addControls(panelControls);
    map.addControl(toolbar);

	var geomArr = [];
	var count = 0;
    polygonLayer.events.register('featureadded', this, function(e){
        var tamp;
        tamp = polygonLayer.features[count].geometry;
        geomArr.push(tamp);
        document.getElementById('geom').value = geomArr.join('|');
        count++;
    });

    var lonlatArr = [];
    map.events.register('click', map, function(e) {
        var lonlat = map.getLonLatFromViewPortPx(e.xy);
        //lonlatArr.push(lonlat.lon + ',' + lonlat.lat);
        //document.getElementById('point').value = lonlatArr.join('|');
	    document.getElementById('longitude').value = lonlat.lon;
	    document.getElementById('latitude').value = lonlat.lat;

	    markers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(lonlat.lon,lonlat.lat),icon));
    });
});