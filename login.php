<?php
require_once 'funcoes.php';

$erro = '';

// Se já estiver logado, redirecionar para o painel
if (verificarLogin()) {
    header("Location: dashboard.php");
    exit();
}

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $usuario = buscarUsuarioPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $erro = "E-mail ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Esporte Total</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <!-- Vídeo de fundo -->
    <video class="video-background" autoplay muted loop>
        <source src="view/fundo0.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
    </video>
    <div class="video-overlay"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🏆 Esporte Total</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Início</a>
                <a class="nav-link active" href="cadastro.php">Cadastrar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($erro): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'cadastro'): ?>
                            <div class="alert alert-success">Cadastro realizado com sucesso! Faça seu login.</div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'perfil_atualizado'): ?>
                            <div class="alert alert-success">Perfil atualizado com sucesso! Faça login novamente com suas credenciais.</div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'senha_atualizada'): ?>
                            <div class="alert alert-success">Senha atualizada com sucesso! Faça login com sua nova senha.</div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#recuperarSenhaModal">Esqueceu a senha?</a>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <p class="mb-0">Não tem uma conta?</p>
                            <a href="cadastro.php" class="btn btn-outline-success btn-sm">Cadastre-se aqui</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Recuperação de Senha -->
    <div class="modal fade" id="recuperarSenhaModal" tabindex="-1" aria-labelledby="recuperarSenhaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="recuperarSenhaModalLabel">Recuperar Senha</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="formRecuperarSenha" method="POST" action="recuperar_senha.php">
                        <div class="mb-3">
                            <label for="emailRecuperacao" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="emailRecuperacao" name="email" required>
                            <div class="form-text">Digite seu e-mail cadastrado para receber as instruções de recuperação de senha.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Enviar Instruções</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'footer.php'; ?>
</body>
</html>

