CREATE PROCEDURE sp_eventscalendarsfiles_save(
pidcalendar INT,
pidfile INT,
pdtregister TIMESTAMP
)
BEGIN

    IF pidcalendar = 0 THEN
    
        INSERT INTO tb_eventscalendarsfiles (dtregister)
        VALUES(pdtregister);
        
        SET pidcalendar = LAST_INSERT_ID();

    ELSE
        
        UPDATE tb_eventscalendarsfiles        
        SET 
            dtregister = pdtregister        
        WHERE idcalendar = pidcalendar;

    END IF;

    CALL sp_eventscalendarsfiles_get(pidcalendar);

END;