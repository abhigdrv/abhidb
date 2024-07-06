<?php

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
function create_database($dbName) {
    $db_path = get_db_path($dbName);
    if (!file_exists($db_path)) {
        mkdir($db_path, 0777, true);
        return true; // Return success or failure
    }
    return false; // Database already exists
}

// Function to create a new table in a database
function create_table($dbName, $tableName, $columns) {
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
function insert_data($dbName, $tableName, $data) {
    $db_path = get_db_path($dbName);
    $filename = "{$db_path}{$tableName}.json";
    $table_data = read_json($filename);
    $table_data['rows'][] = $data;
    write_json($filename, $table_data);
    return true; // Return success or failure
}

// Function to retrieve all data from a table
function get_table_data($dbName, $tableName) {
    $db_path = get_db_path($dbName);
    $filename = "{$db_path}{$tableName}.json";
    return read_json($filename);
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

?>
