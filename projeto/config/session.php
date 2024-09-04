<?php
// Verifica se a sessão não está iniciada e inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e redireciona se não estiver
function verificarLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../view/user/login.php');
        exit();
    }
}

// Adicione outras funções úteis para o gerenciamento de sessão aqui
