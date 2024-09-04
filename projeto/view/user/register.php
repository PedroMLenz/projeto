<?php
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Limpa a mensagem de erro após exibição
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <script src="../../public/js/script.js" defer></script>
</head>

<body>
    <section>
        <div class="signin">
            <div class="content">
                <h2>Fazer registro</h2>
                <form action="../../controller/usuarioController.php?action=register" class="form" method="post"
                    autocomplete="off">
                    <div class="inputBox">
                        <input type="text" required name="name" id="name" oninput="validarNomeUsuario()">
                        <i>Username</i>
                        <div id="nameError" class="error-message"></div>
                    </div>
                    <div class="inputBox">
                        <input type="text" required name="email" id="email" oninput="validarEmail()">
                        <i>Email</i>
                        <div id="emailError" class="error-message"></div>
                    </div>
                    <div class="inputBox">
                        <input type="password" required name="password" id="password" oninput="validarSenha()">
                        <i>Senha</i>
                        <div id="passwordError" class="error-message"></div>
                    </div>
                    <div class="links">
                        <a href="#"></a>
                        <a href="../../view/user/login.php">Login</a>
                    </div>
                    <div class="inputBox">
                        <input type="submit" value="Finalizar">
                    </div>
                </form>

            </div>
        </div>
        </div>
    </section>
</body>

</html>