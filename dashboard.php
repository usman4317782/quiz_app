<?php
require_once 'bootstrap.php';
require_once 'includes/classes/QuizManager.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$quizManager = new QuizManager($pdo);
$attempts = $quizManager->getUserAttempts($_SESSION['user_id']);

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p class="lead">You are now logged in to the Student Quiz Dashboard.</p>
                    <hr class="my-4">
                    <p>Ready to test your knowledge?</p>
                    <a class="btn btn-primary btn-lg" href="take_quiz.php" role="button">Start Quiz</a>
                </div>
            </div>

            <?php if (!empty($attempts)): ?>
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">Previous Attempts</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attempts as $attempt): ?>
                                    <tr>
                                        <td><?php echo date('M j, Y g:i A', strtotime($attempt['attempt_timestamp'])); ?></td>
                                        <td><?php echo $attempt['score'] . ' / ' . $attempt['total_questions']; ?></td>
                                        <td>
                                            <?php
                                            $percent = ($attempt['score'] / $attempt['total_questions']) * 100;
                                            echo number_format($percent, 1) . '%';
                                            ?>
                                        </td>
                                        <td>
                                            <a href="result.php?attempt_id=<?php echo $attempt['id']; ?>" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>