CREATE DEFINER = CURRENT_USER TRIGGER tg_cartscoupons_AFTER_UPDATE AFTER UPDATE ON tb_cartscoupons FOR EACH ROW
BEGIN
	CALL sp_cartsdata_save(NEW.idcart);
END