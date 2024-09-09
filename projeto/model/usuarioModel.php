<?php

class Usuario {
    private $pdo;
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $imagem;

    // Construtor que espera uma instância de PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
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

    public function setImagem($imagem) {
        $this->imagem = $imagem;
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

    public function getImagem() {
        return $this->imagem;
    }

    // Método para registrar um novo usuário
    public function registrar($nome, $email, $senha) {
        // Verifica se o email já está cadastrado
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            return false; // Email já cadastrado
        }

        // Insere o novo usuário no banco de dados
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha]);
    }

    // Método para fazer login de um usuário
    public function login($email, $senha) {
        // Previne SQL Injection
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario && $usuario['senha'] === $senha) {
            return $usuario;
        }
        return false;
    }
    

    public function buscarUsuarioPorId($userId) {
        $sql = 'SELECT nome, email, imagem FROM usuarios WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para atualizar as informações do usuário
    public function atualizarUsuario($id, $nome, $email, $senha = null) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email" .
                ($senha ? ", senha = :senha" : "") .
                " WHERE id = :id";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
    
        return $stmt->execute();
    }

    public function atualizarImagem($id, $imagem) {
        // Aqui salvamos apenas o nome do arquivo no banco de dados
        $nomeImagem = basename($imagem); // basename() retorna apenas o nome do arquivo
        
        $sql = "UPDATE usuarios SET imagem = :imagem WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':imagem', $nomeImagem); // Salva apenas o nome do arquivo
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
}