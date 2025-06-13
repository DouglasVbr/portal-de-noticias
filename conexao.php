<?php
// Configurações do banco de dados
define("DB_HOST", "localhost");
define("DB_NAME", "portal_esportes");
define("DB_USER", "root");
define("DB_PASS", ""); // Senha vazia para o usuário root, conforme configurado

try {
    // Criação da conexão PDO
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Em um ambiente de produção, seria melhor logar o erro e exibir uma mensagem genérica.
    // error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
    die("Erro ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
}

// Iniciar sessão se ainda não foi iniciada (movido para funcoes.php para melhor organização)
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
?>

