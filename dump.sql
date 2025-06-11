-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS portal_esportes;
USE portal_esportes;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de notícias
CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    noticia TEXT NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    autor INT NOT NULL,
    imagem VARCHAR(255) NULL,
    FOREIGN KEY (autor) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserção de dados de exemplo
INSERT INTO usuarios (nome, email, senha) VALUES 
('Admin ', 'admin@esporte.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- senha: password
('Douglas, 'douglascanal1998@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('teste', 'teste@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO noticias (titulo, noticia, autor, imagem) VALUES 
('Flamengo vence clássico contra Vasco', 'Em um jogo emocionante no Maracanã, o Flamengo derrotou o Vasco por 3x1 na noite de ontem. Os gols foram marcados por Pedro, Gabigol e Arrascaeta, enquanto o Vasco descontou com Vegetti. A partida foi marcada por grande intensidade e shows da torcida rubro-negra.', 1, 'flamengo-vasco.jpg'),
('Copa do Mundo FIFA 2026: Preparativos intensificam', 'Com menos de dois anos para a Copa do Mundo de 2026, que será realizada nos Estados Unidos, México e Canadá, os preparativos se intensificam. As seleções já começam a definir suas estratégias e os estádios passam por reformas para receber o maior evento do futebol mundial.', 2, 'copa-2026.jpg'),
('Serena Williams anuncia aposentadoria', 'A lendária tenista americana Serena Williams anunciou oficialmente sua aposentadoria do tênis profissional. Com 23 títulos de Grand Slam, ela é considerada uma das maiores atletas de todos os tempos e deixa um legado inigualável no esporte.', 1, 'serena-williams.jpg'),
('NBA: Lakers contrata novo técnico', 'Os Los Angeles Lakers anunciaram a contratação de um novo técnico para a próxima temporada. A decisão visa melhorar o desempenho da equipe após uma temporada decepcionante. O time busca voltar aos playoffs com as mudanças na comissão técnica.', 3, 'lakers-tecnico.jpg');