CREATE PROCEDURE sp_blogcategories_save(
pidcategory INT,
pdescategory VARCHAR(64)
)
BEGIN

    IF pidcategory = 0 THEN
    
        INSERT INTO tb_blogcategories (descategory)
        VALUES(pdescategory);
        
        SET pidcategory = LAST_INSERT_ID();

    ELSE
        
        UPDATE tb_blogcategories        
        SET 
            descategory = pdescategory      
        WHERE idcategory = pidcategory;

    END IF;

    CALL sp_blogcategories_get(pidcategory);

END