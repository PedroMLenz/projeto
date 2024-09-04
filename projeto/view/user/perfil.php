<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../../config/conexao.php';
require_once '../../model/timesModel.php';
require_once '../../model/usuarioModel.php';

// Cria instâncias dos modelos
$timeModel = new Time($pdo);
$usuarioModel = new Usuario($pdo);

// Obtém o ID do usuário logado da sessão
$userId = $_SESSION['user_id'];

// Obtém as informações do usuário
$usuario = $usuarioModel->buscarUsuarioPorId($userId);

// Obtém os times associados ao usuário onde ele é capitão
$timesComoCapitao = $timeModel->buscarTimesPorUsuario($userId);

// Obtém os times em que o usuário é jogador comum
$timesComoJogador = $timeModel->buscarTimesComoJogador($userId);

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

        <div class="profile-picture">
            <img src="../../public/img/user.jpg" alt="Ícone do Usuário">
        </div>
        <h1>@<?php echo htmlspecialchars($usuario['nome']); ?></h1>
        <h2>Informações Pessoais</h2>
        <p><strong>Nome de Usuário:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>

        <h2>Times como Capitão</h2>
        <?php if (count($timesComoCapitao) > 0): ?>
        <ul>
            <?php foreach ($timesComoCapitao as $time): ?>
            <li><?php echo htmlspecialchars($time['nome']); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Você não é capitão de nenhum time.</p>
        <?php endif; ?>

        <h2>Times como Jogador</h2>
        <?php if (count($timesComoJogador) > 0): ?>
        <ul>
            <?php foreach ($timesComoJogador as $time): ?>
            <li><?php echo htmlspecialchars($time['nome']); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Você não está participando como jogador em nenhum time.</p>
        <?php endif; ?>
        <a href="../team/manage.php" class="subb">Voltar</a><br>
        <a href="logout.php" class="subb">Logout</a>
    </div>
</body>

</html>