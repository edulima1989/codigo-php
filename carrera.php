<?php

/**
 * carrera actions.
 *
 * @package    radiotaxi
 * @subpackage carrera
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class carreraActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->calcularCarreras($request);
    }

    public function executeSimulacion(sfWebRequest $request) {
        $this->inicializarObjetos();
        $this->sectors = Doctrine_Core::getTable('sector')
                ->createQuery('a')
                ->execute();
    }

//    METODOS PARA MANIPULACION DE OBJETOS EN TIEMPO REAL
//    PHP, JAVASCRIPT, AJAX, JSON

    public function executeCargarCarreras(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $this->carreras = Doctrine_Core::getTable('carrera')
                ->createQuery('c')->where('c.estado=?', 'espera')
                ->execute();
        $dataJson = array();
        foreach ($this->carreras as $carrera) {
            $dataJson[] = array('id' => $carrera->getId(),
                'barrio' => $carrera->getBarrio(),
                'calle1' => $carrera->getCalle1(),
                'calle2' => $carrera->getCalle2(),
                'cliente' => $carrera->getNombreCliente(),
                'detalle' => $carrera->getDetalle(),
                'tiempo' => $carrera->getTiempo(),
                'unidad' => $carrera->getVehiculo()->getNumero(),
                'hora' => $carrera->getHora(),
                'codigo' => $carrera->getNumCodigo(),
                'idcodigo' => $carrera->getCodigoId(),
                'atencion' => $carrera->getTiempoAtencion(),
                'idsector' => $carrera->getSector(),
                'latitud' => $carrera->getLatitud(),
                'longitud' => $carrera->getLongitud(),
                'destino' => $carrera->getDestino(),
                'horaDestino' => $carrera->getHoraat()
            );
        }
        return $this->renderText(json_encode($dataJson));
    }

    public function executeCargarSolicitudes(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $this->solicitud = Doctrine_Core::getTable('solicitud_carrera')
                ->createQuery('c')->where('c.estado=?', 'Solicitada')
                ->execute();
        $dataJson = array();
        foreach ($this->solicitud as $sol) {
            $dataJson[] = array('id' => $sol->getId(),
                'barrio' => $sol->getBarrio(),
                'calle1' => $sol->getCodigo()->getCalle1(),
                'calle2' => $sol->getCodigo()->getCalle2(),
                'cliente' => $sol->getCodigo()->getNombreCliente(),
                'detalle' => $sol->getDetalle(),
                'tiempo' => $sol->getTiempo(),
                'codigo' => $sol->getCodigo()->getNumero(),
                'idcodigo' => $sol->getCodigo()->getId(),
                'idsector' => $sol->getCodigo()->getBarrio(),
                'latitud' => $sol->getCodigo()->getLatitud(),
                'longitud' => $sol->getCodigo()->getLongitud()
            );
        }
        return $this->renderText(json_encode($dataJson));
    }

    public function executeCargarCancelacionSolicitudes(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $this->solicitud = Doctrine_Core::getTable('solicitud_carrera')
                ->createQuery('c')->where('c.estado=?', 'cancelada')->andWhere('c.notificacion=?', '2')
                ->execute();
        $dataJson = array();
        foreach ($this->solicitud as $sol) {
            $dataJson[] = array('id' => $sol->getId(),
            );
        }
        return $this->renderText(json_encode($dataJson));
    }

    public function executeCargarNuevasSolicitudes(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $this->solicitud = Doctrine_Core::getTable('solicitud_carrera')
                ->createQuery('c')->where('c.estado=?', 'Solicitada')->andWhere('c.notificacion=?', '1')
                ->execute();
        $dataJson = array();
        foreach ($this->solicitud as $sol) {
            $dataJson[] = array('id' => $sol->getId(),
                'barrio' => $sol->getBarrio(),
                'calle1' => $sol->getCodigo()->getCalle1(),
                'calle2' => $sol->getCodigo()->getCalle2(),
                'cliente' => $sol->getCodigo()->getNombreCliente(),
                'detalle' => $sol->getDetalle(),
                'tiempo' => $sol->getTiempo(),
                'codigo' => $sol->getCodigo()->getNumero(),
                'idcodigo' => $sol->getCodigo()->getId(),
                'idsector' => $sol->getCodigo()->getBarrio(),
                'latitud' => $sol->getCodigo()->getLatitud(),
                'longitud' => $sol->getCodigo()->getLongitud()
            );
        }
        return $this->renderText(json_encode($dataJson));
    }

    public function executeCargarReservaciones(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $dia = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m"), date("d"), date("Y")), 0);
//        $tDesde=time()-600;
//        $tHasta=time()-540;
//        $hDesde=date('H:i:s',$tDesde);
//        $hHasta=date('H:i:s',$tHasta);
        switch ($dia) {
            case '1':
                $reservacion = Doctrine_Core::getTable('reservacion')
                        ->createQuery('c')->where('c.lunes=?', true)
//                    ->andWhere('c.horario1>?',$hDesde)
//                    ->andWhere('c.horario1<?',$hHasta)
                        ->execute();
                break;
            case '2':
                $reservacion = Doctrine_Core::getTable('reservacion')
                        ->createQuery('c')->where('c.martes=?', true)
                        ->execute();
                break;
            case '3':
                $reservacion = Doctrine_Core::getTable('reservacion')
                        ->createQuery('c')->where('c.miercoles=?', true)
                        ->execute();
                break;
            case '4':
                $reservacion = Doctrine_Core::getTable('reservacion')
                        ->createQuery('c')->where('c.jueves=?', true)
                        ->execute();
                break;
            case '5':
                $reservacion = Doctrine_Core::getTable('reservacion')
                        ->createQuery('c')->where('c.viernes=?', true)
                        ->execute();
                break;
            case '6':
                $reservacion = Doctrine_Core::getTable('reservacion')
                        ->createQuery('c')->where('c.sabado=?', true)
                        ->execute();
                break;
            case '0':
                $reservacion = Doctrine_Core::getTable('reservacion')
                        ->createQuery('c')->where('c.domingo=?', true)
                        ->execute();
                break;
            default:
                break;
        }
        $dataJson = array();
        foreach ($reservacion as $reser) {
            if ($reser->getHorario1()) {
                if (Operaciones::esHoraReservacion($reser->getHorario1(), date('H:i:s')))
                    $dataJson[] = array('id' => $reser->getId(),
                        'barrio' => $reser->getCodigo()->getSector()->getNombre(),
                        'calle1' => $reser->getCodigo()->getCalle1(),
                        'calle2' => $reser->getCodigo()->getCalle2(),
                        'cliente' => $reser->getCodigo()->getNombreCliente(),
                        'hora' => $reser->getHorario1(),
                        'codigo' => $reser->getCodigo()->getNumero(),
                        'idcodigo' => $reser->getCodigo()->getId(),
                        'idsector' => $reser->getCodigo()->getBarrio(),
                        'latitud' => $reser->getCodigo()->getLatitud(),
                        'longitud' => $reser->getCodigo()->getLongitud()
                    );
            }
            if ($reser->getHorario2()) {
                if (Operaciones::esHoraReservacion($reser->getHorario2(), date('H:i:s')))
                    $dataJson[] = array('id' => $reser->getId(),
                        'barrio' => $reser->getCodigo()->getSector()->getNombre(),
                        'calle1' => $reser->getCodigo()->getCalle1(),
                        'calle2' => $reser->getCodigo()->getCalle2(),
                        'cliente' => $reser->getCodigo()->getNombreCliente(),
                        'hora' => $reser->getHorario2(),
                        'codigo' => $reser->getCodigo()->getNumero(),
                        'idcodigo' => $reser->getCodigo()->getId(),
                        'idsector' => $reser->getCodigo()->getBarrio(),
                        'latitud' => $reser->getCodigo()->getLatitud(),
                        'longitud' => $reser->getCodigo()->getLongitud()
                    );
            }
            if ($reser->getHorario3()) {
                if (Operaciones::esHoraReservacion($reser->getHorario3(), date('H:i:s')))
                    $dataJson[] = array('id' => $reser->getId(),
                        'barrio' => $reser->getCodigo()->getSector()->getNombre(),
                        'calle1' => $reser->getCodigo()->getCalle1(),
                        'calle2' => $reser->getCodigo()->getCalle2(),
                        'cliente' => $reser->getCodigo()->getNombreCliente(),
                        'hora' => $reser->getHorario3(),
                        'codigo' => $reser->getCodigo()->getNumero(),
                        'idcodigo' => $reser->getCodigo()->getId(),
                        'idsector' => $reser->getCodigo()->getBarrio(),
                        'latitud' => $reser->getCodigo()->getLatitud(),
                        'longitud' => $reser->getCodigo()->getLongitud()
                    );
            }
        }
        return $this->renderText(json_encode($dataJson));
    }

    public function executeActualizarNotificacionSolicitud(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $id = $request->getParameter('id');
            $notificacion = $request->getParameter('notificacion');
            $solicitud_carrera = Doctrine_Core::getTable('solicitud_carrera')->find(array($id));
            $solicitud_carrera->setNotificacion($notificacion);
            $solicitud_carrera->save();
            $dataJson = array();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCargarUnidades(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $this->unidades = Doctrine_Core::getTable('vehiculo')
                ->createQuery('c')
                ->execute();
        $this->carreras = Doctrine_Core::getTable('carrera')
                ->createQuery('c')->where('c.estado=?', 'espera')
                ->execute();
        $dataJson = array();
        foreach ($this->carreras as $carrera) {
            foreach ($this->unidades as $i => $unidad) {
                if ($carrera->getVehiculo()->getId() == $unidad->getId()) {
                    $dataJson[$unidad->getNumero()] = array('id' => $unidad->getId(),
                        'numero' => $unidad->getNumero(),
                        'ano' => $unidad->getAno(),
                        'marca' => $unidad->getMarca(),
                        'placa' => $unidad->getPlaca(),
                        'modelo' => $unidad->getModelo(),
                        'propietario' => $unidad->getPropietario()->getFirstName() . ' ' . $unidad->getPropietario()->getLastName(),
                        'ocupada' => 'si',
                        'posicion' => $unidad->getSector()->getId()
                    );
                    unset($this->unidades[$i]);
                }
            }
        }
        foreach ($this->unidades as $unidad) {
            $dataJson[$unidad->getNumero()] = array('id' => $unidad->getId(),
                'numero' => $unidad->getNumero(),
                'ano' => $unidad->getAno(),
                'marca' => $unidad->getMarca(),
                'placa' => $unidad->getPlaca(),
                'modelo' => $unidad->getModelo(),
                'propietario' => $unidad->getPropietario()->getFirstName() . ' ' . $unidad->getPropietario()->getLastName(),
                'ocupada' => 'no',
                'posicion' => $unidad->getSector()->getId()
            );
        }
        return $this->renderText(json_encode($dataJson));
    }

    public function executeAsignarUnidadCarrera(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            $idCarrera = $request->getParameter('idcarrera');
            $notificacion = $request->getParameter('notificacion');
            $solicitud_carrera = Doctrine_Core::getTable('solicitud_carrera')->find(array($idCarrera));
            date_default_timezone_set("America/Guayaquil");
            $numero = $request->getParameter('unidad');
            $veh = Doctrine_Query::create()
                    ->from('vehiculo v')
                    ->where('v.numero = ?', $numero);
            $vehiculo = $veh->fetchOne();
            $solicitud_carrera->setEstado('aprobada');
            $solicitud_carrera->setNotificacion($notificacion);
            $solicitud_carrera->setTiempo($request->getParameter('tiempo'));
            $solicitud_carrera->save();
            $carrera = new Carrera();
            $carrera->setCalidad(-1);
            $carrera->setSfGuardUser($this->getUser()->getGuardUser());
            $carrera->setCodigo($solicitud_carrera->getCodigo());
            $carrera->setVehiculo($vehiculo);
            $carrera->setTiempo($solicitud_carrera->getTiempo());
            $carrera->setTiempoAtencion('-1');
            $carrera->setDetalle($solicitud_carrera->getDetalle());
            $carrera->setFecha(date("Y-m-d"));
            $carrera->setHora(date("H:i:s"));
            $carrera->setEstado("espera");
            $carrera->save();

            $dataJson[] = array('id' => $carrera->getId(),
                'tiempo' => $carrera->getTiempo(),
                'unidad' => $carrera->getVehiculo()->getNumero(),
                'atencion' => $carrera->getTiempoAtencion(),
                'hora' => $carrera->getHora()
            );
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeEnviarSmsAsignarUnidadCarrera(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $idCarrera = $request->getParameter('idcarrera');
            $tiempo = $request->getParameter('tiempo');
            $carrera = Doctrine_Core::getTable('carrera')->find(array($idCarrera));
            $mensaje = "Radio-Taxi Benjamin Carrion: 
                        Su taxi para el codigo " . $carrera->getCodigo()
                    . " llegara en un tiempo aproximado de $tiempo minutos.
                    Gracias por preferirnos";
            Operaciones::enviarSms('RadioTaxi', $mensaje, '593' . $carrera->getCodigo()->getSfGuardUser()->getTelefonoMovil());
            $dataJson = array();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeEnviarSmsAsignarUnidadReservacion(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $idCarrera = $request->getParameter('idcarrera');
            $unidad = $request->getParameter('unidad');
            $tiempo = $request->getParameter('tiempo');
            $carrera = Doctrine_Core::getTable('carrera')->find(array($idCarrera));
            $mensaje = "Radio-Taxi Benjamin Carrion: 
                        Su taxi para la reservacion del codigo " . $carrera->getCodigo()
                    . " llegara en un tiempo aproximado de $tiempo minutos";
            Operaciones::enviarSms('RadioTaxi', $mensaje, '593' . $carrera->getCodigo()->getSfGuardUser()->getTelefonoMovil());
            $dataJson = array();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeEnviarSmsRechazarSolicitudCarrera(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $idCarrera = $request->getParameter('idcarrera');
            $solicitud_carrera = Doctrine_Core::getTable('solicitud_carrera')->find(array($idCarrera));
            $mensaje = "Radio-Taxi Benjamin Carrion: 
                        Estimado cliente, por falta de taxis libres                        
                        su solicitud de taxi para el codigo " . $solicitud_carrera->getCodigo()
                    . " no pudo ser atendida.
                    Pedimos disculpas.";
            Operaciones::enviarSms('RadioTaxi', $mensaje, '593' . $solicitud_carrera->getCodigo()->getSfGuardUser()->getTelefonoMovil());
            $dataJson = array();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeEnviarSmsCrearCarreraCodigo(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $idCarrera = $request->getParameter('idcarrera');
            $tiempo = $request->getParameter('tiempo');
            $carrera = Doctrine_Core::getTable('carrera')->find(array($idCarrera));
            $mensaje = "Radio-Taxi Benjamin Carrion: 
                        Su taxi para el codigo " . $carrera->getCodigo()
                    . " llegara en un tiempo aproximado de $tiempo minutos.
                    Gracias por preferirnos";
            Operaciones::enviarSms('RadioTaxi', $mensaje, '593' . $carrera->getCodigo()->getSfGuardUser()->getTelefonoMovil());
            $dataJson = array();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeAsignarUnidadReservacion(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            $idreservacion = $request->getParameter('idreservacion');
            $reservacion = Doctrine_Core::getTable('reservacion')->find(array($idreservacion));
            date_default_timezone_set("America/Guayaquil");
            $numero = $request->getParameter('unidad');
            $veh = Doctrine_Query::create()
                    ->from('vehiculo v')
                    ->where('v.numero = ?', $numero);
            $vehiculo = $veh->fetchOne();
            $carrera = new Carrera();
            $carrera->setCalidad(-1);
            $carrera->setSfGuardUser($this->getUser()->getGuardUser());
            $carrera->setCodigo($reservacion->getCodigo());
            $carrera->setVehiculo($vehiculo);
            $carrera->setTiempo($request->getParameter('tiempo'));
            $carrera->setTiempoAtencion('-1');
            $carrera->setDetalle('');
            $carrera->setFecha(date("Y-m-d"));
            $carrera->setHora(date("H:i:s"));
            $carrera->setEstado("espera");
            $carrera->save();

            $dataJson[] = array('id' => $carrera->getId(),
                'tiempo' => $carrera->getTiempo(),
                'unidad' => $carrera->getVehiculo()->getNumero(),
                'atencion' => $carrera->getTiempoAtencion(),
                'hora' => $carrera->getHora()
            );

            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCargarSectores(sfWebRequest $request) {
        $this->sectores = Doctrine_Core::getTable('sector')
                ->createQuery('c')
                ->execute();
        $dataJson = array();
        foreach ($this->sectores as $sector) {
            $dataJson[$sector->getId()] = array('id' => $sector->getId(),
                'nombre' => $sector->getNombre(),
                'lat' => $sector->getLatitud(),
                'lng' => $sector->getLongitud()
            );
        }
        return $this->renderText(json_encode($dataJson));
    }

    public function executeRechazarSolicitudCarrera(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $notificacion = $request->getParameter('notificacion');
            $id = $request->getParameter('id');
            $solicitud_carrera = Doctrine_Core::getTable('solicitud_carrera')->find(array($id));
            $solicitud_carrera->setEstado('rechazada');
            $solicitud_carrera->setNotificacion($notificacion);
            $solicitud_carrera->save();
            $dataJson = array();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCompletarCarrera(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            date_default_timezone_set("America/Guayaquil");
            $dataJson = array();
            $idCarrera = $request->getParameter('idcarrera');
            $carrera = Doctrine_Core::getTable('carrera')->find(array($idCarrera));
            $carrera->setEstado('completada');
            $carrera->setHoraDest(date("H:i:s"));
            $carrera->save();
            $dataJson[] = array(
                'estado' => $carrera->getEstado()
            );
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCancelarCarrera(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            $idCarrera = $request->getParameter('idcarrera');
            $carrera = Doctrine_Core::getTable('carrera')->find(array($idCarrera));
            $carrera->setEstado('cancelada');
            $carrera->setCalidad(-2);
            $carrera->save();
            $dataJson[] = array(
                'estado' => $carrera->getEstado()
            );
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCargarCodigo(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            $c = $request->getParameter('cod');
            $cod = Doctrine_Query::create()
                    ->from('codigo c')
                    ->where('c.numero = ?', $c);
            $codigo = $cod->fetchOne();
            if (!$codigo) {
                $dataJson[] = array(
                    'id' => '-1'
                );
            } else {
                $dataJson[] = array(
                    'id' => $codigo->getId(),
                    'cliente' => $codigo->getNombreCliente(),
                    'barrio' => $codigo->getSector()->getNombre(),
                    'calle1' => $codigo->getCalle1(),
                    'calle2' => $codigo->getCalle2(),
                    'referencia' => $codigo->getObservacion(),
                    'numero' => $codigo->getNumero(),
                    'numCasa' => $codigo->getNumCasa()
                );
            }
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCrearCarreraCodigo(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            date_default_timezone_set("America/Guayaquil");
            $carrera = new Carrera();
            $carrera->setCalidad(-1);
            $idCodigo = $request->getParameter('codigo');
            $carrera->setFecha(date("Y-m-d"));
            $carrera->setHora(date("H:i:s"));
            $codigo = Doctrine_Core::getTable('codigo')->find(array($idCodigo));
            $vehiculo = Doctrine_Core::getTable('vehiculo')->find(array($request->getParameter('unidad')));
            $carrera->setDetalle($request->getParameter('detalle'));
            $carrera->setTiempo($request->getParameter('tiempo'));
            $carrera->setTiempoAtencion('-1');
            $carrera->setCodigo($codigo);
            $carrera->setVehiculo($vehiculo);
            $carrera->setSfGuardUser($this->getUser()->getGuardUser());
            $carrera->setEstado('espera');
            $carrera->save();
            $dataJson[] = array('id' => $carrera->getId(),
                'barrio' => $carrera->getBarrio(),
                'calle1' => $carrera->getCalle1(),
                'calle2' => $carrera->getCalle2(),
                'cliente' => $carrera->getNombreCliente(),
                'detalle' => $carrera->getDetalle(),
                'tiempo' => $carrera->getTiempo(),
                'unidad' => $carrera->getVehiculo()->getNumero(),
                'codigo' => $carrera->getNumCodigo(),
                'hora' => $carrera->getHora(),
                'idcodigo' => $carrera->getCodigoId(),
                'atencion' => $carrera->getTiempoAtencion(),
                'idsector' => $carrera->getSector(),
                'latitud' => $carrera->getLatitud(),
                'longitud' => $carrera->getLongitud(),
            );
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCrearCarreraIngreso(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            date_default_timezone_set("America/Guayaquil");
            $carrera = new Carrera();
            $carrera->setCalidad(-1);
            $carrera->setFecha(date("Y-m-d"));
            $carrera->setHora(date("H:i:s"));
            $vehiculo = Doctrine_Core::getTable('vehiculo')->find(array($request->getParameter('unidad')));
            $carrera->setDetalle($request->getParameter('detalle'));
            $carrera->setTiempo($request->getParameter('tiempo'));
            $carrera->setTiempoAtencion('-1');
            $carrera->setVehiculo($vehiculo);
            $carrera->setSfGuardUser($this->getUser()->getGuardUser());
            $carrera->setEstado('espera');
            $sinCodigo = new Sin_Codigo();
            $sinCodigo->setCliente($request->getParameter('cliente'));
            $sinCodigo->setBarrio($request->getParameter('barrio'));
            $sinCodigo->setCalle1($request->getParameter('calle1'));
            $sinCodigo->setTelefono($request->getParameter('telefono'));
            $sinCodigo->setCalle2($request->getParameter('calle2'));
            $sinCodigo->setNumCasa($request->getParameter('numcasa'));
            $sinCodigo->setObservacion($request->getParameter('referencia'));
            $carrera->setSin_Codigo($sinCodigo);

            $carrera->save();
            $dataJson[] = array('id' => $carrera->getId(),
                'barrio' => $carrera->getBarrio(),
                'calle1' => $carrera->getSin_Codigo()->getCalle1(),
                'calle2' => $carrera->getSin_Codigo()->getCalle2(),
                'cliente' => $carrera->getSin_Codigo()->getCliente(),
                'detalle' => $carrera->getDetalle(),
                'tiempo' => $carrera->getTiempo(),
                'unidad' => $carrera->getVehiculo()->getNumero(),
                'codigo' => $carrera->getNumCodigo(),
                'hora' => $carrera->getHora(),
                'idcodigo' => $carrera->getCodigoId(),
                'atencion' => $carrera->getTiempoAtencion(),
                'idsector' => $carrera->getSector());
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeAsignarAtencionCarrera(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            date_default_timezone_set("America/Guayaquil");
            $dataJson = array();
            $idCarrera = $request->getParameter('idcarrera');
            $carrera = Doctrine_Core::getTable('carrera')->find(array($idCarrera));
            $carrera->setTiempoAtencion($request->getParameter('atencion'));
            $sector = Doctrine_Core::getTable('sector')->find(array($request->getParameter('sector')));
            $carrera->setDestino($sector);
            $carrera->setHoraat(date("H:i:s"));
            $carrera->save();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeAsignarTiempoAproDestino(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            $idCarrera = $request->getParameter('idcarrera');
            $carrera = Doctrine_Core::getTable('carrera')->find(array($idCarrera));
            $carrera->setTiempoAproxDest($request->getParameter('tiempo'));
            $carrera->save();
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeCambiarSectorUnidad(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            $idUnidad = $request->getParameter('unidad');
            $unidad = Doctrine_Core::getTable('vehiculo')->find(array($idUnidad));
            $unidad->setIdSector($request->getParameter('sector'));
            $unidad->save();
            return $this->renderText(json_encode($dataJson));
        }
    }

// METODOS PARA CARGAR LAS PAGINAS DE TEMPLATES

    private function calcularCarreras(sfWebRequest $request) {
        $datepicker = $request->getParameter('datepicker');
        $datepicker2 = $request->getParameter('datepicker2');
        if (strlen($datepicker) == 0) {
            $datepicker = date("d/m/Y");
        }
        if (strlen($datepicker2) == 0) {
            $datepicker2 = date("d/m/Y");
        }
        $this->datepicker = $datepicker;
        $this->datepicker2 = $datepicker2;
        $this->getUser()->setAttribute('datepicker', $datepicker);
        $this->getUser()->setAttribute('datepicker2', $datepicker2);
        $datepicker2 = Operaciones::convertirFecha($datepicker2);
        $datepicker = Operaciones::convertirFecha($datepicker);
        return $this->carreras = Doctrine_Core::getTable('carrera')
                ->createQuery('a')->where('a.fecha>=?', date($datepicker))->andWhere('a.fecha<=?', date($datepicker2))
                ->execute();
    }

    public function executeGestion(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $this->inicializarObjetos();
    }

    public function executeShow(sfWebRequest $request) {
        $this->carrera = Doctrine_Core::getTable('carrera')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->carrera);
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new CarreraForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        date_default_timezone_set("America/Guayaquil");
        $carrera = new Carrera();
        $carrera->setCalidad(-1);
        $idCodigo = $request->getParameter('id_Cod');
        $carrera->setFecha(date("Y-m-d"));
        $carrera->setHora(date("H:i:s"));
        $codigo = Doctrine_Core::getTable('codigo')->find(array($idCodigo));
        $vehiculo = Doctrine_Core::getTable('vehiculo')->find(array($request->getParameter('id_Veh')));
        if (!$codigo) {
            $this->getUser()->setFlash('error', 'No se pudo completar el proceso debido a que el código de cliente especificado no existe.', true);
            $this->redirect('carrera/gestion');
        }
        if (!$vehiculo) {
            $this->getUser()->setFlash('error', 'No se pudo completar el proceso debido a que el numero de vehiculo especificado no existe.', true);
            $this->redirect('carrera/gestion');
        }
        $carrera->setCodigo($codigo);
        $carrera->setVehiculo($vehiculo);
        $carrera->setSfGuardUser($this->getUser()->getGuardUser());
        $carrera->setTiempoAtencion('-1');
//      obtener usuario en el success
//        php $id = $this->user = sfContext::getInstance()->getUser()->getGuardUser()->getId()   
        $carrera->setEstado('espera');

        $this->form = new carreraForm($carrera);

        $this->processForm($request, $this->form);

        $this->setTemplate('gestion');
    }

    public function executeCreateSin(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        date_default_timezone_set("America/Guayaquil");
        $carrera = new Carrera();
        $carrera->setCalidad(-1);
        $carrera->setFecha(date("Y-m-d"));
        $carrera->setHora(date("H:i:s"));
        $vehiculo = Doctrine_Core::getTable('vehiculo')->find(array($request->getParameter('id_Veh')));
        if (!$vehiculo) {
            $this->getUser()->setFlash('error', 'No se pudo completar el proceso debido a que el numero de vehiculo especificado no existe.', true);
            $this->redirect('carrera/gestion');
        }
        $cliente = $request->getParameter('clienteS');
        $barrio = $request->getParameter('barrioS');
        $telefono = $request->getParameter('telefonoS');
        $calle1 = $request->getParameter('calle1S');
        if (empty($cliente) && empty($telefono) && empty($calle1) && empty($barrio)) {
            $this->getUser()->setFlash('error', 'Los datos del cliente estan incompletos.', true);
            $this->redirect('carrera/gestion');
        }
        $this->sinCodigo = new Sin_Codigo();
        $this->sinCodigo->setCliente($cliente);
        $this->sinCodigo->setBarrio($barrio);
        $this->sinCodigo->setCalle1($calle1);
        $this->sinCodigo->setTelefono($telefono);
        $this->sinCodigo->setCalle2($request->getParameter('calle2S'));
        $this->sinCodigo->setNumCasa($request->getParameter('numcasaS'));
        $this->sinCodigo->setObservacion($request->getParameter('observacionS'));
        $carrera->setTiempoAtencion('-1');
        $carrera->setVehiculo($vehiculo);
        $carrera->setSin_Codigo($this->sinCodigo);
        $carrera->setSfGuardUser($this->getUser()->getGuardUser());
        $carrera->setEstado('espera');

        $this->form = new carreraForm($carrera);

        $this->processForm($request, $this->form);

        $this->setTemplate('gestion');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($carrera = Doctrine_Core::getTable('carrera')->find(array($request->getParameter('id'))), sprintf('Object carrera does not exist (%s).', $request->getParameter('id')));
        $this->form = new carreraForm($carrera);
    }

    public function executeCompletar(sfWebRequest $request) {
        $this->forward404Unless($carrera = Doctrine_Core::getTable('carrera')->find(array($request->getParameter('id'))), sprintf('Object carrera does not exist (%s).', $request->getParameter('id')));
        $carrera->setEstado('completada');
        $carrera->save();
        $this->redirect('carrera/gestion');
    }

    public function executeCancelar(sfWebRequest $request) {
        $this->forward404Unless($carrera = Doctrine_Core::getTable('carrera')->find(array($request->getParameter('id'))), sprintf('Object carrera does not exist (%s).', $request->getParameter('id')));
        $carrera->setEstado('cancelada');
        $carrera->save();
        $this->redirect('carrera/gestion');
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($carrera = Doctrine_Core::getTable('carrera')->find(array($request->getParameter('id'))), sprintf('Object carrera does not exist (%s).', $request->getParameter('id')));
        $this->form = new carreraForm($carrera);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($carrera = Doctrine_Core::getTable('carrera')->find(array($request->getParameter('id'))), sprintf('Object carrera does not exist (%s).', $request->getParameter('id')));
        $carrera->delete();

        $this->redirect('carrera/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $carrera = $form->save();
            $this->getUser()->setFlash('notice', 'Carrera en curso creada correctamente.', true);
            $this->redirect('carrera/gestion');
        }
    }

    public function executeAprobarCarrera(sfWebRequest $request) {
        date_default_timezone_set("America/Guayaquil");
        $this->forward404Unless($solicitud_carrera = Doctrine_Core::getTable('solicitud_carrera')->find(array($request->getParameter('id'))), sprintf('Los datos solicitados no existen (%s).', $request->getParameter('id')));
        $numero = $request->getParameter('vehiculo');
        $veh = Doctrine_Query::create()
                ->from('vehiculo v')
                ->where('v.numero = ?', $numero);
        $vehiculo = $veh->fetchOne();

        if ($vehiculo) {

            $carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('c')->where('c.estado=?', 'espera')
                    ->execute();

            $vehiculoActivo = false;
            foreach ($carreras as $carrera) {
                if ($carrera->getVehiculo()->getId() == $vehiculo->getId()) {
                    $vehiculoActivo = true;
                    break;
                }
            }
            if (!$vehiculoActivo) {

                $tiempo = $request->getParameter('tiempo');

                if (Operaciones::is_Entero($tiempo)) {

                    $solicitud_carrera->setTiempo($tiempo);
                    $solicitud_carrera->setEstado('aprobada');
                    $solicitud_carrera->save();
                    $carrera = new Carrera();
                    $carrera->setCalidad(-1);
                    $carrera->setSfGuardUser($this->getUser()->getGuardUser());
                    $carrera->setCodigo($solicitud_carrera->getCodigo());
                    $carrera->setVehiculo($vehiculo);
                    $carrera->setTiempo($tiempo);
                    $carrera->setTiempoAtencion('-1');
                    $carrera->setDetalle($solicitud_carrera->getDetalle());

                    date_default_timezone_set("America/Guayaquil");
                    $carrera->setFecha(date("Y-m-d"));
                    $carrera->setHora(date("H:i:s"));
                    $carrera->setEstado("espera");
                    $carrera->save();
                } else {
                    $this->getUser()->setFlash('error', 'El tiempo de espera se debe especificar con un número entero positivo.', true);
                }
            } else {
                $this->getUser()->setFlash('error', 'El vehiculo : ["' . $vehiculo . '"] se encuentra ocupado.', true);
            }
        } else {
            $this->getUser()->setFlash('error', 'El vehiculo : ["' . $numero . '"] al que hace referencia no existe.', true);
        }
        $this->redirect('carrera/gestion');
    }

    public function executeRechazarCarrera(sfWebRequest $request) {
        $this->inicializarObjetos();
        $this->forward404Unless($solicitud_carrera = Doctrine_Core::getTable('solicitud_carrera')->find(array($request->getParameter('id'))), sprintf('Los datos solicitados no existen (%s).', $request->getParameter('id')));
        $solicitud_carrera->setEstado('rechazada');
        $solicitud_carrera->save();
        $this->getUser()->setFlash('notice', 'Solicitud de Carrera rechazada.', true);
        $this->redirect('carrera/gestion');
    }

    public function executeGetVehiculo(sfWebRequest $request) {
        $this->inicializarObjetos();
        $this->isOcasional = 'CHECKED';
        $numero = $request->getParameter('vehiculoS');
        $veh = Doctrine_Query::create()
                ->from('vehiculo v')
                ->where('v.numero = ?', $numero);
        $this->vehiculoS = $veh->fetchOne();
        if (!$this->vehiculoS) {
            $this->getUser()->setFlash('error', 'El vehiculo : ["' . $numero . '"] al que hace referencia no existe.', true);
            $this->vehiculoS = new Vehiculo();
        } else {
            $vehiculoActivo = false;
            foreach ($this->carreras as $carrera) {
                if ($carrera->getVehiculo()->getId() == $this->vehiculoS->getId()) {
                    $vehiculoActivo = true;
                    break;
                }
            }
            if ($vehiculoActivo) {
//                echo "<script languaje='javascript'>alert('El vehiculo : [" . $this->vehiculoS . "] se encuentra ocupado')</script>";
                $this->getUser()->setFlash('error', 'El vehiculo : [' . $this->vehiculoS . '] se encuentra ocupado', true);
                $this->vehiculoS = new Vehiculo();
            }
        }

        $this->sinCodigo = new Sin_Codigo();
        $cliente = $request->getParameter('clienteS');
        $barrio = $request->getParameter('barrioS');
        $telefono = $request->getParameter('telefonoS');
        $calle1 = $request->getParameter('calle1S');
        if (empty($cliente)) {
            $this->getUser()->setFlash('error', 'El nombre del cliente es un campo obligatorio.', true);
        } elseif (empty($telefono)) {
            $this->getUser()->setFlash('error', 'El teléfono del cliente es un campo obligatorio.', true);
        } elseif (empty($calle1) || empty($barrio)) {
            $this->getUser()->setFlash('error', 'El barrio y calles de la dirección del cliente son obligatorios.', true);
        }
        $this->sinCodigo->setCliente($cliente);
        $this->sinCodigo->setBarrio($barrio);
        $this->sinCodigo->setCalle1($calle1);
        $this->sinCodigo->setTelefono($telefono);
        $this->sinCodigo->setCalle2($request->getParameter('calle2S'));
        $this->sinCodigo->setNumCasa($request->getParameter('numcasaS'));
        $this->sinCodigo->setObservacion($request->getParameter('observacionS'));
        $this->setTemplate('gestion');
    }

    public function executeGetCod_Veh(sfWebRequest $request) {
        $this->inicializarObjetos();

        $cod = Doctrine_Query::create()
                ->from('codigo c')
                ->where('c.numero = ?', $request->getParameter('codigo'));
        $this->codigo = $cod->fetchOne();

        $veh = Doctrine_Query::create()
                ->from('vehiculo v')
                ->where('v.numero = ?', $request->getParameter('vehiculo'));
        $this->vehiculo = $veh->fetchOne();

        if (!$this->codigo) {
            $this->codigo = new Codigo();
        }

        if (!$this->vehiculo) {
            $this->vehiculo = new Vehiculo();
        }

        $vehiculoActivo = false;
        foreach ($this->carreras as $carrera) {
            if ($carrera->getVehiculo()->getId() == $this->vehiculo->getId()) {
                $vehiculoActivo = true;
                break;
            }
        }
        if ($vehiculoActivo) {
            $this->getUser()->setFlash('error', 'El vehiculo : [' . $this->vehiculo . '] se encuentra ocupado', true);
            $this->vehiculo = new Vehiculo();
        }
        $this->setTemplate('gestion');
    }

    private function inicializarObjetos() {
        date_default_timezone_set("America/Guayaquil");
        $this->codigo = new Codigo();
        $this->vehiculo = new Vehiculo();
        $this->vehiculoS = new vehiculo();
        $this->form = new CarreraForm();
        $this->sinCodigo = new Sin_Codigo();
        $this->carreras = Doctrine_Core::getTable('carrera')
                ->createQuery('c')->where('c.estado=?', 'espera')
                ->execute();

        $this->solicitud = Doctrine_Core::getTable('solicitud_carrera')
                ->createQuery('c')->where('c.estado=?', 'Solicitada')
                ->execute();
        $this->isOcasional = '';
        $this->unidades = Doctrine_Core::getTable('vehiculo')
                ->createQuery('c')
                ->execute();
        foreach ($this->carreras as $carrera) {
            foreach ($this->unidades as $i => $unidad) {
                if ($carrera->getVehiculo()->getId() == $unidad->getId()) {
                    unset($this->unidades[$i]);
                }
            }
        }
    }

    public function executeReporte() {
        //Obtener los datos para el reporte
        $datepicker = $this->getUser()->getAttribute('datepicker');
        $datepicker2 = $this->getUser()->getAttribute('datepicker2');
        $date2 = Operaciones::convertirFecha($datepicker2);
        $date = Operaciones::convertirFecha($datepicker);


        $carreras = Doctrine_Core::getTable('carrera')
                ->createQuery('a')->where('a.fecha>=?', $date)->andWhere('a.fecha<=?', $date2)
                ->execute();


        // Configurar el reporte        
        $doc_title = "Reporte de Carreras - Radiotaxi \"Benjamín Carrión\" ";
        $doc_subject = "Reporte de Carreras - Radiotaxi \"Benjamín Carrión\"";
        $doc_keywords = "Reporte";

        $config = sfTCPDFPluginConfigHandler::loadConfig();
        sfTCPDFPluginConfigHandler::includeLangFile($this->getUser()->getCulture());
//crear nuevo documento PDF (unidades del documento se establecen de forma predeterminada en milímetros)
        $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
        // configurar la información del documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Cooperativa de taxis "Benjamín Carrión"');
        $pdf->SetTitle('REPORTE DE CARRERAS REALIZADAS');
        $pdf->SetSubject('Radio-taxi');
        $pdf->SetKeywords('Radio-taxi, PDF, Benjamín Carrión, reporte carreras');

        $pdf->SetHeaderData("", 0, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        //establecer los margenes
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        //establecer saltos de página automático
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //establecido factor de escala de imagen

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        //inicializar el documento
        $pdf->AliasNbPages();
        $pdf->AddPage();

        //diseñar el contenido del reporte



        $htmlcontent = "<h1 style=\"text-align:center;\"> Reporte de Carreras Realizadas</h1> 
<p>Carreras realizadas desde " . $datepicker . " hasta " . $datepicker2 . ". </p>    
<table style=\"font:normal 76%/150% \"Lucida Grande\", \"Lucida Sans Unicode\", Verdana, Arial, Helvetica, sans-serif;border-collapse:separate;border-spacing:0;border-left:1px solid #686868;    border-right:1px solid #686868;    border-bottom: 1px solid #686868;    color:#000;\">    
        <thead>
            <tr bgcolor=\"#ffff73\" style=\"font-weight:bold;line-height:normal;padding:0.25em 0.5em;text-align:left;\">
                <th style=\"border:1px solid #523A0B;border-width:1px 0; width:8%;\">N° </th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:28%;\">Operador</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:11%;\">Código</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:13%;\">Vehiculo</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:14%;\">Fecha</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:11%;\">Hora</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:15%;\">Estado</th>
            </tr>
        </thead>
        <tbody>";

        foreach ($carreras as $i => $carrera) {
            $htmlcontent.= "<tr ";
            if (fmod($i, 2))
                $htmlcontent.="bgcolor=\"#fcfca0\""; else
                $htmlcontent.= "";

            $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:28%;\">" . $carrera->getSfGuardUser() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getNumCodigo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:13%;\">" . $carrera->getVehiculo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:14%;\">" . $carrera->getFecha() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getHora() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:15%;\">" . ucwords($carrera->getEstado()) . "</td>
            </tr>";
        }


        $htmlcontent.= "</tbody>
        </table>";

        $pdf->writeHTML($htmlcontent, true, 0);
        // Cerrar y presentar el documento PDF 
        $pdf->Output('Reporte.pdf', 'I');

        // Detener el proceso de Symfony
        throw new sfStopException();
    }

}


