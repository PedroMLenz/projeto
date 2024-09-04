<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>
    <?php
include_once('view/layouts/header.php');
?>
    <main class="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Bem-vindo ao OrganizeJá</h1>
            <p>Gerencie seus times e torneios de forma fácil e eficiente.</p>
            <div class="button-container-login">
                <a href="view/user/login.php" class="button-login">Login</a>
                <a href="view/user/register.php" class="button-login">Registrar</a>
            </div>
        </div>
    </main>
</body>

</html>
<?php
include_once('view/layouts/footer.php');
?>