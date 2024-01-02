<?php
$jsonFile = 'users.json';
$data = file_get_contents($jsonFile);
$users = json_decode($data, true);
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
switch ($method | $uri) {
   case ($method == 'GET' && $uri == '/kens_api/users'):
       header('Content-Type: application/json');
       echo json_encode($users, JSON_PRETTY_PRINT);
       break;
   case ($method == 'GET' && preg_match('/\/kens_api\/users\/[1-9]/', $uri)):
        header('Content-Type: application/json');
        $id = basename($uri);
        if (!array_key_exists($id, $users)) {
            http_response_code(404);
            echo json_encode(['error' => 'user does not exist']);
            break;
        }
       $responseData = [$id => $users[$id]];
       echo json_encode($responseData, JSON_PRETTY_PRINT);
       break;
   case ($method == 'POST' && $uri == '/kens_api/users'):
       header('Content-Type: application/json');
       $requestBody = json_decode(file_get_contents('php://input'), true);
       $name = $requestBody['name'];
       if (empty($name)) {
           http_response_code(404);
           echo json_encode(['error' => 'Please add name of the user']);
       }
       $users[] = $name;
       $data = json_encode($users, JSON_PRETTY_PRINT);
       file_put_contents($jsonFile, $data);
       echo json_encode(['message' => 'user added successfully']);
       break;
   case ($method == 'PUT' && preg_match('/\/kens_api\/users\/[1-9]/', $uri)):
       header('Content-Type: application/json');
       $id = basename($uri);
       if (!array_key_exists($id, $users)) {
           http_response_code(404);
           echo json_encode(['error' => 'user does not exist']);
           break;
       }
       $requestBody = json_decode(file_get_contents('php://input'), true);
       $name = $requestBody['name'];
       if (empty($name)) {
           http_response_code(404);
           echo json_encode(['error' => 'Please add name of the user']);
       }
       $users[$id] = $name;
       $data = json_encode($users, JSON_PRETTY_PRINT);
       file_put_contents($jsonFile, $data);
       echo json_encode(['message' => 'user updated successfully']);
       break;
   case ($method == 'DELETE' && preg_match('/\/kens_api\/users\/[1-9]/', $uri)):
       header('Content-Type: application/json');
       $id = basename($uri);
       if (empty($users[$id])) {
           http_response_code(404);
           echo json_encode(['error' => 'user does not exist']);
           break;
       }
       unset($users[$id]);
       $data = json_encode($users, JSON_PRETTY_PRINT);
       file_put_contents($jsonFile, $data);
       echo json_encode(['message' => 'user deleted successfully']);
       break;
   default:
       http_response_code(404);
       echo json_encode(['error' => "We cannot find what you're looking for."]);
       break;
}