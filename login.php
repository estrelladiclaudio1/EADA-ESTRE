<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($password == $user['password']) {
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];

            // Redirigir según el rol del usuario segun se identifique
            if ($user['rol'] == 'cliente') {
                header("Location: panel_cliente.php");
            } elseif ($user['rol'] == 'admin') {
                header("Location: panel_admin.php");
            } elseif ($user['rol'] == 'desarrollador') {
                header("Location: panel_desarrollador.php");
            }
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Correo electrónico no encontrado";
    }
}
?>


<form method="POST">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="password" placeholder="password" required>
    <button type="submit">Iniciar sesión</button>
</form>