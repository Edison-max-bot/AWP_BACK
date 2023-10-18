<?php
require_once('DB_config.php');
require_once('BankAccount.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountName = $_POST['accountName'];
    $action = $_POST['action'];
    $amount = floatval($_POST['amount']);

    // Retrieve account details from the database based on the account name
    $sql = "SELECT * FROM Bank_Account WHERE Account_Name = '$accountName'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $account = new BankAccount($row['Account_Name'], $row['Balance']);

        switch ($action) {
            case 'inquire':
                echo "Current Balance: $" . $account->getBalance();
                break;

            case 'deposit':
                $newBalance = $account->deposit($amount);
                $updateSql = "UPDATE Bank_Account SET Balance = '$newBalance' WHERE Account_Name = '$accountName'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "Deposit successful. New Balance: $" . $newBalance;
                } else {
                    echo "Error updating record: " . $conn->error;
                }
                break;

            case 'withdraw':
                $newBalance = $account->withdraw($amount);
                $updateSql = "UPDATE Bank_Account SET Balance = '$newBalance' WHERE Account_Name = '$accountName'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "Withdrawal successful. New Balance: $" . $newBalance;
                } else {
                    echo "Error updating record: " . $conn->error;
                }
                break;

            default:
                echo "Invalid action.";
        }
    } else {
        echo "Account not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bank Account</title>
</head>
<body>
    <h1>Bank Account</h1>
    <form method="post" action="index.php">
        <label for="accountName">Account Name:</label>
        <input type="text" name="accountName" required><br><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" step="0.01" required><br><br>
        <label for="action">Select an action:</label>
        <select name="action" id="action" required>
            <option value="inquire">Inquire</option>
            <option value="deposit">Deposit</option>
            <option value="withdraw">Withdraw</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>