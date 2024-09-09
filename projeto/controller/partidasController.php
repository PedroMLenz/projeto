<?php
session_start();
require_once '../config/conexao.php';
require_once '../model/partidasModel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$partidaModel = new Partida($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['action'];
    switch ($acao) {
        case 'create':
            criarPartida($pdo);
            break;

        case 'edit':
            editarPartida($pdo);
            break;

        case 'delete':
            deletarPartida($pdo);
            break;

        case 'atualizar_posicao':
            atualizarPosicao($pdo);
            break;

        default:
            header('Location: ../view/match/gerenciar.php');
            exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $partidaId = $_GET['id'];
    $partida = $partidaModel->buscarPartidaPorId($partidaId);
    include '../view/match/visualizar.php';
    exit();
}

function criarPartida($pdo) {
    global $partidaModel;

    $timeCasaId = $_POST['time_casa_id'];
    $timeVisitanteId = $_POST['time_visitante_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $local = $_POST['local'];
    $criadorId = $_SESSION['user_id'];
    $status = $_POST['status'];
    $golsCasa = $_POST['gols_casa'];
    $golsVisitante = $_POST['gols_visitante'];

    $partidaId = $partidaModel->criarPartida($timeCasaId, $timeVisitanteId, $data, $hora, $local, $criadorId, $status, $golsCasa, $golsVisitante);

    if ($partidaId) {
        $_SESSION['message'] = 'Partida criada com sucesso!';
        header("Location: ../view/match/visualizar.php?id=$partidaId");
    } else {
        $_SESSION['error_message'] = 'Erro ao criar a partida.';
        header('Location: ../view/match/gerenciar.php');
    }
    exit();
}

function editarPartida($pdo) {
    global $partidaModel;

    $partidaId = $_POST['id'];
    $timeCasaId = $_POST['time_casa_id'];
    $timeVisitanteId = $_POST['time_visitante_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $local = $_POST['local'];
    $status = $_POST['status'];
    $golsCasa = $_POST['gols_casa'];
    $golsVisitante = $_POST['gols_visitante'];

    if ($partidaModel->atualizarPartida($partidaId, $timeCasaId, $timeVisitanteId, $data, $hora, $local, $status, $golsCasa, $golsVisitante)) {
        $_SESSION['message'] = 'Partida atualizada com sucesso!';
    } else {
        $_SESSION['error_message'] = 'Erro ao atualizar a partida.';
    }
    header("Location: ../view/match/visualizar.php?id=$partidaId");
    exit();
}

function deletarPartida($pdo) {
    global $partidaModel;

    $partidaId = $_POST['id'];
    
    if ($partidaModel->deletarPartida($partidaId)) {
        $_SESSION['message'] = 'Partida deletada com sucesso!';
    } else {
        $_SESSION['error_message'] = 'Erro ao deletar a partida.';
    }
    header("Location: ../view/match/gerenciar.php");
    exit();
}

function atualizarPosicao($pdo) {
    $userId = $_POST['user_id'];
    $position = $_POST['position'];

    $stmt = $pdo->prepare("UPDATE times_jogadores SET position = :position WHERE user_id = :user_id");
    if ($stmt->execute(['position' => $position, 'user_id' => $userId])) {
        echo "Posição salva com sucesso.";
    } else {
        echo "Erro ao salvar a posição.";
    }
    exit();
}

