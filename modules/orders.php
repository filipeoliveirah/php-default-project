<?php

// pedidos
/*
$app->get("/orders/all", function(){

    Permission::checkSession(Permission::ADMIN, true);

    echo success(array("data"=>orders::listAll()->getFields()));

});
*/
$app->get("/orders", function(){

    Permission::checkSession(Permission::ADMIN, true);

    $where = array();

    if(isset($_GET['desperson'])){
        if($_GET['desperson'] != '') array_push($where, "b.desperson LIKE '%".utf8_decode(get('desperson'))."%'");        
    }

    if(isset($_GET['idformpayment'])){
        array_push($where, "c.idformpayment = ".((int)get('idformpayment')));
    }

    if(isset($_GET['idstatus'])){
        array_push($where, "d.idstatus = ".((int)get('idstatus')));
    }

    if(isset($_GET['dtstart']) && isset($_GET['dtend'])){
        if($_GET['dtstart'] != '' && $_GET['dtend'] != ''){
            array_push($where, "a.dtregister BETWEEN '".get('dtstart')."' AND '".get('dtend')."'");
        }
    }

    if(isset($_GET['idorder'])){
        if($_GET['idorder'] != '') array_push($where, "a.idorder = ".(int)get('idorder'));
    }

    if(count($where) > 0){
        $where = "WHERE ".implode(" AND ", $where);
    }else{
        $where = "";
    }

    $query = "
    SELECT SQL_CALC_FOUND_ROWS a.*, b.desperson, c.desformpayment, d.desstatus FROM tb_orders a
        LEFT JOIN tb_persons b ON a.idperson = b.idperson
        LEFT JOIN tb_formspayments c ON a.idformpayment = c.idformpayment
        LEFT JOIN tb_ordersstatus d ON a.idstatus = d.idstatus
    ".$where." ORDER BY b.desperson LIMIT ?, ?;";

    $pagina = (int)get('pagina');
    $itemsPerPage = (int)get('limite');

    $pagination = new Pagination(
        $query,
        array(),
        'Orders',
        $itemsPerPage
    );

    $orders = $pagination->getPage($pagina);

    echo success(array(
        "data"=>$orders->getFields(),
        "total"=>$pagination->getTotal(),
        "currentPage"=>$pagina,
        "itemsPerPage"=>$itemsPerPage
    ));

});

$app->post("/orders", function(){

    Permission::checkSession(Permission::ADMIN, true);

    if(post('idorder') > 0){
        $order = new Order((int)post('idorder'));
    }else{
        $order = new Order();
    }

    foreach ($_POST as $key => $value) {
        $order->{'set'.$key}($value);
    }

    $order->save();

    echo success(array("data"=>$order->getFields()));

});

$app->delete("/orders/:idorder", function($idorder){

    Permission::checkSession(Permission::ADMIN, true);

    if(!(int)$idorder){
        throw new Exception("Pedido não informado", 400);
    }

    $order = new Order((int)$idorder);

    if(!(int)$order->getidorder() > 0){
        throw new Exception("Pedido não encontrado", 404);        
    }

    $order->remove();

    echo success();

});
/////////////////////////////////////////////////////////////

// pedidos status
$app->get("/orders-status/all", function(){

    Permission::checkSession(Permission::ADMIN, true);

    $currentPage = (int)get("pagina");
    $itemsPerPage = (int)get("limite");

    $where =array();

    if(get('desstatus')) {
        array_push($where, "desstatus LIKE '%".get('desstatus')."%'");
    }

   if (count($where) > 0) {
        $where = ' WHERE '.implode(' AND ', $where);
    } else {
        $where = '';
    }
    
    $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tb_ordersstatus
    ".$where." LIMIT ?, ?;";

    $pagination = new Pagination(
        $query,
        array(),
        "OrdersStatus",
        $itemsPerPage
    );

    $ordersstatus = $pagination->getPage($currentPage);

     echo success(array(
        "data"=>$ordersstatus->getFields(),
        "currentPage"=>$currentPage,
        "itemsPerPage"=>$itemsPerPage,
        "total"=>$pagination->getTotal(),

    ));

});

$app->post("/orders-status", function(){

    Permission::checkSession(Permission::ADMIN, true);

    if(post('idstatus') > 0){
        $status = new OrderStatus((int)post('idstatus'));
    }else{
        $status = new OrderStatus();
    }

    foreach ($_POST as $key => $value) {
        $status->{'set'.$key}($value);
    }

    $status->save();

    echo success(array("data"=>$status->getFields()));

});

$app->delete("/orders-status/:idstatus", function($idstatus){

    Permission::checkSession(Permission::ADMIN, true);

    if(!(int)$idstatus){
        throw new Exception("Status não informado", 400);        
    }

    $status = new OrderStatus((int)$idstatus);

    if(!(int)$status->getidstatus() > 0){
        throw new Exception("Status não encontrado", 404);        
    }

    $status->remove();

    echo success();

});
///////////////////////////////////////////////////

// pedidos produtos
$app->get("/orders-products/all", function(){

    Permission::checkSession(Permission::ADMIN, true);

    echo success(array("data"=>OrdersProducts::listAll()->getFields()));

});

$app->post("/orders-products", function(){

    Permission::checkSession(Permission::ADMIN, true);

    if(post('idorder') > 0 && post('idproduct') > 0){
        $order = new OrderProduct((int)post('idorder'), (int)post('idproduct'));
    }else{
        $order = new OrderProduct();
    }

    foreach ($_POST as $key => $value) {
        $order->{'set'.$key}($value);
    }

    $order->save();

    echo success(array("data"=>$order->getFields()));

});

$app->delete("/orders/:idorder/products/:idproduct", function($idorder, $idproduct){

    Permission::checkSession(Permission::ADMIN, true);

    if(!(int)$idorder){
        throw new Exception("Pedido não informado", 400);        
    }

    if(!(int)$idproduct){
        throw new Exception("Produto não informado", 400);        
    }

    $order = new OrderProduct((int)$idorder, (int)$idproduct);

    if(!(int)$order->getidorder() > 0 && !(int)$order->getidproduct() > 0){
        throw new Exception("Recurso não encontrado", 404);        
    }

    $order->remove();

    echo success();

});
//////////////////////////////////////////////////////////////////////

// pedidos recibos
$app->get("/orders-receipts/all", function(){

    Permission::checkSession(Permission::ADMIN, true);

    echo success(array("data"=>OrdersReceipts::listAll()->getFields()));

});

$app->post("/orders-receipts", function(){

    Permission::checkSession(Permission::ADMIN, true);

    if(post('idorder') > 0){
        $receipt = new OrderReceipt((int)post('idorder'));
    }else{
        $receipt = new OrderReceipt();
    }

    foreach ($_POST as $key => $value) {
        $receipt->{'set'.$key}($value);
    }

    $receipt->save();

    echo success(array("data"=>$receipt->getFields()));

});

$app->delete("/orders-receipts/:idorder", function($idorder){

    Permission::checkSession(Permission::ADMIN, true);

    if(!(int)$idorder){
        throw new Exception("Pedido não informado", 400);        
    }

    $receipt = new OrderReceipt((int)$idorder);

    if(!(int)$receipt->getidorder() > 0){
        throw new Exception("Pedido não encontrado", 404);        
    }

    $receipt->remove();

    echo success();

});

// recibos
$app->get("/orders/:idorder/receipts", function($idorder){

    Permission::checkSession(Permission::ADMIN, true);

    $order = new Order((int)$idorder);

    echo success(array("data"=>$order->getReceipts()->getFields()));

});
//////////////////////////////////////////////////////////////

// pedidos historicos
$app->get("/orders/logs/all", function(){

    Permission::checkSession(Permission::ADMIN, true);

    echo success(array("data"=>OrdersLogs::listAll()->getFields()));

});

$app->post("/orders-logs", function(){

    Permission::checkSession(Permission::ADMIN, true);

    if((int)post('idlog') > 0){
        $log = new OrderLog((int)post('idlog'));
    }else{
        $log = new OrderLog();
    }

    $log->set($_POST);

    $log->save();

    echo success(array("data"=>$log->getFields()));

});

$app->delete("/orders-logs/:idlog", function($idlog){

    Permission::checkSession(Permission::ADMIN, true);

    if(!(int)$idlog){
        throw new Exception("Histórico não informado", 400);        
    }

    $log = new OrderLog((int)$idlog);

    if(!(int)$log->getidlog() > 0){
        throw new Exception("Histórico não encontrado", 404);        
    }

    $log->remove();

    echo success();

});

//////////////////////////////////////////////////////////////

// pedidosnegociacoestipos
$app->get("/ordersnegotiationstypes", function(){

    Permission::checkSession(Permission::ADMIN, true);

    $currentPage = (int)get("pagina");
    $itemsPerPage = (int)get("limite");

    $where = array();

    if(get('desnegotiation')) {
        array_push($where, "desnegotiation LIKE '%".get('desnegotiation')."%'");
    }

    if (count($where) > 0) {
        $where = ' WHERE '.implode(' AND ', $where);
    } else {
        $where = '';
    }

    $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tb_ordersnegotiationstypes
    ".$where." limit ?, ?;";

    $pagination = new Pagination(
        $query,
        array(),
        "OrdersNegotiationsTypes",
        $itemsPerPage
    );

    $ordersnegotiationstypes = $pagination->getPage($currentPage);

    echo success(array(
        "data"=>$ordersnegotiationstypes->getFields(),
        "currentPage"=>$currentPage,
        "itemsPerPage"=>$itemsPerPage,
        "total"=>$pagination->getTotal(),

    ));

});

$app->post("/ordersnegotiationstypes", function(){

    Permission::checkSession(Permission::ADMIN, true);

    if((int)post('idnegotiation') > 0){
        $order = new OrderNegotiationType((int)post('idnegotiation'));
    }else{
        $order = new OrderNegotiationType();
    }

    $order->set($_POST);

    $order->save();

    echo success(array("data"=>$order->getFields()));

});

$app->delete("/ordersnegotiationstypes/:idnegotiation", function($idnegotiation){

    Permission::checkSession(Permission::ADMIN, true);

    if(!(int)$idnegotiation){
        throw new Exception("Pedido não informado", 400);        
    }

    $order = new OrderNegotiationType((int)$idnegotiation);

    if(!(int)$order->getidnegotiation() > 0){
        throw new Exception("Pedido não encontrado", 404);        
    }

    $order->remove();

    echo success();

});

?>