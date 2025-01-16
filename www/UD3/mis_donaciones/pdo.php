<?php
    
    /**
     * TODO: Documentación.
     */
    function conexion_PDO($db_name="", $host="db", $db_usuario="root", $db_pass="test"){
        // Variables de entorno
        $db_usuario = $_ENV["MYSQL_USER"];
        $db_pass = $_ENV["MYSQL_PASSWORD"];        
        try{
            // Crear una instancia del objeto conexión PDO
            $con = new PDO("mysql:host=$host;dbname=$db_name", $db_usuario, $db_pass);
            // Forzar excepciones
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
        }catch (PDOException $e) {
            return "Fallo en la conexión: $e"; 
        }
    }

    function crear_db($con_PDO){
        try{
            // Comprobar si la base de datos existe. Preparamos y ejecutamos la consulta
            $sql_check = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'donacion';";
            $stmt = $con_PDO->prepare($sql_check);
            $stmt->execute();

            //Recuperar los resultados y guardarlos en un array
            $resultado_comprobacion = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultado_comprobacion = $stmt->fetchAll();

            // Comprobar los resultados
            if(count($resultado_comprobacion) >= 1){
                return "La base de datos 'donacion' ya existe.";
            }
            $sql = "CREATE DATABASE IF NOT EXISTS donacion;";
            $con_PDO->exec($sql);      

            return "La base de datos 'donación' se creó correctamente.";
        }catch (exception $e){
            return "Se produjo un error en la creación de la base de datos 'donación': $e";
        }
    }

    /**
     * TODO: Documentación
     */
    function crear_tabla_donantes($con_PDO){
        try{
            // Comprobar que la tabla donante existe
            $sql_check = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = 'donacion' AND TABLE_NAME = 'donantes';";
            $stmt = $con_PDO->prepare($sql_check);
            $stmt->execute();
            // Recuperar los resultados y guardarlos en un array
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultado_comprobacion = $stmt->fetchAll();
            if(count($resultado_comprobacion) >= 1){
                return "La tabla 'donantes' ya existe.";
            }
            $sql = "CREATE TABLE donantes (
                id INTEGER AUTO_INCREMENT,
                nombre VARCHAR(100) NOT NULL,
                apellidos VARCHAR(150) NOT NULL,
                edad INTEGER UNSIGNED NOT NULL CHECK (edad >= 18),
                `grupo sanguineo` ENUM('0-', '0+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+') NOT NULL,
                `codigo postal` CHAR(5) NOT NULL,
                `telefono movil` CHAR(9) NOT NULL,
                CONSTRAINT pk_donantes PRIMARY KEY (id)
            );";
            $con_PDO->exec($sql);
            return "La tabla 'donantes' se creó con éxito.";
        }catch (PDOException $e){
            return "Se produjo un error en la creación de la tabla 'donantes': $e"; 
        }
    }

    /**
     * TODO: Documentación
     */
    function crear_tabla_historico($con_PDO){
        try{
            // Comprobar que la tala historico existe
            $sql_check = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = 'donacion' AND TABLE_NAME = 'historico';";
            $stmt = $con_PDO->prepare($sql_check);
            $stmt->execute();
            // Comprobar los resultados
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultado_comprobacion = $stmt->fetchAll();
            if(count($resultado_comprobacion) >= 1){
                return "La tabla 'historicos' ya exixste.";
            }
            $sql = "CREATE TABLE historico (
                `id historico` INTEGER NOT NULL,
                `id donante` INTEGER NOT NULL,
                `fecha donacion` DATE NOT NULL,
                /**
                 *  Genera automaticamente la fecha de la próxima donación mediante un campo GENERATED:
                 *  DATE GENERATED ALWAYS AS (DATE_ADD(`fecha donacion`, INTERVAL 4 MONTH)) STORED
                 */
                `proxima donacion` DATE NOT NULL,
                CONSTRAINT pk_historico PRIMARY KEY (`id historico`),
                CONSTRAINT fk_historico_donantes FOREIGN KEY (`id donante`) REFERENCES donantes(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            );";
            $con_PDO->exec($sql);
            return "La tabla 'historico' se creó con éxito.";
        }catch(PDOException $e){
            return "Se produjo un error en la creación de la tabla 'donantes': $e"; 
        }
    }

    function crear_tabla_administradores($con_PDO){
        try{
            // Comprobar si la tabla administradores existe
            $sql_check = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = 'donacion' AND TABLE_NAME = 'administradores';";
            $stmt = $con_PDO->prepare($sql_check);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultado_comprobacion = $stmt->fetchAll();
            if(count($resultado_comprobacion) >= 1){
                return "La tabla 'administradores' ya existe.";
            }
            $sql = "CREATE TABLE administradores(
                `id admin` INTEGER UNSIGNED NOT NULL,
                `nombre de usuario` VARCHAR(50) NOT NULL,
                `pass` VARCHAR(200) NOT NULL,
                CONSTRAINT pk_administradores PRIMARY KEY (`id admin`)
            );";
            $con_PDO->exec($sql);
            return "La tabla 'administradores se creó con éxito.";
        }catch(PDOException $e){
            return "Se produjo un error en la creación de la tabla 'administradores': $e";
        }
    }
?>