<?php
require 'kuizdatabase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST;
    $score = 0;

    foreach ($answers as $questionId => $userAnswer) {
        $questionId = filter_var($questionId, FILTER_SANITIZE_NUMBER_INT);

        $stmt = $conn->prepare("SELECT correct_option FROM questions WHERE id = ?");
        $stmt->execute([$questionId]);
        $correctOption = $stmt->fetchColumn();

        if ($userAnswer === $correctOption) {
            $score++;
        }
    }

    echo "<h1>Rezultati: $score / 10</h1>";
    echo '<a href="kuizindex.php">Provo perseri</a>';
} else {
    header('Location: kuizindex.php');
    exit();
}
?>
