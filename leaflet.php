<!DOCTYPE html>
<html>
<head>
    <title>test</title>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/ardhi/Leaflet.MousePosition/master/src/L.Control.MousePosition.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.js" type="text/javascript"></script>
    <script src="https://cdn.rawgit.com/ardhi/Leaflet.MousePosition/master/src/L.Control.MousePosition.js" type="text/javascript"></script>
    <style>
        html, body, #map {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            z-index: 1;
            background: url(images/tiles/water.png) repeat;

        }

        #slider {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 5;
        }
    </style>
</head>
<body>
<div id="map"></div>
<input id="slider" type="range" min="0" max="1" step="0.1" value="1" oninput="layer.setOpacity(this.value)">
<script type="text/javascript">
    var mapExtent = [1022.5, 1022.5,1022.5,1022.5];
    var mapMinZoom = 0;
    var mapMaxZoom = 3;
    var mapMaxResolution = 1;
    var mapMinResolution = Math.pow(2, mapMaxZoom) * mapMaxResolution;
    var tileExtent = [0,0,0,0];
    var crs = L.CRS.Simple;
    crs.transformation = new L.Transformation(1, 0, 1, 0);
    crs.scale = function(zoom) {
        return Math.pow(2, zoom) / mapMinResolution;
    };
    crs.zoom = function(scale) {
        return Math.log(scale * mapMinResolution) / Math.LN2;
    };
    var layer;
    var map = new L.Map('map', {
        maxZoom: mapMaxZoom,
        minZoom: mapMinZoom,
        crs: crs
    });

    layer = L.tileLayer('images/tiles/sat.{z}.{x}.{y}.png', {
        minZoom: mapMinZoom, maxZoom: mapMaxZoom,
        attribution: 'Charles Blackwood',
        noWrap: true,
        tms: false
    }).addTo(map);

    function mirrorNumbers(min, max, num) {
        j = (max - num) - (num - min);
        return num + j;
    }

    function addToMap(x, y, a) {
        const mapSideLength = 2045.0;
        const topLeftX = -2990.0;
        const topLeftY = 3000.0;

        x = mirrorNumbers(0.0, mapSideLength, (x / topLeftX) * mapSideLength ) / 2.0;
        y = mirrorNumbers(0.0, mapSideLength, (y / topLeftY) * mapSideLength ) / 2.0;

        console.log('Add marker to ' + x + ', ' + y + ', ' + a)
        L.marker([y,x]).addTo(map)
            .bindPopup(a)
            .openPopup()
    }
    addToMap(-2957.2361,3001.0002, 'Top left'); //top left
    addToMap(0,0,'test');
    addToMap(2959.9641,-2969.1841,'Bottom right'); // bottom right
    addToMap(1683.0466,-1796.4633, 'Random place around Unity Station');
    addToMap(2152.9021,-1882.4603, 'Random place around Idlewood / Willowfield area');
    addToMap(-2090.7651,2313.2822, 'Tierra Robada');
    addToMap(-295.2350,1550.7010, '-295.2350,1550.7010');


    map.fitBounds([
        crs.unproject(L.point(mapExtent[2], mapExtent[3])),
        crs.unproject(L.point(mapExtent[0], mapExtent[1]))
    ]);
    L.control.mousePosition().addTo(map)
</script>
</body>
</html>
