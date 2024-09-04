<?php
session_start();
require_once '../../config/conexao.php';
require_once '../../model/timesModel.php';

// Cria uma instância do modelo de times
$timeModel = new Time($pdo);

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

// Obtém todos os times
$times = $timeModel->buscarTodosOsTimes();

// Inclui o cabeçalho
include '../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Partida</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Criar Partida</h1>

        <form method="POST" action="../../controller/partidasController.php">
            <input type="hidden" name="action" value="create">

            <label for="time_casa_id">Time da Casa:</label>
            <select name="time_casa_id" id="time_casa_id" required>
                <?php foreach ($times as $time): ?>
                <option value="<?php echo htmlspecialchars($time['id']); ?>">
                    <?php echo htmlspecialchars($time['nome']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="time_visitante_id">Time Visitante:</label>
            <select name="time_visitante_id" id="time_visitante_id" required>
                <?php foreach ($times as $time): ?>
                <option value="<?php echo htmlspecialchars($time['id']); ?>">
                    <?php echo htmlspecialchars($time['nome']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="data">Data:</label>
            <input type="date" name="data" id="data" required>

            <label for="hora">Hora:</label>
            <input type="time" name="hora" id="hora" required>

            <label for="local">Local:</label>
            <input type="text" name="local" id="local" required>

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="Agendada">Agendada</option>
                <option value="Em Andamento">Em Andamento</option>
                <option value="Finalizada">Finalizada</option>
            </select>

            <label for="gols_casa">Gols time da casa:</label>
            <input type="text" name="gols_casa" id="gols_casa" required>

            <label for="gols_visitante">Local:</label>
            <input type="text" name="gols_visitante" id="gols_visitante" required>
            <div class="espaco">
                <button type="submit" class="button">Criar Partida</button>
            </div>
        </form>
        <a href="manage.php" class="subb">Voltar</a>
    </div>
    <?php
include_once('../../view/layouts/footer.php');
?>
</body>

</html>