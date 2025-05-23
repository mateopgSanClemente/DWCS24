<?php
    
    /**
     * TODO: - Documentación.
     *       - Cambiar el mensaje de error del bloque catch por $e->getMessage().
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
            return "Fallo en la conexión: " . $e->getMessage(); 
        }
    }

    /**
     * TODO: - Documentación
     *       - Cambiar el mensaje de error del bloque catch por $e->getMessage().
     */
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
     * TODO: - Documentación
     *       - Cambiar el mensaje de error del bloque catch por $e->getMessage().
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
                grupo_sanguineo ENUM('0-', '0+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+') NOT NULL,
                codigo_postal CHAR(5) NOT NULL,
                telefono_movil CHAR(9) NOT NULL,
                CONSTRAINT pk_donantes PRIMARY KEY (id)
            );";
            $con_PDO->exec($sql);
            return "La tabla 'donantes' se creó con éxito.";
        }catch (PDOException $e){
            return "Se produjo un error en la creación de la tabla 'donantes': $e"; 
        }
    }

    /**
     * TODO: - Documentación
     *       - Cambiar el mensaje de error del bloque catch por $e->getMessage().
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
                return "La tabla 'historico' ya exixste.";
            }
            $sql = "CREATE TABLE historico (
                id_historico INTEGER AUTO_INCREMENT,
                id_donante INTEGER NOT NULL,
                fecha_donacion DATE NOT NULL,
                /**
                 *  Genera automaticamente la fecha de la próxima donación mediante un campo GENERATED:
                 *  DATE GENERATED ALWAYS AS (DATE_ADD(`fecha donacion`, INTERVAL 4 MONTH)) STORED
                 */
                proxima_donacion DATE NOT NULL,
                CONSTRAINT pk_historico PRIMARY KEY (id_historico),
                CONSTRAINT fk_historico_donantes FOREIGN KEY (id_donante) REFERENCES donantes(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            );";
            $con_PDO->exec($sql);
            return "La tabla 'historico' se creó con éxito.";
        }catch(PDOException $e){
            return "Se produjo un error en la creación de la tabla 'donantes': $e"; 
        }
    }
    
    /**
     * TODO: - Documentación
     *       - Cambiar el mensaje de error del bloque catch por $e->getMessage().
     */
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
                id_admin INTEGER UNSIGNED NOT NULL,
                nombre_administrador VARCHAR(50) NOT NULL,
                pass VARCHAR(200) NOT NULL,
                CONSTRAINT pk_administradores PRIMARY KEY (id_admin)
            );";
            $con_PDO->exec($sql);
            return "La tabla 'administradores se creó con éxito.";
        }catch(PDOException $e){
            return "Se produjo un error en la creación de la tabla 'administradores': $e";
        }
    }

    /**
     * TODO: Documentación
     */
    function insertar_donante($con_PDO, $nombre_donante, $apellido_donante, $edad_donante, $grupo_sanguineo, $codigo_postal, $telefono_movil){
        try{
            // Llevar a cabo una consulta preparada
            $sql = "INSERT INTO donantes(nombre, apellidos, edad, grupo_sanguineo, codigo_postal, telefono_movil)
            VALUES (:nombre, :apellido, :edad, :grupo, :codigo, :telefono);";
            $stmt = $con_PDO->prepare($sql);
            $stmt->execute([
                ":nombre" => $nombre_donante,
                "apellido" => $apellido_donante,
                ":edad" => $edad_donante,
                ":grupo" => $grupo_sanguineo,
                ":codigo" => $codigo_postal,
                ":telefono" => $telefono_movil
            ]);
            return [true, strval($con_PDO->lastInsertId())];
        }catch(PDOException $e){
            return [false, $e->getMessage()];
        }
    }

    /**
     * TODO: - Documentación
     */
    function seleccionar_info_donantes(PDO $con_PDO){
        try{
            $sql = "SELECT * FROM donantes;";
            // Método query() para consultas SELECT donde no tenemos parámetros
            $stmt = $con_PDO->query($sql);
            // Formato en el que queremos el array de datos
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // Devolver el resultado
            return [true, $stmt->fetchAll()];
        }catch(PDOException $e){
            return [false, $e->getMessage()];
        }
    }

    /**
     * TODO: - Documentación
     */
    function insertar_historico(PDO $con_PDO, $id_donante){
        try{
            // Obtener fecha actual (formato YYYY-MM-DD)
            $fecha_donacion = date("Y-m-d");
            // Calcular la fecha de la próxiam donación (4 meses depués, formato YYYY-MM-DD)
            $fecha_proxima_donacion = date("Y-m-d", strtotime("+4 month", strtotime($fecha_donacion)));
            // Consulta sql
            $sql = "INSERT INTO historico (id_donante, fecha_donacion, proxima_donacion) VALUES (:id_donante, :fecha_donacion, :fecha_proxima_donacion);";
            // Preparar
            $stmt = $con_PDO->prepare($sql);
            $stmt->bindParam(":id_donante", $id_donante);
            $stmt->bindParam(":fecha_donacion", $fecha_donacion);
            $stmt->bindParam(":fecha_proxima_donacion", $fecha_proxima_donacion);
            $stmt->execute();
            return [true, strval($con_PDO->lastInsertId())];
        }catch(PDOException $e){
            return [false, $e->getMessage()];
        }
    }

    /**
     * TODO:
     * - Documentacion
     */
    function eliminar_donante(PDO $con_PDO, $id_donante){
        try{
            // Sentencia SQL
            $sql = "DELETE FROM donantes WHERE donantes.id = :id_donante;";
            // Consulta preparada
            $stmt = $con_PDO->prepare($sql);
            // Vincular parámetro
            $stmt->bindParam(":id_donante", $id_donante, PDO::PARAM_INT);
            // Ejecutar consulta
            $stmt->execute();
            // Verificar que la eliminación se realizó correctamente
            if($stmt->rowCount() > 0){
                // Retornar mensaje de confirmación
                return [true, "El donante con ID $id_donante se eliminó correctamente."];
            }else{
                return [false, "No sé encontró un donante con ID: $id_donante"];
            }
        }catch(PDOException $e){
            return [false, "Ocurrio un error en la eliminación del donante: " . $e->getMessage()];
        }
    }

    /**
     * TODO:
     * - Documentación
     * - La página debe mostrar un encabezado con nombre, apellido, edad y grupo sanguíneo y todos los datos de tus donaciones ordenados por fecha decreciente.
     */
    function seleccionar_historico_donante(PDO $con_PDO, $id_donante){
        try{
            // Sentencia SQL
            $sql = "SELECT donantes.nombre, donantes.apellidos, donantes.edad, donantes.grupo_sanguineo, historico.fecha_donacion, historico.proxima_donacion
            FROM donantes
            INNER JOIN historico
            ON donantes.id = historico.id_donante
            WHERE donantes.id = :id_donante
            ORDER BY historico.fecha_donacion;";
            // Consulta preparada
            $stmt = $con_PDO->prepare($sql);
            // Vincular parámetros
            $stmt->bindParam(":id_donante", $id_donante);
            // Ejecutar consulta
            $stmt->execute();
            // Fetch mode 
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // Recoger resultados
            $resultado_consulta = $stmt->fetchAll();
            // Retornar
            return [true, $resultado_consulta];
        }catch(PDOException $e){
            return [false, $e->getMessage()];
        }
    }
?>