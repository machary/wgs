"use strict";
/**
 * Class Animator Multi-Formasi
 * @uses OpenLayers.js, JQuery, script.js, view/rute-operasional.js
 * @author Kanwil
 *
 * @param {OpenLayers.Layer.Vector} layer
 * @param {jQuery} $slider Slider penanda waktu
 * @param {jQuery} $status Text penanda waktu
 */
function Animator_Multi_Formasi(layer, $slider, $status) {
	var self = this;
	$slider = $slider || $('#time-slider');
	$status = $status || $('#time-notice');
	
	this.layer = layer;
	this.$slider = $slider.slider({
		slide: function (e, ui) {
			$status.html(self.actualTime(ui.value));
		},
		change: function (e, ui) {
			self.currentState = ui.value;
            self.setRudalState(ui.value);
            self.setState(ui.value);
			$status.html(self.actualTime(ui.value));
		}
	});
}

Animator_Multi_Formasi.prototype = {
	// properties
	layer: null,
	$slider: null,
	data: [], // {formation, route, line, state}
	timer: null,
	isMoving: false,
	maxState: 0,
	currentState: 0,
	timeConvertion: 0, // 1 second = this in hours
	
	/**
	 * Add an item (pair of formation and route)
	 * @param {Array} symbols Array of {x,y,simbol_taktis}
	 * @param {Array} points Array of {nama,longitude,latitude,kecepatan}
	 */
	addItem: function (symbols, points, rudals) {
		var tempPoints,
			i, 
			featureRute,
            tempDelay;

		if (symbols.length < 1 || points.length < 1) {
			return;
		}
		// create line string
		tempPoints = []; // menampung titik untuk diberikan ke linestring
        tempDelay = [];
		for (i=0; i<points.length; i++) {
            tempPoints.push(new OpenLayers.Geometry.Point(points[i].longitude, points[i].latitude));
		}

        featureRute = new OpenLayers.Feature.Vector(
			new OpenLayers.Geometry.LineString(tempPoints),
			null, 
			{
                strokeWidth: 1.1,
                strokeDashstyle: 'dash',
                strokeColor: '#363636'
			}
		);

		this.layer.addFeatures([featureRute]);
		// add to layer
		this.data.push({
			formation: new Formasi(symbols, this.layer, rudals),
			route: points,
            rudals: rudals,
			line: featureRute,
			state: [],
			rudalState: [],
			lastState: -1,
			lastRudalState: -1
		});
	},

	/**
	 * Generate all position for all items at each tick
	 * @param {Number} asumsi 1 detik simulasi = ? jam
	 * @param {String} unit satuan kecepatan "kmph"/"knot"
	 */
	generateState: function (asumsi, unit) {
		var i,j,
			item,
			maxPoint = 0;

		this.timeConvertion = asumsi;

        for (i=0; i<this.data.length; i++) {
			item = this.data[i];
			// generate state
			if (item.route.length > 1) {
				item.state = item.formation.generateStates(item.route, asumsi, unit);
                maxPoint = (maxPoint < item.state.length) ? item.state.length : maxPoint;
                item.rudalState = [];
                if(typeof(item.rudals) != 'undefined')
                {
                    for (j=0; j<item.rudals.length; j++) {
                        item.rudalState.push(item.formation.generateRudalStates(item.route, asumsi, unit, item.rudals[j]));
                        maxPoint = (maxPoint < item.rudalState[j].length) ? item.rudalState[j].length : maxPoint;
                    }
                }
			}
        }

		this.maxState = maxPoint-1;
		// set max value for slider according to most point count
		this.$slider.slider('option', 'max', this.maxState);
		this.$slider.slider('value', 0);
	},
	
	/**
	 * "Jump" to specific state
	 * @param {Number} ii which state
	 */
	setState: function (ii) {
		var i, l,
			item, state,
			relativeii;
		
		for (i=0, l=this.data.length; i<l; i++) {
			item = this.data[i];
			if (ii >= item.state.length) {
				relativeii = item.state.length-1;
			} else {
				relativeii = ii;
			}
    		if (relativeii != item.lastState) {
				item.lastState = relativeii;
                state = item.state[relativeii];
                if( typeof(state.lon)!='undefined' && typeof(state.lon)!='undefined' ){
                    item.formation.setPosition(state.lon, state.lat);
                }
                if (item.formation.bearing != state.bearing) {
                    item.formation.setBearing(state.bearing);
                }
			}
		}
	},
	setRudalState: function (ii) {
		var i, l, j,
			item, rudal,
			relativeii;

		for (i=0, l=this.data.length; i<l; i++) {
			item = this.data[i];

            for(j=0; j<item.rudalState.length; j++) {
                if (ii >= item.rudalState[j].length) {
                    relativeii = item.rudalState[j].length-1;
                } else {
                    relativeii = ii;
                }
            }

    		if (relativeii != item.lastRudalState) {
        		item.lastRudalState = relativeii;
                for(j=0; j<item.rudalState.length; j++){
                    if(typeof(item.rudalState[j][relativeii])!='undefined')
                    {
                        rudal = item.rudalState[j][relativeii];
                        if( typeof(rudal.lon)!='undefined' && typeof(rudal.lat)!='undefined' ){
                            item.formation.setRudalPosition(rudal.lon, rudal.lat, j);
                            item.formation.unhideRudal( j);
                        }
                        else
                        {
                            item.formation.hideRudal( j);
                        }
                    }
                }

                for(j=0; j<item.rudalState.length; j++){
                    if(typeof(item.rudalState[j][relativeii])!='undefined')
                    {
                        rudal = item.rudalState[j][relativeii];
                        if( typeof(rudal.bearing)!='undefined')
                        {
                            item.formation.setRudalBearing(rudal.bearing,j);
                        }
                    }
                }
			}
		}
	},
	
	startAnimation: function () {
		var self = this,
			tickFunction;
			
		if (!this.isMoving) {
			tickFunction = function () {
				if (self.currentState == self.maxState) {
					self.stopAnimation();
				} else {
					self.$slider.slider('value', self.currentState+1);
					// self.timer = window.setTimeout(tickFunction, 100);
				}
			};
			this.timer = window.setInterval(tickFunction, 100);
			this.isMoving = true;
		}
	},
	
	stopAnimation: function () {
		window.clearInterval(this.timer);
		this.isMoving = false;
	},
	
	resetAnimation: function () {
		this.stopAnimation();
		this.$slider.slider('value', 0);
	},

	/**
	 * Return text representation of current real time
	 * @param {Number} ii the slider value
	 */
	actualTime: function (ii) {
		// ii is the value of slider, in 0.1 seconds unit
		// this.timeConvertion is 1 second equal how many actual hours
		var hours = ii / 10 * this.timeConvertion,
			days = Math.floor(hours / 24);
			
		hours = Math.round(hours % 24);
		return 'H'+days+' J'+hours;
	},
	
	/**
	 * Redraw all feature
	 */
	redraw: function () {
		var i, j;
		
		for (i=0; i<this.data.length; i++) {
			this.data[i].formation.redraw();

            for (j=0; j<this.data[i].rudals.length; j++) {
                this.data[i].formation.redrawRudal(j);
            }
		}
	}
}