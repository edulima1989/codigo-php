<?php

/**
 * reservacion actions.
 *
 * @package    radiotaxi
 * @subpackage reportes
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reportesActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        
    }

    public function executeCalidad_Servicio(sfWebRequest $request) {
        $this->cargarFechas($request);
        if ($this->getUser()->getAttribute('esValido') === 'si') {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $datepicker2 = Operaciones::convertirFecha($datepicker2);
            $datepicker = Operaciones::convertirFecha($datepicker);
            $this->carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('a')->where('a.fecha>=?', date($datepicker))
                    ->andWhere('a.fecha<=?', date($datepicker2))
                    ->andWhereNotIn('a.calidad', array(-1, -2))
                    ->execute();
        } else {
            $this->getUser()->setFlash('error', 'El intervalo de fechas seleccionado no es valido', false);
            $this->carreras = null;
        }
    }

    public function executePorunidad(sfWebRequest $request) {
        $this->cargarFechas($request);
        $this->porUnidad($request);
    }

    public function executeError(sfWebRequest $request) {
        
    }

    public function executePorcodigo(sfWebRequest $request) {
        $this->cargarFechas($request);
        $this->porCodigo($request);
    }

    public function executePoroperador(sfWebRequest $request) {
        $this->cargarFechas($request);
        $this->porOperador($request);
    }

    public function executeInforme_General(sfWebRequest $request) {
        $this->cargarFechas($request);
        if ($this->getUser()->getAttribute('esValido') === 'si') {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $datepicker2 = Operaciones::convertirFecha($datepicker2);
            $datepicker = Operaciones::convertirFecha($datepicker);
            $this->carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('a')->where('a.fecha>=?', date($datepicker))->andWhere('a.fecha<=?', date($datepicker2))
                    ->execute();
        } else {
            $this->getUser()->setFlash('error', 'El intervalo de fechas seleccionado no es valido', false);
            $this->carreras = null;
        }
    }

    private function cargarFechas(sfWebRequest $request) {
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
        $fFin = Operaciones::convertirFecha($datepicker2);
        $fInicio = Operaciones::convertirFecha($datepicker);
        $fInicio = strtotime($fInicio);
        $fFin = strtotime($fFin);
        if ($fFin >= $fInicio) {
            $this->getUser()->setAttribute('datepicker', $datepicker);
            $this->getUser()->setAttribute('datepicker2', $datepicker2);
            $this->getUser()->setAttribute('esValido', 'si');
        } else {
            $this->getUser()->setAttribute('esValido', 'no');
        }
    }

    private function porUnidad(sfWebRequest $request) {
        if ($this->getUser()->getAttribute('esValido') === 'si') {
            $seleccion = $request->getParameter('vehiculo');
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $datepicker2 = Operaciones::convertirFecha($datepicker2);
            $datepicker = Operaciones::convertirFecha($datepicker);
            $this->getUser()->setAttribute('unidad', $seleccion);
            if ($seleccion === '0') {
                $this->carreras = null;
                $this->getUser()->setFlash('error', 'Seleccione la unidad para realizar la búsqueda.', false);
            }else
                $this->carreras = Doctrine_Core::getTable('carrera')
                        ->createQuery('a')->where('a.fecha>=?', date($datepicker))->andWhere('a.fecha<=?', date($datepicker2))
                        ->andWhere('a.id_vehiculo=?', $seleccion)
                        ->execute();
        }else {
            $this->getUser()->setFlash('error', 'El intervalo de fechas seleccionado no es valido', false);
            $this->carreras = null;
        }
        $this->unidades = Doctrine_Core::getTable('vehiculo')
                ->createQuery('v')
                ->execute();
    }

    private function porCodigo(sfWebRequest $request) {
        if ($this->getUser()->getAttribute('esValido') === 'si') {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $datepicker2 = Operaciones::convertirFecha($datepicker2);
            $datepicker = Operaciones::convertirFecha($datepicker);
            $codigo = $request->getParameter('codigo');
            if (!empty($codigo)) {
                $this->getUser()->setAttribute('codigo', $codigo);
                $codigo = Doctrine_Query::create()
                        ->from('codigo c')
                        ->where('c.numero = ?', $codigo);
                $codigo = $codigo->fetchOne();
                if (!$codigo) {
                    $this->getUser()->setFlash('error', 'El código ingresado no existe', false);
                    $this->carreras = null;
                }else
                    $this->carreras = Doctrine_Core::getTable('carrera')
                            ->createQuery('a')->where('a.fecha>=?', date($datepicker))->andWhere('a.fecha<=?', date($datepicker2))
                            ->andWhere('a.id_codigo=?', $codigo->getId())
                            ->execute();
            }else {
                $this->getUser()->setAttribute('codigo', $codigo);
                $this->carreras = null;
            }
        } else {
            $this->getUser()->setFlash('error', 'El intervalo de fechas seleccionado no es valido', false);
            $this->carreras = null;
        }
    }

    private function porOperador(sfWebRequest $request) {
        if ($this->getUser()->getAttribute('esValido') === 'si') {
            $seleccion = $request->getParameter('operador');
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $datepicker2 = Operaciones::convertirFecha($datepicker2);
            $datepicker = Operaciones::convertirFecha($datepicker);
            $this->getUser()->setAttribute('operador', $seleccion);
            if ($seleccion === '0') {
                $this->carreras = null;
                $this->getUser()->setFlash('error', 'Seleccione el operador para realizar la búsqueda.', false);
            } else {
                $this->carreras = Doctrine_Core::getTable('carrera')
                        ->createQuery('a')->where('a.fecha>=?', date($datepicker))->andWhere('a.fecha<=?', date($datepicker2))
                        ->andWhere('a.id_operador=?', $seleccion)
                        ->execute();
            }
        } else {
            $this->getUser()->setFlash('error', 'El intervalo de fechas seleccionado no es valido', false);
            $this->carreras = null;
        }
        $this->operadores = Doctrine_Core::getTable('sfGuardUser')
                ->createQuery('a')
                ->execute();
        foreach ($this->operadores as $i => $oper) {
            if (!$oper->hasPermission("operador") && !$oper->getIsSuperAdmin()) {
                unset($this->operadores[$i]);
            }
        }
    }

    public function executeCargarCodigo(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $dataJson = array();
            $cod = $request->getParameter('numero');
            $codigos = Doctrine_Core::getTable('codigo')
                    ->createQuery('a')->where('a.numero LIKE ?', '' . $cod . '%')
                    ->limit(15)
                    ->execute();
            foreach ($codigos as $codigo) {
                $dataJson[] = array(
//                    'id' => $codigo->getId(),
//                    'cliente' => $codigo->getNombreCliente(),
//                    'barrio' => $codigo->getSector()->getNombre(),
//                    'calle1' => $codigo->getCalle1(),
//                    'calle2' => $codigo->getCalle2(),
//                    'referencia' => $codigo->getObservacion(),
                    'numero' => $codigo->getNumero()
//                    'numCasa' => $codigo->getNumCasa()
                );
            }
            return $this->renderText(json_encode($dataJson));
        }
    }

    public function executeReportePorUnidad(sfWebRequest $request) {
//Obtener los datos para el reporte
        $unidad = $this->getUser()->getAttribute('unidad');
        if (($unidad === '0') || ($this->getUser()->getAttribute('esValido') === 'no')) {
            $this->redirect('reportes/error');
        } else {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $date2 = Operaciones::convertirFecha($datepicker2);
            $date = Operaciones::convertirFecha($datepicker);

            $carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('a')->where('a.fecha>=?', $date)->andWhere('a.fecha<=?', $date2)
                    ->andWhere('a.id_vehiculo=?', $unidad)
                    ->execute();
            $this->forward404Unless($unidad = Doctrine_Core::getTable('vehiculo')->find(array($unidad)), sprintf('El objeto unidad no existe (%s).', $unidad));

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
<p>Carreras realizadas desde " . $datepicker . " hasta " . $datepicker2 . " por parte de la unidad N° " . $unidad->getNumero() . ". </p>    
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
                    $htmlcontent.="bgcolor=\"#fcfca0\"";
                else
                    $htmlcontent.= "";

                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:28%;\">" . $carrera->getSfGuardUser()->getFirstName() . " " . $carrera->getSfGuardUser()->getLastName() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getNumCodigo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:13%;\">" . $carrera->getVehiculo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:14%;\">" . $carrera->getFecha() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . substr($carrera->getHora(), 0, 5) . "</td>
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

    public function executeReportePorCodigo(sfWebRequest $request) {
//Obtener los datos para el reporte

        $codigo = $this->getUser()->getAttribute('codigo');
        $codigo = Doctrine_Query::create()
                ->from('codigo c')
                ->where('c.numero = ?', $codigo);
        $codigo = $codigo->fetchOne();
        if (!$codigo || ($this->getUser()->getAttribute('esValido') === 'no')) {
            $this->redirect('reportes/error');
        } else {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $date2 = Operaciones::convertirFecha($datepicker2);
            $date = Operaciones::convertirFecha($datepicker);
            $carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('a')->where('a.fecha>=?', $date)->andWhere('a.fecha<=?', $date2)
                    ->andWhere('a.id_codigo=?', $codigo->getId())
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
<p>Carreras realizadas desde " . $datepicker . " hasta " . $datepicker2 . " para el código N° " . $codigo->getNumero() . ". </p>    
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
                    $htmlcontent.="bgcolor=\"#fcfca0\"";
                else
                    $htmlcontent.= "";

                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:28%;\">" . $carrera->getSfGuardUser()->getFirstName() . " " . $carrera->getSfGuardUser()->getLastName() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getNumCodigo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:13%;\">" . $carrera->getVehiculo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:14%;\">" . $carrera->getFecha() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . substr($carrera->getHora(), 0, 5) . "</td>
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

    public function executeReportePorOperador(sfWebRequest $request) {
//Obtener los datos para el reporte

        $operador = $this->getUser()->getAttribute('operador');
        if ($operador === '0' || ($this->getUser()->getAttribute('esValido') === 'no')) {
            $this->redirect('reportes/error');
        } else {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $date2 = Operaciones::convertirFecha($datepicker2);
            $date = Operaciones::convertirFecha($datepicker);
            $carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('a')->where('a.fecha>=?', $date)->andWhere('a.fecha<=?', $date2)
                    ->andWhere('a.id_operador=?', $operador)
                    ->execute();
            $this->forward404Unless($operador = Doctrine_Core::getTable('sfGuardUser')->find(array($operador)), sprintf('El objeto operador no existe (%s).', $operador));
//        if (!$codigo) {
//            
//        } else {
//            $carreras = Doctrine_Core::getTable('carrera')
//                    ->createQuery('a')->where('a.fecha>=?', $date)->andWhere('a.fecha<=?', $date2)
//                    ->andWhere('a.id_codigo=?', $codigo->getId())
//                    ->execute();
//        }
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
<p>Carreras realizadas desde " . $datepicker . " hasta " . $datepicker2 . " por el operador " . $operador->getFirstName() . " " . $operador->getLastName() . ". </p>    
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
                    $htmlcontent.="bgcolor=\"#fcfca0\"";
                else
                    $htmlcontent.= "";

                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:28%;\">" . $carrera->getSfGuardUser()->getFirstName() . " " . $carrera->getSfGuardUser()->getLastName() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getNumCodigo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:13%;\">" . $carrera->getVehiculo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:14%;\">" . $carrera->getFecha() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . substr($carrera->getHora(), 0, 5) . "</td>
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

    public function executeInformeGeneral() {
//Obtener los datos para el reporte
        if ($this->getUser()->getAttribute('esValido') === 'no')
            $this->redirect('reportes/error');
        else {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $date2 = Operaciones::convertirFecha($datepicker2);
            $date = Operaciones::convertirFecha($datepicker);
            $carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('a')->where('a.fecha>=?', $date)->andWhere('a.fecha<=?', $date2)
                    ->execute();
            $unidad_sector = array();
            foreach ($carreras as $carrera) {
                $indicador = true;
                foreach ($unidad_sector as $i => $aux) {
                    if ($aux['sector'] === $carrera->getBarrio()) {
                        $indicador = false;
                        $unidad_sector[$i]['cont']++;
                        break;
                    }
                }
                if ($indicador) {
                    $unidad_sector[] = array(
                        'sector' => $carrera->getBarrio(),
                        'cont' => 1
                    );
                }
            }
            $total = count($unidad_sector);
            for ($i = 0; $i < $total - 1; $i++) {
                $bandera = 0;
                for ($j = 0; $j < $total - $i - 1; $j++) {
                    if ($unidad_sector[$j]['cont'] < $unidad_sector[$j + 1]['cont']) {
                        $aux = $unidad_sector[$j];
                        $unidad_sector[$j] = $unidad_sector[$j + 1];
                        $unidad_sector[$j + 1] = $aux;
                        $bandera = 1;
                    }
                }
                if ($bandera === 0)
                    break;
            }

            $codigo = array();
            foreach ($carreras as $carrera) {
                $indicador = true;
                foreach ($codigo as $i => $aux) {
                    if ($aux['codigo'] === $carrera->getNumCodigo()) {
                        $indicador = false;
                        $codigo[$i]['cont']++;
                        break;
                    }
                }
                if ($indicador) {
                    $codigo[] = array(
                        'codigo' => $carrera->getNumCodigo(),
                        'cliente' => $carrera->getNombreCliente(),
                        'cont' => 1
                    );
                }
            }
            $total = count($codigo);
            for ($i = 0; $i < $total - 1; $i++) {
                $bandera = 0;
                for ($j = 0; $j < $total - $i - 1; $j++) {
                    if ($codigo[$j]['cont'] < $codigo[$j + 1]['cont']) {
                        $aux = $codigo[$j];
                        $codigo[$j] = $codigo[$j + 1];
                        $codigo[$j + 1] = $aux;
                        $bandera = 1;
                    }
                }
                if ($bandera === 0)
                    break;
            }

            $unidad = array();
            foreach ($carreras as $carrera) {
                $indicador = true;
                foreach ($unidad as $i => $aux) {
                    if ($aux['unidad'] === $carrera->getVehiculo()) {
                        $indicador = false;
                        $unidad[$i]['cont']++;
                        break;
                    }
                }
                if ($indicador) {
                    $unidad[] = array(
                        'unidad' => $carrera->getVehiculo(),
                        'socio' => $carrera->getVehiculo()->getPropietario(),
                        'cont' => 1
                    );
                }
            }
            $total = count($unidad);
            for ($i = 0; $i < $total - 1; $i++) {
                $bandera = 0;
                for ($j = 0; $j < $total - $i - 1; $j++) {
                    if ($unidad[$j]['cont'] < $unidad[$j + 1]['cont']) {
                        $aux = $unidad[$j];
                        $unidad[$j] = $unidad[$j + 1];
                        $unidad[$j + 1] = $aux;
                        $bandera = 1;
                    }
                }
                if ($bandera === 0)
                    break;
            }
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
            $pdf->SetTitle('INFORME GENERAL DE CARRERAS REALIZADAS');
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



            $htmlcontent = "<h1 style=\"text-align:center;\"> Informe General de Carreras Realizadas</h1> 
    <p>Desde $datepicker hasta $datepicker2 se realizaron " . count($carreras) . " carreras. </p> ";

//        Carreras realizadas por sector

            $htmlcontent .= "<h3>Número de carreras realizadas por sector</h3>
            <table style=\"font:normal 76%/150% \"Lucida Grande\", \"Lucida Sans Unicode\", Verdana, Arial, Helvetica, sans-serif;border-collapse:separate;border-spacing:0;border-left:1px solid #686868;    border-right:1px solid #686868;    border-bottom: 1px solid #686868;    color:#000;\">    
        <thead>
            <tr bgcolor=\"#ffff73\" style=\"font-weight:bold;line-height:normal;padding:0.25em 0.5em;text-align:left;\">
                <th style=\"border:1px solid #523A0B;border-width:1px 0; width:8%;\">N° </th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:60%;\">Sector</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:25%;\">Número de carreras</th>
            </tr>
        </thead>
        <tbody>";

            foreach ($unidad_sector as $i => $aux) {
                $htmlcontent.= "<tr ";
                if (fmod($i, 2))
                    $htmlcontent.="bgcolor=\"#fcfca0\"";

                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:60%;\">" . $aux['sector'] . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:25%;\">" . $aux['cont'] . "</td>
            </tr>";
            }

            $htmlcontent.= "</tbody>
        </table>";
//        Carreras por unidad
            $htmlcontent .= "<h3>Número de carreras realizadas por unidad</h3>
            <table style=\"font:normal 76%/150% \"Lucida Grande\", \"Lucida Sans Unicode\", Verdana, Arial, Helvetica, sans-serif;border-collapse:separate;border-spacing:0;border-left:1px solid #686868;    border-right:1px solid #686868;    border-bottom: 1px solid #686868;    color:#000;\">    
        <thead>
            <tr bgcolor=\"#ffff73\" style=\"font-weight:bold;line-height:normal;padding:0.25em 0.5em;text-align:left;\">
                <th style=\"border:1px solid #523A0B;border-width:1px 0; width:8%;\">N° </th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:60%;\">Unidad</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:25%;\">Número de carreras</th>
            </tr>
        </thead>
        <tbody>";

            foreach ($unidad as $i => $aux) {
                $htmlcontent.= "<tr ";
                if (fmod($i, 2))
                    $htmlcontent.="bgcolor=\"#fcfca0\"";

                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:60%;\">" . $aux['socio'] . " (" . $aux['unidad'] . ")</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:25%;\">" . $aux['cont'] . "</td>
            </tr>";
            }


            $htmlcontent.= "</tbody>
        </table>";

//        Carreras por código
            $htmlcontent .= "<h3>Número de carreras realizadas por código</h3>
            <table style=\"font:normal 76%/150% \"Lucida Grande\", \"Lucida Sans Unicode\", Verdana, Arial, Helvetica, sans-serif;border-collapse:separate;border-spacing:0;border-left:1px solid #686868;    border-right:1px solid #686868;    border-bottom: 1px solid #686868;    color:#000;\">    
        <thead>
            <tr bgcolor=\"#ffff73\" style=\"font-weight:bold;line-height:normal;padding:0.25em 0.5em;text-align:left;\">
                <th style=\"border:1px solid #523A0B;border-width:1px 0; width:8%;\">N° </th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:60%;\">Código</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:25%;\">Número de carreras</th>
            </tr>
        </thead>
        <tbody>";

            foreach ($codigo as $i => $aux) {
                $htmlcontent.= "<tr ";
                if (fmod($i, 2))
                    $htmlcontent.="bgcolor=\"#fcfca0\"";
                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:60%;\">" . $aux['cliente'] . " (" . $aux['codigo'] . ")</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:25%;\">" . $aux['cont'] . "</td>
            </tr>";
            }


            $htmlcontent.= "</tbody>
        </table>";

//Detalle de carreras
            $htmlcontent .= "<h3>Detalle de Carreras</h3>
            <table style=\"font:normal 76%/150% \"Lucida Grande\", \"Lucida Sans Unicode\", Verdana, Arial, Helvetica, sans-serif;border-collapse:separate;border-spacing:0;border-left:1px solid #686868;    border-right:1px solid #686868;    border-bottom: 1px solid #686868;    color:#000;\">    
        <thead>
            <tr bgcolor=\"#ffff73\" style=\"font-weight:bold;line-height:normal;padding:0.25em 0.5em;text-align:left;\">
                <th style=\"border:1px solid #523A0B;border-width:1px 0; width:8%;\">N° </th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:30%;\">Operador</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:12%;\">Código</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:13%;\">Vehiculo</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:14%;\">Fecha</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:8%;\">Hora</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:15%;\">Estado</th>
            </tr>
        </thead>
        <tbody>";

            foreach ($carreras as $i => $carrera) {
                $htmlcontent.= "<tr ";
                if (fmod($i, 2))
                    $htmlcontent.="bgcolor=\"#fcfca0\"";
                else
                    $htmlcontent.= "";

                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:30%;\">" . $carrera->getSfGuardUser()->getFirstName() . $carrera->getSfGuardUser()->getLastName() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:12%;\">" . $carrera->getNumCodigo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:13%;\">" . $carrera->getVehiculo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:14%;\">" . $carrera->getFecha() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:8%;\">" . substr($carrera->getHora(), 0, 5) . "</td>
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

    public function executeCalidadServicio() {
//Obtener los datos para el reporte
        if ($this->getUser()->getAttribute('esValido') === 'no')
            $this->redirect('reportes/error');
        else {
            $datepicker = $this->getUser()->getAttribute('datepicker');
            $datepicker2 = $this->getUser()->getAttribute('datepicker2');
            $date2 = Operaciones::convertirFecha($datepicker2);
            $date = Operaciones::convertirFecha($datepicker);
            $carreras = Doctrine_Core::getTable('carrera')
                    ->createQuery('a')->where('a.fecha>=?', $date)->andWhere('a.fecha<=?', $date2)
                    ->andWhereNotIn('a.calidad', array(-1, -2))
                    ->execute();

// Configurar el reporte        
            $doc_title = "Reporte de Carreras - Radiotaxi \"Benjamín Carrión\" ";
            $doc_subject = "Reporte de Carreras - Radiotaxi \"Benjamín Carrión\"";
            $doc_keywords = "Reporte";

            $config = sfTCPDFPluginConfigHandler::loadConfig();
            sfTCPDFPluginConfigHandler::includeLangFile($this->getUser()->getCulture());
//crear nuevo documento PDF (unidades del documento se establecen de forma predeterminada en milímetros)
            $pdf = new sfTCPDF("l", PDF_UNIT, PDF_PAGE_FORMAT, true);
// configurar la información del documento
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Cooperativa de taxis "Benjamín Carrión"');
            $pdf->SetTitle('INFORME GENERAL DE CARRERAS REALIZADAS');
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



            $htmlcontent = "<h1 style=\"text-align:center;\"> Informe de Calidad de Carreras Realizadas</h1> 
    <p>Carreras realizadas desde $datepicker hasta $datepicker2.</p> ";



//Detalle de carreras
            $htmlcontent .= "<h3>Detalle de Carreras</h3>
            <table style=\"font:normal 76%/150% \"Lucida Grande\", \"Lucida Sans Unicode\", Verdana, Arial, Helvetica, sans-serif;border-collapse:separate;border-spacing:0;border-left:1px solid #686868;    border-right:1px solid #686868;    border-bottom: 1px solid #686868;    color:#000;\">    
        <thead>
            <tr bgcolor=\"#ffff73\" style=\"font-weight:bold;line-height:normal;padding:0.25em 0.5em;text-align:left;\">
                <th style=\"border:1px solid #523A0B;border-width:1px 0; width:6%;text-align: center;\">N° </th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:10%;text-align: center;\">Código</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:10%;text-align: center;\">Unidad</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:11%;text-align: center;\">Fecha</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:10%;text-align: center;\">Hora</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:10%;text-align: center;\">Tiempo</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:11%;text-align: center;\">Atención</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:11%;text-align: center;\">Aproximado<br />Destino</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:11%;text-align: center;\">Llegada</th>
                <th style=\"border:1px solid #523A0B;border-width:1px 0;width:10%;text-align: center;\">Calificación<br />Cliente</th>
            </tr>
        </thead>
        <tbody>";

            foreach ($carreras as $i => $carrera) {
                $htmlcontent.= "<tr ";
                if (fmod($i, 2))
                    $htmlcontent.="bgcolor=\"#fcfca0\"";
                else
                    $htmlcontent.= "";

                $htmlcontent.=" style= \"border-color:#EBE5D9;padding:0.25em 0.5em;text-align:left;vertical-align:top;\"> 
                <td  style= \"border:1px solid #523A0B;border-width:1px 0;width:6%;\">" . ($i + 1) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:10%;\">" . $carrera->getNumCodigo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:10%;\">" . $carrera->getVehiculo() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getFecha() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:10%;\">" . substr($carrera->getHora(), 0, 5) . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:10%;text-align: center;\">" . $carrera->getTiempo() . " min</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getAtencion() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;text-align: center;\">" . $carrera->getTiempoAproxDest() . " min</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:11%;\">" . $carrera->getLlegada() . "</td>
                <td style= \"border:1px solid #523A0B;border-width:1px 0;width:10%;text-align: center;\">" . $carrera->getCalidad() . "</td>
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

}


