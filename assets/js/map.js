function initMap()
{
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: -33.8688,
            lng: 151.2195
        },
        zoom: 13
    });
    //Geolocation start
    var infoWindow = new google.maps.InfoWindow;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            //
            // infoWindow.setPosition(pos);
            // infoWindow.setContent('Jūsų esame vieta.');
            // infoWindow.open(map);
            map.setCenter(pos);
        }, function () {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
    //Geolocation end

    var input = document.getElementById('job_address');

    var autocomplete = new google.maps.places.Autocomplete(input);

    // Bind the map's bounds (viewport) property to the autocomplete object,
    // so that the autocomplete requests use the current map bounds for the
    // bounds option in the request.
    autocomplete.bindTo('bounds', map);

    // Set the data fields to return when the user selects a place.
    autocomplete.setFields(
        ['address_components', 'geometry', 'icon', 'name']
    );

    var infowindow = new google.maps.InfoWindow();
    var infowindowContent = document.getElementById('infowindow-content');
    infowindow.setContent(infowindowContent);
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function () {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17); // Why 17? Because it looks good.
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = [];
        if (place.address_components) {
            for (i = 0; i < place.address_components.length - 1; i++) {    //-1 to avoid getting location POST code
                address[i] = (place.address_components[i] && place.address_components[i].long_name || '');
            }
            address = address.join(' ');
        }

        infowindowContent.children['place-icon'].src = place.icon;
        infowindowContent.children['place-name'].textContent = place.name;
        infowindowContent.children['place-address'].textContent = address;
        infowindow.open(map, marker);

        var placeinfo = {
            coords: {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            },
            addresses: {
                fromObject: address,
                fromInput: document.getElementById('job_address').value
            }
        };

        //send to PHP start
        // const xhr = new XMLHttpRequest();
        // xhr.onload = function () {
        //     const serverResponse = document.getElementById("response");
        //     serverResponse.innerHTML = this.responseText;
        // };
        // xhr.open("POST", "../job");
        // xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // xhr.send("lat=" + placeinfo.coords.lat + "&lng=" + placeinfo.coords.lng + "&address=" + placeinfo.addresses.fromInput);
        //send to PHP end
        // Outputting start
        document.getElementById("coordinates").innerHTML = "";

        var node = document.createElement("li")
        var textnode = document.createTextNode("Address from object: " + placeinfo.addresses.fromObject);
        node.appendChild(textnode);
        document.getElementById("coordinates").appendChild(node);
        console.log("Address from object: " + placeinfo.addresses.fromObject);

        for (i in placeinfo.coords) {
            console.log(i + ": " + placeinfo.coords[i]);
            var node = document.createElement("li");
            switch (i) {
                case "lat":
                    var coordName = "Latitude";
                    break;
                case "lng":
                    var coordName = "Longitude";
                    break;
            }
            var textnode = document.createTextNode(coordName + ": " + placeinfo.coords[i]);
            node.appendChild(textnode);
            document.getElementById("coordinates").appendChild(node);
        }

        var node = document.createElement("li")
        var textnode = document.createTextNode("Address from input: " + placeinfo.addresses.fromInput);
        node.appendChild(textnode);
        document.getElementById("coordinates").appendChild(node);
        console.log("Address from input: " + placeinfo.addresses.fromInput);
        //Outputting end
        console.log(placeinfo);

    });
    // uncomment below to show only addresses
    // autocomplete.setTypes(['address']);
}

function handleLocationError(browserHasGeolocation, infoWindow, pos)
{
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.'
    );
    infoWindow.open(map);
}
