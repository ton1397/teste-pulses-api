<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Catch-all route to serve a 404 Not Found page if none of the routes match
 * NOTE: make sure this route is defined last
 */

/**
 * Get Art
 */
// create GET HTTP request
$app->get('/api/dimensao/', function( Request $request, Response $response){
     $sql = "SELECT * FROM dimensao WHERE flg_apagado = 0 ORDER BY descricao ASC";
 
    try {
      // Get DB Object
      $db = new db();
  
      // connect to DB
      $db = $db->connect();
      
      // query
      $stmt = $db->query( $sql );
      $ret = $stmt->fetchAll( PDO::FETCH_OBJ );
      $db = null; // clear db object
      // print out the result as json format
      //echo json_encode( $arts );
      $payload = json_encode($ret);

        $response->getBody()->write($payload);
        return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        
    } catch( PDOException $e ) {
      // show error message as Json format
      echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
});

$app->get('/api/dimensao/{id}', function( Request $request, Response $response, array $args){
  $sql = "SELECT * FROM dimensao WHERE id_dimensao = ". intval($args['id']);

 try {
   // Get DB Object
   $db = new db();

   // connect to DB
   $db = $db->connect();
   
   // query
   $stmt = $db->query( $sql );
   $ret = $stmt->fetchAll( PDO::FETCH_OBJ );
   $db = null; // clear db object
   // print out the result as json format
   //echo json_encode( $arts );
   $payload = json_encode($ret);

     $response->getBody()->write($payload);
     return $response
             ->withHeader('Content-Type', 'application/json')
             ->withStatus(200);
     
 } catch( PDOException $e ) {
   // show error message as Json format
   echo '{"error": {"msg": ' . $e->getMessage() . '}';
 }
});

// create POST HTTP request
$app->post('/api/dimensao/', function( Request $request, Response $response){
    header("Access-Control-Allow-Origin: *");      
    $data = $request->getParsedBody();
    $sql = "INSERT INTO dimensao(descricao) VALUES('".$data['txtDimensao']."')";
   try {
     // Get DB Object
     $db = new db();
 
     // connect to DB
     $db = $db->connect();
     
     // query
     $stmt = $db->query( $sql );
     $db = null; // clear db object
     // print out the result as json format
        
       return $response
               ->withStatus(201);
       
   } catch( PDOException $e ) {
     // show error message as Json format
     echo '{"error": {"msg": ' . $e->getMessage() . '}';
   }
});

// create PATCH HTTP request
$app->patch('/api/dimensao/{id}', function( Request $request, Response $response, array $args){
    header("Access-Control-Allow-Origin: *");      
    $data = $request->getParsedBody();
    $sql = "UPDATE dimensao set descricao = '".$data['dimensao']."'  WHERE id_dimensao = ".intval($args['id']);
   try {
     // Get DB Object
     $db = new db();
 
     // connect to DB
     $db = $db->connect();
     
     // query
     $stmt = $db->query( $sql );
     $db = null; // clear db object
     // print out the result as json format
        
       return $response
               ->withStatus(200);
       
   } catch( PDOException $e ) {
     // show error message as Json format
     echo '{"error": {"msg": ' . $e->getMessage() . '}';
   }
});

// create DELETE HTTP request
$app->delete('/api/dimensao/{id}', function( Request $request, Response $response, array $args){
    header("Access-Control-Allow-Origin: *");      
    $countsql = "SELECT * FROM 
                  dimensao, pergunta WHERE 
                  dimensao.id_dimensao = pergunta.id_dimensao AND 
                  dimensao.id_dimensao = ".intval($args['id'])." AND
                  pergunta.flg_apagado = 0";

    $sql = "UPDATE dimensao set flg_apagado = 1 WHERE id_dimensao = ".intval($args['id']);
   try {
        // Get DB Object
        $db = new db();
    
        // connect to DB
        $db = $db->connect();
        
        // query
        $stmt = $db->prepare( $countsql );
        $stmt->execute();
        $count = $stmt->rowCount();
        // print out the result as json format
        if($count > 0){
            $db = null; // clear db object
            $data = array("Warning Message" => "There's no existent Model!");
            return $response->withStatus(400);
        }else{
            // query
            $stmt = $db->query( $sql );
            $db = null; // clear db object
            // print out the result as json format
                
            return $response
                    ->withStatus(200);  
        }
   } catch( PDOException $e ) {
     // show error message as Json format
     echo '{"error": {"msg": ' . $e->getMessage() . '}';
   }
});
