<?php
require_once 'config.php';

try {
    // Create quiz_attempts table
    // Stores summary of each attempt
    $sql = "CREATE TABLE IF NOT EXISTS quiz_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        quiz_id VARCHAR(50) DEFAULT 'default_quiz',
        score INT NOT NULL,
        total_questions INT NOT NULL,
        attempt_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "Table 'quiz_attempts' created successfully.<br>";

    // Create attempt_details table
    // Stores each answer for an attempt
    $sql = "CREATE TABLE IF NOT EXISTS attempt_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        attempt_id INT NOT NULL,
        question_id VARCHAR(50) NOT NULL,
        user_answer_index INT NOT NULL,
        is_correct TINYINT(1) NOT NULL,
        FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "Table 'attempt_details' created successfully.<br>";

    // Create questions table
    $sql = "CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_description TEXT NOT NULL,
        correct_answer_index INT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "Table 'questions' created successfully.<br>";

    // Create question_options table
    $sql = "CREATE TABLE IF NOT EXISTS question_options (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_id INT NOT NULL,
        option_text TEXT NOT NULL,
        option_index INT NOT NULL,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "Table 'question_options' created successfully.<br>";
} catch (PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
