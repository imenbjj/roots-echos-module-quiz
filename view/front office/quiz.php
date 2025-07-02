<?php
include '../../controller/QuizController.php';
include '../../controller/QuestionController.php';

session_start();
$quizController = new QuizController();
$questionController = new QuestionController();

$quizData = $quizController->listQuizzes();

$currentQuizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$nextQuizId = ($currentQuizId + 1) % count($quizData);
$currentQuiz = isset($quizData[$currentQuizId]) ? $quizData[$currentQuizId] : null;
$questions = $questionController->listQuestionsByQuiz($currentQuiz['idquiz']);

$score = 0;
$totalQuestions = count($questions); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($questions as $question) {
        if (isset($_POST['reponse'][$question['id']])) {
            $userAnswer = $_POST['reponse'][$question['id']];
            if ($userAnswer == $question['repcorrect']) {
                $score++; 
            }
        }
    }
    $timeTaken = isset($_POST['time_taken']) ? htmlspecialchars($_POST['time_taken']) : 'N/A';

    $_SESSION['score'] = $score;
    $_SESSION['totalQuestions'] = $totalQuestions;
    $_SESSION['timeTaken'] = $timeTaken;

    header("Location: ?quiz_id=$currentQuizId&show_score=1");
    exit();
}

$displayScore = isset($_SESSION['score']) ? $_SESSION['score'] : null;
$totalQuestionsStored = isset($_SESSION['totalQuestions']) ? $_SESSION['totalQuestions'] : null;
$timeTakenStored = isset($_SESSION['timeTaken']) ? $_SESSION['timeTaken'] : null;
$showScore = isset($_GET['show_score']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roots & Echoes Quiz</title>
    <style>
               @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background-color: #fff; 
    color: #333;
    margin: 0;
    padding: 20px;
}

header {
    text-align: center;
    margin-bottom: 20px;
}

header h1 {
    font-size: 48px;
    font-weight: 700;
    color: #6E332C;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

header h2 {
    font-size: 22px;
    font-weight: 500;
    color: #444;
    margin-bottom: 30px;
}

.container {
    width: 90%;
    max-width: 750px;
    margin: 0 auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

h2.text-center {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

form {
    margin-top: 20px;
    text-align: left;
}

label {
    font-size: 18px;
    font-weight: 500;
    color: #555;
    display: block;
    margin-bottom: 10px;
    cursor: pointer;
    transition: color 0.3s ease;
}

label:hover {
    color: #6E332C;
}

input[type="radio"] {
    margin-right: 10px;
    accent-color: #6E332C;
}

button {
    background-color: #6E332C;
    color: #fff;
    padding: 15px 30px;
    font-size: 18px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    text-transform: uppercase;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.2s ease;
    display: block;
    margin: 20px auto;
}

button:hover {
    background-color: #5a2a26;
    transform: translateY(-2px);
}

button:active {
    transform: translateY(0);
}

.question-box {
    margin-bottom: 25px;
    padding: 20px;
    background: #fafafa;
    border: 1px solid #ddd;
    border-radius: 12px;
    text-align: left;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.question-box h4 {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    text-transform: capitalize;
}

@media (max-width: 768px) {
    header h1 {
        font-size: 36px;
    }

    header h2 {
        font-size: 18px;
    }

    button {
        font-size: 16px;
    }

    .question-box h4 {
        font-size: 18px;
    }
    .hidden {
        display: none;
    }
}
</style>
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1>ROOTS & ECHOS</h1>
        <h3>Évaluez votre culture</h3>
    </div>
</header>

<section class="page-section" id="quiz">
    <div id="timer" style="font-size: 20px; text-align: center; margin-bottom: 20px; color: #6E332C;">
        Temps écoulé : <span id="time">00:00</span>
    </div>

    <div class="container">
        <h2 class="text-center">Quiz: <?php echo htmlspecialchars($currentQuiz['titre'] ?? 'No Quiz'); ?></h2>

        <?php if (!$showScore): ?>
            <form method="POST" action="">
                <input type="hidden" id="time_taken" name="time_taken" value="0">
                <?php if ($questions): ?>
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-box">
                            <h4>Q<?php echo $index + 1; ?>: <?php echo htmlspecialchars($question['question']); ?></h4>
                            <div>
                                <label>
                                    <input type="radio" name="reponse[<?php echo $question['id']; ?>]" value="1" />
                                    <?php echo htmlspecialchars($question['rep1']); ?>
                                </label><br>
                                <label>
                                    <input type="radio" name="reponse[<?php echo $question['id']; ?>]" value="2" />
                                    <?php echo htmlspecialchars($question['rep2']); ?>
                                </label><br>
                                <label>
                                    <input type="radio" name="reponse[<?php echo $question['id']; ?>]" value="3" />
                                    <?php echo htmlspecialchars($question['rep3']); ?>
                                </label><br>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit">Submit Quiz</button>
                <?php else: ?>
                    <p>No questions available for this quiz.</p>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <div class="score-display">
            <script>
                    document.getElementById('timer').style.display = 'none'; // Hide timer when showing score
                </script>

                <h3>C'est terminé!</h3>
                <p>Votre score: <?php echo htmlspecialchars($displayScore); ?> sur <?php echo htmlspecialchars($totalQuestionsStored); ?>.</p>
                <p>Temps pris: <?php echo htmlspecialchars($timeTakenStored); ?> secondes.</p>
                <a href="?quiz_id=<?php echo $nextQuizId; ?>" style="display: block; margin-top: 20px; text-align: center; background-color: #6E332C; color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none;">Next Quiz</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    let startTime = Date.now();

    function updateTimer() {
        let elapsedTime = Math.floor((Date.now() - startTime) / 1000);
        let minutes = Math.floor(elapsedTime / 60);
        let seconds = elapsedTime % 60;
        document.getElementById('time').textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        document.getElementById('time_taken').value = elapsedTime;
    }

    setInterval(updateTimer, 1000);
</script>
</body>
</html>
