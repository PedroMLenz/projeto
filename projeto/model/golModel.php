<?php
class Gol {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarGol($partida_id, $jogador_id, $time_id, $minuto, $tipo) {
        $sql = "INSERT INTO gols (partida_id, jogador_id, time_id, minuto, tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$partida_id, $jogador_id, $time_id, $minuto, $tipo]);
    }
}
?>