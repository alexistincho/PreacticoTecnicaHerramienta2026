<?php

session_start();

include("../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = trim($_POST['nombre']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);

    /* Roles seleccionados */
    $roles = $_POST['roles'] ?? [];

    /* Validar campos obligatorios */

    if (empty($nombre) || empty($email) || empty($password)) {

        $_SESSION['error'] =
        "Nombre, Email y Contraseña son obligatorios.";

        header("Location: ../pages/registro.php");
        exit;

    }

    /* Validar que tenga al menos un rol */

    if (empty($roles)) {

        $_SESSION['error'] =
        "Debe seleccionar al menos un rol.";

        header("Location: ../pages/registro.php");
        exit;

    }

    /* Verificar email duplicado */

    $stmt = $conexion->prepare(
        "SELECT id_usuario
         FROM usuarios
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        $_SESSION['error'] =
        "El correo ya existe.";

        $stmt->close();

        header("Location: ../pages/registro.php");
        exit;

    }

    $stmt->close();

    /* Encriptar contraseña */

    $passwordHash =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );

    /* Insertar usuario */

    $stmt = $conexion->prepare(
        "INSERT INTO usuarios
        (nombre, email, contrasenia)
        VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "sss",
        $nombre,
        $email,
        $passwordHash
    );

    if ($stmt->execute()) {

        $id_usuario =
            $conexion->insert_id;

        $stmt->close();

        /* Guardar roles */

        foreach ($roles as $id_rol) {

            $stmtRol =
                $conexion->prepare(
                    "INSERT INTO usuario_roles
                     (id_usuario, id_rol)
                     VALUES (?, ?)"
                );

            $stmtRol->bind_param(
                "ii",
                $id_usuario,
                $id_rol
            );

            $stmtRol->execute();

            $stmtRol->close();

        }

        /* Registro exitoso */

        $_SESSION['registro'] =
        "Registro exitoso. Ahora puede iniciar sesión.";

        header(
            "Location: ../pages/login.php"
        );

        exit;

    } else {

        $_SESSION['error'] =
        "Error al registrar usuario.";

        header(
            "Location: ../pages/registro.php"
        );

        exit;

    }

} else {

    header(
        "Location: ../pages/registro.php"
    );

    exit;

}

?>