<?php 

$app->get("/files", function(){

    Permission::checkSession(Permission::ADMIN, true);

    $currentPage = (int)get("pagina");
    $itemsPerPage = (int)get("limite");

    $where = array();

    if (get('desafile')) {
        array_push($where, "desalias LIKE '%".get('desafile')."%'");
    }

    if (get('desextension')) {
        array_push($where, "desextension = '".get('desextension')."'");
    }

    if (count($where) > 0) {
        $where = 'WHERE '.implode(' AND ', $where);
    } else {
        $where = '';
    }

    $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM tb_files '.$where.' LIMIT ?, ?';

    $pagination = new Pagination(
        $query,
        array(),
        "Files",
        $itemsPerPage
    );

    $files = $pagination->getPage($currentPage); 

    echo success(array(
        "data"=>$files->getFields(),
        "currentPage"=>$currentPage,
        "itemsPerPage"=>$itemsPerPage,
        "total"=>$pagination->getTotal(),
    ));
  

});

$app->get("/files-upload_max_filesize", function(){

    echo success(array(
        "data"=>parse_size(ini_get('upload_max_filesize'))
    ));

});

$app->post("/files", function(){

    Permission::checkSession(Permission::ADMIN, true);

    $files = Files::upload($_FILES['file']);
    
    echo success(array(
        'data'=>$files->getFields()
    ));

});

$app->delete("/files/:idfile", function($idfile){

    Permission::checkSession(Permission::ADMIN, true);

    if(!(int)$idfile){
        throw new Exception("Arquivo não informado", 400);        
    }

    $file = new File((int)$idfile);

    if(!(int)$file->getidfile() > 0){
        throw new Exception("Arquivo não encontrado", 404);        
    }

    $file->remove();

    echo success();

});

$app->delete("/files", function(){

    Permission::checkSession(Permission::ADMIN, true);

    $ids = explode(",", post('ids'));

    foreach ($ids as $idfile) {

        $file = new File(array(
            'idfile'=>(int)$idfile
        ));
        $file->remove();

    }

    echo success();

});

 ?>