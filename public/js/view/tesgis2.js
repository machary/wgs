var map=null;
var selectControl;
function OLinit() {
    map = new OpenLayers.Map('map');

    map.addControl(new OpenLayers.Control.LayerSwitcher());
    map.addControl(new OpenLayers.Control.MousePosition());
    map.addControl(new OpenLayers.Control.PanZoomBar());

    var planeStyleMap = new OpenLayers.StyleMap({
        externalGraphic: jsUrl + 'js/img/kapal.png',
        graphicWidth: 25,
        graphicHeight: 25,
        fillOpacity: 0.85,
        rotation: "${angle}"
    });
    var ol_wms = new OpenLayers.Layer.WMS( "Metacarta Basic Layer",
        "http://10.1.1.114:8080/geoserver/wms",
        {layers: 'BaseSeskoal'},{singleTile:true} );

    planes=new OpenLayers.Layer.Vector("Planes",{styleMap:planeStyleMap});

    selectControl = new OpenLayers.Control.SelectFeature(planes,
        {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect, hover: false});

    map.addLayers([ol_wms,planes]);
    map.addControl(selectControl);
    selectControl.activate();
    map.zoomToExtent(new OpenLayers.Bounds(-125, 17.84, -59, 52.55));
    generateMarkers(planes);
}
// Generate a random point inside the map area, and add a marker to the list.
function generateMarkers(planes){
    //add as many new features as we need to keep object count same as specified
    var maxFeatureSelect = document.getElementById('features');
    var maxFeatureCount = parseInt(maxFeatureSelect[maxFeatureSelect.selectedIndex].value);

    var added = false;
    var intialCount = planes.features.length;
    var neededFeatureCount = maxFeatureCount - intialCount;
    //only add 100 or less at a time
    neededFeatureCount = (neededFeatureCount > 100)?intialCount + 100:intialCount+neededFeatureCount;
    //create new vector markers
    while (planes.features.length < neededFeatureCount) {
        var extent = planes.map.getExtent();
        var yspan = extent.getHeight();
        var xspan = extent.getWidth();
        var x = extent.left + xspan * Math.random();
        var y = extent.top - yspan * Math.random();
        var planeangle = Math.floor(Math.random() * 360);
        var feature = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Point(x, y), {
            angle: planeangle,
            poppedup: false
        });
        planes.addFeatures([feature]);
        added = true;
    }
    if (added) {
        document.getElementById('update').innerHTML = 'Feature was added<br>Current feature count is ' + planes.features.length;
    }
}
function moveFeatures(vectorLayer){
    generateMarkers(vectorLayer);
    var features = vectorLayer.features, selectedPlane = null;
    for (var i=features.length-1;i>=0;i--) {
        var feature = features[i];
        var planeangle = feature.attributes.angle;
        var poppedup = feature.attributes.poppedup;

        var x = feature.geometry.x + Math.cos(planeangle * Math.PI / 180) / 5;
        var y = feature.geometry.y + Math.sin(planeangle * Math.PI / 180) / 5;

        if (poppedup == true) {
            selectControl.unselect(feature);
        }

        if (planes.map.getExtent().contains(x, y)) {
            var newPoint = new OpenLayers.LonLat(x, y);
            feature.attributes.angle += Math.floor(Math.random()*20);
            if(feature.attributes.angle>360){feature.attributes.angle -= 360;}
            feature.move(newPoint);
            if (poppedup == true) {
                selectControl.select(feature);
            }
        }
        else {
            planes.destroyFeatures([feature]);
            feature.destroy();
            feature = null;
            document.getElementById('update').innerHTML = 'Feature was removed (off the current map).';
            document.getElementById('update').innerHTML += '<br>Current feature count is ' + planes.features.length;
        }
    }
    setTimeout(function(){moveFeatures(planes, planes.features || [])}, document.getElementById('refreshinterval').value);
}

function onFeatureSelect(feature) {
    selectedFeature = feature;
    var planeAngle = feature.attributes.angle;
    popup = new OpenLayers.Popup.AnchoredBubble(null,
        new OpenLayers.LonLat(feature.geometry.x, feature.geometry.y),
        null,
        "<div style='font-size:.8em'>Plane is flying at an angle of " + planeAngle +
            " &deg;<br>Current position is " + Math.round(feature.geometry.x*10000)/10000 + ", " + Math.round(feature.geometry.y*10000)/10000 + "</div>",
        null,true,  function () { onPopupClose(feature) } );
    feature.popup = popup;
    feature.attributes.poppedup=true;
    map.addPopup(popup);
}
function onPopupClose(feature) {
    selectControl.unselect(feature);
}
function onFeatureUnselect(feature) {
    map.removePopup(feature.popup);
    feature.popup.destroy();
    feature.attributes.poppedup = false;
    feature.popup = null;
}