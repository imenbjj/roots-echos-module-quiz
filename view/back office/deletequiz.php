<?php
include '../../controller/QuizController.php';
$quizController = new QuizController();
$quizController->deleteQuiz($_GET["id"]);
header('Location:quizList.php');
?>
