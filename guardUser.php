<?php

require_once dirname(__FILE__) . '/../lib/sfGuardUserGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/sfGuardUserGeneratorHelper.class.php';

/**
 * sfGuardUser actions.
 *
 * @package    radiotaxi
 * @subpackage sfGuardUser
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserActions extends autoSfGuardUserActions {

    public function executeEdit(sfWebRequest $request) {
        $this->sf_guard_user = $this->getRoute()->getObject();
        $this->form = $this->configuration->getForm($this->sf_guard_user);
        $this->carreras = Doctrine_Core::getTable('carrera')
                ->createQuery('a')->where('a.id_operador=?', $this->sf_guard_user->getId())
                ->limit(2)
                ->execute();
        $this->codigos = Doctrine_Core::getTable('codigo')
                ->createQuery('a')->where('a.id_user=?', $this->sf_guard_user->getId())
                ->limit(2)
                ->execute();
        $this->solicitudes = Doctrine_Core::getTable('solicitud_carrera')
                ->createQuery('a')->where('a.id_cliente=?', $this->sf_guard_user->getId())
                ->limit(2)
                ->execute();
    }

    public function executeEditagregar(sfWebRequest $request) {
        $this->sf_guard_user = $this->getRoute()->getObject();
        $this->form = $this->configuration->getForm($this->sf_guard_user);
        $aux = 1;
        $this->getUser()->setAttribute('usercrear', $aux);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->sf_guard_user = $this->getRoute()->getObject();
        $this->form = $this->configuration->getForm($this->sf_guard_user);

        $nu = $this->getUser()->getAttribute('usercrear');
        if ($nu == 1) {
            $a = 0;
            $this->getUser()->setAttribute('usercrear', $a);
            $this->processForm($request, $this->form);
        } else {
            $this->processFormedit($request, $this->form);
        }

        $this->setTemplate('edit');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {

            try {
                $p = $this->gen_chars_no_dup();
                $form->getObject()->setPassword($p);

                try {
                    $sfguarduser = $form->save();

                    // Create a message                
                    $sms = "\n 
                        COOPERATIVA DE TAXIS \"BENJAMÍN CARRIÓN\"\n\n
                        Bienvenido al sistema de RADIO-TAXI en linea \n
                        Gracias por registrase con radio-taxi \"BENJAMÍN CARRIÓN\"\n\n
                        Su cuenta le permite acceder a muchas funciones 
                        importantes en nuestro sitio web, incluyendo:\n\n
                        -Solicitar el servicio de radio-taxi.
                        -Crear códigos para la utilizacion del servicio de radio-taxi.\n\n
                        Por favor, guarde este mensaje para futuras referencias.\n\n
                        Su información de la cuenta:\n
                        Cliente: " . $sfguarduser->getFirstName() . " " . $sfguarduser->getLastName() . "
                        Email: " . $sfguarduser->getEmailAddress() . "
                        Usuario: " . $sfguarduser->getUsername() . "
                        Contraseña: " . $p . "\n
                        Puede iniciar sesión en su cuenta y editar sus opciones de perfil en:   
                        " . sfConfig::get('app_url_pagina') . " \n\n\n\n
                        ";

                    $message = Swift_Message::newInstance()
                            ->setFrom(array(sfConfig::get('app_correo_admin') => sfConfig::get('app_nombre_admin')))
                            ->setTo(array($sfguarduser->getEmailAddress() => 'user'))
                            ->setSubject('Notificacion')
                            ->setBody($sms)
                    ;
                    // Send the message
                    if ($this->getMailer()->send($message)) {
                        $this->getUser()->setFlash('notice', 'EL correo se envio correctamente.');
                    } else {
                        $this->getUser()->setFlash('error', 'Error en el envio de correo.', false);
                    }
                    $this->getUser()->setFlash('notice', 'Datos agregados exitosamente');
                } catch (Exception $e) {
                    $this->getUser()->setFlash('error', 'Error en el envio de correo.', false);
                }
            } catch (Doctrine_Validator_Exception $e) {

                $errorStack = $form->getObject()->getErrorStack();

                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');

                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@sf_guard_user_new');
            } else {
                
            }
        } else {
            $this->getUser()->setFlash('error', 'El artículo no se ha guardado debido a algunos errores.', false);
        }
    }

    function gen_chars_no_dup($long = 8) {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        mt_srand((double) microtime() * 1000000);
        $i = 0;
        $pass = "user";
        while ($i != $long) {
            $rand = mt_rand() % strlen($chars);
            $tmp = $chars[$rand];
            $pass = $pass . $tmp;
            $chars = str_replace($tmp, "", $chars);
            $i++;
        }
        return strrev($pass);
    }

    protected function processFormedit(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
//            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {

                try {
//                    Envio de correos con SMTP configurando el php.ini [mail function]
//                    mail("eduarditolima@gmail.com","asuntillo","Este es el cuerpo del mensaje") ;

                    $sf_guard_user = $form->save();

                    // Create a message      
                    if ($sf_guard_user->getSexo() == "M") {
                        $gen = "masculino";
                    } else {
                        $gen = "femenino";
                    }

                    $sms = "\n 
                        COOPERATIVA DE TAXIS \"BENJAMÍN CARRIÓN\"\n\n
                        Sistema de RADIO-TAXI en linea \n
                        Los datos de su cuenta han sido actualizados exitosamente                        
                        \n\n
                        Usuario: " . $sf_guard_user->getUsername() . "
                        Nombre: " . $sf_guard_user->getFirstName() . "  
                        Apellido: " . $sf_guard_user->getLastName() . "
                        Sexo: " . $gen . "\n    
                        Email: " . $sf_guard_user->getEmailAddress() . "
                        Teléfono: " . $sf_guard_user->getTelefono() . "
                        Celular: " . $sf_guard_user->getTelefonoMovil() . "
                        \n\n

                        Le recordamos que su cuenta le permite acceder a muchas funciones 
                        importantes en nuestro sitio web, incluyendo:\n\n
                        -Solicitar el servicio de radio-taxi.
                        -Crear códigos para la utilizacion del servicio de radio-taxi.\n\n
                        Por favor, guarde este mensaje para futuras referencias.\n\n
                        Puede iniciar sesión en su cuenta y editar sus opciones de perfil en: \n                        
                        " . sfConfig::get('app_url_pagina') . " \n\n\n\n
                        ";

                    $message = Swift_Message::newInstance()
                            ->setFrom(array(sfConfig::get('app_correo_admin') => sfConfig::get('app_nombre_admin')))
                            ->setTo(array($sf_guard_user->getEmailAddress() => 'user'))
                            ->setSubject('Notificación')
                            ->setBody($sms)
                    ;

                    // Send the message
                    if ($this->getMailer()->send($message)) {
                        $this->getUser()->setFlash('notice', 'EL correo se envio correctamente.');
                    } else {
                        $this->getUser()->setFlash('error', 'Error en el envio de correo.', false);
                    }
                    $this->getUser()->setFlash('notice', 'Cambio exitoso de datos.');
                } catch (Exception $e) {
                    $this->getUser()->setFlash('error', 'Error en el envio de correo.', false);
                }
            } catch (Doctrine_Validator_Exception $e) {

                $errorStack = $form->getObject()->getErrorStack();

                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');

                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }

//            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $sf_guard_user)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@sf_guard_user_new');
            } else {
//                $this->getUser()->setFlash('notice', $notice);
//
//                $this->redirect(array('sf_route' => 'sf_guard_user_edit', 'sf_subject' => $sf_guard_user));
            }
        } else {
            $this->getUser()->setFlash('error', 'El usuario no se ha guardado debido a algunos errores.', false);
        }
    }

}

