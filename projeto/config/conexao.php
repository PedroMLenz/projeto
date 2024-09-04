<?php
// config/conexao.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuração de conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=projeto', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro ao conectar com o banco de dados: ' . $e->getMessage();
}
