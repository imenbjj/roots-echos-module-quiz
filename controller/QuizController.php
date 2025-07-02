<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../Model/Quiz.php');

class QuizController
{
    
    public function listQuizzes()
    {
        $sql = "SELECT * FROM quiz";//slect from quiz *
        $db = config::getConnexion();//conn a a la base de donnee
        try {
            $list = $db->query($sql);//query tekhou mel bd thot fl list
            $quizzes = $list->fetchAll(PDO::FETCH_ASSOC);//fetchall thothom fi tableau
            return $quizzes;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }


    function deleteQuiz($idquiz)
    {
        // Step 1: Delete related questions
        $sqlDeleteQuestions = "DELETE FROM questions WHERE idquiz = :idquiz";
        $db = config::getConnexion();
        $req = $db->prepare($sqlDeleteQuestions);
        $req->bindValue(':idquiz', $idquiz);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error deleting related questions: ' . $e->getMessage());
        }
    
        // Step 2: Delete the quiz itself
        $sqlDeleteQuiz = "DELETE FROM quiz WHERE idquiz = :idquiz";
        $req = $db->prepare($sqlDeleteQuiz);
        $req->bindValue(':idquiz', $idquiz);
    
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error deleting quiz: ' . $e->getMessage());
        }
    }
    

    
        // In QuizController.php
    public function createQuiz($quiz)
    {
        $sql = "INSERT INTO quiz (idquiz, titre, date) VALUES (NULL, :titre, NOW())";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':titre', $quiz->getTitre());
        try {
            $req->execute();
            // Debugging: check if the insert worked
            echo "Quiz inserted successfully!";
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    


    function updateQuiz($quiz, $idquiz)
    {
        try {
            $db = config::getConnexion();
    
            $query = $db->prepare(
                'UPDATE quiz SET 
                    titre = :titre,
                    date = :date  // Add date here
                WHERE idquiz = :idquiz'
            );
    
            $query->execute([
                'idquiz' => $idquiz,
                'titre' => $quiz->getTitre(),
                'date' => $quiz->getDate()  // Add date here
            ]);
    
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    

    
    function showQuiz($idquiz)
    {
        $sql = "SELECT * FROM quiz WHERE idquiz = :idquiz";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['idquiz' => $idquiz]);
    
            $quiz = $query->fetch();
            return $quiz;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    // In QuizController.php
    public function showQuizByTitle($title)
    {
        $sql = "SELECT * FROM quiz WHERE titre = :titre";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':titre', $title);
        try {
            $req->execute();
            return $req->fetch(PDO::FETCH_ASSOC); // returns quiz or false if not found
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

}
?>