var geocoder;
var $map;
var $carreraSeleccionada=null;
var $markers = [];
var directionDisplay;
var directionsService = new google.maps.DirectionsService();
const CARRERA_CURSO_ICON = new google.maps.MarkerImage('/../images/simulacion/cliente-on.png',
    new google.maps.Size(20, 32),
    new google.maps.Point(0,0),
    new google.maps.Point(0, 32));
const CARRERA_SOLICITADA_ICON = new google.maps.MarkerImage('/../images/simulacion/cliente.png',
    new google.maps.Size(20, 32),
    new google.maps.Point(0,0),
    new google.maps.Point(0, 32));
const CARRERA_DEMORA_ICON = new google.maps.MarkerImage('/../images/simulacion/cliente-off.png',
    new google.maps.Size(20, 32),
    new google.maps.Point(0,0),
    new google.maps.Point(0, 32));
const CARRERA_TAXI_ICON = new google.maps.MarkerImage('/../images/simulacion/taxi-map.png',
    new google.maps.Size(20, 18),
    new google.maps.Point(0,0),
    new google.maps.Point(0, 18));
    
var $shape = {
    coord: [1, 1, 1, 20, 18, 20, 18 , 1],
    type: 'poly'
};

//Metodos para Map de Simulacion 
function getUrlCodigo(unCarrera){
    var url;
    if(unCarrera.codigo==='ocasional'){
        url= rootPath + 'codigoOcacional/show?id=' + unCarrera.idcodigo;    
    }else{
        url= rootPath + 'codigo/datos?id=' + unCarrera.idcodigo;    
    }
    return url;
}
function getInformacionCarrera(unCarrera){
    var masInfo='<div id="map-'+unCarrera.idDiv+'" class="info-map">'+unCarrera.cliente + '<br />'+ 
    'Código: <a target="_blank" href="'+getUrlCodigo(unCarrera)+'">'+unCarrera.codigo+'</a><br />'+
    unCarrera.barrio + ' (' + unCarrera.calle1 + ' , ' + unCarrera.calle2 + ')<br />';
    
    if(unCarrera.destino){
        masInfo+='Destino: '+$sectores[unCarrera.destino].nombre+'<br />';
        if(unCarrera.duracion)
            masInfo+='Tiempo: '+secondsToTime(unCarrera.duracion)+'<br />';
    }else{
        masInfo+='Tiempo de  espera: '+unCarrera.tiempo+' minutos<br />';
    }
    if(unCarrera.detalle)
        masInfo+='Detalle: '+unCarrera.detalle+'<br />';
    if(unCarrera.tipo!=CARRERA_SOLICITADA){
        masInfo+='Unidad: '+ unCarrera.unidad;
    }
    masInfo+=' </div>';
    return masInfo;
}
function getInformacionReservacion(unCarrera){
    var masInfo='<div id="map-'+unCarrera.idDiv+'" class="info-map">'+unCarrera.cliente + '<br />'+ 
    'Código: <a target="_blank" href="'+getUrlCodigo(unCarrera)+'">'+unCarrera.codigo+'</a><br />'+
    unCarrera.barrio + ' (' + unCarrera.calle1 + ' , ' + unCarrera.calle2 + ')<br />'+
    'Hora de reservación: '+unCarrera.hora+' <br /></div>';
    return masInfo;
}
function getDireccionCarrera(unCarrera){
    var direccion = unCarrera.calle1 +' '+unCarrera.calle2 +', ' +unCarrera.barrio +", Loja, Ecuador";
    return direccion;  
}
function marcarMapa(unIcono,unCarrera){
    var direccion=getDireccionCarrera(unCarrera);
    var latlng;
    var marker;
    if(unCarrera.latitud && unCarrera.longitud){
        latlng = new google.maps.LatLng(unCarrera.latitud,unCarrera.longitud);
        marker = new google.maps.Marker({
            map: $map,
            icon:unIcono,
            shape:$shape,
            position: latlng,
            region: "EC"                            
        });
        marker.setTitle(direccion);   
        unCarrera.marker=marker;    
        unCarrera = setInfoCarrera(unCarrera);
    }else if(unCarrera.idsector){
        latlng = new google.maps.LatLng($sectores[unCarrera.idsector].lat,$sectores[unCarrera.idsector].lng);
        marker = new google.maps.Marker({
            map: $map,
            icon:unIcono,
            shape:$shape,
            position: latlng,
            region: "EC"                            
        });
        marker.setTitle(direccion);   
        unCarrera.marker=marker;    
        unCarrera = setInfoCarrera(unCarrera);
    }else{
        geocoder.geocode({
            'address': direccion
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var marker = new google.maps.Marker({
                    map: $map,
                    icon:unIcono,
                    shape:$shape,
                    position: results[0].geometry.location,
                    region: "EC"                            
                });
                marker.setTitle(direccion);   
                unCarrera.marker=marker;    
                unCarrera = setInfoCarrera(unCarrera);
            } else {
                alert("Geocode no tuvo éxito por las siguientes razones: " + status);
            }                 
        });
    }
    return unCarrera;
}
function marcarMapaSolicitud(unIcono,unCarrera){
    var direccion=getDireccionCarrera(unCarrera);
    var latlng;
    var marker;
    if(unCarrera.latitud && unCarrera.longitud){
        latlng = new google.maps.LatLng(unCarrera.latitud,unCarrera.longitud);
        marker = new google.maps.Marker({
            map: $map,
            icon:unIcono,
            shape:$shape,
            position: latlng,
            region: "EC",
            animation:google.maps.Animation.BOUNCE
        });
        marker.setTitle(direccion);   
        unCarrera.marker=marker;    
        unCarrera = setInfoCarrera(unCarrera);
    }else if(unCarrera.idsector){
        latlng = new google.maps.LatLng($sectores[unCarrera.idsector].lat,$sectores[unCarrera.idsector].lng);
        marker = new google.maps.Marker({
            map: $map,
            icon:unIcono,
            shape:$shape,
            position: latlng,
            region: "EC",
            animation:google.maps.Animation.BOUNCE
        });
        marker.setTitle(direccion);   
        unCarrera.marker=marker;    
        unCarrera = setInfoCarrera(unCarrera);
    }else{
        geocoder.geocode({
            'address': direccion
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var marker = new google.maps.Marker({
                    map: $map,
                    icon:unIcono,
                    shape:$shape,
                    position: results[0].geometry.location,
                    region: "EC",
                    animation:google.maps.Animation.BOUNCE
                });
                marker.setTitle(direccion);   
                unCarrera.marker=marker;    
                unCarrera = setInfoCarrera(unCarrera);
            } else {
                alert("Geocode no tuvo éxito por las siguientes razones: " + status);
            }                 
        });
    }
    return unCarrera;
}

function marcarMapaReservacion(unIcono,unCarrera){
    var direccion=getDireccionCarrera(unCarrera);
    var latlng;
    var marker;
    if(unCarrera.latitud && unCarrera.longitud){
        latlng = new google.maps.LatLng(unCarrera.latitud,unCarrera.longitud);
        marker = new google.maps.Marker({
            map: $map,
            icon:unIcono,
            shape:$shape,
            position: latlng,
            region: "EC",
            animation:google.maps.Animation.BOUNCE
        });
        marker.setTitle(direccion);   
        unCarrera.marker=marker;    
        unCarrera = setInfoReservacion(unCarrera);
    }else if(unCarrera.idsector){
        latlng = new google.maps.LatLng($sectores[unCarrera.idsector].lat,$sectores[unCarrera.idsector].lng);
        marker = new google.maps.Marker({
            map: $map,
            icon:unIcono,
            shape:$shape,
            position: latlng,
            region: "EC",
            animation:google.maps.Animation.BOUNCE
        });
        marker.setTitle(direccion);   
        unCarrera.marker=marker;    
        unCarrera = setInfoReservacion(unCarrera);
    }else{
        geocoder.geocode({
            'address': direccion
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var marker = new google.maps.Marker({
                    map: $map,
                    icon:unIcono,
                    shape:$shape,
                    position: results[0].geometry.location,
                    region: "EC",
                    animation:google.maps.Animation.BOUNCE
                });
                marker.setTitle(direccion);   
                unCarrera.marker=marker;    
                unCarrera = setInfoReservacion(unCarrera);
            } else {
                alert("Geocode no tuvo éxito por las siguientes razones: " + status);
            }                 
        });
    }
    return unCarrera;
}
function marcarMapaUnidad(unUnidad){
    if(unUnidad.marker){
        unUnidad.marker.setMap(null);
    }
    var latlng = new google.maps.LatLng($sectores[unUnidad.posicion].lat,$sectores[unUnidad.posicion].lng);
    var marker = new google.maps.Marker({
        map: $map,
        icon:CARRERA_TAXI_ICON,
        shape:$shape,
        position: latlng,
        region: "EC"                            
    });
    marker.setTitle('Unidad: '+unUnidad.numero);
    unUnidad.marker=marker;    
    return unUnidad;
}
function marcarMapaRuta(unCarrera,inicio,fin){
    var request = {
        origin:inicio,
        destination:fin,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC
    };
    //    unCarrera.ruta= new google.maps.DirectionsRenderer();
    var directionsService = new google.maps.DirectionsService();
    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            //            unCarrera.ruta.setDirections(result);
            //            alert(result.routes[0].overview_path);
            unCarrera.ruta = new google.maps.Polyline({
                path: result.routes[0].overview_path,
                strokeColor: "#3366ff",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
            //            unCarrera.ruta=ruta;
            unCarrera.ruta.setMap($map);
        }
    });
    return unCarrera;
}
function marcarMapaRutaDestino(unCarrera,inicio,fin){
    var request = {
        origin:inicio,
        destination:fin,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC
    };
    //    unCarrera.ruta= new google.maps.DirectionsRenderer();
    var directionsService = new google.maps.DirectionsService();
    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            //            unCarrera.ruta.setDirections(result);
            //            alert(result.routes[0].overview_path);
            unCarrera.rutaDestino = new google.maps.Polyline({
                path: result.routes[0].overview_path,
                strokeColor: "#669900",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
            unCarrera.duracion=calcularTiempoEstimado(result);
            $('#'+unCarrera.idDiv).html(getInformacionCarreraDiv(unCarrera,'Carrera en Curso:'));
            setBotonesMesageMap(unCarrera,'carrera');
            //            unCarrera.ruta=ruta;
            unCarrera.rutaDestino.setMap($map);
        }
    });
    return unCarrera;
}
function iniciar() {
    $('#content .content').css("padding-bottom","28px");
    $('#footer').css("display", "none");
    $('#menuflotante').css("display", "block");
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-4.007595, -79.208379);
    var myOptions = {
        zoom: 13,
        center: latlng,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    $map = new google.maps.Map(document.getElementById("mapa"),
        myOptions);     
    cargarSectores();
    setTimeout(function(){
        cargarUnidades();
    },1000);
    setTimeout(function(){
        cargarCarreras();
        cargarSolicitudes();
    },2000);
    setTimeout(function(){
        actualizarNumNotif();
        actualizarNumSolicitudes();
    },7000);
    setInterval(function(){
        cargarNuevasSolicitudes();
        cargarCancelacionSolicitudes();
    },3000);
    setInterval(function(){
        verificarDemora();
        verificarDemoraDestino();        
    },5000);
    setInterval(function(){
        cargarReservaciones();
    },30000);
}
function setInfoCarrera(unCarrera) {
    var infowindow = new google.maps.InfoWindow(
    {
        content: getInformacionCarrera(unCarrera),
        size: new google.maps.Size(50,50)
    });
    google.maps.event.addListener(unCarrera.marker, 'click', function() {
        infowindow.open($map,unCarrera.marker);
        $carreraSeleccionada=unCarrera;
    });
    unCarrera.infowindow=infowindow;
    return unCarrera;
}
function setInfoReservacion(unCarrera) {
    var infowindow = new google.maps.InfoWindow(
    {
        content: getInformacionReservacion(unCarrera),
        size: new google.maps.Size(50,50)
    });
    google.maps.event.addListener(unCarrera.marker, 'click', function() {
        infowindow.open($map,unCarrera.marker);
        $carreraSeleccionada=unCarrera;
    });
    unCarrera.infowindow=infowindow;
    return unCarrera;
}
//Metodos para map de codigos
function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-4.007595, -79.208379);
    var myOptions = {
        zoom: 13,
        center: latlng,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    $map = new google.maps.Map(document.getElementById("mapa"),
        myOptions);     
}
function calcRoute() {                
    var start = $markers[0].position;
    var end = $markers[1].position;
    var request = {
        origin:start,
        destination:end,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC
    };
    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);
            computeTotalDistance(directionsDisplay.directions);
                    
        }
    });
    deleteOverlays();
    directionsDisplay.setMap($map);
                
}
function computeTotalDistance(result) {
    var total = 0;
    var myroute = result.routes[0];
    for (var i = 0; i < myroute.legs.length; i++) {
        total += myroute.legs[i].distance.value;
    }
    total = total / 1000.
    document.getElementById("distancia").value = total + " km";
}
function calcularTiempoEstimado(result) {
    var total = 0;
    var myroute = result.routes[0];
    for (var i = 0; i < myroute.legs.length; i++) {
        total += myroute.legs[i].duration.value;
    }
    return total;
}
function codeAddress() {
    var direccion = document.getElementById("direccion").value + ", Loja, Ecuador";

    geocoder.geocode( {
        'address': direccion
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            $map.setCenter(results[0].geometry.location);
            marker = new google.maps.Marker({
                map: $map,
                icon:CARRERA_CURSO_ICON,
                shape:$shape,
                position: results[0].geometry.location,
                region: "EC"
            });
            $markers.push(marker);
        } else {
            alert("Geocode no tuvo éxito por las siguientes razones: " + status);
        }
    });
}          
function setAllMap(unMap) {
    for (var i = 0; i < $markers.length; i++) {
        $markers[i].setMap(unMap);
    }
}            
function clearOverlays() {
    setAllMap(null);

}            
function deleteOverlays() {
    clearOverlays();
    directionsDisplay.setMap(null);
    $markers = [];
    document.getElementById("direccion").value = "";
}
