<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>ルート作成</title>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style>
        #maps{
            height: 400px;
            width: 800px;
        }
        </style>
    </head>
    <body>
        <a>出発地点</a></br>
        <input type="text"  id="place_1" placeholder="出発地点を入力して下さい"></br>
        
        <a>経由地点</a></br>
        <div id="form_area">
            <input type="text" id="place_3" placeholder="経由地を入力してください">
            <button id="3" onclick="deleteBtn(this)">削除</button>
        </div>
        <input  type="button" value="経由地追加"  onclick="addForm()"></br>
        
        <a>目的地</a></br>
        <input type="text"  id="place_2" placeholder="目的地を入力してください">
        
        <input type="button" value="ルート作成" onclick="calcRoute()">
        
        <div id="maps"></div>
        
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDh09VL82gIovb7lT94whL6Io0yJxxy9oQ&callback=initAutocomplete&libraries=places&v=weekly"
            defer
        ></script>
        
        
        <script>
        var map;
        var lat;
        var lng;
        var i = 4;
        var points = [];
        var wayPoints = [];

            function addForm() {
                var input_data = document.createElement('input');
                input_data.type = 'text';
                input_data.id = 'place_' + i;
                input_data.placeholder = '経由地を入力してください';
                var parent = document.getElementById('form_area');
                parent.appendChild(input_data);
            
                var button_data = document.createElement('button');
                button_data.id = i;
                button_data.onclick = function(){deleteBtn(this);}
                button_data.innerHTML = '削除';
                var input_area = document.getElementById(input_data.id);
                parent.appendChild(button_data);
              i++;
                initAutocomplete()
            }
            function deleteBtn(target) {
                geocoder = new google.maps.Geocoder();
                var target_id = target.id;
                var parent = document.getElementById('form_area');
                var ipt_id = document.getElementById('place_' + target_id);
                var tgt_id = document.getElementById(target_id);
                parent.removeChild(ipt_id);
                parent.removeChild(tgt_id);	
            }
            
            function initAutocomplete() {
              map = new google.maps.Map(document.getElementById("maps"), {
                center: { lat: 35.68125, lng: 139.76637 },
                zoom: 13,
                mapTypeId: "roadmap",
              });
              // Create the search box and link it to the UI element.
 
              for(var j = 1; j<=i; j++) {
                
                const input = document.getElementById('place_' + j);
                const searchBox  = new google.maps.places.SearchBox(input);

              // Bias the SearchBox results towards current map's viewport.
              map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
                
              });
              
              let markers = [];
              
              // Listen for the event fired when the user selects a prediction and retrieve
              // more details for that place.
              searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                
                if (places.length == 0) {
                  return;
                }

                // Clear out the old markers.
                markers.forEach((marker) => {
                  marker.setMap(null);
                });
                markers = [];
                
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                
                places.forEach((place) => {
                  if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                  }
            
                  const icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25),
                  };
            
                  // Create a marker for each place.
                  markers.push(
                    new google.maps.Marker({
                      map,
                      icon,
                      title: place.name,
                      position: place.geometry.location,
                    }),
                  );
                  if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                  } else {
                    bounds.extend(place.geometry.location);
                  }
                  
                  lat = place.geometry.location.lat();
                  lng = place.geometry.location.lng();
                  place = [lat, lng];

                  
                  points.push(new google.maps.LatLng(lat,lng));

                });
                map.fitBounds(bounds);

              });
            } 
            
          }
          
          function calcRoute() {
 
            var origin = null;
            var dest = null;
            
            for (var k = 0, len = points.length; k < len; k++) {
              // 最初の場合、originに値をセット
              if (origin == null) {
                  origin = points[k];
              }
              else if (k == len - 1) {
                  dest = points[k];
              }else{
                console.log(points[k]);
                wayPoints.push({ location: points[k], stopover: true });
              }
            }
            
          	// DirectionsService生成
          	var directionsService = new google.maps.DirectionsService();
          
          	// DirectionｓRenderer生成
          	var directionsRenderer = new google.maps.DirectionsRenderer();
          	directionsRenderer.setPanel(document.getElementById('route-panel'));
          	directionsRenderer.setMap(map);
          
          	// ルート検索実行
          	directionsService.route({
          		origin: origin,  // 出発地
          		destination: dest, // 到着地
          		avoidHighways: true, // 高速は利用しない
          		travelMode: google.maps.TravelMode.DRIVING, // 車モード
          		optimizeWaypoints: true, // 最適化を有効
          		waypoints: wayPoints, // 経由地
          	}, function(response, status) {
          		console.log(response);
          		if (status === google.maps.DirectionsStatus.OK) {
          			directionsRenderer.setDirections(response);
          			var legs = response.routes[0].legs;
          			
          		} else {
          			alert('Directions 失敗(' + status + ')');
          		}
          	});
          	wayPoints = [];
          	points = [];
          };	
          
            window.initAutocomplete = initAutocomplete;
        </script>
    </body>
</html>