<?php
session_start();
require_once '../config/conexao.php';
require_once '../model/PartidasModel.php';

$partidaModel = new Partida($pdo);

try {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error_message'] = 'Você precisa estar logado para realizar essa ação.';
        header('Location: ../../login.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ação de criar uma nova partida
        if ($_POST['action'] === 'create') {
            $timeCasaId = $_POST['time_casa_id'];
            $timeVisitanteId = $_POST['time_visitante_id'];
            $data = $_POST['data'];
            $hora = $_POST['hora'];
            $local = $_POST['local'];
            $criadorId = $_SESSION['user_id'];
            $status = $_POST['status'];
            $gols_casa = $_POST['gols_casa'];
            $gols_visitante = $_POST['gols_visitante'];

            $partidaId = $partidaModel->criarPartida($timeCasaId, $timeVisitanteId, $data, $hora, $local, $criadorId, $status, $gols_casa, $gols_visitante);
            $_SESSION['message'] = 'Partida criada com sucesso!';

            header("Location: ../view/match/view.php?id=$partidaId");
            exit();
        }

        // Ação de deletar uma partida
        if ($_POST['action'] === 'delete') {
            $partidaId = $_POST['id'];
            $partidaModel->deletarPartida($partidaId);
            $_SESSION['message'] = 'Partida deletada com sucesso!';
            header("Location: ../view/match/manage.php");
            exit();
        }

        if ($_POST['action'] === 'edit') { // Alterado de 'update' para 'edit'
            $partidaId = $_POST['id'];
            $timeCasaId = $_POST['time_casa_id'];
            $timeVisitanteId = $_POST['time_visitante_id'];
            $data = $_POST['data'];
            $hora = $_POST['hora']; // Adicione a hora
            $local = $_POST['local'];
            $status = $_POST['status'];
            $gols_casa = $_POST['gols_casa'];
            $gols_visitante = $_POST['gols_visitante'];

            $partidaModel->atualizarPartida($partidaId, $timeCasaId, $timeVisitanteId, $data, $hora, $local, $status, $gols_casa, $gols_visitante);
            $_SESSION['message'] = 'Partida atualizada com sucesso!';
            header("Location: ../view/match/view.php?id=$partidaId");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'atualizar_posicao') {
            $user_id = $_POST['user_id'];
            $position = $_POST['position'];
            
            $stmt = $pdo->prepare("UPDATE times_jogadores SET position = :position WHERE user_id = :user_id");
            $stmt->execute(['position' => $position, 'user_id' => $user_id]);
            
            echo "Posição salva com sucesso.";
            
        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $partidaId = $_GET['id'];
        $partida = $partidaModel->buscarPartidaPorId($partidaId);
        include '../view/match/view.php';
        exit();
    }

} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: ../view/match/manage.php');
    exit();
}