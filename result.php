<?php
require_once 'bootstrap.php';
require_once 'includes/classes/QuizManager.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['attempt_id'])) {
    header("Location: dashboard.php");
    exit;
}

$attemptId = $_GET['attempt_id'];
$quizManager = new QuizManager($pdo);
$attempt = $quizManager->getAttempt($attemptId, $_SESSION['user_id']);

if (!$attempt) {
    die("Attempt not found or access denied.");
}

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow text-center">
                <div class="card-header bg-success text-white">
                    <h3>Quiz Results</h3>
                </div>
                <div class="card-body">
                    <h1 class="display-1 fw-bold"><?php echo $attempt['score']; ?> / <?php echo $attempt['total_questions']; ?></h1>
                    <p class="lead">Your Score</p>
                    <hr>
                    <?php
                    $percentage = ($attempt['score'] / $attempt['total_questions']) * 100;
                    if ($percentage >= 80) {
                        echo '<div class="alert alert-success">Excellent! You have a great understanding.</div>';
                    } elseif ($percentage >= 50) {
                        echo '<div class="alert alert-warning">Good job! But there is room for improvement.</div>';
                    } else {
                        echo '<div class="alert alert-danger">Keep practicing! You can do better.</div>';
                    }
                    ?>
                    <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
                    <a href="take_quiz.php" class="btn btn-outline-secondary mt-3">Retake Quiz</a>
                </div>
                <div class="card-footer text-muted">
                    Attempted on: <?php echo date('F j, Y, g:i a', strtotime($attempt['attempt_timestamp'])); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>