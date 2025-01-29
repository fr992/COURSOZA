<?php 
require 'kuizdatabase.php';

$subject_ndarja = [
    '1' => ['name' => 'Databaza', 'start' => 0, 'end' => 25],
    '2' => ['name' => 'Shkenca Kompjuterike 2', 'start' => 26, 'end' => 50],
    '3' => ['name' => 'Dizajni dhe Zhvillimi i Web', 'start' => 51, 'end' => 75],
];

$subject_id = $_GET['subject_id'] ?? null;

if(!$subject_id || !isset($subject_ndarja[$subject_id])){
    die("Lenda nuk ekziston.");
}
// marrja e pytjeve per lenden perkatese
$marrja = $subject_ndarja[$subject_id];
$query = "SELECT id, question, option_a, option_b, option_c, option_d, correct_option 
            FROM questions
            WHERE id BETWEEN :start AND :end
            ORDER BY RAND() LIMIT 10"; // limiti 10 pytje edhe bohen shuffle sdalin tnjejtat gjith prej atyne 25

$stmt = $conn->prepare($query);
$stmt->execute(['start' => $marrja['start'], 'end' => $marrja['end']]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$questions){
    die("Ska pytje per kete lende.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuiz: <?php htmlspecialchars($subject_ndarja[$subject_id]['name']); ?></title>
    <link rel="stylesheet" href="kuizstyles.css">
</head>
<body>
    <header>
        <div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
            </div>
            <ul class="nav-link">
                <li style="padding-right: 30rem"><a href="index.html">Ballina</a></li>
            </ul>
        </div>
    </header>

    <div class="quiz-container">
        <h1>Quiz: <?php echo htmlspecialchars($subject_ndarja[$subject_id]['name']) ?></h1>
        <div id="quiz">
        </div>
        <button id="next-button" disabled>Next</button>
        <div id="result"></div>
    </div>

    <script>
        const questions = <?= json_encode($questions) ?>;
        const quizContainer = document.getElementById('quiz');
        const nextButton = document.getElementById('next-button');
        const resultContainer = document.getElementById('result');

        let currentQuestionsIndex = 0;
        let score = 0;

        function loadQuestion(index){
            const questionText = questions[index];
            quizContainer.innerHTML = `
                <div class="question">
                    <p>${index + 1}. ${questionText.question}</p>
                    <label><input type="radio" name="answer" value="a"> ${questionText.option_a}</label>
                    <label><input type="radio" name="answer" value="b"> ${questionText.option_b}</label>
                    <label><input type="radio" name="answer" value="c"> ${questionText.option_c}</label>
                    <label><input type="radio" name="answer" value="d"> ${questionText.option_d}</label>
                </div>    
            `;
            nextButton.disabled=true;

            document.querySelectorAll('input[name="answer"]').forEach((input) => {
                input.addEventListener('change', () => {
                    nextButton.disabled=false;
                });
            });
        }

        function showResult(){
            quizContainer.innerHTML = '';
            nextButton.style.display = 'none';
            resultContainer.innerHTML = `Your Score: ${score} / ${questions.length}`;
        }

        nextButton.addEventListener('click', () => {
            const selectedAnswer = document.querySelector('input[name="answer"]:checked').value;
            const correctAnswer = questions[currentQuestionsIndex].correct_option.trim().toLowerCase();

            if(selectedAnswer === correctAnswer){
                score++;
            }

            currentQuestionsIndex++;
            if(currentQuestionsIndex < questions.length){
                loadQuestion(currentQuestionsIndex);
            } else {
                showResult();
            }
        });

        loadQuestion(currentQuestionsIndex);
    </script>
</body>
</html>
