<?php

/**
 * seguridad actions.
 *
 * @package    radiotaxi
 * @subpackage seguridad
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class seguridadActions extends sfActions {

    public function executeRespaldo(sfWebRequest $request) {
        
    }

    public function executeRespaldar(sfWebRequest $request) {
        $host = sfConfig::get('app_rbd_host');
        $user = sfConfig::get('app_rbd_user');
        $pass = sfConfig::get('app_rbd_password');
        $name = sfConfig::get('app_rbd_name');
        $tables = 'persona,sf_guard_permission,sf_guard_group,sf_guard_user
            ,cliente,peticion,propietario,sector,vehiculo,codigo,reservacion
            ,sin_codigo,carrera,solicitud_carrera,peticion_codigo
            ,sf_guard_user_group,sf_guard_user_permission
            ,sf_guard_group_permission
            ,sf_guard_remember_key
            ,sf_guard_forgot_password';
        $link = mysql_connect($host, $user, $pass);
        mysql_select_db($name, $link);

        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = mysql_query('SHOW TABLES');
            while ($row = mysql_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        $return = "DROP DATABASE IF EXISTS " . $name . "; 
            CREATE DATABASE " . $name . ";
            USE " . $name . ";
            ";
        $return .= "CREATE TABLE carrera (id BIGINT AUTO_INCREMENT, id_operador BIGINT NOT NULL, id_codigo BIGINT, id_vehiculo BIGINT NOT NULL, id_sincodigo BIGINT, tiempo SMALLINT NOT NULL, detalle LONGTEXT, fecha DATE NOT NULL, hora TIME NOT NULL, estado VARCHAR(255), tiempo_atencion SMALLINT, destino BIGINT, horaat TIME, tiempo_aprox_dest MEDIUMINT, hora_dest TIME, calidad SMALLINT, INDEX id_operador_idx (id_operador), INDEX id_codigo_idx (id_codigo), INDEX id_vehiculo_idx (id_vehiculo), INDEX id_sincodigo_idx (id_sincodigo), INDEX destino_idx (destino), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE cliente (id BIGINT AUTO_INCREMENT, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, sexo VARCHAR(1) DEFAULT 'M' NOT NULL, direccion VARCHAR(100), telefono VARCHAR(10) NOT NULL, telefonomovil VARCHAR(10), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE codigo (id BIGINT AUTO_INCREMENT, id_user BIGINT, id_cliente BIGINT, numero VARCHAR(5) NOT NULL UNIQUE, barrio BIGINT NOT NULL, calle1 VARCHAR(50) NOT NULL, calle2 VARCHAR(50), numcasa VARCHAR(8), observacion LONGTEXT NOT NULL, latitud VARCHAR(50) NOT NULL, longitud VARCHAR(50) NOT NULL, INDEX id_cliente_idx (id_cliente), INDEX id_user_idx (id_user), INDEX barrio_idx (barrio), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE persona (id BIGINT AUTO_INCREMENT, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, sexo VARCHAR(1) DEFAULT 'M' NOT NULL, direccion VARCHAR(100), telefono VARCHAR(10) NOT NULL, telefonomovil VARCHAR(10), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE peticion (id BIGINT AUTO_INCREMENT, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, sexo VARCHAR(1) DEFAULT 'M' NOT NULL, direccion VARCHAR(100), telefono VARCHAR(10) NOT NULL, telefonomovil VARCHAR(10), username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), email_address VARCHAR(50) NOT NULL UNIQUE, is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE peticion_codigo (id BIGINT AUTO_INCREMENT, id_user BIGINT, id_cliente BIGINT, numero VARCHAR(5) NOT NULL UNIQUE, barrio BIGINT NOT NULL, calle1 VARCHAR(50) NOT NULL, calle2 VARCHAR(50), numcasa VARCHAR(8), observacion LONGTEXT NOT NULL, latitud VARCHAR(50) NOT NULL, longitud VARCHAR(50) NOT NULL, INDEX id_cliente_idx (id_cliente), INDEX id_user_idx (id_user), INDEX barrio_idx (barrio), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE propietario (id BIGINT AUTO_INCREMENT, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, sexo VARCHAR(1) DEFAULT 'M' NOT NULL, direccion VARCHAR(100), telefono VARCHAR(10) NOT NULL, telefonomovil VARCHAR(10), cedula VARCHAR(10) NOT NULL UNIQUE, licencia VARCHAR(20), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE reservacion (id BIGINT AUTO_INCREMENT, id_codigo BIGINT NOT NULL, lunes TINYINT(1) DEFAULT '1', martes TINYINT(1) DEFAULT '1', miercoles TINYINT(1) DEFAULT '1', jueves TINYINT(1) DEFAULT '1', viernes TINYINT(1) DEFAULT '1', sabado TINYINT(1) DEFAULT '1', domingo TINYINT(1) DEFAULT '1', horario1 TIME NOT NULL, horario2 TIME, horario3 TIME, INDEX id_codigo_idx (id_codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE sector (id BIGINT AUTO_INCREMENT, nombre VARCHAR(50) NOT NULL, latitud VARCHAR(50) NOT NULL, longitud VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE sin_codigo (id BIGINT AUTO_INCREMENT, cliente VARCHAR(50) NOT NULL, telefono VARCHAR(10) NOT NULL, barrio BIGINT, calle1 VARCHAR(50) NOT NULL, calle2 VARCHAR(50), numcasa VARCHAR(8), observacion LONGTEXT, INDEX barrio_idx (barrio), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE solicitud_carrera (id BIGINT AUTO_INCREMENT, id_codigo BIGINT NOT NULL, id_cliente BIGINT NOT NULL, estado VARCHAR(255) DEFAULT 'nueva', tiempo SMALLINT NOT NULL, detalle LONGTEXT, fecha DATE NOT NULL, hora TIME NOT NULL, notificacion VARCHAR(2), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX id_codigo_idx (id_codigo), INDEX id_cliente_idx (id_cliente), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE vehiculo (id BIGINT AUTO_INCREMENT, id_propietario BIGINT NOT NULL, id_sector BIGINT, numero VARCHAR(10) NOT NULL UNIQUE, placa VARCHAR(7) NOT NULL UNIQUE, marca VARCHAR(20) NOT NULL, modelo VARCHAR(20) NOT NULL, ano VARCHAR(10) NOT NULL, INDEX id_propietario_idx (id_propietario), INDEX id_sector_idx (id_sector), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE sf_guard_forgot_password (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, unique_key VARCHAR(255), expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group_permission (group_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(group_id, permission_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_permission (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_remember_key (id BIGINT AUTO_INCREMENT, user_id BIGINT, remember_key VARCHAR(32), ip_address VARCHAR(50), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user (id BIGINT AUTO_INCREMENT, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, sexo VARCHAR(1) DEFAULT 'M' NOT NULL, direccion VARCHAR(100), telefono VARCHAR(10) NOT NULL, telefonomovil VARCHAR(10), username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), email_address VARCHAR(50) NOT NULL UNIQUE, is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE sf_guard_user_group (user_id BIGINT, group_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, group_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_permission (user_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, permission_id)) ENGINE = INNODB;
ALTER TABLE carrera ADD CONSTRAINT carrera_id_vehiculo_vehiculo_id FOREIGN KEY (id_vehiculo) REFERENCES vehiculo(id);
ALTER TABLE carrera ADD CONSTRAINT carrera_id_sincodigo_sin_codigo_id FOREIGN KEY (id_sincodigo) REFERENCES sin_codigo(id);
ALTER TABLE carrera ADD CONSTRAINT carrera_id_operador_sf_guard_user_id FOREIGN KEY (id_operador) REFERENCES sf_guard_user(id);
ALTER TABLE carrera ADD CONSTRAINT carrera_id_codigo_codigo_id FOREIGN KEY (id_codigo) REFERENCES codigo(id);
ALTER TABLE carrera ADD CONSTRAINT carrera_destino_sector_id FOREIGN KEY (destino) REFERENCES sector(id);
ALTER TABLE codigo ADD CONSTRAINT codigo_id_user_sf_guard_user_id FOREIGN KEY (id_user) REFERENCES sf_guard_user(id);
ALTER TABLE codigo ADD CONSTRAINT codigo_id_cliente_cliente_id FOREIGN KEY (id_cliente) REFERENCES cliente(id);
ALTER TABLE codigo ADD CONSTRAINT codigo_barrio_sector_id FOREIGN KEY (barrio) REFERENCES sector(id);
ALTER TABLE peticion_codigo ADD CONSTRAINT peticion_codigo_id_user_sf_guard_user_id FOREIGN KEY (id_user) REFERENCES sf_guard_user(id);
ALTER TABLE peticion_codigo ADD CONSTRAINT peticion_codigo_id_cliente_cliente_id FOREIGN KEY (id_cliente) REFERENCES cliente(id);
ALTER TABLE peticion_codigo ADD CONSTRAINT peticion_codigo_barrio_sector_id FOREIGN KEY (barrio) REFERENCES sector(id);
ALTER TABLE reservacion ADD CONSTRAINT reservacion_id_codigo_codigo_id FOREIGN KEY (id_codigo) REFERENCES codigo(id);
ALTER TABLE sin_codigo ADD CONSTRAINT sin_codigo_barrio_sector_id FOREIGN KEY (barrio) REFERENCES sector(id);
ALTER TABLE solicitud_carrera ADD CONSTRAINT solicitud_carrera_id_codigo_codigo_id FOREIGN KEY (id_codigo) REFERENCES codigo(id);
ALTER TABLE solicitud_carrera ADD CONSTRAINT solicitud_carrera_id_cliente_sf_guard_user_id FOREIGN KEY (id_cliente) REFERENCES sf_guard_user(id);
ALTER TABLE vehiculo ADD CONSTRAINT vehiculo_id_sector_sector_id FOREIGN KEY (id_sector) REFERENCES sector(id);
ALTER TABLE vehiculo ADD CONSTRAINT vehiculo_id_propietario_propietario_id FOREIGN KEY (id_propietario) REFERENCES propietario(id);
ALTER TABLE sf_guard_forgot_password ADD CONSTRAINT sf_guard_forgot_password_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_remember_key ADD CONSTRAINT sf_guard_remember_key_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
";
        //cycle through
        foreach ($tables as $table) {
            $result = mysql_query('SELECT * FROM ' . $table);
            $num_fields = mysql_num_fields($result);

//            $return.= 'DROP TABLE  IF EXISTS ' . $table . ';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
//            $return.= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $return.= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            if ($row[$j] == NULL)
                                $return.= 'NULL';
                            else {
                                $return.= '\'' . $row[$j] . '\'';
                            }
                        } else {
                            $return.= 'NULL';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return.= ',';
                        }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }

        //save file
        date_default_timezone_set("America/Guayaquil");
        $archivo = 'respaldo/db-backup-radiotaxi-' . date("Y_m_d") . '-' . date("H_i_s") . '.sql';
        $handle = fopen($archivo, 'w+');
        fwrite($handle, $return);
        fclose($handle);

        try {
            // Create a message                
            $sms = "\n 
                        COOPERATIVA DE TAXIS \"BENJAMÍN CARRIÓN\"\n\n
                        Respaldo de base de datos \n"
            ;

            $message = Swift_Message::newInstance()
                    ->setFrom(array(sfConfig::get('app_correo_admin') => sfConfig::get('app_nombre_admin')))
                    ->setTo(array($this->getUser()->getGuardUser()->getEmailAddress() => 'user'))
                    ->setSubject('Notificacion')
                    ->setBody($sms)
                    ->attach(\Swift_Attachment::fromPath($archivo))
            ;

            // Send the message
            if ($this->getMailer()->send($message)) {

                $this->getUser()->setFlash('notice', 'EL respado de la base de datos se realizo con exito. La copia del archivo de respaldo se ha enviado a su correo electrónico.');
            } else {
                $this->getUser()->setFlash('error', 'Error en el envio del respaldo.', false);
            }
        } catch (Exception $e) {
            $this->getUser()->setFlash('error', 'Error en el envio del respaldo.', false);
        }
        $this->getUser()->setAttribute('urlArchivo', $archivo);

        $this->redirect('seguridad/respaldado');
    }

    public function executeRespaldado(sfWebRequest $request) {
        $archi = $this->getUser()->getAttribute('urlArchivo');
        unlink($archi);
        $this->setTemplate('respaldo');
    }

    public function executeRestaurar(sfWebRequest $request) {

        error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR);

        $servername = sfConfig::get('app_rbd_host'); //localhost 
        $dbusername = sfConfig::get('app_rbd_user'); //root 
        $dbpassword = sfConfig::get('app_rbd_password'); //tupass 
        $dbname = sfConfig::get('app_rbd_name'); //tuclave 

        $link = mysql_connect($servername, $dbusername, $dbpassword);
        if (!$link) {
            die('No puedo Conectarme al Administrador MySQL ' . mysql_error());
        }
        mysql_select_db($dbname, $link)
                or die('No puedo seleccionar la base de datos ' . mysql_error());

        if (!isset($_FILES["ficheroDeCopia"])) {
            
        } else {
            $archivoRecibido = $_FILES["ficheroDeCopia"]["tmp_name"];
            $destino = "respaldo/ficheroParaRestaurar.sql";

            if (!move_uploaded_file($archivoRecibido, $destino)) {
                $mensaje = 'Error: Debe seleccionar el archivo de copia con la extencion ".sql"' . $archivoRecibido;
                $this->getUser()->setFlash('error', $mensaje, false);
            } else {
                $file_content = file($destino);
                $sqlSchema = "";
                foreach ($file_content as $sql_line) {
                    if (trim($sql_line) != "" && strpos($sql_line, "--") == false) {
                        $sqlSchema .= $sql_line;
                    }
                }
                $sqlSchema = explode(';', $sqlSchema);
                for ($i = 0; $i < count($sqlSchema); $i++) {
                    mysql_query($sqlSchema[$i], $link);
                }
                $mensaje2 = $executa . " La base de datos se ha restaurado completamente a partir de la copia de seguridad.";
                $this->getUser()->setFlash('notice', $mensaje2);
                unlink($destino);
            }
        }
    }

}
