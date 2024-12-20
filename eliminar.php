<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['usuario_id']; 

$consulta = "UPDATE usuarios SET estado = 'eliminado' WHERE id = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "El usuario ha sido eliminado correctamente.";

    session_destroy();
    header('Location: login.php');
    exit();
} else {
    echo "Hubo un error al eliminar el usuario.";
}
?>

