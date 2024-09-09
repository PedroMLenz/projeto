<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../view/user/login.php');
    exit();
}

require_once '../../config/conexao.php';
require_once '../../model/timesModel.php';
require_once '../../model/usuarioModel.php';

$timeModel = new Time($pdo);
$usuarioModel = new Usuario($pdo);

$userId = $_SESSION['user_id'];

$usuario = $usuarioModel->buscarUsuarioPorId($userId);

// Inclui o cabeçalho
include '../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container-perfil">

        <section>
            <form action="../../controller/usuarioController.php" method="post">
                <input type="hidden" name="action" value="alterar">
                <p><label for="nome">Nome: </label><input type="text" name="nome" id="nome"
                        value="<?php echo $usuario['nome']; ?>"></p>
                <p><label for="email">Email: </label><input type="text" name="email" id="email"
                        value="<?php echo $usuario['email']; ?>"></p>

                <div class="profile-picture">
                    <label for="imagem">Imagem de Perfil:</label>
                    <input type="file" name="imagem" id="imagem">
                    <?php if (!empty($usuario['imagem'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['imagem']); ?>" alt="Imagem do Perfil"
                        class="imagem-perfil">
                    <?php else: ?>
                    <img src="../../public/img/user.jpg" alt="Imagem Padrão" class="imagem-perfil">
                    <?php endif; ?>
                </div>

                <p> <input type="submit" class="button">
                </p>
            </form>


        </section>
        <a href="../user/perfil.php" class="subb">Voltar</a><br>
        <a href="logout.php" class="subb">Logout</a>
    </div>
</body>

</html>