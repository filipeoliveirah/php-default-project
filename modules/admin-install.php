<?php

define("PATH_PROC", PATH."/res/sql/procedures/");

$app->get("/install", function(){

	$page = new Page(array(
		'header'=>false,
		'footer'=>false
	));

	$page->setTpl("install/index");

});

$app->get("/install-admin/sql/clear/tables", function(){

	$sql = new Sql();

	$sql->query("DROP TABLE IF EXISTS tb_contatostipos;");
	$sql->query("DROP TABLE IF EXISTS tb_contatos;");
	$sql->query("DROP TABLE IF EXISTS tb_documentostipos;");
	$sql->query("DROP TABLE IF EXISTS tb_documentos;");
	$sql->query("DROP TABLE IF EXISTS tb_enderecostipos;");
	$sql->query("DROP TABLE IF EXISTS tb_enderecos;");
	$sql->query("DROP TABLE IF EXISTS tb_permissoesmenus;");
	$sql->query("DROP TABLE IF EXISTS tb_permissoesusuarios;");
	$sql->query("DROP TABLE IF EXISTS tb_permissoes;");
	$sql->query("DROP TABLE IF EXISTS tb_menususuarios;");
	$sql->query("DROP TABLE IF EXISTS tb_menus;");
	$sql->query("DROP TABLE IF EXISTS tb_usuarios;");
	$sql->query("DROP TABLE IF EXISTS tb_pessoastipos;");
	$sql->query("DROP TABLE IF EXISTS tb_pessoas;");

	echo success();

});

$app->get("/install-admin/sql/pessoas/tables", function(){

	$sql = new Sql();

	$sql->query("
		CREATE TABLE tb_pessoas (
		  idpessoa int(11) NOT NULL AUTO_INCREMENT,
		  idpessoatipo int(1) NOT NULL,
		  despessoa varchar(64) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (idpessoa),
		  KEY FK_pessoastipos (idpessoatipo)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	$sql->query("
		CREATE TABLE tb_pessoastipos (
		  idpessoatipo int(11) NOT NULL AUTO_INCREMENT,
		  despessoatipo varchar(64) NOT NULL,
		  PRIMARY KEY (idpessoatipo)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	echo success();

});

$app->get("/install-admin/sql/pessoas/inserts", function(){

	$sql = new Sql();

	$sql->query("
		INSERT INTO tb_pessoas (despessoa, idpessoatipo) VALUES
		(?, ?);
	", array(
		'Super Usuário (root)', 1
	));

	$sql->query("
		INSERT INTO tb_pessoastipos (despessoatipo) VALUES
		(?),
		(?);
	", array(
		'Física',
		'Jurídica'
	));


	echo success();

});

$app->get("/install-admin/sql/pessoas/get", function(){

	$sql = new Sql();

	$name = "sp_pessoa_get";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/pessoas/list", function(){

	$sql = new Sql();

	$name = "sp_pessoas_list";	
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");
	
	echo success();

});

$app->get("/install-admin/sql/pessoas/save", function(){

	$sql = new Sql();

	$name = "sp_pessoa_save";	
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");
	
	echo success();

});

$app->get("/install-admin/sql/pessoas/remove", function(){

	$sql = new Sql();

	$name = "sp_pessoa_remove";	
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");
	
	echo success();

});

$app->get("/install-admin/sql/usuarios/tables", function(){

	$sql = new Sql();

	$sql->query("
		CREATE TABLE tb_usuarios (
		  idusuario int(11) NOT NULL AUTO_INCREMENT,
		  idpessoa int(11) NOT NULL,
		  desusuario varchar(128) NOT NULL,
		  dessenha varchar(256) NOT NULL,
		  inbloqueado bit(1) NOT NULL DEFAULT b'0',
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (idusuario),
		  KEY FK_usuarios_pessoas_idx (idpessoa),
		  CONSTRAINT FK_usuarios_pessoas FOREIGN KEY (idpessoa) REFERENCES tb_pessoas (idpessoa) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	echo success();

});

$app->get("/install-admin/sql/usuarios/inserts", function(){

	$sql = new Sql();

	$hash = Usuario::getPasswordHash("root");

	$sql->query("
		INSERT INTO tb_usuarios (idpessoa, desusuario, dessenha) VALUES
		(?, ?, ?);
	", array(
		1, 'root', $hash
	));

	echo success();

});

$app->get("/install-admin/sql/usuarios/get", function(){

	$sql = new Sql();

	$name = "sp_usuario_get";	
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	$name = "sp_usuariologin_get";	
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/usuarios/remove", function(){

	$sql = new Sql();

	$name = "sp_usuario_remove";	
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/usuarios/save", function(){

	$sql = new Sql();

	$name = "sp_usuario_save";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/usuarios/list", function(){

	$sql = new Sql();

	

	echo success();

});

$app->get("/install-admin/sql/menus/tables", function(){

	$sql = new Sql();

	$sql->query("
		CREATE TABLE tb_menus (
		  idmenupai int(11) DEFAULT NULL,
		  idmenu int(11) NOT NULL AUTO_INCREMENT,
		  desmenu varchar(128) NOT NULL,
		  desicone varchar(64) NOT NULL,
		  deshref varchar(64) NOT NULL,
		  nrordem int(11) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (idmenu),
		  KEY FK_menus_menus (idmenupai),
		  CONSTRAINT FK_menus_menus FOREIGN KEY (idmenupai) REFERENCES tb_menus (idmenu) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	$sql->query("
		CREATE TABLE tb_menususuarios (
		  idmenu int(11) NOT NULL,
		  idusuario int(11) NOT NULL,
		  KEY FK_usuariosmenuspessoas (idusuario),
		  KEY FK_usuariosmenusmenus (idmenu)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	echo success();

});

$app->get("/install-admin/sql/menus/inserts", function(){

	$sql = new Sql();

	$sql->query("
		INSERT INTO tb_menus (desmenu, desicone, deshref, nrordem) VALUES
		(?, ?, ?, ?),
		(?, ?, ?, ?),
		(?, ?, ?, ?);
	", array(
		'Dashboard', 'wb-bookmark', '/', 0,
		'Sistema', 'wb-cog', '', 0,
		'Menu', '', 'sys-menu', 2
	));

	echo success();

});

$app->get("/install-admin/sql/menus/get", function(){

	$sql = new Sql();

	$name = "sp_menu_get";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/menus/list", function(){

	$sql = new Sql();

	$name = "sp_menu_list";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/menus/remove", function(){

	$sql = new Sql();

	$name = "sp_menu_remove";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/menus/save", function(){

	$sql = new Sql();

	$name = "sp_menu_save";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/contatos/tables", function(){

	$sql = new Sql();

	$sql->query("
		CREATE TABLE tb_contatostipos (
		  idcontatotipo int(11) NOT NULL AUTO_INCREMENT,
		  descontatotipo varchar(64) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (idcontatotipo)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	$sql->query("
		CREATE TABLE tb_contatos (
		  idcontato int(11) NOT NULL AUTO_INCREMENT,
		  idcontatotipo int(11) NOT NULL,
		  idpessoa int(11) NOT NULL,
		  descontato varchar(64) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (idcontato),
		  KEY FK_contatostipos (idcontatotipo),
		  KEY FK_pessoascontatos (idpessoa)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	echo success();

});

$app->get("/install-admin/sql/contatos/inserts", function(){

	$sql = new Sql();

	$sql->query("
		INSERT INTO tb_contatostipos (descontatotipo) VALUES
		(?),
		(?),
		(?);
	", array(
		'E-mail',
		'Celular',
		'Telefone'
	));

	echo success();

});

$app->get("/install-admin/sql/contatos/get", function(){

	$sql = new Sql();

	$name = "sp_contato_get";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/contatos/list", function(){

	$sql = new Sql();

	$name = "sp_contatofrompessoa_list";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	$name = "sp_contatostipos_list";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/contatos/save", function(){

	$sql = new Sql();

	$name = "sp_contato_save";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/contatos/remove", function(){

	$sql = new Sql();

	$name = "sp_contato_remove";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/documentos/tables", function(){

	$sql = new Sql();

	$sql->query("
		CREATE TABLE tb_documentostipos (
		  iddocumentotipo int(11) NOT NULL AUTO_INCREMENT,
		  desdocumentotipo varchar(64) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (iddocumentotipo)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	$sql->query("
		CREATE TABLE tb_documentos (
		  iddocumento int(11) NOT NULL AUTO_INCREMENT,
		  iddocumentotipo int(11) NOT NULL,
		  idpessoa int(11) NOT NULL,
		  desdocumento varchar(64) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (iddocumento),
		  KEY FK_pessoasdocumentos (idpessoa),
		  KEY FK_documentos (iddocumentotipo),
		  CONSTRAINT FK_documentos FOREIGN KEY (iddocumentotipo) REFERENCES tb_documentostipos (iddocumentotipo)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	echo success();

});

$app->get("/install-admin/sql/documentos/inserts", function(){

	$sql = new Sql();

	$sql->query("
		INSERT INTO tb_documentostipos (desdocumentotipo) VALUES
		(?),
		(?),
		(?);
	", array(
		'CPF',
		'CNPJ',
		'RG'
	));

	echo success();

});

$app->get("/install-admin/sql/documentos/get", function(){

	$sql = new Sql();

	$name = "sp_documento_get";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/documentos/list", function(){

	$sql = new Sql();

	$name = "sp_documentofrompessoa_list";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	$name = "sp_documentostipos_list";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/documentos/save", function(){

	$sql = new Sql();

	$name = "sp_documento_save";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/documentos/remove", function(){

	$sql = new Sql();

	$name = "sp_documento_remove";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/enderecos/tables", function(){

	$sql = new Sql();

	$sql->query("
		CREATE TABLE tb_enderecostipos (
		  idenderecotipo int(11) NOT NULL AUTO_INCREMENT,
		  desenderecotipo varchar(64) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (idenderecotipo)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	$sql->query("
		CREATE TABLE tb_enderecos (
		  idendereco int(11) NOT NULL AUTO_INCREMENT,
		  idenderecotipo int(11) NOT NULL,
		  idpessoa int(11) NOT NULL,
		  desendereco varchar(64) NOT NULL,
		  dtcadastro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (idendereco),
		  KEY FK_enderecostipos (idenderecotipo),
		  KEY FK_pessoasenderecos (idpessoa)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	echo success();

});

$app->get("/install-admin/sql/enderecos/inserts", function(){

	$sql = new Sql();

	$sql->query("
		INSERT INTO tb_enderecostipos (desenderecotipo) VALUES
		(?),
		(?),
		(?),
		(?);
	", array(
		'Residencial',
		'Comercial',
		'Cobrança',
		'Entrega'
	));

	echo success();

});

$app->get("/install-admin/sql/enderecos/get", function(){

	$sql = new Sql();

	$name = "sp_endereco_get";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/enderecos/list", function(){

	$sql = new Sql();

	$name = "sp_enderecofrompessoa_list";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/enderecos/save", function(){

	$sql = new Sql();

	$name = "sp_endereco_save";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/enderecos/remove", function(){

	$sql = new Sql();

	$name = "sp_endereco_remove";
	$sql->query("DROP PROCEDURE IF EXISTS {$name};");
	$sql->queryFromFile(PATH_PROC."{$name}.sql");

	echo success();

});

$app->get("/install-admin/sql/permissoes/tables", function(){

	$sql = new Sql();

	$sql->query("
		CREATE TABLE tb_permissoes (
		  idpermissao int(11) NOT NULL AUTO_INCREMENT,
		  despermissao varchar(64) NOT NULL,
		  dtcadastro timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (idpermissao)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	$sql->query("
		CREATE TABLE tb_permissoesmenus (
		  idpermissao int(11) NOT NULL,
		  idmenu int(11) NOT NULL,
		  dtcadastro timestamp NULL DEFAULT NULL,
		  KEY FK_permissoesmenus (idpermissao),
		  KEY FK_menuspermissoes (idmenu),
		  CONSTRAINT FK_menuspermissoes FOREIGN KEY (idmenu) REFERENCES tb_menus (idmenu),
		  CONSTRAINT FK_permissoesmenus FOREIGN KEY (idpermissao) REFERENCES tb_permissoes (idpermissao)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	$sql->query("
		CREATE TABLE tb_permissoesusuarios (
		  idpermissao int(11) NOT NULL,
		  idusuario int(11) NOT NULL,
		  dtcadastro timestamp NULL DEFAULT NULL,
		  KEY FK_permissoesusuarios (idpermissao),
		  KEY FK_usuariospermissoes (idusuario),
		  CONSTRAINT FK_permissoesusuarios FOREIGN KEY (idpermissao) REFERENCES tb_permissoes (idpermissao),
		  CONSTRAINT FK_usuariospermissoes FOREIGN KEY (idusuario) REFERENCES tb_usuarios (idusuario)
		) ENGINE=".DB_ENGINE." AUTO_INCREMENT=1 DEFAULT CHARSET=".DB_COLLATE.";
	");

	echo success();

});

$app->get("/install-admin/sql/permissoes/inserts", function(){

	$sql = new Sql();

	$sql->query("
		INSERT INTO tb_permissoes (despermissao) VALUES
		(?),
		(?),
		(?);
	", array(
		'Super Usuário',
		'Acesso Administrativo',
		'Acesso Área Restrita'
	));

	$sql->query("
		INSERT INTO tb_permissoesmenus (idpermissao, idmenu) VALUES
		(?, ?);
	", array(
		1, 1		
	));

	$sql->query("
		INSERT INTO tb_permissoesusuarios (idpermissao, idusuario) VALUES
		(?, ?),
		(?, ?),
		(?, ?);
	", array(
		1, 1,
		2, 1,
		3, 1
	));

	echo success();

});

$app->get("/install-admin/sql/permissoes/get", function(){

	echo success();

});

$app->get("/install-admin/sql/permissoes/list", function(){

	echo success();

});

$app->get("/install-admin/sql/permissoes/save", function(){

	echo success();

});

$app->get("/install-admin/sql/permissoes/remove", function(){

	echo success();

});
?>