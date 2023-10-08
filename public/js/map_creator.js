function showForm(formId) {
	var form = document.getElementById(formId);
	form.style.display = "block";
}

function hideForm(formId) {
	var form = document.getElementById(formId);
	form.style.display = "none";
}

function addMarker(mark) {
    // Tworzymy marker
    var newMarker = L.marker([mark.latitude, mark.longitude]).addTo(map);
    newMarker.bindPopup("<b>" + mark.name + "</b><br>" + mark.address);

    addMarkerDiv(mark);
}
function editMarker(mark) {

    var existingMarker = L.marker([mark.latitude, mark.longitude]);

    existingMarker.addTo(map)
        .bindPopup("<b>" + mark.name + "</b><br>" + mark.address);
}

// Przykład użycia funkcji editMarker do dodawania lub aktualizacji markera
var markerData = {
    latitude: 51.12345, // Szerokość geograficzna
    longitude: 4.56789, // Długość geograficzna
    name: "Nazwa markera",
    address: "Adres markera"
};


// ------------------- przesyłanie formularza ---------------------

$(document).ready(function() {
    $('#tripForm').submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var trip_id = $('#trip_id').val();
        var name = $('#name').val();
        var desc = $('#desc').val();
        var address = $('#address').val();
        var latitude = $('#latitude').val();
        var longitude = $('#longitude').val();
        var token = $('input[name="_token"]').val();

        if (name === '' || desc === '' || address === '' || latitude === '' || longitude === '') {
            alert('Wszystkie pola są wymagane.');
            return;
        }

        var formData = {
            trip_id: trip_id,
            name: name,
            desc: desc,
            address: address,
            latitude: latitude,
            longitude: longitude,
            _token: token
        };

        // $.ajax({
        //     url: form.attr('action'),
        //     type: "POST",
        //     data: formData,
        //     success: function(response) {
        //         console.log(response);
        //         // addMarker('address','button1-form');
        //         alert('Dane zapisane pomyślnie.');
        //         form.trigger('reset');
        //     },
        //     error: function(xhr, status, error) {
        //         console.log(xhr.responseText);
        //     }
        // });

        axios.post(form.attr('action'), formData)
            .then(function(response) {
                console.log(response.data);
                alert('Dane zapisane pomyślnie.');
                form.trigger('reset');
            })
            .catch(function(error) {
                console.error(error);
            });



    });
});








// -------------------------------------------------------------

var suggestInput = document.getElementById('address');
var suggestionsContainer = document.getElementById('suggestions');
var geocoder = L.Control.Geocoder.nominatim();

var delay = 400;
var timerId = null;

suggestInput.addEventListener('input', function() {
    clearTimeout(timerId);

    timerId = setTimeout(function() {
        var query = suggestInput.value.toLowerCase();

        if (query.length > 2) {
            geocoder.geocode(query, function(results) {
                suggestionsContainer.innerHTML = '';

                if (results.length > 0) {
                    results.forEach(function(result) {
                        var suggestion = document.createElement('div');
                        suggestion.classList.add('suggestion');
                        suggestion.textContent = result.name;

                        suggestion.addEventListener('click', function() {
                            var addressParts = [];

                            if (result.properties && result.properties.address) {
                                var address = result.properties.address;

                                if (address.country) {
                                    addressParts.push(address.country);
                                }
                                if (address.city) {
                                    addressParts.push(address.city);
                                }
                                if (address.town) {
                                    addressParts.push(address.town);
                                }
                                if (address.village) {
                                    addressParts.push(address.village);
                                }
                                if (address.road && address.house_number) {
                                    addressParts.push(address.road + ' ' + address.house_number);
                                } else if (address.road) {
                                    addressParts.push(address.road);
                                }
                            }
                            suggestInput.value = addressParts.join(', ');
                            suggestionsContainer.innerHTML = '';

                            if (result.center) {
                                document.getElementById('latitude').value = result.center.lat;
                                document.getElementById('longitude').value = result.center.lng;
                            }
                        });

                        suggestionsContainer.appendChild(suggestion);
                    });
                }
            });
        } else {
            suggestionsContainer.innerHTML = '';
        }
    }, delay);
});






document.addEventListener("DOMContentLoaded", function() {
    var locationInput = document.getElementById("address");
    var suggestionsDiv = document.getElementById("suggestions");

    locationInput.addEventListener("input", function() {
        if (locationInput.value.trim() === "") {
            suggestionsDiv.parentNode.style.display = "none";
        } else {
            suggestionsDiv.parentNode.style.display = "block";
        }
    });
});
