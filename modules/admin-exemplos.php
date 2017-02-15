<?php 

$app->get("/".DIR_ADMIN."/exemplos/upload", function(){

    Permissao::checkSession(Permissao::ADMIN, true);

    $page = new AdminPage(array(
        'data'=>array(
            'head_title'=>'Administração'
        )
    ));

    $page->setTpl('/admin/exemplos-upload');

});

$app->post("/".DIR_ADMIN."/exemplos/upload-form-exemplo-2", function(){
	
	$file = $_FILES['arquivo'];

	$arquivo = Arquivo::upload(
		$file['name'],
		$file['type'],
		$file['tmp_name'],
		$file['error'],
		$file['size']
	);
	
	echo success(array(
		'data'=>$arquivo->getFields()
	));

});

$app->post("/".DIR_ADMIN."/exemplos/upload-form-exemplo-3", function(){

	$file = $_FILES['desarquivo'];

	$arquivo = Arquivo::upload(
		$file['name'],
		$file['type'],
		$file['tmp_name'],
		$file['error'],
		$file['size']
	);
	
	echo success(array(
		'data'=>$arquivo->getFields()
	));

});

$app->post("/".DIR_ADMIN."/exemplos/upload-form-exemplo-4", function(){

	$arquivo = Arquivo::download(post('desurl'));
	
	echo success(array(
		'data'=>$arquivo->getFields()
	));

});

?>