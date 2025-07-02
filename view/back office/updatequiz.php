<?php
include '../../controller/QuizController.php';
include '../../controller/QuestionController.php';

// Check if the quiz ID is set in the GET request
if (isset($_GET['id'])) {
    $quizId = intval($_GET['id']);
    $quizController = new QuizController();
    $questionController = new QuestionController();

    // Retrieve quiz and related questions
    $quiz = $quizController->showQuiz($quizId);
    $questions = $questionController->listQuestionsByQuiz($quizId);

    if (!$quiz) {
        die('Quiz not found');
    }
} else {
    die('Invalid quiz ID');
}

// Handle the form submission for updating the quiz and its questions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quiz details
    $title = $_POST['titre'];
    $date = $_POST['date'];

    $quiz['titre'] = $title;
    $quiz['date'] = $date;

    try {
        $quizController->updateQuiz((object) $quiz, $quizId);

        // Update each question
        foreach ($_POST['questions'] as $questionId => $questionData) {
            $question = new Questions(
                $questionId, // Question ID
                $quizId, // Quiz ID
                $questionData['question'], // Question text
                $questionData['rep1'], // Answer 1
                $questionData['rep2'], // Answer 2
                $questionData['rep3'], // Answer 3
                $questionData['repcorrect'] // Correct answer
            );

            $questionController->updatequestions($question, $quizId);
        }

        // Redirect to quiz list after successful update
        header('Location: quizList.php');
        exit;
    } catch (Exception $e) {
        echo 'Error updating quiz or questions: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Quiz</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            margin: 0;
            min-height: 100vh; /* Ensure the body takes at least full viewport height */
            padding: 0 20px; /* Add some padding for smaller screens */
            box-sizing: border-box;
            overflow: auto; /* Allow scrolling if content exceeds viewport height */
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Ensure content inside the container can scroll if needed */
        }


        h1 {
            font-size: 24px;
            color: #6E332C;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        input, button {
            font-size: 16px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #6E332C;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #5a2a26;
        }
        .question-block {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .answers {
            margin-left: 20px; /* Indent for answers */
        }
        .answers input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>mise a jour du quiz</h1>
    <form method="POST">
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($quiz['titre']); ?>" required>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($quiz['date']); ?>" required>

        <h2>Questions</h2>
        <?php foreach ($questions as $question): ?>
            <div class="question-block">
                <label>Question</label>
                <input type="text" name="questions[<?= $question['id']; ?>][question]" 
                       value="<?= htmlspecialchars($question['question']); ?>" required>

                <div class="answers">
                    <label>reponse 1</label>
                    <input type="text" name="questions[<?= $question['id']; ?>][rep1]" 
                           value="<?= htmlspecialchars($question['rep1']); ?>" required>

                    <label>reponse 2</label>
                    <input type="text" name="questions[<?= $question['id']; ?>][rep2]" 
                           value="<?= htmlspecialchars($question['rep2']); ?>" required>

                    <label>reponse 3</label>
                    <input type="text" name="questions[<?= $question['id']; ?>][rep3]" 
                           value="<?= htmlspecialchars($question['rep3']); ?>" required>

                    <label>reponse correcte</label>
                    <input type="text" name="questions[<?= $question['id']; ?>][repcorrect]" 
                           value="<?= htmlspecialchars($question['repcorrect']); ?>" required min="1" max="3">
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit">Mise a jour</button>
    </form>
</div>
</body>
</html>
