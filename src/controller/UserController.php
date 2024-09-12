<?php

class UserController
{

  // Obtener la lista de usuarios (GET /api/users)
  public function indexAction($request)
  {

    $queryParams = $request['queryParams'];
    if($queryParams){
      echo json_encode(["message" => "index route with query params" . json_encode($queryParams)]);
      return;
    }
    echo json_encode(["message" => "Listado de usuarios"]);
  }

  public function createAction($request)
  {
  
    $body = $request['body'];
    echo json_encode(["message" => "Usuario creado: " . json_encode($body)]);
  }

  public function showAction($request)
  {

    $params = $request['params'];
    echo json_encode(["message" => "Usuario con el idxx " . $params['id']]);
  }

  public function updateAction($id)
  {
    echo json_encode(["message" => "Usuario con ID $id actualizado"]);
  }

  public function deleteAction($id)
  {
    echo json_encode(["message" => "Usuario con ID $id eliminado"]);
  }

  public function showViewAction()
  {
    include "./src/views/hero.view.php";
  }
}
