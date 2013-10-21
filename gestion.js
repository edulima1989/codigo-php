var $carreras;
var $solicitudes=null;
var $reservaciones=[];
var $unidades;
var $unidadSeleccionada=null;
var $unidadManejo=null;
var $codigo=null;
const CARRERA_CURSO = 10 ;
const CARRERA_SOLICITADA = 11 ;
const CARRERA_ESPERA = 12 ;
//No cambiar valores porque son usados en PHP
const SOLICITUD_NUEVA = 1;
const SOLICITUD_CANCELADA = 2;
const SOLICITUD_APROBADA = 3;
const SOLICITUD_RECHAZADA = 4;
var $sectores=[];
var $isMenuO = false;  
var $isMenuU = false;  
var $isMenuN = false;  
var $isMenuS = false;  
var $isMenuA = false;
var $isMenuR = false;
var $contAlertas=0;
//$(document).ready(function(){
$('#menu-reservaciones').click(function(){
    $('#panel-unidades').css("display", "none");
    $('#panel-notificaciones').css("display", "none");
    $('#panel-solicitudes').css("display", "none");
    $('#panel-alertas').css("display", "none");
    $('#panel-opciones').css("display", "none");
    $isMenuU = false;  
    $isMenuN = false;  
    $isMenuS = false;  
    $isMenuA = false;     
    $isMenuO = false;  
    if($isMenuR){
        $('#panel-reservaciones').css("display", "none");
        $isMenuR=false;
    }else{
        $('#panel-reservaciones').css("display", "block");
        $('#content .content').css("padding-bottom","28px");
        //            scrollTo(0,window.scrollMaxY);
        $isMenuR=true;
        $('html, body').animate({
            scrollTop: $(document).height()
        },
        2000);
    }
});
$('#menu-opciones').click(function(){
    $('#panel-unidades').css("display", "none");
    $('#panel-notificaciones').css("display", "none");
    $('#panel-solicitudes').css("display", "none");
    $('#panel-alertas').css("display", "none");
    $('#panel-reservaciones').css("display", "none");
    $isMenuU = false;  
    $isMenuN = false;  
    $isMenuS = false;  
    $isMenuA = false;     
    $isMenuR = false;  
    if($isMenuO){
        $('#panel-opciones').css("display", "none");
        $isMenuO=false;
    }else{
        $('#panel-opciones').css("display", "block");
        $('#content .content').css("padding-bottom","28px");
        //            scrollTo(0,window.scrollMaxY);
        $isMenuO=true;
        $('html, body').animate({
            scrollTop: $(document).height()
        },
        2000);
    }
});
$('#menu-unidades').click(function(){
    $('#panel-opciones').css("display", "none");
    $('#panel-notificaciones').css("display", "none");
    $('#panel-solicitudes').css("display", "none");
    $('#panel-alertas').css("display", "none");
    $('#panel-reservaciones').css("display", "none");
    $isMenuO = false;  
    $isMenuN = false;  
    $isMenuS = false;  
    $isMenuA = false;  
    $isMenuR = false;  
    if($isMenuU){
        $('#panel-unidades').css("display", "none");
        $isMenuU=false;
    }else{
        $('#panel-unidades').css("display", "block");
        $('#content .content').css("padding-bottom","28px");
        //            scrollTo(0,window.scrollMaxY);
        $isMenuU=true;
        $('html, body').animate({
            scrollTop: $(document).height()
        },
        2000);
    }
});
$('#menu-notificaciones').click(function(){
    $('#panel-opciones').css("display", "none");
    $('#panel-unidades').css("display", "none");
    $('#panel-solicitudes').css("display", "none");
    $('#panel-alertas').css("display", "none");
    $('#panel-reservaciones').css("display", "none");
    $isMenuO = false;  
    $isMenuU = false;  
    $isMenuS = false;  
    $isMenuA = false;  
    $isMenuR = false;  
    if($isMenuN){
        $('#panel-notificaciones').css("display", "none");
        $isMenuN=false;
    }else{
        $('#panel-notificaciones').css("display", "block");
        $('#content .content').css("padding-bottom","28px");
        //            scrollTo(0,window.scrollMaxY);
        $isMenuN=true;
        $('html, body').animate({
            scrollTop: $(document).height()
        },
        2000);
    }
});
$('#menu-solicitudes').click(function(){
    $('#panel-opciones').css("display", "none");
    $('#panel-unidades').css("display", "none");
    $('#panel-notificaciones').css("display", "none");
    $('#panel-alertas').css("display", "none");
    $('#panel-reservaciones').css("display", "none");
    $isMenuO = false;  
    $isMenuU = false;  
    $isMenuN = false;  
    $isMenuA = false;  
    $isMenuR = false;  
    if($isMenuS){
        $('#panel-solicitudes').css("display", "none");
        $isMenuS=false;
    }else{
        $('#panel-solicitudes').css("display", "block");
        $('#content .content').css("padding-bottom","28px");
        //            scrollTo(0,window.scrollMaxY);
        $isMenuS=true;
        $('html, body').animate({
            scrollTop: $(document).height()
        },
        2000);
    }
});
$('#menu-alertas').click(function(){
    $('#panel-opciones').css("display", "none");
    $('#panel-unidades').css("display", "none");
    $('#panel-notificaciones').css("display", "none");
    $('#panel-solicitudes').css("display", "none");
    $('#panel-reservaciones').css("display", "none");
    $isMenuO = false;  
    $isMenuU = false;  
    $isMenuN = false;  
    $isMenuS = false;  
    $isMenuR = false;  
    if($isMenuA){
        $('#panel-alertas').css("display", "none");
        $isMenuA=false;
    }else{
        $('#panel-alertas').css("display", "block");
        $('#content .content').css("padding-bottom","28px");
        //            scrollTo(0,window.scrollMaxY);
        $isMenuA=true;
        $('html, body').animate({
            scrollTop: $(document).height()
        },
        2000);  
    }
});
$('#btn-ubicar-unidad').click(function(){
    ubicarUnidad();
});
$('#sectores-loja').change(function(){
    var index=$(this).val();
    centrarMapa($sectores[index]);
});
    
$('#btn-manejo-unidades').click(function(){
    setUnidadesManejo('panel-manejo-taxis');
    $('#modal-manejo-unidad').reveal({
        animation: 'fadeAndPop',                   //fade, fadeAndPop, none
        animationspeed: 300,                       //how fast animtions are
        closeonbackgroundclick: false              //if you click background will modal close?
    //            dismissmodalclass: 'taxi'    //the class of a button or element that will close an open modal
    });

});
  
$('#codigo-codigo').keypress(function(event){
    if(checkKeyEnter(event)){
        cargarCodigo(this.value,'codigo');
    }
});
$('#codigo-unidad').change(function(){
    setDatosUnidad($(this).val(),'codigo');
});
$('#codigo-unidad').focus(function(){
    setDatosUnidad($(this).val(),'codigo');
});
    
$('#codigo-crear').click(function(){
    crearCarreraCodigo();
});
$('#ingreso-crear').click(function(){
    crearCarreraIngreso();
});
$('#ingreso-unidad').change(function(){
    setDatosUnidad($(this).val(),'ingreso');
});
$('#ingreso-unidad').focus(function(){
    setDatosUnidad($(this).val(),'ingreso');
});
$('#btn-ingresar-carrera-codigo').click(function(){
    setUnidadesSelect('codigo-unidad');
    $('#modal-ingresar-carrera-codigo').reveal();

}); 
$('#btn-ingresar-carrera').click(function(){
    setUnidadesSelect('ingreso-unidad');
    $('#modal-ingresar-carrera').reveal();

}); 
$('#btn-asignar-unidad').click(function(){
    setUnidadesBotones('panel-taxis');
    $('#modal-asignar-unidad').reveal({
        animation: 'fadeAndPop',                   //fade, fadeAndPop, none
        animationspeed: 300,                       //how fast animtions are
        closeonbackgroundclick: true,              //if you click background will modal close?
        dismissmodalclass: 'btn-taxi'    //the class of a button or element that will close an open modal
    });

}); 
$('input#btn-destino-unidad').click(function(){
    atencionCarrera();
});
    
//});

//METODOS PARA GESTION TE TODAS LAS CARRERAS
function getInformacionCarreraDiv(unCarrera,titulo){
    var masInfo='<h2>'+titulo+'</h2><p>'+unCarrera.cliente + '<br />'
    +'Código: <a target="_blank" href="'+getUrlCodigo(unCarrera)+'">'+unCarrera.codigo+'</a><br />'+
    unCarrera.barrio + ' (' + unCarrera.calle1 + ' , ' + unCarrera.calle2 + ')<br />';
    if(unCarrera.destino){
        masInfo+='Destino: '+$sectores[unCarrera.destino].nombre+'<br />';
        if(unCarrera.duracion)
            masInfo+='Tiempo de llegada: '+secondsToTime(unCarrera.duracion)+'<br />';
    }else{
        masInfo+='Tiempo de  espera: '+unCarrera.tiempo+' minutos<br />';
    }
    if(unCarrera.tipo!=CARRERA_SOLICITADA){
        masInfo+='Unidad: '+ unCarrera.unidad;
    }
    masInfo+='</p>';
    return masInfo;
}
function getInformacionReservacionDiv(unCarrera,titulo){
    var masInfo='<h2>'+titulo+'</h2><p>'+unCarrera.cliente + '<br />'
    +'Código: <a target="_blank" href="'+getUrlCodigo(unCarrera)+'">'+unCarrera.codigo+'</a><br />'+
    unCarrera.barrio + ' (' + unCarrera.calle1 + ' , ' + unCarrera.calle2 + ')<br />'+
    'Hora de reservación: '+unCarrera.hora+' <br />'
    masInfo+='</p>';
    return masInfo;
}
function cargarCarreras(){
    $.ajax({
        url:rootPath+"carrera/cargarCarreras",
        type: "POST",
        data: "",
        dataType: 'json',
        cache: false,
        success: function(datos){
            $carreras=eval(datos);
            for(var i in $carreras){
                $carreras[i].aviso=false;
                $carreras[i].tipo=CARRERA_CURSO;
                $carreras[i].idDiv='c'+$carreras[i].id;
                $carreras[i]=marcarMapa(CARRERA_CURSO_ICON, $carreras[i]);
                $('<div></div>',{
                    id:$carreras[i].idDiv
                }).addClass('notif-carrera').html(getInformacionCarreraDiv($carreras[i],'Carrera en Curso:')).prependTo('div#panel-notificaciones');
                setEventoClickDiv($carreras[i]);
                setBotonesMesageMap($carreras[i],'carrera');
            }     
            setTimeout(function(){
                for(var i in $carreras){
                    if($unidades[$carreras[i].unidad].posicion){
                        $carreras[i]=marcarMapaRuta($carreras[i],new google.maps.LatLng($sectores[$unidades[$carreras[i].unidad].posicion].lat,$sectores[$unidades[$carreras[i].unidad].posicion].lng),$carreras[i].marker.getPosition());
                    }
                    if($carreras[i].destino){
                        $carreras[i]=marcarMapaRutaDestino($carreras[i],$carreras[i].marker.getPosition(), new google.maps.LatLng($sectores[$carreras[i].destino].lat,$sectores[$carreras[i].destino].lng));
                    }
                }
            }, 1000);
        }
    });
}
function cargarNuevasSolicitudes(){
    $.ajax({
        url:rootPath+"carrera/cargarNuevasSolicitudes",
        type: "POST",
        data: "",
        dataType: 'json',
        cache: false,
        success: function(datos){
            var solicitudes = eval(datos);
            for(var i in solicitudes){
                var noEsta = true;
                for(var j in $solicitudes){
                    if($solicitudes[j].id===solicitudes[i].id){
                        noEsta = false;
                        break;
                    }
                }
                if(noEsta){                    
                    solicitudes[i].tipo=CARRERA_SOLICITADA;
                    solicitudes[i].idDiv='s'+solicitudes[i].id;
                    solicitudes[i]=marcarMapaSolicitud(CARRERA_SOLICITADA_ICON, solicitudes[i]);
                    $().toastmessage('showToast', {
                        text     : 'Solicitud de carrera: '+getInformacionCarrera(solicitudes[i]),
                        sticky   : false,
                        position : 'top-left',
                        type     : 'warning',
                        //                        solicitud   : solicitudes[i],
                        //                        accion: 'solicitud',
                        close    : function () {
                            console.log("toast is closed ...");
                        }
                    });  
                    $('<div></div>',{
                        id:solicitudes[i].idDiv
                    }).addClass('notif-solicitud').html(getInformacionCarreraDiv(solicitudes[i],'Solicitud de Carrera:')).prependTo('div#panel-solicitudes');      
                    setEventoClickDiv(solicitudes[i]);
                    setBotonesMesageMap(solicitudes[i],'solicitud');
                    $solicitudes.push(solicitudes[i]);
                    $.ajax({
                        url: rootPath + 'carrera/actualizarNotificacionSolicitud',
                        data: {
                            notificacion: '0',
                            id:solicitudes[i].id
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (dato){                
                        },
                        error: function () {
                            alert('Ha ocurrido un error al acceder al servidor');
                        }
                    });   
                    actualizarNumSolicitudes();
                }
            }
        }
    });
}
function cargarReservaciones(){
    $.ajax({
        url:rootPath+"carrera/cargarReservaciones",
        type: "POST",
        data: "",
        dataType: 'json',
        cache: false,
        success: function(datos){
            var reservaciones = eval(datos);     
            for(var i in reservaciones){
                var noEsta = true;
                for(var j in $reservaciones){
                    if($reservaciones[j].id===reservaciones[i].id){
                        noEsta = false;
                        break;
                    }
                }
                if(noEsta){                    
                    reservaciones[i].tipo=CARRERA_SOLICITADA;
                    reservaciones[i].idDiv='r'+reservaciones[i].id;
                    reservaciones[i]=marcarMapaReservacion(CARRERA_SOLICITADA_ICON, reservaciones[i]);
                    $().toastmessage('showToast', {
                        text     : 'Reservacion de carrera: '+getInformacionReservacion(reservaciones[i]),
                        sticky   : false,
                        position : 'top-left',
                        type     : 'warning',
                        close    : function () {
                            console.log("toast is closed ...");
                        }
                    });  
                    $('<div></div>',{
                        id:reservaciones[i].idDiv
                    }).addClass('notif-solicitud').html(getInformacionReservacionDiv(reservaciones[i],'Reservación de Carrera:')).prependTo('div#panel-reservaciones');
                    setEventoClickDiv(reservaciones[i]);
                    setBotonesMesageMap(reservaciones[i],'reservacion');
                    $reservaciones.push(reservaciones[i]);
                    //                    $.ajax({
                    //                        url: rootPath + 'carrera/actualizarNotificacionSolicitud',
                    //                        data: {
                    //                            notificacion: '0',
                    //                            id:solicitudes[i].id
                    //                        },
                    //                        type: 'POST',
                    //                        dataType: 'json',
                    //                        success: function (dato){                
                    //                        },
                    //                        error: function () {
                    //                            alert('Ha ocurrido un error al acceder al servidor');
                    //                        }
                    //                    });   
                    actualizarNumReservaciones();
                }
            }
        }, 
        error: function(){
            alert("Error al intentar acceder al servidor");
        }
    });
}
function cancelarReservacion(){
    var index=$reservaciones.indexOf($carreraSeleccionada);
    if(index!=-1){
        $reservaciones[index].marker.setMap(null);
        $('#'+$reservaciones[index].idDiv).remove();
        $reservaciones.splice(index,1);
        actualizarNumReservaciones();
    }
}
function cargarCancelacionSolicitudes(){
    $.ajax({
        url:rootPath+"carrera/cargarCancelacionSolicitudes",
        type: "POST",
        data: "",
        dataType: 'json',
        cache: false,
        success: function(datos){
            var solicitudes = eval(datos);
            for(var i in solicitudes){
                var esta = false;
                for(var j in $solicitudes){
                    if($solicitudes[j].id===solicitudes[i].id){
                        esta = true;
                        break;
                    }
                }
                if(esta){                    
                    $solicitudes[j].marker.setMap(null);
                    $().toastmessage('showToast', {
                        text     : 'Solicitud cancelada: '+getInformacionCarrera($solicitudes[j]),
                        sticky   : true,
                        position : 'top-left',
                        type     : 'success',
                        close    : function () {
                            console.log("toast is closed ...");
                        }
                    });  
                    $solicitudes.splice(j,1);
                    $.ajax({
                        url: rootPath + 'carrera/actualizarNotificacionSolicitud',
                        data: {
                            notificacion: '0',
                            id:solicitudes[i].id
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (dato){                
                        },
                        error: function () {
                            alert('Ha ocurrido un error al acceder al servidor');
                        }
                    });   
                    actualizarNumSolicitudes();
                }
                 
            }
        }
    });
}
function cargarSolicitudes(){
    $.ajax({
        url:rootPath+"carrera/cargarSolicitudes",
        type: "POST",
        data: "",
        dataType: 'json',
        cache: false,
        success: function(datos){
            $solicitudes=eval(datos);
            for(var i in $solicitudes){
                $solicitudes[i].tipo=CARRERA_SOLICITADA;
                $solicitudes[i].idDiv='s'+$solicitudes[i].id;
                $solicitudes[i]=marcarMapaSolicitud(CARRERA_SOLICITADA_ICON, $solicitudes[i]);
                $('<div></div>',{
                    id:$solicitudes[i].idDiv
                }).addClass('notif-solicitud').html(getInformacionCarreraDiv($solicitudes[i],'Solicitud de Carrera:')).prependTo('div#panel-solicitudes');
                setEventoClickDiv($solicitudes[i]);
                setBotonesMesageMap($solicitudes[i],'solicitud');
            }
        }
    });
}
function cargarUnidades(){
    $.ajax({
        url:rootPath+"carrera/cargarUnidades",
        type: "POST",
        data: "",
        dataType: 'json',
        cache: false,
        success: function(datos){
            $unidades=eval(datos);
            for(var i in $unidades){
                if($unidades[i].posicion){
                    $unidades[i]=marcarMapaUnidad($unidades[i]);
                }
            }
        }
    });
}
function cargarSectores(){
    $.ajax({
        url:rootPath+"carrera/cargarSectores",
        type: "POST",
        data: "",
        dataType: 'json',
        cache: false,
        success: function(datos){
            $sectores=eval(datos);
        }
    });
}
function atencionCarrera(){
    var index=$carreras.indexOf($carreraSeleccionada);
    var sector=$('#sectores-destino-unidad').val();
    if(sector)
        if(index!=-1){
            var atencion =calTiempo($carreraSeleccionada.hora);
            $carreras[index].horaDestino=hora();
            $.ajax({
                url: rootPath + 'carrera/asignarAtencionCarrera',
                data: {
                    atencion: atencion,
                    idcarrera:$carreraSeleccionada.id,
                    sector:sector
                //                    hora:$carreras[index].horaDestino
                },
                type: 'POST',
                dataType: 'json',
                success: function (dato){
                    $carreras[index].destino=sector;
                    $carreras[index].atencion=atencion;
                    $carreras[index].marker.setIcon(CARRERA_CURSO_ICON);
                    $carreras[index]=marcarMapaRutaDestino($carreras[index],$carreras[index].marker.getPosition(), new google.maps.LatLng($sectores[sector].lat,$sectores[sector].lng));
                    setTimeout(function(){
                        $.ajax({
                            url: rootPath + 'carrera/asignarTiempoAproDestino',
                            data: {
                                tiempo: deSegundosAMinutos($carreras[index].duracion),
                                idcarrera:$carreras[index].id
                            },
                            type: 'POST',
                            dataType: 'json',
                            success: function (dato){
                            },
                            error: function () {
                                alert('Ha ocurrido un error al guardar el tiempo aproximado de llegada.');
                            }
                        });
                    },3000);
                    $carreraSeleccionada=$carreras[index];
                    $('#'+$carreraSeleccionada.idDiv).remove();
                    if($carreraSeleccionada.idDivA){
                        $('#'+$carreraSeleccionada.idDivA).remove();
                        $contAlertas--;
                        actualizarNumAlertas();
                    }
                    $('<div></div>',{
                        id:$carreraSeleccionada.idDiv
                    }).addClass('notif-carrera').html(getInformacionCarreraDiv($carreraSeleccionada,'Carrera en Curso:')).prependTo('div#panel-notificaciones');
                    setEventoClickDiv($carreraSeleccionada);
                    setBotonesMesageMap($carreraSeleccionada,'carrera');
                    $carreraSeleccionada=null;
                },
                error: function () {
                    alert('Ha ocurrido un error al asignar la unidad');
                }
            });
        }else{
            alert('No se ha seleccionado ninguna carrera');
        }
    else
        alert('Para completar el proceso debe seleccionar el sector de destino.');
}
function ingresarDestinoUnidad(){
    var index=$carreras.indexOf($carreraSeleccionada);
    if(index!=-1){
        $('#modal-destino-unidad').reveal({
            animation: 'fadeAndPop',                   //fade, fadeAndPop, none
            animationspeed: 300,                       //how fast animtions are
            closeonbackgroundclick: true,              //if you click background will modal close?
            dismissmodalclass: 'btn-destino-unidad'    //the class of a button or element that will close an open modal
        });
    }else{
        alert('No se ha seleccionado ninguna solicitud');
    }
}
function asignarUnidadCarrera(unidad){
    var index=$solicitudes.indexOf($carreraSeleccionada);
    if(index!=-1){
        var tiempo = $('#asignar-unidad-tiempo').val();
        $.ajax({
            url: rootPath + 'carrera/asignarUnidadCarrera',
            data: {
                unidad: unidad,
                notificacion: SOLICITUD_APROBADA,
                idcarrera:$carreraSeleccionada.id,
                tiempo: tiempo
            },
            type: 'POST',
            dataType: 'json',
            success: function (dato){
                var carrera = dato[0];
                $carreraSeleccionada.id=carrera.id;    
                $carreraSeleccionada.aviso=false;
                $carreraSeleccionada.unidad=carrera.unidad;
                $carreraSeleccionada.tiempo=carrera.tiempo;
                $carreraSeleccionada.atencion=carrera.atencion;
                $carreraSeleccionada.hora=carrera.hora;
                $carreraSeleccionada.tipo=CARRERA_CURSO;
                $carreraSeleccionada.infowindow.setContent(getInformacionCarrera($carreraSeleccionada));
                $carreraSeleccionada.marker.setIcon(CARRERA_CURSO_ICON);
                $carreraSeleccionada.marker.setAnimation(null);
                $('#'+$carreraSeleccionada.idDiv).remove();
                $carreraSeleccionada.idDiv='c'+$carreraSeleccionada.id;
                $('<div></div>',{
                    id:$carreraSeleccionada.idDiv
                }).addClass('notif-carrera').html(getInformacionCarreraDiv($carreraSeleccionada,'Carrera en Curso:')).prependTo('div#panel-notificaciones');
                setEventoClickDiv($carreraSeleccionada);
                setBotonesMesageMap($carreraSeleccionada,'carrera');
                //                setTimeout(function(){
                if($unidades[$carreraSeleccionada.unidad].posicion){
                    $carreraSeleccionada=marcarMapaRuta($carreraSeleccionada,new google.maps.LatLng($sectores[$unidades[$carreraSeleccionada.unidad].posicion].lat,$sectores[$unidades[$carreraSeleccionada.unidad].posicion].lng),$carreraSeleccionada.marker.getPosition());
                } 
                //                },1000);
                $carreras.push($carreraSeleccionada);
                $solicitudes.splice(index,1);
                $.ajax({
                    url: rootPath + 'carrera/enviarSmsAsignarUnidadCarrera',
                    data: {
                        idcarrera:$carreraSeleccionada.id,
                        tiempo: tiempo
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (dato){
                    }
                });
                $carreraSeleccionada=null;
                $unidades[unidad].ocupada='si';
                var unid = document.getElementById(unidad);
                if (!unid){
                    alert("La unidad selecionada no esta disponible");
                } else {
                    var padre = unid.parentNode;
                    padre.removeChild(unid);
                }
                actualizarNumSolicitudes();
                actualizarNumNotif();
                
            },
            error: function () {
                alert('Ha ocurrido un error al asignar la unidad');
            }
        });
    }else{
        alert('No se ha seleccionado ninguna solicitud');
    }
}

function asignarUnidadReservacion(unidad){
    var index=$reservaciones.indexOf($carreraSeleccionada);
    if(index!=-1){
        var tiempo = $('#asignar-unidad-tiempo').val();
        $.ajax({
            url: rootPath + 'carrera/asignarUnidadReservacion',
            data: {
                unidad: unidad,
                idreservacion:$carreraSeleccionada.id,
                tiempo: tiempo
            },
            type: 'POST',
            dataType: 'json',
            success: function (dato){
                var carrera = dato[0];
                $carreraSeleccionada.id=carrera.id;    
                $carreraSeleccionada.aviso=false;
                $carreraSeleccionada.unidad=carrera.unidad;
                $carreraSeleccionada.tiempo=carrera.tiempo;
                $carreraSeleccionada.atencion=carrera.atencion;
                $carreraSeleccionada.detalle='';
                $carreraSeleccionada.hora=carrera.hora; 
                $carreraSeleccionada.tipo=CARRERA_CURSO;
                $carreraSeleccionada.infowindow.setContent(getInformacionCarrera($carreraSeleccionada));
                $carreraSeleccionada.marker.setIcon(CARRERA_CURSO_ICON);
                $carreraSeleccionada.marker.setAnimation(null);
                $('#'+$carreraSeleccionada.idDiv).remove();
                $carreraSeleccionada.idDiv='c'+$carreraSeleccionada.id;
                $('<div></div>',{
                    id:$carreraSeleccionada.idDiv
                }).addClass('notif-carrera').html(getInformacionCarreraDiv($carreraSeleccionada,'Carrera en Curso:')).prependTo('div#panel-notificaciones');
                setEventoClickDiv($carreraSeleccionada);
                setBotonesMesageMap($carreraSeleccionada,'carrera');
                //                setTimeout(function(){
                if($unidades[$carreraSeleccionada.unidad].posicion){
                    $carreraSeleccionada=marcarMapaRuta($carreraSeleccionada,new google.maps.LatLng($sectores[$unidades[$carreraSeleccionada.unidad].posicion].lat,$sectores[$unidades[$carreraSeleccionada.unidad].posicion].lng),$carreraSeleccionada.marker.getPosition());
                } 
                //                },1000);
                $carreras.push($carreraSeleccionada);
                $reservaciones.splice(index,1);
                $.ajax({
                    url: rootPath + 'carrera/enviarSmsAsignarUnidadReservacion',
                    data: {
                        unidad: unidad,
                        idcarrera:$carreraSeleccionada.id,
                        tiempo: tiempo
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (dato){
                    }
                });
                $carreraSeleccionada=null;
                $unidades[unidad].ocupada='si';
                var unid = document.getElementById(unidad);
                if (!unid){
                    alert("La unidad selecionada no esta disponible");
                } else {
                    var padre = unid.parentNode;
                    padre.removeChild(unid);
                }
                actualizarNumNotif();
                actualizarNumReservaciones();
            },
            error: function () {
                alert('Ha ocurrido un error al asignar la unidad');
            }
        });
    }else{
        alert('No se ha seleccionado ninguna solicitud');
    }
}
function rechazarSolicitudCarrera(){
    var index=$solicitudes.indexOf($carreraSeleccionada);
    if(index!=-1){
        $.ajax({
            url: rootPath + 'carrera/rechazarSolicitudCarrera',
            data: {
                notificacion: SOLICITUD_RECHAZADA,
                id:$carreraSeleccionada.id
            },
            type: 'POST',
            dataType: 'json',
            success: function (dato){
                $solicitudes[index].marker.setMap(null);
                $('#'+$solicitudes[index].idDiv).remove();
                $solicitudes.splice(index,1);
                $.ajax({
                    url: rootPath + 'carrera/enviarSmsRechazarSolicitudCarrera',
                    data: {
                        idcarrera:$carreraSeleccionada.id
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (dato){
                    }
                });
                $carreraSeleccionada=null;
                actualizarNumSolicitudes();
            },
            error: function () {
                alert('Ha ocurrido un error al asignar la unidad');
            }
        });
    }else{
        alert('No se ha seleccionado ninguna solicitud');
    }
}
function completarCarrera(){
    var index=$carreras.indexOf($carreraSeleccionada);
    if(index!=-1){
        $.ajax({
            url: rootPath + 'carrera/completarCarrera',
            data: {
                idcarrera:$carreraSeleccionada.id
            },
            type: 'POST',
            dataType: 'json',
            success: function (data){
                if(data[0].estado==='completada'){
                    $carreras[index].marker.setMap(null);
                    if($carreras[index].ruta){
                        $carreras[index].ruta.setMap(null);
                    }
                    if($carreras[index].rutaDestino){
                        $carreras[index].rutaDestino.setMap(null);
                    }
                    if($carreras[index].idDivA){
                        $('#'+$carreras[index].idDivA).remove();
                        $contAlertas--;
                        actualizarNumAlertas();
                    }
                    $('#'+$carreras[index].idDiv).remove();
                    $carreraSeleccionada=$carreras[index];
                    $carreras.splice(index,1);
                    $unidades[$carreraSeleccionada.unidad].ocupada='no';
                    //                   Reubicar la unidad en lugar de destino del cliente
                    $unidades[$carreraSeleccionada.unidad].posicion=$carreraSeleccionada.destino;
                    $unidades[$carreraSeleccionada.unidad]=marcarMapaUnidad($unidades[$carreraSeleccionada.unidad]);                  
                    $.ajax({
                        url: rootPath + 'carrera/cambiarSectorUnidad',
                        data: {
                            unidad:$unidades[$carreraSeleccionada.unidad].id,
                            sector:$carreraSeleccionada.destino
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (data){
                        },
                        error: function () {
                            alert('Ha ocurrido un error en el servidor');
                        }
                    });
                    $carreraSeleccionada=null;
                    actualizarNumNotif();
                }else{
                    alert('Error al completar la carrera');
                }
            },
            error: function () {
                alert('Ha ocurrido un error al completar la carrera');
            }
        });
    }
}
function cancelarCarrera(){
    var index=$carreras.indexOf($carreraSeleccionada);
    if(index!=-1){
        $.ajax({
            url: rootPath + 'carrera/cancelarCarrera',
            data: {
                idcarrera:$carreraSeleccionada.id
            },
            type: 'POST',
            dataType: 'json',
            success: function (data){
                if(data[0].estado==='cancelada'){
                    $carreras[index].marker.setMap(null);
                    if($carreras[index].ruta){
                        $carreras[index].ruta.setMap(null);
                    }
                    $('#'+$carreras[index].idDiv).remove();
                    if($carreras[index].idDivA){
                        $('#'+$carreras[index].idDivA).remove();
                        $contAlertas--;
                        actualizarNumAlertas();
                    }
                    $carreras.splice(index,1);
                    $unidades[$carreraSeleccionada.unidad].ocupada='no';
                    actualizarNumNotif();
                }else{
                    alert('Error al cancelar la carrera');
                }
            },
            error: function () {
                alert('Ha ocurrido un error al cancelar la carrera');
            }
        });
    }
}
function setUnidadesBotones(contenedor){
    var conten = $('#'+contenedor);
    var conten2=$('#'+contenedor+'-cercanos');
    conten.html('');
    conten2.html('');
    var elementos=[];
    var elementos2=[];
    $('p#titulo-cernanos').css("display", "none");
    for(var i in $unidades){
        if($unidades[i].ocupada==='no')
            if($carreraSeleccionada.idsector===$unidades[i].posicion){
                elementos2.push('<button onclick="asignarUnidadCarrera('+i+')" class="btn-taxi" id="'+i+'"><span>'+i+'</span></button>');
                $('p#titulo-cernanos').css("display", "block");
            }else
                elementos.push('<button onclick="asignarUnidadCarrera('+i+')" class="btn-taxi" id="'+i+'"><span>'+i+'</span></button>');

    }      
    conten.append(elementos.join(''));
    conten2.append(elementos2.join(''));

}
function setUnidadesBotonesReservacion(contenedor){
    var conten = $('#'+contenedor);
    conten.html('');
    var elementos=[];
    for(var i in $unidades){
        if($unidades[i].ocupada==='no')
            elementos.push('<button onclick="asignarUnidadReservacion('+i+')" class="btn-taxi" id="'+i+'"><span>'+i+'</span></button>');
    }      
    conten.append(elementos.join(''));
}
function setUnidadesManejo(contenedor){
    var conten = $('#'+contenedor);
    conten.html('');
    var elementos=[];
    for(var i in $unidades){
        elementos.push('<button onclick="manejarUnidad('+i+')" class="btn-taxi" id="manejo-unidad-'+i+'"><span>'+i+'</span></button>');
    }
    conten.append(elementos.join(''));
}
function manejarUnidad(unUnidad){
    if($unidadManejo){
        $('#manejo-unidad-'+$unidadManejo).attr('class','btn-taxi');   
    }
    $('#manejo-unidad-'+unUnidad).attr('class','btn-taxi-seleccionado');
    $unidadManejo=unUnidad;
    $('#sectores-loja-unidad option[value='+$unidades[unUnidad].posicion+']').attr("selected",true);
//    alert($unidades[unUnidad].posicion);
}
function ubicarUnidad(){
    if($unidadManejo){
        var sector=$('#sectores-loja-unidad').val();
        if(sector){
            $.ajax({
                url: rootPath + 'carrera/cambiarSectorUnidad',
                data: {
                    unidad:$unidades[$unidadManejo].id,
                    sector:sector
                },
                type: 'POST',
                dataType: 'json',
                success: function (data){
                    $unidades[$unidadManejo].posicion=sector;
                    $unidades[$unidadManejo]=marcarMapaUnidad($unidades[$unidadManejo]);
                    $().toastmessage('showToast', {
                        text     : 'Ubicación de Unidad:<div style="font-size: .8em;">La unidad: '+$unidades[$unidadManejo].numero +
                        ' se ubicó en el sector "'+$sectores[sector].nombre+'"</div>',
                        sticky   : false,
                        position : 'top-left',
                        type     : 'success',
                        close    : function () {
                            console.log("toast is closed ...");
                        }
                    });
                },
                error: function () {
                    alert('Ha ocurrido un error en el servidor');
                }
            });
        }else{
            alert('Por favor selccione un sector.');
        }
    }else{
        alert('Por favor selccione una unidad.');
    }
}
function verificarDemora(){
    for(var i in $carreras){
        var tiempoActual=calTiempo($carreras[i].hora);
        var tiempodemora=tiempoActual-$carreras[i].tiempo;
        if($carreras[i].atencion==='-1')
//Un minuto de gracia
            if((tiempoActual -1)>$carreras[i].tiempo && !$carreras[i].aviso){
                $carreras[i].aviso=true;
                $carreras[i].marker.setIcon(CARRERA_DEMORA_ICON);
                $().toastmessage('showToast', {
                    text     : 'Tiempo excedido: <div style="font-size: .8em;">'+
                    'La carrera en curso del código: '+$carreras[i].codigo+ 
                    ' lleva una demora de atención de <span style="color: #ff3333;">'+tiempodemora+
                    ' minutos </span>por parte de la unidad: '+$carreras[i].unidad+'</div>',
                    sticky   : false,
                    position : 'top-left',
                    type     : 'error',
                    close    : function () {
                        console.log("toast is closed ...");
                    }
                });
                $carreras[i].idDivA='a'+$carreras[i].id;
                $('<div></div>',{
                    id:$carreras[i].idDivA
                }).addClass('notif-carrera').html('<h2>Tiempo excedido:</h2> <p>'+
                    'La carrera en curso del código: '+$carreras[i].codigo+ 
                    ' lleva una demora de atención de <span style="color: #ff3333;">'+tiempodemora+
                    ' minutos </span>por parte de la unidad: '+$carreras[i].unidad+'</p>').prependTo('div#panel-alertas');
                setEventoClickDivA($carreras[i]);
                //                setBotonesMesageMap($carreraSeleccionada,'carrera');
                $contAlertas++;
                actualizarNumAlertas();
            }
    }
}
function verificarDemoraDestino(){
    for(var i in $carreras){
        var tiempoActual=calTiempo($carreras[i].horaDestino);
        var tiempoDuracion=Math.floor($carreras[i].duracion/60);
        // Dos minutos de gracia antes de notificar
        if((tiempoActual -2)>tiempoDuracion && !$carreras[i].avisoDestino){
            $carreras[i].avisoDestino=true;
            $carreras[i].marker.setIcon(CARRERA_DEMORA_ICON);
            $().toastmessage('showToast', {
                text     : 'Aviso : <div style="font-size: .8em;">'+
                'La carrera en curso del código: '+$carreras[i].codigo+ 
                ' ha tardado demasiado en la entrega del cliente <br />'+
                ' Unidad: '+$carreras[i].unidad+'</div>',
                sticky   : false,
                position : 'top-left',
                type     : 'error',
                close    : function () {
                    console.log("toast is closed ...");
                }
            });
            $carreras[i].idDivA='a'+$carreras[i].id;
            $('<div></div>',{
                id:$carreras[i].idDivA
            }).addClass('notif-carrera').html('<h2>Aviso:</h2> <p>'+
                'La carrera en curso del codigo: '+$carreras[i].codigo+ 
                ' ha tardado demasiado en la entrega del cliente <br />'+
                ' Unidad: '+$carreras[i].unidad+'</p>').prependTo('div#panel-alertas');
            setEventoClickDivA($carreras[i]);
            //                setBotonesMesageMap($carreraSeleccionada,'carrera');
            $contAlertas++;
            actualizarNumAlertas();
        }
    }
}
//METODOS PARA INGRESAR CARRERA
function cargarCodigo(codigo,id){
    if(codigo.length!=0){
        $.ajax({
            url:rootPath+"carrera/cargarCodigo",
            type: "POST",
            data: {
                cod:codigo
            },
            dataType: 'json',
            cache: false,
            success: function(datos){
                $codigo = datos[0];
                if($codigo.id!=-1){
                    $('td#'+id+'-cliente').html($codigo.cliente);
                    $('td#'+id+'-barrio').html($codigo.barrio);
                    $('td#'+id+'-calle1').html($codigo.calle1);
                    $('td#'+id+'-calle2').html($codigo.calle2);
                    $('td#'+id+'-referencia').html($codigo.referencia);
                    $('td#'+id+'-numCasa').html($codigo.numCasa);
                }else{
                    alert('Codigo incorrecto');
                }
            },
            error: function () {
                alert('Ha ocurrido un error al acceder al servidor');
            }
        });
    }
}
function setUnidadesSelect(select){
    $('#'+select).html('');
    for(var i in $unidades){
        if($unidades[i].ocupada==='no'){
            var option = document.createElement('option');
            $(option).attr({
                value:i
            });
            $(option).html(i);
            $('#'+select).append(option);
        }
    }
}
function setDatosUnidad(index,id){
    var unidad = $unidades[index];
    $('td#'+id+'-propietario').html(unidad.propietario);
    $('td#'+id+'-placa').html(unidad.placa);
    $('td#'+id+'-modelo').html(unidad.marca + ' - ' + unidad.modelo );
}
function crearCarreraCodigo(){
    if($codigo){
        var unidad = $unidades[$('#codigo-unidad').val()];
        var tiempo = $('#codigo-tiempo').val();
        var detalle = $('#codigo-detalle').val();     
        $.ajax({
            url: rootPath + 'carrera/crearCarreraCodigo',
            data: {
                unidad:unidad.id,
                tiempo:tiempo,
                detalle:detalle,
                codigo:$codigo.id
            },
            type: 'POST',
            dataType: 'json',
            success: function (data){
                var carrera=data[0];
                $codigo=null;
                unidad.ocupada='si';
                setUnidadesSelect('codigo-unidad');
                $('input#codigo-codigo').attr('value','');
                $('td#codigo-propietario').html('');
                $('td#codigo-placa').html('');
                $('td#codigo-modelo').html('');
                $('td#codigo-cliente').html('');
                $('td#codigo-barrio').html('');
                $('td#codigo-calle1').html('');
                $('td#codigo-calle2').html('');
                $('td#codigo-referencia').html('');
                $('td#codigo-numCasa').html('');
                $('textarea#codigo-detalle').attr('value','');
                carrera.tipo=CARRERA_CURSO;
                carrera.idDiv='c'+carrera.id;
                carrera=marcarMapa(CARRERA_CURSO_ICON, carrera);
                carrera.aviso=false;
                $('<div></div>',{
                    id:carrera.idDiv
                }).addClass('notif-carrera').html(getInformacionCarreraDiv(carrera,'Carrera en Curso:')).prependTo('div#panel-notificaciones');
                setEventoClickDiv(carrera);
                setBotonesMesageMap(carrera,'carrera');
                setTimeout(function(){
                    if($unidades[carrera.unidad].posicion){
                        carrera=marcarMapaRuta(carrera,new google.maps.LatLng($sectores[$unidades[carrera.unidad].posicion].lat,$sectores[$unidades[carrera.unidad].posicion].lng),carrera.marker.getPosition());
                    } 
                },1000);
                $.ajax({
                    url: rootPath + 'carrera/enviarSmsCrearCarreraCodigo',
                    data: {
                        idcarrera:carrera.id,
                        tiempo: tiempo
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (dato){
                    }
                });
                $carreras.push(carrera);     
                actualizarNumNotif();
            },
            error: function () {
                alert('Ha ocurrido un error al crear la carrera');
            }
        });
    }else{
        alert('Ingrese primero un codigo');
    }
}
function crearCarreraIngreso(){
    var cliente = $('#ingreso-cliente').val();     
    if(cliente.length > 0){
        var telefono = $('#ingreso-telefono').val();     
        if(telefono.length > 0){
            var barrio = $('#ingreso-barrio').val();     
            if(barrio.length > 0){
                var calle1 = $('#ingreso-calle1').val();     
                if(calle1.length > 0){
                    var unidad = $unidades[$('#ingreso-unidad').val()];
                    var tiempo = $('#ingreso-tiempo').val();
                    var detalle = $('#ingreso-detalle').val();
                    var numCasa = $('#ingreso-numCasa').val();     
                    var calle2 = $('#ingreso-calle2').val();     
                    var referencia = $('#ingreso-referencia').val();
                    $.ajax({
                        url: rootPath + 'carrera/crearCarreraIngreso',
                        data: {
                            unidad:unidad.id,
                            tiempo:tiempo,
                            detalle:detalle,
                            cliente:cliente,
                            telefono:telefono,
                            barrio:barrio,
                            calle1:calle1,
                            calle2:calle2,
                            numcasa:numCasa,
                            referencia:referencia
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (data){
                            var carrera=data[0];
                            unidad.ocupada='si';
                            setUnidadesSelect('ingreso-unidad');
                            $('td#ingreso-propietario').html('');
                            $('td#ingreso-placa').html('');
                            $('td#ingreso-modelo').html('');
                            $('input#ingreso-cliente').attr('value','');
                            $('input#ingreso-telefono').attr('value','');
                            $('input#ingreso-barrio').attr('value','');
                            $('input#ingreso-calle1').attr('value','');
                            $('input#ingreso-calle2').attr('value','');
                            $('textarea#ingreso-referencia').attr('value','');
                            $('input#ingreso-numCasa').attr('value','');
                            $('textarea#ingreso-detalle').attr('value','');
                            carrera.tipo=CARRERA_CURSO;
                            carrera.idDiv='c'+carrera.id;
                            carrera=marcarMapa(CARRERA_CURSO_ICON, carrera);
                            carrera.aviso=false;
                            $('<div></div>',{
                                id:carrera.idDiv
                            }).addClass('notif-carrera').html(getInformacionCarreraDiv(carrera,'Carrera en Curso:')).prependTo('div#panel-notificaciones');
                            setEventoClickDiv(carrera);
                            setBotonesMesageMap(carrera,'carrera');
                            setTimeout(function(){
                                if($unidades[carrera.unidad].posicion){
                                    carrera=marcarMapaRuta(carrera,new google.maps.LatLng($sectores[$unidades[carrera.unidad].posicion].lat,$sectores[$unidades[carrera.unidad].posicion].lng),carrera.marker.getPosition());
                                } 
                            },1000);
                            $carreras.push(carrera);   
                            actualizarNumNotif();
                        },
                        error: function () {
                            alert('Ha ocurrido un error al crear la carrera');
                        }
                    });
                }else alert('La calle principal del cliente es obligatorio');
            }else alert('El Barrio del cliente es obligatorio');
        }else alert('El teléfono del cliente es obligatorio');
    }else alert('El nombre del cliente es obligatorio');
}

function checkKeyEnter(key){
    var unicode
    if (key.charCode)
        unicode=key.charCode;
    else
        unicode=key.keyCode;
    if (unicode == 13)
        return true;
    else
        return false;    
}

//METODO PARA RESTAR DOS HORAS
function padNmb(nStr, nLen){
    var sRes = String(nStr);
    var sCeros = "0000000000";
    return sCeros.substr(0, nLen - sRes.length) + sRes;
}
function stringToSeconds(tiempo){
    var sep1 = tiempo.indexOf(":");
    var sep2 = tiempo.lastIndexOf(":");
    var hor = tiempo.substr(0, sep1);
    var min = tiempo.substr(sep1 + 1, sep2 - sep1 - 1);
    var sec = tiempo.substr(sep2 + 1);
    return (Number(sec) + (Number(min) * 60) + (Number(hor) * 3600));
}
function secondsToTime(secs){
    var hor = Math.floor(secs / 3600);
    var min = Math.floor((secs - (hor * 3600)) / 60);
    var sec = secs - (hor * 3600) - (min * 60);
    return padNmb(hor, 2) + ":" + padNmb(min, 2) + ":" + padNmb(sec, 2);
}

function deSegundosAMinutos(seg){
    return Math.floor(seg/60);
}
function substractTimes(t1, t2){
    var secs1 = stringToSeconds(t1);
    var secs2 = stringToSeconds(t2);
    var secsDif = secs1 - secs2;
    return secondsToTime(secsDif);
}   
//Modificacion del metodo para restar 2 horas
function calTiempo(h2){
    var h1 = hora();
    var secs1 = stringToSeconds(h1);
    var secs2 = stringToSeconds(h2);
    var secsDif = secs1 - secs2;
    return Math.floor(secsDif/60);
} 

function hora(){
    var fecha = new Date();
    var hora = fecha.getHours();
    var minuto = fecha.getMinutes();
    var segundo = fecha.getSeconds();

    if (hora < 10) {
        hora = "0" + hora;
    }
    if (minuto < 10) {
        minuto = "0" + minuto;
    }
    if (segundo < 10) {
        segundo = "0" + segundo;
    }
    var horita = hora + ":" + minuto + ":" + segundo;
    return horita;
}

function setEventoClickDiv(unCarrera){
    $('#'+unCarrera.idDiv).click(function(){
        $map.setZoom(16);
        $map.setCenter(unCarrera.marker.getPosition());
        unCarrera.infowindow.open($map,unCarrera.marker);
        $carreraSeleccionada=unCarrera;         
    });
}
function setEventoClickDivA(unCarrera){
    $('#'+unCarrera.idDivA).click(function(){
        $map.setZoom(16);
        $map.setCenter(unCarrera.marker.getPosition());
        unCarrera.infowindow.open($map,unCarrera.marker);
        $carreraSeleccionada=unCarrera;         
    });
}
function setBotonesMesageMap(unCarrera,tipo){
    if(tipo==='solicitud'){
        $('<input type="button"  value="Asignar U."/>').addClass('btn-mensaje-solicitud').appendTo('#'+unCarrera.idDiv).click(function() {
            $carreraSeleccionada=unCarrera;
            setUnidadesBotones('panel-taxis');
            $('#modal-asignar-unidad').reveal({
                animation: 'fadeAndPop',                   //fade, fadeAndPop, none
                animationspeed: 300,                       //how fast animtions are
                closeonbackgroundclick: true,              //if you click background will modal close?
                dismissmodalclass: 'btn-taxi'    //the class of a button or element that will close an open modal
            });
        });
        $('<input type="button" value="Rechazar"/>').addClass('btn-mensaje-solicitud').appendTo('#'+unCarrera.idDiv).click(function() {
            $carreraSeleccionada=unCarrera;                    
            rechazarSolicitudCarrera();
        });                    
    }else if(tipo==='carrera'){
        if(unCarrera.atencion==='-1'){
            $('<input type="button" value="Atendida"/>').addClass('btn-mensaje-carrera-encurso').appendTo('#'+unCarrera.idDiv).click(function() {
                $carreraSeleccionada=unCarrera;
                //                atencionCarrera();
                ingresarDestinoUnidad()
            });
            $('<input type="button" value="Cancelar"/>').addClass('btn-mensaje-carrera-encurso').appendTo('#'+unCarrera.idDiv).click(function() {
                $carreraSeleccionada=unCarrera;
                cancelarCarrera();
            });
        }else{
            $('<input type="button" value="Completar"/>').addClass('btn-mensaje-carrera-encurso').appendTo('#'+unCarrera.idDiv).click(function() {
                $carreraSeleccionada=unCarrera;
                completarCarrera();
            });
        }
    }else if(tipo==='reservacion'){
        $('<input type="button"  value="Asignar U."/>').addClass('btn-mensaje-solicitud').appendTo('#'+unCarrera.idDiv).click(function() {
            $carreraSeleccionada=unCarrera;
            setUnidadesBotonesReservacion('panel-taxis');
            $('#modal-asignar-unidad').reveal({
                animation: 'fadeAndPop',                   //fade, fadeAndPop, none
                animationspeed: 300,                       //how fast animtions are
                closeonbackgroundclick: true,              //if you click background will modal close?
                dismissmodalclass: 'btn-taxi'    //the class of a button or element that will close an open modal
            });
        });
        $('<input type="button" value="Cancelar"/>').addClass('btn-mensaje-solicitud').appendTo('#'+unCarrera.idDiv).click(function() {
            $carreraSeleccionada=unCarrera;                    
            cancelarReservacion();
        });     
    }
}
function centrarMapa(unSector){
    $map.setZoom(16);
    var latlng = new google.maps.LatLng(unSector.lat,unSector.lng);
    $map.setCenter(latlng);
}
function actualizarNumSolicitudes(){
    if($solicitudes.length > 0){            
        $('span#num-solicitudes').css("display", "inline");
        $('span#num-solicitudes').html($solicitudes.length).addClass('animating');
        setTimeout(function() {
            $('span#num-solicitudes').removeClass('animating');
        }, 1000);
    }else{
        $('span#num-solicitudes').css("display", "none");
    }
}
function actualizarNumReservaciones(){
    if($reservaciones.length > 0){            
        $('span#num-reservaciones').css("display", "inline");
        $('span#num-reservaciones').html($reservaciones.length).addClass('animating');
        setTimeout(function() {
            $('span#num-reservaciones').removeClass('animating');
        }, 1000);
    }else{
        $('span#num-reservaciones').css("display", "none");
    }
}
function actualizarNumNotif(){
    if($carreras.length > 0){            
        $('span#num-notificaciones').css("display", "inline");
        $('span#num-notificaciones').html($carreras.length).addClass('animating');
        setTimeout(function() {
            $('span#num-notificaciones').removeClass('animating');
        }, 1000);
    }else{
        $('span#num-notificaciones').css("display", "none");
    }
}
function actualizarNumAlertas(){
    if($contAlertas > 0){            
        $('span#num-alertas').css("display", "inline");
        $('span#num-alertas').html($contAlertas).addClass('animating');
        setTimeout(function() {
            $('span#num-alertas').removeClass('animating');
        }, 1000);
    }else{
        $('span#num-alertas').css("display", "none");
    }
}
    
