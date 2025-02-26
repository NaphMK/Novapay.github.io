<?php
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
  
  $result1 = json_decode($result, true);
  $id_resp = isset($result1["id"]) ? $result1["id"] : null;

  $status_resp = isset($result1["Status"]) ?  $result1["Status"] : null;
echo "$result";
  // Check API response for success
  if ($status_resp === "successful") {
      // Deduct from user's wallet if the transaction was successful
      $stmt3 = $mysqli->prepare("UPDATE users SET wallet_balance = wallet_balance - ? 
                                 WHERE wallet_balance >= ? AND username = ?");
      $stmt3->bind_param("sss", $price, $price, $_SESSION['username']);
      $stmt3->execute();

      if ($stmt3->affected_rows > 0) {
          echo "Transaction successful, wallet balance deducted.";
      } else {
          echo "Transaction unsuccessful, but insufficient balance to deduct.";
      }
      $stmt3->close();
  } else {
      // Handle transaction failure
      echo "Transaction failed: " ;//. ($result1["message"] ?? "Unknown error");
  }
} else {
  echo "Fill the required fields";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barewa TopUP | Buy </title>
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../build/css/tailwind.css" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
  </head>
  <body>
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark}">
      <!-- Loading screen -->
      <div
        x-ref="loading"
        class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker"
      >
        Loading.....
      </div>
      <div
        class="flex flex-col items-center justify-center min-h-screen p-4 space-y-4 antialiased text-gray-900 bg-gray-100 dark:bg-dark dark:text-light"
      >
        <!-- Brand -->
        <a
          href="../index.html"
          class="inline-block mb-6 text-3xl font-bold tracking-wider uppercase text-primary-dark dark:text-light"
        >
       Barewa TopUp
        </a>
        <main>
          <div class="w-200 max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker">
            <h1 class="text-xl font-semibold text-center">Buy Data</h1>
            <form action="#" class="space-y-6" method="POST">
             <!-- Mobile Network -->
          <div class="mb-2">
            <label for="network" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Mobile Network
            </label>
            <select
              id="network"
              name="network"
              class="block w-full px-4 py-3 border rounded-lg dark:bg-white dark:border-gray-600 focus:ring-primary focus:border-primary text-gray-700 dark:text-gray-200"
            >
              <option value="" disabled selected>Select Network</option>
              <option value="1">MTN</option>
              <option value="4">Airtel</option>
              <option value="3">Etisalat</option>
              <option value="2">Glo</option>
            </select>
          </div>
          <!-- Type -->
          <div class="mb-6">
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Type
            </label>
            <select
              id="type"
              
              class="block w-full px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 focus:ring-primary focus:border-primary text-gray-700 dark:text-gray-200"
            >
              <option value="" disabled selected>Select Type</option>
              <option value="sme">SME</option>
              <option value="cg">CG</option>
              <option value="direct">Direct</option>
              <option value="sme2">SME 2</option>
              <option value="gifting">Gifting</option>
            </select>
          </div>

          <!-- Plan -->
          <div class="mb-6">
            <label for="plan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Plan
            </label>
            <select
              id="plan"
              name="plan_id"
              class="block w-full px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 focus:ring-primary focus:border-primary text-gray-700 dark:text-gray-200"
            >
              <option value="" disabled selected>Select Plan</option>
              <option value="234">500MB</option>
            </select>
          </div>

          <!-- Amount -->
          <div class="mb-6">
            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Amount
            </label>
            <input
              type="text"
              id="amount"
              placeholder="0"
              class="block w-full px-4 py-3 border rounded-lg bg-gray-100 dark:bg-gray-800 dark:border-gray-600 text-gray-500 dark:text-gray-300 focus:outline-none"
              readonly
            />
          </div>

          <!-- Mobile Number -->
          <div class="mb-6">
            <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Mobile Number
            </label>
            <input
              type="tel"
              id="mobile"
              name="mobile_number"
              placeholder="Mobile Number"
              class="block w-full px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 focus:ring-primary focus:border-primary text-gray-700 dark:text-gray-200"
              required
            />
          </div>
           <!-- Purchase Button -->
           <button
           class="w-20 px-3 py-3 text-white bg-primary rounded-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darker"
         >
           Purchase
         </button>
            </form>
            <span> Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat eius, dicta quaerat vero laboriosam eos corporis rem blanditiis voluptates esse magni dignissimos ratione quis ipsum reprehenderit cumque repellendus maiores facilis?
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit est ducimus magnam consectetur autem sint adipisci nesciunt qui, id facere sed cum enim mollitia repudiandae facilis maxime. Itaque, sapiente nemo.
              </span>
          </div>
        </main>
      </div>
    

            <!-- Back to Top -->
            <button
            class="fixed p-4 text-white bg-primary rounded-full shadow-lg bottom-5 right-5 hover:bg-primary-dark focus:outline-none"
            onclick="window.scrollTo({ top: 0, behavior: 'smooth' });"
          >
            <svg
              class="w-6 h-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 15l7-7 7 7"
              />
            </svg>
          </button>
    
      <!-- Toggle dark mode button -->
      <div class="fixed bottom-5 left-5">
        <button
          aria-hidden="true"
          @click="toggleTheme"
          class="p-2 transition-colors duration-200 rounded-full shadow-md bg-primary hover:bg-primary-darker focus:outline-none focus:ring focus:ring-primary"
        >
          <svg
            x-show="isDark"
            class="w-8 h-8 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
            />
          </svg>
          <svg
            x-show="!isDark"
            class="w-8 h-8 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
            />
          </svg>
        </button>
      </div>
    </div>

    <script>
      const setup = () => {
        const getTheme = () => {
          if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
          }
          return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
        }

        const setTheme = (value) => {
          window.localStorage.setItem('dark', value)
        }

        const getColor = () => {
          if (window.localStorage.getItem('color')) {
            return window.localStorage.getItem('color')
          }
          return 'cyan'
        }

        const setColors = (color) => {
          const root = document.documentElement
          root.style.setProperty('--color-primary', `var(--color-${color})`)
          root.style.setProperty('--color-primary-50', `var(--color-${color}-50)`)
          root.style.setProperty('--color-primary-100', `var(--color-${color}-100)`)
          root.style.setProperty('--color-primary-light', `var(--color-${color}-light)`)
          root.style.setProperty('--color-primary-lighter', `var(--color-${color}-lighter)`)
          root.style.setProperty('--color-primary-dark', `var(--color-${color}-dark)`)
          root.style.setProperty('--color-primary-darker', `var(--color-${color}-darker)`)
          this.selectedColor = color
          window.localStorage.setItem('color', color)
        }

        return {
          loading: true,
          isDark: getTheme(),
          color: getColor(),
          toggleTheme() {
            this.isDark = !this.isDark
            setTheme(this.isDark)
          },
          setColors,
        }
      }
    </script>
  </body>
</html>
