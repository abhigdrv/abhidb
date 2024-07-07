<?php

require_once 'auth.php';
// Function to get all database users
function get_database_users() {
    $users_data = read_json("../databases/users.json");
    if (isset($users_data['users'])) {
        return $users_data['users'];
    }
    return []; // Return empty array if no users found
}

function get_token($userName, $password){
    $users_data = read_json("../databases/users.json");
    if (isset($users_data['users'])) {
        $user = $users_data['users'][$userName];
        if(isset($user) && password_verify($password, $user['password'])){
            return $user['token'];
        }
    }
    return "Credential mismatch"; // Return empty array if no users found
}

// Function to create a new user (for demonstration purposes)
function create_user($username, $password) {
    $users_data = read_json("../databases/users.json");
    if (!isset($users_data['users'][$username])) {
        $users_data['users'][$username] = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'databases' => [], // Initialize with no accessible databases
            'token' => generate_token()
        ];
        write_json("../databases/users.json", $users_data);
        return $users_data['users'][$username]; // Return success
    }
    return false; // User already exists
}

// Function to grant access to a database for a user
function grant_database_access($username, $dbName) {
    $users_data = read_json("../databases/users.json");
    if (isset($users_data['users'][$username])) {
        $users_data['users'][$username]['databases'][] = $dbName;
        write_json("../databases/users.json", $users_data);
        return true; // Return success
    }
    return false; // User not found
}

// Function to revoke access to a database for a user
function revoke_database_access($username, $dbName) {
    $users_data = read_json("../databases/users.json");
    if (isset($users_data['users'][$username])) {
        $key = array_search($dbName, $users_data['users'][$username]['databases']);
        if ($key !== false) {
            unset($users_data['users'][$username]['databases'][$key]);
            write_json("../databases/users.json", $users_data);
            return true; // Return success
        }
    }
    return false; // User or database not found
}

// Function to check if a user has access to a specific database
function check_database_access($username, $dbName) {
    $users_data = read_json("../databases/users.json");
    if (isset($users_data['users'][$username])) {
        if (in_array($dbName, $users_data['users'][$username]['databases'])) {
            return true;
        }
    }
    return false;
}

?>
