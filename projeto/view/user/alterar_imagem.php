<?php
session_start();
require_once '../../config/conexao.php';
require_once '../../model/timesModel.php';
require_once '../../model/usuarioModel.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Imagem de Perfil</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>

    <div class="container">
        <h1>Alterar Imagem de Perfil</h1>

        <form action="../../controller/usuarioController.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="atualizar_imagem">

            <div class="espaco">
                <label for="imagem">Escolha uma nova imagem de perfil:</label>
                <input type="file" name="imagem" id="imagem" accept="image/*" required>
            </div>

            <div class="espaco">
                <button type="submit" class="button">Alterar Imagem</button>
            </div>
        </form>

        <a href="../team/gerenciar.php" class="subb">Voltar</a><br>

    </div>

</body>

</html>