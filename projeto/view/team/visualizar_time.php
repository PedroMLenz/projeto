<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Inclui o arquivo de configuração para a conexão com o banco de dados
require_once '../../config/conexao.php';

// Inclui o modelo de times
require_once '../../model/timesModel.php';

// Cria uma instância do modelo de times
$timeModel = new Time($pdo);

// Obtém o ID do time da URL
$timeId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtém os detalhes do time
$time = $timeModel->buscarTimePorId($timeId);

// Verifica se o time existe
if (!$time) {
    $_SESSION['error_message'] = 'Time não encontrado.';
    header('Location: manage.php');
    exit();
}

// Obtém a lista de membros do time
$teamMembers = $timeModel->buscarMembrosDoTime($timeId);

// Inclui o cabeçalho
include '../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Time</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Visualizar Time</h1>
        <section class="team-details">
            <h2>Detalhes do Time</h2>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($time['nome']); ?></p>
            <p><strong>Capitão:</strong> <?php echo htmlspecialchars($time['nome_capitao']); ?></p>
        </section>

        <section class="team-members">
            <h2>Membros do Time</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Posição</th>
                    </tr>
                </thead>
                <tbody id="members-table">
                    <?php foreach ($teamMembers as $member): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member['nome']); ?></td>
                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                        <td><?php echo htmlspecialchars($member['position']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <br>
        <a href="manage.php" class="subb">Voltar</a>
    </div>

    <!-- Inclui o rodapé -->
    <?php include_once('../../view/layouts/footer.php'); ?>
</body>

</html>