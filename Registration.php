<?php
require_once('DB_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Account_Name = $_POST['Account_Name'];
    $Balance = floatval($_POST['Balance']);
    $Account_Type = $_POST['Account_Type'];
    $Password = $_POST['Password'];

    if ($Balance < 0) {
        echo "Balance cannot be negative.";
    } else {
        $sql = "INSERT INTO Bank_Account (AccountName, Balance, Account_Type, Password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdss", $Account_Name, $Balance, $Account_Type, $Password);

        if ($stmt->execute()) {
            echo "Account registered successfully!";
        } else {
            echo "Error registering the account: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bank Account Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bank Account Registration</h1>
    <form class="form" method="post" action="Registration.php">
        <label for="Account_Name">AccountName:</label>
        <input type="text" name="Account_Name" required><br></br>

        <label for="Balance">Balance:</label>
        <input type="number" name="Balance" step="0.01" required><br></br>

        <label for="Account_Type">Account Type:</label>
        <input type="text" name="Account_Type" required><br></br>

        <label for="Password">Password:</label>
        <input type="password" name="Password" required><br></br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>