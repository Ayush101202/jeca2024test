// Answer key data
const answerKey = {
    // Category 1 - Single correct answers (Q1-80)
    1: 'A',
    2: 'A',
    3: 'B',
    4: 'A',
    5: 'B',
    6: 'C',
    7: 'C',
    8: 'A',
    9: 'D',
    10: 'D',
    11: 'D',
    12: 'A',
    13: 'C',
    14: 'C',
    15: 'C',
    16: 'C',
    17: 'A',
    18: 'A',
    19: 'A',
    20: 'C',
    21: 'C',
    22: 'D',
    23: 'A',
    24: 'D',
    25: 'B',
    26: 'D',
    27: 'B',
    28: 'B',
    29: 'A',
    30: 'D',
    31: 'C',
    32: 'D',
    33: 'A',
    34: 'B',
    35: 'C',
    36: 'A',
    37: 'C',
    38: 'A',
    39: 'C',
    40: 'B',
    41: 'C',
    42: 'B',
    43: 'C',
    44: 'D',
    45: 'B',
    46: 'B',
    47: 'B',
    48: 'A',
    49: 'C',
    50: 'B',
    51: 'D',
    52: 'D',
    53: 'A',
    54: 'C',
    55: 'B',
    56: 'B',
    57: 'B',
    58: 'C',
    59: 'D',
    60: 'A',
    61: 'C',
    62: 'A',
    63: 'A',
    64: 'D',
    65: 'B',
    66: 'A',
    67: 'D',
    68: 'B',
    69: 'C',
    70: 'D',
    71: 'D',
    72: 'C',
    73: 'A',
    74: 'A',
    75: 'B',
    76: 'A',
    77: 'C',
    78: 'B',
    79: 'A',
    80: 'D',

    // Category 2 - Multiple correct answers possible (Q81-100)
    81: ['B', 'D'],
    82: ['D'],
    83: ['C'],
    84: ['A', 'D'],
    85: ['B'],
    86: ['B', 'D'],
    87: ['B', 'C', 'D'],
    88: ['C'],
    89: ['B'],
    90: ['C'],
    91: ['A'],
    92: ['D'],
    93: ['C'],
    94: ['B'],
    95: ['C'],
    96: ['A', 'B'],
    97: ['A', 'B', 'C', 'D'],
    98: ['C'],
    99: ['C'],
    100: ['B']
};

// Function to calculate total score
function calculateTotalScore(answerKey, nomineeResponses) {
    let totalScore = 0;
    
    // Category 1 (Q1-80): 1 mark each, -0.25 for incorrect
    for (let i = 1; i <= 80; i++) {
        if (nomineeResponses[i]) {
            if (nomineeResponses[i] === answerKey[i]) {
                totalScore += 1;  // Correct answer
            } else {
                totalScore -= 0.25;  // Incorrect answer (negative marking)
            }
        }
        // Unattempted questions get 0 marks (no addition/subtraction)
    }
    
    // Category 2 (Q81-100): 2 marks each with formula for partial credit
    for (let i = 81; i <= 100; i++) {
        if (nomineeResponses[i] && nomineeResponses[i].length > 0) {
            // Check if all selected options are correct (no incorrect options selected)
            let allCorrect = true;
            for (let j = 0; j < nomineeResponses[i].length; j++) {
                if (!answerKey[i].includes(nomineeResponses[i][j])) {
                    allCorrect = false;
                    break;
                }
            }
            
            if (allCorrect) {
                // Calculate partial credit: 2 × (correct marked ÷ total correct)
                const correctMarked = nomineeResponses[i].length;
                const totalCorrect = answerKey[i].length;
                const score = 2 * (correctMarked / totalCorrect);
                totalScore += score;
            }
            // If any incorrect option is marked, score is 0 (no addition)
        }
        // Unattempted questions get 0 marks (no addition)
    }
    
    return totalScore;
}

// Function to calculate category-wise scores
function calculateCategoryScores(answerKey, nomineeResponses) {
    let category1Score = 0;
    let category2Score = 0;
    
    // Category 1 (Q1-80)
    for (let i = 1; i <= 80; i++) {
        if (nomineeResponses[i]) {
            if (nomineeResponses[i] === answerKey[i]) {
                category1Score += 1;
            } else {
                category1Score -= 0.25;
            }
        }
    }
    
    // Category 2 (Q81-100)
    for (let i = 81; i <= 100; i++) {
        if (nomineeResponses[i] && nomineeResponses[i].length > 0) {
            let allCorrect = true;
            for (let j = 0; j < nomineeResponses[i].length; j++) {
                if (!answerKey[i].includes(nomineeResponses[i][j])) {
                    allCorrect = false;
                    break;
                }
            }
            
            if (allCorrect) {
                const correctMarked = nomineeResponses[i].length;
                const totalCorrect = answerKey[i].length;
                const score = 2 * (correctMarked / totalCorrect);
                category2Score += score;
            }
        }
    }
    
    return {
        category1: category1Score,
        category2: category2Score
    };
}

// Function to extract form data
function extractFormData() {
    const nomineeResponses = {};
    
    // Process Category 1 questions (Q1-80) - single answers
    for (let i = 1; i <= 80; i++) {
        const selected = document.querySelector(`input[name="q${i}"]:checked`);
        if (selected) {
            nomineeResponses[i] = selected.value;
        }
    }
    
    // Process Category 2 questions (Q81-100) - multiple answers
    for (let i = 81; i <= 100; i++) {
        const selected = document.querySelectorAll(`input[name="q${i}"]:checked`);
        if (selected.length > 0) {
            nomineeResponses[i] = Array.from(selected).map(input => input.value);
        }
    }
    
    return nomineeResponses;
}

// Function to display results
function displayResults(score, categoryScores) {
    // Check if result section exists, if not create it
    let resultSection = document.getElementById('result-section');
    if (!resultSection) {
        resultSection = document.createElement('div');
        resultSection.id = 'result-section';
        resultSection.className = 'instructions-section';
        document.querySelector('form').insertAdjacentElement('afterend', resultSection);
    }
    
    // Create result HTML
    resultSection.innerHTML = `
        <h2>Your JECA 2024 Score</h2>
        <div class="score-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin-top: 20px; background-color: #f9f9f9;">
            <div class="score-item" style="margin-bottom: 10px; padding: 5px; border-bottom: 1px solid #eee;">
                <strong>Category 1 Score:</strong> ${categoryScores.category1.toFixed(2)} out of 80
            </div>
            <div class="score-item" style="margin-bottom: 10px; padding: 5px; border-bottom: 1px solid #eee;">
                <strong>Category 2 Score:</strong> ${categoryScores.category2.toFixed(2)} out of 40
            </div>
            <div class="total-score" style="font-size: 1.2em; font-weight: bold; color: #2c3e50; margin-top: 15px; padding: 10px; background-color: #ecf0f1; border-radius: 5px;">
                <strong>Total Score:</strong> ${score.toFixed(2)} out of 120
            </div>
        </div>
    `;
    
    // Create a new congratulations container
    const congratsContainer = document.createElement('div');
    congratsContainer.className = 'container';
    congratsContainer.style.marginTop = '30px';
    congratsContainer.innerHTML = `
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
    `;
    
    resultSection.appendChild(congratsContainer);
    
    // Scroll to results
    resultSection.scrollIntoView({ behavior: 'smooth' });
}

// Function to handle form submission
function handleFormSubmit(event) {
    event.preventDefault();
    
    const nomineeResponses = extractFormData();
    
    if (Object.keys(nomineeResponses).length > 0) {
        const score = calculateTotalScore(answerKey, nomineeResponses);
        const categoryScores = calculateCategoryScores(answerKey, nomineeResponses);
        displayResults(score, categoryScores);
        
        // Scroll to results
        document.getElementById('result-section').scrollIntoView({ behavior: 'smooth' });
    } else {
        alert('Please answer at least one question before submitting.');
    }
}

// Create sparkles for animation
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

// Start sparkles at regular intervals
function startSparkleAnimation() {
    setInterval(createSparkles, 300);
    createSparkles(); // Initial sparkles
}

// Initialize the calculator when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up form submission handler
    const form = document.getElementById('exam-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
});