<div id="map" style="width: 100%; height: 300px;"></div>		

<?php
            $lat = ['30.6407','29.6496','31.6496'];
            $long = ['76.7753', '75.7567', '74.7567'];
        ?>
<script>
        var lat_array = <?=json_encode($lat)?>;
        var long_array = <?=json_encode($long)?>;

        function initMap() {
            var mapOptions = {
                zoom: 11,
                center: new google.maps.LatLng(30.6407, 75.7567),
                styles: []
            };
            var mapElement = document.getElementById('map');
            var map = new google.maps.Map(mapElement, mapOptions);

            // assuming that the length of lat_array and long_array are the same
            for(var i = 0; i < lat_array.length; i++){
				
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(parseFloat(lat_array[i]), parseFloat(long_array[i])),
                    map: map,
                    title: 'Snazzy!'
                });
            }

        }
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUzORuoDbTFYk9iKtG0_QDU59uC4uz9Bs&callback=initMap"> </script>