<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>ルート作成</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style>
        #maps{
            height: 400px;
        }
        </style>
    </head>
    <body>
        <a>出発地点</a></br>
        <textarea placeholder="出発地点を入力して下さい"></textarea></br>
        
        <a>経由地点</a></br>
        <div id="form_area">
            <input type="text" id="inputform_0" placeholder="経由地を入力してください">
            <button id="0" onclick="deleteBtn(this)">削除</button>
        </div>
        <input type="button" value="経由地追加" onclick="addForm()"></br>
        
        <a>目的地</a></br>
        <textarea placeholder="目的地を入力してください"></textarea>
        
        <input type="button" value="ルート作成" onclick="">
        
        <div id="maps"></div>
        <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDh09VL82gIovb7lT94whL6Io0yJxxy9oQ&callback=initMap" async></script>        
        
        <script>
        var i = 1;
            function addForm() {
                var input_data = document.createElement('input');
                input_data.type = 'text';
                input_data.id = 'inputform_' + i;
                input_data.placeholder = '経由地を入力してください';
                var parent = document.getElementById('form_area');
                parent.appendChild(input_data);
            
                var button_data = document.createElement('button');
                button_data.id = i;
                button_data.onclick = function(){deleteBtn(this);}
                button_data.innerHTML = '削除';
                var input_area = document.getElementById(input_data.id);
                parent.appendChild(button_data);
            
              i++ ;
            }
            function deleteBtn(target) {
                geocoder = new google.maps.Geocoder();
                var target_id = target.id;
                var parent = document.getElementById('form_area');
                var ipt_id = document.getElementById('inputform_' + target_id);
                var tgt_id = document.getElementById(target_id);
                parent.removeChild(ipt_id);
                parent.removeChild(tgt_id);	
            }
            
            function initMap() {
                
                var mapPosition = {lat: 35.68125, lng: 139.76637};
                var mapArea = document.getElementById('maps');
                var mapOptions = {
                    center: mapPosition,
                    zoom: 15,
                };
                var map = new google.maps.Map(mapArea, mapOptions);
            }
            
        </script>
    </body>
    <footer>
        
    </footer>
</html>