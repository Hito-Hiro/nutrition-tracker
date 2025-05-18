<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/forms.css">
    <title>Registrate</title>
</head>
    <?php

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $usuario = $_POST['usuario'] ?? '';
        $contraseña = $_POST['contraseña'] ?? '';

        $usujson = 'data/users.json';
        $usuarios = file_exists($usujson) ? json_decode(file_get_contents($usujson), true): [];

        if(isset($usuarios[$usuario])){

            echo '<script> alert("Este usuario ya se encuentra registrado");</script>';

        }else{
            $usuarios[$usuario] = password_hash($contraseña, PASSWORD_DEFAULT);
            file_put_contents($usujson, json_encode($usuarios));

            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            exit;
        }
    }
    
    ?>
<body>
    <form class="formulario" id="form" method="post">

        <h1>Registro</h1>

        <label for="usuario">Nombre:</label>
        <br><input type="text" name="usuario" required>
        
        <br><label for="contraseña">Contraseña:</label>
        <br><input type="password" name="contraseña" required>

        <p>¿Ya tienes cuenta? Inicia sesión <a href="login.php">aquí</a></p>

        <br><button type="submit" class="btn-formulario">Registrate</button>

    </form>
    <script src="interactividad.js"></script>
</body>
</html>