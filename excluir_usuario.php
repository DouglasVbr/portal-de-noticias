<?php
require_once 'verifica_login.php';
require_once 'funcoes.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Confirma a exclusão
if (isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
    // Exclui o usuário
    if (excluirUsuario($usuario_id)) {
        // Limpa a sessão
        session_unset();
        session_destroy();
        
        // Redireciona para a página inicial com mensagem de sucesso
        header('Location: index.php?sucesso=conta_excluida');
        exit();
    } else {
        header('Location: editar_usuario.php?erro=erro_ao_excluir');
        exit();
    }
}

// Se não confirmou, redireciona de volta
header('Location: editar_usuario.php');
exit();
?> 