<?php

$app->get("/lugares", function(){

	Permission::checkSession(Permission::ADMIN, true);

	$where = array();

	if(isset($_GET['deslugar']) && $_GET['deslugar'] != ""){
		array_push($where, "a.deslugar LIKE '%".utf8_decode(get('deslugar'))."%'");
	}

	if(isset($_GET['desendereco']) && $_GET['desendereco'] != ""){
		array_push($where, "b.desendereco LIKE '%".utf8_decode(get('desendereco'))."%'");
	}

	if(isset($_GET['idlugartipo'])){
		array_push($where, "c.idlugartipo = ".(int)get('idlugartipo'));
	}

	if(count($where) > 0){
		$where = "WHERE ".implode(" AND ", $where);
	}else{
		$where = "";
	}

	$query = "
		SELECT SQL_CALC_FOUND_ROWS a.*, b.desendereco, c.deslugartipo FROM tb_lugares a
			INNER JOIN tb_lugaresenderecos b1 ON a.idlugar = b1.idlugar
			INNER JOIN tb_enderecos b ON b1.idendereco = b.idendereco
		    INNER JOIN tb_lugarestipos c ON a.idlugartipo = c.idlugartipo
		".$where." ORDER BY a.deslugar LIMIT ?, ?;
	";

	$pagina = (int)get('pagina');
	$itemsPerPage = (int)get('limite');

	$paginacao = new Pagination(
		$query,
		array(),
		"Lugares",
		$itemsPerPage
	);

	$lugares = $paginacao->getPage($pagina);

	echo success(array(
		"data"=>$lugares->getFields(),
		"total"=>$paginacao->getTotal(),
		"currentPage"=>$pagina,
		"itemsPerPage"=>$itemsPerPage
	));

});

$app->get("/lugares/:idlugar", function($idlugar){

	Permission::checkSession(Permission::ADMIN, true);

	$lugar = new Lugar((int)$idlugar);

	echo success(array("data"=>$lugar->getFields()));

});

$app->get("/lugares/:idlugar/enderecos", function($idlugar){

	Permission::checkSession(Permission::ADMIN, true);

	$lugar = new Lugar((int)$idlugar);

	echo success(array("data"=>$lugar->getEnderecos()->getFields()));

});

$app->get("/lugares/:idlugar/arquivos", function($idlugar){

	// pre($_GET);
	// exit;

	Permission::checkSession(Permission::ADMIN, true);

	$lugar = new Lugar((int)$idlugar);

	$where = array();

	array_push($where, "b.idlugar = ".$lugar->getidlugar()."");

	if(count($where) > 0){
		$where = "WHERE ".implode(" AND ", $where);
	}else{
		$where = "";
	}

	$query = "
		SELECT SQL_CALC_FOUND_ROWS a.*, b.deslugar FROM tb_arquivos a
			INNER JOIN tb_lugaresarquivos c ON a.idarquivo = c.idarquivo
	        INNER JOIN tb_lugares b ON c.idlugar = b.idlugar
	    ".$where." LIMIT ?, ?;
	";

	$pagina = (int)get('pagina');
	$itemsPerPage = (int)get('limit');

	// pre($query);
	// exit;

	$paginacao = new Pagination(
		$query,
		array(),
		"Arquivos",
		$itemsPerPage
	);

	$arquivos = $paginacao->getPage($pagina);

	echo success(array(
		"data"=>$arquivos->getFields(),
		"total"=>$paginacao->getTotal(),
		"currentPage"=>$pagina,
		"itemsPerPage"=>$itemsPerPage
	));

});

$app->post("/lugares", function(){

	Permission::checkSession(Permission::ADMIN, true);

	if(isset($_POST['idendereco'])){

		if((int)post('idendereco') > 0){
			$endereco = new Endereco((int)post('idendereco'));
		}else{
			$endereco = new Endereco();
		}

		foreach (array(
			'idenderecotipo',
			'descep',
			'desendereco',
			'desnumero',
			'descomplemento',
			'desbairro',
			'descidade'
		) as $field) {
			if (isset($_POST[$field]) && post($field)) {
				$endereco->{'set'.$field}(post($field));
			}	
		}

		if (isset($_POST['idcidade']) && (int)post('idcidade') > 0) {
			$cidade = new Cidade((int)post('idcidade'));
		} else {
			if (post('desuf')) {
				$cidade = Cidade::loadFromName(post('descidade'), post('desuf'));
			} else {
				$cidade = Cidade::loadFromName(post('descidade'));
			}
		}

		if (count($cidade->getFields())) $endereco->set($cidade->getFields());

		if (count($endereco->getFields())) {

			$endereco->setinprincipal(true);

			$endereco->save();

		}

		$_POST['idendereco'] = $endereco->getidendereco();

	}

	if(post('idlugar') > 0){
		$lugar = new Lugar((int)post('idlugar'));
	}else{
		$lugar = new Lugar();
	}

	foreach ($_POST as $key => $value) {
		$lugar->{'set'.$key}($value);
	}

	if(count($endereco->getFields())) $lugar->setEndereco($endereco);

	if(post('idlugarpai') == '' || (int)$lugar->getidlugarpai() == 0) $lugar->setidlugarpai(NULL);

	$lugar->save();

	if(isset($_POST['vllatitude']) && isset($_POST['vllongitude'])){

		if((float)$_POST['vllatitude'] != 0 && (float)$_POST['vllongitude'] != 0){

			if($lugar->getidcoordenada() > 0){
				$c = new Coordenada((int)$lugar->getidcoordenada());
			}else{
				$c = new Coordenada();
			}

			$c->setvllatitude((float)post('vllatitude'));
			$c->setvllongitude((float)post('vllongitude'));
			$c->setnrzoom((float)post('nrzoom'));

			$lugar->setCoordenada($c);

		}

	}

	echo success(array("data"=>$lugar->getFields()));

});

$app->post("/lugares/:idlugar/coordenadas", function($idlugar){

	Permission::checkSession(Permission::ADMIN, true);

	$lugar = new Lugar((int)$idlugar);

	if($lugar->getidcoordenada() > 0){
		$c = new Coordenada((int)$lugar->getidcoordenada());
	}else{
		$c = new Coordenada();
	}

	$c->set($_POST);

	$lugar->setCoordenada($c);

	echo success(array("data"=>$lugar->getFields()));

});

$app->post("/lugares/:idlugar/arquivos", function($idlugar){

	Permission::checkSession(Permission::ADMIN, true);

	$lugar = new Lugar((int)$idlugar);

	$arquivos = Arquivos::upload($_FILES['arquivo']);

	pre($arquivos->getItens());
	exit;

	foreach($arquivos->getItens() as $arquivo){
		$lugar->addArquivo($arquivo);
	}

	echo success(array("data"=>$arquivos->getFields()));

});

$app->delete("/lugares/:idlugar", function($idlugar){

	Permission::checkSession(Permission::ADMIN, true);

	if(!(int)$idlugar){
		throw new Exception("Lugar não informado", 400);		
	}

	$lugar = new Lugar((int)$idlugar);

	if(!(int)$lugar->getidlugar() > 0){
		throw new Exception("Lugar não encontrado", 404);		
	}

	$lugar->remove();

	echo success();

});
/////////////////////////////////////////////////////////////

// lugares tipos

$app->get("/lugares-tipos", function(){

	Permission::checkSession(Permission::ADMIN, true);

	$currentPage = (int)get("pagina");
	$itemsPerPage = (int)get("limite");

	$where = array();

	if(get('deslugartipo')) {
		array_push($where, "deslugartipo LIKE '%".utf8_decode(get('deslugartipo'))."%'");
	}

	if (count($where) > 0) {
		$where = ' WHERE '.implode(' AND ', $where);
	} else {
		$where = '';
	}

	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tb_lugarestipos
	".$where." LIMIT ?, ?;";

	$paginacao = new Pagination(
        $query,
        array(),
        "LugaresTipos",
        $itemsPerPage
    );

	 $lugarestipos = $paginacao->getPage($currentPage);

	echo success(array(
		"data"=> $lugarestipos->getFields(),
		"currentPage"=>$currentPage,
		"itemsPerPage"=>$itemsPerPage,
		"total"=>$paginacao->getTotal(),
	));
});

$app->post("/lugares-tipos", function(){

	Permission::checkSession(Permission::ADMIN, true);

	if(post('idlugartipo') > 0){
		$lugartipo = new LugarTipo((int)post('idlugartipo'));
	}else{
		$lugartipo = new LugarTipo();
	}

	foreach ($_POST as $key => $value) {
		$lugartipo->{'set'.$key}($value);
	}

	$lugartipo->save();

	echo success(array("data"=>$lugartipo->getFields()));

});

$app->delete("/lugares-tipos/:idlugartipo", function($idlugartipo){

	Permission::checkSession(Permission::ADMIN, true);

	if(!(int)$idlugartipo){
		throw new Exception("Tipo de lugar não informado", 400);	
	}

	$lugartipo = new LugarTipo((int)$idlugartipo);

	if(!(int)$lugartipo->getidlugartipo() > 0){
		throw new Exception("Tipo de lugur não encontrado", 404);		
	}

	$lugartipo->remove();
	
	echo success();

});
////////////////////////////////////////////////////////////////

// lugares horarios

$app->post("/lugares-horarios", function(){

	Permission::checkSession(Permission::ADMIN, true);

	$ids = explode(",", post("ids"));

	foreach ($ids as $idlugar) {
		
		$lugar = new Lugar((int)$idlugar);
	
		$horarios = new LugaresHorarios();

		$nrdia = explode(",", post('nrdia'));
		$hrabre = explode(",", post('hrabre'));
		$hrfecha = explode(",", post('hrfecha'));

		for($i = 0; $i < count($nrdia); $i++){

			$horarios->add(new LugarHorario(array(
				'nrdia'=>$nrdia[$i],
				'hrabre'=>$hrabre[$i],
				'hrfecha'=>$hrfecha[$i]
			)));

		}

		$horarios = $lugar->setHorarios($horarios);

	}

	echo success(array("data"=>$horarios->getFields()));

});

?>