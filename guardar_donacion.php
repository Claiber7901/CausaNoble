<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    echo "Error: No se encontró el usuario.";
    exit();
}

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $user_id = $_SESSION['user_id']; 
    $cantidad = $_POST['amount']; 

    $fecha_donacion = date('Y-m-d H:i:s');

    $query = "INSERT INTO Donacion (CantidadDonacion, UsuarioID, FechaDonacion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dis", $cantidad, $user_id, $fecha_donacion); 
    
    if ($stmt->execute()) {
        echo "Donación guardada exitosamente.";
    } else {
        echo "Error al guardar la donación: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se recibieron los datos necesarios.";
}
?>
