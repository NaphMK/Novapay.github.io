<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "vtu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users from the database
$sql = "SELECT fname, lname, email, username, wallet_balance, pnum FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $users = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customers Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      /* Custom scrollbar for overflow areas */
      ::-webkit-scrollbar {
        width: 8px;
      }
      ::-webkit-scrollbar-thumb {
        background-color: #60a5fa;
        border-radius: 8px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background-color: #3b82f6;
      }
    </style>
  </head>
  <body class="bg-blue-900 text-white font-sans">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Page Header -->
      <h1 class="text-3xl font-bold mb-6 text-center">Customer Details</h1>

      <!-- Search Bar -->
      <div class="mb-4">
        <input
          type="text"
          id="search"
          placeholder="Search by username, first name, or phone number"
          class="w-full px-4 py-2 border rounded-lg bg-blue-700 text-white placeholder-gray-300 focus:ring focus:ring-blue-500"
        />
      </div>

      <!-- Responsive Table -->
      <div class="overflow-x-auto shadow-md rounded-lg bg-blue-800">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-blue-700">
              <th class="px-4 py-2 border-b text-sm font-medium">First Name</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Last Name</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Email</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Username</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Balance</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Phone Number</th>
              <th class="px-4 py-2 border-b text-sm font-medium">Referrer Balance</th>
              <th class="px-4 py-2 border-b text-sm font-medium">User Type</th>
            </tr>
          </thead>
          <tbody id="table-body">
            <?php if (count($users) > 0): ?>
              <?php foreach ($users as $user): ?>
                <tr class="odd:bg-blue-800 even:bg-blue-700 hover:bg-blue-600">
                  <td class="px-4 py-2 border-b"><?= htmlspecialchars($user['fname']) ?></td>
                  <td class="px-4 py-2 border-b"><?= htmlspecialchars($user['lname']) ?></td>
                  <td class="px-4 py-2 border-b"><?= htmlspecialchars($user['email']) ?></td>
                  <td class="px-4 py-2 border-b"><?= htmlspecialchars($user['username']) ?></td>
                  <td class="px-4 py-2 border-b">₦<?= number_format($user['wallet_balance'], 2) ?></td>
                  <td class="px-4 py-2 border-b"><?= htmlspecialchars($user['pnum']) ?></td>
                  <td class="px-4 py-2 border-b">₦</td>
                  <td class="px-4 py-2 border-b"></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-4">No customer data found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <script>
      // Search functionality
      document.getElementById("search").addEventListener("input", function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll("#table-body tr");

        rows.forEach((row) => {
          const cells = row.querySelectorAll("td");
          const text = Array.from(cells)
            .map((cell) => cell.textContent.toLowerCase())
            .join(" ");

          row.style.display = text.includes(query) ? "" : "none";
        });
      });
    </script>
  </body>
</html>

