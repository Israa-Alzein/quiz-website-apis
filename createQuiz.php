<?php
include("./connection.php");

$data = json_decode(file_get_contents('php://input'), true);


if (!isset($data['title'], $data['description'], $data['url_picture'], $data['genre'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing fields"]);
    exit;
}

$title = $data['title'];
$description = $data['description'];
$url_picture = $data['url_picture'];
$genre = $data['genre'];

try {

    $query = $connection->prepare("
        INSERT INTO Quiz (Title, Description, Url_picture, Genre)
        VALUES (:title, :description, :url_picture, :genre)
    ");


    $query->bindParam(':title', $title, PDO::PARAM_STR_CHAR);
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->bindParam(':url_picture', $url_picture, PDO::PARAM_STR);
    $query->bindParam(':genre', $genre, PDO::PARAM_STR_CHAR);

    $query->execute();

    http_response_code(201); // Created
    echo json_encode(["message" => "Quiz created successfully"]);

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
