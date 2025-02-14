<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
        exit;
    }
    // Redirigir a index en caso de que la persona que pretende acceder lo haga sin sen administrador
    if ($_SESSION["rol"] !== 1) {
        header("Location: index.php");
        exit;
    }
                        //Crear y comprobar conecxion
                        require_once "pdo.php";
                        // Crear conexión PDO
                        $resultado_conexion_PDO = conectar_PDO();
                        // Comprobar que la conexión fue exitosa
                        if(!$resultado_conexion_PDO["success"]){
                            $_SESSION["errorConPDO"] = $resultado_conexion_PDO["error"];
                            header ("Location: " . $_SERVER["HTTP_REFERER"]);
                            exit;
                        } else {
                            // Guardar la conexión en una variable
                            $conexion_PDO = $resultado_conexion_PDO["conexion"];
                            $id_usuario = $_GET["id"];
                            // Eliminar usuario
                            $resultado_eliminar_usuario = eliminar_usuario($conexion_PDO, $id_usuario);
                            // Mostrar resultado
                            if (!$resultado_eliminar_usuario["success"]){
                                $_SESSION["errorDel"] = $resultado_eliminar_usuario["mensaje"];
                                header("Location: " . $_SERVER["HTTP_REFERER"]);
                            } else {
                                $_SESSION["success"] = $resultado_eliminar_usuario["mensaje"];
                                header("Location: " . $_SERVER["HTTP_REFERER"]);
                            }
                            // Cerrar conexión
                            $conexion_PDO = null;
                        }
                    ?>
                </main>
            </div>
        </div>
        <?php include_once "footer.php";?>
    </body>
</html>