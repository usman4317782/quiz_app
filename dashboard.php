<?php
require_once 'bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include 'includes/header.php';
?>

<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p class="lead">You are now logged in to the Student Quiz Dashboard.</p>
                    <hr class="my-4">
                    <p>Ready to test your knowledge?</p>
                    <a class="btn btn-primary btn-lg" href="#" role="button">Start Quiz</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
