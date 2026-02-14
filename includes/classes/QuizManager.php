<?php

class QuizManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getQuizzes()
    {
        $stmt = $this->pdo->query("SELECT * FROM questions ORDER BY id ASC");
        $questions = $stmt->fetchAll();

        foreach ($questions as &$question) {
            $optStmt = $this->pdo->prepare("SELECT option_text FROM question_options WHERE question_id = :q_id ORDER BY option_index ASC");
            $optStmt->execute([':q_id' => $question['id']]);
            $question['options'] = $optStmt->fetchAll(PDO::FETCH_COLUMN);
        }

        return $questions;
    }

    public function getQuizMap()
    {
        $quizzes = $this->getQuizzes();
        $map = [];
        foreach ($quizzes as $quiz) {
            $map[$quiz['id']] = $quiz;
        }
        return $map;
    }

    public function saveAttempt($userId, $answers)
    {
        $quizMap = $this->getQuizMap();
        $score = 0;
        $totalQuestions = count($quizMap);
        $details = [];

        // Validate all questions are answered and calculate score
        foreach ($quizMap as $id => $quiz) {
            if (!isset($answers[$id])) {
                throw new Exception("Missing answer for question: " . $quiz['question_description']);
            }

            $userAnswerIndex = (int)$answers[$id];
            // Validate option range
            if ($userAnswerIndex < 0 || $userAnswerIndex >= count($quiz['options'])) {
                throw new Exception("Invalid answer index for question: " . $id);
            }

            $isCorrect = ($userAnswerIndex === $quiz['correct_answer_index']);
            if ($isCorrect) {
                $score++;
            }

            $details[] = [
                'question_id' => $id,
                'user_answer_index' => $userAnswerIndex,
                'is_correct' => $isCorrect
            ];
        }

        try {
            $this->pdo->beginTransaction();

            // Insert Attempt Summary
            $stmt = $this->pdo->prepare("INSERT INTO quiz_attempts (user_id, score, total_questions) VALUES (:user_id, :score, :total_questions)");
            $stmt->execute([
                ':user_id' => $userId,
                ':score' => $score,
                ':total_questions' => $totalQuestions
            ]);
            $attemptId = $this->pdo->lastInsertId();

            // Insert Details
            $stmtDetail = $this->pdo->prepare("INSERT INTO attempt_details (attempt_id, question_id, user_answer_index, is_correct) VALUES (:attempt_id, :question_id, :user_answer_index, :is_correct)");

            foreach ($details as $detail) {
                $stmtDetail->execute([
                    ':attempt_id' => $attemptId,
                    ':question_id' => $detail['question_id'],
                    ':user_answer_index' => $detail['user_answer_index'],
                    ':is_correct' => $detail['is_correct'] ? 1 : 0
                ]);
            }

            $this->pdo->commit();
            return $attemptId;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function getAttempt($attemptId, $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM quiz_attempts WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $attemptId, ':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function getUserAttempts($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM quiz_attempts WHERE user_id = :user_id ORDER BY attempt_timestamp DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
