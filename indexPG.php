<?php

$host = 'localhost';
$username = 'your_postgres_user';
$password = 'your_postgres_password';
$database = 'your_postgres_database';
$port = 5432; // Change the port if your PostgreSQL server is running on a different port

header("Access-Control-Allow-Origin: *");

$conn = pg_connect("host=$host port=$port dbname=$database user=$username password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case ($method == 'GET' && $uri == '/kens_api/users'):
        header('Content-Type: application/json');
        $result = pg_query($conn, "SELECT * FROM users");
        $users = array();
        while ($row = pg_fetch_assoc($result)) {
            $users[$row['user_id']] = $row;
        }
        echo json_encode($users, JSON_PRETTY_PRINT);
        break;

    case ($method == 'GET' && preg_match('/\/kens_api\/users\/[1-9]/', $uri)):
        header('Content-Type: application/json');
        $id = basename($uri);
        $result = pg_query($conn, "SELECT * FROM users WHERE user_id = $id");
        if (pg_num_rows($result) == 0) {
            http_response_code(404);
            echo json_encode(['error' => 'user does not exist']);
            break;
        }
        $responseData = pg_fetch_assoc($result);
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        break;

    case ($method == 'POST' && $uri == '/kens_api/users'):
        header('Content-Type: application/json');
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $name = $requestBody['username'];
        $email = $requestBody['user_email'];
        $status = $requestBody['user_status'];
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['error' => 'Please add the name of the user']);
            break;
        }
        pg_query($conn, "INSERT INTO users (username, user_email, user_status ) VALUES ('$name', '$email', '$status')");
        echo json_encode(['message' => 'user added successfully']);
        break;

    case ($method == 'PUT' && preg_match('/\/kens_api\/users\/[1-9]/', $uri)):
        header('Content-Type: application/json');
        $id = basename($uri);
        $result = pg_query($conn, "SELECT * FROM users WHERE user_id = $id");
        if (pg_num_rows($result) == 0) {
            http_response_code(404);
            echo json_encode(['error' => 'user does not exist']);
            break;
        }
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $name = $requestBody['username'];
        $email = $requestBody['user_email'];
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['error' => 'Please add the name of the user']);
            break;
        }
        pg_query($conn, "UPDATE users SET username = '$name', user_email = '$email' WHERE user_id = $id");
        echo json_encode(['message' => 'user updated successfully']);
        break;

    case ($method == 'DELETE' && preg_match('/\/kens_api\/users\/[1-9]/', $uri)):
        header('Content-Type: application/json');
        $id = basename($uri);
        $result = pg_query($conn, "SELECT * FROM users WHERE user_id = $id");
        if (pg_num_rows($result) == 0) {
            http_response_code(404);
            echo json_encode(['error' => 'user does not exist']);
            break;
        }
        pg_query($conn, "DELETE FROM users WHERE user_id = $id");
        echo json_encode(['message' => 'user deleted successfully']);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => "We cannot find what you're looking for."]);
        break;
}

pg_close($conn);

?>
