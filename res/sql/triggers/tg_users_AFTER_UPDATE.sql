CREATE DEFINER = CURRENT_USER TRIGGER tg_users_AFTER_UPDATE AFTER UPDATE ON tb_users FOR EACH ROW
BEGIN
	CALL sp_personsdata_save(NEW.idperson);
END