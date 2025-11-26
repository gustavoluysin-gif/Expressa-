-- Expressa_full.sql
CREATE DATABASE IF NOT EXISTS expressa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE expressa;

CREATE TABLE usuario (
  idUser INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  nomeUser VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  idade INT(11) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idUser),
  UNIQUE KEY (nomeUser),
  UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE perfil (
  idPerfil INT(11) NOT NULL AUTO_INCREMENT,
  idUser INT(11) NOT NULL,
  biografia TEXT DEFAULT '',
  meioContato VARCHAR(100) DEFAULT '',
  imagemPerfil VARCHAR(255) DEFAULT 'default.png',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idPerfil),
  UNIQUE KEY uq_perfil_user (idUser),
  KEY idx_perfil_user (idUser),
  CONSTRAINT perfil_fk_user FOREIGN KEY (idUser) REFERENCES usuario(idUser) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE obra (
  idObra INT(11) NOT NULL AUTO_INCREMENT,
  idUser INT(11) NOT NULL,
  titulo VARCHAR(100) NOT NULL,
  descricao TEXT DEFAULT '',
  imagem VARCHAR(255) DEFAULT NULL,
  classificacao18 TINYINT(1) DEFAULT 0,
  tags VARCHAR(255) DEFAULT '',
  dataCriacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idObra),
  KEY idx_obra_user (idUser),
  KEY idx_obra_tags (tags(50)),
  KEY idx_obra_data (dataCriacao),
  CONSTRAINT obra_fk_user FOREIGN KEY (idUser) REFERENCES usuario(idUser) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE curtida (
  id INT(11) NOT NULL AUTO_INCREMENT,
  idUser INT(11) NOT NULL,
  idObra INT(11) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY ux_curtida_user_obra (idUser, idObra),
  KEY idx_curtida_obra (idObra),
  CONSTRAINT curtida_fk_user FOREIGN KEY (idUser) REFERENCES usuario(idUser) ON DELETE CASCADE,
  CONSTRAINT curtida_fk_obra FOREIGN KEY (idObra) REFERENCES obra(idObra) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE denuncia (
  idDenuncia INT(11) NOT NULL AUTO_INCREMENT,
  idUser INT(11) NOT NULL,
  idObra INT(11) NOT NULL,
  motivo TEXT NOT NULL,
  dataHora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idDenuncia),
  KEY idx_denuncia_user (idUser),
  KEY idx_denuncia_obra (idObra),
  CONSTRAINT denuncia_fk_user FOREIGN KEY (idUser) REFERENCES usuario(idUser) ON DELETE CASCADE,
  CONSTRAINT denuncia_fk_obra FOREIGN KEY (idObra) REFERENCES obra(idObra) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE seguir (
  idSeg INT(11) NOT NULL AUTO_INCREMENT,
  idSeguidor INT(11) NOT NULL,
  idSeguido INT(11) NOT NULL,
  dataSeguimento TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idSeg),
  UNIQUE KEY ux_seguir (idSeguidor, idSeguido),
  KEY idx_seguir_seguidor (idSeguidor),
  KEY idx_seguir_seguido (idSeguido),
  CONSTRAINT seguir_fk_seguidor FOREIGN KEY (idSeguidor) REFERENCES usuario(idUser) ON DELETE CASCADE,
  CONSTRAINT seguir_fk_seguido FOREIGN KEY (idSeguido) REFERENCES usuario(idUser) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE comentario (
  idComentario INT(11) NOT NULL AUTO_INCREMENT,
  idUser INT(11) NOT NULL,
  idObra INT(11) NOT NULL,
  comentario TEXT NOT NULL,
  dataHora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idComentario),
  KEY idx_comentario_user (idUser),
  KEY idx_comentario_obra (idObra),
  CONSTRAINT comentario_fk_user FOREIGN KEY (idUser) REFERENCES usuario(idUser) ON DELETE CASCADE,
  CONSTRAINT comentario_fk_obra FOREIGN KEY (idObra) REFERENCES obra(idObra) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE notificacao (
  idNotificacao INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  type VARCHAR(30) NOT NULL,
  from_user_id INT(11) DEFAULT NULL,
  post_id INT(11) DEFAULT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idNotificacao),
  KEY idx_not_user (user_id),
  CONSTRAINT notificacao_fk_user FOREIGN KEY (user_id) REFERENCES usuario(idUser) ON DELETE CASCADE,
  CONSTRAINT notificacao_fk_from_user FOREIGN KEY (from_user_id) REFERENCES usuario(idUser) ON DELETE SET NULL,
  CONSTRAINT notificacao_fk_post FOREIGN KEY (post_id) REFERENCES obra(idObra) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample users
INSERT INTO usuario (nome, nomeUser, email, senha, idade) VALUES
('Lilian','lili','lili@gmail.com','$2y$10$Drn9ZXEHd2PDJmRToitL4.vYTv2kHJhTD3IDHkcEm90WEZZd92Twa',24),
('Clara','clara','clararegina0417@gmail.com','$2y$10$B.jCQY.bGD2e1PWWRMeoB.nar4ITEcumGL6uKOgjxLc8.wEOHK3Ky',21);

INSERT INTO perfil (idUser, biografia, meioContato) VALUES
(1,'Artista digital.','@lili'),
(2,'Fotógrafa.','@clara');
