<?php

require_once '../config/conexao.php'; // Inclui a conexão com o banco de dados
require_once '../model/usuarioModel.php'; // Inclui o modelo de usuário

// Verifica a ação e executa o método correspondente
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'register':
        handleRegister($pdo);
        break;

    case 'login':
        handleLogin($pdo);
        break;

    default:
        header('Location: ../public/index.php');
        exit();
}

/**
 * Função para lidar com o registro de usuários.
 */
function handleRegister($pdo) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Cria uma instância do modelo de usuário e registra o usuário
    $userModel = new Usuario($pdo);
    if ($userModel->register($name, $email, $password)) {
        $_SESSION['message'] = 'Usuário registrado com sucesso.';
        header('Location: ../view/user/login.php');
    } else {
        $_SESSION['error_message'] = 'Falha ao registrar o usuário. Tente novamente.';
        header('Location: ../view/user/register.php');
    }
    exit();
}

/**
 * Função para lidar com o login de usuários.
 */
function handleLogin($pdo) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Cria uma instância do modelo de usuário e tenta o login
    $userModel = new Usuario($pdo);
    $user = $userModel->login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        header('Location: ../view/team/manage.php');
    } else {
        $_SESSION['error_message'] = 'E-mail ou senha inválidos.';
        header('Location: ../view/user/login.php');
    }
    exit();
}