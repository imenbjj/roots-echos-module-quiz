<?php
session_start();

// Check if score is set in session
if (!isset($_SESSION['score'])) {
    header("Location: quiz.php"); // Redirect back to quiz if no score is available
    exit();
}

$score = $_SESSION['score'];
$totalQuestions = $_SESSION['totalQuestions']; // Retrieve total questions

// Optionally clear the score after displaying it
unset($_SESSION['score']);
unset($_SESSION['totalQuestions']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Score</title>
</head>
<body>
    <h1>Your Score</h1>
    <p>You scored <?php echo htmlspecialchars($score); ?> out of <?php echo htmlspecialchars($totalQuestions); ?>.</p>

    <?php if ($totalQuestions === 0): ?>
        <p>No questions were answered. Please make sure to select an answer for each question.</p>
    <?php endif; ?>
</body>
</html>