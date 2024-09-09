<?php
// Inclua o arquivo de configuração para a conexão com o banco de dados
require_once '../../config/conexao.php';

// Inclua o modelo de partidas e times
require_once '../../model/partidasModel.php';
require_once '../../model/timesModel.php';

// Crie instâncias dos modelos
$partidaModel = new Partida($pdo);
$timeModel = new Time($pdo);

// Verifique se a ID da partida foi passada
if (!isset($_GET['id'])) {
    header('Location: visualizar.php');
    exit();
}

$partidaId = $_GET['id'];

// Busque os detalhes da partida
$partida = $partidaModel->buscarPartidaPorId($partidaId);

// Verifique se a partida foi encontrada
if (!$partida) {
    header('Location: visualizar.php');
    exit();
}

// Busque todos os times para preencher o formulário
$times = $timeModel->buscarTodosOsTimes();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Partida</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container">
        <div class="editar">
            <h1>Editar Partida</h1>
            <form action="../../controller/partidasController.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($partida['id']); ?>">

                <label for="time_casa_id">Time da Casa:</label>
                <select name="time_casa_id" id="time_casa_id" required>
                    <?php foreach ($times as $time): ?>
                    <option value="<?php echo htmlspecialchars($time['id']); ?>"
                        <?php echo $partida['time_casa_id'] == $time['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($time['nome']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label for="time_visitante_id">Time Visitante:</label>
                <select name="time_visitante_id" id="time_visitante_id" required>
                    <?php foreach ($times as $time): ?>
                    <option value="<?php echo htmlspecialchars($time['id']); ?>"
                        <?php echo $partida['time_visitante_id'] == $time['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($time['nome']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label for="data">Data:</label>
                <input type="date" name="data" id="data"
                    value="<?php echo htmlspecialchars($partida['data_formatada']); ?>" required>
                <br>
                <label for="hora">Hora:</label>
                <input type="time" name="hora" id="hora"
                    value="<?php echo htmlspecialchars($partida['hora_formatada']); ?>" required>
                <br>
                <label for="local">Local:</label>
                <input type="text" name="local" id="local" value="<?php echo htmlspecialchars($partida['local']); ?>"
                    required>
                <br>
                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="Agendada" <?php echo ($partida['status'] == 'Agendada') ? 'selected' : ''; ?>>
                        Agendada</option>
                    <option value="Em Andamento"
                        <?php echo ($partida['status'] == 'Em Andamento') ? 'selected' : ''; ?>>Em Andamento</option>
                    <option value="Finalizada" <?php echo ($partida['status'] == 'Finalizada') ? 'selected' : ''; ?>>
                        Finalizada</option>
                </select>
                <label for="gols_casa">Gols time da casa:</label>
                <input type="text" name="gols_casa" id="gols_casa"
                    value="<?php echo htmlspecialchars($partida['gols_casa']); ?>" required>
                <br>
                <label for="gols_visitante">Gols time visitante:</label>
                <input type="text" name="gols_visitante" id="gols_visitante"
                    value="<?php echo htmlspecialchars($partida['gols_visitante']); ?>" required>
                <div class="espaco">
                    <button type="submit" class="button">Salvar Alterações</button>
                </div>
            </form>
        </div>
        <a href="gerenciar.php" class="subb">Voltar</a>
    </div>
    <?php
include_once('../../view/layouts/footer.php');
?>
</body>

</html>