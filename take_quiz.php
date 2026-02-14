<?php
require_once 'bootstrap.php';
require_once 'functions.php';
require_once 'includes/classes/QuizManager.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$quizManager = new QuizManager($pdo);
$quizzes = $quizManager->getQuizzes();

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">PHP Quiz</h3>
                </div>
                <div class="card-body">
                    <form action="submit_quiz.php" method="POST" id="quizForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

                        <?php foreach ($quizzes as $index => $quiz): ?>
                            <div class="mb-4">
                                <h5><?php echo ($index + 1) . ". " . htmlspecialchars($quiz['question_description']); ?></h5>
                                <?php foreach ($quiz['options'] as $optIndex => $option): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="answers[<?php echo $quiz['id']; ?>]"
                                            id="q<?php echo $quiz['id'] . '_' . $optIndex; ?>"
                                            value="<?php echo $optIndex; ?>" required>
                                        <label class="form-check-label" for="q<?php echo $quiz['id'] . '_' . $optIndex; ?>">
                                            <?php echo htmlspecialchars($option); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Submit Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>