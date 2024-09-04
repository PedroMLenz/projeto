<?php

class Usuario {
    private $db;
    private $id;
    private $nome;
    private $email;
    private $senha;

    // Construtor que espera uma instância de PDO
    public function __construct($db) {
        $this->db = $db;
    }

    // Métodos para definir as propriedades
    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    // Métodos para acessar as propriedades
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    // Método para criar um novo usuário
    public function criar() {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);
        return $stmt->execute();
    }

    // Método para registrar um novo usuário
    public function register($nome, $email, $senha) {
        // Verifica se o email já está cadastrado
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            return false; // Email já cadastrado
        }

        // Cria um hash da senha
        $hashSenha = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o novo usuário no banco de dados
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $hashSenha]);
    }

    // Método para fazer login de um usuário
    public function login($email, $senha) {
        // Verifica as credenciais do usuário
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }

    // Método para buscar um usuário por ID
    public function buscarPorId() {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para listar todos os usuários
    public function listarTodos() {
        $sql = "SELECT * FROM usuarios";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para atualizar as informações de um usuário
    public function atualizar() {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);
        return $stmt->execute();
    }

    // Método para excluir um usuário
    public function excluir() {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function buscarUsuarioPorId($userId) {
        $sql = 'SELECT nome, email FROM usuarios WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}