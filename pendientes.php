<?php
session_start();
include('conexion.php'); // Incluir el archivo de conexión
// Verificar que el id_cliente esté disponible en la sesión
if (isset($_SESSION['id_cliente'])) {
    $id_cliente = $_SESSION['id_cliente']; // Obtener el id_cliente desde la sesión
} else {
    echo "No se ha encontrado el cliente en la sesión.";
    exit; // Si no hay id_cliente en la sesión, salimos del script
}

// Consulta SQL para obtener los tickets del cliente específico
$sql = "SELECT * FROM tickets WHERE id_cliente = ?"; // Filtrar por id_cliente

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente); // Bind del parámetro, "i" indica un entero
$stmt->execute();

// Obtener el resultado de la consulta
$result = $stmt->get_result();

// Verificar si hay resultados y mostrarlos en una tabla
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr>";
    
    // Mostrar los nombres de las columnas como encabezado
    while ($field_info = $result->fetch_field()) {
        echo "<th>" . $field_info->name . "</th>";
    }
    echo "</tr>";
    
    // Mostrar cada fila de la tabla
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . $cell . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay tickets para este cliente.";
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>