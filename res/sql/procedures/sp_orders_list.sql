CREATE PROCEDURE sp_orders_list()
BEGIN
	
	SELECT * FROM tb_orders a
		INNER JOIN tb_persons b ON a.idperson = b.idperson
        INNER JOIN tb_formspayments c ON a.idformorder = c.idformorder
        INNER JOIN tb_ordersstatus d ON a.idstatus = d.idstatus;
    
END