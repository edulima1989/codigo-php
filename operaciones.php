<?php

class Operaciones {

    public static function enviarSms($remite, $contenido, $destino) {
        $postUrl = "http://api2.joomfast.com/api/sendsms/xml";
        // datos en formato XML
        $xmlString =
                "<SMS>
                    <authentification>
                       <username>eduardolima</username>
                       <password>gngRKdS8</password>
                    </authentification>
                    <message>
                       <sender>$remite</sender>
                       <text> $contenido </text>
                    </message>
                    <recipients>
                       <GSM>$destino</GSM>
                    </recipients>
                 </SMS>";
        $fields = "XML=" . urlencode($xmlString);
// en este ejemplo, Solicitud POST fue realizada utilizando CURL de PHP
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//// respuesta de la solicitud POST
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    public static function is_Entero($cadena) {
        if (ctype_digit($cadena)) {
            return true;
        } else {
            return false;
        }
    }

    public static function convertirFecha($fechaString) {
        $objetoFecha = DateTime::createFromFormat("d/m/Y", $fechaString);
        return $mifecha = $objetoFecha->format("Y-m-d");
    }

    public static function esHoraReservacion($horareser, $horaactual) {
        $horai = substr($horareser, 0, 2);
        $mini = substr($horareser, 3, 2);
        $segi = substr($horareser, 6, 2);

        $horaf = substr($horaactual, 0, 2);
        $minf = substr($horaactual, 3, 2);
        $segf = substr($horaactual, 6, 2);

        $reser = ((($horai * 60) * 60) + ($mini * 60) + $segi);
        $actual = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

        $actual600 = $actual + 600;
        $actual540 = $actual + 540;
        if ($reser < $actual600 && $reser > $actual540)
            return true;
        else
            return false;
    }

    public static function validaCedula($strCedula) {
        //El decimo Digito es un resultante de un calculo
//Se trabaja con los 9 digitos de la cedula
//Cada digito de posicion impar se lo duplica, si este es mayor que 9 se resta 9 
//Se suman todos los resultados de posicion impar
//Ahora se suman todos los digitos de posicion par
//se suman los dos resultados
//se resta de la decena inmediata superior
//este es el decimo digito
//si la suma nos resulta 10, el decimo digito es cero    

        if (is_null($strCedula) || empty($strCedula)) {//compruebo si que el numero enviado es vacio o null
            //echo "Por Favor Ingrese la Cedula";
            $this->getUser()->setFlash('error', 'Campo cédula Vacio!');
        } else {//caso contrario sigo el proceso
            if (is_numeric($strCedula)) {
                $total_caracteres = strlen($strCedula); //  se suma el total de caracteres
                if ($total_caracteres == 10) {//compruebo que tenga 10 digitos la cedula
                    $nro_region = substr($strCedula, 0, 2); //extraigo los dos primeros caracteres de izq  a der
                    if ($nro_region >= 1 && $nro_region <= 24) {// compruebo a que region pertenece esta cedula//
                        $ult_digito = substr($strCedula, -1, 1); //extraigo el ultimo digito de la cedula
                        //extraigo los valores pares//
                        $valor2 = substr($strCedula, 1, 1);
                        $valor4 = substr($strCedula, 3, 1);
                        $valor6 = substr($strCedula, 5, 1);
                        $valor8 = substr($strCedula, 7, 1);
                        $suma_pares = ($valor2 + $valor4 + $valor6 + $valor8);
                        //extraigo los valores impares//
                        $valor1 = substr($strCedula, 0, 1);
                        $valor1 = ($valor1 * 2);
                        if ($valor1 > 9) {
                            $valor1 = ($valor1 - 9);
                        } else {
                            
                        }
                        $valor3 = substr($strCedula, 2, 1);
                        $valor3 = ($valor3 * 2);
                        if ($valor3 > 9) {
                            $valor3 = ($valor3 - 9);
                        } else {
                            
                        }
                        $valor5 = substr($strCedula, 4, 1);
                        $valor5 = ($valor5 * 2);
                        if ($valor5 > 9) {
                            $valor5 = ($valor5 - 9);
                        } else {
                            
                        }
                        $valor7 = substr($strCedula, 6, 1);
                        $valor7 = ($valor7 * 2);
                        if ($valor7 > 9) {
                            $valor7 = ($valor7 - 9);
                        } else {
                            
                        }
                        $valor9 = substr($strCedula, 8, 1);
                        $valor9 = ($valor9 * 2);
                        if ($valor9 > 9) {
                            $valor9 = ($valor9 - 9);
                        } else {
                            
                        }

                        $suma_impares = ($valor1 + $valor3 + $valor5 + $valor7 + $valor9);
                        $suma = ($suma_pares + $suma_impares);
                        $dis = substr($suma, 0, 1); //extraigo el primer numero de la suma
                        $dis = (($dis + 1) * 10); //luego ese numero lo multiplico  x 10, consiguiendo asi la decena inmediata superior
                        $digito = ($dis - $suma);
                        if ($digito == 10) {
                            $digito = '0';
                        } else {
                            
                        }//si la suma nos resulta 10, el decimo digito es cero
                        if ($digito == $ult_digito) {//comparo los digitos final y ultimo
                            $re = true;
                            //echo "Cedula Correcta";
                        } else {
                            $re = false;
                            //echo "Cedula Incorrecta";
                        }
                    } else {

                        $this->getUser()->setFlash('error', 'Este  Nro  de Cedula no corresponde a ninguna provincia del ecuador!');
                    }

                    //echo "Es un Numero y tiene el numero correcto de caracteres  que es de " . $total_caracteres . "<br>";
                } else {
                    $this->getUser()->setFlash('error', 'Este  Nro  de Cedula no corresponde a ninguna provincia del ecuador!');
//                    echo "Es un Numero y tiene  solo" . $total_caracteres;
                }
            } else {
                $this->getUser()->setFlash('error', 'Este  Nro  de Cedula no corresponde a ninguna provincia del ecuador!');
                //echo "Esta Cedula no corresponde a un Nro de Cedula de Ecuador";
            }
        }
        return $re;
    }

}
