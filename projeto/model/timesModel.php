<?php

class Time
{
    private $pdo;
    private $id;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function deletarTime($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM times WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Erro ao excluir o time: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPlayerPositions($timeId)
    {
        $stmt = $this->pdo->prepare("
            SELECT user_id, position
            FROM times_usuarios
            WHERE time_id = :time_id
        ");
        $stmt->bindParam(':time_id', $timeId);
        $stmt->execute();
        
        $positions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $positions[$row['user_id']] = $row['position'];
        }
        return $positions;
    }

    public function verificarNomeExistente($nome, $timeId = null) {
        $sql = "SELECT COUNT(*) FROM times WHERE nome = :nome";
        if ($timeId) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        if ($timeId) {
            $stmt->bindParam(':id', $timeId);
        }
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
    

    public function buscarTimePorId($timeId) {
        $stmt = $this->pdo->prepare("
            SELECT t.id, t.nome, u.nome AS nome_capitao
            FROM times t
            INNER JOIN usuarios u ON t.capitao_id = u.id
            WHERE t.id = :time_id
        ");
        $stmt->execute(['time_id' => $timeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function buscarTodosOsTimes() {
        $query = "SELECT id, nome FROM times";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarTime($nome, $capitaoId){
    $token = bin2hex(random_bytes(16)); 
    try {
        $this->pdo->beginTransaction();

        // Inserir o novo time
        $stmt = $this->pdo->prepare("
            INSERT INTO times (nome, capitao_id, token)
            VALUES (:nome, :capitao_id, :token)
        ");
        $stmt->execute([
            'nome' => $nome,
            'capitao_id' => $capitaoId,
            'token' => $token
        ]);
        $timeId = $this->pdo->lastInsertId(); 

        // Adicionar o capitão à tabela times_usuarios
        $stmt = $this->pdo->prepare("
            INSERT INTO times_usuarios (user_id, time_id)
            VALUES (:user_id, :time_id)
        ");
        $stmt->execute([
            'user_id' => $capitaoId,
            'time_id' => $timeId
        ]);

        $this->pdo->commit();

        return $timeId;
    } catch (PDOException $e) {
        $this->pdo->rollBack();
        throw new Exception('Erro ao criar o time: ' . $e->getMessage());
    }
}

    public function updatePlayerPosition($user_id, $time_id, $position)
    {
        // Atualiza a posição do jogador no banco de dados
        $stmt = $this->pdo->prepare("
            UPDATE times_usuarios
            SET position = :position
            WHERE user_id = :user_id AND time_id = :time_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':time_id', $time_id);
        $stmt->bindParam(':position', $position);
        $stmt->execute();
    }
    

    public function atualizarTime($id, $nome)
    {
        $stmt = $this->pdo->prepare("UPDATE times SET nome = :nome WHERE id = :id");
        return $stmt->execute(['nome' => $nome, 'id' => $id]);
    }

    public function buscarTimesPorUsuario($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT t.id, t.nome 
            FROM times t
            JOIN usuarios tu ON t.capitao_id = tu.id
            WHERE tu.id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarCapitao($userId, $teamId)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as count
            FROM times
            WHERE id = :team_id AND capitao_id = :user_id
        ");
        $stmt->execute(['team_id' => $teamId, 'user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function buscarTimesComoJogador($usuarioId) {
        $stmt = $this->pdo->prepare("
            SELECT t.*
            FROM times t
            INNER JOIN times_usuarios tu ON t.id = tu.time_id
            WHERE tu.user_id = :usuario_id
            AND t.capitao_id != :usuario_id
        ");
        $stmt->execute(['usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarMembrosDoTime($timeId) {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.nome, u.email, tu.position
            FROM usuarios u
            JOIN times_usuarios tu ON u.id = tu.user_id
            WHERE tu.time_id = :time_id
        ");
        $stmt->bindParam(':time_id', $timeId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function removerUsuarioDoTime($userId, $timeId) {
        // Verificar se o usuário é o capitão
        $stmt = $this->pdo->prepare("SELECT capitao_id FROM times WHERE id = :time_id");
        $stmt->execute(['time_id' => $timeId]);
        $time = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($time && $time['capitao_id'] == $userId) {
            return false; // Não permitir a remoção do capitão
        }
    
        // Se não for o capitão, prosseguir com a remoção
        $stmt = $this->pdo->prepare("DELETE FROM times_usuarios WHERE user_id = :user_id AND time_id = :time_id");
        return $stmt->execute(['user_id' => $userId, 'time_id' => $timeId]);
    }

    public function adicionarMembro($nome, $timeId){
    // Encontrar o usuário pelo nome
    $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE nome = :nome");
    $stmt->execute(['nome' => $nome]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userId = $user['id'];

        // Verificar se o usuário já é membro do time
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM times_usuarios WHERE user_id = :user_id AND time_id = :time_id");
        $stmt->execute(['user_id' => $userId, 'time_id' => $timeId]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Usuário já é membro do time
        }

        // Adicionar o usuário ao time
        $stmt = $this->pdo->prepare("
            INSERT INTO times_usuarios (user_id, time_id)
            VALUES (:user_id, :time_id)
        ");
        return $stmt->execute(['user_id' => $userId, 'time_id' => $timeId]);
    }

    return false; // Usuário não encontrado
}
}
?>