"use strict";
/**
 * Pengurus animasi RO
 *
 * @author Kanwil
 *
 * @param {Array} options
 - map {OpenLayers.Map}
 - $slider {jQuery} slider penanda waktu silumasi
 - $status {jQuery} memberitahu waktu simulasi sekarang
 - $assume {jQuery} input asumsi 1 detik = ? jam
 - maxStartTime {Number} berapa jam maksimal nilai slider delay
 */
var Animator_Ro = function (options) {
    var self = this;

    // options
    this.map = options.map;
    this.$slider = options.$slider;
    this.$status = options.$status;
    this.$assume = options.$assume;
    this.maxStartTime = options.maxStartTime;
    this.minStartTime = options.minStartTime;
    this.rangeTime = options.rangeTime;

    // properties
    this.resetFlag = true;
    this.isMoving = false;
    this.data = [];
    this.timer = null;
    this.maxState = options.rangeTime;
    this.currentState = 0;
    this.timeConvertion = 0;

    this.lengthSlide = options.rangeTime;

    // add vector layer
    this.layer = new OpenLayers.Layer.Vector('Rute', {
        displayInLayerSwitcher: false
    });
    this.map.addLayer(this.layer);
    // onchange
    this.$slider.slider({
        max: this.rangeTime * 10,
        slide: function (e, ui) {
            self.$status.html(self.actualTime(ui.value));
        },
        change: function (e, ui) {
            self.currentState = ui.value;
            self.setRudalState(ui.value);
            self.setState(ui.value);
            self.$status.html(self.actualTime(ui.value));
        }
    });
    this.$assume.bind('change', function () {
        self.resetFlag = true;
    });
    // refresh features after zooming
    this.map.events.register('zoomend', this.map, function (e) {
        self.redraw();
    });

    var h_start = (this.minStartTime < 0) ? Math.ceil(this.minStartTime / 24) : Math.floor(this.minStartTime / 24),
        j_start = this.minStartTime % 24,
        h_end = (this.maxStartTime < 0) ? Math.ceil(this.maxStartTime / 24) : Math.floor(this.maxStartTime / 24),
        j_end = this.maxStartTime % 24;

//        if( h_end > 1) {
//            $caption = $('<div class="inblock-caption range-caption">').insertBefore(this.$slider).text('H0 J0');
//        }

    $('<div class="inblock-slider-start range-caption">').insertBefore(this.$slider).text('H' + h_start + ' J' + j_start);
    $('<div class="inblock-slider-end range-caption">').insertAfter(this.$slider).text('H' + h_end + ' J' + j_end);

};

Animator_Ro.prototype = {
    /**
     * Add an item (pair of formation and route)
     * @param {Array} symbols Array of {x,y,simbol_taktis}
     * @param {Array} points Array of {nama,longitude,latitude,kecepatan}
     * @param {String} unit satuan kecepatan "kmph"/"knot"
     * @param {jQuery} $slider Penanda waktu berangkat
     */
    addItem: function (symbols, points, rudals, unit, $slider, $durasi, $value) {
        var i, l,
            self = this,
            tempPoints,
            featureRute,
            $status,
            $caption;

        if (symbols.length < 1 || points.length < 1) {
            return;
        }
        // create line string
        tempPoints = []; // menampung titik untuk diberikan ke linestring
        for (i = 0, l = points.length; i < l; i++) {
            tempPoints.push(new OpenLayers.Geometry.Point(points[i].longitude, points[i].latitude));
        }

        var random_color;
        do
        {
            random_color = '#'+Math.floor(Math.random()*16777216).toString(16); // random color generator
        }
        while (random_color == '#ff0000');

        featureRute = new OpenLayers.Feature.Vector(
            new OpenLayers.Geometry.LineString(tempPoints),
            null,
            {
                strokeWidth: 1.1,
                strokeDashstyle: 'dash',
                strokeColor : random_color
            }
        );
        this.layer.addFeatures([featureRute]);
        // slider notification

        var current = $value + this.minStartTime;
        var h = (current < 0) ? Math.ceil(current / 24) : Math.floor(current / 24),
            j = current % 24,
            h_start = (this.minStartTime < 0) ? Math.ceil(this.minStartTime / 24) : Math.floor(this.minStartTime / 24),
            j_start = this.minStartTime % 24,
            h_end = (this.maxStartTime < 0) ? Math.ceil(this.maxStartTime / 24) : Math.floor(this.maxStartTime / 24),
            j_end = this.maxStartTime % 24;

        if( h_end > 1) {
            $caption = $('<div class="inblock-caption range-caption">').insertBefore($slider).text('H0 J0');
        }

        $('<div class="inblock-start range-caption">').insertBefore($slider).text('H' + h_start + ' J' + j_start);
        $status = $('<div class="inblock">').insertAfter($slider).text('H' + h + ' J' + j);
        $('<div class="inblock-end range-caption">').insertAfter($slider).text('H' + h_end + ' J' + j_end);
        // aktifkan slider
        $slider.slider({
            value: $value,
            max: self.rangeTime - Math.round($durasi), // nilai slider ini satuannya jam
            slide: function (e, ui) {
                // sementara
                var current = ui.value + self.minStartTime;
                var h = (current < 0) ? Math.ceil(current / 24) : Math.floor(current / 24),
                    j = current % 24;

                $status.text('H' + h + ' J' + j);

            },
            change: function (e, ui) {
                self.resetFlag = true;

                self.resetLeft(ui.value, self.lengthSlide, $slider);
            }
        });


        this.rewidthHandler(this, $slider, this.rangeTime);
        this.resetLeft($value, this.lengthSlide, $slider);
        if( h_end > 1) {
            this.resetCaptionLeft(Math.abs(this.minStartTime), this, $slider, $caption);
        }

        // save this item
        this.data.push({
            formation: new Formasi(symbols, this.layer, rudals),
            route: points,
            rudals: rudals,
            unit: unit,
            line: featureRute,
            state: [],
            rudalState: [],
            lastState: -1,
            lastRudalState: -1,
            $slider: $slider,
            durasi: $durasi
        });

    },

    /**
     * Generate all positions for all items at each tick
     */
    generateStates: function () {
        var i, l, j,
            item,
            delayHour,
            delayStateCount,
            delayStates1,
            delayStates2,
            maxPoint = 0,
            last;

        this.timeConvertion = parseInt(this.$assume.val(), 10);
        for (i = 0, l = this.data.length; i < l; i++) {
            item = this.data[i];

            if (item.route.length > 1) {
                // generate delay state
                delayHour = (typeof item.$slider != "undefined") ? item.$slider.slider('option', 'value') : 0;
                delayStateCount = Math.floor((delayHour / this.timeConvertion) * 10); // 10 = 1000 miliseconds / 100 interval
                delayStates1 = [];
                while (delayStateCount--) {
                    delayStates1.push({
                        lon: item.route[0].longitude,
                        lat: item.route[0].latitude,
                        bearing: 0
                    });
                }
                // generate state
                item.state = delayStates1.concat(item.formation.generateStates(item.route, this.timeConvertion, item.unit));

                delayHour = item.$slider.slider('option', 'value');
                delayStateCount = Math.floor(((this.rangeTime -(delayHour + parseInt(item.durasi))) / this.timeConvertion) * 10); // 10 = 1000 miliseconds / 100 interval
                delayStates2 = [];
                last = item.state.length;
                if(delayStateCount > 0)
                {
                    while (delayStateCount--) {
                        delayStates2.push({
                            lon: item.state[last-1].lon,
                            lat: item.state[last-1].lat,
                            bearing: item.state[last-1].bearing
                        });
                    }
                }
                item.state = item.state.concat(delayStates2);

                maxPoint = (maxPoint < item.state.length) ? item.state.length : maxPoint;

                item.rudalState = [];

                if(typeof(item.rudals) != "undefined")
                {
                    for (j=0; j<item.rudals.length; j++) {
                        item.rudalState.push(delayStates1.concat(item.formation.generateRudalStates(item.route, this.timeConvertion, item.unit, item.rudals[j])));
                        maxPoint = (maxPoint < item.rudalState[j].length) ? item.rudalState[j].length : maxPoint;
                    }
                }
                delayStates2 = [];
            }
            this.maxState = maxPoint - 1;
            this.lengthSlide = (this.maxState / ( 10 / this.timeConvertion));
            this.rewidthHandler( this, item.$slider, this.lengthSlide);
        }
        this.$slider.slider( 'option', 'max', this.maxState);
        this.$slider.slider( 'value', 0);

        this.resetChildSlider( this, this.data, this.maxState);
    },

    /**
     * "Jump" to specific state
     * @param {Number} ii which state
     */
    setState: function (ii) {
        var i, l,
            item, state,
            relativeii;

        for (i = 0, l = this.data.length; i < l; i++) {
            item = this.data[i];
            if (ii >= item.state.length) {
                relativeii = item.state.length - 1;
            } else {
                relativeii = ii;
            }
            if (relativeii !== item.lastState) {
                item.lastState = relativeii;
                state = item.state[relativeii];
                item.formation.setPosition(state.lon, state.lat);
                if (item.formation.bearing !== state.bearing) {
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

                if (relativeii != item.lastRudalState) {
                    item.lastRudalState = relativeii;
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

    /**
     * Start the animation, will recalculate states if needed
     */
    startAnimation: function () {
        var self = this,
            tickFunction;

        if (this.resetFlag) {
            this.generateStates();
            this.resetFlag = false;
        }

        if (!this.isMoving) {
            tickFunction = function () {
                if (self.currentState === self.maxState) {
                    self.stopAnimation();
                } else {
                    self.$slider.slider('value', self.currentState + 1);
                    self.timer = window.setTimeout(tickFunction, 100);
                }
            };
            this.timer = window.setTimeout(tickFunction, 100);
            this.isMoving = true;
        }
    },

    /**
     * Stop animation
     */
    stopAnimation: function () {
        window.clearTimeout(this.timer);
        this.isMoving = false;
    },

    /**
     * Return to animation state zero
     */
    resetAnimation: function () {
        this.stopAnimation();
        if (this.resetFlag) {
            this.generateStates();
            this.resetFlag = false;
        }
        this.$slider.slider('value', 0);
    },

    /**
     * Redraw all features
     */
    redraw: function () {
        var i, j;

        for (i=0; i<this.data.length; i++) {
            this.data[i].formation.redraw();

            for (j=0; j<this.data[i].rudals.length; j++) {
                this.data[i].formation.redrawRudal(j);
            }
        }
    },

    /**
     * Return text representation of current real time
     * @param {Number} ii the slider value
     */
    actualTime: function (ii) {
        // ii is the value of slider, in 0.1 seconds unit
        // this.timeConvertion is 1 second equal how many actual hours
        var hours = (ii / 10 * this.timeConvertion) + this.minStartTime;

        var days = (hours < 0) ? Math.ceil(hours / 24): Math.floor(hours / 24);

        hours = Math.round(hours % 24);
        return 'H' + days + ' J' + hours;
    },

    resetLeft: function (value, range, slider) {
        var el = slider.find('a');
        var gerak = (value) / (range) * 100;

        el.css('left', ( gerak ) + '%');
    },

    rewidthHandler: function ( object, slider, rangeTime) {
        var sliderRange = slider.slider('option', 'max');
        var defaultWidth = (slider.width()) / object.lengthSlide;
        var widthHandler = (rangeTime - sliderRange) * defaultWidth;
        slider.find('a').css('width', widthHandler + 13  + 'px');
    },

    resetCaptionLeft: function (value, object, slider, el) {
        var gerak = (value/2) / (object.maxStartTime - object.minStartTime) * 100;
        el.css('left', ( gerak ) + '%');
    },

    resetChildSlider: function ( object, data, maxState ) {
        var i,l;
        for (i = 0, l = data.length; i < l; i++) {
            var slider = data[i].$slider;
            if(typeof slider != "undefined")
            {
                var durasi = slider.attr('durasi');
                slider.slider( 'option', 'max', Math.round(( (maxState) / 10) - Math.round(durasi)));
                this.rewidthHandler( object, slider, maxState / 10);
                this.resetLeft(slider.slider( 'option', 'value'), object.lengthSlide, slider);
            }
        }
    }
};