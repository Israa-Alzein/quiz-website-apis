<?php
include("./connection.php");

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing question ID"]);
    exit;
}

$question_id = $_GET['id'];


if (!isset($data['question_statement'], $data['option_one'], $data['option_two'], $data['option_three'], $data['answer'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing fields"]);
    exit;
}

$question_statement = $data['question_statement'];
$option_one = $data['option_one'];
$option_two = $data['option_two'];
$option_three = $data['option_three'];
$answer = $data['answer'];

try {

    $query = $connection->prepare("
        UPDATE Question
        SET Question_Statement = :question_statement, Option_One  = :option_one, Option_Two  = :option_two, Option_Three  = :option_three, Answer = :answer
        WHERE Question_ID = :question_id
    ");

    $query->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $query->bindParam(':question_statement', $question_statement, PDO::PARAM_STR);
    $query->bindParam(':option_one', $option_one, PDO::PARAM_STR);
    $query->bindParam(':option_two', $option_two, PDO::PARAM_STR);
    $query->bindParam(':option_three', $option_three, PDO::PARAM_STR);
    $query->bindParam(':answer', $answer, PDO::PARAM_STR);


    $query->execute();

    if ($query->rowCount() > 0) {
        http_response_code(200); // OK
        echo json_encode(["message" => "Question updated successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Question not found or no changes made"]);
    }

} catch (Throwable $e) {
    http_response_code(500); // Server Error
    echo json_encode(["message" => "Server Error", "error" => $e->getMessage()]);
}
?>
