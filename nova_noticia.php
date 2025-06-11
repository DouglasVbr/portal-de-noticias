<?php
require_once 'verifica_login.php';

$erro = '';
$sucesso = '';

// Processar formulário
if ($_POST) {
    $titulo = trim($_POST['titulo'] ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $imagem = '';
    
    // Validações
    if (empty($titulo) || empty($noticia)) {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    } elseif (strlen($titulo) < 5) {
        $erro = "Título deve ter pelo menos 5 caracteres.";
    } elseif (strlen($noticia) < 50) {
        $erro = "Notícia deve ter pelo menos 50 caracteres.";
    } else {
        // Upload de imagem (opcional)
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
                        $imagem = $nome_arquivo;
                    } else {
                        $erro = "Erro ao fazer upload da imagem.";
                    }
                } else {
                    $erro = "Imagem deve ter no máximo 5MB.";
                }
            } else {
                $erro = "Formato de imagem não permitido. Use JPG, PNG ou GIF.";
            }
        }
        
        // Se não houve erro, criar a notícia
        if (empty($erro)) {
            if (criarNoticia($titulo, $noticia, $_SESSION['usuario_id'], $imagem)) {
                $sucesso = "Notícia criada com sucesso!";
                // Limpar campos
                $_POST = [];
            } else {
                $erro = "Erro ao criar notícia. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Notícia - Portal Esporte Total</title>
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
                        <a class="nav-link active" href="nova_noticia.php">Nova Notícia</a>
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
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">📝 Criar Nova Notícia</h3>
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
                                <a href="index.php" class="btn btn-primary btn-sm">Ver Portal</a>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Notícia *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>" 
                                       required minlength="5" maxlength="255"
                                       placeholder="Ex: Palmeiras vence o Campeonato Paulista">
                                <div class="form-text">Mínimo de 5 caracteres</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem (opcional)</label>
                                <input type="file" class="form-control" id="imagem" name="imagem" 
                                       accept="image/*">
                                <div class="form-text">Formatos aceitos: JPG, PNG, GIF. Máximo: 5MB</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="noticia" class="form-label">Conteúdo da Notícia *</label>
                                <textarea class="form-control" id="noticia" name="noticia" rows="8" 
                                          required minlength="50"
                                          placeholder="Escreva aqui o conteúdo completo da sua notícia..."><?php echo htmlspecialchars($_POST['noticia'] ?? ''); ?></textarea>
                                <div class="form-text">Mínimo de 50 caracteres. Seja detalhado e informativo.</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>📋 Dicas para uma boa notícia:</h6>
                                        <ul class="mb-0 small">
                                            <li>Use um título chamativo e descritivo</li>
                                            <li>Inclua informações importantes: quem, quando, onde, o que</li>
                                            <li>Escreva de forma clara e objetiva</li>
                                            <li>Adicione uma imagem relacionada ao conteúdo</li>
                                            <li>Revise antes de publicar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    📝 Publicar Notícia
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    🔄 Limpar
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
        // Contador de caracteres
        const textarea = document.getElementById('noticia');
        const titulo = document.getElementById('titulo');
        
        function criarContador(elemento, minimo) {
            const container = elemento.parentNode;
            const contador = document.createElement('small');
            contador.className = 'text-muted float-end';
            container.appendChild(contador);
            
            function atualizarContador() {
                const atual = elemento.value.length;
                contador.textContent = `${atual} caracteres`;
                
                if (atual < minimo) {
                    contador.className = 'text-danger float-end';
                } else {
                    contador.className = 'text-success float-end';
                }
            }
            
            elemento.addEventListener('input', atualizarContador);
            atualizarContador();
        }
        
        criarContador(titulo, 5);
        criarContador(textarea, 50);
        
        // Preview da imagem
        document.getElementById('imagem').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('preview-imagem');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'preview-imagem';
                        preview.className = 'img-fluid mt-2 rounded';
                        preview.style.maxHeight = '200px';
                        e.target.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>