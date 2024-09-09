<?php
require_once '../../config/conexao.php';
require_once '../../model/timesModel.php';

$timeModel = new Time($pdo);

$timeId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$timeData = null;

if ($timeId > 0) {
    $time = $timeModel->buscarTimePorId($timeId);
}

include '../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $timeId > 0 ? 'Mudar nome do time' : 'Criar Novo Time'; ?></title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <script src="../../public/js/script.js" defer></script>
</head>

<body>
    <main>
        <div class="container">
            <h1><?php echo $timeId > 0 ? 'Mudar nome do time' : 'Criar Novo Time'; ?></h1>

            <!-- Mensagens de feedback -->
            <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message']); ?>
            <?php elseif (isset($_SESSION['error_message'])): ?>
            <div class="message error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="../../controller/timesController.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($time['id'] ?? ''); ?>">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($time['nome'] ?? ''); ?>"
                    required>
                <div class="espaco">
                    <button class="button" type="submit" name="action"
                        value="<?php echo $timeId > 0 ? 'edit' : 'create'; ?>">
                        <?php echo $timeId > 0 ? 'Atualizar' : 'Criar'; ?>
                    </button>
                </div>

            </form>
            <a href="gerenciar.php" class="subb">Voltar</a>
        </div>

    </main>
    <?php
include_once('../../view/layouts/footer.php');
?>
</body>

</html>