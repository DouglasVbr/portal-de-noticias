<?php
require_once 'verifica_login.php';
require_once 'funcoes.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: dashboard.php?erro=noticia_nao_encontrada');
    exit();
}

$id = intval($_GET['id']);
$noticia = buscarNoticiaPorId($id);

if (!$noticia) {
    header('Location: dashboard.php?erro=noticia_nao_encontrada');
    exit();
}

if ($noticia['autor'] != $_SESSION['usuario_id']) {
    header('Location: dashboard.php?erro=permissao_negada');
    exit();
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $texto = trim($_POST['noticia']);
    $imagem_atual = $noticia['imagem']; // Manter imagem atual por padrão

    // Lógica para upload de nova imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        
        if (in_array($extensao, $extensoes_permitidas)) {
            if ($_FILES['imagem']['size'] <= 5 * 1024 * 1024) { // 5MB
                $nome_arquivo = uniqid() . '.' . $extensao;
                $caminho_destino = 'imagens/' . $nome_arquivo;
                
                // Criar diretório se não existir
                if (!is_dir('imagens')) {
                    mkdir('imagens', 0755, true);
                }
                
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_destino)) {
                    $imagem_atual = $nome_arquivo; // Atualiza para a nova imagem
                } else {
                    $erro = "Erro ao fazer upload da nova imagem.";
                }
            } else {
                $erro = "Formato de imagem não permitido. Use JPG, PNG ou GIF.";
            }
        } else {
            $erro = "Formato de imagem não permitido. Use JPG, PNG ou GIF.";
        }
    }

    if (empty($titulo) || empty($texto)) {
        $erro = 'Preencha todos os campos obrigatórios!';
    } else {
        if (editarNoticia($id, $titulo, $texto, $imagem_atual)) {
            $sucesso = 'Notícia atualizada com sucesso!';
            // Atualiza os dados exibidos
            $noticia = buscarNoticiaPorId($id);
        } else {
            $erro = 'Erro ao atualizar notícia.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia - Portal Esporte Total</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-white">
                        <h3 class="mb-0">✏️ Editar Notícia</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($erro): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($sucesso): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($sucesso); ?>
                                <hr>
                                <a href="dashboard.php" class="btn btn-success btn-sm">Ver Minhas Notícias</a>
                                <a href="noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-primary btn-sm">Ver Notícia</a>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Notícia *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       value="<?php echo htmlspecialchars($noticia['titulo']); ?>" 
                                       required minlength="5" maxlength="255">
                                <div class="form-text">Mínimo de 5 caracteres</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem (opcional)</label>
                                <input type="file" class="form-control" id="imagem" name="imagem" 
                                       accept="image/*">
                                <div class="form-text">Formatos aceitos: JPG, PNG, GIF. Máximo: 5MB</div>
                                <?php if (!empty($noticia['imagem'])): ?>
                                    <div class="mt-2">
                                        <p>Imagem atual:</p>
                                        <img src="imagens/<?php echo htmlspecialchars($noticia['imagem']); ?>" 
                                             alt="Imagem atual" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="noticia" class="form-label">Conteúdo da Notícia *</label>
                                <textarea class="form-control" id="noticia" name="noticia" rows="8" 
                                          required minlength="50"><?php echo htmlspecialchars($noticia['noticia']); ?></textarea>
                                <div class="form-text">Mínimo de 50 caracteres. Seja detalhado e informativo.</div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    💾 Salvar Alterações
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-primary">
                                    ↩️ Voltar ao Painel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2025 Portal Esporte Total. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contador de caracteres para título e notícia
        function criarContador(elemento, minimo) {
            const container = elemento.parentNode;
            let contador = container.querySelector('.char-count');
            if (!contador) {
                contador = document.createElement('small');
                contador.className = 'text-muted float-end char-count';
                container.appendChild(contador);
            }
            
            function atualizarContador() {
                const atual = elemento.value.length;
                contador.textContent = `${atual} caracteres`;
                
                if (atual < minimo) {
                    contador.className = 'text-danger float-end char-count';
                } else {
                    contador.className = 'text-success float-end char-count';
                }
            }
            
            elemento.addEventListener('input', atualizarContador);
            atualizarContador();
        }
        
        criarContador(document.getElementById('titulo'), 5);
        criarContador(document.getElementById('noticia'), 50);
    </script>
</body>
</html>

