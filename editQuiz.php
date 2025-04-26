<?php
include("./connection.php");

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing quiz ID"]);
    exit;
}

$quizId = $_GET['id'];

// Check if all required fields are provided
if (!isset($data['title'], $data['description'], $data['url_picture'], $data['genre'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing fields"]);
    exit;
}

$title = $data['title'];
$description = $data['description'];
$urlPicture = $data['url_picture'];
$genre = $data['genre'];

try {

    $query = $connection->prepare("
        UPDATE Quiz
        SET Title = :title, Description = :description, Url_picture = :url_picture, Genre = :genre
        WHERE Quiz_ID = :quiz_id
    ");

    $query->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
    $query->bindParam(':title', $title, PDO::PARAM_STR);
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->bindParam(':url_picture', $urlPicture, PDO::PARAM_STR);
    $query->bindParam(':genre', $genre, PDO::PARAM_STR);


    $query->execute();

    if ($query->rowCount() > 0) {
        http_response_code(200); // OK
        echo json_encode(["message" => "Quiz updated successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Quiz not found or no changes made"]);
    }

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
