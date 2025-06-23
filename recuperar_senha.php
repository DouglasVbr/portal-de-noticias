<?php
require_once 'funcoes.php';

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $mensagem = "Por favor, informe seu e-mail.";
        $tipo_mensagem = "danger";
    } else {
        $usuario = buscarUsuarioPorEmail($email);
        
        if ($usuario) {
            // Redirecionar para a página de editar usuário
            $_SESSION['usuario_editar_id'] = $usuario['id'];
            header("Location: editar_usuario.php");
            exit();
        } else {
            $mensagem = "E-mail não encontrado em nossa base de dados.";
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
    <title>Recuperar Senha - Portal Esporte Total</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <img src="imagens/logo.png" alt="Logo Esporte Total" class="logo-folheto me-2">
                Esporte Total
            </a>
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
                        <h3 class="mb-0">Recuperar Senha</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($mensagem): ?>
                            <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                                <?php echo htmlspecialchars($mensagem); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($tipo_mensagem !== 'success'): ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="form-text">Digite seu e-mail cadastrado para acessar a página de edição de perfil.</div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Enviar Instruções</button>
                                    <a href="login.php" class="btn btn-outline-secondary">Voltar para Login</a>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center">
                                <a href="login.php" class="btn btn-primary">Voltar para Login</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'footer.php'; ?>
</body>
</html> 