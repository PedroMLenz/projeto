<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../view/user/login.php');
    exit();
}

// Inclui o arquivo de configuração para a conexão com o banco de dados
require_once '../../config/conexao.php';

// Inclui o modelo de times
require_once '../../model/timesModel.php';

// Cria uma instância do modelo de times
$timeModel = new Time($pdo);

// Obtém o ID do usuário logado da sessão
$userId = $_SESSION['user_id'];

// Obtém os times associados ao usuário onde ele é capitão
$times = $timeModel->buscarTimesPorUsuario($userId);

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
    <title>Gerenciar Times</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Gerenciar Times</h1>
        <div class="emcima">
            <button class="button" onclick="window.location.href='criar_editar.php'">Criar Novo Time</button>
        </div>

        <section class="times-capitao">
            <h2>Times como Capitão</h2>
            <table class="table1">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($times as $time): ?>
                    <?php
                        // Verifique se o usuário é o capitão do time
                        $isCapitao = $timeModel->verificarCapitao($userId, $time['id']);
                        ?>
                    <tr>
                        <td><?php echo htmlspecialchars($time['nome']); ?></td>
                        <td>
                            <div class="button-container">
                                <?php if ($isCapitao): ?>
                                <button class="button"
                                    onclick="window.location.href='gerenciar_membros.php?id=<?php echo htmlspecialchars($time['id']); ?>'">Gerenciar
                                    Membros</button><br>
                                <button class="button"
                                    onclick="window.location.href='criar_editar.php?id=<?php echo htmlspecialchars($time['id']); ?>'">Editar</button>
                                <form action="../../controller/timesController.php" method="post"
                                    style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($time['id']); ?>">
                                    <button type="submit" class="button"
                                        onclick="return confirm('Tem certeza que deseja excluir este time?');">Excluir</button>
                                </form>
                                <?php else: ?>
                                <a href="visualizar_equipe.php?id=<?php echo htmlspecialchars($time['id']); ?>"
                                    class="button">Visualizar Equipe</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="times-jogador">
            <h2>Times como Jogador</h2>
            <table class="table2">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timesComoJogador as $time): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($time['nome']); ?></td>
                        <td>
                            <button class="button"
                                onclick="window.location.href='visualizar_time.php?id=<?php echo htmlspecialchars($time['id']); ?>'">Visualizar
                                Equipe</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

</body>

<?php
// Inclui o rodapé
include_once('../../view/layouts/footer.php');
?>

</html>