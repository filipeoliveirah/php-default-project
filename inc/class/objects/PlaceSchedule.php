<?php

class PlaceSchedule extends Model {

    public $required = array('idplace', 'nrday');
    protected $pk = "idschedule";

    public function get(){

        $args = func_get_args();
        if(!isset($args[0])) throw new Exception($this->pk." não informado");

        $this->queryToAttr("CALL sp_placeesschedules_get(".$args[0].");");
                
    }

    public function save(){

        if($this->getChanged() && $this->isValid()){

            $this->queryToAttr("CALL sp_placeesschedules_save(?, ?, ?, ?, ?);", array(
                $this->getidschedule(),
                $this->getidplace(),
                $this->getnrday(),
                $this->gethropen(),
                $this->gethrclose()
            ));

            return $this->getidschedule();

        }else{

            return false;

        }
        
    }

    public function remove(){

        $this->execute("CALL sp_placeesschedules_remove(".$this->getidhorario().")");

        return true;
        
    }

}

?>