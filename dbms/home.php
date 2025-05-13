<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Dashboard | Expense Tracker</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4CAF50;
            --primary-dark: #45a049;
            --danger: #f44336;
            --danger-dark: #d32f2f;
            --dark: #333;
            --light: #f5f5f5;
            --white: #ffffff;
            --border: #e0e0e0;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), #2E7D32);
            color: var(--white);
            padding: 2rem 0;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .welcome-message {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .quick-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: var(--white);
        }
        
        .btn-danger:hover {
            background-color: var(--danger-dark);
            transform: translateY(-2px);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .card h2 {
            color: var(--primary);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin: 1rem 0;
        }
        
        .tip-box {
            background-color: #f9f9f9;
            border-left: 4px solid var(--primary);
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }
        
        .tip-box h3 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .tax-info {
            background-color: #e8f5e9;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .tax-info h3 {
            color: #2E7D32;
            margin-bottom: 0.5rem;
        }
        
        footer {
            background-color: var(--dark);
            color: var(--white);
            text-align: center;
            padding: 2rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 1rem 0;
        }
        
        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--white);
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Financial Dashboard</h1>
            <p>Manage your expenses and grow your wealth</p>
        </header>
        
        <div class="welcome-section">
            <div class="welcome-message">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
            <div class="quick-actions">
                <a href="report.php" class="btn btn-primary">View Expense Report</a>
                <a href="add_expense.php" class="btn btn-primary">Add New Expense</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="card">
                <h2>Budget Management</h2>
                <div class="tip-box">
                    <h3>Set Clear Budget Goals</h3>
                    <p>Start by categorizing your expenses into fixed (rent, utilities) and variable (entertainment, dining out) costs. Allocate specific amounts to each category based on your income.</p>
                </div>
                <div class="tip-box">
                    <h3>Use the 80/20 Rule</h3>
                    <p>Try spending 80% of your income and saving 20%. As you progress, aim to increase your savings percentage while maintaining your quality of life.</p>
                </div>
                <div class="tip-box">
                    <h3>Review Weekly</h3>
                    <p>Set aside time each week to review your spending against your budget. Small adjustments made regularly are more effective than major overhauls.</p>
                </div>
            </div>
            
            <div class="card">
                <h2>Current Expense Breakdown</h2>
                <div class="chart-container">
                    <canvas id="expenseChart"></canvas>
                </div>
                <p>Track where your money is going each month to identify saving opportunities.</p>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="card">
                <h2>Smart Saving Tips</h2>
                <div class="tip-box">
                    <h3>Automate Your Savings</h3>
                    <p>Set up automatic transfers to your savings account right after payday. Start with 10% of your income and gradually increase.</p>
                </div>
                <div class="tip-box">
                    <h3>High-Yield Savings</h3>
                    <p>Consider opening a high-yield savings account (2-3% APY) for your emergency fund to combat inflation.</p>
                </div>
                <div class="tip-box">
                    <h3>The 24-Hour Rule</h3>
                    <p>For non-essential purchases over $100, wait 24 hours before buying. You'll often find you didn't really need it.</p>
                </div>
            </div>
            
            <div class="card">
                <h2>Investment Strategies</h2>
                <div class="tip-box">
                    <h3>Start Early</h3>
                    <p>Even small amounts invested regularly can grow significantly over time due to compound interest.</p>
                </div>
                <div class="tip-box">
                    <h3>Diversify</h3>
                    <p>Spread your investments across different asset classes (stocks, bonds, real estate) to manage risk.</p>
                </div>
                <div class="tip-box">
                    <h3>Low-Cost Index Funds</h3>
                    <p>For beginners, consider low-cost index funds that track the market rather than trying to pick individual stocks.</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Tax Information (GST & Financial Rules)</h2>
            <div class="tax-info">
                <h3>Understanding GST</h3>
                <p>Goods and Services Tax (GST) is a comprehensive indirect tax levied on the supply of goods and services. The current rates are 5%, 12%, 18%, and 28% depending on the category of goods/services.</p>
                
                <h3>Tax-Saving Investments</h3>
                <p>Under Section 80C, you can claim deductions up to ₹1.5 lakh for investments in ELSS, PPF, NSC, life insurance premiums, and more.</p>
                
                <h3>Recent Changes</h3>
                <p>• Increased standard deduction for salaried individuals to ₹50,000<br>
                • New tax regime with lower rates but fewer deductions<br>
                • Higher TDS threshold for senior citizens</p>
            </div>
        </div>
        
        <footer>
            <p>© <?php echo date("Y"); ?> Expense Tracker. All rights reserved.</p>
            <div class="footer-links">
                <a href="https://www.incometaxindia.gov.in" target="_blank">Income Tax Department</a>
                <a href="https://www.gst.gov.in" target="_blank">GST Portal</a>
                <a href="https://www.rbi.org.in" target="_blank">RBI</a>
                <a href="https://www.sebi.gov.in" target="_blank">SEBI</a>
            </div>
        </footer>
    </div>

    <script>
        // Sample pie chart data - replace with your actual data from PHP
        const ctx = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Housing', 'Food', 'Transport', 'Entertainment', 'Utilities', 'Others'],
                datasets: [{
                    data: [30, 20, 15, 10, 15, 10],
                    backgroundColor: [
                        '#4CAF50',
                        '#8BC34A',
                        '#FFC107',
                        '#FF9800',
                        '#2196F3',
                        '#9C27B0'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>