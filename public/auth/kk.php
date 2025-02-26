if (
  isset($_POST["network"]) &&
  isset($_POST["mobile_number"]) &&
  isset($_POST["plan_id"])
) {
  require_once 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\config\db.php';
  include 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\config\session.php';

  $mobile_number = $_POST["mobile_number"];
  $plan_id = $_POST["plan_id"];
  $network = $_POST["network"];

  // Request payload for API
  $request = ["Ported_number" => true, "network" => $network, "plan" => $plan_id, "mobile_number" => $mobile_number];

  // Fetch the price and amount for the selected plan
  $stmt = $mysqli->prepare("SELECT price, amount FROM data_plans WHERE plan_id = ?");
  $stmt->bind_param("s", $plan_id);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($price, $amount);
  $stmt->fetch();
  $stmt->close();

  // Check if the user has enough wallet balance before proceeding with the transaction
  $stmt_balance = $mysqli->prepare("SELECT wallet_balance FROM users WHERE username = ?");
  $stmt_balance->bind_param("s", $_SESSION['username']);
  $stmt_balance->execute();
  $stmt_balance->store_result();
  $stmt_balance->bind_result($wallet_balance);
  $stmt_balance->fetch();
  $stmt_balance->close();

  // Only proceed if the wallet balance is greater than or equal to the data amount
  if ($wallet_balance >= $price) {
      // Insert a record into the `data_trans` table to log the transaction
      $stmt2 = $mysqli->prepare("INSERT INTO data_trans (amount, price, network, number) 
                                 VALUES(?, ?, ?, ?)");
      $stmt2->bind_param("ssss", $amount, $price, $network, $mobile_number);
      $stmt2->execute();
      $transaction_id = $stmt2->insert_id; // Get the inserted transaction ID
      $stmt2->close();

      // API Request to the external service
      $url = 'https://husmodataapi.com/api/data/';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request)); // Encode the object to be sent
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $headers = [
          'Content-Type: application/json',
          'Authorization: Token 87e99b9247fbea2ce5a31e20015f3b585811fd8b'
      ];
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_URL, $url);

      $result = curl_exec($ch);
      
      echo "$result";
      $result1 = json_decode($result, true);
      $id_resp = isset($result1["id"]) ? $result1["id"] : null;
      $status_resp = isset($result1["Status"]) ? $result1["Status"] : null;

      // Check API response for success
      if ($status_resp === "successful") {
          // Deduct from user's wallet if the transaction was successful
          $stmt3 = $mysqli->prepare("UPDATE users SET wallet_balance = wallet_balance - ? 
                                     WHERE wallet_balance >= ? AND username = ?");
          $stmt3->bind_param("sss", $price, $price, $_SESSION['username']);
          $stmt3->execute();


          


          if ($stmt3->affected_rows > 0) {
              echo "Transaction successful, wallet balance deducted. ";
          } else {
              echo "Transaction unsuccessful, insufficient balance to deduct.";
          }
          $stmt3->close();
      } else {
          // Handle transaction failure
          echo "<script>
                 Swal.fire({
  title: 'oops',
  text: 'user not found',
  icon: 'error',
  confirmButtonText: 'OK'
})
    
                </script>";
              

      }
  } else {
      // Handle insufficient balance
      echo "Insufficient wallet balance for this transaction.";
  }
} else {
  echo "Fill the required fields";
}
