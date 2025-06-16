<?php
require_once 'conexao.php';

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Função para verificar se o usuário está logado
function verificarLogin() {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

// Função para buscar usuário por e-mail (usada no login.php)
function buscarUsuarioPorEmail($email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Em um ambiente de produção, logar o erro em vez de exibi-lo
        // error_log('Erro ao buscar usuário: ' . $e->getMessage());
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
        // error_log('Erro ao cadastrar usuário: ' . $e->getMessage());
        return "Erro ao cadastrar usuário. Tente novamente mais tarde.";
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
        
        if ($limite && is_numeric($limite)) {
            $sql .= " LIMIT " . intval($limite);
        }
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // error_log('Erro ao buscar notícias: ' . $e->getMessage());
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // error_log('Erro ao buscar notícia por ID: ' . $e->getMessage());
        return false;
    }
}

// Função para criar notícia
function criarNoticia($titulo, $noticia, $autor_id, $imagem = null) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, noticia, autor, imagem, data) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$titulo, $noticia, $autor_id, $imagem]);
        return $pdo->lastInsertId(); // Retorna o ID da notícia inserida
    } catch (PDOException $e) {
        // error_log('Erro ao criar notícia: ' . $e->getMessage());
        return false;
    }
}

// Função para editar notícia
function editarNoticia($id, $titulo, $noticia_texto, $imagem = null) {
    global $pdo;
    
    try {
        if ($imagem) {
            $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ?");
            $stmt->execute([$titulo, $noticia_texto, $imagem, $id]);
        } else {
            // Se não houver nova imagem, não atualiza o campo imagem
            $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, noticia = ? WHERE id = ?");
            $stmt->execute([$titulo, $noticia_texto, $id]);
        }
        return $stmt->rowCount() > 0; // Retorna true se alguma linha foi afetada
    } catch (PDOException $e) {
        // error_log('Erro ao editar notícia: ' . $e->getMessage());
        return false;
    }
}

// Função para excluir notícia
function excluirNoticia($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0; // Retorna true se alguma linha foi afetada
    } catch (PDOException $e) {
        // error_log('Erro ao excluir notícia: ' . $e->getMessage());
        return false;
    }
}

// Função para buscar notícias do usuário
function buscarNoticiasPorAutor($autor_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT n.*, u.nome as autor_nome FROM noticias n INNER JOIN usuarios u ON n.autor = u.id WHERE n.autor = ? ORDER BY data DESC");
        $stmt->execute([$autor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // error_log('Erro ao buscar notícias por autor: ' . $e->getMessage());
        return [];
    }
}

// Função para formatar data
function formatarData($data_string) {
    if (empty($data_string)) return '';
    $datetime = new DateTime($data_string);
    return $datetime->format('d/m/Y H:i');
}

// Função para criar resumo
function criarResumo($texto, $limite = 150) {
    if (empty($texto)) return '';
    if (mb_strlen($texto) <= $limite) {
        return htmlspecialchars($texto);
    }
    return htmlspecialchars(mb_substr($texto, 0, $limite)) . "...";
}

// Função para contar notícias de um autor
function contarNoticiasPorAutor($autor_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE autor = ?");
        $stmt->execute([$autor_id]);
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        // error_log('Erro ao contar notícias: ' . $e->getMessage());
        return 0;
    }
}

// Função para buscar usuário por ID
function buscarUsuarioPorId($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Em um ambiente de produção, logar o erro em vez de exibi-lo
        // error_log('Erro ao buscar usuário por ID: ' . $e->getMessage());
        return false;
    }
}

// Função para atualizar usuário
function atualizarUsuario($id, $nome, $senha = null) {
    global $pdo;
    try {
        if ($senha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, senha = ? WHERE id = ?");
            $stmt->execute([$nome, $senhaHash, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
            $stmt->execute([$nome, $id]);
        }
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // error_log('Erro ao atualizar usuário: ' . $e->getMessage());
        return false;
    }
}

function salvarTokenRecuperacao($usuario_id, $token, $expiracao) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expiracao) VALUES (?, ?, ?)");
    return $stmt->execute([$usuario_id, $token, $expiracao]);
}

function verificarTokenRecuperacao($token) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT u.* 
        FROM usuarios u 
        INNER JOIN tokens_recuperacao t ON u.id = t.usuario_id 
        WHERE t.token = ? 
        AND t.expiracao > NOW() 
        AND t.usado = 0
    ");
    
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function invalidarTokenRecuperacao($token) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE tokens_recuperacao SET usado = 1 WHERE token = ?");
    return $stmt->execute([$token]);
}

function atualizarSenha($usuario_id, $nova_senha) {
    global $pdo;
    
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    return $stmt->execute([$senha_hash, $usuario_id]);
}

function excluirUsuario($id) {
    global $pdo;
    try {
        // Primeiro, excluir todas as notícias do usuário
        $stmt = $pdo->prepare("DELETE FROM noticias WHERE autor = ?");
        $stmt->execute([$id]);
        
        // Depois, excluir o usuário
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // error_log('Erro ao excluir usuário: ' . $e->getMessage());
        return false;
    }
}

?>

