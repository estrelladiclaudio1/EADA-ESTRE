<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión y es desarrollador
if ($_SESSION['rol'] != 'desarrollador') {
    header("Location: login.php");
    exit;
}

$id_desarrollador = $_SESSION['id_usuario'];

// Mostrar las tareas asignadas al desarrollador, incluyendo la descripción del ticket
$query = "SELECT tareas.*, tickets.descripcion 
          FROM tareas 
          JOIN tickets ON tareas.id_ticket = tickets.id_ticket 
          WHERE tareas.id_desarrollador = '$id_desarrollador'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($tarea = mysqli_fetch_assoc($result)) {
        echo "<h3>Tarea del ticket: {$tarea['id_ticket']}</h3>";
        echo "<p>{$tarea['descripcion']}</p>"; // Descripción del ticket obtenida de la tabla tickets
        echo "<p>Estado: {$tarea['estado']}</p>";

        // Actualizar estado de la tarea
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['estado']) && isset($_POST['id_tarea']) && $_POST['id_tarea'] == $tarea['id_tarea']) {
            $nuevo_estado = $_POST['estado'];
            $query_update = "UPDATE tareas SET estado = '$nuevo_estado' WHERE id_tarea = '{$tarea['id_tarea']}'";

            if (mysqli_query($conn, $query_update)) {
                echo "Estado actualizado.";
            } else {
                echo "Error al actualizar: " . mysqli_error($conn);
            }
        }

        // Formulario para actualizar el estado
        echo "<form method='POST'>";
        echo "<input type='hidden' name='id_tarea' value='{$tarea['id_tarea']}'>";
        echo "<select name='estado'>";
        echo "<option value='pendiente'>Pendiente</option>";
        echo "<option value='en progreso'>En Progreso</option>";
        echo "<option value='completada'>Completada</option>";
        echo "</select>";
        echo "<button type='submit'>Actualizar Estado</button>";
        echo "</form>";
    }
} else {
    echo "No tienes tareas asignadas.";
}
?>