<?php

include("./connection.php");


$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username'], $data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing username or password"]);
    exit;
}

$username = $data['username'];
$password = $data['password'];

try {
    // Check if it is Admin
    $adminQuery = $connection->prepare("SELECT * FROM Admin WHERE Username = :username");
    $adminQuery->bindParam(':username', $username, PDO:: PARAM_STR);
    $adminQuery->execute();

    $admin = $adminQuery->fetch(PDO::FETCH_ASSOC);


    if ($admin && password_verify($password, $admin['Password'])) {
        http_response_code(200);
        echo json_encode([
            "message" => "Admin login successful",
            "role" => "admin",
            "admin" => [
                "id" => $admin['Admin_ID'],
                "username" => $admin['Username'],
                "email" => $admin['Email']
            ]
        ]);
        exit;
    }

    // Check if it is Normal User
    $userQuery = $connection->prepare("SELECT * FROM User WHERE Username = :username");
    $userQuery->bindParam(':username', $username, PDO::PARAM_STR);
    $userQuery->execute();

    $user = $userQuery->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        http_response_code(200);
        echo json_encode([
            "message" => "User login successful",
            "role" => "user",
            "user" => [
                "id" => $user['User_ID'],
                "username" => $user['Username'],
                "email" => $user['Email']
            ]
        ]);
        exit;
    }

    // If there is no match
    http_response_code(401); // Unauthorized
    echo json_encode(["message" => "Invalid credentials"]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
