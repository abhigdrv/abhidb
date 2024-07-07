<?php

// Include necessary files
require_once 'auth.php';
require_once 'database.php';
require_once 'users.php';

$authenticatedUserName = authenticate();

// API endpoint for creating a database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_database') {
    $dbName = $_POST['dbName'];
    $result = create_database($dbName, $authenticatedUserName);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for creating a table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_table') {
    $dbName = $_POST['dbName'];
    $tableName = $_POST['tableName'];
    $columns = json_decode($_POST['columns'], true);
    $result = create_table($dbName, $tableName, $columns, $authenticatedUserName);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for inserting data into a table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert_data') {
    $dbName = $_POST['dbName'];
    $tableName = $_POST['tableName'];
    $data = json_decode($_POST['data'], true);
    $result = insert_data($dbName, $tableName, $data, $authenticatedUserName);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for retrieving data from a table
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_table_data') {
    $dbName = $_GET['dbName'];
    $tableName = $_GET['tableName'];
    $result = get_table_data($dbName, $tableName, $authenticatedUserName);
    echo json_encode($result);
    exit;
}

// API endpoint for retrieving users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_users') {
    $users = get_database_users();
    $response = [
        'success' => true,
        'users' => $users
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// API endpoint for retrieving token
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_token') {
    $userName = $_POST['userName'];
    $password = $_POST['password'];
    $token = get_token($userName, $password);
    $response = [
        'success' => true,
        'token' => $token
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// API endpoint for creating users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_user') {
    $userName = $_POST['userName'];
    $password = $_POST['password'];
    $user = create_user($userName, $password);
    $response = [
        'success' => true,
        'user' => $user
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// API endpoint for updating data in a table
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_PUT['action']) && $_PUT['action'] === 'update_data') {
    parse_str(file_get_contents("php://input"), $_PUT);
    $dbName = $_PUT['dbName'];
    $tableName = $_PUT['tableName'];
    $index = $_PUT['index'];
    $data = json_decode($_PUT['data'], true);
    $result = update_data($dbName, $tableName, $index, $data);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for deleting data from a table
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_DELETE['action']) && $_DELETE['action'] === 'delete_data') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $dbName = $_DELETE['dbName'];
    $tableName = $_DELETE['tableName'];
    $index = $_DELETE['index'];
    $result = delete_data($dbName, $tableName, $index);
    echo json_encode(['success' => $result]);
    exit;
}

// Handle other API endpoints as needed...

?>
