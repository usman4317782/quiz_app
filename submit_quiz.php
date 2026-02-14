<?php
require_once 'bootstrap.php';
require_once 'functions.php';
require_once 'includes/classes/QuizManager.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF Check
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $logger->log('CSRF Failure', $_SESSION['user_id'], session_id(), 403, "Quiz Submission CSRF mismatch");
        die("Security Check Failed. Please try again.");
    }

    $quizManager = new QuizManager($pdo);
    $answers = $_POST['answers'] ?? [];

    try {
        $attemptId = $quizManager->saveAttempt($_SESSION['user_id'], $answers);
        $logger->log('Quiz Submitted', $_SESSION['user_id'], session_id(), 200, "Attempt ID: $attemptId");

        // Redirect to result page
        header("Location: result.php?attempt_id=" . $attemptId);
        exit;
    } catch (Exception $e) {
        $logger->log('Quiz Error', $_SESSION['user_id'], session_id(), 500, $e->getMessage());
        $_SESSION['error'] = "Error submitting quiz: " . $e->getMessage();
        header("Location: take_quiz.php");
        exit;
    }
} else {
    header("Location: take_quiz.php");
    exit;
}
