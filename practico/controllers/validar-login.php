<?php

session_start();

include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"] ?? "";
    $contraseña = $_POST["contraseña"] ?? "";

    if (empty($email) || empty($contraseña)) {

        $_SESSION["error"] =
        "Por favor ingrese email y contraseña.";

        header("Location: ../view/login.php");
        exit();

    }

    $stmt = $conexion->prepare(
        "SELECT id_usuario, nombre, email, contrasenia
         FROM usuarios
         WHERE email = ?
         LIMIT 1"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($usuario = $resultado->fetch_assoc()) {

        if (
            password_verify(
                $contraseña,
                $usuario['contrasenia']
            )
        ) {

            $_SESSION['id_usuario'] =
                $usuario['id_usuario'];

            $_SESSION['nombre'] =
                $usuario['nombre'];

            $_SESSION['email'] =
                $usuario['email'];

            header("Location: ../index.php");
            exit();

        } else {

            $_SESSION["error"] =
                "Contraseña incorrecta.";

            header("Location: ../view/login.php");
            exit();

        }

    } else {

        $_SESSION["error"] =
        "No existe usuario con ese email.";

        header("Location: ../view/login.php");
        exit();

    }

}
?>