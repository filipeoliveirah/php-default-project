<?php

class Estados extends Collection {

    protected $class = "Estado";
    protected $saveQuery = "sp_estados_save";
    protected $saveArgs = array("idestado", "desestado", "desuf", "idpais");
    protected $pk = "idestado";

    public function get(){}

    public static function listAll():Estados
    {

    	$estados = new Estados();

    	$estados->loadFromQuery("CALL sp_estados_list();");

    	return $estados;

    }

}

?>