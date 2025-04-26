<?php
include("./connection.php");


$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['quiz_id'], $data['question_statement'], $data['option_one'], $data['option_two'], $data['option_three'], $data['answer'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing fields"]);
    exit;
}

$quizId = $data['quiz_id'];  //to specify to which quiz the question belongs (quiz_id is foreighn key)
$questionStatement = $data['question_statement'];
$optionOne = $data['option_one'];
$optionTwo = $data['option_two'];
$optionThree = $data['option_three'];
$answer = $data['answer'];

try {

    // First, check if the quiz exists by its id
    $checkQuiz = $connection->prepare("SELECT * FROM Quiz WHERE Quiz_ID = :quiz_id");
    $checkQuiz->bindParam(':quiz_id', $quizId);
    $checkQuiz->execute();


    if ($checkQuiz->rowCount() === 0) {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Quiz with ID $quizId not found."]);
        exit;
    }


    $query = $connection->prepare("INSERT INTO Question (Quiz_ID, Question_Statement, Option_One, Option_Two, Option_Three, Answer) 
                                VALUES (:quiz_id, :question_statement, :option_one, :option_two, :option_three, :answer)");

    $query->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
    $query->bindParam(':question_statement', $questionStatement, PDO::PARAM_STR);
    $query->bindParam(':option_one', $optionOne, PDO::PARAM_STR);
    $query->bindParam(':option_two', $optionTwo, PDO::PARAM_STR);
    $query->bindParam(':option_three', $optionThree, PDO::PARAM_STR);
    $query->bindParam(':answer', $answer, PDO::PARAM_STR);

    $query->execute();

    http_response_code(201); // Created
    echo json_encode(["message" => "Question created successfully"]);

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
