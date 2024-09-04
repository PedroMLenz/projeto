<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

// Inclui o arquivo de configuração para a conexão com o banco de dados
require_once '../../config/conexao.php';

// Inclui o modelo de partidas
require_once '../../model/partidasModel.php';

// Cria uma instância do modelo de partidas
$partidaModel = new Partida($pdo);

// Obtém o ID do usuário logado da sessão
$userId = $_SESSION['user_id'];

// Obtém as partidas criadas pelo usuário logado
$partidasCriadas = $partidaModel->buscarPartidasPorCriador($userId);

// Obtém as partidas em que o usuário está envolvido (como jogador)
$partidasComoJogador = $partidaModel->buscarPartidasComoJogador($userId);

// Inclui o cabeçalho
include '../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Partidas</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Gerenciar Partidas</h1>
        <div class="cima">
            <button class="button" onclick="window.location.href='create.php'">Criar Nova Partida</button>
        </div>
        <h2>Partidas Criadas</h2>
        <table class="table1">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Local</th>
                    <th>Times</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partidasCriadas as $partida): ?>
                <tr>
                    <td><?php echo htmlspecialchars($partida['data_formatada']); ?></td>
                    <td><?php echo htmlspecialchars($partida['hora_formatada']); ?></td>
                    <td><?php echo htmlspecialchars($partida['local']); ?></td>
                    <td><?php echo htmlspecialchars($partida['time_casa_nome'] . ' vs ' . $partida['time_visitante_nome']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($partida['status']); ?></td>
                    <td>
                        <div class="button-container">
                            <a href="edit.php?id=<?php echo htmlspecialchars($partida['id']); ?>"
                                class="button">Editar</a>
                            <form action="../../controller/partidasController.php" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($partida['id']); ?>">
                                <button type="submit" class="button"
                                    onclick="return confirm('Tem certeza que deseja excluir esta partida?');">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Partidas Como Jogador</h2>
        <table class="table2">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Local</th>
                    <th>Times</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partidasComoJogador as $partida): ?>
                <tr>
                    <td><?php echo htmlspecialchars($partida['data_formatada']); ?></td>
                    <td><?php echo htmlspecialchars($partida['hora_formatada']); ?></td>
                    <td><?php echo htmlspecialchars($partida['local']); ?></td>
                    <td><?php echo htmlspecialchars($partida['time_casa_nome'] . ' vs ' . $partida['time_visitante_nome']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($partida['status']); ?></td>
                    <td>
                        <div class="button-container">
                            <a href="view.php?id=<?php echo htmlspecialchars($partida['id']); ?>"
                                class="button">Visualizar</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
include_once('../../view/layouts/footer.php');
?>
</body>
<?php
include_once('../../view/layouts/footer.php');
?>

</html>