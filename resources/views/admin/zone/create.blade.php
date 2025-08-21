
@extends('admin.layouts.app')

@section('title', 'Main page')

@section('content')

@php


$value=web_map_settings();
@endphp
@if($value=="google")

<style>
    #map {
    height: 400px;
    width: 80%;
    left: 10px;
    }
    html, body {
    padding: 0;
    margin: 0;
    height: 100%;
    }

    #panel {
    width: 200px;
    font-family: Arial, sans-serif;
    font-size: 13px;
    float: right;
    margin: 10px;
    margin-top: 100px;
    }

    #delete-button, #add-button, #delete-all-button, #save-button {
    margin-top: 5px;
    }
    #search-box {
    background-color: #f7f7f7;
    font-size: 15px;
    font-weight: 300;
    margin-top: 10px;
    margin-bottom: 10px;
    margin-left: 10px;
    padding: 0 11px 0 13px;
    text-overflow: ellipsis;
    height: 25px;
    width: 80%;
    border: 1px solid #c7c7c7;
    }
    .map_icons{
    font-size: 24px;
    color: white;
    padding: 10px;
    background-color: #43439999;
    margin: 5px;
    }
    .autocomplete-container {
      position: relative; /* Needed for absolute positioning of the results */
      font-family: sans-serif;
    }

    #autocomplete-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }

    .autocomplete-results {
      position: absolute; /* Position the results below the input */
      top: 100%; /* Place it just below the input */
      left: 0;
      width: 100%;
      background-color: #fff;
      border: 1px solid #ccc;
      border-top: none; /* Remove the top border to make it look connected */
      border-radius: 0 0 4px 4px; /* Round only the bottom corners */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
      z-index: 10; /* Ensure it's above other content */
      max-height: 200px; /* Limit the height of the dropdown */
      overflow-y: auto; /* Add a scrollbar if there are too many suggestions */
    }

    .autocomplete-results div {
      padding: 10px;
      cursor: pointer; /* Change the cursor to indicate it's clickable */
    }

    .autocomplete-results div:hover {
      background-color: #f0f0f0; /* Highlight on hover */
    }

    .autocomplete-results div + div {
      border-top: 1px solid #eee; /* Add a separator between suggestions */
    }
    </style>

    <div class="content">
    <div class="container-fluid">
    <div class="row">
    <div class="col-sm-12">
    <div class="box">

    <div class="box-header with-border">
    <a href="{{ url('zone') }}">
    <button class="btn btn-danger btn-sm pull-right" type="submit">
    <i class="mdi mdi-keyboard-backspace mr-2"></i>
    @lang('view_pages.back')
    </button>
    </a>
    </div>
    <div class="col-sm-12">
        <form  method="post" class="form-horizontal" action="{{url('zone/store')}}" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" id="info" name="coordinates" value="">

    <input type="hidden" id="city_polygon" name="city_polygon" value="{{ old('city_polygon') }}">

    <div class="row">
    <div class="col-sm-6">
    <div class="form-group">
    <label for="zone_admin" class="">@lang('view_pages.select_area') <sup>*</sup></label>
    <select name="admin_id" id="zone_admin" class="form-control" required>
    <option value="" >@lang('view_pages.select_area')</option>
    @foreach($services as $key=>$service)
    <option value="{{$service->id}}">{{$service->name}}</option>
    @endforeach
    </select>
    </div>
    </div>

    <div class="col-sm-6">
    @if(!auth()->user()->company_key)
    <!-- <div class="row">
    <div class="col-sm-9">
    <div class="form-group">
    <label for="city" class="">@lang('view_pages.select_city')</label>
    <select name="city" id="city" class="form-control select2" data-placeholder="@lang('view_pages.select_city')" >
    <option value="" >@lang('view_pages.select_city')</option>
    @foreach($cities as $key=>$city)
    <option value="{{$city}}">{{$city}}</option>
    @endforeach
    </select>
    </div>
    </div>
    <div class="col-sm-3" style="padding-top: 30px">
        <button class="btn btn-success btn-sm searchCity" type="button"><i class="fa fa-search" style="font-size: 20px;"></i></button>
    </div>
    </div> -->
    @endif

    </div>

    <div class="col-sm-6">
    <div class="form-group">
    <label> @lang('view_pages.name') <sup>*</sup></label>
    <input class="form-control" id="zone_name" type="text" name="zone_name" value="{{ old('zone_name') }}" placeholder="@lang('view_pages.enter_name')" required>
    <span class="text-danger">{{ $errors->first('zone_name') }}</span>
    </div>
    </div>

    <div class="col-sm-6">
    <div class="form-group">
    <label for="zone_admin" class="">@lang('view_pages.select_unit') <sup>*</sup></label>
    <select name="unit" id="unit" class="form-control" required>
    <option value="" selected disabled>@lang('view_pages.select_unit')</option>
    <option value="1">@lang('view_pages.kilo_meter')</option>
    <option value="2">@lang('view_pages.miles')</option>
    </select>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-12">
        <div class="autocomplete-container">

            <input id="autocomplete-input" class="form-control controls" onInput="handleInput()" type="text" placeholder="@lang('view_pages.search')" />
            <div id="autocomplete-results" class="autocomplete-results"></div>
        </div>

    <div id="map" class="col-sm-10" style="float:left;"></div>

    <div id="" class="col-sm-2" style="float:right;">
    <ul style="list-style: none;">
    <li>
    <a id="select-button" href="javascript:void(0)" onclick="drawingManager.setDrawingMode(null)" class="btn-floating zone-add-btn btn-large waves-effect waves-light tooltipped" >
    <i class="fa fa-hand-pointer-o map_icons"></i>
    </a>
    </li>

    <li>
    <a id="add-button" href="javascript:void(0)" onclick="drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON)" class="btn-floating zone-add-btn btn-large waves-effect waves-light tooltipped" >
    <i class="fa fa-plus-circle map_icons"></i>
    </a>
    </li>

    <li>
    <a id="delete-button" href="javascript:void(0)" onclick="deleteSelectedShape()" class="btn-floating zone-delete-btn btn-large waves-effect waves-light tooltipped" >
    <i class="fa fa-times map_icons"></i>
    </a>
    </li>
    <li>
    <a id="delete-all-button" href="javascript:void(0)" onclick="clearMap()" class="btn-floating zone-delete-all-btn btn-large waves-effect waves-light tooltipped" >
    <i class="fa fa-trash-o map_icons"></i>
    </a>
    </li>

    </ul>
    </div>

    </div>
    </div>
    <div class="form-group text-right m-b-0"><br>
    <button id="save-button" class="btn btn-primary btn-sm m-5 pull-right" type="submit">
    @lang('view_pages.save')
    </button>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <script src="https://maps.google.com/maps/api/js?key={{get_settings('google_map_key')}}&libraries=drawing,geometry,places"></script>

    <script src="{{asset('assets/js/polygon/main.js')}}"></script>
    <script src="{{asset('assets/js/polygon/nucleu.js')}}"></script>

<script>
    function fetchAutocomplete() {

        const search = $('#autocomplete-input').val();
        if(search.length <= 3) {
            return;
        }
        const apiUrl = `https://places.googleapis.com/v1/places:autocomplete`;
        const headers = {
            "Content-Type": "application/json",
            "X-Goog-Api-Key": "{{get_settings('google_map_key')}}",
            "X-Goog-FieldMask": "suggestions.placePrediction.placeId,suggestions.placePrediction.place,suggestions.placePrediction.text",
        };
        const requestData = {
            input: search,
        };


        $.ajax({
            url: apiUrl,
            type:  'POST',
            headers: headers,
            data: JSON.stringify(requestData),
            success: function(result)
            {
                var HTML = '';
                result.suggestions
                    .filter(
                        (suggestion) => suggestion.placePrediction
                    )
                    .map((suggestion) => ({
                        placeId: suggestion.placePrediction.placeId,
                        formattedAddress: suggestion.placePrediction.text.text,
                    })).forEach((item)=> {

                        HTML+= `<div id=${item.placeId} class="autocomplete-item" onClick="selectSuggestion(this)">${item.formattedAddress}</div>`;
                    });
                $('#autocomplete-results').html(HTML);
            },
            error: function(error)
            {
                console.error(error);
                $('#autocomplete-results').html('');
            }
        });
    }
    function selectSuggestion(element) {
        const placeId = $(element).attr('id');

        const headers = {
            "X-Goog-Api-Key": "{{get_settings('google_map_key')}}",
            "X-Goog-FieldMask": "viewport,location",
        };

        $('#autocomplete-input').val('')
        $('#autocomplete-results').html('');
        $.ajax({
            url:  `https://places.googleapis.com/v1/places/${placeId}?fields=viewport,location`,
            headers: headers,
            success: function(data)
            {

                const position = new google.maps.LatLng(data.location.latitude, data.location.longitude );
                
                map.setCenter(position);

                if (data.viewport && data.viewport.high && data.viewport.low) {
                    const bounds = new google.maps.LatLngBounds(
                        new google.maps.LatLng(data.viewport.low.latitude, data.viewport.low.longitude),
                        new google.maps.LatLng(data.viewport.high.latitude, data.viewport.high.longitude),
                    );
                    
                    map.fitBounds(bounds);
                }else{
                    map.setZoom(15);
                }
            },
            error: function(error)
            {
                console.error(error);
            }
        });

    }
    var typing = false;
    function handleInput(){
        if (!typing) {
            setTimeout(function() {
                fetchAutocomplete();
                typing = false;
            }, 1000);
        }
        typing = true;
    }
</script>
    <script type="text/javascript">

        $(document).ready(function() {
            var keyword = $('#city').val();

            if(keyword) getCoordsByKeyword(keyword);
        });

        $(document).on('change','#city',function(){
            var val = $(this).val();
            getCoordsByKeyword(val);
        });

        $(document).on('click','.searchCity',function(){
            var val = $('#city option:selected').val();
            if(val) getCoordsByKeyword(val);
        });

        $(document).on('keyup','.select2-search__field',function(){
            var val = $(this).val();

            if(val != '' && val.length > 2){
                $.ajax({
                    url: '{{ route("getCityBySearch") }}',
                    data: {search:val},
                    method: 'get',
                    success: function(results){
                        if(results.length > 0 ){
                            $('#city').html('');

                            results.forEach(city => {
                                $('#city').append('<option value="'+city[0]+'">'+city[0]+'</option>');
                            });
                        }
                    }
                });
            }
        });

        function getCoordsByKeyword(keyword){
            // $('#loader').css('display','block');
            // $('#map').css('display','none');

            $.ajax({
                url: "{{ url('zone/coords/by_keyword') }}/"+keyword,
                data: '',
                method: 'get',
                success: function(results){
                    if(results){
                        $('#city_polygon').val(results);

                        // setTimeout(function(){
                            // $('#loader').css('display','none');
                            // $('#map').css('display','block');
                        // }, 1000);
                        window.onload = initMap()
                    }
                }
            });
        }
    </script>
@elseif($value=="open_street")
<style>
    #map {
        height: 400px;
        width: 80%;
        left: 10px;
    }

    html, body {
        padding: 0;
        margin: 0;
        height: 100%;
    }

    #panel {
        width: 200px;
        font-family: Arial, sans-serif;
        font-size: 13px;
        float: right;
        margin: 10px;
        margin-top: 100px;
    }

    #delete-button, #add-button, #delete-all-button, #save-button {
        margin-top: 5px;
    }

    #search-box {
        background-color: #f7f7f7;
        font-size: 15px;
        font-weight: 300;
        margin-top: 10px;
        margin-bottom: 10px;
        margin-left: 10px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        height: 25px;
        width: 80%;
        border: 1px solid #c7c7c7;
    }

    .map_icons {
        font-size: 24px;
        color: white;
        padding: 10px;
        background-color: #43439999;
        margin: 5px;
    }
</style>


<div class="box-header with-border">
    <a href="{{ url('zone') }}">
        <button class="btn btn-danger btn-sm pull-right" type="submit">
            <i class="mdi mdi-keyboard-backspace mr-2"></i>
            @lang('view_pages.back')
        </button>
    </a>
</div>

<div class="col-sm-12">
    <form method="post" class="form-horizontal" action="{{url('zone/store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" id="coordinates" name="coordinates" value="">

        <input type="hidden" id="city_polygon" name="city_polygon" value="{{ old('city_polygon') }}">

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="zone_admin" class="">@lang('view_pages.select_area') <sup>*</sup></label>
                    <select name="admin_id" id="zone_admin" class="form-control" required>
                        <option value="">@lang('view_pages.select_area')</option>
                        @foreach($services as $key=>$service)
                            <option value="{{$service->id}}">{{$service->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Other form fields -->

            <div class="col-sm-6">
                <div class="form-group">
                    <label> @lang('view_pages.name') <sup>*</sup></label>
                    <input class="form-control" id="zone_name" type="text" name="zone_name" value="{{ old('zone_name') }}" placeholder="@lang('view_pages.enter_name')" required>
                    <span class="text-danger">{{ $errors->first('zone_name') }}</span>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                <label for="zone_admin" class="">@lang('view_pages.select_unit') <sup>*</sup></label>
                <select name="unit" id="unit" class="form-control" required>
                <option value="" selected disabled>@lang('view_pages.select_unit')</option>
                <option value="1">@lang('view_pages.kilo_meter')</option>
                <option value="2">@lang('view_pages.miles')</option>
                </select>
                </div>
                </div>

        </div>
        <div class="row">
            <div id="map" class="col-sm-10" style="float:left;"></div>
        </div>

        <div class="form-group text-right m-b-0"><br>
            <button id="save-button" class="btn btn-primary btn-sm m-5 pull-right" type="submit">
                @lang('view_pages.save')
            </button>
        </div>
        <button id="draw-polygon-btn" style="display:none;">Draw Polygon</button>
    </form>
</div>


<!-- Include Leaflet and Leaflet Draw JavaScript libraries -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script type="text/javascript">
    var map = L.map('map').setView([0, 0], 2);
    var zoneErr = document.getElementById('zoneErr');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Initialize Leaflet Draw plugin
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);
    var drawControl = new L.Control.Draw({
        draw: {
            polygon: true,
            polyline: false,
            circle: false,
            marker: false,
            circlemarker: false
        },
        edit: {
            featureGroup: drawnItems,
            edit: true,
            remove: true
        }
    });
    map.addControl(drawControl);

    map.on('draw:created', function (e) {
        var layer = e.layer;
        overlaps = false;
        var overlaps = is_overlap(layer);

        if (!overlaps) {
            drawnItems.addLayer(layer);
            updateCoordinates();
            zoneErr.innerHTML =  '';
        } else {
            zoneErr.innerHTML =  'Zone overlaps with existing zone.';
        }
    });
    function is_overlap(layer) {
        var overlaps = false;

        var layerGeoJson = layer.toGeoJSON();


        drawnItems.eachLayer(function (existingLayer) {
            if (layer.getBounds().intersects(existingLayer.getBounds())) {
                overlaps = true;
                return false; // Stop iterating if overlap found
            }
        });

        return overlaps;
    }

    map.on('draw:edited', function (e) {
        var overlaps = false;

        e.layers.eachLayer(function (layer) {
          if (is_overlap(layer)) {
            overlaps = is_overlap(layer);
          }
        });
        if (!overlaps) {
            updateCoordinates();
            zoneErr.innerHTML = '';
        } else {
            zoneErr.innerHTML = 'Zone overlaps with existing zone.';
        }
    });


    // Get user's location and update the map view
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            map.setView([lat, lng], 8); // Set the map view to user's location
        }, function (error) {
            console.error('Error getting user location:', error);
            alert('Error getting your location. Please allow location access.');
        });
    } else {
        alert('Geolocation is not supported by your browser.');
    }

      map.on('draw:deleted', function (e) {
          updateCoordinates(); // Call to update coordinates when polygons are removed
      });

    // Function to update coordinates input field
    function updateCoordinates() {
        var coordinates = [];
        var all_coordinates = [];
        drawnItems.eachLayer(function (layer) {
            var latLngs = layer.getLatLngs();
            coordinates = coordinates.concat(latLngs.flat());
        });
        all_coordinates = [coordinates];
        var coordinatesInput = document.getElementById('coordinates');
        coordinatesInput.value = JSON.stringify(all_coordinates);
    }
    updateCoordinates();
</script>


@endif



@endsection
