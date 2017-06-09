CREATE PROCEDURE sp_materialsunitstypes_save(
pidunitytype INT,
pdesidunitytype VARCHAR(64),
pdesunitytypeshort VARCHAR(8),
pdtregister TIMESTAMP
)
BEGIN

    IF pidunitytype = 0 THEN
    
        INSERT INTO tb_materialsunitstypes (desidunitytype, desunitytypeshort, dtregister)
        VALUES(pdesidunitytype, pdesunitytypeshort, pdtregister);
        
        SET pidunitytype = LAST_INSERT_ID();

    ELSE
        
        UPDATE tb_materialsunitstypes        
        SET 
            desidunitytype = pdesidunitytype,
            desunitytypeshort = pdesunitytypeshort,
            dtregister = pdtregister        
        WHERE idunitytype = pidunitytype;

    END IF;

    CALL sp_materialsunitstypes_get(pidunitytype);

END;