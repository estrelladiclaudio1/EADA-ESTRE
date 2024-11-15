<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión y es cliente
if ($_SESSION['rol'] != 'cliente') {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_SESSION['id_usuario'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_creacion = date('Y-m-d');
    $estado = 'abierto';

    $query = "INSERT INTO tickets (id_cliente, titulo, descripcion, fecha_creacion, estado) 
              VALUES ('$id_cliente', '$titulo', '$descripcion', '$fecha_creacion', '$estado')";
    
    if (mysqli_query($conn, $query)) {
        echo "Ticket creado exitosamente.";
    } else {
        echo "Error al crear el ticket: " . mysqli_error($conn);
    }
}
?>

<!-- Formulario de creación de ticket -->
<form method="POST">
    <input type="text" name="titulo" placeholder="Título del ticket" required>
    <textarea name="descripcion" placeholder="Descripción del pedido" required></textarea>
    <button type="submit">Crear Ticket</button>
</form>