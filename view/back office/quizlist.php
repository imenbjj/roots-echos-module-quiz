<?php
include '../../controller/QuizController.php';
include '../../controller/QuestionController.php';

$quizController = new QuizController();
$questionController = new QuestionController();

$quizzes = $quizController->listQuizzes();
$questions = $questionController->listquestions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quiz List - Dashboard</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 80%; 
            max-width: 1000px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            overflow-y: auto;
            max-height: 90vh;
        }

        h1 {
            font-size: 36px;
            font-weight: 600;
            color: #6E332C;
            margin-bottom: 20px;
        }

        table {
            width: 100%; 
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .question {
            margin-bottom: 10px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;  
        }

        .btn {
        padding: 10px 20px;
        margin: 0;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        background-color: #6E332C;
        color: #000; /* Ajouté pour rendre le texte noir */
        text-align: center;
        width: 48%; 
    }


        .btn:hover {
            background-color: #5a2a26; 
        }

    </style>
</head>
<body>

<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Quiz List</h1>

    <table>
    <tr>
        <th>titre du quiz</th>
        <th>Questions et reponses</th>
        <th>Actions</th>
        <th>Date de sortie</th>
    </tr>

    <?php foreach ($quizzes as $quiz): ?>
        <tr>
            <td><?= htmlspecialchars($quiz['titre']); ?></td>
            <td>
                <?php 
                foreach ($questions as $question): 
                    if ($question['idquiz'] == $quiz['idquiz']): ?>
                        <div class="question">
                            <strong>Q:</strong> <?= htmlspecialchars($question['question']); ?><br>
                            <strong>A1:</strong> <?= htmlspecialchars($question['rep1']); ?><br>
                            <strong>A2:</strong> <?= htmlspecialchars($question['rep2']); ?><br>
                            <strong>A3:</strong> <?= htmlspecialchars($question['rep3']); ?><br>
                            <strong>Correct Answer:</strong> <?= htmlspecialchars($question['repcorrect']); ?>
                        </div>
                        <hr>
                    <?php endif; 
                endforeach; ?>
            </td>
            <td>
                <div class="btn-container">
                    <a href="updateQuiz.php?id=<?= $quiz['idquiz']; ?>" class="btn">update</a>
                    <a href="deleteQuiz.php?id=<?= $quiz['idquiz']; ?>" class="btn">Delete</a>
                </div>
            </td>
            <td><?= htmlspecialchars($quiz['date']); ?></td> 
        </tr>
    <?php endforeach; ?>
</table>

</div>

</body>
</html>
