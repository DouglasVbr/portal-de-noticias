<?php
require_once 'funcoes.php';

// Verificar se o usuário está logado ou se veio da recuperação de senha
if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_editar_id'])) {
    header('Location: login.php');
    exit();
}

// Determinar qual ID usar
if (isset($_SESSION['usuario_editar_id'])) {
    $usuario_id = $_SESSION['usuario_editar_id'];
    $modo_recuperacao = true;
} else {
    $usuario_id = $_SESSION['usuario_id'];
    $modo_recuperacao = false;
}

$usuario = buscarUsuarioPorId($usuario_id);

if (!$usuario) {
    // Tratar erro: usuário não encontrado
    header('Location: login.php');
    exit();
}

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if (empty($nome)) {
        $mensagem = 'O nome de usuário não pode ser vazio.';
        $tipo_mensagem = 'danger';
    } elseif (!empty($senha) && $senha !== $confirmar_senha) {
        $mensagem = 'A senha e a confirmação de senha não coincidem.';
        $tipo_mensagem = 'danger';
    } else {
        $sucesso = atualizarUsuario($usuario_id, $nome, $senha);
        if ($sucesso) {
            if ($modo_recuperacao) {
                // Se veio da recuperação de senha, limpar a sessão e redirecionar para login
                unset($_SESSION['usuario_editar_id']);
                header('Location: login.php?sucesso=senha_atualizada');
                exit();
            } else {
                // Se não é modo recuperação, redirecionar para login após atualização
                header('Location: login.php?sucesso=perfil_atualizado');
                exit();
            }
        } else {
            $mensagem = 'Erro ao atualizar o perfil. Tente novamente.';
            $tipo_mensagem = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Portal Esporte Total</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Vídeo de fundo -->
    <video class="video-background" autoplay muted loop>
        <source src="view/fundo0.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
    </video>
    <div class="video-overlay"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <img src="imagens/logo.png" alt="Logo Esporte Total" class="logo-folheto me-2">
                Esporte Total
            </a>
            <?php if (!$modo_recuperacao): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Meu Painel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nova_noticia.php">Nova Notícia</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link active">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
            <?php else: ?>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Início</a>
                <a class="nav-link" href="login.php">Login</a>
            </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <?php if ($modo_recuperacao): ?>
                    <h2 class="mb-0">Redefinir Senha</h2>
                <?php else: ?>
                    <h2 class="mb-0">Editar Perfil</h2>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if ($mensagem): ?>
                    <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                        <?php echo $mensagem; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="editar_usuario.php" method="POST">
                    <div class="mb-4">
                        <label for="nome" class="form-label">Nome de Usuário</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="senha" class="form-label"><?php echo $modo_recuperacao ? 'Nova Senha' : 'Nova Senha (deixe em branco para não alterar)'; ?></label>
                        <input type="password" class="form-control" id="senha" name="senha" <?php echo $modo_recuperacao ? 'required' : ''; ?>>
                    </div>
                    <div class="mb-4">
                        <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" <?php echo $modo_recuperacao ? 'required' : ''; ?>>
                    </div>
                    <button type="submit" class="btn btn-warning mb-3">
                        <?php echo $modo_recuperacao ? 'Redefinir Senha' : 'Salvar Alterações'; ?>
                    </button>
                    <?php if (!$modo_recuperacao): ?>
                        <a href="dashboard.php" class="btn btn-secondary mb-3">Voltar ao Painel</a>
                        <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#excluirContaModal">
                            Excluir Minha Conta
                        </button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary mb-3">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <?php if (!$modo_recuperacao): ?>
    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="excluirContaModal" tabindex="-1" aria-labelledby="excluirContaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="excluirContaModalLabel">Confirmar Exclusão de Conta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">⚠️ Atenção! Esta ação não pode ser desfeita.</p>
                    <p>Tem certeza que deseja excluir sua conta? Todas as suas notícias serão excluídas permanentemente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="excluir_usuario.php" method="POST">
                        <input type="hidden" name="confirmar" value="sim">
                        <button type="submit" class="btn btn-danger">Sim, Excluir Minha Conta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 