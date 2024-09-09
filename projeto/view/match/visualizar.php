<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/user/login.php');
    exit();
}

// Inclui o arquivo de configuração para a conexão com o banco de dados
require_once '../../config/conexao.php';

// Inclui o modelo de partidas e times
require_once '../../model/partidasModel.php';
require_once '../../model/timesModel.php';

$userId = $_SESSION['user_id'];

// Cria instâncias dos modelos
$partidaModel = new Partida($pdo);
$timeModel = new Time($pdo);

// Obtém o ID da partida a ser visualizada
$partidaId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verifica se a partida existe
$partida = $partidaModel->buscarPartidaPorId($partidaId);
if (!$partida) {
    $_SESSION['error_message'] = 'Partida não encontrada.';
    header('Location: gerenciar.php');
    exit();
}

// Obtém os detalhes dos times
$timeCasa = $timeModel->buscarTimePorId($partida['time_casa_id']);
$timeVisitante = $timeModel->buscarTimePorId($partida['time_visitante_id']);

// Inclui o cabeçalho
include '../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Partida</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <script src="../../public/js/script.js"></script>
</head>

<body>
    <div class="container">
        <h1>Detalhes da Partida</h1>

        <h2><?php echo htmlspecialchars($timeCasa['nome']) . ' ' . htmlspecialchars($partida['gols_casa']); ?> x
            <?php echo htmlspecialchars($partida['gols_visitante']) . ' ' . htmlspecialchars($timeVisitante['nome']); ?>
        </h2>
        <p><strong>Data:</strong> <?php echo htmlspecialchars($partida['data_formatada']); ?></p>
        <p><strong>Hora:</strong> <?php echo htmlspecialchars($partida['hora_formatada']); ?></p>
        <p><strong>Local:</strong> <?php echo htmlspecialchars($partida['local']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($partida['status']); ?></p>

        <h2>Jogadores do Time Casa</h2>
        <ul>
            <?php 
            $jogadoresCasa = $timeModel->buscarMembrosDoTime($timeCasa['id']);
            foreach ($jogadoresCasa as $jogador): ?>
            <li><?php echo htmlspecialchars($jogador['nome']); ?></li>
            <?php endforeach; ?>
        </ul>
        <h2>Jogadores do Time Visitante</h2>
        <ul>
            <?php 
            $jogadoresVisitante = $timeModel->buscarMembrosDoTime($timeVisitante['id']);
            foreach ($jogadoresVisitante as $jogador): ?>
            <li><?php echo htmlspecialchars($jogador['nome']); ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="gerenciar.php" class="subb">Voltar</a>
    </div>
    <?php
include_once('../../view/layouts/footer.php');
?>
</body>

</html>