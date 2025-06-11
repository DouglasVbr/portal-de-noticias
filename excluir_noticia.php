<?php
require_once 'verifica_login.php';
require_once 'funcoes.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: dashboard.php?erro=noticia_nao_encontrada');
    exit();
}

$id = intval($_GET['id']);
$noticia = buscarNoticiaPorId($id);

if (!$noticia) {
    header('Location: dashboard.php?erro=noticia_nao_encontrada');
    exit();
}

if ($noticia['autor'] != $_SESSION['usuario_id']) {
    header('Location: dashboard.php?erro=permissao_negada');
    exit();
}

if (excluirNoticia($id)) {
    header('Location: dashboard.php?sucesso=noticia_excluida');
    exit();
} else {
    header('Location: dashboard.php?erro=erro_ao_excluir');
    exit();
}

// Página para excluir notícia
?> 