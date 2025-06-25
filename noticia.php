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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($noticia['titulo']); ?> - Portal Esporte Total</title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Início</a>
                    </li>
                    <?php if (verificarLogin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Meu Painel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nova_noticia.php">Nova Notícia</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cadastro.php">Cadastrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <article class="card shadow-sm mb-4">
                    <?php if (!empty($noticia['imagem'])): ?>
                        <img src="imagens/<?php echo htmlspecialchars($noticia['imagem']); ?>" 
                             class="card-img-top news-image-full" 
                             alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h1 class="card-title display-5 mb-3">
                            <?php echo htmlspecialchars($noticia['titulo']); ?>
                        </h1>
                        <p class="text-muted small mb-3">
                            Por <strong><?php echo htmlspecialchars($noticia['autor_nome']); ?></strong> 
                            em <?php echo formatarData($noticia['data']); ?>
                        </p>
                        <div class="card-text news-content">
                            <?php echo nl2br(htmlspecialchars($noticia['noticia'])); ?>
                        </div>
                        <hr>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <a href="index.php" class="btn btn-primary btn-lg w-100">↩️ Voltar para o Início</a>
                            </div>
                            <?php if (verificarLogin() && $_SESSION['usuario_id'] == $noticia['autor']): ?>
                                <div class="col-md-4">
                                    <a href="editar_noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-yellow btn-lg w-100">✏️ Editar</a>
                                </div>
                                <div class="col-md-4">
                                    <a href="excluir_noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-danger btn-lg w-100" onclick="return confirm('Tem certeza que deseja excluir esta notícia?');">🗑️ Excluir</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

