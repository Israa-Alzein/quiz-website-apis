<?php
include("./connection.php");


if (!isset($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing quiz ID"]);
    exit;
}

$quizId = $_GET['id'];

try {

    $query = $connection->prepare("DELETE FROM Quiz WHERE Quiz_ID = :quiz_id");
    $query->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);

    $query->execute();

    if ($query->rowCount() > 0) {
        http_response_code(200); // OK
        echo json_encode(["message" => "Quiz deleted successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Quiz not found"]);
    }

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
