create database db_teste_pulses;
commit;
use db_teste_pulses;
commit;
CREATE TABLE `dimensao` (
  `id_dimensao` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `flg_apagado` int DEFAULT '0',
  PRIMARY KEY (`id_dimensao`)
);
commit;
CREATE TABLE `pergunta` (
  `id_pergunta` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `id_dimensao` int DEFAULT NULL,
  `flg_ativo` int DEFAULT '1',
  `flg_apagado` int DEFAULT '0',
  PRIMARY KEY (`id_pergunta`),
  KEY `id_dimensao` (`id_dimensao`),
  CONSTRAINT `pergunta_ibfk_1` FOREIGN KEY (`id_dimensao`) REFERENCES `dimensao` (`id_dimensao`)
);
commit;