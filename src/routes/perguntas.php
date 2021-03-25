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
$app->get('/api/perguntas/filtro/{filtro}', function (Request $request, Response $response, array $args) {
  $sql = null;
  if ($args['filtro'] == 0) {
    $sql = "SELECT 
              p.id_pergunta, 
              p.descricao AS descricao_pergunta, 
              p.flg_ativo, d.id_dimensao, 
              d.descricao AS descricao_dimensao FROM 
              pergunta p, 
              dimensao d WHERE 
              p.id_dimensao = d.id_dimensao AND 
              p.flg_apagado = 0";    
  }else{
    $sql = "SELECT 
              p.id_pergunta, 
              p.descricao AS descricao_pergunta, 
              p.flg_ativo, 
              d.id_dimensao, 
              d.descricao AS descricao_dimensao FROM 
              pergunta p, 
              dimensao d WHERE 
              p.id_dimensao = d.id_dimensao AND 
              p.flg_apagado = 0 AND 
              d.id_dimensao = ".intval($args['filtro']);
  }

  try {
    // Get DB Object
    $db = new db();

    // connect to DB
    $db = $db->connect();

    // query
    $stmt = $db->query($sql);
    $ret = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null; // clear db object
    // print out the result as json format
    //echo json_encode( $arts );
    $payload = json_encode($ret);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
  } catch (PDOException $e) {
    // show error message as Json format
    echo '{"error": {"msg": ' . $e->getMessage() . '}';
  }
});

$app->get('/api/perguntas/{id}', function (Request $request, Response $response, array $args) {
  $sql = "SELECT * FROM pergunta WHERE id_pergunta = " . $args['id'];

  try {
    // Get DB Object
    $db = new db();

    // connect to DB
    $db = $db->connect();

    // query
    $stmt = $db->query($sql);
    $ret = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null; // clear db object
    // print out the result as json format
    //echo json_encode( $arts );
    $payload = json_encode($ret);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
  } catch (PDOException $e) {
    // show error message as Json format
    echo '{"error": {"msg": ' . $e->getMessage() . '}';
  }
});

// create POST HTTP request
$app->post('/api/perguntas/', function (Request $request, Response $response) {
  header("Access-Control-Allow-Origin: *");
  $data = $request->getParsedBody();
  $sql = "INSERT INTO pergunta(descricao, id_dimensao) VALUES('" . $data['txtPergunta'] . "'," . $data['IdDimensao'] . ")";
  try {
    // Get DB Object
    $db = new db();

    // connect to DB
    $db = $db->connect();

    // query
    $stmt = $db->query($sql);
    $db = null; // clear db object
    // print out the result as json format

    return $response
      ->withStatus(201);
  } catch (PDOException $e) {
    // show error message as Json format
    echo '{"error": {"msg": ' . $e->getMessage() . '}';
  }
});

// create PATCH HTTP request
$app->patch('/api/perguntas/{id}', function (Request $request, Response $response, array $args) {
  header("Access-Control-Allow-Origin: *");
  $data = $request->getParsedBody();
  $sql = "UPDATE pergunta SET 
            descricao = '" . $data['pergunta'] . "', 
            id_dimensao = " . intval($data['IdDimensao']) . " WHERE 
            id_pergunta = " . intval($args['id']);
  try {
    // Get DB Object
    $db = new db();

    // connect to DB
    $db = $db->connect();

    // query
    $stmt = $db->query($sql);
    $db = null; // clear db object
    // print out the result as json format

    return $response
      ->withStatus(200);
  } catch (PDOException $e) {
    // show error message as Json format
    echo '{"error": {"msg": ' . $e->getMessage() . '}';
  }
});

// create PUT HTTP request
$app->patch('/api/perguntas/ativo/{id}', function (Request $request, Response $response, array $args) {
  header("Access-Control-Allow-Origin: *");
  $data = $request->getParsedBody();
  $sql = "UPDATE pergunta set flg_ativo = " . intval($data['flg_ativo']) . " WHERE id_pergunta = " . intval($args['id']);
  try {
    // Get DB Object
    $db = new db();

    // connect to DB
    $db = $db->connect();

    // query
    $stmt = $db->query($sql);
    $db = null; // clear db object
    // print out the result as json format

    return $response
      ->withStatus(200);
  } catch (PDOException $e) {
    // show error message as Json format
    echo '{"error": {"msg": ' . $e->getMessage() . '}';
  }
});

// create PUT HTTP request
$app->delete('/api/perguntas/{id}/', function (Request $request, Response $response, array $args) {
  header("Access-Control-Allow-Origin: *");
  $sql = "UPDATE pergunta set flg_apagado = 1 WHERE id_pergunta = " . intval($args['id']);
  try {
    // Get DB Object
    $db = new db();

    // connect to DB
    $db = $db->connect();

    // query
    $stmt = $db->query($sql);
    $db = null; // clear db object
    // print out the result as json format

    return $response
      ->withStatus(200);
  } catch (PDOException $e) {
    // show error message as Json format
    echo '{"error": {"msg": ' . $e->getMessage() . '}';
  }
});
