CREATE PROCEDURE sp_materials_save(
pidmaterial INT,
pidmaterialparent INT,
pidmaterialtype INT,
pidunitytype INT,
pidphoto INT,
pdesmaterial VARCHAR(64),
pdescode VARCHAR(64),
pinreusable BIT,
pdtregister TIMESTAMP
)
BEGIN

    IF pidmaterial = 0 THEN
    
        INSERT INTO tb_materials (idmaterialparent, idmaterialtype, idunitytype, idphoto, desmaterial, descode, inreusable, dtregister)
        VALUES(pidmaterialparent, pidmaterialtype, pidunitytype, pidphoto, pdesmaterial, pdescode, pinreusable, pdtregister);
        
        SET pidmaterial = LAST_INSERT_ID();

    ELSE
        
        UPDATE tb_materials        
        SET 
            idmaterialparent = pidmaterialparent,
            idmaterialtype = pidmaterialtype,
            idunitytype = pidunitytype,
            idphoto = pidphoto,
            desmaterial = pdesmaterial,
            descode = pdescode,
            inreusable = pinreusable,
            dtregister = pdtregister        
        WHERE idmaterial = pidmaterial;

    END IF;

    CALL sp_materials_get(pidmaterial);

END;