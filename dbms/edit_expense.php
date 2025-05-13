<?php
include 'db_connection.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $expense_date = $_POST['expense_date'];

    $stmt = $pdo->prepare("UPDATE expenses SET category=?, amount=?, description=?, expense_date=? WHERE id=?");
    $stmt->execute([$category, $amount, $description, $expense_date, $id]);

    header("Location: report.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ?");
$stmt->execute([$id]);
$expense = $stmt->fetch();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1, h2 {
            margin: 0;
        }
        .card {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .nav-links {
            margin-top: 20px;
            text-align: center;
        }
        .nav-links a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Expense Tracker</h1>
        </header>
        
        <div class="card">
            <h2>Edit Expense</h2>
            <form method="post">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" value="<?= $expense['category'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" value="<?= $expense['amount'] ?>" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" value="<?= $expense['description'] ?>">
                </div>
                
                <div class="form-group">
                    <label for="expense_date">Date:</label>
                    <input type="date" id="expense_date" name="expense_date" value="<?= $expense['expense_date'] ?>" required>
                </div>
                
                <input type="submit" value="Update Expense">
            </form>
        </div>
        
        <div class="nav-links">
            <a href="report.php">Back to Report</a>
        </div>
    </div>
</body>
</html>
