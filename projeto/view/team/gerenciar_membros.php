<?php
session_start();
require_once '../../config/conexao.php';
require_once '../../model/timesModel.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$timeId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$timeModel = new Time($pdo);

// Obtém as informações do time
$time = $timeModel->buscarTimePorId($timeId);

// Verifica se o time foi encontrado
if (!$time) {
    $_SESSION['error_message'] = 'Time não encontrado.';
    header('Location: ../team/gerenciar.php');
    exit();
}

// Verifica se o usuário é o capitão do time
$isCapitao = $timeModel->verificarCapitao($userId, $timeId);
if (!$isCapitao) {
    $_SESSION['error_message'] = 'Você não tem permissão para gerenciar este time.';
    header('Location: ../team/gerenciar.php');
    exit();
}

// Obtém as posições dos jogadores para o time correto
$playerPositions = $timeModel->getPlayerPositions($timeId);

// Lida com a remoção de membros
if (isset($_POST['remove_user_id'])) {
    $removeUserId = intval($_POST['remove_user_id']);
    if ($timeModel->removerUsuarioDoTime($removeUserId, $timeId)) {
        $_SESSION['message'] = 'Membro removido com sucesso!';
    } else {
        $_SESSION['error_message'] = 'Erro ao remover o membro.';
    }
    header("Location: gerenciar_membros.php?id=$timeId");
    exit();
}

// Recupera a lista de membros do time
$teamMembers = $timeModel->buscarMembrosDoTime($timeId);

// Inclui o cabeçalho
include '../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Membros do Time</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <script src="../../public/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Gerenciar Membros do <?php echo htmlspecialchars($time['nome']); ?></h1>

        <!-- Mensagens de feedback -->
        <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
        <?php elseif (isset($_SESSION['error_message'])): ?>
        <div class="message error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Formulário para adicionar membros -->
        <h2 class="sub">Adicionar Membro</h2>
        <form class="addMember" method="POST" action="../../controller/timesController.php">
            <input type="hidden" name="action" value="add_member">
            <input type="hidden" name="time_id" value="<?php echo htmlspecialchars($timeId); ?>">
            <label for="add_user_name">Nome:</label>
            <input type="text" name="add_user_name" id="add_user_name" required>
            <div class="espaco">
                <button type="submit" class="button">Adicionar Membro</button>
            </div>
        </form>

        <!-- Lista de membros do time -->
        <h2 class="sub">Membros do Time</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Posição</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teamMembers as $member): ?>
                <?php 
                    $position = isset($playerPositions[$member['id']]) ? $playerPositions[$member['id']] : 'Não Atribuído';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($member['nome']); ?></td>
                    <td><?php echo htmlspecialchars($position); ?></td>
                    <td>
                        <form method="POST" action="gerenciar_membros.php?id=<?php echo htmlspecialchars($timeId); ?>">
                            <input type="hidden" name="remove_user_id"
                                value="<?php echo htmlspecialchars($member['id']); ?>">
                            <button type="submit" class="button delete">Remover</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulário de gerenciamento de posições -->
        <h2 class="sub">Gerenciar Posições</h2>
        <form method="POST" action="../../controller/timesController.php" onsubmit="prepareForm(event)">
            <h1>Gerenciar posições</h1>
            <h2 class="sub">Arraste e solte o jogador para sua posição</h2>
            <div class="quadros">
                <!-- Pivô -->
                <div class="quadro" id="pivo" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <h3>Pivô</h3>
                    <div class="jogadores" data-position="pivo">
                        <?php foreach ($teamMembers as $member): ?>
                        <?php 
                                    $position = isset($playerPositions[$member['id']]) ? $playerPositions[$member['id']] : null;
                                ?>
                        <?php if ($position === 'pivo'): ?>
                        <div class="jogador" id="jogador-<?php echo $member['id']; ?>" draggable="true"
                            ondragstart="drag(event)">
                            <?php echo $member['nome']; ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Ala -->
                <div class="quadro" id="ala" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <h3>Ala</h3>
                    <div class="jogadores" data-position="ala">
                        <?php foreach ($teamMembers as $member): ?>
                        <?php 
                                    $position = isset($playerPositions[$member['id']]) ? $playerPositions[$member['id']] : null;
                                ?>
                        <?php if ($position === 'ala'): ?>
                        <div class="jogador" id="jogador-<?php echo $member['id']; ?>" draggable="true"
                            ondragstart="drag(event)">
                            <?php echo $member['nome']; ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Fixo -->
                <div class="quadro" id="fixo" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <h3>Fixo</h3>
                    <div class="jogadores" data-position="fixo">
                        <?php foreach ($teamMembers as $member): ?>
                        <?php 
                                    $position = isset($playerPositions[$member['id']]) ? $playerPositions[$member['id']] : null;
                                ?>
                        <?php if ($position === 'fixo'): ?>
                        <div class="jogador" id="jogador-<?php echo $member['id']; ?>" draggable="true"
                            ondragstart="drag(event)">
                            <?php echo $member['nome']; ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Goleiro -->
                <div class="quadro" id="goleiro" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <h3>Goleiro</h3>
                    <div class="jogadores" data-position="goleiro">
                        <?php foreach ($teamMembers as $member): ?>
                        <?php 
                                    $position = isset($playerPositions[$member['id']]) ? $playerPositions[$member['id']] : null;
                                ?>
                        <?php if ($position === 'goleiro'): ?>
                        <div class="jogador" id="jogador-<?php echo $member['id']; ?>" draggable="true"
                            ondragstart="drag(event)">
                            <?php echo $member['nome']; ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Jogadores não alocados -->
            <div class="jogadores-box">
                <?php foreach ($teamMembers as $member): ?>
                <?php 
                            $position = isset($playerPositions[$member['id']]) ? $playerPositions[$member['id']] : null;
                        ?>
                <?php if (!in_array($position, ['pivo', 'ala', 'fixo', 'goleiro'])): ?>
                <div class="jogador" id="jogador-<?php echo $member['id']; ?>" draggable="true"
                    ondragstart="drag(event)">
                    <?php echo $member['nome']; ?>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <input type="hidden" name="time_id" value="<?php echo $timeId; ?>">
            <input type="hidden" id="positions" name="positions">
            <input type="hidden" name="action" value="position">
            <div class="espaco">
                <button type="submit" class="button">Salvar posições</button>
            </div>
        </form>
        <a href="gerenciar.php" class="subb">Voltar</a>
    </div>
    <script src="../../public/js/script.js"></script>
    <?php
include_once('../../view/layouts/footer.php');
?>
</body>

</html>