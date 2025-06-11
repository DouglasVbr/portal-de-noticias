<?php
require_once 'funcoes.php';

// Verificar se foi passado um ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];
$noticia = buscarNoticiaPorId($id);

// Se não encontrou a notícia, redirecionar
if (!$noticia) {
    header("Location: index.php");
    exit();
}
?>

