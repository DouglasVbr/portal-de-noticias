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

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $texto = trim($_POST['noticia']);
    $imagem = isset($_POST['imagem']) ? trim($_POST['imagem']) : null;

    if (empty($titulo) || empty($texto)) {
        $erro = 'Preencha todos os campos obrigatórios!';
    } else {
        if (editarNoticia($id, $titulo, $texto, $imagem)) {
            $sucesso = 'Notícia atualizada com sucesso!';
            // Atualiza os dados exibidos
            $noticia = buscarNoticiaPorId($id);
        } else {
            $erro = 'Erro ao atualizar notícia.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Notícia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Editar Notícia</h2>
    <?php if ($erro): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <p style="color:green;"><?= $sucesso ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Título:<br>
            <input type="text" name="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
        </label><br><br>
        <label>Notícia:<br>
            <textarea name="noticia" rows="8" required><?= htmlspecialchars($noticia['noticia']) ?></textarea>
        </label><br><br>
        <label>Imagem (URL ou caminho):<br>
            <input type="text" name="imagem" value="<?= htmlspecialchars($noticia['imagem']) ?>">
        </label><br><br>
        <button type="submit">Salvar Alterações</button>
        <a href="dashboard.php">Cancelar</a>
    </form>
</body>
</html> 