<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require_once 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\config\db.php'; // Include database connection
require_once 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\controllers\authcontroller.php'; // Include AuthController class
include 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\api\monnify.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Instantiate AuthController
    $auth = new AuthController($pdo);

    // Retrieve form data
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $pnum = trim($_POST['pnum']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $referral = trim($_POST['referral']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confpass']);
    $accountNumber = generateAccountNumber($username, $email);
    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo 
        "<script>  
          Swal.fire({
  title: 'Error',
  text: 'Passwords do not match! ',
  icon: 'error',
  confirmButtonText: 'OK'
})
        
        </script>";
        exit;
    }

    // Register the user
    $result = $auth->register($fname, $lname, $pnum, $username, $email, $referral, $password);

    if ($result === "success") {
      echo  "
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
          Swal.fire({
                  title: 'Error',
                  text: 'Username or Email Already Exist',
                  icon: 'error',
                  confirmButtonText: 'OK'
              });
      
      </script>";
    
    

        // Redirect to login page upon success
        
    } else {
        // Display error message
        echo "<script>
        Swal.fire({
  title: 'Error',
  text: 'Unable to Generate Account Number',
  icon: 'error',
  confirmButtonText: 'OK'
});
</script>";
    }




    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
      echo "
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              Swal.fire({
                  title: 'Error',
                  text: 'Username or Email Already Exist',
                  icon: 'error',
                  confirmButtonText: 'OK'
              });
          });
      </script>";
     
     
  
    }
   
;
    // Generate Wema Bank virtual account number for the user
    $accountNumber = generateAccountNumber($username, $email);

    if (!$accountNumber) {
        echo "<script>alert('Failed to generate account number. Please try again later.');</script>";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into the database with the account number
    $insertQuery = "
        INSERT INTO users (fname, lname, pnum, username, email, referral, password, account_number) 
        VALUES (:fname, :lname, :pnum, :username, :email, :referral, :password, :account_number)";
    $stmt = $pdo->prepare($insertQuery);

    try {
        $stmt->execute([
            'fname' => $fname,
            'lname' => $lname,
            'pnum' => $pnum,
            'username' => $username,
            'email' => $email,
            'referral' => $referral,
            'password' => $hashedPassword,
            'account_number' => $accountNumber
        ]);

        // Redirect to login page upon success
        header("Location: login.php?success=1");
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error registering user: " . $e->getMessage() . "');</script>";
    }

   // $stmt->close();
   // $pdo->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barewa TopUP | Register</title>
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
          <div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker">
            <h1 class="text-xl font-semibold text-center">Register</h1>
            <form action=""      method="POST" class="space-y-6" onsubmit="return submitForm(this);">
              <input
                class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
                type="text"
                name="fname"
                placeholder="First Name"
                required
              />
              <input
              class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
              type="text"
              name="lname"
              placeholder="Last Name"
              required
            />
            <input
            class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
            type="text"
            name="pnum"
            placeholder="Phone Number"
            required
          />
             
              <input
                class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
                type="text"
                name="username"
                placeholder="Username"
                required
              />
              <input
                class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
                type="email"
                name="email"
                placeholder="Email address"
                required
              />
              <input
              class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
              type="text"
              name="referral"
              placeholder="Referrer Username"
             
            />
              <input
                class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
                type="password"
                name="password"
                placeholder="Password"
                id="password"
                required
              />
              <input
                class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
                type="password"
                name="confpass"
                placeholder="Confirm Password"
                id="confirmPassword"
                required
              />
              <small id="passwordMessage"></small>
              <div>
                <button
                  type="submit"
                  class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
                >
                  Register
                </button>
              </div>
            </form>

           
            <!-- Login link -->
            <div class="text-sm text-gray-600 dark:text-gray-400">
              Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login</a>
            </div>
          </div>
        </main>
      </div>
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
          selectedColor: 'cyan',
          toggleTheme() {
            this.isDark = !this.isDark
            setTheme(this.isDark)
          },
          setColors,
        }
      }
    </script>



<script>
document.addEventListener("DOMContentLoaded", () => {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const form = document.querySelector("form");


    form.addEventListener("submit", (e) => {
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            Swal.fire({
  title: 'Error',
  text: 'Passwords do not match',
  icon: 'error',
  confirmButtonText: 'OK'
})
        }
    });
});
</script>



</body>
</html>
