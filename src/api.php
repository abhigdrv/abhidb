<?php

// Include necessary files
require_once 'auth.php';
require_once 'database.php';
require_once 'users.php';

// API endpoint for creating a database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_database') {
    $token = $_POST['token'];
    $dbName = $_POST['dbName'];
    $result = create_database($dbName, $token);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for creating a table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_table') {
    $token = $_POST['token'];
    $dbName = $_POST['dbName'];
    $tableName = $_POST['tableName'];
    $columns = json_decode($_POST['columns'], true);
    $result = create_table($dbName, $tableName, $columns, $token);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for inserting data into a table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert_data') {
    $token = $_POST['token'];
    $dbName = $_POST['dbName'];
    $tableName = $_POST['tableName'];
    $data = json_decode($_POST['data'], true);
    $result = insert_data($dbName, $tableName, $data, $token);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for retrieving data from a table
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_table_data') {
    $token = $_GET['token'];
    $dbName = $_GET['dbName'];
    $tableName = $_GET['tableName'];
    $result = get_table_data($dbName, $tableName, $token);
    echo json_encode($result);
    exit;
}

// API endpoint for updating data in a table
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_PUT['action']) && $_PUT['action'] === 'update_data') {
    parse_str(file_get_contents("php://input"), $_PUT);
    $token = $_PUT['token'];
    $dbName = $_PUT['dbName'];
    $tableName = $_PUT['tableName'];
    $index = $_PUT['index'];
    $data = json_decode($_PUT['data'], true);
    $result = update_data($dbName, $tableName, $index, $data, $token);
    echo json_encode(['success' => $result]);
    exit;
}

// API endpoint for deleting data from a table
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_DELETE['action']) && $_DELETE['action'] === 'delete_data') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $token = $_DELETE['token'];
    $dbName = $_DELETE['dbName'];
    $tableName = $_DELETE['tableName'];
    $index = $_DELETE['index'];
    $result = delete_data($dbName, $tableName, $index, $token);
    echo json_encode(['success' => $result]);
    exit;
}

// Handle other API endpoints as needed...

?>
