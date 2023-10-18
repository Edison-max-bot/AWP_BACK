<?php
class Bank_Account
{
  private $Account_ID;
  private $Account_Name;
  private $Balance;
  private $Account_Type;
  private $Password;

  public function __construct($accountName, $balance, $accountType, $password)
  {
    $this->Account_Name = $accountName;
    $this->Balance = $balance;
    $this->Account_Type = $accountType;
    $this->Password = $password;
  }

  public function getAccountID()
  {
    return $this->Account_ID;
  }

  public function getAccountName()
  {
    return $this->Account_Name;
  }

  public function getBalance()
  {
    return $this->Balance;
  }

  public function getAccountType()
  {
    return $this->Account_Type;
  }

  public function getPassword()
  {
    return $this->Password;
  }

  public function createAccount($conn)
  {
    // Implement the code to insert this account into the database
    $sql = "INSERT INTO bank_account (Account_Name, Balance, Account_Type, Password) VALUES (?, ?, ?, ?)";

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdss", $this->Account_Name, $this->Balance, $this->Account_Type, $this->Password);

    if ($stmt->execute()) {
      // Registration successful
      return true;
    } else {
      // Registration failed
      return false;
    }
    $stmt->close();
  }


  public function inquire()
  {
    // Implement the code to inquire the balance
    return $this->Balance; // Replace with actual logic
  }

  public function deposit($amount, $conn)
  {
    // Check if $amount is a positive value
    if ($amount > 0) {
      // Update the balance in the database
      $newBalance = $this->Balance + $amount;
      $sql = "UPDATE bank_account SET Balance = ? WHERE Account_ID = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("di", $newBalance, $this->Account_ID); // "di" for double and integer
      if ($stmt->execute()) {
        $this->Balance = $newBalance; // Update the balance in the object
        return $newBalance;
      } else {
        return "Failed to update balance in the database.";
      }
    } else {
      return "Invalid deposit amount.";
    }
  }



  public function withdraw($amount, $conn)
  {
    if ($amount <= $this->Balance) {
      $this->Balance -= $amount; // Update the balance property

      $sql = "UPDATE bank_account SET Balance = ? WHERE Account_ID = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("di", $this->Balance, $this->Account_ID);

      if ($stmt->execute()) {
        // The balance was updated in the database successfully
        return $this->Balance;
      } else {
        // Failed to update balance in the database
        return "Failed to update balance in the database: " . $conn->error;
      }
    } else {
      return "Insufficient funds.";
    }
  }



  public static function fetchDataFromDatabase($accountID, $conn)
  {
    // Use a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM bank_account WHERE Account_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $accountID); // "i" for integer

    if ($stmt->execute()) {
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row; // Return the fetched account data
      }
    }

    return null; // Return null if the account is not found
  }
}
?>