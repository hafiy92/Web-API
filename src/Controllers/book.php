<?php

use Slim\Http\Request; //namespace
use Slim\Http\Response; //namespace



//search 
$app->get("/book/search/", function (Request $request, Response $response, array $args){
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM book WHERE price LIKE '%$keyword%' OR name LIKE '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $mainCount=$stmt->rowCount();
    $result = $stmt->fetchAll();
    if($mainCount==0) {
        return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
    }
    return $response->withJson(["status" => "success", "data" => $result], 200);
});


//update data
$app->put('/book/update/', function (Request $request, Response $response, array $args) {
    $keyword = $request->getQueryParam("keyword");
    $input = $request->getParsedBody();
    
   
    $name=trim(strip_tags($input['name']));
    $price=trim(strip_tags($input['price']));
    $quantity=trim(strip_tags($input['quantity']));
    
    $sql = "UPDATE book SET name=:name, price=:price, quantity=:quantity WHERE price LIKE '%$keyword%' OR name LIKE '%$keyword%'";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("name", $name);                       
    $sth->bindParam("price", $price);  
    $sth->bindParam("quantity", $quantity);               
         
    $StatusUpdate=$sth->execute();
    if($StatusUpdate){
        return $this->response->withJson(['status' => 'success','data'=>'success update product.'],200); 
    } else {
        return $this->response->withJson(['status' => 'error','data'=>'error update product.'],200); 
    }
});

//delete data 
    $app->delete('/book/delete/', function (Request $request, Response $response, array $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "DELETE FROM book WHERE name LIKE '%$keyword%'OR price LIKE '%$keyword%'";
        $sth = $this->db->prepare($sql);
            
        $StatusDelete=$sth->execute();
        if($StatusDelete){
            return $this->response->withJson(['status' => 'success','data'=>'success delete product.'],200); 
        } else {
            return $this->response->withJson(['status' => 'error','data'=>'error delete product.'],200); 
        }
    });