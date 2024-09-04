<?php

class Partida {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para buscar partidas criadas pelo usuário
    public function buscarPartidasPorCriador($userId) {
        $sql = "SELECT p.id, 
                DATE_FORMAT(p.data, '%d/%m/%Y') AS data_formatada, 
                TIME_FORMAT(p.hora, '%H:%i') AS hora_formatada, 
                p.local, 
                p.status, tc.nome AS time_casa_nome, tv.nome AS time_visitante_nome
                FROM partidas p
                JOIN times tc ON p.time_casa_id = tc.id
                JOIN times tv ON p.time_visitante_id = tv.id
                WHERE p.criador_id = :userId";  // Usando 'criador_id'
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 

    public function buscarPartidasComoJogador($usuarioId) {
        $sql = "
            SELECT p.id, 
                DATE_FORMAT(p.data, '%d/%m/%Y') AS data_formatada, 
                TIME_FORMAT(p.hora, '%H:%i') AS hora_formatada, 
                p.local, 
                p.status,
                tc.nome AS time_casa_nome, 
                tv.nome AS time_visitante_nome
            FROM partidas p
            JOIN times tc ON p.time_casa_id = tc.id
            JOIN times tv ON p.time_visitante_id = tv.id
            JOIN times_usuarios tu ON p.time_casa_id = tu.time_id OR p.time_visitante_id = tu.time_id
            WHERE tu.user_id = :usuario_id
            GROUP BY p.id;


        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Função para criar uma nova partida
    public function criarPartida($timeCasaId, $timeVisitanteId, $data, $hora, $local, $criadorId, $status, $gols_casa, $gols_visitante) {
        $sql = "INSERT INTO partidas (time_casa_id, time_visitante_id, data, hora, local, criador_id, status, gols_casa, gols_visitante) VALUES (:time_casa_id, :time_visitante_id, :data, :hora, :local, :criador_id, :status, :gols_casa, :gols_visitante)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':time_casa_id', $timeCasaId);
        $stmt->bindParam(':time_visitante_id', $timeVisitanteId);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':local', $local);
        $stmt->bindParam(':criador_id', $criadorId);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':gols_casa', $gols_casa);
        $stmt->bindParam(':gols_visitante', $gols_visitante);

        $stmt->execute();
        return $this->pdo->lastInsertId();
    }
    

    // Função para buscar uma partida por ID
    public function buscarPartidaPorId($partidaId) {
        $sql = "SELECT id, time_casa_id, time_visitante_id, 
                DATE_FORMAT(data, '%d/%m/%Y') AS data_formatada, 
                TIME_FORMAT(hora, '%H:%i') AS hora_formatada, 
                local, status, criador_id, gols_casa, gols_visitante
                FROM partidas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $partidaId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para atualizar uma partida
    public function atualizarPartida($id, $timeCasaId, $timeVisitanteId, $data, $hora, $local, $status, $gols_casa, $gols_visitante) {
        $sql = "UPDATE partidas SET time_casa_id = :time_casa_id, time_visitante_id = :time_visitante_id, data = :data, hora = :hora, local = :local, status = :status, gols_casa = :gols_casa, gols_visitante = :gols_visitante WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':time_casa_id', $timeCasaId);
        $stmt->bindParam(':time_visitante_id', $timeVisitanteId);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':local', $local);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':gols_casa', $gols_casa);
        $stmt->bindParam(':gols_visitante', $gols_visitante);
        $stmt->execute();
    }
    

    // Função para deletar uma partida
    public function deletarPartida($partidaId) {
        $sql = "DELETE FROM partidas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $partidaId);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao deletar a partida.");
        }
    }
}