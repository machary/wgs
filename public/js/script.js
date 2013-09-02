"use strict";
/**
 * Fungsi-Fungsi JavaScript yang sering dipakai
 * @author Kanwil
 */

/**
 * Class Filterer
 * membungkus sebuah jQuery object yang sudah di-datatables-kan
 * menambahkan drop down kolom untuk pilihan filter secara otomatis
 * @author Kanwil
 */
function Filterer($table) {
	var $wrapper = $('#'+$table.attr('id')+'_wrapper');
	var $select = $('<select><option value="">Semua</option></select>');
	var oriSearchable = [];
	var aoColumns = $table.dataTableSettings[0].aoColumns;
	for (var i=0, inum=aoColumns.length; i<inum; i++) {
		oriSearchable[i] = aoColumns[i].bSearchable;
	}
	// ambil daftar kolom yang bisa disearch
	$wrapper.find('table.display').find('th').each(function(i, el) {
		if (oriSearchable[i]) {
			var $el = $(el);
			if ($el.text().length) {
				$select.append('<option value="'+i+'">'+ $el.text() +'</option>');
			}
		}
	});
	// event ketika diubah
	var $filter = $('#'+$table.attr('id')+'_filter');
	$filter.find('input').before($select);
	$filter.css('width', '350px');
	
	$select.bind('change', function(e) {
		var selected = parseInt($select.val(), 10);
		if (isNaN(selected)) {
			// artinya semua jadi kondisi semula
			for (var i=0, inum=aoColumns.length; i<inum; i++) {
				aoColumns[i].bSearchable = oriSearchable[i];
			}
		} else {
			// artinya semua jadi unsearchable kecuali satu
			for (var i=0, inum=aoColumns.length; i<inum; i++) {
				aoColumns[i].bSearchable = false;
			}
			aoColumns[selected].bSearchable = true;
		}
		$table.fnFilter($filter.find('input').val());
	});
	
	this.$wrapper = $wrapper;
	this.$table = $table;
	this.$select = $select;
	this.$filter = $filter;
}

/**
 * Mengembalikan OpenLayers.Map dengan konfigurasi standar yang banyak dipakai di sini
 * @uses jQuery.js, OpenLayers.js, peta-dasar.js
 * @param {String} mapId string dom element ID tempat peta ditampilkan
 * @param {Object} inputOptions options untuk peta standard, dapat berisi key:
	- useDefaultNavigationControl {Boolean} apakah menggunakan navigation control biasa
	- scaleElement {HTMLElement} tempat menampung skala peta
	- locationElement {HTMLElement} tempat menampung koordinat mouse
	- showNavigator {Boolean} tampilkan overview map atau tidak
	- coordinateJquery {JQuery} Object tempat menampung koordinat click
	- layerSwitcherTree {Boolean} tampilkan layer switcher dalam bentuk tree
 * @author Kanwil
 */
var map;
function standardMap(mapId, inputOptions) {
	// default options
	var ops = {
		useDefaultNavigationControl: true,
		scaleElement: false,
		locationElement: false,
		showNavigator: false,
		coordinateJquery: false,
		layerSwitcherTree: true
	};
	if (inputOptions) for (var k in inputOptions) {
		ops[k] = inputOptions[k];
	}
	
	//var map;
	/* == Initializing Map */
	var options = PETA_DASAR.getOptions();
	map = new OpenLayers.Map(mapId, options);
	/* == End of Initializing Map */

	/* == Setup map layers */
	var layers = PETA_DASAR.getLayers();
	map.addLayers(layers);

	var initialZoomLevel = 5;
	map.setCenter(new OpenLayers.LonLat(118.0001, -2.4), initialZoomLevel);
	/* == End of Setup map layers */
	
	/* == Setting Default Controls */
	var zp = new OpenLayers.Control.ZoomPanel();
	map.addControl(zp);
	zp.moveTo(new OpenLayers.Pixel(10, 10));
	map.addControl(new OpenLayers.Control.LayerSwitcher());
	map.addControl(new OpenLayers.Control.Scale(ops.scaleElement || null));
	map.addControl(new OpenLayers.Control.ScaleLine());
	map.addControl(new OpenLayers.Control.MousePosition({
		element: ops.locationElement || null,
		formatOutput: function (lonLat) {
			var lat = lonLat.lat;
			var lon = lonLat.lon;
			var ns = OpenLayers.Util.getFormattedLonLat(lat);
			var ew = OpenLayers.Util.getFormattedLonLat(lon,'lon');
			return ns + ', ' + ew + ' (' + (Math.round(lat * 10000) / 10000) + ', ' + (Math.round(lon * 10000) / 10000) + ')';
		}
	}));
	/* == End of Setting Default Controls */
	
	/* == Fitur Zoom Area + Drag Pan */
	if (ops.useDefaultNavigationControl) {
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
		var ctc = new CustomNavToolbar();
		map.addControl(ctc);
		ctc.moveTo(new OpenLayers.Pixel(5, 70));
	}
	/* == End of Fitur Zoom Area + Drag Pan */
	
	/* == Fitur Navigator Map */
	if (ops.showNavigator) {
		var ov = new OpenLayers.Control.OverviewMap({maximized: true});
		map.addControl(ov);
		ov.maxRatio = ov.ovmap.getResolution()/map.getResolutionForZoom(map.numZoomLevels);
		ov.minRatio = ov.ovmap.getResolution()/map.getResolutionForZoom(0);
	}
	/* == End of Fitur Navigator Map */
	
	/* == Fitur Klik Dapatkan Koordinat */
	if (ops.coordinateJquery) {
		var $coord = ops.coordinateJquery;
		map.events.register('click', map, function(e) {
			var lonlat = map.getLonLatFromViewPortPx(e.xy);
			var text = lonlat.lon + ', ' + lonlat.lat;
			$coord.find('input').val(text).select();
		});
	}
	/* == End of Fitur Klik Dapatkan Koordinat */
	
	/* == Fitur Layer Switcher Tree */
	// perlu style view/switcher-tree.css
	if (ops.layerSwitcherTree) {
		PETA_DASAR.makeLayerSwitcherTree(map);
	}
	/* == End of Fitur Layer Switcher Tree */
	
	return map;
}

/**
 * Function: addMarker
 * Add a new marker to the markers layer given the following lonlat, 
 *     popupClass, and popup contents HTML. Also allow specifying 
 *     whether or not to give the popup a close box.
 * Source: http://openlayers.org/dev/examples/popupMatrix.html
 * Modified by Kanwil
 *     add parameter: markerLayer and icon
 * 
 * Parameters:
 * markerLayer - {<OpenLayers.Layer.Marker>} Which layer to contain the marker
 * ll - {<OpenLayers.LonLat>} Where to place the marker
 * popupClass - {<OpenLayers.Class>} Which class of popup to bring up when the marker is clicked
 * popupContentHTML - {String} What to put in the popup
 * closeBox - {Boolean} Should popup have a close box?
 * overflow - {Boolean} Let the popup overflow scrollbars?
 * icon - {<OpenLayers.Icon>} (Optional) Icon for the marker
 * toggle - {Boolean} If true toggle popup active and if false only one popup activated
 * @author Kanwil
 */
function addMarker(markerLayer, ll, popupClass, popupContentHTML, closeBox, overflow, icon, toggle) {

	var feature = new OpenLayers.Feature(markerLayer, ll); 
	feature.closeBox = closeBox;
	feature.popupClass = popupClass;
	feature.data.popupContentHTML = popupContentHTML;
	feature.data.overflow = (overflow) ? "auto" : "hidden";
	if (icon) feature.data.icon = icon;
	
	var marker = feature.createMarker();

	var markerClick = function (evt) {
        //edited by tajhul.faijin@sangkuriang.co.id
        if( toggle === true ){
            if (this.popup == null) {
                this.popup = this.createPopup(this.closeBox);
                markerLayer.map.addPopup(this.popup);
                this.popup.show();
            } else {
                this.popup.toggle();
            }
        } else {
            //cleare all popup on the map
            while( map.popups.length ) {
                map.removePopup(map.popups[0]);
            }
            this.popup = this.createPopup(this.closeBox);
            markerLayer.map.addPopup(this.popup);
            this.popup.show();
        }
		OpenLayers.Event.stop(evt);
	};
	marker.events.register("mousedown", feature, markerClick);

	markerLayer.addMarker(marker);
}

/**
 * Hitung derajat kemiringan garis dari titik 1 (lon1, lat1) ke titik 2 (lon2, lat2)
 * Reference: http://mathforum.org/library/drmath/view/55417.html
 * @param Number lon1,lat1 koordinat EPSG:4326 titik asal
 * @param Number lon2,lat2 koordinat EPSG:4326 titik tujuan
 * @return Number derajat kemiringan [-179.9 s/d 180] (utara = 0) clockwise positif
 * @author Kanwil
 */
function countBearing(lon1, lat1, lon2, lat2) {
	// radian to degree
	var toDeg = function (rad) {
		return rad * 180 / Math.PI;
	};
	// degree to radian
	var toRad = function (deg) {
		return deg * Math.PI / 180;
	};
	// ubah ke radian karena Math pakainya radian
	lon1 = toRad(lon1);
	lat1 = toRad(lat1);
	lon2 = toRad(lon2);
	lat2 = toRad(lat2);
	
	var y = Math.sin(lon2-lon1) * Math.cos(lat2);
	var x = Math.cos(lat1)*Math.sin(lat2) - Math.sin(lat1)*Math.cos(lat2)*Math.cos(lon2-lon1);
	if (y > 0) {
	// sisi "kanan" (0.1-179.9)
		if (x > 0) return toDeg(Math.atan(y/x));
		if (x < 0) return 180 - toDeg(Math.atan(-y/x));
		return 90; // x = 0
	} else if (y < 0) {
	// sisi "kiri" (180.1-359.9)
		if (x > 0) return -toDeg(Math.atan(-y/x));
		if (x < 0) return toDeg(Math.atan(y/x)) - 180;
		return 270; // x = 0
	} else { // y = 0
		if (x > 0) return 0;
		if (x < 0) return 180;
		return NaN; // [the 2 points are the same]
	}
}

/**
 * Return the duration to travel between point (lon1,lat1) to (lon2,lat2) with constant speed
 * @param Number lon1 longitude origin
 * @param Number lat1 latitude origin
 * @param Number lon2 longitude destination
 * @param Number lat2 latitude destination
 * @param Number speed the constant speed to travel (in unit)
 * @param String unit unit of the speed ('kmph' or 'knot')
 * @return Number the duration (in hours)
 */
function countDuration(lon1, lat1, lon2, lat2, speed, unit) {
	var origin,
		destination,
		line,
		distance,
		kmph,
		duration;
		
	unit = unit || 'knot'; // default unit
	origin = new OpenLayers.Geometry.Point(lon1, lat1);
	destination = new OpenLayers.Geometry.Point(lon2, lat2);
	line = new OpenLayers.Geometry.LineString([origin, destination]);
	// distance in meters
	distance = line.getGeodesicLength(new OpenLayers.Projection("EPSG:4326")); // HARDCODED! map projection
	// convert speed
	// 1 knot = 1.85200 kilometers / hour
	if (unit === 'knot') {
		kmph = 1.852 * speed;
	} else if (unit === 'kmph') {
		kmph = speed;
	} else {
		// what unit?
		kmph = speed; // assume kmph
	}
	// count duration
	// s = v.t ; t = s/v
	var duration = distance / (1000 * kmph);
	return duration;
}

function countDurationWithDelay(lon1, lat1, lon2, lat2, speed, unit, delay) {
	var origin,
		destination,
		line,
		distance,
		kmph,
		duration;

	unit = unit || 'knot'; // default unit
	origin = new OpenLayers.Geometry.Point(lon1, lat1);
	destination = new OpenLayers.Geometry.Point(lon2, lat2);
	line = new OpenLayers.Geometry.LineString([origin, destination]);
	// distance in meters
	distance = line.getGeodesicLength(new OpenLayers.Projection("EPSG:4326")); // HARDCODED! map projection
	// convert speed
	// 1 knot = 1.85200 kilometers / hour
	if (unit === 'knot') {
		kmph = 1.852 * speed;
	} else if (unit === 'kmph') {
		kmph = speed;
	} else {
		// what unit?
		kmph = speed; // assume kmph
	}
	// count duration
	// s = v.t ; t = s/v
	var duration = (distance / (1000 * kmph) + parseInt(delay));
	return duration;
}

function minMap(map)
{
	$('.controlmap').show('slow');
	$('.contentmap').removeClass('grid_16');
	map.updateSize();
}

function toggleMap(map)
{
	$('.controlmap').toggle();
	$('.contentmap').toggleClass('grid_16');
	map.updateSize();
}

function minmaxMap(map){
	$('.controlmap').hide();

	$('.titlemap').click(function(){
		toggleMap(map);
	})
}

/**
 * <Fn in_array JS>
 * <http://stackoverflow.com/questions/784012/javascript-equivalent-of-phps-in-array>
 * @param needle
 * @param haystack
 */
function in_array(needle, haystack) {
    for(var i in haystack) {
        if(haystack[i] == needle) return true;
    }
    return false;
}


function createMarkerGeodesicPolygon( lon, lat, radius, projection)
{
    var latlon = new OpenLayers.LonLat(lon, lat);

    var angle;
    var new_lonlat, geom_point;
    var points = [];

    for (var i = 0; i < 40; i++) {
        angle = (i * 360 / 40) + 45;
        new_lonlat = OpenLayers.Util.destinationVincenty(latlon, angle, radius);
        geom_point = new OpenLayers.Geometry.Point(new_lonlat.lon, new_lonlat.lat);
        points.push(geom_point);
    }
    var ring = new OpenLayers.Geometry.LinearRing(points);
    return new OpenLayers.Geometry.Polygon([ring]);

}