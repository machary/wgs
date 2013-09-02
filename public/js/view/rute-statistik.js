"use strict";
/**
 * Class Statistik_Cb
 * menampilkan data suatu CB Operasional untuk dibandingkan
 * @uses OpenLayers.js, script.js, rute-operasional.js
 * @author Kanwil
 */
function Statistik_Cb() {
	this.totalJarak = 0;
	this.totalKapal = 0;
	this.detailKapal = {};
	this.data = [];
}

Statistik_Cb.prototype = {
	/**
	 * Menambah pasangan formasi-rute
	 * @param formation Array of {x,y,simbol_taktis,singkatan}
	 * @param points Array of {nama,longitude,latitude,kecepatan}
	 */
	addFormasi: function(formation, points) {
		var tempPoints,
			geometryPath,
			singkatan,
			i;
		// formasi dan titik harus ada
		if (formation.length < 1 || points.length < 1) {
			return;
		}
		
		// create line string
		tempPoints = []; // menampung titik untuk diberikan ke linestring
		for (i=0; i<points.length; i++) {
			tempPoints.push(new OpenLayers.Geometry.Point(points[i].longitude, points[i].latitude));
		}
		geometryPath = new OpenLayers.Geometry.LineString(tempPoints);
		
		this.data.push({
			symbols: formation,
			points: points,
			geom: geometryPath
		});
		// update statistik
		this.totalKapal += formation.length;
		this.totalJarak += geometryPath.getGeodesicLength(new OpenLayers.Projection("EPSG:4326")); // in meters
		for (i=0; i<formation.length; i++) {
			singkatan = formation[i].singkatan;
			if (typeof this.detailKapal[singkatan] === 'undefined') {
				this.detailKapal[singkatan] = 1;
			} else {
				this.detailKapal[singkatan]++;
			}
		}
	},
	
	/**
	 * @return Number Total jarak tempuh semua rute dalam kilometer
	 */
	totalJarakInKm: function() {
		return this.totalJarak / 1000;
	},
	
	/**
	 * @return Number Total jarak tempuh semua rute dalam nautical mile
	 */
	totalJarakInNauMile: function() {
		// 1 nautical mile = 1.85200 kilometers
        return this.totalJarak / 1000 / 1.852;
	},
	
	/**
	 * @return String Representasi string untuk detail kekuatan kapal
	 */
	detailKapalToString: function() {
		var result = '',
			k;
		
		for (k in this.detailKapal) {
			if (result.length) {
				result += ', ';
			}
			result += this.detailKapal[k] + ' ' + k;
		}
		return result;
	}
	
}