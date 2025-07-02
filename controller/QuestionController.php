<?php

include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../Model/question.php');

class QuestionController{

    public function listquestions()
    {
        $sql = "SELECT * FROM questions";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            $questions = $list->fetchAll(PDO::FETCH_ASSOC);
            return $questions;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deletequestions($id)
    {
        $sql = "DELETE FROM questions WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

    function createquestions($questions)
{
    $sql = "INSERT INTO questions (idquiz, question, rep1, rep2, rep3, repcorrect) 
            VALUES (:idquiz, :question, :rep1, :rep2, :rep3, :repcorrect)";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'idquiz' => $questions->getIdQuiz(),
            'question' => $questions->getQuestion(),
            'rep1' => $questions->getRep1(),
            'rep2' => $questions->getRep2(), 
            'rep3' => $questions->getRep3(),
            'repcorrect' => $questions->getRepcorrect()
        ]);
    } catch (Exception $e) {
        throw new Exception('Error in createquestions(): ' . $e->getMessage());
    }
}

    


    function updatequestions($questions, $idquiz)
    {
        try {
            $db = config::getConnexion();

            $query = $db->prepare(
                'UPDATE questions SET 
                    question = :question,
                    rep1 = :rep1,
                    rep2 = :rep2,
                    rep3 = :rep3,
                    repcorrect=:repcorrect
                WHERE idquiz = :idquiz'
            );

            $query->execute([
                'idquiz' => $idquiz,
                'questions' => $questions->getQuestion(),
                'rep1'=>$questions->getRep1(),
                'rep2'=>$questions->getRep2(),
                'rep3'=>$questions->getRep3(),
                'repcorrect'=>$questions->getRepcorrect()
            ]);

            echo $query->rowCount() . " records UPDATED successfully <br>";//kadeh men ligne mise a jour fl bd
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); 
        }
    }

    function showquestion($id)
    {
        $sql = "SELECT * FROM questions WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);

            $questions = $query->fetch();//bl ligne bl ligne
            return $questions;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listQuestionsByQuiz($idquiz)
    {
        $sql = "SELECT * FROM questions WHERE idquiz = :idquiz";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['idquiz' => $idquiz]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
}
function createQuestionForQuiz($question)
{
    $sql = "INSERT INTO questions (idquiz, question, rep1, rep2, rep3, repcorrect) 
            VALUES (:idquiz, :question, :rep1, :rep2, :rep3, :repcorrect)";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'idquiz' => $question->getIdQuiz(),
            'question' => $question->getQuestion(),
            'rep1' => $question->getRep1(),
            'rep2' => $question->getRep2(),
            'rep3' => $question->getRep3(),
            'repcorrect' => $question->getRepcorrect(),
        ]);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
public function countQuestionsByQuiz($idquiz) {
    $query = "SELECT COUNT(*) AS total FROM questions WHERE idquiz = :idquiz";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':idquiz', $idquiz, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}
public function getAnswersByQuestionId($questionId) {
    // Assuming you have a database connection method in place, e.g. $db
    global $db;

    $stmt = $db->prepare("SELECT * FROM answers WHERE question_id = :questionId");
    $stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}



?>