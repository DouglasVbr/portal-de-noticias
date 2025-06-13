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

// Verifica se o usuário logado é o autor da notícia
if ($noticia['autor'] != $_SESSION['usuario_id']) {
    header('Location: dashboard.php?erro=permissao_negada');
    exit();
}

// Tenta excluir a notícia
if (excluirNoticia($id)) {
    // Se a imagem existir e não for a imagem padrão, tenta excluí-la
    if (!empty($noticia['imagem']) && $noticia['imagem'] !== 'default.jpg') {
        $caminho_imagem = 'imagens/' . $noticia['imagem'];
        if (file_exists($caminho_imagem)) {
            unlink($caminho_imagem);
        }
    }
    header('Location: dashboard.php?sucesso=noticia_excluida');
    exit();
} else {
    header('Location: dashboard.php?erro=erro_ao_excluir');
    exit();
}
?>

