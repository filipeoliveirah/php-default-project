CREATE DEFINER = CURRENT_USER TRIGGER tg_contacts_AFTER_INSERT AFTER INSERT ON tb_contacts FOR EACH ROW
BEGIN
	CALL sp_personsdata_save(NEW.idperson);
END