<?php
include("./connection.php");

// I want the questions related to quiz_id so I need quiz_id in request
$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if ($quizId === 0) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing or invalid quiz ID"]);
    exit;
}

try {
    // Check if quiz exists
    $checkQuiz = $connection->prepare("SELECT * FROM Quiz WHERE Quiz_ID = :quiz_id");
    $checkQuiz->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
    $checkQuiz->execute();

    if ($checkQuiz->rowCount() === 0) {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Quiz not found"]);
        exit;
    }

    // Fetch all questions for the quiz of specific id
    $query = $connection->prepare("SELECT * FROM Question WHERE Quiz_ID = :quiz_id");
    $query->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
    $query->execute();

    $questions = $query->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200); // OK
    echo json_encode([
        "message" => "Questions fetched successfully",
        "questions" => $questions
    ]);

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
