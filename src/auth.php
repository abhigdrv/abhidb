<?php

function generate_token() {
    return bin2hex(random_bytes(16));
}

function authenticate_user($token) {
    $users_data = read_json("../databases/users.json");
    if (isset($users_data['users']) && !empty($users_data['users'])) {
        foreach ($users_data['users'] as $username => $user) {
            if (isset($user['token']) && $user['token'] === $token) {
                return $username; // Return the username if token matches
            }
        }
    }

    return null; // Return null if token not found or invalid
}

function get_token_from_header() {
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return null;
    }
    $authorization_header = $_SERVER['HTTP_AUTHORIZATION'];
    if (strpos($authorization_header, 'Bearer ') !== 0) {
        return null;
    }
    $token = substr($authorization_header, 7);
    return $token;
}

function authenticate(){
    if(escapeAuthentication()){
        return;
    }
    $token = get_token_from_header();
    if (!$token) {
        echo "No token found in Authorization header";
        exit;
    }
    $authenticated_user = authenticate_user($token);
    if (!$authenticated_user) {
        echo "Authentication failed";
        exit;
    }else{
        return $authenticated_user;
    }
}

function escapeAuthentication(){
    if(isset($_POST['action']) && ($_POST['action'] === 'create_user' || $_POST['action'] === 'get_token')) return true;
}

?>
