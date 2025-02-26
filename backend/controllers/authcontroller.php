<?php
require_once 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\config\db.php'; // Database connection

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // User registration method
    public function register($firstname, $lastname, $phone, $username, $email, $referral, $password) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if username or email already exists
        $checkQuery = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $this->pdo->prepare($checkQuery);
        $stmt->execute(['username' => $username, 'email' => $email]);

        if ($stmt->rowCount() > 0) {
            return "Username or email already exists.";
        }

        // Insert user into the database
        $insertQuery = "INSERT INTO users (fname, lname, pnum, username, email, referral, password) 
                        VALUES (:fname, :lname, :phone, :username, :email, :referral, :password)";
        $stmt = $this->pdo->prepare($insertQuery);

        try {
            $stmt->execute([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'phone' => $phone,
                'username' => $username,
                'email' => $email,
                'referral' => $referral,
                'password' => $hashedPassword
            ]);
            return "success";
        } catch (PDOException $e) {
            return "Error registering user: " . $e->getMessage();
        }
    }

    // User login method
    public function login($usernameOrEmail, $password) {
        $query = "SELECT * FROM users WHERE username = :usernameOrEmail OR email = :usernameOrEmail";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['usernameOrEmail' => $usernameOrEmail]);

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Login successful
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                return "success";
            } else {
                return "Incorrect password.";
            }
        } else {
            return "Username or email not found.";
        }
    }
}
?>
