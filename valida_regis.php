<?php 
session_start();
include('conexion.php');

if (isset($_POST['registrar'])) {
    $Nombre = htmlspecialchars(trim($_POST['Nombre']));
    $Apellido = htmlspecialchars(trim($_POST['Apellido']));
    $Email = htmlspecialchars(trim($_POST['Email']));
    $Telefono = htmlspecialchars(trim($_POST['Telefono']));
    $Contraseña = htmlspecialchars(trim($_POST['Contraseña']));
    
    // Verificar si el nombre de usuario o email ya existe
    $sql = "SELECT * FROM usuarios WHERE Nombre = ? OR Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $Nombre, $Email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>El nombre de usuario o email ya está en uso</h3>";
    } else {
        // Preparar la consulta SQL para insertar los datos en la tabla `usuarios`
        $sql = "INSERT INTO usuarios (Nombre, Apellido, Email, Telefono, Contraseña) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql); // Prepara la consulta SQL para insertar
        $stmt->bind_param("sssss", $Nombre, $Apellido, $Email, $Telefono, $Contraseña); // Asigna los parámetros
        $resultado = $stmt->execute();

        if ($resultado) {
            // Redirigir a la página principal
            header("Location: principal.php");
            exit(); // Asegúrate de añadir exit() después de header()
        } else {
            echo "<h3>Error ingresando los datos</h3>";
        }
    }

    // Cerrar la declaración y la conexión después de terminar
    $stmt->close();
    $conn->close();
}
?>