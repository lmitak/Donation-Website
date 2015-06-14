/*varijable mape*/
var map;
var markersArray;
var autocomplete;
var youMarker;
/*varijable ratinga*/
var zvijezde;
/*varijable sklopke*/
var radiosDa, radiosNe, sklopke;
/*jezični sadržaj*/
var autocomplete_text;
var you_marker_text;
var content_text = [];


document.addEventListener("DOMContentLoaded", function(event) {

    if(document.getElementById('map')){
        jezik_contenta();
        google.maps.event.addDomListener(window, 'load', initialize);
    }

    if(document.getElementById('rating') || document.getElementById('rating2')){
        inicijalizirajRating();
    }
    if(document.getElementById('sklopka')){
        postavkeSklopke();
    }

    if(document.getElementById('autocomplete_places')){
        autocomplete_places();
    }

    if(document.getElementsByClassName("spremiPromjene").length > 0){
        intializeSpremiPromjene();
    }

});


function initialize() {

    var mapOptions = {
        zoom: 16,
        mapTypeId:google.maps.MapTypeId.ROADMAP
        //center: new google.maps.LatLng(-34.397, 150.644)
    };
    map = new google.maps.Map(document.getElementById('map'),
        mapOptions);

    // Create the autocomplete object and associate it with the UI input control.
    // Restrict the search to the default country, and to place type "cities".
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
        {
            /*types: ['(cities)'],*/
            language: "hr"
            /*componentRestrictions: countryRestrict*/
        });
    places = new google.maps.places.PlacesService(map);

    google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);


    // When the user selects a city, get the place details for the city and
    // zoom the map in on the city.
    function onPlaceChanged() {
        var place = autocomplete.getPlace();
        if (place.geometry) {
            map.panTo(place.geometry.location);
            clearMarkers();
            youMarker.setMap(null);
            centerYourself(place.geometry.location);
            obavi_ajax_promise(place.geometry.location.A, place.geometry.location.F);
            filtriraj();
        } else {
            document.getElementById('autocomplete').placeholder = autocomplete_text;
        }

    }

    function clearMarkers() {
        for (var i = 0; i < markersArray.length; i++) {
            if (markersArray[i]) {
                markersArray[i][0].setMap(null);
            }
        }
        markersArray = [];
    }

    function centerYourself(position){
        youMarker = new google.maps.Marker({
            position: position,
            animation: google.maps.Animation.BOUNCE
        });

        var youInfowindow = new google.maps.InfoWindow({
            content: you_marker_text
        });

        youMarker.setMap(map);
        youInfowindow.open(map, youMarker);

        map.setCenter(position);
    }


    function obavi_ajax_promise(positionLatitude, positionLongitude){
        promise_ajax_upit(positionLatitude, positionLongitude).then(function(response){
            console.log("Success!", response);
            /*markersArray = [new Array(response.length), new Array(3)];*/
            markersArray = [];


            for(var i = 0; i < response.length; i++){
                markersArray[i] = [];
                //alert(response[i]['2']);

                markersArray[i][0] = new google.maps.Marker({
                    position: new google.maps.LatLng(response[i]['latitude'], response[i]['longitude']),
                    icon: 'images/small/' + response[i]['ikona'],
                    map: map
                });


                markersArray[i][1] = response[i]['idTipa'];
                markersArray[i][2] = response[i]['kolPopusta'] == 100;

                console.log(markersArray);
                var content = "<h2>" + response[i][2] +  "</h2>";
                content += "<div style='width:200px;'>";
                content += "<p>" + content_text['adresa'] + ": " + response[i][4] + "</p>";
                content += "<p>" +content_text['popust'] + ": " + response[i]['kolPopusta'] + "</p>";
                content += "<p>" + content_text['dt'] + ": " + response[i]['vrijemePopusta'] + "</p>";
                if(response[i]['dostupno'] == 0){
                    content += "<p>" + content_text['dosuptnost'] + ": " + content_text['ne'] + "<p>";
                }else{
                    content += "<p>" + content_text['dosuptnost'] + ": " + content_text['da'] +"<p>";
                }
                content += "<a href='info=" + response[i]['id'] + "'>" + content_text['about'] + "</a>";
                content += "</div>";

                attachSecretMessage(markersArray[i][0], content);

            }
        }, function(error){
            console.error("Failed!", error);
        });
    }


    var geoOptions = {
        timeout: 10 * 100
    };
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = new google.maps.LatLng(position.coords.latitude,
                position.coords.longitude);
            centerYourself(pos);

            obavi_ajax_promise(position.coords.latitude, position.coords.longitude);
        }, function() {
            handleNoGeolocation(true);
        }, geoOptions);
    } else {
        // Browser doesn't support Geolocation
        handleNoGeolocation(false);
    }
}


function handleNoGeolocation(errorFlag) {
    if (errorFlag) {
        var content = 'Error: The Geolocation service failed.';
    } else {
        var content = 'Error: Your browser doesn\'t support geolocation.';
    }

    var options = {
        map: map,
        position: new google.maps.LatLng(60, 105),
        content: content
    };

    var infowindow = new google.maps.InfoWindow(options);
    map.setCenter(options.position);
}

function attachSecretMessage(marker, content) {
    var infowindow = new google.maps.InfoWindow({
        content: content
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(marker.get('map'), marker);
    });
}


function promise_ajax_upit(latitude, longitude){
    

    //Return a new promise
    return new Promise(function(resolve, reject){
        //Do the usual XHR stuff
        var req = new XMLHttpRequest();
        var url = "ajax/get_locs.php";
        var vars = "lat=" + latitude + "&lng=" + longitude;
        req.open("POST", url, true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        req.onreadystatechange = function(){
            if(req.status == 200/* && req.readyState == 4*/){
                //resolve the promise with the response text
                resolve(JSON.parse(req.responseText));
            }else{
                //otherwise reject with the status text
                //reject(Error(req.statusText));
                reject("Error status: " + Error(req.status) + "readystate: " + Error(req.readyState));
            }

        };

        //Handle network errors
        req.onerror = function(){
            reject(Error("Network error"));
        };

        //make the request
        req.send(vars);

    });
}

function filtriraj(){
    console.log(markersArray);
    var tip_trgovine = document.getElementById("tip");
    var besplatno = document.getElementById("free");
    var tipovi = document.getElementsByTagName("option");
    if(besplatno.checked){
        for(var i = 0; i < markersArray.length; i++){
            if((tip_trgovine.value == markersArray[i][1] || tip_trgovine.value === "sve") && markersArray[i][2] === true){
                markersArray[i][0].setMap(map);
                markersArray[i][0].setAnimation(google.maps.Animation.DROP);

                console.log("nije " + markersArray[i][1] + " nego je " + tip_trgovine.value);
            }else{
                markersArray[i][0].setMap(null);
            }

        }

    }else{
        console.log("besplatno nije klinkuto, a value je:" + tip_trgovine.value);

        for(var i = 0; i < markersArray.length; i++){
            if(tip_trgovine.value == markersArray[i][1] || tip_trgovine.value === "sve"){
                markersArray[i][0].setMap(map);
                markersArray[i][0].setAnimation(google.maps.Animation.DROP);

                console.log("nije " + markersArray[i][1] + " nego je " + tip_trgovine.value);
            }else{
                markersArray[i][0].setMap(null);
            }
        }
    }
}

//funkcije ratinha\\


function inicijalizirajRating(){
    zvijezde = [];
    var boja;
    for(var i = 1; i < 6; i++){
        zvijezde[i] = document.getElementById(i);
    }
    console.log(zvijezde);

    if(document.getElementById('rating') != null){
        boja = "#29C2FF";
        var ratingLista = document.getElementById('rating');
        for(var i = 1; i < 6; i++){
            osvijetljenje(zvijezde[i], i);
        }
    }else{
        var ratingLista = document.getElementById('rating2');
        boja = "rgba(255, 201, 84, 1)";
    }

    var rating = ratingLista.getAttribute('value');
    console.log(rating);
    pocetniRating(boja);


    function osvijetljenje(zvijezda, i){

        zvijezda.onmouseover = function(){
            console.log("i =" + i);
            for(var j = 1; j <= i; j++){
                zvijezde[j].style.color = "rgba(255, 201, 84, 1)";
            }

        };

        zvijezda.onmouseout = function(){
            pocetniRating("#29C2FF");
        };

        zvijezda.onclick = function(){
            rating_ajax(i);
        };
    }

    function pocetniRating(boja){
        for(var i = 1; i < zvijezde.length; i++){
            if(i <= rating){
                zvijezde[i].style.color = boja;
            }else{
                zvijezde[i].style.color = "rgb(90, 90, 90)";
            }
        }
    }

    function rating_ajax(star_nmbr){
        var nazivTvrtke = document.getElementById('naziv');
        var adresaTvrtke = document.getElementById('adresa');
        var naziv = nazivTvrtke.innerHTML.split(": ");
        var adresa = adresaTvrtke.innerHTML.split(": ");

        var hr = new XMLHttpRequest();
        // Create some variables we need to send to our PHP file
        var url = "ajax/rate.php";

        var vars = "stars=" + star_nmbr + "&nazivTvrtke=" + naziv[1] + "&adresaTvrtke=" + adresa[1];

        hr.open("POST", url, true);
        // Set content type header information for sending url encoded variables in the request
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Access the onreadystatechange event for the XMLHttpRequest object
        hr.onreadystatechange = function() {
            if(hr.readyState == 4 && hr.status == 200)
            {
                console.log(hr.responseText);
                if(hr.responseText == "success"){
                    lock_stars(star_nmbr);
                }else{
                    alert("Ispričavamo se, došlo je do pogreške, msg=" + hr.responseText);
                }
            }
        };
        // Send the data to PHP now... and wait for response to update the status div
        hr.send(vars);
        // Actually execute the request
    }

    function lock_stars(star_nmbr){
        for(var i = 1; i <= zvijezde.length; i++){
            if(star_nmbr >= i){
                zvijezde[i].style.color = "rgba(255, 201, 84, 1)";
            }else{
                zvijezde[i].style.color = "rgba(90, 90, 90, 1)";
            }
            disable_mouse(zvijezde[i]);
        }
    }

    function disable_mouse(zvijezda){
        zvijezda.onmouseover = false;
        zvijezda.onclick = false;
        zvijezda.onmouseout = false;
    }
}



//dodavanje komentara u bazu i prikazivanje na stranici
function addComent(){
    var komentar = document.getElementById("comment");
    var nazivTvrtke = document.getElementById('naziv');
    var adresaTvrtke = document.getElementById('adresa');
    var naziv = nazivTvrtke.innerHTML.split(": ");
    var adresa = adresaTvrtke.innerHTML.split(": ");

    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "ajax/comment.php";
    var vars = "comment=" + komentar.value + "&nazivTvrtke=" + naziv[1] + "&adresaTvrtke=" + adresa[1];

    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function() {
        if(hr.readyState == 4 && hr.status == 200)
        {
            console.log(hr.responseText);

            if(hr.responseText == "success"){
                var comment_place = document.getElementById('newComment');
                comment_place.innerHTML = komentar.value;
            }else{
                alert("Ispričavamo se, došlo je do pogreške, msg=" + hr.responseText);
            }
        }
    };
    hr.send(vars);
}

function postavkeSklopke(){
    radiosDa = document.getElementsByClassName("radioDa");
    radiosNe = document.getElementsByClassName("radioNe");
    sklopke = document.getElementsByClassName("sklopke");
    for(var i = 0; i < radiosDa.length; i++){
        if(radiosDa[i].checked) {
            radiosDa[i].previousElementSibling.style.color = "white";

        }else{
            radiosNe[i].previousElementSibling.style.color = "white";
            sklopke[i].style.transform = "translateX(50px)";
        }
        promjeniStanjeSklopke(radiosDa[i], radiosNe[i], sklopke[i], i)
    }


    function promjeniStanjeSklopke(radioDa, radioNe, sklopka, i){

        radioDa.onclick = function(){
            radiosDa[i].previousElementSibling.style.color = "white";
            radiosNe[i].previousElementSibling.style.color = "rgba(15,15,15,0.6)";
            sklopke[i].style.transform = "translateX(0px)";
            radiosDa[i].checked = true;
        }

        radioNe.onclick = function(){
            radiosNe[i].previousElementSibling.style.color = "white";
            radiosDa[i].previousElementSibling.style.color = "rgba(15,15,15,0.6)";
            sklopke[i].style.transform = "translateX(50px)";
            radiosNe[i].checked = true;
        }

    }

}
//funkcije za autocomplete adrese bez mape
function autocomplete_places(){
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(document.getElementById('autocomplete_places')),
        {
            types: ['address'],
            language: "hr"
            /*componentRestrictions: countryRestrict*/
        });
    geolocate();
    google.maps.event.addListener(autocomplete, 'place_changed', function(){
        giveLatLng();

        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.

    });
}

function giveLatLng(){
    var longitude = document.getElementById('longitude');
    var latitude = document.getElementById('latitude');

    var place = autocomplete.getPlace();
    if (place.geometry) {
        longitude.value = place.geometry.location.F;
        latitude.value = place.geometry.location.A;
    }

}

function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = new google.maps.LatLng(
                position.coords.latitude, position.coords.longitude);
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

function intializeSpremiPromjene(){
    var gumbi = document.getElementsByClassName('spremiPromjene');


    for(var i = 0; i < gumbi.length; i++){
        srediKlik(gumbi[i], i);
    }

    function srediKlik(gumb, i){
        gumb.onclick = function(){
            spremiPromjene(i, gumb.getAttribute('id'));
        };
    }
}


//za brzo spremanje korisnikovih izmjena(oko popusta)
function spremiPromjene(i, id){
    var kolPopusta = document.getElementsByClassName('kolPopusta');
    var vrijemePopusta = document.getElementsByClassName('vrijemePopusta');
    var dostupnost;
    var kolPoustaVrijednost = kolPopusta[i].value;

    var vrijemePopustaVrijednost = vrijemePopusta[i].value;
    if(radiosDa[i].checked){
        dostupnost = 1;
    }else{
        dostupnost = 0;
    }

    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "ajax/promjene_tvrtke.php";
    var vars = "kolPopusta=" + kolPoustaVrijednost + "&vrijemePopusta=" + vrijemePopustaVrijednost + "&dostupnost=" + dostupnost + "&idTvrtke=" + id;

    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function() {
        if(hr.readyState == 4 && hr.status == 200)
        {
            console.log(hr.responseText);

            if(hr.responseText == "success"){
                alert("Uspješna izmjena")
            }else{
                alert("Ispričavamo se, došlo je do pogreške: " + hr.responseText);
            }
        }
    };
    hr.send(vars);
}

function izbrisiTvrtku(id){
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "ajax/brisanje_tvrtke.php";
    var vars = "idTvrtke=" + id;

    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function() {

    };
    hr.send(vars);

}

function jezik_contenta(){
    if(document.getElementById("Hrv")){
        autocomplete_text = 'Upiši adresu';
        you_marker_text = 'Ti si tu';
        content_text['adresa'] = "Adresa";
        content_text['popust'] = 'Popust';
        content_text['dt'] = 'Vrijeme popusta';
        content_text['dosuptnost'] = 'Dosuptno';
        content_text['da'] = "da";
        content_text['ne'] = "ne";
        content_text['about'] = "Informacije o tvrtki";
    }else if(document.getElementById("Cro")){
        autocomplete_text = 'Type in the address';
        you_marker_text = 'You are here';
        content_text['adresa'] = "Address";
        content_text['popust'] = 'Discount';
        content_text['dt'] = 'Discount time';
        content_text['dosuptnost'] = 'Availability';
        content_text['da'] = "yes";
        content_text['ne'] = "no";
        content_text['about'] = "About company";
    }
}

