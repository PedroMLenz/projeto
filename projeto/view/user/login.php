<?php

session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Limpa a mensagem de erro após exibição
?>

<head>
    <title>OrganizeJá</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <script src="../../public/js/script.js" defer></script>
</head>

<body>
    <section>
        <div class="signin">
            <div class="content">
                <h2>Fazer login</h2>
                <form action="../../controller/usuarioController.php?action=login" class="form" method="post"
                    autocomplete="off">
                    <div class="inputBox">
                        <input type="email" required name="email" id="email" aria-label="Digite seu email"
                            aria-required="true"><i>Email</i>
                    </div>
                    <div class="inputBox">
                        <input type="password" required name="password" id="password" aria-label="Digite sua senha"
                            aria-required="true"><i>Senha</i>
                    </div>
                    <div id="error-message" class="error-message"></div>
                    <div class="links"> <a href="#"></a> <a href="../../view/user/register.php">Registro</a>
                    </div>
                    <div class="inputBox">
                        <input type="submit" value="Finalizar">
                    </div>
                    <script>
                    const errorMessage = "<?php echo $error_message; ?>";
                    </script>
            </div>
        </div>
    </section>
</body>

</html>