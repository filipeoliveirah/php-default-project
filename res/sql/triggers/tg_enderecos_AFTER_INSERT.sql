CREATE DEFINER = CURRENT_USER TRIGGER tb_enderecos_AFTER_INSERT AFTER INSERT ON tb_enderecos FOR EACH ROW
BEGIN
	CALL sp_pessoasdados_save(NEW.idpessoa);
END