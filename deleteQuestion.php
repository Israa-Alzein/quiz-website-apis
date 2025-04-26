<?php

include("./connection.php");



if (!isset($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing question_id"]);
    exit;
}

$questionId = $_GET['id'];

try {

    $query = $connection->prepare("DELETE FROM Question WHERE Question_ID = :question_id");
    $query->bindParam(':question_id', $questionId, PDO::PARAM_INT);
    $query->execute();


    if ($query->rowCount() > 0) {
        http_response_code(200); // OK
        echo json_encode(["message" => "question deleted successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "question not found"]);
    }



} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
