<?php
require_once 'conexao.php';

// Função para verificar se o usuário está logado
function verificarLogin() {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

// Função para fazer login
function fazerLogin($email, $senha) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            return true;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

// Função para cadastrar usuário
function cadastrarUsuario($nome, $email, $senha) {
    global $pdo;
    
    try {
        // Verificar se o email já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return "E-mail já cadastrado!";
        }
        
        // Criptografar senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Inserir usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senhaHash]);
        
        return true;
    } catch (PDOException $e) {
        return "Erro ao cadastrar usuário: " . $e->getMessage();
    }
}

// Função para buscar notícias com autor
function buscarNoticias($limite = null) {
    global $pdo;
    
    try {
        $sql = "SELECT n.*, u.nome as autor_nome 
                FROM noticias n 
                INNER JOIN usuarios u ON n.autor = u.id 
                ORDER BY n.data DESC";
        
        if ($limite) {
            $sql .= " LIMIT " . intval($limite);
        }
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Função para buscar notícia por ID
function buscarNoticiaPorId($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT n.*, u.nome as autor_nome 
                              FROM noticias n 
                              INNER JOIN usuarios u ON n.autor = u.id 
                              WHERE n.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

// Função para criar notícia
function criarNoticia($titulo, $noticia, $autor, $imagem = null) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, noticia, autor, imagem) VALUES (?, ?, ?, ?)");
        $stmt->execute([$titulo, $noticia, $autor, $imagem]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Função para editar notícia
function editarNoticia($id, $titulo, $noticia, $imagem = null) {
    global $pdo;
    
    try {
        if ($imagem) {
            $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ?");
            $stmt->execute([$titulo, $noticia, $imagem, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, noticia = ? WHERE id = ?");
            $stmt->execute([$titulo, $noticia, $id]);
        }
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Função para excluir notícia
function excluirNoticia($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Função para buscar notícias do usuário
function buscarNoticiasPorAutor($autor_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM noticias WHERE autor = ? ORDER BY data DESC");
        $stmt->execute([$autor_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Função para formatar data
function formatarData($data) {
    $datetime = new DateTime($data);
    return $datetime->format('d/m/Y H:i');
}

// Função para criar resumo
function criarResumo($texto, $limite = 150) {
    if (strlen($texto) <= $limite) {
        return $texto;
    }
    return substr($texto, 0, $limite) . "...";
}
?>