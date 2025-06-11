<?php
require_once 'funcoes.php';

// Buscar todas as notícias
$noticias = buscarNoticias();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Esporte Total</title>
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

    <!-- Header -->
    <header class="hero-section text-white text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Portal Esporte Total</h1>
            <p class="lead">Sua fonte completa de notícias esportivas</p>
            <?php if (!verificarLogin()): ?>
                <a href="cadastro.php" class="btn btn-primary btn-lg">Junte-se a nós!</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4">Últimas Notícias</h2>
                
                <?php if (empty($noticias)): ?>
                    <div class="alert alert-info">
                        <h4>Nenhuma notícia encontrada</h4>
                        <p>Seja o primeiro a publicar uma notícia!</p>
                        <?php if (verificarLogin()): ?>
                            <a href="nova_noticia.php" class="btn btn-primary">Publicar Notícia</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Fazer Login</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($noticias as $noticia): ?>
                        <article class="card mb-4 shadow-sm">
                            <?php if ($noticia['imagem']): ?>
                                <img src="imagens/<?php echo htmlspecialchars($noticia['imagem']); ?>" 
                                     class="card-img-top news-image" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="noticia.php?id=<?php echo $noticia['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($noticia['titulo']); ?>
                                    </a>
                                </h3>
                                <p class="card-text">
                                    <?php echo htmlspecialchars(criarResumo($noticia['noticia'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Por <strong><?php echo htmlspecialchars($noticia['autor_nome']); ?></strong>
                                        em <?php echo formatarData($noticia['data']); ?>
                                    </small>
                                    <a href="noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        Ler mais
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Sobre o Portal</h5>
                    </div>
                    <div class="card-body">
                        <p>O <strong>Esporte Total</strong> é seu portal de notícias esportivas mais completo!</p>
                        <p>Aqui você encontra as últimas notícias sobre:</p>
                        <ul>
                            <li>⚽ Futebol</li>
                            <li>🏀 Basquete</li>
                            <li>🎾 Tênis</li>
                            <li>🏐 Vôlei</li>
                            <li>🏊 Natação</li>
                            <li>🏃 Atletismo</li>
                        </ul>
                    </div>
                </div>
                
                <?php if (!verificarLogin()): ?>
                    <div class="card mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Participe!</h5>
                        </div>
                        <div class="card-body text-center">
                            <p>Cadastre-se e publique suas próprias notícias!</p>
                            <a href="cadastro.php" class="btn btn-success">Criar Conta</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2025 Portal Esporte Total. Todos os direitos reservados.</p>
            <p>Desenvolvido como projeto acadêmico por Douglas Vieira</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>