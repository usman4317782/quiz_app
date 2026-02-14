<?php
require_once 'config.php';
require_once 'includes/classes/QuizManager.php';

try {
    $quizManager = new QuizManager($pdo);
    $quizzes = $quizManager->getQuizzes();

    echo "Quizzes Count: " . count($quizzes) . "\n";
    if (count($quizzes) > 0) {
        foreach ($quizzes as $i => $q) {
            echo ($i + 1) . ". " . $q['question_description'] . " (" . count($q['options']) . " options)\n";
        }
    } else {
        echo "NO QUIZZES FOUND IN DB\n";
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
