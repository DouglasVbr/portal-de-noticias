# Portal de Notícias

Projeto de portal de notícias em PHP.

## Funcionalidades
- Cadastro, login e logout de usuários
- CRUD de notícias (criar, editar, excluir, listar)
- Apenas o autor pode editar/excluir suas notícias
- Listagem pública das notícias na página inicial
- Página individual para leitura da notícia
- Interface responsiva (HTML/CSS, pode usar Bootstrap)

## Estrutura de Arquivos
- `index.php` — Página inicial
- `noticia.php` — Página individual da notícia
- `login.php` — Login de usuário
- `cadastro.php` — Cadastro de usuário
- `logout.php` — Logout do usuário
- `dashboard.php` — Painel do usuário logado
- `nova_noticia.php` — Cadastro de nova notícia
- `editar_noticia.php` — Edição de notícia
- `excluir_noticia.php` — Exclusão de notícia
- `conexao.php` — Conexão com o banco de dados
- `funcoes.php` — Funções auxiliares
- `verifica_login.php` — Protege páginas restritas
- `style.css` — Estilos
- `dump.sql` — Estrutura e dados do banco
- `imagens/` — Imagens das notícias

## Banco de Dados
- MySQL
- Tabelas: `usuarios`, `noticias`
- Veja o arquivo `dump.sql` para estrutura e exemplos

## Como rodar
1. Clone o repositório
2. Importe o `dump.sql` no seu MySQL
3. Configure o acesso ao banco em `conexao.php`
4. Coloque os arquivos em um servidor local (ex: XAMPP)
5. Acesse `index.php` pelo navegador

## Personalização
- Altere o tema, cores e logo conforme o tema do seu portal

## Requisitos
- PHP 7.4+
- MySQL
- Navegador moderno

---
Trabalho final para a disciplina de Desenvolvimento Web. 