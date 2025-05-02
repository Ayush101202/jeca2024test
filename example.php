<?php
$answerKey = [
    // Category 1 - Single correct answers (Q1-80)
    1 => 'A',
    2 => 'A',
    3 => 'B',
    4 => 'A',
    5 => 'B',
    6 => 'C',
    7 => 'C',
    8 => 'A',
    9 => 'D',
    10 => 'D',
    11 => 'D',
    12 => 'A',
    13 => 'C',
    14 => 'C',
    15 => 'C',
    16 => 'C',
    17 => 'A',
    18 => 'A',
    19 => 'A',
    20 => 'C',
    21 => 'C',
    22 => 'D',
    23 => 'A',
    24 => 'D',
    25 => 'B',
    26 => 'D',
    27 => 'B',
    28 => 'B',
    29 => 'A',
    30 => 'D',
    31 => 'C',
    32 => 'D',
    33 => 'A',
    34 => 'B',
    35 => 'C',
    36 => 'A',
    37 => 'C',
    38 => 'A',
    39 => 'C',
    40 => 'B',
    41 => 'C',
    42 => 'B',
    43 => 'C',
    44 => 'D',
    45 => 'B',
    46 => 'B',
    47 => 'B',
    48 => 'A',
    49 => 'C',
    50 => 'B',
    51 => 'D',
    52 => 'D',
    53 => 'A',
    54 => 'C',
    55 => 'B',
    56 => 'B',
    57 => 'B',
    58 => 'C',
    59 => 'D',
    60 => 'A',
    61 => 'C',
    62 => 'A',
    63 => 'A',
    64 => 'D',
    65 => 'B',
    66 => 'A',
    67 => 'D',
    68 => 'B',
    69 => 'C',
    70 => 'D',
    71 => 'D',
    72 => 'C',
    73 => 'A',
    74 => 'A',
    75 => 'B',
    76 => 'A',
    77 => 'C',
    78 => 'B',
    79 => 'A',
    80 => 'D',

    // Category 2 - Multiple correct answers possible (Q81-100)
    81 => ['B', 'D'],
    82 => ['D'],
    83 => ['C'],
    84 => ['A', 'D'],
    85 => ['B'],
    86 => ['B', 'D'],
    87 => ['B', 'C', 'D'],
    88 => ['C'],
    89 => ['B'],
    90 => ['C'],
    91 => ['A'],
    92 => ['D'],
    93 => ['C'],
    94 => ['B'],
    95 => ['C'],
    96 => ['A', 'B'],
    97 => ['A', 'B', 'C', 'D'],
    98 => ['C'],
    99 => ['C'],
    100 => ['B']
];

// Get nominee's responses from POST data
$nomineeResponses = [];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process Category 1 questions (Q1-80) - single answers
    for ($i = 1; $i <= 80; $i++) {
        if (isset($_POST["q$i"]) && !empty($_POST["q$i"])) {
            $nomineeResponses[$i] = $_POST["q$i"];
        }
    }
    
    // Process Category 2 questions (Q81-100) - multiple answers
    for ($i = 81; $i <= 100; $i++) {
        if (isset($_POST["q$i"]) && !empty($_POST["q$i"])) {
            // If multiple options are selected, it will be an array
            if (is_array($_POST["q$i"])) {
                $nomineeResponses[$i] = $_POST["q$i"];
            } else {
                // If only one option is selected, make it an array
                $nomineeResponses[$i] = [$_POST["q$i"]];
            }
        }
    }
} else {
    // If no POST data, use example data for testing
    $nomineeResponses = [
        // Category 1 - Single selected answers (Example data)
        1 => 'A',
        2 => 'B',
        // ... more example answers
        80 => 'B',
        
        // Category 2 - Multiple selected answers (Example data)
        81 => ['A'],
        82 => ['B', 'D'],
        // ... more example answers
        100 => ['A', 'B', 'C']
    ];
    
    echo "<p><strong>Note:</strong> Using example data. Submit the form to calculate actual scores.</p>";
}

// Calculate the total score
function calculateTotalScore($answerKey, $nomineeResponses) {
    $totalScore = 0;
    
    // Category 1 (Q1-80): 1 mark each, -0.25 for incorrect
    for ($i = 1; $i <= 80; $i++) {
        if (isset($nomineeResponses[$i])) {
            if ($nomineeResponses[$i] === $answerKey[$i]) {
                $totalScore += 1;  // Correct answer
            } else {
                $totalScore -= 0.25;  // Incorrect answer (negative marking)
            }
        }
        // Unattempted questions get 0 marks (no addition/subtraction)
    }
    
    // Category 2 (Q81-100): 2 marks each with formula for partial credit
    for ($i = 81; $i <= 100; $i++) {
        if (isset($nomineeResponses[$i])) {
            // Check if all selected options are correct (no incorrect options selected)
            $allCorrect = true;
            foreach ($nomineeResponses[$i] as $response) {
                if (!in_array($response, $answerKey[$i])) {
                    $allCorrect = false;
                    break;
                }
            }
            
            if ($allCorrect) {
                // Calculate partial credit: 2 × (correct marked ÷ total correct)
                $correctMarked = count($nomineeResponses[$i]);
                $totalCorrect = count($answerKey[$i]);
                $score = 2 * ($correctMarked / $totalCorrect);
                $totalScore += $score;
            }
            // If any incorrect option is marked, score is 0 (no addition)
        }
        // Unattempted questions get 0 marks (no addition)
    }
    
    return $totalScore;
}

// Optional: Calculate category-wise scores
function calculateCategoryScores($answerKey, $nomineeResponses) {
    $category1Score = 0;
    $category2Score = 0;
    
    // Category 1 (Q1-80)
    for ($i = 1; $i <= 80; $i++) {
        if (isset($nomineeResponses[$i])) {
            if ($nomineeResponses[$i] === $answerKey[$i]) {
                $category1Score += 1;
            } else {
                $category1Score -= 0.25;
            }
        }
    }
    
    // Category 2 (Q81-100)
    for ($i = 81; $i <= 100; $i++) {
        if (isset($nomineeResponses[$i])) {
            $allCorrect = true;
            foreach ($nomineeResponses[$i] as $response) {
                if (!in_array($response, $answerKey[$i])) {
                    $allCorrect = false;
                    break;
                }
            }
            
            if ($allCorrect) {
                $correctMarked = count($nomineeResponses[$i]);
                $totalCorrect = count($answerKey[$i]);
                $score = 2 * ($correctMarked / $totalCorrect);
                $category2Score += $score;
            }
        }
    }
    
    return [
        'category1' => $category1Score,
        'category2' => $category2Score
    ];
}

// HTML Form for display
function displayForm() {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>JECA 2024 Score Calculator</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                line-height: 1.6;
            }
            .title {
                text-align: center;
                margin-bottom: 20px;
            }
            .result-section {
                background-color: #f5f5f5;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
            }
            .score-card {
                border: 1px solid #ddd;
                padding: 20px;
                border-radius: 5px;
                margin-top: 20px;
                background-color: #f9f9f9;
            }
            .score-item {
                margin-bottom: 10px;
                padding: 5px;
                border-bottom: 1px solid #eee;
            }
            .total-score {
                font-size: 1.2em;
                font-weight: bold;
                color: #2c3e50;
                margin-top: 15px;
                padding: 10px;
                background-color: #ecf0f1;
                border-radius: 5px;
            }
            .container {
              text-align: center;
            }
            
            .rainbow-text {
              font-size: 3rem;
              font-weight: bold;
              animation: rainbow 8s linear infinite;
              text-shadow: 3px 3px 5px rgba(0,0,0,0.3);
              letter-spacing: 0.1em;
            }
            
            .letter {
              display: inline-block;
              animation: bounce 2s infinite;
            }
            
            .letter:nth-child(1) { 
              color: #FF5252; 
              animation-delay: 0.1s;
            }
            .letter:nth-child(2) { 
              color: #FF9800; 
              animation-delay: 0.2s;
            }
            .letter:nth-child(3) { 
              color: #FFEB3B; 
              animation-delay: 0.3s;
            }
            .letter:nth-child(4) { 
              color: #4CAF50; 
              animation-delay: 0.4s;
            }
            .letter:nth-child(5) { 
              color: #2196F3; 
              animation-delay: 0.5s;
            }
            .letter:nth-child(6) { 
              color: #9C27B0; 
              animation-delay: 0.6s;
            }
            .letter:nth-child(7) { 
              color: #E91E63; 
              animation-delay: 0.7s;
            }
            .letter:nth-child(8) { 
              color: #00BCD4; 
              animation-delay: 0.8s;
            }
            .letter:nth-child(9) { 
              color: #FF4081; 
              animation-delay: 0.9s;
            }
              .letter:nth-child(10) { 
              color: #FF4081; 
              animation-delay: 1s;
            }
              .letter:nth-child(11) { 
              color: #FF4081; 
              animation-delay: 1.1s;
            }
              .letter:nth-child(12) { 
              color: #FF4081; 
              animation-delay: 1.2s;
            }
              .letter:nth-child(13) { 
              color: #FF4081; 
              animation-delay: 1.3s;
            }
              .letter:nth-child(14) { 
              color: #FF4081; 
              animation-delay: 1.4s;
            }
            
            @keyframes bounce {
              0%, 100% {
                  transform: translateY(0);
              }
              50% {
                  transform: translateY(-20px);
              }
            }
            
            @keyframes rainbow {
              0% {
                  filter: hue-rotate(0deg);
              }
              100% {
                  filter: hue-rotate(360deg);
              }
            }
            
            .sparkle {
              position: absolute;
              background-color: white;
              border-radius: 50%;
              opacity: 0;
              animation: sparkle 1.5s infinite;
            }
            
            @keyframes sparkle {
              0% {
                  opacity: 0;
                  transform: scale(0);
              }
              50% {
                  opacity: 1;
                  transform: scale(1);
              }
              100% {
                  opacity: 0;
                  transform: scale(0);
              }
            }
        </style>
    </head>
    <body>
        <div class="title">
            <h1>Your JECA 2024 Score</h1>
        </div>
        <div class="container">
            <div class="rainbow-text">
              <span class="letter">C</span>
              <span class="letter">O</span>
              <span class="letter">N</span>
              <span class="letter">G</span>
              <span class="letter">R</span>
              <span class="letter">A</span>
              <span class="letter">T</span>
              <span class="letter">U</span>
              <span class="letter">L</span>
              <span class="letter">A</span>
              <span class="letter">T</span>
              <span class="letter">I</span>
              <span class="letter">O</span>
              <span class="letter">N</span>
            </div>
        </div>';
}

// Display the results
function displayResults($score, $categoryScores) {
    echo '<div class="result-section">
        <h2>Score Results</h2>
        <div class="score-card">
            <div class="score-item">
                <strong>Category 1 Score:</strong> ' . number_format($categoryScores['category1'], 2) . ' out of 80
            </div>
            <div class="score-item">
                <strong>Category 2 Score:</strong> ' . number_format($categoryScores['category2'], 2) . ' out of 40
            </div>
            <div class="total-score">
                <strong>Total Score:</strong> ' . number_format($score, 2) . ' out of 120
            </div>
        </div>
    </div>
    </body>
    </html>';
}

// Start HTML output
displayForm();

// Calculate and display the total score if we have responses

if (!empty($nomineeResponses)) {
    $score = calculateTotalScore($answerKey, $nomineeResponses);
    $categoryScores = calculateCategoryScores($answerKey, $nomineeResponses);
    displayResults($score, $categoryScores);
} else {
    echo '<div class="result-section">
        <h2>No Responses Submitted</h2>
        <p>Please submit the exam form to calculate your score.</p>
    </div>';
}

?>

<script>
        function createSparkles() {
          const container = document.querySelector('.container');
          const containerRect = container.getBoundingClientRect();
          
          for (let i = 0; i < 20; i++) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            
            // Random size between 3px and 6px
            const size = Math.random() * 3 + 3;
            sparkle.style.width = `${size}px`;
            sparkle.style.height = `${size}px`;
            
            // Random position around the text
            const left = Math.random() * containerRect.width;
            const top = Math.random() * containerRect.height;
            sparkle.style.left = `${containerRect.left + left}px`;
            sparkle.style.top = `${containerRect.top + top}px`;
            
            // Random animation delay
            sparkle.style.animationDelay = `${Math.random() * 2}s`;
            
            document.body.appendChild(sparkle);
            
            // Remove sparkle after one animation cycle
            setTimeout(() => {
              sparkle.remove();
            }, 1500);
          }
        }
        
        // Create new sparkles regularly
        setInterval(createSparkles, 300);
        
        // Initial sparkles
        createSparkles();
</script>