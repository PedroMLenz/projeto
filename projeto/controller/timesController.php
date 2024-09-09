<?php
session_start();

require_once '../config/conexao.php';
require_once '../model/timesModel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['action'];
    switch ($acao) {
        case 'create':
            criarTime($pdo);
            break;

        case 'edit':
            editarTime($pdo);
            break;

        case 'delete':
            deleteTime($pdo);
            break;

        case 'add_member':
            adicionarMembro($pdo);
        break;

        case 'remove_member':
            removerMembro($pdo);
        break;  

        case 'position':
            salvarPosicao($pdo);
        break;

        default:
            header('Location: ../view/team/pika.php');
            exit();
    }
}

/**
 * Função para lidar com a criação de um novo time.
 */
function criarTime($pdo)
{
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $capitaoId = $_SESSION['user_id'];

    $timeModel = new Time($pdo);

    if ($timeModel->verificarNomeExistente($nome)) {
        $_SESSION['error_message'] = 'Nome do time já está em uso.';

        header('Location: ../view/team/criar_editar.php?>');
exit();
}
$id = $timeModel->criarTime($nome, $capitaoId);

if ($id) {
$_SESSION['message'] = 'Time criado com sucesso!';
} else {
$_SESSION['error_message'] = 'Erro ao criar o time.';
}

header('Location: ../view/team/gerenciar.php');
exit();
}

/**
* Função para lidar com a edição de um time existente.
*/
function editarTime($pdo)
{
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nome = $_POST['nome'];
$timeModel = new Time($pdo);

// Verifica se o nome já está em uso, excluindo o próprio time em edição
if ($timeModel->verificarNomeExistente($nome, $id)) {
$_SESSION['error_message'] = 'Nome do time já está em uso.';
header('Location: ../view/team/criar_editar.php?id=' . $id);
exit();
}

$result = $timeModel->atualizarTime($id, $nome);

if ($result) {
$_SESSION['message'] = 'Time atualizado com sucesso!';
header('Location: ../view/team/gerenciar.php');
} else {
$_SESSION['error_message'] = 'Erro ao atualizar o time.';
header('Location: ../view/team/criar_editar.php?id=' . $id);
}
exit();
}

/**
* Função para lidar com a exclusão de um time.
*/
function deleteTime($pdo) {
// Verifica se o ID do time foi fornecido
if (!isset($_POST['id'])) {
$_SESSION['error_message'] = 'ID do time não fornecido.';
header('Location: ../view/team/gerenciar.php');
exit();
}

$timeId = intval($_POST['id']);

// Log do ID recebido para depuração
error_log("ID recebido para exclusão: " . $timeId);

// Cria uma instância do modelo de time
$timeModel = new Time($pdo);

// Verifica se o time existe
$time = $timeModel->buscarTimePorId($timeId);
if (!$time) {
$_SESSION['error_message'] = 'Time não encontrado.';
error_log("Time não encontrado para ID: " . $timeId);
header('Location: ../view/team/gerenciar.php');
exit();
}

// Tenta excluir o time
if ($timeModel->deletarTime($timeId)) {
$_SESSION['message'] = 'Time excluído com sucesso!';
error_log("Time excluído com sucesso para ID: " . $timeId);
} else {
$_SESSION['error_message'] = 'Erro ao excluir o time.';
error_log("Erro ao excluir o time para ID: " . $timeId);
}

header('Location: ../view/team/gerenciar.php');
exit();
}

/**
* Função para para salvar posição de jogador.
*/
function salvarPosicao($pdo)
{
$time_id = $_POST['time_id'];

// Verifica se 'positions' está definido e é um array
if (isset($_POST['positions']) && is_array($_POST['positions'])) {
$positions = $_POST['positions'];

$timeModel = new Time($pdo);

// Atualiza a posição para cada jogador
foreach ($positions as $user_id => $position) {
$timeModel->atualizarPosicaoJogador($user_id, $time_id, $position);
}
}

// Redireciona para a página original
header('Location: ../view/team/gerenciar_membros.php?id=' . $time_id);
exit(); // Sempre use exit após header para garantir que o script seja encerrado
}

/**
* Função para para aidionar novo jogador.
*/
function adicionarMembro($pdo){

$timeId = intval($_POST['time_id']);
$nome = trim($_POST['add_user_name']);

$timeModel = new Time($pdo);

if ($timeModel->adicionarMembro($nome, $timeId)) {
$_SESSION['message'] = 'Membro adicionado com sucesso!';
} else {
$_SESSION['error_message'] = 'Erro ao adicionar o membro ou membro já existente.';
}
header("Location: ../view/team/gerenciar_membros.php?id=$timeId");
exit();

}

/**
* Função para para remover jogador.
*/
function removerMembro($pdo) {
if (!isset($_POST['time_id']) || !isset($_POST['remove_user_id'])) {
error_log("Dados necessários não foram enviados: " . print_r($_POST, true));
$_SESSION['error_message'] = 'Dados necessários não foram enviados.';
header('Location: ../view/team/gerenciar_membros.php');
exit();
}

$timeId = intval($_POST['time_id']);
$userId = intval($_POST['remove_user_id']);

$timeModel = new Time($pdo);

if ($timeModel->removerUsuarioDoTime($userId, $timeId)) {
$_SESSION['message'] = 'Membro removido com sucesso!';
} else {
$_SESSION['error_message'] = 'Não é possível remover o capitão ou erro ao remover o membro.';
}

header('Location: ../view/team/gerenciar_membros.php?id=' . $timeId);
exit();
}
