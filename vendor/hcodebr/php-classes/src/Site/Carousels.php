<?php

namespace Hcode\Site;

use Hcode\Collection;

class Carousels extends Collection {

    protected $class = "Hcode\Site\Carousel";
    protected $saveQuery = "sp_carousels_save";
    protected $saveArgs = array("idcarousel", "descarousel", "inloop", "innav", "incenter", "inautowidth", "invideo", "inlazyload", "indots", "nritems", "nrstagepadding");
    protected $pk = "idcarousel";

    public function get(){}

    public static function listAll():Carousels
    {

    	$car = new Carousels();

    	$car->loadFromQuery("CALL sp_carousels_list();");

    	return $car;

    }

}

?>