<!DOCTYPE html>
<html>

<head>
    <meta charset=utf-8 />
    <title>A simple map</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />

    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>

    <div id='map' style=''></div>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoicnl0b2RldiIsImEiOiJjbGtncDB3a3YwMXV3M2VvOHFqdmd2NWY4In0.pag9rpV51QYupsyPdSFfOw';
        const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v12', // style URL
            center: [108.281043, -6.408414], // starting position [lng, lat]
            zoom: 14, // starting zoom
        });
    </script>s
</body>

</html>
