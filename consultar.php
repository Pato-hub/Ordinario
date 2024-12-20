<?php
session_start();
include('conexion.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['usuario_id']; 

// Consultar 
$consulta = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Registro de Clientes</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="container">
        <!-- Banner -->
        <header class="banner">
            <h1>Sistema de Registro de Clientes</h1>
        </header>

        <!-- Menú de Navegación -->
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="registrar.php">Registrar</a></li>
                <li><a href="consultar.php">Consultar</a></li>
                <li><a href="#">Nosotros</a></li>
            </ul>
        </nav>

        <!-- Contenido Principal -->
        <main class="tab-registrar">
            <div class="registrar-form">
                <h2>Información del Usuario</h2>
                <p>Nombre: <?php echo htmlspecialchars($usuario['nombre']); ?></p>
                <p>Apellidos: <?php echo htmlspecialchars($usuario['apellidos']); ?></p>
                <p>Edad: <?php echo htmlspecialchars($usuario['edad']); ?></p>
                <p>Sexo: <?php echo htmlspecialchars($usuario['sexo']); ?></p>
                <p>Direccion: <?php echo htmlspecialchars($usuario['direccion']); ?></p>
                <p>Ciudad: <?php echo htmlspecialchars($usuario['ciudad']); ?></p>
                <p>C.P.: <?php echo htmlspecialchars($usuario['cp']); ?></p>

                <img src="fotografias/<?php echo htmlspecialchars($usuario['fotografia']); ?>" width="100px" height="100px">


                <form action="actualizar.php" method="GET">
                    <button type="submit" id="actualizar">ACTUALIZAR MIS DATOS</button>
                </form>

                <form action="eliminar.php" method="POST">
                    <button type="submit" id="baja">DARME DE BAJA</button>
                </form>
                
                <form action="cerrar.php" method="POST">
                  <button type="submit" id="cerrar">Cerrar Sesión</button>
                 </form>
               
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2024 - Todos los derechos reservados</p>
        </footer>
    </div>
</body>
</html>
