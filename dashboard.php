<?php
require_once 'verifica_login.php';

// Buscar notícias do usuário logado
$minhas_noticias = buscarNoticiasPorAutor($_SESSION['usuario_id']);
$total_noticias = count($minhas_noticias);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Painel - Portal Esporte Total</title>
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
                        <a class="nav-link active" href="dashboard.php">Meu Painel</a>
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header do Dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card card">
                    <div class="card-body">
                        <h2 class="mb-0">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
                        <p class="text-muted mb-0">Gerencie suas notícias e perfil</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card dashboard-stats text-center">
                    <div class="card-body">
                        <h3><?php echo $total_noticias; ?></h3>
                        <p class="mb-0">Notícias Publicadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h3><?php echo date('d/m/Y'); ?></h3>
                        <p class="mb-0">Data Atual</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h3>Ativo</h3>
                        <p class="mb-0">Status da Conta</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ações Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="nova_noticia.php" class="btn btn-success w-100">
                                    📝 Nova Notícia
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="index.php" class="btn btn-primary w-100">
                                    🏠 Ver Portal
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="editar_usuario.php" class="btn btn-warning w-100">
                                    👤 Editar Perfil
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="logout.php" class="btn btn-danger w-100">
                                    🚪 Sair
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minhas Notícias -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Minhas Notícias</h5>
                        <a href="nova_noticia.php" class="btn btn-success btn-sm">+ Nova</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($minhas_noticias)): ?>
                            <div class="text-center py-4">
                                <h6>Nenhuma notícia publicada ainda</h6>
                                <p class="text-muted">Que tal criar sua primeira notícia?</p>
                                <a href="nova_noticia.php" class="btn btn-primary">Criar Primeira Notícia</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Data</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($minhas_noticias as $noticia): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($noticia['titulo']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo htmlspecialchars(criarResumo($noticia['noticia'], 80)); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo formatarData($noticia['data']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="noticia.php?id=<?php echo $noticia['id']; ?>" 
                                                           class="btn btn-outline-primary" title="Ver">
                                                            👁️
                                                        </a>
                                                        <a href="editar_noticia.php?id=<?php echo $noticia['id']; ?>" 
                                                           class="btn btn-outline-warning" title="Editar">
                                                            ✏️
                                                        </a>
                                                        <a href="excluir_noticia.php?id=<?php echo $noticia['id']; ?>" 
                                                           class="btn btn-outline-danger" title="Excluir"
                                                           onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">
                                                            🗑️
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>