<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/forms.css">
    <title>Iniciar sesión</title>
</head>
    <?php
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        $usuario = $_POST['usuario'] ?? '';
        $contraseña = $_POST['contraseña'] ?? '';

        $usujson = 'data/users.json';
        $usuarios = file_exists($usujson) ? json_decode(file_get_contents($usujson), true): [];

        if(!isset($usuarios[$usuario])){

            echo '<script> alert("Usuario no registrado"); </script>';

        }elseif(!password_verify($contraseña, $usuarios[$usuario])){

            echo '<script> alert("contraseña incorrecta"); </script>';

        }else{
            $_SESSION['usuario'] = $usuario;
            header('Location: index.php');
            exit;
        }
    }
    ?>
<body>
    <form class="formulario" id="form" method="post">

        <h1>Iniciar sesion</h1>

        <label for="usuario">Usuario:</label>
        <br><input type="text" name="usuario" required>

        <br><label for="contraseña">Contraseña:</label>
        <br><input type="password" name="contraseña" required>

        <p>¿Aún no tienes cuenta? Registrate <a href="register.php">aquí</a></p>
        
        <br><button type="submit" class="btn-formulario">Iniciar sesion</button>

    </form>
    <script src="interactividad.js"></script>

</body>
</html>