<?php

// Function to create a new user (for demonstration purposes)
function create_user($username, $password) {
    $users_data = read_json("../users.json");
    if (!isset($users_data['users'][$username])) {
        $users_data['users'][$username] = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'databases' => [] // Initialize with no accessible databases
        ];
        write_json("../users.json", $users_data);
        return true; // Return success
    }
    return false; // User already exists
}

// Function to grant access to a database for a user
function grant_database_access($username, $dbName) {
    $users_data = read_json("../users.json");
    if (isset($users_data['users'][$username])) {
        $users_data['users'][$username]['databases'][] = $dbName;
        write_json("../users.json", $users_data);
        return true; // Return success
    }
    return false; // User not found
}

// Function to revoke access to a database for a user
function revoke_database_access($username, $dbName) {
    $users_data = read_json("../users.json");
    if (isset($users_data['users'][$username])) {
        $key = array_search($dbName, $users_data['users'][$username]['databases']);
        if ($key !== false) {
            unset($users_data['users'][$username]['databases'][$key]);
            write_json("../users.json", $users_data);
            return true; // Return success
        }
    }
    return false; // User or database not found
}

?>
