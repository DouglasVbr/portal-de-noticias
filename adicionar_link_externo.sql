-- Adicionar campo link_externo na tabela noticias
ALTER TABLE noticias ADD COLUMN link_externo VARCHAR(255) NULL AFTER imagem; 