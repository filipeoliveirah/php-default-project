CREATE PROCEDURE sp_contatossubtipos_remove(
pidcontatosubtipo INT
)
BEGIN

    DELETE FROM tb_contatossubtipos 
    WHERE idcontatosubtipo = pidcontatosubtipo;

END