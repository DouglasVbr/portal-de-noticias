<?php
require_once 'verifica_login.php';
require_once 'funcoes.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$usuario = buscarUsuarioPorId($usuario_id);

if (!$usuario) {
    // Tratar erro: usuário não encontrado, talvez redirecionar para uma página de erro ou dashboard
    header('Location: dashboard.php');
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
            $_SESSION['usuario_nome'] = $nome; // Atualiza o nome na sessão
            $mensagem = 'Perfil atualizado com sucesso!';
            $tipo_mensagem = 'success';
            // Recarregar dados do usuário para refletir as mudanças (se necessário)
            $usuario = buscarUsuarioPorId($usuario_id);
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🏆 Esporte Total</a>
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
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Editar Perfil</h2>
            </div>
            <div class="card-body">
                <?php if ($mensagem): ?>
                    <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                        <?php echo $mensagem; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="editar_usuario.php" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome de Usuário</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                        <input type="password" class="form-control" id="senha" name="senha">
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                    </div>
                    <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                    <a href="dashboard.php" class="btn btn-secondary">Voltar ao Painel</a>
                </form>
            </div>
        </div>
    </div>

    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 