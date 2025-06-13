<?php
require_once 'funcoes.php';

$mensagem = '';
$tipo_mensagem = '';
$token_valido = false;
$token = $_GET['token'] ?? '';

if (empty($token)) {
    header("Location: login.php");
    exit();
}

// Verificar se o token é válido
$usuario = verificarTokenRecuperacao($token);
if ($usuario) {
    $token_valido = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valido) {
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($senha) || empty($confirmar_senha)) {
        $mensagem = "Por favor, preencha todos os campos.";
        $tipo_mensagem = "danger";
    } elseif ($senha !== $confirmar_senha) {
        $mensagem = "As senhas não coincidem.";
        $tipo_mensagem = "danger";
    } elseif (strlen($senha) < 6) {
        $mensagem = "A senha deve ter pelo menos 6 caracteres.";
        $tipo_mensagem = "danger";
    } else {
        // Atualizar a senha
        if (atualizarSenha($usuario['id'], $senha)) {
            // Invalidar o token
            invalidarTokenRecuperacao($token);
            $mensagem = "Senha atualizada com sucesso! Você já pode fazer login com sua nova senha.";
            $tipo_mensagem = "success";
            $token_valido = false;
        } else {
            $mensagem = "Erro ao atualizar a senha. Por favor, tente novamente.";
            $tipo_mensagem = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Portal Esporte Total</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🏆 Esporte Total</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Início</a>
                <a class="nav-link" href="login.php">Login</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Redefinir Senha</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($mensagem): ?>
                            <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                                <?php echo htmlspecialchars($mensagem); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!$token_valido): ?>
                            <div class="alert alert-danger">
                                Link inválido ou expirado. Por favor, solicite uma nova recuperação de senha.
                            </div>
                            <div class="text-center">
                                <a href="login.php" class="btn btn-primary">Voltar para Login</a>
                            </div>
                        <?php else: ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Nova Senha</label>
                                    <input type="password" class="form-control" id="senha" name="senha" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Redefinir Senha</button>
                                    <a href="login.php" class="btn btn-outline-secondary">Cancelar</a>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 