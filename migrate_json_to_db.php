<?php
require_once 'config.php';

try {
    $json = file_get_contents('quizzes.json');
    $quizzes = json_decode($json, true);

    if (!$quizzes) {
        die("Error: Could not read or decode quizzes.json\n");
    }

    $pdo->beginTransaction();

    // Clear existing data to avoid duplicates during migration
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("DELETE FROM question_options");
    $pdo->exec("DELETE FROM questions");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $stmtQuestion = $pdo->prepare("INSERT INTO questions (question_description, correct_answer_index) VALUES (:desc, :correct)");
    $stmtOption = $pdo->prepare("INSERT INTO question_options (question_id, option_text, option_index) VALUES (:q_id, :text, :idx)");

    foreach ($quizzes as $quiz) {
        $stmtQuestion->execute([
            ':desc' => $quiz['question_description'],
            ':correct' => $quiz['correct_answer_index']
        ]);
        $questionId = $pdo->lastInsertId();

        foreach ($quiz['options'] as $index => $optionText) {
            $stmtOption->execute([
                ':q_id' => $questionId,
                ':text' => $optionText,
                ':idx' => $index
            ]);
        }
    }

    $pdo->commit();
    echo "Migration completed successfully. " . count($quizzes) . " questions migrated.\n";
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Migration failed: " . $e->getMessage() . "\n");
}
