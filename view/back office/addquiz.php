<?php
include '../../controller/QuizController.php';
include '../../controller/QuestionController.php';

$quizController = new QuizController();
$questionController = new QuestionController();

$error = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["num_questions"])) {
    $numQuestions = (int)$_POST["num_questions"];

    // Validate quiz title and ensure at least one question is provided
    if (isset($_POST["titre"]) && !empty($_POST["titre"])) {
        try {
            // Check if the quiz title already exists
            $quizdeja = $quizController->showQuizByTitle($_POST["titre"]);
            if ($quizdeja) {
                throw new Exception("Le titre '{$_POST["titre"]}' existe déjà.");
            }

            // Create the quiz
            $quiz = new Quiz(null, $_POST["titre"], date('Y-m-d H:i:s')); // Add the current date and time
            $quizController->createQuiz($quiz);
            $quiz = $quizController->showQuizByTitle($_POST["titre"]);
            if (!$quiz) {
                throw new Exception("Failed to retrieve quiz after creation.");
            }

            // Loop through the submitted questions
            for ($i = 1; $i <= $numQuestions; $i++) {
                $questionField = "question$i";
                $rep1Field = "rep1_$i";
                $rep2Field = "rep2_$i";
                $rep3Field = "rep3_$i";
                $repcorrectField = "repcorrect_$i";

                if (
                    isset($_POST[$questionField], $_POST[$rep1Field], $_POST[$rep2Field], $_POST[$rep3Field], $_POST[$repcorrectField]) &&
                    !empty($_POST[$questionField]) &&
                    !empty($_POST[$rep1Field]) &&
                    !empty($_POST[$rep2Field]) &&
                    !empty($_POST[$rep3Field]) &&
                    !empty($_POST[$repcorrectField])
                ) {
                    $question = new Questions(
                        null,
                        $quiz['idquiz'],
                        $_POST[$questionField],
                        $_POST[$rep1Field],
                        $_POST[$rep2Field],
                        $_POST[$rep3Field],
                        $_POST[$repcorrectField]
                    );
                    $questionController->createquestions($question);
                } else {
                    throw new Exception("All fields for question $i are required.");
                }
            }

            header('Location: quizlist.php');
            exit();
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Quiz title is required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Quiz and Questions</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9f9;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Allow content taller than viewport */
        margin: 0;
        padding: 0;
        }

        .container {
            width: 50%;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px auto; /* Add space above and below */
        }


        h1 {
            font-size: 36px;
            font-weight: 600;
            color: #6E332C;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-size: 18px;
            font-weight: 500;
            color: #333;
        }

        input[type="text"], input[type="number"] {
            width: 80%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #6E332C;
            color: #fff;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }

        button:hover {
            background-color: #5a2a26;
        }

        p {
            color: red;
            font-size: 16px;
        }
    </style>
    <script>
        function generateQuestionFields() {
            const numQuestions = document.getElementById("num_questions").value;
            const questionsContainer = document.getElementById("questions-container");

            questionsContainer.innerHTML = ""; // Clear existing fields

            for (let i = 1; i <= numQuestions; i++) {
                const questionHTML = `
                    <h3>Question ${i}</h3>
                    <label>Question:</label>
                    <input type="text" name="question${i}" placeholder="Enter question ${i}" required><br>
                    
                    <label>Reponse 1:</label>
                    <input type="text" name="rep1_${i}" placeholder="Reponse 1" required><br>
                    
                    <label>Reponse 2:</label>
                    <input type="text" name="rep2_${i}" placeholder="Reponse 2" required><br>
                    
                    <label>Reponse 3:</label>
                    <input type="text" name="rep3_${i}" placeholder="Reponse 3" required><br>
                    
                    <label>Reponse correcte:</label>
                    <input type="number" name="repcorrect_${i}" min="1" max="3" placeholder="Correct answer (1, 2, or 3)" required><br><br>
                `;
                questionsContainer.insertAdjacentHTML("beforeend", questionHTML);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Add a New Quiz</h1>
        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

        <form method="POST" action="">
            <label for="titre">Quiz Title:</label>
            <input type="text" id="titre" name="titre" placeholder="Enter quiz title" required><br>

            <label for="num_questions">Number of Questions (1-8):</label>
            <input type="number" id="num_questions" name="num_questions" min="1" max="8" onchange="generateQuestionFields()" required><br>

            <div id="questions-container"></div>

            <button type="submit">Add Quiz</button>
        </form>
    </div>
</body>
</html>
