<?php

require_once '../config/conexao.php'; // Inclui a conexão com o banco de dados
require_once '../model/usuarioModel.php'; // Inclui o modelo de usuário

// Verifica a ação e executa o método correspondente
$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'register':
        registrar($pdo);
        break;

    case 'login':
        logar($pdo);
        break;

    case 'alterar':
        alterarPerfil($pdo);
        break;

    case 'atualizar_imagem':
        atualizarImagem($pdo);
        break;

    default:
        header('Location: ../view/user/perfil.php');
        exit();
}

/**
 * Função para lidar com o registro de usuários.
 */
function registrar($pdo) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Cria uma instância do modelo de usuário e registra o usuário
    $userModel = new Usuario($pdo);
    if ($userModel->registrar($name, $email, $password)) {
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
function logar($pdo) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Cria uma instância do modelo de usuário e tenta o login
    $userModel = new Usuario($pdo);
    $user = $userModel->login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        header('Location: ../view/team/gerenciar.php');
    } else {
        $_SESSION['error_message'] = 'E-mail ou senha inválidos.';
        header('Location: ../view/user/login.php');
    }
    exit();
}

/**
 * Função para lidar com a alteração de perfil.
 */
function alterarPerfil($pdo) {
    session_start();

    $id = $_SESSION['user_id']; // Assume que o ID do usuário está na sessão
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Cria uma instância do modelo de usuário
    $userModel = new Usuario($pdo);

    // Atualiza o usuário no banco de dados
    if ($userModel->atualizarUsuario($id, $nome, $email, $senha)) {
        $_SESSION['message'] = 'Perfil atualizado com sucesso!';
    } else {
        $_SESSION['error_message'] = 'Falha ao atualizar o perfil. Tente novamente.';
    }

    // Redireciona para a página de edição de perfil
    header('Location: ../view/user/perfil.php');
    exit();
}

/**
 * Função para lidar com a alteração de foto de perfil.
 */
function atualizarImagem($pdo) {
    session_start();

    $user_id = $_SESSION['user_id'];
    $usuarioModel = new Usuario($pdo);

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem = $_FILES['imagem'];
        $caminhoDestino = '../public/uploads/' . basename($imagem['name']);

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($imagem['tmp_name'], $caminhoDestino)) {
            // Atualiza a imagem no banco de dados
            if ($usuarioModel->atualizarImagem($user_id, $caminhoDestino)) {
                $_SESSION['message'] = 'Imagem de perfil atualizada com sucesso.';
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar a imagem no banco de dados.';
            }
        } else {
            $_SESSION['error_message'] = 'Erro ao fazer upload da imagem.';
        }
    } else {
        $_SESSION['error_message'] = 'Nenhuma imagem foi selecionada ou ocorreu um erro no upload.';
    }

    // Redireciona de volta para a página de perfil
    header('Location: ../view/user/perfil.php');
    exit();
}