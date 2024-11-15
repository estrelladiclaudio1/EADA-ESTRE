<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión y es administrador
if ($_SESSION['rol'] != 'admin') {
    header("Location: login.php");
}

// Mostrar los tickets abiertos
$query = "SELECT * FROM tickets WHERE estado = 'abierto'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($ticket = mysqli_fetch_assoc($result)) {
        echo "<h3>{$ticket['titulo']}</h3>";
        echo "<p>{$ticket['descripcion']}</p>";
        
        // Asignar tarea
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_ticket = $ticket['id_ticket'];
            $id_desarrollador = $_POST['id_desarrollador'];
            $fecha_asignacion = date('Y-m-d');

            $query_tarea = "INSERT INTO tareas (id_ticket, id_desarrollador, fecha_asignacion, estado)
                            VALUES ('$id_ticket', '$id_desarrollador', '$fecha_asignacion', 'pendiente')";
            
            if (mysqli_query($conn, $query_tarea)) {
                echo "Tarea asignada exitosamente.";
            } else {
                echo "Error al asignar tarea: " . mysqli_error($conn);
            }
        }

        // Formulario para asignar tarea
        echo "<form method='POST'>";
        echo "<select name='id_desarrollador'>";
        
        // Mostrar lista de desarrolladores
        $query_desarrolladores = "SELECT * FROM usuarios WHERE rol = 'desarrollador'";
        $desarrolladores = mysqli_query($conn, $query_desarrolladores);
        while ($desarrollador = mysqli_fetch_assoc($desarrolladores)) {
            echo "<option value='{$desarrollador['id_usuario']}'>{$desarrollador['nombre_usuario']}</option>";
        }

        echo "</select>";
        echo "<button type='submit'>Asignar tarea</button>";
        echo "</form>";
    }
} else {
    echo "No hay tickets abiertos.";
}
?>