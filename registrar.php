<?php
include('conexion.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreVariable = $_POST['nombre'];
    $apellidosVariable = $_POST['apellidos'];
    $edadVariable = $_POST['edad'];
    $sexoVariable = $_POST['sexo'];
    $direccionVariable = $_POST['direccion'];
    $ciudadVariable = $_POST['ciudad'];
    $cpVariable = $_POST['cp'];
    $usuario = $_POST['email'];
    $password = $_POST['password'];

   
    if ($edadVariable < 18) {
        echo "No puedes registrarte, debes ser mayor de edad.";
        exit();
    }

   
    $imagen = $_FILES['imagenFormulario'];
    $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';
    $carpetaFotografias = __DIR__ . '/fotografias/';
    if (move_uploaded_file($imagen['tmp_name'], $carpetaFotografias . $nombreImagen)) {
        echo "Imagen subida con éxito.";
    } else {
        echo "Error al subir la imagen.";
        exit();
    }


    $sql = "INSERT INTO usuarios (nombre, apellidos, edad, sexo, direccion, ciudad, cp, fotografia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $smtp = $conn->prepare($sql);
    if ($smtp) {
        $smtp->bind_param("ssisssss", $nombreVariable, $apellidosVariable, $edadVariable, 
                          $sexoVariable, $direccionVariable, $ciudadVariable, $cpVariable, $nombreImagen);
        if ($smtp->execute()) {
            echo "Usuario registrado con éxito.";
        } else {
            echo "Hubo un error al registrar el usuario.";
        }
        $smtp->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO login (usuario, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $usuario, $_POST['email'], $passwordHash);
    $stmt->execute();
    $stmt->close();

   
    header("Location: login.php");
    exit();  
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
                <li><a href="registrar.php">Registrar</a></li>
                <li><a href="consultar.php">Consultar</a></li>
                <li><a href="#">Nosotros</a></li>
            </ul>
        </nav>

        <!-- Contenido Principal -->
        <main class="tab-registrar">
    <div class="registrar-form">
        <h2>Iniciar Registro</h2> 
        <form method="POST" action="registrar.php" enctype="multipart/form-data">

            <div class="form-grid">
                <div class="form-item">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Ingresa tu email" required>
                </div>
                <div class="form-item">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
                <div class="form-item">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
                </div>
                <div class="form-item">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" required>
                </div>
                <div class="form-item">
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad">
                </div>
                <div class="form-item">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="hombre">Hombre</option>
                        <option value="mujer">Mujer</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Dirección" required>
                </div>
                <div class="form-item">
                    <label for="ciudad">Ciudad:</label>
                    <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad" required>
                </div>
                <div class="form-item">
                    <label for="cp">C.P.:</label>
                    <input type="text" id="cp" name="cp" required>
                </div>
                <div class="form-item">
                    <label for="fotografia">Imagen:</label>
                    <input type="file" id="fotografia" name="imagenFormulario" accept="image/jpeg, image/png" required>
                </div>
            </div>
            <div class="form-row">
                <button type="submit" class="submit-button">REGISTRARSE</button>
            </div>
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