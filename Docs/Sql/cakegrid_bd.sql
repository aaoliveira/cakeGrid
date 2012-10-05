SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `estados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `estados` ;

CREATE  TABLE IF NOT EXISTS `estados` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `uf` VARCHAR(2) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `cidades`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cidades` ;

CREATE  TABLE IF NOT EXISTS `cidades` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  `estado_id` INT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `fk_cidades_estados1_idx` (`estado_id` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios` ;

CREATE  TABLE IF NOT EXISTS `usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(20) NOT NULL ,
  `senha` VARCHAR(45) NOT NULL ,
  `nome` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  `ultimo` DATETIME NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT true ,
  `acessos` INT NOT NULL DEFAULT 0 ,
  `cidade_id` INT NOT NULL DEFAULT 2302 ,
  PRIMARY KEY (`id`) ,
  INDEX `i_login` (`login` ASC) ,
  INDEX `i_email` (`email` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `i_ultimo` (`ultimo` ASC) ,
  INDEX `fk_usuarios_cidades1_idx` (`cidade_id` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) ,
  INDEX `i_acessos` (`acessos` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `grupos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grupos` ;

CREATE  TABLE IF NOT EXISTS `grupos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1 ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `grupos_usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grupos_usuarios` ;

CREATE  TABLE IF NOT EXISTS `grupos_usuarios` (
  `grupo_id` INT NOT NULL ,
  `usuario_id` INT NOT NULL ,
  PRIMARY KEY (`grupo_id`, `usuario_id`) ,
  INDEX `fk_usuarios_has_grupos_grupos1_idx` (`grupo_id` ASC) ,
  INDEX `fk_usuarios_has_grupos_usuarios_idx` (`usuario_id` ASC) )
ENGINE = MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `usuarios`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `usuarios` (`id`, `login`, `senha`, `nome`, `email`, `criado`, `modificado`, `ultimo`, `ativo`, `acessos`, `cidade_id`) VALUES (1, 'admin', 'b87de6322b2c92feaf0941e87cdff60c24d5229a', 'ADMINISTRADOR CAKEGRID', 'admin@sistema.com.br', '2012-09-28 21:22:15', '2012-09-28 21:22:16', '2012-09-28 21:22:17', true, 0, 2302);

COMMIT;

-- -----------------------------------------------------
-- Data for table `grupos`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `grupos` (`id`, `nome`, `ativo`, `criado`, `modificado`) VALUES (1, 'ADMINISTRADOR', 1, '2012-09-28 21:41:51', '2012-09-28 21:41:52');
INSERT INTO `grupos` (`id`, `nome`, `ativo`, `criado`, `modificado`) VALUES (2, 'GERENTE', 1, '2012-09-28 21:41:52', '2012-09-28 21:41:53');
INSERT INTO `grupos` (`id`, `nome`, `ativo`, `criado`, `modificado`) VALUES (3, 'USUARIO', 1, '2012-09-28 21:41:53', '2012-09-28 21:41:54');
INSERT INTO `grupos` (`id`, `nome`, `ativo`, `criado`, `modificado`) VALUES (4, 'CONVIDADO', 1, '2012-09-28 21:41:54', '2012-09-28 21:41:55');

COMMIT;

-- -----------------------------------------------------
-- Data for table `grupos_usuarios`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `grupos_usuarios` (`grupo_id`, `usuario_id`) VALUES (1, 1);
INSERT INTO `grupos_usuarios` (`grupo_id`, `usuario_id`) VALUES (2, 2);

COMMIT;
