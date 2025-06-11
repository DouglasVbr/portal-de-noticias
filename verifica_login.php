<?php
require_once 'funcoes.php';

// Verificar se o usuário está logado
if (!verificarLogin()) {
    header("Location: login.php?erro=acesso_negado");
    exit();
}
?>