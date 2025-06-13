# Portal de Notícias

Projeto de portal de notícias sobre esportes em PHP.

## Funcionalidades
- Cadastro, login e logout de usuários
- CRUD de notícias (criar, editar, excluir, listar)
- Apenas o autor pode editar/excluir suas notícias
- Listagem pública das notícias na página inicial
- Página individual para leitura da notícia
- Interface responsiva (HTML/CSS, pode usar Bootstrap)

## Tecnologias Utilizadas
- PHP 7.4+
- MySQL
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- XAMPP (servidor local)

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
### Tabela: usuarios
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- nome (VARCHAR(100))
- email (VARCHAR(100), UNIQUE)
- senha (VARCHAR(255))
- data_cadastro (DATETIME)

### Tabela: noticias
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- titulo (VARCHAR(200))
- conteudo (TEXT)
- imagem (VARCHAR(255))
- data_publicacao (DATETIME)
- autor_id (INT, FOREIGN KEY)
- categoria (VARCHAR(50))

## Como rodar
1. Clone o repositório
2. Importe o `dump.sql` no seu MySQL
3. Configure o acesso ao banco em `conexao.php`:
   ```php
   $host = 'localhost';
   $dbname = 'portal_noticias';
   $usuario = 'seu_usuario';
   $senha = 'sua_senha';
   ```
4. Coloque os arquivos em um servidor local (ex: XAMPP)
5. Acesse `index.php` pelo navegador

## Personalização
- Altere o tema, cores e logo conforme o tema do seu portal
- Modifique as categorias de notícias em `funcoes.php`
- Ajuste o layout em `style.css`

## Requisitos
- PHP 7.4+
- MySQL
- Navegador moderno
- XAMPP ou similar

## Segurança
- Senhas armazenadas com hash
- Proteção contra SQL Injection
- Validação de dados
- Controle de sessão
- Proteção de rotas

## Manutenção
- Backup regular do banco de dados
- Atualização de dependências
- Monitoramento de logs
- Otimização de consultas

---
Trabalho final para a disciplina de Desenvolvimento Web II.

## Quadro Kanban de Acompanhamento do Projeto

### Grupo: Notícias de Esportes em Geral  
**Integrantes:** Douglas Vieira

| Etapa (Requisito)      | Descrição do Requisito                | Data Início | Data Entrega | Responsável    | Observações                  |
|------------------------|---------------------------------------|-------------|--------------|----------------|------------------------------|
| Levantamento de Dados  | Coletar informações iniciais do tema  | 01/06/2024  | 03/06/2024   | Douglas Vieira |                              |
| Análise de Requisitos  | Definir requisitos do sistema         | 04/06/2024  | 06/06/2024   | Douglas Vieira |                              |
| Prototipação           | Criar protótipo das telas             | 07/06/2024  | 10/06/2024   | Douglas Vieira |                              |
| Desenvolvimento        | Programar funcionalidades principais  | 11/06/2024  | 18/06/2024   | Douglas Vieira |                              |
| Testes                 | Testar todas as funcionalidades       | 19/06/2024  | 21/06/2024   | Douglas Vieira |                              |
| Ajustes Finais         | Corrigir bugs e refinar o sistema     | 22/06/2024  | 24/06/2024   | Douglas Vieira |                              |
| Entrega                | Apresentar o projeto final            | 25/06/2024  | 25/06/2024   | Douglas Vieira |                              | 
