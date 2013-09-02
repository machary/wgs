"use strict";
/**
 * Daftar konfigurasi peta yang secara umum dipakai
 * @uses OpenLayers.js
 */
// Global object
var PETA_DASAR = {};
// Global constants

//PETA_DASAR.geoUrl = "http://10.1.1.103:8080/geoserver/wms"; // NO CACHE
//PETA_DASAR.geoUrl = "http://10.1.1.103:8080/geoserver/gwc/service/wms"; // WITH CACHE
PETA_DASAR.geoUrl = "http://localhost:8080/geoserver/seskoal/wms"; // NO CACHE
PETA_DASAR.format = 'image/png';

OpenLayers.DOTS_PER_INCH = 90.71428571428572; // Dibutuhkan untuk kesesuaian dengan peta hasil cache

// Default map options
PETA_DASAR.getOptions = function () {
	return {
		controls: [],
		//maxResolution: 0.1,
		projection: "EPSG:4326",
		units: 'degrees',
		restrictedExtent: new OpenLayers.Bounds(88.109896320213, -14.740413207701, 147.65989632021, 9.9595867922987)
		// maxExtent: new OpenLayers.Bounds(95.115729755272, -12.012413207701, 140.70672975527, 7.4095867922987)
	};
};

// Default map layers
PETA_DASAR.getLayers = function () {
	return [
		// Load layer pertama
        //Layer --> 0 ,teks,teks3
        new OpenLayers.Layer.WMS(
			"Provinsi", PETA_DASAR.geoUrl,
			{
                //layers: 'provinsi,teks3,grid,kontur',
				layers: 'gr1',
				format: PETA_DASAR.format,
				STYLES: '',
				bgcolor: '0xFFFFFF'
			},
			{
				isBaseLayer: true,
				displayInLayerSwitcher: false,
				transitionEffect: 'resize'
			}
		),

        // Kelompok Layer Peta Dasar Wilayah Darat
        // Layer --> 1 (batas kabupaten / kota)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Darat|Kota / Kabupaten", PETA_DASAR.geoUrl,
            {
                transparent: true,
                layers: 'kabupaten',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
		),
        // Layer --> 2 (Tutupan Lahan)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Darat|Tutupan Lahan", PETA_DASAR.geoUrl,
            {
                transparent: true,
                layers: 'land',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 3 (jalan)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Darat|Jalan", PETA_DASAR.geoUrl,
            {
                transparent: true,
                layers: 'jalan',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 4 (sungai)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Darat|Sungai", PETA_DASAR.geoUrl,
            {
                transparent: true,
                layers: 'sungai',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 5 (Titik Tinggi)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Darat|Titik Tinggi", PETA_DASAR.geoUrl,
            {
                transparent: true,
                layers: 'tinggi',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 6 (gunung)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Darat|Gunung", PETA_DASAR.geoUrl,
            {
                transparent: true,
                layers: 'gunung',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),

        // Kelompok Layer Peta Dasar Wilayah Laut
        // Layer --> 7 (batas garis pantai)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Laut|Batas Garis Pantai", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'grs_pantai',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
////////// Layer --> 8 (batas garis pantai)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Laut|Bathymetri", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'bathymetri',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
       // Layer --> 8 (Wilayah Perairan Dangkal) ,bathymetri 
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Laut|Titik Kedalaman", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'gr2',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 9 (batas laut teritorial)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Laut|Batas Teritorial 12 mi", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'laut_teritori',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 10 (batas ZEE)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Laut|Batas ZEE 200 mi", PETA_DASAR.geoUrl,
            {
                transparent: true,
                layers: 'zee',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 11 (Jalur ALKI)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Administrasi Laut|Jalur ALKI", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'alki',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Kelompok Layer Peta Lingkungan Laut
        // Layer --> 12 (Jaringan Listrik Laut)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Kabel (Listrik)", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'kabel',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 13 (Rintangan Laut)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Rintangan Berbahaya", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'rintangan_berbahaya',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 14 (Kapal Karam)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Kapal Karam", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'kapal_karam',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 15 (Pelampung)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Pelampung", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pelampung',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 16 (Mercusuar)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Mercusuar", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'mercusuar',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 17 (Area Berlabuh)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Area Berlabuh", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'area_berlabuh',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 18 (Daerah Terlarang)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Daerah Terlarang", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'daerah_terlarang,d.terlarang',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 19 (Bom Laut)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Bom Laut", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'bom',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 20 (Taman Laut)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Taman Nasional Laut", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'taman',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 21 (Latihan Militer)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Lingkungan Laut|Kawasan Latihan Militer", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'militer,latmil',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),

        // Kelompok Layer Peta Dasar Transportasi
        // Layer --> 22 (Bandara Udara)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Transportasi|Bandara Udara", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'bandara',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 23 (Pelabuhan)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Transportasi|Pelabuhan", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pelabuhan',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),

        // Kelompok Layer Peta Dasar Asset Negara
        // Layer --> 24 (Industri Pertahanan)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Asset Negara|Industri Pertahanan", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'industri',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 25 (Depot Pertamina)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Asset Negara|Depot Pertamina", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pertamina',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 26 (PLTU-PLTA)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Asset Negara|PLTU/PLTA", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pembangkit',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),

        // Kelompok Layer Peta Dasar Obyek Vital
        // Layer --> 27 (Pertambangan)
        new OpenLayers.Layer.WMS(
            "Peta Dasar|Obyek Vital|Pertambangan", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'tambang',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),

        // Kelompok Layer Peta Kesatuan Angkatan TNI-AL
        // Layer --> 28 (Kawasan Koarmabar)
         new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KOARMABAR|Batas Kawasan", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'koarmabar',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 29 (Pusat Pangkalan Wilayah Barat)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KOARMABAR|Lanal & Lantamal", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pst_koarmabar',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 30 (Kawasan Wilayah Timur)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KOARMATIM|Batas Kawasan", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'koarmatim',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 31 (Pusat Pangkalan Wilayah Timur)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KOARMATIM|Lanal & Lantamal", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pst_koarmatim',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 32 (Lokasi Pasmar)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KORMAR|Pasmar I & Pasmar II", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pasmar',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 33 (Lokasi Komando Lat Militer)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KORMAR|Kolatmar", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'kolat',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 34 (Lokasi Brigif)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KORMAR|Brigif 3", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'Brigif',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 35 (Lokasi Rumkit CLD)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KORMAR|Rumkit CLD", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'Rumkit',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 36 (Lokasi Pangkalan Marinir)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KORMAR|Lanmar", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'lanmar',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 37 (Lokasi Denjaka)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|KORMAR|Denjaka", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'denjaka',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 38 (Lokasi Pos-AL)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|Lain-Lain|Pos AL", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'posal',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 39 (Lokasi Pelabuhan udara AL)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|Lain-Lain|Pelabuhan Udara AL", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pelud',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 40 (Lokasi Stasion AL)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|Lain-Lain|Stasion AL", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'sional',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 41 (Lokasi Stasion Udara AL)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|Lain-Lain|Stasion Udara AL", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'sionudal',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 42 (Lokasi Pangkalan Udara AL)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|Lain-Lain|Landasan Udara AL", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'lanudal',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 43 (Lokasi Pangkalan Udara AL) ,singkawang1
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AL|Lain-Lain|KARVAK", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'sangata',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),

        // Kelompok Layer Peta Kesatuan Angkatan TNI-AD
        // Layer --> 44 (Wilayah Kodam)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AD|KODAM|Wilayah Kodam", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'kodam',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 45 (Pusat Kodam)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AD|KODAM|Pusat Kodam", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'pusat_kodam',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 46 (Pusat Yonif)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AD|YONIF|Pusat Yonif", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'yonif',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),

        // Kelompok Layer Peta Kesatuan Angkatan TNI-AU
        // Layer --> 47 (Pusat KoopsAU)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AU|KoopsAU|KoopsAU 1", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'koopsau 1',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 48 (Pusat KoopsAU)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AU|KoopsAU|KoopsAU 2", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'koopsau 2',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
        // Layer --> 49 (Radar Udara)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AU|Coverage Radar|Radar Militer", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'ramil',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        ),
		// Layer --> 49 (Radar Udara)
        new OpenLayers.Layer.WMS(
            "Kesatuan TNI|TNI-AU|Coverage Radar|Radar Sipil", PETA_DASAR.geoUrl,
            {
                transparent : true,
                layers: 'rasip',
                format: PETA_DASAR.format
            },
            {isBaseLayer: false, visibility: false}
        )
	];
};



// Global function to transform the layer switcher into tree representation
// Menambahkan method redrawSwitcherTree() ke object map
PETA_DASAR.makeLayerSwitcherTree = function (map) {
	var $link,
		makeLayerTree,
		oldcontrol,
		$switcher, switcherControl, prevRedraw,
		clearUniform;

	// tambahkan css file
	if ($('link.layer-switcher-tree-css').size() < 1) {
		$link = $('<link class="layer-switcher-tree-css" type="text/css" rel="stylesheet">');
		$link.attr('href', baseUrl + '/css/view/switcher-tree.css');
		$('head').first().append($link);
	}
	// parsing nama layer "<NAMA PARENT>|<NAMA ANAK>" diubah jadi tree 1 level

	// Convert layer switcher jadi berbentuk tree
	// @param JQuery $switcher div yang menampung daftar layer (class .dataLayersDiv)
	//
	makeLayerTree = function ($switcher) {
		var defaultParentName = 'Lain-Lain', // HARDCODED!
			tree = {},
			br = [], // element buangan
			tempInput, tempSpan,
			child, i, pname,
			toggleFunction,
			$children, $li, $parent,
			branchCount, parentName, branch;

		$switcher.attr('id', 'switcher-tree'); // HARDCODED! untuk styling css

		// retrieve all layers
		$switcher.children().each(function (i, e) {
			var parentName, matches;

			if (e.nodeName === 'INPUT') {
				// checkbox
				tempInput = $(e);
			} else if (e.nodeName === 'SPAN') {
				// label
				tempSpan = $(e);
			} else if (e.nodeName === 'BR') {
				// saatnya insert elemen baru
				br.push(e);
				if (matches = tempSpan.html().match(/^(.*)\|(.*)$/)) {
					parentName = matches[1];
					tempSpan.html(matches[2]);
				} else {
					parentName = defaultParentName;
				}
				if (!tree.hasOwnProperty(parentName)) {
					tree[parentName] = [];
				}
				tree[parentName].push({
					parent: parentName,
					checkbox: tempInput,
					label: tempSpan
				});
				tempInput = tempSpan = null;
			}
		});
		// show/hide children
		toggleFunction = function (e) {
			$(this).next('ul').toggle();
		}
		// generate tree element
		for (pname in tree) {
			$parent = $('<div class="branch">');
			$children = $('<ul>');

			$parent.html(pname);
			$parent.bind('click', toggleFunction);
			for (i = 0; i < tree[pname].length; i += 1) {
				child = tree[pname][i];
				$li = $('<li>');
				$li.append(child.checkbox).append(child.label);
				$children.append($li);
			}

			$switcher.append($parent).append($children);
		}
		// remove unused elements
		for (i = 0; i < br.length; i += 1) {
			$(br[i]).remove();
		}
		// parse parents to create another level
		do {
			tree = {};
			branchCount = 0;
			$switcher.children('div').each(function (i, e) {
				var $this = $(this),
					matches = $this.html().match(/^(.*)\|(.*)$/);
				
				if (matches) {
					parentName = matches[1];
					if (!tree.hasOwnProperty(parentName)) {
						tree[parentName] = [];
						branchCount++;
					}
					tree[parentName].push({
						childName: matches[2],
						$label: $this,
						$list: $this.next('ul'),
					});
				}
			});
			// apakah masih ada yg bisa dibuat parent
			if (branchCount > 0) {
				for (pname in tree) {
					branch = tree[pname];
					// @TODO cek apakah sudah ada parent bernama ini
					$parent = $('<div class="branch">');
					$children = $('<ul>');
					
					$parent.html(pname);
					$parent.bind('click', toggleFunction);
					
					for (i = 0; i < branch.length; i++) {
						child = branch[i];
						child.$label.html(child.childName);
						$li = $('<li>');
						$li.append(child.$label).append(child.$list);
						$children.append($li);
					}
					$switcher.append($parent).append($children);
				}
			}
		} while (branchCount > 0);
		return tree;
	};
	// remove default layer switcher control
	oldcontrol = map.getControlsByClass('OpenLayers.Control.LayerSwitcher');
	map.removeControl(oldcontrol[0]);
	// create new control class
	switcherControl = new OpenLayers.Control.LayerSwitcher();
	prevRedraw = switcherControl.redraw;
	OpenLayers.Util.extend(switcherControl, {
		redraw: function () {
			if (!this.drawn) {
				prevRedraw.call(switcherControl);
				this.drawn = true;
			}
		}
	});
	map.addControl(switcherControl);
	$switcher = $(map.div).find('div[class="dataLayersDiv"]').first();
	makeLayerTree($switcher);
	// clear the uniform.js attributes
	clearUniform = function () {
		var $uni = $switcher.find('div.checker');
		// check whether uniformed element exists or not
		if ($uni.length > 0) {
			$uni.each(function (i) {
				var $this = $(this),
					$origin = $this.find('input').first();;
				
				$origin.removeAttr('style');
				$origin.prependTo($this.parent());
				$this.remove();
			});
		} else {
			// try again later
			window.setTimeout(clearUniform, 1000);
		}
	};
	window.setTimeout(clearUniform, 1000);
	
	// add method
	map.redrawSwitcherTree = function () {
		switcherControl.drawn = false;
		switcherControl.redraw();
		makeLayerTree($switcher);
	};	
};