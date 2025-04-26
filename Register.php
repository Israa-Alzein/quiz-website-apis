<?php 

include("./connection.php");

// Get the raw posted data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is available
if (!isset($data['username'], $data['email'], $data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing fields"]);
    exit;
}

$username = $data['username'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); 

try {
    // Check if user already exists
    $query = $connection->prepare("SELECT * FROM User WHERE Username = :username OR Email = :email");

    $query->bindParam(':username', $username, PDO::PARAM_STR_CHAR );
    $query->bindParam(':email', $email, PDO::PARAM_STR_CHAR);

    $query->execute();

    if ($query->rowCount() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "User already exists"]);
        exit;
    }

    // Insert the new user
    $insert = $connection->prepare("INSERT INTO User (Email, Username, Password) VALUES (:email, :username, :password)");

    $insert->bindParam(':username', $username, PDO::PARAM_STR_CHAR );
    $insert->bindParam(':email', $email, PDO::PARAM_STR_CHAR);
    $insert->bindParam(':password', $password, PDO::PARAM_STR_CHAR);

    $insert->execute();

    http_response_code(201); // Created
    echo json_encode(["message" => "User registered successfully"]);

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Something went wrong", "error" => $e->getMessage()]);
}

?>