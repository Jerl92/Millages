function ajax_get_map($)  {
  $('#geolocation_place_in_post').on('click', function() {
    navigator.geolocation.getCurrentPosition(showPosition);     
  });
}

function ajax_get_geoloc($)  {
    $.ajax({    
        type: 'post',
        url: get_map_ajax_url,
        data: {
            'object_id': getUrlParameter('postid'),
            'action': 'get_map'
        },
        dataType: 'JSON',
        success: function(data){
          console.log(data);
          initMap(data); 
        },
        error: function(errorThrown){
            //error stuff here.text
        }
    });
}

function showPosition(position) {
  var pos = {
    lat: position.coords.latitude,
    lng: position.coords.longitude
  };
  var s = document.getElementById('geoloc');
  s.value = pos.lat + ', ' + pos.lng;
  console.log(pos);
}


function initMap(data) {
    var n = data.length - 1;
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 4,
    center: {lat: -24.345, lng: 134.46}  // Australia.
  });

  var geocoder = new google.maps.Geocoder;
  var directionsService = new google.maps.DirectionsService;
  var directionsRenderer = new google.maps.DirectionsRenderer({
    draggable: false,
    map: map
  });

  directionsRenderer.addListener('directions_changed', function() {
    computeTotalDistance(directionsRenderer.getDirections());
  });

  geocodeLatLng(geocoder, map);

  total_calc();

  var places = [];
  i = 0;
  data.forEach(element => {
    places.push({
        location: element,
        stopover: true
      });
    i++;
  });

  places.splice(0, 1);
  places.splice(n, 1);

  displayRoute(data[0], data[n], places, directionsService,
      directionsRenderer);

}

function displayRoute(origin, destination, waypoints, service, display) {
  console.log(waypoints);
  service.route({
    origin: origin,
    destination: destination,
    waypoints: waypoints,
    travelMode: 'DRIVING',
    avoidTolls: true
  }, function(response, status) {
    if (status === 'OK') {
      display.setDirections(response);
    } else {
      alert('Could not display directions due to: ' + status);
    }
  });
}

function computeTotalDistance(result) {
  var Dist = 0;
  var totalDist = 0;
  var totalTime = 0;
  var myroute = result.routes[0];

  for (var i = 0; i < myroute.legs.length; i++) {
    totalDist += myroute.legs[i].distance.value;
    totalTime += myroute.legs[i].duration.value; 
  }
  totalDist = totalDist / 1000;
  document.getElementById('total-by-google').innerHTML = parseFloat(totalDist).toFixed(0);
  document.getElementById('time-total-by-google').innerHTML = timeConvert((totalTime / 60).toFixed(2));

  var kmgoogle = document.getElementsByClassName('km-by-google');  
  var timegoogle = document.getElementsByClassName('time-by-google');
  kmgoogle[0].innerHTML = 0;
  timegoogle[0].innerHTML = '00:00';
  for (var i = 0; i < myroute.legs.length; i++) {
    Dist = (myroute.legs[i].distance.value / 1000);
    time = myroute.legs[i].duration.value;
    kmgoogle[i+1].innerHTML = parseFloat(Dist).toFixed(0);
    timegoogle[i+1].innerHTML = timeConvert((time.toFixed(2) / 60));
  }

}

function geocodeLatLng(geocoder) {
  var input = document.getElementsByClassName('latlng');
  var geolocs = [];
  var adresses = [];

  for (var i = 0; i < input.length; i++) {
    geolocs.push({
      0: input[i].innerHTML.split(',', 2)
    });
  }

  var i = 0;
  geolocs.forEach(function(element) { 
    var latlngStr = element[0];
    var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
    var table_cell = document.getElementById('google-address-'+i);

    console.log(adresses);

    if (table_cell.innerHTML == "") {
      geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
          if (results[0]) {
            adresse_cell = results[0].formatted_address;
            adresses.push(
              results[0].formatted_address
            );
          } else {
            adresse_cell = 'No results found';
          }
        } else {
          adresse_cell = 'Geocoder failed due to: ' + status;
        }
        table_cell.innerHTML = adresse_cell;
      });
    } else {
      adresses.push(
        table_cell.innerHTML
      );
    }
    i++;
  },  this);
  return adresses;
}

function timeStringToFloat(time) {
  var hoursMinutes = time.split(/[.:]/);
  var hours = parseInt(hoursMinutes[0], 10);
  var minutes = hoursMinutes[1] ? parseInt(hoursMinutes[1], 10) : 0;
  return hours + minutes / 60;
}

function timeConvert(n) {
  var num = n;
  var hours = (num / 60);
  var rhours = Math.floor(hours);
  var minutes = (hours - rhours) * 60;
  var rminutes = Math.round(minutes);
  return rhours + ":" + rminutes;
  }

function total_calc() {
  var arrivaltime = document.getElementsByClassName('traveled-time');
  var departuretime = document.getElementsByClassName('worked-time');
  var totaltime = 0;
  var calarrivaltime = 0;
  var caldeparturetime = 0;

  for (var i = 0; i < arrivaltime.length; i++) {
    calarrivaltime = calarrivaltime + timeStringToFloat(arrivaltime[i].innerHTML);
    console.log(calarrivaltime);
  }
  totaltime = calarrivaltime
  document.getElementById('total-traveled').innerHTML = timeConvert(calarrivaltime.toFixed(2) * 60);

  for (var i = 0; i < departuretime.length; i++) {
    caldeparturetime = caldeparturetime + timeStringToFloat(departuretime[i].innerHTML);
    console.log(caldeparturetime);
  }
  totaltime = totaltime + caldeparturetime
  document.getElementById('total-worked').innerHTML = timeConvert(caldeparturetime.toFixed(2) * 60);

  document.getElementById('total-time').innerHTML = timeConvert(totaltime.toFixed(2) * 60);
}

jQuery(document).ready(function($) {
  ajax_get_map($);  
  ajax_get_geoloc($);  
});