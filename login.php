<?php
session_start();
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['email']; 
    $password = $_POST['password'];

    $query = "SELECT * FROM login WHERE email = ?"; 
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        echo "Error en la preparación de la consulta: " . $conn->error;
        exit();
    }

    $stmt->bind_param("s", $login); 
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            
            $consulta_estado = "SELECT estado FROM usuarios WHERE id = ?";
            $stmt_estado = $conn->prepare($consulta_estado);
            $stmt_estado->bind_param("i", $usuario['id']);
            $stmt_estado->execute();
            $resultado_estado = $stmt_estado->get_result();
            $estado = $resultado_estado->fetch_assoc();

            if ($estado && $estado['estado'] == 'eliminado') {
                echo "Este usuario ha sido eliminado y no puede iniciar sesión.";
                session_destroy();  
                exit();
            }

            $_SESSION['usuario_id'] = $usuario['id']; 
            $_SESSION['usuario_nombre'] = $usuario['usuario']; 
            
            header('Location: consultar.php');
            exit();
        } else {
            echo "Credenciales incorrectas.";
        }
    } else {
        echo "El usuario no existe.";
    }

    $stmt->close();
}
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
                <li><a href="#">Registrar</a></li>
                <li><a href="consultar.php">Consultar</a></li>
                <li><a href="#">Nosotros</a></li>
            </ul>
        </nav>
        <!-- Contenido Principal -->
        <main class="main-content">
            <div class="login-form">
                <h2>Iniciar Sesión</h2>
                <form action="login.php" method="POST" enctype="multipart/form-data">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Ingresa tu email" required>
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                    
                    <button type="submit">Iniciar Sesión</button>
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
