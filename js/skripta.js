var map;
var markersArray;
function initialize() {
    var mapOptions = {
        zoom: 16,
        mapTypeId:google.maps.MapTypeId.ROADMAP
        //center: new google.maps.LatLng(-34.397, 150.644)
    };
    map = new google.maps.Map(document.getElementById('map'),
        mapOptions);

    var geoOptions = {
        timeout: 10 * 100
    }
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = new google.maps.LatLng(position.coords.latitude,
                position.coords.longitude);



            var youMarker = new google.maps.Marker({
                position: pos,
                animation: google.maps.Animation.BOUNCE
            })

            var youInfowindow = new google.maps.InfoWindow({
                content: 'Ti si tu'
            });

            youMarker.setMap(map);
            youInfowindow.open(map, youMarker);

            map.setCenter(pos);


            //ajax_upit(position.coords.latitude, position.coords.longitude);
            //alert(lokacije);
            promise_ajax_upit(position.coords.latitude, position.coords.longitude).then(function(response){
                console.log("Success!", response);
                markersArray = [new Array(response.length), new Array(3)];
                var markArray = [];
                var windows = [];
                for(var i = 0; i < response.length; i++){

/*
                    markArray[0] = new google.maps.Marker({
                        position: new google.maps.LatLng(response[i]['latitude'], response[i]['longitude']),
                        icon: 'images/small/' + response[i]['ikona'],
                        map: map
                    });

                    markArray[1] = response[i]['12'];
                    markArray[2] = response[i]['kolPopusta'] == 100;
                    markersArray[i] = markArray;*/
                    markersArray[i][0] = new google.maps.Marker({
                        position: new google.maps.LatLng(response[i]['latitude'], response[i]['longitude']),
                        icon: 'images/small/' + response[i]['ikona'],
                        map: map
                    });
                    //markersArray[i][1] = response[i]['12'];
                    markersArray[i][1] = response[i]['idTipa'];
                    markersArray[i][2] = markArray[2] = response[i]['kolPopusta'] == 100;
                    //alert(markersArray);
                    //alert("i je :" + i + ", markArray je: " + markArray + "\n, markersArray je : " + markersArray);
                    var content = "<h2>" + response[i][2] +  "</h2>";
                    content += "<div style='width:200px;'>";
                    content += "<p>Adresa: " + response[i][4] +"</p>";
                    content += "<p>Popust: " + response[i]['kolPopusta'] +"</p>";
                    content += "<p>Trajanje akcije: " + response[i]['vrijemePopusta'] +"</p>";
                    content += "<a href='Info'>Informacije o tvrtki</a>"
                    content += "</div>";


                    attachSecretMessage(markersArray[i][0], content);

                }
            }, function(error){
                console.error("Failed!", error);
            });

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


/*
document.addEventListener("DOMContentLoaded", function(event) {

});
*/
/*
function ajax_upit(latitude, longitude){

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        hr = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        hr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "ajax/get_locs.php";

    var vars = "lat="+latitude+"&lng="+longitude;
    hr.open("POST", url, true);
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    hr.onreadystatechange = function() {
        if (hr.readyState == 4 && hr.status == 200) {

            //console.log(xmlhttp.responeTest);
            var popis=JSON.parse(hr.responseText);
            if (popis.ERROR)
                if (popis.ERROR.length > 0) {
                    alert(popis.ERROR);
                    return;
                }

            if (popis.length > 0) {
                console.log(popis);
                lokacije = popis;
            }


        }
    }
    //hr.open("GET","ajax_part.php?upit="+filter.value,true);
    hr.send(vars);
}
*/
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
    console.log(tipovi.length);
    if(besplatno.checked){

        /*switch (tip_trgovine.value){
            case "pekarna":
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Pekarna" || markersArray[i][2] != true){
                        markersArray[i][0].setMap(null);
                    }else{
                        markersArray[i][0].setMap(map);
                    }
                }
                break;
            case "mesnica":
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Mesnica" || markersArray[i][2] != true){
                        markersArray[i][0].setMap(null);
                    }else{
                        markersArray[i][0].setMap(map);
                    }
                }
                break;
            case "vocarna":
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Voćarna" || markersArray[i][2] != true){
                        markersArray[i][0].setMap(null);
                    }else{
                        markersArray[i][0].setMap(map);
                    }
                }
                break;
            case "trgovina":
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Trgovina" || markersArray[i][2] != true){
                        markersArray[i][0].setMap(null);
                    }else{
                        markersArray[i][0].setMap(map);
                    }
                }
                break;
            default:
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][2] != true){
                        markersArray[i][0].setMap(null);
                    }else{
                        markersArray[i][0].setMap(map);
                    }
                }
                break;
        }*/
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
        /*switch (tip_trgovine.value){
            case "pekara":
                //console.log("pekare");
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Pekara"){
                        markersArray[i][0].setMap(null);
                        console.log("nije pekara nego je " + markersArray[i][1]);
                    }else{
                        markersArray[i][0].setMap(map);
                        console.log("je pekara");
                    }
                }
                break;
            case "mesnica":
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Mesnica"){
                        markersArray[i][0].setMap(null);
                    }else{
                        markersArray[i][0].setMap(map);
                    }
                }
                break;
            case "vocarna":
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Voćarna"){
                        markersArray[i][0].setMap(null);
                        console.log("nije vocarna nego je " + markersArray[i][1]);
                    }else{
                        markersArray[i][0].setMap(map);
                        console.log("je vocarna");
                    }
                }
                break;
            case "trgovina":
                for(var i = 0; i < markersArray.length; i++){
                    if(markersArray[i][1] != "Trgovina"){
                        markersArray[i][0].setMap(null);
                    }else{
                        markersArray[i][0].setMap(map);
                    }
                }
                break;
            default:
                for(var i = 0; i < markersArray.length; i++){
                    markersArray[i][0].setMap(map);
                }
                break;
        }*/
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

google.maps.event.addDomListener(window, 'load', initialize);