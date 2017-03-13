<?php

class ConfigurationType extends Model {

    const STRING = 1;
    const INT = 2;
    const FLOAT = 3;
    const BOOL = 4;
    const DATETIME = 5;
    const ARRAY = 6;

    public $required = array('desconfigurationtype');
    protected $pk = "idconfigurationtype";

    public function get(){

        $args = func_get_args();
        if(!isset($args[0])) throw new Exception($this->pk." não informado");

        $this->queryToAttr("CALL sp_configurationstypes_get(".$args[0].");");
                
    }

    public function save(){

        if($this->getChanged() && $this->isValid()){

            $this->queryToAttr("CALL sp_configurationstypes_save(?, ?);", array(
                $this->getidconfigurationtype(),
                $this->getdesconfigurationtype()
            ));

            return $this->getidconfigurationtype();

        }else{

            return false;

        }
        
    }

    public function remove(){

        $this->proc("sp_configurationstypes_remove", array(
            $this->getidconfigurationtype()
        ));

        return true;
        
    }

}

?>