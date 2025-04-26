<?php
include("./connection.php");

try {
    $query = $connection->prepare("SELECT * FROM Quiz");
    $query->execute();

    $quizzes = $query->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200); // OK
    echo json_encode([
        "message" => "Quizzes fetched successfully",
        "quizzes" => $quizzes
    ]);

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode([
        "message" => "Server Error",
        "error" => $e->getMessage()
    ]);
}
?>
