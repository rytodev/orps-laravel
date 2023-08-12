<html>

<head>
    <title>Default Advanced Marker</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
</head>

<body>
    <div id="map" style="height: 500px;"></div>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9V-m-1908zbjyEEE042iAqgdacw70nkw&callback=initMap&v=beta&libraries=marker"
        defer></script>
    <script>
        async function initMap() {
            var infoWindow = new google.maps.InfoWindow;

            function bindInfoWindow(marker, map, infoWindow, html) {
                google.maps.event.addListener(marker, 'click', function() {
                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                });
            }

            function pinColor(background, glyphColor, borderColor) {
                return new google.maps.marker.PinView({
                    background: background,
                    glyphColor: glyphColor,
                    borderColor: borderColor
                });
            }

            const map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: -6.1754,
                    lng: 106.8272
                },
                zoom: 8,
                mapId: "1",
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            function addMarker(lat, lng, info, color) {
                var pt = new google.maps.LatLng(lat, lng);
                bounds.extend(pt);

                var marker = new google.maps.marker.AdvancedMarkerView({
                    map,
                    position: pt,
                    content: pinColor("yellow", "red", "black").element,
                });
                map.fitBounds(bounds);
                bindInfoWindow(marker, map, infoWindow, info);
            }
            const items = [{
                name: '1',
                lat: -6.408495,
                lng: 108.281456,
                driver_name: 'pak supir',
                color: 'blue'
            }, {
                name: '2',
                lat: -6.408466,
                lng: 108.280794,
                driver_name: 'pak supir',
                color: 'blue'
            }];

            items.forEach(item => {
                addMarker(
                    item.lat,
                    item.lng,
                    "<b>Nama Lokasi : </b>" + item.name +
                    "<br> <b>Latitude : </b> " + item.lat +
                    "<br> <b>Driver : </b> " + item.driver_name +
                    "<br> <b>Longtitude : </b>" + item.lng,
                    item.color
                );
            });

        }


        window.initMap = initMap;
    </script>

</body>

</html>
