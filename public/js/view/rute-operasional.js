"use strict";
/** 
 * Class Formasi
 * untuk simulasi rute operasional
 *
 * contains several ship {x,y,simbol_taktis}
 * @uses OpenLayers.js
 * @author Kanwil
 *
 * @global baseUrl
 * @param Array formasi
 * @param OpenLayers.Layer.Vector layer
 */
function Formasi(formasi, layer, rudals) {
	this.formation = formasi;
	this.layer = layer;
	this.rudals = rudals;
	this.position = new OpenLayers.LonLat(0, 0); // current center position
	this.positionRudal = []; // current center position
	this.point = new OpenLayers.Geometry.Point(0, 0); // current center position in Point
	this.pointRudal = []; // current center position in Point
	this.bearing = 0; // current bearing of all ships
	this.bearingRudal = []; // current bearing of all ships
	this.defaultBearing = 0; // bearing bawaan gambar simbol
    this.rudalVectors = [];
    this.rudalRadius = [];
	
	// generate feature vector2 untuk formasi
	this.vectors = [];
    this.rudalVectors = [];
    this.rudalRadius = [];
	for (var i=0; i<this.formation.length; i++) {
		var newCoord = this.pixelToLonLat(this.formation[i].x, this.formation[i].y);
        var prefixUrl = baseUrl+'/';
		var vector = new OpenLayers.Feature.Vector(
			new OpenLayers.Geometry.Point(newCoord.lon, newCoord.lat), // position each ship according to formation
			null,
			{externalGraphic: prefixUrl+this.formation[i].simbol_taktis, pointRadius: 15} // HARDCODED! ukuran gambar kapal pada peta
		);
		this.vectors.push(vector);

        if(typeof(this.rudals) != 'undefined')
        {
            for (var j=0; j<this.rudals.length; j++) {
                if(this.rudals[j]['urutan_formasi'] == this.formation[i].urutan) {
                    this.bearingRudal[j] = 0;
                    var tempPoints = []; // menampung titik untuk diberikan ke linestring
                    var newRudalCoord = this.pixelRudalToLonLat(this.formation[i].x, this.formation[i].y, j);
                    var prefixUrl = baseUrl+'/';
                    var rudalVector = new OpenLayers.Feature.Vector(
                        new OpenLayers.Geometry.Point(newRudalCoord.lon, newRudalCoord.lat), // position each ship according to formation
                        null,
                        {externalGraphic: prefixUrl+'./upload/simbol/rudal.png', pointRadius: 15, graphicZIndex: 100} // HARDCODED! ukuran gambar kapal pada peta
                    );
                    this.rudalVectors.push(rudalVector);

                    tempPoints.push(new OpenLayers.Geometry.Point( parseFloat(this.rudals[j]['longitude_start']) + newRudalCoord.lon, parseFloat(this.rudals[j]['latitude_start']) + newRudalCoord.lat));
                    tempPoints.push(new OpenLayers.Geometry.Point( this.rudals[j]['longitude_target'], this.rudals[j]['latitude_target']));

                    var circle = OpenLayers.Geometry.Polygon.createRegularPolygon(
                        new OpenLayers.Geometry.Point(parseFloat(this.rudals[j]['longitude_start']) + newRudalCoord.lon, parseFloat(this.rudals[j]['latitude_start']) + newRudalCoord.lat),
                        (parseInt(this.rudals[j]['radius'])*0.009),
                        30
                    );
                    var vectorRadius = new OpenLayers.Feature.Vector(circle, null,
                        {
                            fillColor: '#FFB33F',
                            strokeWidth: 1,
                            strokeColor: '#FFB33F',
                            fillOpacity: 0.2,
                            strokeOpacity: 0.3,
                            graphicZIndex: 10
                        }
                    );

                    var featureRute = new OpenLayers.Feature.Vector(
                        new OpenLayers.Geometry.LineString(tempPoints),
                        null,
                        {
                            strokeWidth: 1.1,
                            strokeDashstyle: 'dot',
                            strokeColor: '#FF0000'
                        });

                    this.rudalRadius.push(vectorRadius);

                    this.layer.addFeatures([featureRute]);
                }
            }
        }
    }

	// other attributes
	this.isMoving = false;
}

Formasi.prototype = {
	/**
	 * Convert pixel position to coordinate
	 * based on current position
	 * @param Number x
	 * @param Number y
	 * @return OpenLayers.LonLat
	 */
	pixelToLonLat: function(x, y) {
		// rumus konversi (sementara):
		// 40 px ~ 1 km ~ 0.1 lon/lat
		var relativeLon = x/40000;
		var relativeLat = -y/40000;

		// posisi memperhitungkan bearing
		var relativePoint = new OpenLayers.Geometry.Point(relativeLon, relativeLat);
		relativePoint.rotate(this.defaultBearing - this.bearing, new OpenLayers.Geometry.Point(0, 0));
		return this.position.add(relativePoint.x, relativePoint.y);
	},
	pixelRudalToLonLat: function( x, y, index) {
		// rumus konversi (sementara):
		// 40 px ~ 1 km ~ 0.1 lon/lat
		var relativeLon = x/40000;
		var relativeLat = -y/40000;

		// posisi memperhitungkan bearing
		var relativePoint = new OpenLayers.Geometry.Point(relativeLon, relativeLat);
		relativePoint.rotate(this.defaultBearing - this.bearingRudal[index], new OpenLayers.Geometry.Point(0, 0));
        this.positionRudal[index] = new OpenLayers.LonLat(0, 0);
		return this.positionRudal[index].add(relativePoint.x, relativePoint.y);
	},

	addToLayer: function() {
		this.layer.addFeatures(this.vectors);
	},

	removeFromLayer: function() {
		this.layer.removeFeatures(this.vectors);
	},

	addRudalToLayer: function() {
		this.layer.addFeatures(this.rudalVectors);
	},

	removeRudalFromLayer: function() {
		this.layer.removeFeatures(this.rudalVectors);
	},

	redraw: function() {
		var i, inum;
		for (i=0, inum=this.vectors.length; i<inum; ++i) {
			this.layer.drawFeature(this.vectors[i]);
		}
	},

	redrawRudal: function(index) {
		var i, inum;
        this.layer.drawFeature(this.rudalRadius[index]);
        this.layer.drawFeature(this.rudalVectors[index]);
	},

	/**
	 * @return OpenLayers.LonLat {lon, lat}
	 */
	currentPosition: function() {
		return this.position;
	},
    currentPositionRudal: function(index) {
		return this.positionRudal[index];
	},

	/**
	 * @return OpenLayers.Geometry.Point
	 */
	currentPoint: function() {
		return this.point;
	},
	currentPointRudal: function(index) {
		return this.pointRudal[index];
	},

	/**
	 * Set position to a certain coordinate
	 * @param Number lon
	 * @param Number lat
	 */
	setPosition: function(lon, lat) {
		var diffLon = (lon - this.position.lon);
		var diffLat = (lat - this.position.lat);
		this.position = new OpenLayers.LonLat(lon, lat);
		this.point = new OpenLayers.Geometry.Point(lon, lat);
		// update posisi semua vector
		for (var i=0, l=this.vectors.length; i<l; i++) {
			this.vectors[i].geometry.x += diffLon;
			this.vectors[i].geometry.y += diffLat;
			this.vectors[i].geometry.calculateBounds(); // refresh bound manual, kalo ga ntar "hilang"
		}
		this.redraw();
	},

	setRudalPosition: function(lon, lat, index) {
		var diffLon = (lon - this.positionRudal[index].lon);
		var diffLat = (lat - this.positionRudal[index].lat);
		this.positionRudal[index] = new OpenLayers.LonLat(lon, lat);
		this.pointRudal[index] = new OpenLayers.Geometry.Point(lon, lat);
		// update posisi semua vector
        this.rudalVectors[index].geometry.x += diffLon;
        this.rudalVectors[index].geometry.y += diffLat;
        this.rudalVectors[index].geometry.calculateBounds(); // refresh bound manual, kalo ga ntar "hilang"
		this.redrawRudal(index);
	},

	hideRudal: function( index) {
        this.rudalRadius[index].style.display = 'none';
        this.rudalVectors[index].style.display = 'none';
        this.redrawRudal(index);
	},

	unhideRudal: function( index) {
        this.rudalRadius[index].style.display = 'true';
        this.rudalVectors[index].style.display = 'true';
        this.redrawRudal(index);
	},

	/**
	 * @return Number
	 */
	currentBearing: function() {
		return this.bearing;
	},
	currentRudalBearing: function(index) {
		return this.bearingRudal[index];
	},

	/**
	 * Reset bearing to the original degree
	 */
	resetBearing: function() {
		var resetDeg = this.defaultBearing - this.bearing;
		var centroid = this.point;
		for (var i=0, l=this.vectors.length; i<l; i++) {
			this.vectors[i].style.rotation = 0; // rotasi style clockwise bernilai positif
			this.vectors[i].geometry.rotate(-resetDeg, centroid); // rotasi geometry clockwise bernilai negatif
		}
	},
	resetRudalBearing: function(index) {
		var resetDeg = this.defaultBearing - this.bearingRudal[index];
		var centroid = this.pointRudal[index];
        this.rudalVectors[index].style.rotation = 0; // rotasi style clockwise bernilai positif
        this.rudalVectors[index].geometry.rotate(-resetDeg, centroid); // rotasi geometry clockwise bernilai negatif
	},

	/**
	 * Set bearing to a certain degree
	 * @param Number deg
	 */
	setBearing: function(deg) {
		this.resetBearing();
		this.bearing = deg;
		var setDeg = deg - this.defaultBearing;
		var centroid = this.point;
		for (var i=0, l=this.vectors.length; i<l; i++) {
			this.vectors[i].style.rotation = setDeg;
			this.vectors[i].geometry.rotate(-setDeg, centroid);
		}
		this.redraw();
	},
	setRudalBearing: function(deg, index) {
		this.resetRudalBearing(index);
		this.bearingRudal[index] = deg;
		var setDeg = deg - this.defaultBearing;
		var centroid = this.pointRudal[index];
        this.rudalVectors[index].style.rotation = setDeg;
        this.rudalVectors[index].geometry.rotate(-setDeg, centroid);
        this.redrawRudal(index);
	},

	/**
	 * Animate the movement of this formation from current position
	 * to a designated coordinate
	 * @param Number lon
	 * @param Number lat
	 * @param Number duration (assumption according to $second2hour)
	 * @param JQuery $second2hour element yang menyimpan informasi asumsi 1 detik = berapa jam real
	 * @param Function whenFinish (Optional) function to call when object has reached destination
	 */
	animateMoveTo: function(lon, lat, duration, $second2hour, whenFinish) {
		var second2hour = parseFloat($second2hour.val()); // ambil jumlah jam dalam 1 detik
		var actualDuration = duration * 1000 / second2hour; // in miliseconds
		// define interval, pointCount, xmove, ymove
		var interval = 100; // HARDCODED! delay between movement, in miliseconds
		var pCount = actualDuration / interval; // amount of movements
		var xmove = Math.abs(this.position.lon - lon)/pCount; // x-axis movement each step
		var ymove = Math.abs(this.position.lat - lat)/pCount; // y-axis movement each step
		// define points
		var current = this.point;
		var destination = new OpenLayers.Geometry.Point(lon, lat);
		// set bearing
		var degree = Formasi.countBearing(this.position.lon, this.position.lat, lon, lat);
		this.setBearing(degree);
		// define one step
		var movePoint = function(dari, ke) {
			// @param OpenLayers.Geometry.Point dari {x,y} the current coordinate of moving element
			// @param OpenLayers.Geometry.Point ke {x,y} the final coordinate
			// x-axis
			if (dari.x < ke.x) {
				if (dari.x + xmove < ke.x) {
					dari.x += xmove;
				} else {
					dari.x = ke.x;
				}
			} else {
				if (dari.x - xmove > ke.x) {
					dari.x -= xmove;
				} else {
					dari.x = ke.x;
				}
			}
			// y-axis
			if (dari.y < ke.y) {
				if (dari.y + ymove < ke.y) {
					dari.y += ymove;
				} else {
					dari.y = ke.y;
				}
			} else {
				if (dari.y - ymove > ke.y) {
					dari.y -= ymove;
				} else {
					dari.y = ke.y;
				}
			}
		};
		var self = this;
		var moving = function() {
			if (current.x != destination.x || current.y != destination.y) {
				movePoint(current, destination);
				self.setPosition(current.x, current.y);
				window.setTimeout(moving, interval);
			} else if (typeof whenFinish == 'function') {
				whenFinish();
			}
		};
		window.setTimeout(moving, interval);
	},
	animateRudalMoveTo: function(lon, lat, duration, $second2hour, whenFinish) {
		var second2hour = parseFloat($second2hour.val()); // ambil jumlah jam dalam 1 detik
		var actualDuration = duration * 1000 / second2hour; // in miliseconds
		// define interval, pointCount, xmove, ymove
		var interval = 100; // HARDCODED! delay between movement, in miliseconds
		var pCount = actualDuration / interval; // amount of movements
		var xmove = Math.abs(this.positionRudal.lon - lon)/pCount; // x-axis movement each step
		var ymove = Math.abs(this.positionRudal.lat - lat)/pCount; // y-axis movement each step
		// define points
		var current = this.pointRudal;
		var destination = new OpenLayers.Geometry.Point(lon, lat);
		// set bearing
		var degree = Formasi.countBearing(this.positionRudal.lon, this.positionRudal.lat, lon, lat);
		this.setRudalBearing(degree);
		// define one step
		var movePoint = function(dari, ke) {
			// @param OpenLayers.Geometry.Point dari {x,y} the current coordinate of moving element
			// @param OpenLayers.Geometry.Point ke {x,y} the final coordinate
			// x-axis
			if (dari.x < ke.x) {
				if (dari.x + xmove < ke.x) {
					dari.x += xmove;
				} else {
					dari.x = ke.x;
				}
			} else {
				if (dari.x - xmove > ke.x) {
					dari.x -= xmove;
				} else {
					dari.x = ke.x;
				}
			}
			// y-axis
			if (dari.y < ke.y) {
				if (dari.y + ymove < ke.y) {
					dari.y += ymove;
				} else {
					dari.y = ke.y;
				}
			} else {
				if (dari.y - ymove > ke.y) {
					dari.y -= ymove;
				} else {
					dari.y = ke.y;
				}
			}
		};
		var self = this;
		var moving = function() {
			if (current.x != destination.x || current.y != destination.y) {
				movePoint(current, destination);
				self.setRudalPosition(current.x, current.y);
				window.setTimeout(moving, interval);
			} else if (typeof whenFinish == 'function') {
				whenFinish();
			}
		}
		window.setTimeout(moving, interval);
	},

	/**
	 * Menggerakkan formasi mengikuti suatu rute
	 * @param Array points array of {nama, longitude, latitude, waktu_tempuh}
	 * @param JQuery $second2hour element yang menyimpan informasi asumsi 1 detik = berapa jam real
	 * @param Function whenFinish (Optional) function to call when all points have been travelled
	 */
	moveAlong: function(points, $second2hour, whenFinish) {
		var cpoints = points.slice(0); // copy the array
		if (this.isMoving) {
			return;
		}
		this.isMoving = true;
		var initialPoint = cpoints.shift();
		this.setPosition(initialPoint.longitude, initialPoint.latitude);
		var self = this;
		var oneMovement = function() {
			if (cpoints.length > 0) {
				var destination = cpoints.shift();
				self.animateMoveTo(destination.longitude, destination.latitude, destination.waktu_tempuh, $second2hour, oneMovement);
			} else {
				self.isMoving = false;
				if (typeof whenFinish == 'function') {
					whenFinish();
				}
			}
		}
		oneMovement();
	},
	moveRudalAlong: function(points, $second2hour, whenFinish) {
		var cpoints = points.slice(0); // copy the array
		if (this.isMoving) {
			return;
		}
		this.isMoving = true;
		var initialPoint = cpoints.shift();
		this.setRudalPosition(initialPoint.longitude, initialPoint.latitude);
		var self = this;
		var oneMovement = function() {
			if (cpoints.length > 0) {
				var destination = cpoints.shift();
				self.animateRudalMoveTo(destination.longitude, destination.latitude, destination.waktu_tempuh, $second2hour, oneMovement);
			} else {
				self.isMoving = false;
				if (typeof whenFinish == 'function') {
					whenFinish();
				}
			}
		}
		oneMovement();
	},

	/**
	 * Apparently every geometry object in OpenLayers has some kind of bounds
	 * which determine whether that object should/not be drawn
	 * So, when moving a geometry object, we must refresh those bounds
	 */
	refreshBounds: function() {
		for (var i=0, l=this.vectors.length; i<l; i++) {
			this.vectors[i].geometry.calculateBounds();
		}
	},

	refreshRudalBounds: function() {
		for (var i=0, l=this.rudalVectors.length; i<l; i++) {
			this.rudalVectors[i].geometry.calculateBounds();
		}
	},

	/**
	 * A slight modification of method animateMoveTo
	 * Using speed instead of duration as input
	 * @param Number lon
	 * @param Number lat
	 * @param Number speed (assumed 1 hour ~ actually 10 seconds)
	 * @param JQuery $second2hour element yang menyimpan informasi asumsi 1 detik = berapa jam real
	 * @param Function whenFinish (Optional) function to call when object has reached destination
	 */
	animateMoveToWithSpeed: function(lon, lat, speed, $second2hour, whenFinish) {
		var duration = Formasi.countDuration(this.position.lon, this.position.lat, lon, lat, speed);
		this.animateMoveTo(lon, lat, duration, $second2hour, whenFinish);
	},
	animateRudalMoveToWithSpeed: function(lon, lat, speed, $second2hour, whenFinish) {
		var duration = Formasi.countDuration(this.positionRudal.lon, this.positionRudal.lat, lon, lat, speed);
		this.animateRudalMoveTo(lon, lat, duration, $second2hour, whenFinish);
	},

	/**
	 * A slight modification of method moveAlong
	 * Using speed instead of duration of points
	 * @param Array points array of {nama, longitude, latitude, kecepatan}
	 * @param JQuery $second2hour element yang menyimpan informasi asumsi 1 detik = berapa jam real
	 * @param Function whenFinish (Optional) function to call when all points have been travelled
	 */
	moveAlongWithSpeed: function(points, $second2hour, whenFinish) {
		var cpoints = points.slice(0); // copy the array
		if (this.isMoving) {
			return;
		}
		this.isMoving = true;
		var initialPoint = cpoints.shift();
		this.setPosition(initialPoint.longitude, initialPoint.latitude);
		var self = this;
		var oneMovement = function() {
			if (cpoints.length > 0) {
				var destination = cpoints.shift();
				self.animateMoveToWithSpeed(destination.longitude, destination.latitude, destination.kecepatan, $second2hour, oneMovement);
			} else {
				self.isMoving = false;
				if (typeof whenFinish == 'function') {
					whenFinish();
				}
			}
		}
		oneMovement();
	},
	moveRudalAlongWithSpeed: function(points, $second2hour, whenFinish) {
		var cpoints = points.slice(0); // copy the array
		if (this.isMoving) {
			return;
		}
		this.isMoving = true;
		var initialPoint = cpoints.shift();
		this.setRudalPosition(initialPoint.longitude, initialPoint.latitude);
		var self = this;
		var oneMovement = function() {
			if (cpoints.length > 0) {
				var destination = cpoints.shift();
				self.animateRudalMoveToWithSpeed(destination.longitude, destination.latitude, destination.kecepatan, $second2hour, oneMovement);
			} else {
				self.isMoving = false;
				if (typeof whenFinish == 'function') {
					whenFinish();
				}
			}
		}
		oneMovement();
	},

	/**
	 * Generate all position at each tick
	 * @param {Array} rute Array of {nama, longitude, latitude, kecepatan}
	 * @param {Number} asumsi 1 detik simulasi = ? jam
	 * @param {String} unit satuan kecepatan
	 * @return {Array} Array of {lon, lat, bearing}
	 */
	generateStates: function(rute, asumsi, unit) {
		var state,
			i, j, m, n,
			prevTitik, nextTitik,
			currbearing,
			duration, actualDuration,
			interval, pCount, xmove, ymove,
			longitude, latitude,
            delayStateCount;

		state = [];
		// generate state
		prevTitik = rute[0];
		for (i=1; i<rute.length; i++) {
			nextTitik = rute[i];

			currbearing = Formasi.countBearing(prevTitik.longitude, prevTitik.latitude, nextTitik.longitude, nextTitik.latitude);
			duration = Formasi.countDuration(prevTitik.longitude, prevTitik.latitude, nextTitik.longitude, nextTitik.latitude, nextTitik.kecepatan, unit);
			actualDuration = duration * 1000 / asumsi; // in milliseconds
			interval = 100; // HARDCODED! in milliseconds
			pCount = Math.round(actualDuration / interval); // amount of movements
			xmove = (nextTitik.longitude - prevTitik.longitude)/pCount; // x-axis movement each step
			ymove = (nextTitik.latitude - prevTitik.latitude)/pCount; // y-axis movement each step

            longitude = parseFloat(prevTitik.longitude);
            latitude = parseFloat(prevTitik.latitude);

            delayStateCount = Math.round((prevTitik.delay * 1000 / asumsi) / interval); // 10 = 1000 miliseconds / 100 interval
            while (delayStateCount--) {
                state.push({
                    lon: longitude,
                    lat: latitude,
                    bearing: currbearing
                });
            }

			// iterate points in one line
			for (j=0; j<pCount; j++) {

				state.push({ // add starting point until one point before the last
					lon: longitude,
					lat: latitude,
					bearing: currbearing
				});
				longitude += xmove;
				latitude += ymove;
			}

            prevTitik = nextTitik;
		}
		// add the last point
		state.push({
			lon: parseFloat(nextTitik.longitude),
			lat: parseFloat(nextTitik.latitude),
			bearing: currbearing
		});
		return state;
	},

    generateRudalStates: function(rute, asumsi, unit, rudals) {
		var state,
			i, j, m, n,
			prevTitik, nextTitik,
			rudalData, currbearing,
			duration, actualDuration,
			interval, pCount, xmove, ymove,
			longitude, latitude,
            delayStateCount;

		state = [];
		// generate state
		for (i=0; i<rute.length; i++) {
            prevTitik = rute[i];
            nextTitik = rute[(i+1)];
            rudalData = rudals;
            if( rudalData.longitude_start == prevTitik.longitude && rudalData.latitude_start == prevTitik.latitude)
            {
                currbearing = Formasi.countBearing( rudalData.longitude_start, rudalData.latitude_start, rudalData.longitude_target, rudalData.latitude_target);
                duration = Formasi.countDuration( rudalData.longitude_start, rudalData.latitude_start, rudalData.longitude_target, rudalData.latitude_target, rudalData.kecepatan);
                actualDuration = duration * 1000 / asumsi; // in milliseconds
                interval = 100; // HARDCODED! in milliseconds
                pCount = Math.round(actualDuration / interval); // amount of movements
                xmove = ( rudalData.longitude_target - rudalData.longitude_start)/pCount; // x-axis movement each step
                ymove = ( rudalData.latitude_target - rudalData.latitude_start)/pCount; // y-axis movement each step

                longitude = parseFloat( rudalData.longitude_start);
                latitude = parseFloat( rudalData.latitude_start);

                delayStateCount = Math.round((prevTitik.delay * 1000 / asumsi) / interval); // 10 = 1000 miliseconds / 100 interval
                while (delayStateCount--) {
                    state.push({
                        lon: longitude,
                        lat: latitude,
                        bearing: currbearing
                    });
                }

                // iterate points in one line
                for (j=0; j<pCount; j++) {

                    state.push({ // add starting point until one point before the last
                        lon: longitude,
                        lat: latitude,
                        bearing: currbearing
                    });
                    longitude += xmove;
                    latitude += ymove;
                }
            } else {
                if( typeof(nextTitik) != 'undefined')
                {
                    duration = Formasi.countDuration(prevTitik.longitude, prevTitik.latitude, nextTitik.longitude, nextTitik.latitude, nextTitik.kecepatan, unit);
                    actualDuration = duration * 1000 / asumsi; // in milliseconds
                    interval = 100; // HARDCODED! in milliseconds
                    pCount = Math.round(actualDuration / interval);

                    delayStateCount = Math.round((prevTitik.delay * 1000 / asumsi) / interval); // 10 = 1000 miliseconds / 100 interval
                    while (delayStateCount--) {
                        state.push({
                            lon: longitude,
                            lat: latitude,
                            bearing: currbearing
                        });
                    }

                    // iterate points in one line
                    for (j=0; j<pCount; j++) {

                        state.push({ // add starting point until one point before the last
                            lon: longitude,
                            lat: latitude,
                            bearing: currbearing
                        });
                    }
                }
            }
		}
		return state;
	}
}

// =============================================================================
// ============================== STATIC METHODS ===============================
// =============================================================================

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
Formasi.countDuration = function(lon1, lat1, lon2, lat2, speed, unit) {
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
	duration = distance / (1000 * kmph);
	return duration;
};

/**
 * Hitung derajat kemiringan garis dari titik 1 (lon1, lat1) ke titik 2 (lon2, lat2)
 * Reference: http://mathforum.org/library/drmath/view/55417.html
 * @param Number lon1,lat1 koordinat EPSG:4326 titik asal
 * @param Number lon2,lat2 koordinat EPSG:4326 titik tujuan
 * @return Number derajat kemiringan [-179.9 s/d 180] (utara = 0) clockwise positif
 */
Formasi.countBearing = function(lon1, lat1, lon2, lat2) {
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
};