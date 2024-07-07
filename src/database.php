<?php
require_once 'users.php';
// Function to read JSON file
function read_json($filename) {
    $json_data = file_get_contents($filename);
    return json_decode($json_data, true);
}

// Function to write to JSON file
function write_json($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

// Function to get database path
function get_db_path($dbName) {
    return "../databases/{$dbName}/";
}

// Function to create a new database
function create_database($dbName, $userName) {
    $db_path = get_db_path($dbName);
    if (!file_exists($db_path)) {
        mkdir($db_path, 0777, true);
        grant_database_access($userName, $dbName);
        return true; // Return success or failure
    }
    return false; // Database already exists
}

// Function to create a new table in a database
function create_table($dbName, $tableName, $columns, $authenticatedUserName) {
    if(!check_database_access($authenticatedUserName, $dbName)){
        return "Database access restricted!";
    }
    $db_path = get_db_path($dbName);
    $filename = "{$db_path}{$tableName}.json";
    $data = [
        'columns' => $columns,
        'rows' => []
    ];
    write_json($filename, $data);
    return true; // Return success or failure
}

// Function to insert data into a table
function insert_data($dbName, $tableName, $data, $authenticatedUserName) {
    if(!check_database_access($authenticatedUserName, $dbName)){
        return "Database access restricted!";
    }
    $db_path = get_db_path($dbName);
    $filename = "{$db_path}{$tableName}.json";
    $table_data = read_json($filename);
    $error = [];
    foreach ($data as $row) {
        if(verify_columns_match($table_data['columns'], $row)){
            array_push($table_data['rows'], $row);
        }else{
            array_push($error, "Row does not match table columns:" . json_encode($row));
        }
    }
    write_json($filename, $table_data);
    if(count($error) > 0){
        return $error;
    }
    return true; // Return success or failure
}

// Function to retrieve all data from a table
function get_table_data($dbName, $tableName, $authenticatedUserName) {
    if(!check_database_access($authenticatedUserName, $dbName)){
        return "Database access restricted!";
    }
    $db_path = get_db_path($dbName);
    $filename = "{$db_path}{$tableName}.json";
    $table_data = read_json($filename);
    return $table_data;
}

// Function to update data in a table
function update_data($dbName, $tableName, $index, $data) {
    $db_path = get_db_path($dbName);
    $filename = "{$db_path}{$tableName}.json";
    $table_data = read_json($filename);
    if (isset($table_data['rows'][$index])) {
        $table_data['rows'][$index] = $data;
        write_json($filename, $table_data);
        return true; // Return success or failure
    }
    return false; // Data at index not found
}

// Function to delete data from a table
function delete_data($dbName, $tableName, $index) {
    $db_path = get_db_path($dbName);
    $filename = "{$db_path}{$tableName}.json";
    $table_data = read_json($filename);
    if (isset($table_data['rows'][$index])) {
        unset($table_data['rows'][$index]);
        $table_data['rows'] = array_values($table_data['rows']); // Reindex array
        write_json($filename, $table_data);
        return true; // Return success or failure
    }
    return false; // Data at index not found
}

function verify_columns_match($columns, $data) {
    $keys = array_keys($data);
    sort($columns);
    sort($keys);
    if ($columns === $keys) {
        return true;
    } else {
        return false;
    }
}

?>
