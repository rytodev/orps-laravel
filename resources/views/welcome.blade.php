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
        function initMap() {
            var mapCanvas = document.getElementById('map');
            var mapOptions = {
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(mapCanvas, mapOptions);
            var infoWindow = new google.maps.InfoWindow;
            var bounds = new google.maps.LatLngBounds();

            function bindInfoWindow(marker, map, infoWindow, html) {
                google.maps.event.addListener(marker, 'click', function() {
                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                });
            }

            function addMarker(lat, lng, info, color) {
                var pt = new google.maps.LatLng(lat, lng);
                bounds.extend(pt);

                var marker = new google.maps.Marker({
                    map: map,
                    position: pt,
                    // icon: getCustomMarkerIcon(color),

                });
                map.fitBounds(bounds);
                bindInfoWindow(marker, map, infoWindow, info);
            }
            const items = {
                @json($lokasi)
            };

            items.forEach(item => {
                addMarker(
                    item.lat,
                    item.lng,
                    "<b>Nama Lokasi : </b>" + item.name +
                    "<br> <b>Latitude : </b> " + item.lat +
                    "<br> <b>Driver : </b> " + item.driver_name +
                    "<br> <b>Longtitude : </b>" + item.lng,
                    item.color,
                );
            });
        }

        window.initMap = initMap;
    </script>

</body>

</html>
